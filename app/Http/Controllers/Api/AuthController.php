<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
class AuthController extends Controller
{
    public function register(Request $request)
    {

        // on vérifie que les données sont bien présentes dans le formulaire
        $validation = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users|max:155',
            'password' => 'required|string|min:4',
        ]);
        // on vérifie que les données sont bien présentes dans le formulaire
        if ($validation->fails()) {
            $errors = $validation->errors();
            return response()->json([
                "errors" => $errors,
                "status" => 401
            ]);
        }
        // on vérifie que les données sont bien présentes dans le formulaire
        if ($validation->passes()) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
            // on génère un token pour l'utilisateur et on le renvoie au format JSON
            $token = $user->createToken('auth_token')->plainTextToken;
            return response()->json([
                'token' => $token,
                // bearer est un type de token qui permet de sécuriser l'API
                'type' => 'Bearer',

            ]);

        }

    }

    public function login(Request $request)
    {

        // on vérifie que les données sont bien présentes dans le formulaire
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'msg' => 'Invalid login details'
            ], 401);
        }
        // on récupère l'utilisateur en base de données
        $user = User::where('email', $request->email)->firstOrFail();
        // on génère un token pour l'utilisateur et on le renvoie au format JSON
        $token = $user->createToken('auth_token')->plainTextToken;
        // on génère un token pour l'utilisateur et on le renvoie au format JSON
        return response()->json([
            'token' => $token,
            // bearer est un type de token qui permet de sécuriser l'API
            'type' => 'Bearer',
            'status' => 200,
        ])->cookie('jwt', $token); // 1 day
    }

    public function user(Request $request){

        return $request->user();
    }
}
