<?php

namespace App\Http\Controllers;

use App\Models\BookCategory;
use Illuminate\Http\Request;

class BookCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $categories = BookCategory::select('id', 'name')->orderBy('name')->get();
            return response()->json(['categories' => $categories]);
        }

        abort(404);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // return view('pages.category.create');
    }

    /**
     * Store a newly created resource in storage.
     */


    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:100'],
        ]);

        $category = BookCategory::firstOrCreate(['name' => $request->name]);

        return response()->json([
            'success' => true,
            'category' => $category,
        ]);
    }


    /**
     * Display the specified resource.
     */
    public function show(BookCategory $bookCategory)
    {
        // return view('category.show', compact('bookCategory'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BookCategory $bookCategory)
    {
        // return view('category.edit', compact('bookCategory'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BookCategory $bookCategory)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:book_categories,name,' . $bookCategory->id],
        ]);

        $bookCategory->update($validated);

        return response()->json(['success' => true, 'category' => $bookCategory]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BookCategory $bookCategory)
    {
        $bookCategory->delete();
        return response()->json(['success' => true]);
    }
}
