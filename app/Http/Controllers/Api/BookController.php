<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\Book;
use App\Helpers\ResponseHelper;

use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use Illuminate\Support\Facades\Storage;


class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $books = Book::with(['category', 'authors'])->get();
        return ResponseHelper::success(' جميع الكتب', $books);
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBookRequest $request)
    {
        $book = Book::create($request->except('authors'));

        if ($request->hasFile('cover')){
            $file = $request->file('cover');
            $filename = "$request->ISBN." . $file->extension();
            Storage::putFileAs('book-images', $file ,$filename );
            $book->cover = $filename;
            $book->save();
        }
        
        // Attach authors to the book
        if ($request->has('authors')) {
            $book->authors()->attach($request->authors);
        }
        
        $book->load(['category', 'authors']);
        return ResponseHelper::success("تمت إضافة الكتاب", $book);

     
    }

 

    /**
     * Display the specified resource.
     */
    public function show(Book $book)
    {
        $book->load(['category', 'authors']);
        return ResponseHelper::success("تفاصيل الكتاب", $book);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBookRequest $request, Book $book)
    {
        // Update book fields except authors
        $book->update($request->except('authors', 'cover'));
        
        // Handle cover image update
        if ($request->hasFile('cover')) {
            // Delete old cover if exists
            if ($book->cover) {
                Storage::delete('book-images/' . $book->cover);
            }
            
            $file = $request->file('cover');
            $filename = "$request->ISBN." . $file->extension();
            Storage::putFileAs('book-images', $file, $filename);
            $book->cover = $filename;
            $book->save();
        }
        
        // Update authors using sync (replace old authors with new ones)
        if ($request->has('authors')) {
            $book->authors()->sync($request->authors);
        }
        
        $book->load(['category', 'authors']);
        return ResponseHelper::success("تمت تعديل الكتاب", $book);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
        // Delete the cover image if exists
        if ($book->cover) {
            Storage::delete('book-images/' . $book->cover);
        }
        
        // Detach authors before deleting the book
        $book->authors()->detach();
        
        $book->delete();
        return ResponseHelper::success("تمت حذف الكتاب", $book);
    }
}
