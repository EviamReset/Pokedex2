<?php

namespace App\Http\Controllers;

use App\Http\Resources\PokemonResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


use PDO;

use function PHPUnit\Framework\returnSelf;

class PokemonController extends Controller
{
    public function index()
    {
        $pdo = DB::connection()->getPdo();
        $stmt = $pdo->prepare("SELECT * FROM pokemons");
        $stmt->execute();
        $pokemons = $stmt->fetchAll(PDO::FETCH_OBJ);

        $stmt = $pdo->prepare("SELECT * FROM types");
        $stmt->execute();
        $types = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $pokemons;

        // return view('components.table', compact('pokemons', 'types'));
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
        $pokemons = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Convertir la cadena `types` en un array JSON válido
        foreach ($pokemons as &$row) {
            $row['types'] = json_decode($row['types'], true);
        }

        $pokemons = json_decode(json_encode($pokemons));

        $stmt = $pdo->prepare("SELECT * FROM types");
        $stmt->execute();
        $types = $stmt->fetchAll(PDO::FETCH_OBJ);

        // return $pokemons;

        return view('components.table', compact('pokemons', 'types'));
    }


    public function show($id)
    {
        $pdo = DB::connection()->getPdo();
        $stmt = $pdo->prepare("SELECT * FROM pokemons WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $pokemon = $stmt->fetch(PDO::FETCH_ASSOC);

        return response()->json($pokemon);
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $pdo = DB::connection()->getPdo();
        $stmt = $pdo->prepare("INSERT INTO pokemons (name, hp, attack, defense, speed) values (:name, :hp, :attack, :defense, :speed)");
        $stmt->execute([
            'name' => $request->input('name'),
            'hp' => $request->input('hp'),
            'attack' => $request->input('attack'),
            'defense' => $request->input('defense'),
            'speed' => $request->input('speed')
        ]);

        $pokemonId = $pdo->lastInsertId();

        $types = $request->input('types');
        
        foreach ($types as $type)
        {
            $stmt = $pdo->prepare("INSERT INTO pokemon_types (pokemon_id, type_id) VALUES (:pokemon_id, :type_id)");
            $stmt->execute([
            'pokemon_id' => $pokemonId,
            'type_id' => $type
            ]);
        }
    
        return redirect()->back()->with('success', 'Pokémon agregado correctamente');
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

            return redirect()->back()->with('success', 'Pokémon agregado correctamente');
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al eliminar el Pokémon', 'details' => $e->getMessage()], 400);
        }
    }

    public function update (Request $request, $id)
    {
        // dd($request->all());

        $pdo = DB::connection()->getPdo();
        $stmt = $pdo->prepare("UPDATE pokemons 
                                SET name = :name, hp = :hp, attack = :attack, defense = :defense, speed = :speed
                                WHERE id = :id
                            ");
        $stmt->execute([
            'id' => $id,
            'name' => $request->input('name'),
            'hp' => $request->input('hp'),
            'attack' => $request->input('attack'),
            'defense' => $request->input('defense'),
            'speed' => $request->input('speed')
        ]);

        $stmt = $pdo->prepare("SELECT id, type_id FROM pokemon_types WHERE pokemon_id = :pokemon_id");
        $stmt->execute(['pokemon_id' => $id]);
        $currentTypes_assoc = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $currentTypeIds = array_column($currentTypes_assoc, 'type_id');

        // dd($currentTypeIds);

        $stmt = $pdo->prepare("SELECT id, type_id FROM pokemon_types WHERE pokemon_id = :pokemon_id");
        $stmt->execute(['pokemon_id' => $id]);
        $currentTypes = $stmt->fetchAll(PDO::FETCH_COLUMN);

        // dd($currentTypes);

        $newTypes = $request->input('types');

        $typesToAdd = array_diff($newTypes, $currentTypeIds);
        $typesToRemove = array_diff($currentTypeIds, $newTypes);

        // dd($typesToRemove);

        $current_length = count($currentTypeIds);
        $new_length = count($newTypes);

        // ARRAYS MISMA LONGITUD

        if ($current_length == $new_length)
        {
            $stmt = $pdo->prepare("DELETE FROM pokemon_types WHERE pokemon_id = :pokemon_id AND type_id = :type_id");
            foreach ($typesToRemove as $typeId) {
                $stmt->execute([
                    'pokemon_id' => $id,
                    'type_id' => $typeId
                ]);
            }

            $stmt = $pdo->prepare("INSERT INTO pokemon_types (pokemon_id, type_id) VALUES (:pokemon_id, :type_id)");
            foreach ($typesToAdd as $typeId) {
                $stmt->execute([
                    'pokemon_id' => $id,
                    'type_id' => $typeId
                ]);
            }
        }

        // ARRAYS CON DIFERENTE LONGITUD

        if ($current_length > $new_length)
        {
            $stmt = $pdo->prepare("DELETE FROM pokemon_types WHERE pokemon_id = :pokemon_id AND type_id = :type_id");
            foreach ($typesToRemove as $typeId) {
                $stmt->execute([
                    'pokemon_id' => $id,
                    'type_id' => $typeId
                ]);
            }
        }

        if ($current_length < $new_length)
        {
            $stmt = $pdo->prepare("INSERT INTO pokemon_types (pokemon_id, type_id) VALUES (:pokemon_id, :type_id)");
            foreach ($typesToAdd as $typeId) {
                $stmt->execute([
                    'pokemon_id' => $id,
                    'type_id' => $typeId
                ]);
            }
        }

        return redirect()->back()->with('success', 'Pokémon actualizado correctamente');
    }
}
