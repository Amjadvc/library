<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Helpers\ResponseHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    //  Eloquent methods: all()  /  find() / save()  / delete()
    
    public function index()
    {
        $categories =  Category::all();  //// Get ALL categories from database
       return ResponseHelper::success(' جميع الأصناف',$categories);
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:50|unique:categories',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);


        $category = Category::create([
        'name' => $request->name
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = $category->id . '.' . $file->extension(); // or use name
            Storage::putFileAs('category-images', $file, $filename);
            $category->image = $filename;
            $category->save();
        }

        return ResponseHelper::success("تمت إضافة الصنف" , $category);
    }

    

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => "required|max:50|unique:categories,name,$id"
        ]);

        $category = Category::find($id);
        $category->name = $request->name;
        $category->save();
        return ResponseHelper::success("تم تعديل الصنف" , $category);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = Category::find($id);
        $category->delete();
        return ResponseHelper::success("تم حذف الصنف" , $category);
    }
}
