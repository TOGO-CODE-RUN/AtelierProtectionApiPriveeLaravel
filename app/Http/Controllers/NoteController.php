<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
namespace App\Http\Controllers;

use App\Models\Note;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class NoteController extends Controller
{
    // Afficher toutes les notes de l'utilisateur authentifié
    public function index()
    {
        try 
        {
            // Récupère uniquement les notes de l'utilisateur connecté
            $notes = Auth::user()->notes;
            
            return response()->json(
                [
                    "success" => false,
                    "message" => "Notes récupérées avec succès.",
                    "notes" => $notes,
                ], 201
            );
        } 
        catch (Exception $e) 
        {
            return response()->json(
                [
                    'user_id' => Auth::id(), 
                    'message' => 'Erreur lors de la récupération des notes.',
                    'error' => $e->getMessage(),
                ], 500);
        }
    }

    // Afficher toutes les notes
    public function list()
    {
        try {
            $notes = Note::all();
            
            return response()->json(
                [
                    "success" => true,
                    "message" => "Notes récupérées avec succès.",
                    "notes" => $notes,
                ], 201
            );
        } catch (Exception $e) {
            return response()->json(
                [
                    'user_id' => Auth::id(), 
                    'message' => 'Erreur lors de la récupération des notes.',
                    'error' => $e->getMessage(),
                ], 500,
            );
        }
    }

    // Créer une nouvelle note
    public function store(Request $request)
    {
        try {
            $request->validate([
                'title' => 'required|string|max:255',
                'content' => 'required|string',
            ]);

            $note = Note::create([
                'title' => $request->title,
                'content' => $request->content,
                'user_id' => Auth::id(), // Associe la note à l'utilisateur connecté
            ]);

            return response()->json($note, 201);
        } catch (Exception $e) {
            return response()->json(
                [
                    'user_id' => Auth::id(), 
                    'message' => 'Erreur lors de la création de la note.',
                    'error' => $e->getMessage(),
                ], 500);
        }
    }

    // Afficher une note spécifique
    public function show($id)
    {
        try {
            // Vérifie que la note appartient à l'utilisateur connecté
            $note = Note::where('id', $id)
                        ->where('user_id', Auth::user()->id) 
                        ->select('title','content')
                        ->firstOrFail();

            return response()->json([
                'success'=> true,
                'message' => 'Note récupérée avec succès.',
                'note' => $note,
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Note introuvable.'], 404);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Erreur lors de la récupération de la note.',
                // 'error' => $e->getMessage(), 
            ], 500);
        }
    }

    // Mettre à jour une note
    public function update(Request $request, $id)
    {
        try 
        {
            // Vérifie que la note appartient à l'utilisateur connecté
            $note = Note::where('id', $id)
                        ->where('user_id', Auth::id())
                        ->firstOrFail();

            $request->validate([
                'title' => 'string|max:255',
                'content' => 'string',
            ]);

            $note->update($request->only('title', 'content'));

            return response()->json($note);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Note introuvable.'], 404);
        } catch (Exception $e) {
            return response()->json(['message' => 'Erreur lors de la mise à jour de la note.'], 500);
        }
    }

    // Supprimer une note
    public function destroy($id)
    {
        try {
            $note = Note::where('id', $id)
                        ->where('user_id', Auth::id()) // Vérifie que la note appartient à l'utilisateur connecté
                        ->firstOrFail();

            $note->delete();

            return response()->json(['message' => 'Note supprimée avec succès.']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Note introuvable.'], 404);
        } catch (Exception $e) {
            return response()->json(['message' => 'Erreur lors de la suppression de la note.'], 500);
        }
    }
}
