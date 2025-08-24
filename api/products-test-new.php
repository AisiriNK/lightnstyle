<?php 
header('Content-Type: application/json');
$response = [
    'success' => true,
    'count' => 2,
    'products' => [
        [
            'id' => 1,
            'name' => 'Elegant Gold Chandelier',
            'category' => 'Chandelier',
            'material' => 'Crystal + Brass',
            'wattage' => 60,
            'size' => '24 inches',
            'finish' => 'Antique Gold',
            'image_url' => 'images/chandelier1.jpeg'
        ],
        [
            'id' => 2,
            'name' => 'Modern Ring Chandelier',
            'category' => 'Chandelier',
            'material' => 'Aluminum + Acrylic',
            'wattage' => 45,
            'size' => '20 inches',
            'finish' => 'Chrome',
            'image_url' => 'images/chandelier2.jpeg'
        ]
    ]
];
echo json_encode($response);
?>
