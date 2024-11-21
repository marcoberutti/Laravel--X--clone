<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Like;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        dd(request()->all());
        $request->validate([
            'liked_user_id' => 'required|integer',
            'tweet_id' => 'required|integer',
        ]);

        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        try {
            $like = Like::create([
                "user_id" => Auth::id(),
                "liked_user_id" => $request->liked_user_id,
                "tweet_id" => $request->tweet_id,
            ]);

            return response()->json(['message' => 'Like added successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $tweetId)
    {
        // Log per il debug
        // \Log::info('Request data: ', $request->all());
        try {
            $likedUserId = $request->input('liked_user_id');
            $user_id = Auth::id(); // Usa Auth::id() per ottenere l'ID dell'utente

            $like = Like::where('liked_user_id', $likedUserId)
                        ->where('tweet_id', $tweetId)
                        ->where('user_id', $user_id)
                        ->first();

            if ($like) {
                $like->delete();
                return response()->json(['message' => 'Like deleted successfully.'], 200);
            } else {
                return response()->json(['message' => 'Like not found.'], 404);
            }
        } catch (\Exception $e) {
            \Log::error('Error deleting like: ' . $e->getMessage());
            return response()->json(['message' => 'Internal Server Error.'], 500);
        }
    }
}