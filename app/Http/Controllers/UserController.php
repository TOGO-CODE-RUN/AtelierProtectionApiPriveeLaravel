<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRegisterRequest;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Créer un nouvel utilisateur.
     */
    public function store(UserRegisterRequest $request): JsonResponse
    {
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);

            return response()->json([
                'success' => true,
                'message' => "Votre compte a été crée avec succès !.",
                'data' => $user
            ], 201);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => "Erreur lors de la création de l'utilisateur."
            ], 500);
        }
    }

    /**
     * Afficher un utilisateur spécifique.
     */
    public function show(): JsonResponse
    {
        try {
            $user = User::find(Auth::user()->id);
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => "Utilisateur non trouvé."
                ], 404);
            }
            return response()->json([
                'success' => true,
                'data' => $user
            ], 201);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => "Erreur lors de la récupération de l'utilisateur."
            ], 500);
        }
    }

    /**
     * Mettre à jour le profil de utilisateur.
     */
    public function update(UserRegisterRequest $request, $id): JsonResponse
    {
        try {
            $user = User::find(Auth::user()->id);
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => "Utilisateur non trouvé."
                ], 404);
            }

            $user->name = $request->name;
            $user->email = $request->email;
            if ($request->password) {
                $user->password = Hash::make($request->password);
            }
            $user->save();

            return response()->json([
                'success' => true,
                'message' => "Votre profil a été mis à jour.",
                'data' => $user
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => "Erreur lors de la mise à jour de l'utilisateur."
            ], 500);
        }
    }

    /**
     * Supprimer un utilisateur.
     */
    public function destroy(): JsonResponse
    {
        try {
            $user = User::find(Auth::user()->id);
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => "Utilisateur non trouvé."
                ], 404);
            }

            $user->delete();

            return response()->json([
                'success' => true,
                'message' => "Utilisateur supprimé avec succès."
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => "Erreur lors de la suppression de l'utilisateur."
            ], 500);
        }
    }



    /**
     * Connexion de l'utilisateur et génération d'un token d'authentification.
     *
     * @param UserLoginRequest $request
     * @return JsonResponse
     */
    public function login(UserLoginRequest $request): JsonResponse
    {
        // Tentative de connexion avec les informations fournies
        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'success' => false,
                'message' => 'Identifiants incorrects. Veuillez réessayer.',
            ], 401);
        }

        // Récupérer l'utilisateur authentifié
        $user = Auth::user();
        
        // Générer un token d'authentification
        $authToken = $user->createToken('authToken')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Connexion réussie.',
            'token' => $authToken,
            'user' => $user,
        ]);
    }


}
