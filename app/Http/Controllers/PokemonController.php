<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


use PDO;

class PokemonController extends Controller
{
    public function index()
    {
        $pdo = DB::connection()->getPdo();
        $stmt = $pdo->prepare("SELECT * FROM pokemons");
        $stmt->execute();
        $pokemons = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return view('pokemons.components.table', compact('pokemons'));
        // return response()->json($pokemon);
    }

    public function pokemonsWithTypes()
    {
        $pdo = DB::connection()->getPdo();
        $stmt = $pdo->prepare("SELECT p.id, p.name, p.hp, p.attack, p.defense, p.speed, 
                                    CONCAT('[', 
                                        GROUP_CONCAT(
                                            JSON_OBJECT(
                                                'id', pt.id, 
                                                'type_id', t.id, 
                                                'type_name', t.name
                                            )
                                        ), 
                                    ']') AS types
                                FROM pokemons p
                                JOIN pokemon_types pt ON p.id = pt.pokemon_id
                                JOIN types t ON pt.type_id = t.id
                                GROUP BY p.id, p.name, p.hp, p.attack, p.defense, p.speed
    ");
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Convertir la cadena `types` en un array JSON válido
        foreach ($result as &$row) {
            $row['types'] = json_decode($row['types'], true);
        }

        return response()->json($result);
    }


    public function show($id)
    {
        $pdo = DB::connection()->getPdo();
        $stmt = $pdo->prepare("SELECT * FROM pokemons WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $pokemon = $stmt->fetch(PDO::FETCH_ASSOC);

        return response()->json($pokemon);
    }

    public function create()
    {

    }

    public function destroy($id)
    {
        $pdo = DB::connection()->getPdo();

        try {
            // 1️⃣ First delete records from pokemon_types
            $stmt1 = $pdo->prepare("DELETE FROM pokemon_types WHERE pokemon_id = :id");
            $stmt1->execute(['id' => $id]);

            // 2️⃣ Delete Pokemon
            $stmt2 = $pdo->prepare("DELETE FROM pokemons WHERE id = :id");
            $stmt2->execute(['id' => $id]);

            return response()->json(['mensaje' => 'Pokémon eliminado correctamente'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al eliminar el Pokémon', 'details' => $e->getMessage()], 400);
        }
    }
}
