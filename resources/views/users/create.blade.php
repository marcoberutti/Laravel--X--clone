@extends('layouts.app')
@section('content')
<style>
    img{
        position: absolute;
        transition: transform 0.3s ease;
    }
    img:hover{
        transform: scale(1.2);        
        position: absolute;
        z-index: 1000;
    }
    .follow{
        border: 1px solid grey;
        text-align: center;
        border-radius: 15px;
        text-decoration: none;
        background-image: linear-gradient(to right, red,rgb(192, 14, 192));
        color: black;
        font-weight: bold;
        transition: transform 0.3s ease;
        height: 25px;
        margin-top: 5px;
        text-align: center;
    }
    .follow:hover{
        transform: scale(1.1);
    }
    td:not(.logoUser){
        vertical-align: middle;
    }
</style>
    <div class="container col-8" >
        <div class="container col-6 d-flex flex-column justify-content-center">
            <p>insert users, you will see them here down</p>
            <form action="{{route('users.store')}}" method="post" enctype="multipart/form-data">
                
                @csrf
                <label for="name">name</label>
                <input class="form-control" type="text" name="name"> 
                <label for="email">email</label>
                <input class="form-control" type="email" name="email">
                <label for="password">password</label>
                <input class="form-control" type="password" name="password">
                <label for="formFile" class="form-label">Insert user logo</label>
                <input class="form-control" type="file" id="userLogo" name="logo" accept="image/*">                    
                <br>
                <div class="container d-flex justify-content-center">
                <button class="btn btn-secondary" type="submit" style="margin: 0 50% 0 50%">click</button>
                </div>
            </form>
        </div>

        <hr>

        <div class="container"> 
            <table class="table table-light table-striped table-fixed">
                <thead class="table-primary">
                    <tr>
                        <th>Id</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Password</th>
                        <th>Actions</th>
                        <th>Logo</th>
                    </tr>
                </thead>
                
                <tbody>
                    @foreach ($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->password }}</td>
                        <td>
                            @if(auth()->check() && auth()->user()->id === $user->id)
                            <div class="d-flex row-cols-2">
                                <div>                                
                                    <a class="btn btn-success" href="{{ url('edit/'.$user->id) }}">EDIT</a>
                                </div>
                                <div>                                
                                    <a class="btn btn-danger" href="{{ url('delete/'.$user->id) }}">DELETE</a>
                                </div>
                            </div>
                            @else
                                <div class="d-flex row-cols-2" style="height: 35px">
                                    <form action="{{route('follow.store')}}" method="POST">
                                        @csrf
                                        <input hidden data-follower-id="{{ auth()->id() }}" id="follower-{{ auth()->id() }}-{{ $user->id }}"  name="follower_id" type="number" value="{{ auth()->id() }}">
                                        <input hidden data-followed-id="{{ $user->id }}" id="followed-{{ $user->id }}"  type="number" value="{{ $user->id }}" name="followed_user_id">
                                        <button data-user-id="{{ $user->id }}" type="submit" class="follow" id="followUnfollow-{{ $user->id }}">follow</button>
                                    </form>
                                </div>
                            @endif
                        </td>
                        @if (auth()->check() && auth()->user()->id === $user->id)
                        <td  class="logoUser"><a href="{{ route('users.find2', ['id' => auth()->user()->id]) }}"><img src="{{ asset('storage/' . $user->logo) }}" alt="" style="width: 36px; height:36px; border-radius:50%; border:3px solid rgb(20, 175, 20);"></a></td>
                        @else
                        <td  class="logoUser"><a href="{{ route('users.find2', ['id' => $user->id]) }}"><img src="{{ asset('storage/' . $user->logo) }}" alt="" style="margin-bottom:200px; width: 36px; height:36px; border-radius:50%; border:2px solid grey;"></a></td>
                        @endif
                    </tr>
                    @endforeach
                </tbody>
            
            </table>
        </div>  

       
            
    </div>
<script>
   $(document).ready(function() {
    // Funzione per impostare il testo e lo stile del pulsante in base allo stato di follow/unfollow
    function updateFollowButtons() {
        $(".follow").each(function() {
            var followedId = $(this).data('user-id');
            if (localStorage.getItem('followUnfollow-' + followedId) === 'true') {
                $(this).text('unfollow').css({
                    'background-image': 'linear-gradient(to right, blue,yellow)',
                });
            } else {
                $(this).text('follow').css({
                    'background-image': '',
                });
            }
        });
    }

    // Chiamata iniziale per aggiornare lo stato dei pulsanti al caricamento della pagina
    updateFollowButtons();

    // Gestione del click sui pulsanti di follow
    $(".follow").on('click', function(evt) {
        evt.preventDefault(); // Impedisce il comportamento predefinito del form

        var button = $(this); // Il pulsante attuale su cui è stato fatto clic
        var follower = button.siblings('input[name="follower_id"]').val(); // Ottieni l'ID del follower
        var followed = button.siblings('input[name="followed_user_id"]').val(); // Ottieni l'ID dell'utente seguito

        if (button.text() === 'follow') {
            // Richiesta AJAX per seguire l'utente
            $.ajax({
                url: "{{ route('follow.store') }}",
                type: "POST",
                data: {
                    follower_id: follower,
                    followed_user_id: followed,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    button.text('unfollow').css({
                        'background-image': 'linear-gradient(to right, blue,yellow)',
                    });
                    localStorage.setItem('followUnfollow-' + followed, 'true');
                },
                error: function(xhr) {
                    console.error(xhr.responseText); // Log dell'errore
                }
            });
        } else {
            // Richiesta AJAX per smettere di seguire l'utente
            $.ajax({
                url: "{{ route('follow.destroy', '') }}/" + followed,
                type: "POST",
                data: {
                    follower_id: follower,
                    followed_user_id: followed,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    button.text('follow').css({
                        'background-image': '',
                    });
                    localStorage.removeItem('followUnfollow-' + followed);
                },
                error: function(xhr) {
                    console.error(xhr.responseText); // Log dell'errore
                }
            });
        }
    });
});

</script>
</body>
</html>
@endsection