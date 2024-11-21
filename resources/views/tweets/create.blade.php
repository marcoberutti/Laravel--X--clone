@extends('layouts.app')
@section('content')
<style>
#cardTweet .card-link {
    text-decoration: none;
    color: black;
}
#cardTweet .card-link:hover {
    color: blue;
}
#likeContainer{
    position: relative;
    width: 10px;
    margin-left: 290px;
    height: 20px
}
.fa{
    float: right;
    position: absolute;
}
@keyframes grow {
    0% {
        transform: scale(1) rotate(0deg);
        color: brown;
    }
    50% {
        transform: scale(2.5) rotate(-40deg);
        color: yellow;
    }
    100% {
        transform: scale(1) rotate(0deg);
        color: blue;
    }
}
@keyframes up {
    0% {
        transform: translateY(0) translateX(0);
        background-color: brown;
        opacity: 1;
    }
    10% {
        transform: translateY(-5px) translateX(2px); 
        opacity: 0.9;
    }
    20% {
        transform: translateY(-10px) translateX(-3px); 
        opacity: 0.8;
    }
    30% {
        transform: translateY(-15px) translateX(4px); 
        opacity: 0.7;
    }
    40% {
        transform: translateY(-20px) translateX(-2px); 
        opacity: 0.6;
    }
    50% {
        transform: translateY(-25px) translateX(3px); 
        opacity: 0.5;
        background-color: yellow;
    }
    60% {
        transform: translateY(-30px) translateX(-4px); 
        opacity: 0.4;
    }
    70% {
        transform: translateY(-35px) translateX(2px); 
        opacity: 0.3;
    }
    80% {
        transform: translateY(-40px) translateX(-3px); 
        opacity: 0.2;
    }
    90% {
        transform: translateY(-45px) translateX(1px); 
        opacity: 0.2;
    }
    100% {
        transform: translateY(-50px) translateX(0); 
        opacity: 0;
        background-color: blue;
    }
}
.animate-grow {
    animation: grow 0.8s ease-in-out;
}

</style>
<div class="container">
    <div class="row">
        <div class="container col-3" style="border-right: 1px solid grey;">
            <h3>Insert new tweet</h3>
            <form action="{{ route('tweets.store') }}" method="post" enctype="multipart/form-data">
                @csrf
                <input hidden name="user_name" type="text" value="{{ auth()->user()->name }}">
                <input id="user_id_liker" hidden name="user_id" type="number" value="{{ auth()->id() }}">
                <label for="title">Title</label>
                <input class="form-control" name="title"></input>
                <label for="body">Body</label>
                <input class="form-control" name="body"></input>
                <label for="formFile" class="form-label">Insert file</label>
                <input class="form-control" type="file" id="formFile" name="image">                
                <br>
                <button class="btn btn-primary" type="submit">Post tweet</button>
            </form>
        </div>
        <div class="container col-9 d-flex flex-column align-items-center">
            @foreach ($tweets as $tweet)
            <div id="cardTweet" class="card col-9" style="margin: 20px 0;">
                <div class="card-header">
                    @if (auth()->check() && auth()->user()->id === $tweet->user_id)
                    <img src="{{ asset('storage/' . $tweet->user->logo) }}" alt="" style="width: 36px; height: 36px; border-radius: 50%; border: 3px solid rgb(20, 175, 20);">
                    @else
                    <img src="{{ asset('storage/' . $tweet->user->logo) }}" alt="" style="width: 36px; height: 36px; border-radius: 50%; border: 2px solid grey;">
                    @endif
                </div>
                <div class="card-body">
                  <h5 class="card-title">{{ $tweet->tweetTitle }}</h5>
                  <p class="card-text">{{ $tweet->tweetContent }}</p>
                  @if ($tweet->image)
                  <img src="{{ asset('storage/' . $tweet->image) }}" alt="" style="max-width: 250px; max-height: 250px;">
                  @endif
                </div>
                
                <div class="card-footer d-flex justify-content-between align-items-center">
                    <div>
                        <a href="" class="card-link">Posted by: 
                            @if (auth()->check() && auth()->user()->id === $tweet->user_id)
                            me
                            @else
                            {{ $tweet->user_name }}
                            @endif
                        </a>
                        <a style="margin-left: 18px;">posted on: {{ $tweet->created_at->format('d/m/Y H:i') }}</a>
                        <a href="" class="card-link"><ion-icon name="share-social-outline" style="margin-left: 15px;"></ion-icon></a>  
                    </div>
                    @if (auth()->check() && auth()->user()->id === $tweet->user_id)
                    <div>
                        <a class="btn btn-success btn-sm" href="{{ url('tweets/edit/'.$tweet->id) }}">EDIT</a>
                        <a class="btn btn-danger btn-sm" href="{{ url('tweets/delete/'.$tweet->id) }}">DELETE</a>
                    </div>
                    @else
                    <div id="likeContainer" class="container">
                        <a class="like-btn" href="#">
                            <i data-liked-user-id="{{ $tweet->user_id }}" data-tweet-id="{{ $tweet->id }}" id="like-{{ $tweet->id }}" class="fa fa-thumbs-o-up" style="font-size: 20px; color: black"></i>
                        </a>   
                    </div> 
                    <div class="bubbles-{{ $tweet->id }}"></div>                
                    @endif
                </div>
              </div>
            @endforeach
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {

    // Inizializza i like per tutti i bottoni esistenti
    $(".fa").each(function() {
        var likedUserId = $(this).data('liked-user-id'); // Ottieni liked_user_id dall'elemento specifico
        var tweetId = $(this).data('tweet-id'); // Ottieni tweet_id dall'elemento specifico
        var liker = parseInt($("#user_id_liker").val());
        var likeKey = 'like_' + tweetId + '_' + liker;

        if (localStorage.getItem(likeKey) === 'true') {
            $(this).removeClass('fa-thumbs-o-up').addClass('fa-thumbs-up').css({"color": "blue"});
        }
    });

    $(".like-btn").on('click', function(e) {
        e.preventDefault();

        // Recupera i dati dall'elemento specifico che è stato cliccato
        var likedUserId = $(this).find(".fa").data('liked-user-id');
        var tweetId = $(this).find(".fa").data('tweet-id');
        var liker = parseInt($("#user_id_liker").val());
        var likeKey = 'like_' + tweetId + '_' + liker;   
         
        if($('#like-' + tweetId).css('color') === 'rgb(0, 0, 0)'){
            $.ajax({
            url: "{{ route('like.store') }}", // Assicurati di avere una rotta corretta
            type: "POST",
            data: {
                liked_user_id: likedUserId,
                tweet_id: tweetId,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                // Cambia l'icona e il colore
                $('#like-' + tweetId).removeClass('fa-thumbs-o-up').addClass('fa-thumbs-up').css({
                    "color": "blue",
                });
                
                $('#like-' + tweetId).addClass('animate-grow');

                const div = document.createElement('div')
                div.classList.add('bubbles-' + tweetId)
                console.log(div)
                $('.bubbles-' + tweetId).append(div)

                $('.bubbles-' + tweetId).css({
                    'margin-left':'-50px',
                    'border-radius':'50%',
                    'background-color': 'blue',
                    'height':'10px',
                    'width':'10px',
                    'animation':'up 5s ease-in-out'
                })
                setTimeout(() => {
                    $('#like-' + tweetId).removeClass('animate-grow');
                }, 1000);
                setTimeout(() => {
                    $('.bubbles-' + tweetId).css({
                        'border-radius':'none',
                        'background-color': 'transparent',
                        'height':'0px',
                        'width':'0px',
                        'animation':'none'
                    })
                }, 5000);

                localStorage.setItem(likeKey, 'true'); // Salva lo stato del like
            },
            error: function(xhr) {
                console.error(xhr.responseText);
            }
        });
        } else{
            $.ajax({
                url: "{{ route('like.destroy', '') }}/" + tweetId, // Add tweetId to the route
                    type: "POST",
                data:{
                    liked_user_id: likedUserId,
                    tweet_id: tweetId,
                    user_id: liker,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response){
                    $('#like-' + tweetId).removeClass('fa-thumbs-up').addClass('fa-thumbs-o-up').css({"color": "black"});
                    localStorage.setItem(likeKey, 'false'); // Salva lo stato del like
                }
            })
        }

    });
});


</script>
@endsection
