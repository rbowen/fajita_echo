<?php

$data = file_get_contents("php://input");
$query = json_decode( $data );
# error_log( print_r( $query, 1 ) );

$meat = array(
    'sun dried tomato chicken',
    'steak',
    'bangers and mash',
    'shepherd\'s pie',
    'chicken etoufee',
    'tandoori chicken',
    'ethiopian',
);

$vegetarian = array(
    'grilled cheese and soup',
    'monkeys and cheese',
    'spaghetti',
    'enchiladas',
    'ramen',
);

$verb = array(
    'Have',
    'How about',
    'Try',
    'What about',
    'You could have',
);


if ( $query->request->intent->name == "GetVegetarian" ) {
    $menu = $vegetarian;
} else {
    $menu = array_merge( $meat, $vegetarian );
}

$meal = $menu[ array_rand( $menu ) ];

$recommend = $verb[ array_rand( $verb )] . ' '
           . $meal . ' for dinner';

$response = array (
   "version" => '1.0',
    'response' => array (
        'outputSpeech' => array (
            'type' => 'PlainText',
            'text' => $recommend
        ),

         'card' => array (
               'type' => 'Simple',
               'title' => 'Fajita',
               'content' => 'Fajita recommends ' . $meal . ' for dinner.'
         ),

        'shouldEndSession' => 'true'
    ),
);

echo json_encode($response);

?>

