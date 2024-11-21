@extends('layouts.app')
@section('content')
<style>
#backFromUserData{
    display: flex;
    justify-content: center;
}
ion-icon{
    margin-bottom: -2px;
}

#backFromUserData a{
    text-decoration: none;
    color: black;
    font-size: 20px;

}
</style>
<div class="container col-2">
    <div id="backFromUserData">
        <a href="http://127.0.0.1:8000/home"><ion-icon name="arrow-back-circle-outline"></ion-icon>Back</a>
    </div>
    <div class="card">
        <div class="card-header">
            <strong>Info about user:</strong>
        </div>
        <div class="card-body">
            <h5 class="card-title"></h5>
            <p class="card-text">Id utente: {{ $userActive->id }}</p>
            <p class="card-text">Username: {{ $userActive->name }}</p>
            <p class="card-text">Email: {{ $userActive->email }}</p>
            @if (auth()->check() && auth()->user()->id === $userActive->id)
                <p class="card-text">Logo: <img src="{{ asset('storage/' . $userActive->logo) }}" alt="" style="width: 36px; height:36px; border-radius:50%; border:3px solid rgb(20, 175, 20);"></p>
            @else
            <p class="card-text">Logo: <img src="{{ asset('storage/' . $userActive->logo) }}" alt="" style="width: 36px; height:36px; border-radius:50%; border:2px solid grey;"></p>
            @endif

            <p>Followers: {{ $count }}</p>
            <p>Like received: {{ $countLike }}</p>
        
        </div>
    </div>
</div>

@endsection