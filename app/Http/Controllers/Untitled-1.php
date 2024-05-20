// Assuming you have a Zone model with a 'polygon' field
$zones = Zone::all();

// Given point coordinates
$x = 26.421813284735137;
$y = 43.9439818756710;

foreach ($zones as $zone) {
    // Parse the polygon from the database (assuming it's stored as WKT)
    $polygon = \Spatial\Types\Polygon::fromWKT($zone->polygon);

    // Create a point from the given coordinates
    $point = new \Spatial\Types\Point($x, $y);

    // Check if the point is within the polygon
    if ($polygon->contains($point)) {
        // Point is within this zone
        // You can handle this case as needed (e.g., return the zone information)
        return "Point lies within Zone {$zone->id}";
    }
}

// If no zone contains the point
return "Point does not lie within any zone.";
