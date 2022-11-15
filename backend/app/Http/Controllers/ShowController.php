<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Stones;
use App\Models\Minerals;
use App\Models\MineralTerritory;
use App\Models\Territories;
use Illuminate\Support\Facades\Storage;
class ShowController extends Controller
{

    function getStringBetween($str,$from,$to)
    {
        $sub = substr($str, strpos($str,$from)+strlen($from),strlen($str));
        return substr($sub,0,strpos($sub,$to));
    }

    public function allStones()
    {
        return response()->json(Stones::all()); 
    }

    public function allTerritories()
    {
        return response()->json(Territories::all()); 
    }


    public function allMinerals()
    {
        $minerals = Minerals::all();

        $response = [];

        foreach( $minerals as $mineral )
        {

            $photos = Storage::disk("local")->allFiles("public/photo/$mineral->id");
            if($photos != null)
            {
                $photo =  Storage::url($photos[0]);
                $photo = env('DOMEN_URL').($photo);
            }
           else{
            $photo = null;

           }
            $stone_name = Stones::where('id',$mineral->id_stone)->first()->name;
            $response[] = ([
                'id' => $mineral->id,
                'name'      =>$mineral->name,
                'weight'    =>$mineral->weight,
                'length'    =>$mineral->length,
                'width'	    =>$mineral->width,
                'height'    =>$mineral->height,
                'description'=>	$mineral->description,
                'stone_name' => $stone_name,
                'id_stone'	=>$mineral->id_stone,
                'photo' => $photo ,
            ]);
            
        }



        return $response; 
    }

    public function getMineral(Request $request)
    {
        $id = $request->input('id_mineral');

        $mineral  = Minerals::where('id',$id)->first();
        
        $stone_name = Stones::where('id',$mineral->id_stone)->first()->name;

        $response['mineral'] = ([
            'name'      =>$mineral->name,
            'weight'    =>$mineral->weight,
            'length'    =>$mineral->length,
            'width'	    =>$mineral->width,
            'height'    =>$mineral->height,
            'description'=>	$mineral->description,
            'stone_name' => $stone_name,
            
        ]);

        $m_ts = MineralTerritory::where('id_mineral', $mineral->id)->get();


        $photos = Storage::disk("local")->allFiles("public/photo/$mineral->id");

       
        foreach($photos as $photo)
        {
            $photo =  Storage::url($photo);
            $response['photos'][] = env('DOMEN_URL').($photo);
        }
        
        $territories = [];
        foreach($m_ts as $m_t)
        {
            $territory_info = Territories::where('id',$m_t->id_territory)->first();
            $response['territories'][]  = ($territory_info->name);
        }
        return  $response;
    }

    public function getMineralEdit(Request $request)
    {
        $mineral_id = $request->input('id_mineral');

        $mineral  = Minerals::where('id',$mineral_id)->first();

        $stone_id = Stones::where('id',$mineral->id_stone)->first()->id;

        $m_ts = MineralTerritory::where('id_mineral', $mineral_id)->get();




        $response['mineral'] = ([
            'id_mineral'=> $mineral_id,
            'name'      =>$mineral->name,
            'weight'    =>$mineral->weight,
            'length'    =>$mineral->length,
            'width'	    =>$mineral->width,
            'height'    =>$mineral->height,
            'description'=>	$mineral->description,
            'id_stone' => $stone_id,
            
        ]);
       

        $photos = Storage::disk("local")->allFiles("public/photo/$mineral->id");

       
        foreach($photos as $photo)
        {
            $photo =  Storage::url($photo);
            $response['photos'][] = env('DOMEN_URL').($photo);
        }
       
        foreach($m_ts as $m_t)
        {
            $territory_info = Territories::where('id',$m_t->id_territory)->first();
            $response['territories'][]  = ($territory_info->id);
        }

        return $response;
    }
}
