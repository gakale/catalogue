<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Film;
use Illuminate\Http\Request;

class  FilmController extends Controller
{
    public function index(){
        // on récupère tous les films en base de données et on les renvoie au format JSON

        $totalFilms = Film::all();
        return response()->json([
            'films' => $totalFilms,
            "status"=>200
        ]);
    }

    public function show($id){
        // on récupère un film en base de données et on le renvoie au format JSON
        $film = Film::find($id);
        return response()->json([
            'film' => $film,
            "status"=>200,
            "msg"=>"Film trouvé"
        ]);
    }


    public function store(request $request, Film $film){

        // on récupère les données du formulaire et on les stocke en base de données
        // on renvoie une réponse au format JSON
        // on vérifie que les données sont bien présentes dans le formulaire
        $url = $request->url;
        $title = $request->title;
        $description = $request->description;

        if(!empty($title) && !empty($description) && !empty($url)){
            $film->url = $url;
            $film->title = $title;
            $film->description = $description;
            $film->save();
            return response()->json([
                "status"=>200,
                "message"=>"Film ajouté avec succès"
            ]);
        }else{
            return response()->json([
                "status"=>400,
                "message"=>"Veuillez remplir tous les champs"
            ]);
        }

    }
}
