<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Favorite;
use Illuminate\Http\Request;

class PostController extends Controller
{
    // 1. LIST POSTS
    public function index()
    {
        return Post::orderBy('id', 'asc')->get();

    }

    // 2. CREATE POST
    public function store(Request $request)
    {
        return Post::create($request->all());
    }

    // 3. UPDATE POST
    public function update(Request $request, $id)
    {
        $post = Post::findOrFail($id);
        $post->update($request->all());
        return $post;
    }

    // 4. DELETE (SOFT DELETE)
    public function destroy($id)
    {
        Post::findOrFail($id)->delete();
        return response()->json(['message' => 'Moved to trash']);
    }

    // ⭐ FEATURE 2: SEARCH + FILTER
    public function search(Request $request)
    {
        $search = $request->search;

        $posts = \App\Models\Post::query()
            ->where('title', 'like', "%$search%")
            ->orWhere('content', 'like', "%$search%")
            ->get();

        return response()->json([
            'data' => $posts
        ]);
    }

    // ⭐ FEATURE 3: TRASH LIST
    public function trash()
    {
        return Post::onlyTrashed()->orderBy('id', 'asc')->get();
    }

    // RESTORE POST
    public function restore($id)
    {
        Post::withTrashed()->findOrFail($id)->restore();
        return response()->json(['message' => 'Restored successfully']);
    }

    // ⭐ FEATURE 4: FAVORITE TOGGLE
    public function favorite($id)
    {
        $fav = Favorite::where('post_id', $id)->first();

        if ($fav) {
            $fav->delete();
            return response()->json(['message' => 'Removed from favorites']);
        }

        Favorite::create(['post_id' => $id]);

        return response()->json(['message' => 'Added to favorites']);
    }

    // FAVORITE LIST
    public function favorites()
    {
        return Favorite::with('post')->get();
    }
}
