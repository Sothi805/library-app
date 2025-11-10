<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookBorrow;
use App\Models\BookCategory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BorrowReturnController extends Controller
{
    public function index()
    {
        $activeBorrows = BookBorrow::with(['book', 'member'])
            ->where('status', 'borrowed')
            ->latest('borrowed_date')
            ->get();

        $borrowHistory = BookBorrow::with(['book', 'member'])
            ->where('status', 'returned')
            ->latest('returned_date')
            ->get();

        return view('pages.borrow-return.index', compact('activeBorrows', 'borrowHistory'));
    }

    public function borrowSelect(Request $request)
    {
        $search = $request->search;
        $category = $request->category;
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $perPage = $request->get('per_page', 12);

        $books = Book::with('category')
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
            ->orderBy($sortBy, $sortOrder)
            ->paginate($perPage)
            ->withQueryString();

        $categories = BookCategory::all();

        return view('pages.borrow-return.borrow-select', compact('books', 'categories', 'search', 'category', 'sortBy', 'sortOrder', 'perPage'));
    }

    public function borrowForm(Book $book)
    {
        $members = \App\Models\Member::orderBy('member_code')->get();
        return view('pages.borrow-return.borrow-form', compact('book', 'members'));
    }

    public function borrow(Request $request, Book $book)
    {
        $request->validate([
            'member_id' => 'required|integer',
            'condition_before' => ['required', 'string', Rule::in(['As new', 'Good', 'Fair', 'Poor', 'Damaged'])],
            'borrow_days' => 'required|integer|min:1|max:90',
            'borrowed_date' => 'required|date',
        ]);

        $borrowedDate = \Carbon\Carbon::parse($request->borrowed_date);

        BookBorrow::create([
            'book_id' => $book->id,
            'member_id' => $request->member_id,
            'borrowed_date' => $borrowedDate,
            'due_date' => $borrowedDate->copy()->addDays((int) $request->borrow_days),
            'condition_before' => $request->condition_before,
            'status' => 'borrowed',
        ]);

        $book->decrement('available_copies');

        return redirect()->route('borrow-return.index')
            ->with('success', 'Book borrowed successfully!');
    }    public function returnForm(Book $book, BookBorrow $borrow)
    {
        return view('pages.borrow-return.return-form', compact('book', 'borrow'));
    }

    public function return(Request $request, Book $book)
    {
        $request->validate([
            'borrow_id' => 'required|integer|exists:book_borrows,id',
            'return_status' => 'required|in:returned,borrowed',
            'condition_after' => ['required', 'string', Rule::in(['As new', 'Good', 'Fair', 'Poor', 'Damaged', 'Lost'])],
            'returned_date' => 'required|date',
        ]);

        $borrow = BookBorrow::findOrFail($request->borrow_id);

        // If changing from returned to borrowed (reverting)
        if ($borrow->status === 'returned' && $request->return_status === 'borrowed') {
            $borrow->update([
                'returned_date' => null,
                'condition_after' => null,
                'status' => 'borrowed',
            ]);
            $book->decrement('available_copies');
            $message = 'Return reverted successfully! Book is now marked as borrowed.';
        }
        // If changing from borrowed to returned OR updating return details
        else {
            $borrow->update([
                'returned_date' => $request->returned_date,
                'condition_after' => $request->condition_after,
                'status' => $request->return_status,
            ]);

            // Only increment available copies if changing from borrowed to returned
            if ($borrow->wasChanged('status') && $request->return_status === 'returned') {
                $book->increment('available_copies');
            }

            $message = 'Book return updated successfully!';
        }

        return redirect()->route('borrow-return.index')
            ->with('success', $message);
    }
}
