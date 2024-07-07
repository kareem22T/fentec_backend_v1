<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Scooter;
use Exception;

class MapController extends Controller
{
    public function getAllScooters() {
        $scooters = Scooter::all();

        if ($scooters && $scooters->count() > 0) {
            return response()->json([
                "status" => true,
                "account_status" => true,
                "message" => "successfuly operation",
                "errors" => [],
                "data" => $scooters
            ]);
        } elseif ($scooters && $scooters->count() == 0) {
            return response()->json([
                "status" => false,
                "account_status" => true,
                "message" => "No scooters founded",
                "errors" => ['There is no any scooter'],
                "data" => []
            ], 200);
        } else {
            return response()->json([
                "status" => false,
                "account_status" => true,
                "message" => "Could not fetch scooters",
                "errors" => ['Server error could not fetch scooters'],
                "data" => []
            ], 500);
        }
    }
    public function getNearstScooter(Request $request) {
        $scooters = Scooter::all();

        if ($scooters->count() == 0)
            return  response()->json(

                array(

                    "status" => false,

                    "account_status" => true,

                    "message" => "",

                    "errors" => array("there is no avilable scooters now"),

                    "data" => array()
                )

            );

        $destinations = "";
        foreach ($scooters as $scooter) {
            $destinations .= $scooter->latitude . "," . $scooter->longitude . "|";
        }
        $origins = $request->lat . ',' . $request->lng . "|";

        try {

            $response = Http::get('https://maps.googleapis.com/maps/api/distancematrix/json?destinations='. $destinations .'&origins=' . $origins . 'departure_time=now&key=AIzaSyD92ePxBG5Jk6mM3djSW49zs3dRKJroWRk');

            $distances = $response->json();

        } catch (Exception $e) {

            $distances = [

                'status' => 'error',

                'message' => $e->getMessage(),

            ];

        }
        $nearest_distance_b_user_scooter = 0;

        $nearest_distance_b_user_scooter_km = "";

        $distanceIndex = 0;

        $i = 0;

        foreach ($distances['rows'][0]['elements'] as $distance) {

            $dis = $distance['distance']['value'];

            $km = $distance['distance']['text'];

            if ($i == 0) {

                $nearest_distance_b_user_scooter = $dis;

                $nearest_distance_b_user_scooter_km = $km;

            } else {

                if ($dis < $nearest_distance_b_user_scooter) {

                    $nearest_distance_b_user_scooter = $dis;

                    $nearest_distance_b_user_scooter_km = $km;

                    $distanceIndex = $i;

                }

            }


            $i++;

        }


        if ($nearest_distance_b_user_scooter > 3000) {

            // Replace with your response sending function

            return  response()->json(

                array(

                    "status" => true,

                    "account_status" => true,

                    "message" => "There are no nearest scooters to your location. The nearest one is about {$nearest_distance_b_user_scooter_km}",

                    "errors" => array(),

                    "data" => array(

                        "scooter" => $scooters[$distanceIndex]

                    )

                )

            );

        } else {

            // Replace with your response sending function

            return  response()->json(

                array(

                    "status" => true,

                    "account_status" => true,

                    "message" => "The nearest scooters to your location is about {$nearest_distance_b_user_scooter_km}",

                    "errors" => array(),

                    "data" => array(

                        "scooter" => $scooters[$distanceIndex]

                    )

                )

            );

        }

    }
}
