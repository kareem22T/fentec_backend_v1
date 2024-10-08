<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Scooter;
use Exception;
use GuzzleHttp\Client;

class MapController extends Controller
{
    public function getAllScooters() {
        $filteredScooters = Scooter::with(['trips' => function($query) {
            $query;
        }])->get();

        if ($filteredScooters && $filteredScooters->count() > 0) {
            return response()->json([
                "status" => true,
                "account_status" => true,
                "message" => "Operation successful",
                "errors" => [],
                "data" => $filteredScooters
            ]);
        } elseif ($filteredScooters && $filteredScooters->count() == 0) {
            return response()->json([
                "status" => false,
                "account_status" => true,
                "message" => "No scooters found",
                "errors" => ['There are no scooters available'],
                "data" => []
            ], 200);
        } else {
            return response()->json([
                "status" => false,
                "account_status" => true,
                "message" => "Could not fetch scooters",
                "errors" => ['Server error: could not fetch scooters'],
                "data" => []
            ], 500);
        }
    }
    public function getNearstScooter(Request $request) {
        $scooters = Scooter::all();

        if ($scooters->count() == 0) {
            return response()->json([
                "status" => false,
                "account_status" => true,
                "message" => "",
                "errors" => ["There are no available scooters now"],
                "data" => []
            ]);
        }

        $destinations = "";
        foreach ($scooters as $scooter) {
            $destinations .= $scooter->latitude . "," . $scooter->longitude . "|";
        }
        $origins = $request->lat . ',' . $request->lng . "|";

        try {
            $response = Http::get('https://maps.googleapis.com/maps/api/distancematrix/json', [
                'destinations' => rtrim($destinations, "|"),
                'origins' => $origins,
                'departure_time' => 'now',
                'key' => 'YOUR_API_KEY_HERE',
            ]);

            $distances = $response->json();
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ]);
        }

        if (!isset($distances['rows'][0]['elements']) || empty($distances['rows'][0]['elements'])) {
            return response()->json([
                'status' => false,
                'message' => 'Error in fetching distances',
                'errors' => [],
                'data' => []
            ]);
        }

        $nearest_distance_b_user_scooter = null;
        $nearest_distance_b_user_scooter_km = "";
        $distanceIndex = 0;

        foreach ($distances['rows'][0]['elements'] as $i => $distance) {
            if (isset($distance['distance']['value'])) {
                $dis = $distance['distance']['value'];
                $km = $distance['distance']['text'];

                if ($nearest_distance_b_user_scooter === null || $dis < $nearest_distance_b_user_scooter) {
                    $nearest_distance_b_user_scooter = $dis;
                    $nearest_distance_b_user_scooter_km = $km;
                    $distanceIndex = $i;
                }
            }
        }

        if ($nearest_distance_b_user_scooter === null) {
            return response()->json([
                'status' => false,
                'message' => 'No valid distance data found',
                'errors' => [],
                'data' => []
            ]);
        }

        if ($nearest_distance_b_user_scooter > 3000) {
            return response()->json([
                "status" => true,
                "account_status" => true,
                "message" => "There are no nearest scooters to your location. The nearest one is about {$nearest_distance_b_user_scooter_km}",
                "errors" => [],
                "data" => ["scooter" => $scooters[$distanceIndex]]
            ]);
        } else {
            return response()->json([
                "status" => true,
                "account_status" => true,
                "message" => "The nearest scooter to your location is about {$nearest_distance_b_user_scooter_km}",
                "errors" => [],
                "data" => ["scooter" => $scooters[$distanceIndex]]
            ]);
        }
    }
    public function notifyScooter(Request $request) {
        $iot = Scooter::find($request->id);
        if ($iot) {
            $client = new Client();
            $use_alarm = $client->post('http://api.uqbike.com/terControl/sendControl.do', [
                'form_params' => [
                    'machineNO' => $iot->machine_no,
                    'token' => $iot->token,
                    'paramName' => 9,
                    'controlType' => 'control'
                ]
            ]);
            return response()->json([
                "status" => true,
            ], 200);
        }
        return response()->json([
            "status" => false,
        ], 200);
    }
}
