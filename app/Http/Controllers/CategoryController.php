<?php

namespace App\Http\Controllers;

use App\Category;
use App\Http\Resources\CategoryCollection;
use App\Http\Resources\Category as CategoryResource;
use Illuminate\Http\Request;

class CategoryController extends Controller
{   
    public function index()
    {
        $categories = Category::with('childrens.childrens')->parents()->orderBy('name', 'asc')->get();
    
        return new CategoryCollection($categories);
    }

    public function items(Category $category)
    {
        return new CategoryResource($category->load('items', 'items.user', 'items.category'));
    }
}
