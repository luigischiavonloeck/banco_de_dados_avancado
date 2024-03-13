<?php 

$data = array(
    "nome" => "Ciclano",
    "email" => "ciclano@ifsul.edu.br"
);

$apiKey = json_decode(file_get_contents("firebase_credentials.json"), true)['apiKey'];
printf($apiKey);

$curl = curl_init("https://firstproject-24838-default-rtdb.firebaseio.com/users.json?auth=".$apiKey);
curl_setopt_array($curl, array(
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_POST  => true,
    CURLOPT_POSTFIELDS => json_encode($data),
    CURLOPT_HTTPHEADER => array(
        "cache-control: no-cache",
        "Content-Type: application/json",
        'Content-Length: ' . strlen(json_encode($data))
    ),
));

$response = curl_exec($curl);
$err = curl_error($curl);
curl_close($curl);
if ($err) {
    echo "Codigo de erro #:" . $err;
} else {
    echo $response;
}