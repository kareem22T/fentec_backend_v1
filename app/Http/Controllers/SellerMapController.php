<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Seller;
use Exception;

class SellerMapController extends Controller
{
    public function getAllSellers() {
        $filteredSellers = Seller::all();

        if ($filteredSellers && $filteredSellers->count() > 0) {
            return response()->json([
                "status" => true,
                "message" => "Operation successful",
                "errors" => [],
                "data" => $filteredSellers
            ]);
        } elseif ($filteredSellers && $filteredSellers->count() == 0) {
            return response()->json([
                "status" => false,
                "message" => "No sellers found",
                "errors" => ['There are no sellers available'],
                "data" => []
            ], 200);
        } else {
            return response()->json([
                "status" => false,
                "message" => "Could not fetch sellers",
                "errors" => ['Server error: could not fetch sellers'],
                "data" => []
            ], 500);
        }
    }

    public function getNearestSeller(Request $request) {
        $sellers = Seller::all();
        $lang = $request->lang ? $request->lang : 'en';

        $error_msgs = [
            "message" => [
                "en" => "Currently there is no FenPay point, please call 0540842707",
                "fr" => "Il n'y a actuellement pas de point FenPay, veuillez appeler le 0540842707",
                "ar" => "حاليا لايوجد نقطة FenPay, الرجاء الإتصال ب 0540842707.",
            ],
        ];


        if ($sellers->count() == 0) {
            return response()->json([
                "status" => false,
                "message" => "There are no available sellers now",
                "errors" => ["There are no available sellers now"],
                "data" => []
            ]);
        }

        $userLat = $request->lat;
        $userLng = $request->lng;

        $nearestSeller = null;
        $nearestDistance = null;

        foreach ($sellers as $seller) {
            $distance = $this->calculateDistance($userLat, $userLng, $seller->latitude, $seller->longitude);

            if ($nearestDistance === null || $distance < $nearestDistance) {
                $nearestDistance = $distance;
                $nearestSeller = $seller;
            }
        }

        if ($nearestSeller === null || $nearestDistance === null) {
            return response()->json([
                'status' => false,
                'message' => 'No valid distance data found',
                'errors' => [],
                'data' => []
            ]);
        }

        $nearestDistanceKm = $nearestDistance . ' km';

        if ($nearestDistance > 3) { // assuming 3 km as the threshold for 'nearest'
            return response()->json([
                "status" => true,
                "message" => "{$error_msgs['message'][$lang]}",
                "errors" => [],
                "data" => ["seller" => $nearestSeller]
            ]);
        } else {
            return response()->json([
                "status" => true,
                "message" => "The nearest seller to your location is about",
                "errors" => [],
                "data" => ["seller" => $nearestSeller]
            ]);
        }
    }

    private function calculateDistance($lat1, $lng1, $lat2, $lng2) {
        $earthRadius = 6371; // Earth's radius in kilometers

        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLng / 2) * sin($dLng / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c; // Distance in kilometers
    }
}
