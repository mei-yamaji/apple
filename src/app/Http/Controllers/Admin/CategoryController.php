<?php

namespace App\Http\Controllers\Admin;
 
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
 
class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::paginate(20);
        return view('admin.categories.index', compact('categories'));
    }
 
    public function create()
    {
        return view('admin.categories.create');
    }
 
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
        ]);
 
        Category::create(['name' => $request->name]);
 
        return redirect()->route('admin.categories.index')->with('success', 'カテゴリを追加しました。');
    }
 
    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }
 
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
        ]);
 
        $category->update(['name' => $request->name]);
 
        return redirect()->route('admin.categories.index')->with('success', 'カテゴリを更新しました。');
    }
 
    public function destroy(Category $category)
    {
        $category->delete();
 
        return redirect()->route('admin.categories.index')->with('success', 'カテゴリを削除しました。');
    }
}