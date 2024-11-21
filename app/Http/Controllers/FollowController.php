<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Follow;
use Illuminate\Support\Facades\Auth;

class FollowController extends Controller
{
    public function store(Request $request){
        // dd(request()->all());
        $request -> validate([
            "followed_user_id"=> "required|integer",
        ]);

        if(!Auth::check()){
            return response(["error"=>"Unauthorized"],401);
        }

        try{
            Follow::create([
            "follower_id" => Auth::id(),
            "followed_user_id"=> $request -> followed_user_id,
        ]);
            return redirect()->back()->with(['message'=> 'you are now following this user']);

        } catch(\Exception $e){

            return redirect()->back()->with(['error'=> $e->getMessage()],500);
        }
    }  
    
    public function destroy(Request $request, $followed){

        try{
            $follower = Auth::id();

            $follow = Follow::where('follower_id', $follower)
            ->where('followed_user_id', $followed)
            ->first();

            if($follow){
                $follow->delete();
                return response()->json(['message'=> 'user not following anymore']);
            } else {
                return response()->json(['error'=> 'user not found'],404);
            }
        }catch(\Exception $e){
            return response()->json(['error'=> $e->getMessage()],500);
        }
    }
}
