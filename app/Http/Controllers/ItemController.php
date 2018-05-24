<?php

namespace App\Http\Controllers;

use App\Item;
use App\Http\Resources\ItemCollection;
use App\Http\Resources\Item as ItemResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->except('index', 'show');
    }

    public function index()
    {
        $items = Item::with('user', 'childCategory', 'parentCategory')->paginate(10);

        return new ItemCollection($items);
    }

    public function show(Item $item)
    {
        return new ItemResource($item->load('user', 'parentCategory', 'childCategory'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'bail|required|max:60',
            'body' => 'required',
            'parent_category' => 'required|exists:categories,id',
            'child_category' => 'required|exists:categories,id'
        ]);

        $item = new Item([
            'slug' => str_slug($request->input('title')),
            'title' => $request->input('title'),
            'body' => $request->input('body'),
            'parent_category_id' => $request->input('parent_category'),
            'child_category_id' => $request->input('child_category')
        ]);

        $user = Auth::user();
        
        $user->items()->save($item);

        return response()->json([
            'version' => config('api.version'),
            'status' => true,
            'message' => 'Item create with success.'
        ], 201);
    }

    public function update(Item $item)
    {

    }

    public function delete(Item $item)
    {
        
    }
}
