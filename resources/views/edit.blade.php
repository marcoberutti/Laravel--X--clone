@extends('layouts.app')

@section('content')

<div class="container col-6">
    

    <div class="card">
        <h5 class="card-header">Change user data</h5>
        <div class="card-body">
            <form action="{{url('update/'.$user->id)}}" method="post" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="name">Name</label>
                    <input class="form-control" value="{{$user->name}}" type="text" name="name">
                </div>
                <div class="mb-3">
                    <label for="email">Email</label>
                    <input class="form-control" value="{{$user->email}}" type="emal" name="email"> 
                </div>
                <div class="mb-3">
                    <label for="formFile" class="form-label">Insert user logo</label>
                    <input class="form-control" type="file" id="userLogoUpdate" name="logo" accept="image/*">                    
                </div>

                <button type="submit" class="btn btn-success">UPDATE</button>
            </form>
        </div>
    </div>
</div>

@endsection