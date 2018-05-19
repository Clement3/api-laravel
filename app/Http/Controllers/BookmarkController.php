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
        $this->authorize('create', $item);

        $user = Auth::user();

        $bookmark = new Bookmark(['item_id' => $item->id]);

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

        $bookmark = Bookmark::where('item_id', $item->id)->where('user_id', $user->id)->delete();

        if ($bookmark) {
            return response()->json([
                'version' => config('api.version'),
                'status' => true,
                'message' => __('bookmark.deleted', ['title' => $item->title])
            ], 200);    
        }

        return response()->json([
            'version' => config('api.version'),
            'status' => false,
            'message' => __('bookmark.not_found')
        ], 404);            
    }
}
