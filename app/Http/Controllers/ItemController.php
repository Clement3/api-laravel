<?php

namespace App\Http\Controllers;

use App\Item;
use App\Rules\ChildCategory;
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

        $items->load('user', 'category');
        
        return new ItemCollection($items);
    }

    public function show(Item $item)
    {
        return new ItemResource($item->load('user', 'category'));
    }

    public function create(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'title' => 'required|max:60',
            'body' => 'required|max:1000',
            'price' => 'required|integer',
            'category' => 'required|exists:categories,id',
        ]);

        $item = new Item([
            'title' => $request->input('title'),
            'body' => $request->input('body'),
            'price' => $request->input('price'),
            'category' => $request->input('category'),
        ]);
        
        $user->items()->save($item);

        return response()->json([
            'version' => config('api.version'),
            'status' => true,
            'message' => 'Item create with success.'
        ], 201);
    }

    public function update(Request $request, Item $item)
    {
        $this->authorize('update', $item);

        if (is_null($item->verified_at) || now() >= Carbon::parse($item->edited_at)->addDays(7)) {
            
            $request->validate([
                'title' => 'max:60',
                'body' => 'max:1000',
                'price' => 'integer'
            ]);
    
            $item->title = $request->input('title');
            $item->body = $request->input('body');
            $item->price = $request->input('price');
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
        $this->authorize('delete', $item);

        $item->delete();

        return response()->json([
            'version' => config('api.version'),
            'status' => true,
            'message' => 'The item has been deleted.'
        ], 200);        
    }
}
