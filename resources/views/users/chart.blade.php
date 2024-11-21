@push('scripts')
<script>
// CHART 1 ---------------------------------------------------------------------------
const data = {
    labels: @json($data->map(fn ($data) => $data->date)),
    datasets: [{
        label: 'Registered users in the last year',
        backgroundColor: 'blue',
        borderColor: 'green',
        data: @json($data->map(fn ($data) => $data->aggregate)),
    }]
};
const config = {
    type: 'bar',
    data: data
};
const myChart = new Chart(
    document.getElementById('myChart'),
    config
);

// CHART 2 ---------------------------------------------------------------------------
const data2 = {
    labels: @json($data2->map(fn ($data2) => $data2->date)),
    datasets: [{
        label: 'Registered tweets in the last 30 days',
        backgroundColor: 'blue',
        borderColor: 'green',
        data: @json($data2->map(fn ($data2) => $data2->aggregate)),
    }]
};
const config2 = {
    type: 'bar',
    data: data2
};
const myChart2 = new Chart(
    document.getElementById('myChart2'),
    config2
);
// CHART 3 ---------------------------------------------------------------------------
// Dati del grafico, incluse le etichette e i dati per i "likes"
const data3 = {
    labels: @json($formattedData3->map(fn ($data3) => $data3->date)),
    datasets: [{
        label: 'Registered likes in the last 30 days',
        backgroundColor: 'red',
        borderColor: 'green',
        data: @json($formattedData3->map(fn ($data3) => $data3->aggregate)),
    }]
};

// Estrai i nomi degli utenti e uniscili in un array
const userNamesArray = @json($formattedData3->map(fn ($item) => $item->user_names)).flat();
console.log(userNamesArray); // Log dell'array di nomi

// Funzione per contare i likes per ogni utente
function countLikes(userNames) {
    const count = {};
    userNames.forEach(user => {
        count[user] = (count[user] || 0) + 1; // Incrementa il conteggio per ciascun utente
    });
    return count;
}

// Crea un oggetto con il conteggio dei like
const likesCount = countLikes(userNamesArray);

// Funzione per formattare la stringa dei nomi degli utenti
function formatUserNames(likesCount) {
    return Object.entries(likesCount)
        .map(([user, count]) => `${user}: ${count}`) // Formatta ogni utente con il numero di like
        .join(', '); // Unisce i nomi in una stringa
}

// Configurazione del grafico
const config3 = {
    type: 'bar',
    data: data3,
    options: {
        plugins: {
            tooltip: {
                callbacks: {
                    title: function(tooltipItems) {
                        return tooltipItems[0].label; // Data del tooltip
                    },
                    label: function(tooltipItem) {
                        const likesCountValue = tooltipItem.raw; // Numero di like
                        const userNamesString = formatUserNames(likesCount); // Genera la stringa dei nomi
                        return `Likes: ${likesCountValue} by: ${userNamesString}`; // Messaggio personalizzato
                    }
                }
            }
        }
    }
};

// Creazione del grafico
const myChart3 = new Chart(
    document.getElementById('myChart3'),
    config3
);



</script>
@endpush

@extends('layouts.app')
@section('content')
<div class="container d-flex justify-content-center">
    <h3>Dasboard insight</h3>
</div>
<div class="container d-flex flex-row" style="margin-top: 80px">
    <div class="container col-4">
        <canvas id="myCkhart"></canvas>
    </div>
    <div class="container col-4">
        <canvas id="myChart2"></canvas>
    </div>
    <div class="container col-4">
        <canvas id="myChart3"></canvas>
    </div>
</div>


@endsection

