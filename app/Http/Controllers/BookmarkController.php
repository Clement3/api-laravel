<?php

namespace App\Http\Controllers;

use App\Item;
use App\Bookmark;
use App\Http\Resources\BookmarkCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class BookmarkController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index()
    {
        $user = Auth::user();

        $bookmarks = $user->bookmarks()->with(
            'item', 
            'item.user', 
            'item.parentCategory', 
            'item.childCategory'
        )->latest()->paginate(10);
    
        return new BookmarkCollection($bookmarks);
    }

    public function create(Item $item)
    {
        $bookmark = new Bookmark(['item_id' => $item->id]);

        $user = Auth::user();

        $user->bookmarks()->save($bookmark);

        return response()->json([
            'version' => config('api.version'),
            'status' => true,
            'message' => __('bookmark.created', ['title' => $item->title])
        ], 201);        
    }

    public function delete(Item $item)
    {
        $user = Auth::user();

        $user->bookmarks()->where('item_id', $item->id)->delete();

        return response()->json([
            'version' => config('api.version'),
            'status' => true,
            'message' => __('bookmark.deleted', ['title' => $item->title])
        ], 200);        
    }
}
