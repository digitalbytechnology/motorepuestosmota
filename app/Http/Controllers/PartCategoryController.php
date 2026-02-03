<?php

namespace App\Http\Controllers;

use App\Models\PartCategory;
use Illuminate\Http\Request;

class PartCategoryController extends Controller
{
    public function index()
    {
        $categories = PartCategory::latest()->get();
        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => ['required','string','max:80','unique:part_categories,nombre'],
        ]);

        PartCategory::create($data);
        return redirect()->route('categories.index')->with('success','Categoría creada.');
    }

    public function edit(PartCategory $category)
    {
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, PartCategory $category)
    {
        $data = $request->validate([
            'nombre' => ['required','string','max:80','unique:part_categories,nombre,'.$category->id],
            'activo' => ['required','in:0,1'],
        ]);

        $category->update($data);
        return redirect()->route('categories.index')->with('success','Categoría actualizada.');
    }

    public function destroy(PartCategory $category)
    {
        $category->delete();
        return redirect()->route('categories.index')->with('success','Categoría eliminada.');
    }
}
