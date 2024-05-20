<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Zone;
class ZonesController extends Controller
{
    public function whereIot() {
        // Assume $points is an array containing the latitude and longitude of the point to check
        $point = [35.555651468582454, 6.184567020457852];

        // Assume $polygonsJson is the JSON data containing the polygons
        $polygons = Zone::all();

        // Loop through each polygon
        foreach ($polygons as $polygon) {
            // Parse the path of the polygon
            $coordinates = json_decode($polygon['path'], true);

            // Check if the point lies within the polygon
            if ($this->pointInPolygon($point, $coordinates)) {
                // Return the zone of the polygon containing the point
                $zone = $polygon['type'];
                echo "Point lies in zone: $zone";
                break; // Exit the loop if the point is found within a polygon
            }
        }

    }

    function pointInPolygon($point, $polygon) {
        $vertices = count($polygon);
        $intersections = 0;

        for ($i = 0, $j = $vertices - 1; $i < $vertices; $j = $i++) {
            if (($polygon[$i]['lng'] > $point[1]) != ($polygon[$j]['lng'] > $point[1]) &&
                $point[0] < ($polygon[$j]['lat'] - $polygon[$i]['lat']) * ($point[1] - $polygon[$i]['lng']) / ($polygon[$j]['lng'] - $polygon[$i]['lng']) + $polygon[$i]['lat']) {
                $intersections++;
            }
        }

        return $intersections % 2 != 0;
    }
}
