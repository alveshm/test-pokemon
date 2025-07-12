<?php

namespace App\Http\Controllers;

use App\Models\Pokemon;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PokemonController extends Controller
{
    public function list(Request $request)
    {
        $nameFilter = $request->query('name');
        $typeFilter = $request->query('type');
        $page = $request->query('page');

        $listPokemon = Pokemon::where(function($query) use($nameFilter, $typeFilter){

            if ($nameFilter)
                $query->orWhere('name', 'LIKE', "%$nameFilter%");

            if ($typeFilter)
                $query->orWhere('type', 'LIKE', "%$typeFilter%");
            
        })->paginate(10, pageName: 'p', page: $page);
        
        return Response()->json($listPokemon);
    }

    public function detail(string $id)
    {
        $pokemon = Pokemon::where('id', $id)->first();

        if (!$pokemon)
            return Response()->json([]);

        $pokemon->height = $pokemon->height * 10;
        $pokemon->weight = $pokemon->weight / 10;

        return Response()->json($pokemon);
    }

    public function populate()
    {
        set_time_limit(150);
        try {
            
            $responseCount = Http::get('https://pokeapi.co/api/v2/pokemon');
            $dataCount = $responseCount->json();
            $limit = $dataCount['count'];
            $response = Http::get("https://pokeapi.co/api/v2/pokemon?offset=0&limit=$limit");

            $data = $response->json();

            $count = 0;
            foreach ($data['results'] as $pokemon) {

                if (!$pokemon['url']) continue;

                $pokeDetail = Http::get($pokemon['url']);

                if ($pokeDetail->status() != 200) continue;

                $type = '';
                if (!empty($pokeDetail['types']))
                    $type = $pokeDetail['types'][0]['type']['name'];

                Pokemon::insert([
                    'name' => $pokemon['name'],
                    'height' => $pokeDetail['height'],
                    'weight' => $pokeDetail['weight'],
                    'type' => $type
                ]);

                $count++;
            }

            return Response()->json([$count . ' registros criados no banco']);


        } catch (RequestException $e) {
            Response()->json([
                'error' => $e->getMessage()
            ]);
        }
    }


}
