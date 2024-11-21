<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log;
use App\Models\like;
use App\Models\Tweets;
use App\Models\User;
use App\Models\Follow;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Collection;
use IcehouseVentures\LaravelChartjs\Facades\Chartjs;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $users = User::all();
    return view("users.create", ['users' => $users]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
        public function store(Request $request)
        {
            // dd($request->all());
            $this->validate($request, [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8',
                'logo' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:4096'
            ]);

            if ($request->hasFile('logo')) {
                $imagePath = $request->file('logo')->store('images', 'public'); // Salva nel filesystem
            }

            $user = User::create([
                'name'=> $request->name,
                'email'=> $request->email,
                'password'=> bcrypt($request->password),
                'logo'=> $imagePath

            ]);
            return redirect()->route('users.create')->with('success','user inserted');
        }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::find($id);
        return view('users.show', compact('user'));

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::find($id);
        return view('edit', compact('user'));


    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // dd($request->all());
        $user = User::find($id);
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->logo = $request->input('logo');

        if ($request->hasFile('logo')) {
            // Salva il file immagine nella cartella di archiviazione e ottieni il percorso
            $imagePath = $request->file('logo')->store('images', 'public');
            $user->logo = $imagePath; // Aggiorna il percorso immagine
        }

        $user->update();

        return redirect('/users/create')->with('success','user updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        
        $user = User::find($id);
        // dd($user);
        $user->delete();

        return redirect()->route('users.find')->with('success','user deleted');
    }
    public function find(Request $request){

        // dd($request->all());
        $request->validate([
            'id' => 'required|integer|exists:users,id',
        ]);
    
        // Ricerca dell'utente
        $user = User::find($request->id);
        $users = User::all();
        
        session()->flash('success', 'User found');


        // Passa sempre id e name alla vista, anche se l'utente non esiste
        return view('users.create', [
            'id' => $request->id,
            'name' => $user ? $user->name : null, // Se non esiste, name sarà null
            'password' => $user ? $user->password : null,
            'users' => $users,
        ]);
}


public function find2($id)
{
    $userActive = User::find($id);

    if (!$userActive) {
        return redirect()->back()->withErrors('User not found');
    }

    $count = Follow::where('followed_user_id', $id)->count();
    $countLike = Like::where('liked_user_id', $id)->count();


    return view('users.show', compact('userActive', 'count','countLike'));
}


public function showChart()
{
    $data = User::selectRaw("date_format(created_at, '%Y-%m-%d') as date, count(*) as aggregate")
        ->whereDate('created_at', '>=', now()->subDays(360))
        ->groupBy('date')
        ->get();

    $data2 = Tweets::selectRaw("date_format(created_at, '%Y-%m-%d') as date, count(*) as aggregate")
        ->whereDate('created_at', '>=', now()->subDays(30))
        ->where('user_id', auth()->id())
        ->groupBy('date')
        ->get();        


        $data3 = Like::with('likedUser', 'user')
        ->where('liked_user_id', auth()->id())
        ->get();

    // Inizializza array per date e nomi utenti
    $likesData = [];
    foreach ($data3 as $like) {
        // Ottieni il nome dell'utente che ha messo il like
        $likerName = $like->user->name; // L'utente che ha messo il like

        // Controlla se la data è già presente nell'array
        $dateKey = $like->created_at->format('Y-m-d'); // Formatta la data

        // Aggiungi dati all'array
        if (!isset($likesData[$dateKey])) {
            $likesData[$dateKey] = [
                'aggregate' => 0,
                'user_names' => [],
            ];
        }

        $likesData[$dateKey]['aggregate']++;
        $likesData[$dateKey]['user_names'][] = $likerName; // Aggiungi il nome dell'utente
    }

    // Rendi l'array più facile da utilizzare nel grafico
    $formattedData3 = collect($likesData)->map(function ($item, $date) {
        return (object)[
            'date' => $date,
            'aggregate' => $item['aggregate'],
            'user_names' => $item['user_names'],
        ];
    })->values();

    // dd($formattedData3);

    return view("users.chart", compact("data", "data2", "formattedData3"));
}






}
