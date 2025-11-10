<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookBorrow;
use App\Models\BookCategory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;
        $category = $request->category;
        $status = $request->status;
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $perPage = $request->get('per_page', 10);

        $books = Book::with('category', 'user')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('book_id', 'like', '%' . $search . '%')
                        ->orWhere('title', 'like', '%' . $search . '%')
                        ->orWhere('author', 'like', '%' . $search . '%');
                });
            })
            ->when($category, function ($query) use ($category) {
                $query->where('category_id', $category);
            })
            ->when($status, function ($query) use ($status) {
                if ($status === 'available') {
                    $query->where('available_copies', '>', 0);
                } elseif ($status === 'out_of_stock') {
                    $query->where('available_copies', 0);
                }
            })
            ->orderBy($sortBy, $sortOrder)
            ->paginate($perPage)
            ->withQueryString();

        $categories = BookCategory::all();

        return view('pages.book.index', compact('books', 'categories', 'search', 'category', 'status', 'sortBy', 'sortOrder', 'perPage'));
    }

    public function create()
    {
        $categories = BookCategory::all();
        return view('pages.book.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'            => ['required', 'string', 'max:255'],
            'language'         => ['required', Rule::in(['khmer', 'english'])],
            'author'           => ['nullable', 'string', 'max:255'],
            'category_id'      => ['nullable', 'exists:book_categories,id'],
            'published_year'   => ['nullable', 'integer', 'digits:4'],
            'total_copies'     => ['required', 'integer', 'min:1'],
            'source'           => ['required', Rule::in(['donated', 'sponsored', 'purchased', 'other'])],
            'cover'            => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'description'      => ['nullable', 'string', 'max:1000']
        ]);

        $coverPath = null;

        if ($request->hasFile('cover')) {
            $extension = $request->file('cover')->getClientOriginalExtension();
            $safeTitle = preg_replace('/[^A-Za-z0-9_\-]/', '', str_replace(' ', '_', strtolower($validated['title'])));
            $filename = $safeTitle . '_' . time() . '.' . $extension;

            $coverPath = $request->file('cover')->storeAs(
                'covers',
                $filename,
                'public'
            );
        }

        // Auto-generate ID
        $prefix = $validated['language'] === 'khmer' ? 'K' : 'E';
        $lastBook = Book::where('book_id', 'like', $prefix . '%')->orderBy('book_id', 'desc')->first();
        $nextNumber = $lastBook ? ((int) substr($lastBook->book_id, 2)) + 1 : 1;
        $bookId = $prefix . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);

        Book::create([
            ...$validated,
            'book_id' => $bookId,
            'cover_path' => $coverPath,
            'available_copies' => $validated['total_copies'],
            'added_by' => Auth::id(),
            'snapshot_added_by' => trim(
                collect([
                    Auth::user()->first_name ?? '',
                    Auth::user()->middle_name ?? '',
                    Auth::user()->last_name ?? '',
                ])->filter()->join(' ')
            ),
        ]);

        return redirect()->route('books.index')
            ->with('success', 'Book added successfully!');
    }

    public function show(Request $request, Book $book)
    {
        $perPage = $request->get('per_page', 10);

        $borrowHistory = $book->borrows()
            ->with('member')
            ->latest()
            ->paginate($perPage, ['*'], 'history_page')
            ->withQueryString();

        $currentBorrowers = $book->borrows()
            ->where('status', 'borrowed')
            ->with('member')
            ->latest()
            ->paginate($perPage, ['*'], 'current_page')
            ->withQueryString();

        return view('pages.book.details', compact(
            'book',
            'borrowHistory',
            'currentBorrowers',
            'perPage'
        ));
    }

    public function edit(Book $book)
    {
        $categories = BookCategory::all();
        return view('pages.book.edit', compact('book', 'categories'));
    }

    public function update(Request $request, Book $book)
    {
        $validated = $request->validate([
            'title'            => ['required', 'string', 'max:255'],
            'language'         => ['required', Rule::in(['khmer', 'english'])],
            'author'           => ['nullable', 'string', 'max:255'],
            'category_id'      => ['nullable', 'exists:book_categories,id'],
            'published_year'   => ['nullable', 'integer', 'digits:4'],
            'total_copies'     => ['required', 'integer', 'min:1'],
            'available_copies' => ['required', 'integer', 'min:0', 'lte:total_copies'],
            'source'           => ['required', Rule::in(['donated', 'sponsored', 'purchased', 'other'])],
            'cover'            => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'delete_cover'     => ['nullable', 'boolean'],
        ]);

        // Handle cover deletion
        if ($request->boolean('delete_cover') && $book->cover_path) {
            // Delete the old file
            \Storage::disk('public')->delete($book->cover_path);
            $coverPath = null;
        }
        // Handle cover update
        else if ($request->hasFile('cover')) {
            // Delete the old file if exists
            if ($book->cover_path) {
                \Storage::disk('public')->delete($book->cover_path);
            }

            $extension = $request->file('cover')->getClientOriginalExtension();
            $safeTitle = preg_replace('/[^A-Za-z0-9_\-]/', '', str_replace(' ', '_', strtolower($validated['title'])));
            $filename = $safeTitle . '_' . time() . '.' . $extension;

            $coverPath = $request->file('cover')->storeAs(
                'covers',
                $filename,
                'public'
            );
        } else {
            $coverPath = $book->cover_path;
        }

        $book->update([
            ...$validated,
            'cover_path' => $coverPath,
            'updated_by' => Auth::id(),
            'snapshot_updated_by' => trim(
                collect([
                    Auth::user()->first_name ?? '',
                    Auth::user()->middle_name ?? '',
                    Auth::user()->last_name ?? '',
                ])->filter()->join(' ')
            ),
        ]);

        return redirect()->route('books.index')
            ->with('success', 'Book updated successfully!');
    }

    public function destroy(Book $book)
    {
        // Delete the cover image if exists
        if ($book->cover_path) {
            Storage::disk('public')->delete($book->cover_path);
        }

        $book->delete();

        return redirect()->route('books.index')
            ->with('success', 'Book deleted successfully!');
    }

        // --------------- NEW NON-MODAL FLOWS ------------------------

    public function borrowForm(Book $book)
    {
        return view('pages.book.partials.borrow', compact('book'));
    }

    public function borrow(Request $request, Book $book)
    {
        $request->validate([
            'member_id' => 'required|integer',
            'condition_before' => 'required|string|max:50',
        ]);

        BookBorrow::create([
            'book_id' => $book->id,
            'member_id' => $request->member_id,
            'borrowed_date' => now(),
            'due_date' => now()->addDays(14), // Fixed 14-day borrow period
            'condition_before' => $request->condition_before,
            'status' => 'borrowed',
        ]);

        $book->decrement('available_copies');

        return redirect()->route('books.show', $book)
            ->with('success', 'Book borrowed successfully!');
    }

    public function returnForm(Book $book, BookBorrow $borrow)
    {
        return view('pages.book.partials.return', compact('book', 'borrow'));
    }

    public function return(Request $request, Book $book)
    {
        $request->validate([
            'condition_after' => 'required|string|max:50',
        ]);

        $activeBorrow = $book->borrows()
            ->where('status', 'borrowed')
            ->latest()
            ->first();

        if (!$activeBorrow) {
            return back()->with('error', 'No active borrow found.');
        }

        $activeBorrow->update([
            'returned_date' => now(),
            'condition_after' => $request->condition_after,
            'status' => 'returned',
        ]);

        $book->increment('available_copies');

        return redirect()->route('books.show', $book)
            ->with('success', 'Book returned successfully!');
    }
}
