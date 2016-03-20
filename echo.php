<?php

$data = file_get_contents("php://input");
$query = json_decode( $data );
# error_log( print_r( $query, 1 ) );

$me = array(
    'version' => '0.1',
    'name'    => 'Fajita'
);

$guid = '179cac3e-f062-48e6-ba41-109a728d01fe';
$userid = 'AERVD6MDLOMTUHVRFQ2JMWVPXZSHPHCNYUCOEEXB43QOFQL22M36Q';
$help = "Hi. .  I'm Fajita.  .  Ask me what's for dinner.";

$verb = array(
    'Have',
    'How about',
    'Try',
    'What about',
    'You could have',
);

try {
    $db = new PDO('mysql:host=localhost;dbname=fajita;charset=utf8mb4', 'fajita', 'fajita');
}
catch(PDOException $e) {
    error_log( $e->getMessage() ) ;
}

include('../validate-echo-request-php/valid_request.php');
$valid = validate_request( $guid, $userid );

if ( ! $valid['success'] )  {
    error_log( 'Request failed: ' . $valid['message'] );
    die();
}

$vegetarian = 0;

if ( $query ) {
    $action = $query->request->intent->name;

    if ( $action == "GetVegetarian" ) {
        $vegetarian = 1;
    }

    # Get a suggestion for dinner
    if ( $action == 'GetVegetarian' || $action == 'GetMenu' ) {

        $dbq = "SELECT name FROM menu ";
        if ( $vegetarian ) {
            $dbq = $dbq . " WHERE vegetarian IS TRUE ";
        }
        $dbq = $dbq . " ORDER BY RAND() LIMIT 0,1 ";

        $sth = $db->prepare( $dbq );
        $sth->execute();
        $result = $sth->fetch();
        $meal = $result['name'];

        $recommend = $verb[ array_rand( $verb )] . ' '
                   . $meal . ' for dinner';

        $response = $recommend;
    }

    # Add an item to the menu
    elseif ( $action == 'AddMenu' ) {
    }

    # Help
    elseif ( $action == 'AMAZON.HelpIntent' ) {
        sendresponse( $help, $me );
    }

}

else {
    $response = $help;
}

sendresponse( $response, $me );
echo json_encode($response);

?>

