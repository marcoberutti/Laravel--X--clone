@extends('layouts.app')
@section('content')
<div class="container col-8 d-flex">
  <div class="container col-6">
    <h3>Change tweet for:</h3>
    <p>[{{$tweet->tweetTitle}}], posted on {{ $tweet->created_at->format('D m M Y, H:s') }}</p>
    
    <form action="{{ url('tweets/update/'.$tweet->id) }}" method="post" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="titleTweet" class="form-label">Title</label>
            <input type="text" class="form-control" id="titleTweet" value="{{$tweet->tweetTitle}}" name="title" oninput="updatePreview()">
        </div>
        <div class="mb-3">
            <label for="bodyTweet" class="form-label">Body of tweet</label>
            <input type="text" class="form-control" id="bodyTweet" name="body" value="{{ $tweet->tweetContent }}" oninput="updatePreview()">
        </div>
        <div class="mb-3">
            <label for="formFile" class="form-label">Insert file</label>
            <input class="form-control" type="file" id="formFile" name="image" accept="image/*">                
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
  </div>

    <div class="container col-4" style="width: 18rem; display:none;" id="cardContainer">
      <div class="card mt-4" style="width: 18rem; display:none;" id="cardPreview">
        <div class="card-header position-relative pb-0" style="height: 38px">
          <button type="button" class="btn-close position-absolute top-0 end-0 m-2" aria-label="Close" id="closeBtnCard"></button>
        </div>
        <div class="card-body">
            <h5 class="card-title" id="titlePreview">{{$tweet->tweetTitle}}</h5>
            <p class="card-text" id="bodyPreview">{{ $tweet->tweetContent }}</p>
            <img id="imagePreviewCard" class="card-img-top" style="display:none; max-width: 100%;" alt="Image Preview" />
        </div>
      </div>
    </div>
</div>

<script>
    function previewImage(event) {
        const imagePreview = document.getElementById('imagePreviewCard'); // Cambiato a 'imagePreviewCard'
        const previewCard = document.getElementById('cardPreview');
        const cardContainer = document.getElementById('cardContainer');

        const file = event.target.files[0];

        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                imagePreview.src = e.target.result; // Imposta l'immagine della card
                imagePreview.style.display = 'block'; // Mostra l'immagine
                previewCard.style.display = 'block'; // Mostra l'anteprima della card
                cardContainer.style.display = 'block'; // Mostra l'anteprima della card
            }
            reader.readAsDataURL(file);
        } else {
            imagePreview.style.display = 'none'; // Nascondi immagine se non c'è file
            previewCard.style.display = 'none'; // Nascondi la card se non c'è file
            cardContainer.style.display = 'none';
        }
    }

    function updatePreview() {
        const newTitle = document.getElementById('titleTweet').value;
        const newBody = document.getElementById('bodyTweet').value;

        document.getElementById('titlePreview').textContent = newTitle; // Aggiorna titolo
        document.getElementById('bodyPreview').textContent = newBody; // Aggiorna corpo
    }

    window.onload = function() {
        updatePreview(); // Imposta l'anteprima all'apertura della pagina
    };
    $("#closeBtnCard").on('click', function(){
      const imagePreview = document.getElementById('imagePreviewCard'); // Cambiato a 'imagePreviewCard'
      const previewCard = document.getElementById('cardPreview');
      const cardContainer = document.getElementById('cardContainer');

      imagePreview.style.display = 'none'; // Nascondi immagine se non c'è file
      previewCard.style.display = 'none'; // Nascondi la card se non c'è file
      cardContainer.style.display = 'none';
    })
</script>
@endsection
