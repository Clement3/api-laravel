<?php

namespace App\Http\Controllers;

use Vinkla\Hashids\Facades\Hashids;
use App\Item;
use App\Http\Resources\ItemCollection;
use App\Http\Resources\Item as ItemResource;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->except('index', 'show');
    }

    public function index(Request $request)
    {
        $items = Item::search($request->q)->paginate(10);

        $items->load('user', 'childCategory', 'parentCategory');
        
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

    public function update(Request $request, Item $item)
    {
        if (is_null($item->verified_at) || now() >= Carbon::parse($item->edited_at)->addDays(7)) {
            
            // Validation Custom : child_category -> le parent est bien le parent de l'enfant
            $request->validate([
                'title' => 'bail|max:60',
                'body' => '',
                'parent_category' => 'exists:categories,id',
                'child_category' => 'exists:categories,id'
            ]);
    
            $item->title = $request->input('title');
            $item->body = $request->input('body');
            $item->parent_category_id = $request->input('parent_category');
            $item->child_category_id = $request->input('child_category');
            $item->edited_at = now();
            $item->verified_at = null;

            $item->save();
    
            return response()->json([
                'version' => config('api.version'),
                'status' => true,
                'message' => 'The item has been edited.'
            ], 200);
        }

        return response()->json([
            'version' => config('api.version'),
            'status' => false,
            'message' => 'You already edited your ad. You have to wait :time'
        ], 401);
    }

    public function delete(Item $item)
    {
        
    }
}
