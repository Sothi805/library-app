<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookBorrow;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Count lost books
        $lostBooks = BookBorrow::where('status', 'returned')
            ->where('condition_after', 'Lost')
            ->count();

        // Total statistics (excluding lost books)
        $totalBooks = Book::count();
        $totalMembers = Member::count();
        $activeMembers = Member::where('status', 'active')->count();
        $inactiveMembers = Member::where('status', 'inactive')->count();

        // Book statistics (subtract lost books from totals)
        $totalCopies = Book::sum('total_copies') - $lostBooks;
        $availableCopies = Book::sum('available_copies');
        $borrowedCopies = $totalCopies - $availableCopies;

        // Borrow statistics
        $activeBorrows = BookBorrow::where('status', 'borrowed')->count();
        $overdueBorrows = BookBorrow::where('status', 'borrowed')
            ->where('due_date', '<', now())
            ->count();

        $returnedBooks = BookBorrow::where('status', 'returned')->count();

        // Top 10 most borrowed books
        $topBooks = Book::select('books.*', DB::raw('COUNT(book_borrows.id) as borrow_count'))
            ->leftJoin('book_borrows', 'books.id', '=', 'book_borrows.book_id')
            ->groupBy('books.id')
            ->orderBy('borrow_count', 'desc')
            ->limit(10)
            ->get();

        // Recent borrows (last 10)
        $recentBorrows = BookBorrow::with('book')
            ->latest('borrowed_date')
            ->limit(10)
            ->get();

        // Category statistics
        $booksByCategory = Book::select('book_categories.name', DB::raw('COUNT(books.id) as count'))
            ->leftJoin('book_categories', 'books.category_id', '=', 'book_categories.id')
            ->groupBy('book_categories.id', 'book_categories.name')
            ->orderBy('count', 'desc')
            ->get();

        return view('dashboard', compact(
            'totalBooks',
            'totalMembers',
            'activeMembers',
            'inactiveMembers',
            'totalCopies',
            'availableCopies',
            'borrowedCopies',
            'activeBorrows',
            'overdueBorrows',
            'returnedBooks',
            'lostBooks',
            'topBooks',
            'recentBorrows',
            'booksByCategory'
        ));
    }
}
