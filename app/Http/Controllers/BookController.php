<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookCategory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $books = Book::with('category', 'user')->latest()->get();
        return view('pages.book.index', compact('books'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = BookCategory::all();
        return view('pages.book.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
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

        // Handle cover upload
        $coverPath = null;
        if ($request->hasFile('cover')) {
            $extension = $request->file('cover')->getClientOriginalExtension();
            $safeTitle = preg_replace('/[^A-Za-z0-9_\-]/', '', str_replace(' ', '_', strtolower($validated['title'])));
            $filename = $safeTitle . '_' . time() . '.' . $extension;
            $coverPath = $request->file('cover')->storeAs('covers', $filename, 'public');
        }

        // Generate sequential Book ID
        $prefix = $validated['language'] === 'khmer' ? 'K' : 'E';
        $lastBook = Book::where('book_id', 'like', $prefix . '%')->orderBy('book_id', 'desc')->first();
        $nextNumber = $lastBook ? ((int) substr($lastBook->book_id, 2)) + 1 : 1;
        $bookId = $prefix . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);

        // Create new book entry
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

        return redirect()
            ->route('books.index')
            ->with('success', 'Book added successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $book)
    {
        $condition = 'As New';
        return view('pages.book.details', compact('book', 'condition'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Book $book)
    {
        $categories = BookCategory::all();
        return view('pages.book.edit', compact('book', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
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
        ]);

        $book->update([
            ...$validated,
            'updated_by' => Auth::id(),
            'snapshot_updated_by' => trim(
                collect([
                    Auth::user()->first_name ?? '',
                    Auth::user()->middle_name ?? '',
                    Auth::user()->last_name ?? '',
                ])->filter()->join(' ')
            ),
        ]);

        return redirect()
            ->route('books.index')
            ->with('success', 'Book updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
        $book->delete();

        return redirect()
            ->route('books.index')
            ->with('success', 'Book deleted successfully!');
    }
}
