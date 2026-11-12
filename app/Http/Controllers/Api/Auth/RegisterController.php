<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        // 1. Validation des données
        $validated = $request->validate([
            'nom' => ['required', 'string', 'max:255'],

            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email'
            ],

            'password' => [
                'required',
                'confirmed',
                Password::min(8)
            ],

            'company_name' => [
                'required',
                'string',
                'max:255'
            ],
        ]);

        // 2. Création de l'utilisateur et de son entreprise
        $result = DB::transaction(function () use ($validated) {

            $user = User::create([
                'nom' => $validated['nom'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);

            $company = Company::create([
                'nom' => $validated['company_name'],
                'slug' => Str::slug($validated['company_name'])
                    . '-' . Str::lower(Str::random(6)),
                'status' => 'active',
            ]);

            // 3. Association avec le rôle propriétaire
            $company->users()->attach($user->id, [
                'role' => 'owner',
                'status' => 'active',
                'joined_at' => now(),
            ]);

            return compact('user', 'company');
        });

        // 4. Génération du jeton d'accès
        $token = $result['user']->createToken(
            'smartbiz-api'
        )->plainTextToken;

        // 5. Réponse JSON
        return response()->json([
            'message' => 'Entreprise créée avec succès.',
            'user' => $result['user'],
            'company' => $result['company'],
            'token' => $token,
        ], 201);
    }
}