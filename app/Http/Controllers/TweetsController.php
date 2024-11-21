<?php

namespace App\Http\Controllers;

use App\Models\Tweets;
use Illuminate\Http\Request;

class TweetsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tweets = Tweets::all(); // Recupera tutti i tweet dal database
    return view("tweets.create", compact("tweets")); // Passa i tweet alla vista
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("tweets.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        
        $this ->validate($request, [
            "user_name"=> "required|string|max:255",
            "user_id"=> "required|numeric|max:255",
            "title"=> "required|string|max:255",
            "body"=> "required|string|max:5000",
            "image"=> "required|image|mimes:jpeg,png,jpg,gif,webp|max:4096",
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images', 'public'); // Salva nel filesystem
        }

        Tweets::create([
            "user_name"=> $request->user_name,
            "user_id"=> $request->user_id,
            "tweetTitle"=> $request -> title,
            "tweetContent"=> $request -> body,
            "image" => $imagePath, // Salva il percorso dell'immagine nel database
        ]);
        return redirect()->route("tweets.create")->with("success","tweets posted!");
    }

    /**
     * Display the specified resource.
     */
    public function show(Tweets $tweets)
    {
        $tweets -> Tweets::all();
        return view("tweets.create", compact("tweet"));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit( $id)
    {
        $tweet = Tweets::find($id);
        return view("/tweets/edit", compact("tweet")); //qui dovrebbe mandare alla pagina. route per questo: Route::get('tweets/edit/{id}', [TweetsController::class, 'edit']);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,  $id)
    {
        // dd($request->all());
        $tweet = Tweets::find($id);
        $tweet->tweetTitle = $request->input('title');
        $tweet->tweetContent = $request->input('body');

        if ($request->hasFile('image')) {
            // Salva il file immagine nella cartella di archiviazione e ottieni il percorso
            $imagePath = $request->file('image')->store('images', 'public');
            $tweet->image = $imagePath; // Aggiorna il percorso immagine
        }

        $tweet->update();

        return redirect('/tweets/create')->with('success','tweet updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $tweet =  Tweets::find($id);
        $tweet -> delete();

        return redirect('tweets/create')->with('success','tweet removed successfully');
        
    }
}
