<?php
/**
 * Requires libcurl
 */

$json = file_get_contents('php://input');
$data = json_decode($json);

$query = array(
  "key" => "c1df4fef-379b-4d4a-bdad-27660c22881c"
);

$curl = curl_init();

$payload = array(
  "points" => array(
    array(
      $data[0][1],
      $data[0][0]
    ),
    array(
      $data[1][1],
      $data[1][0]
    )
  ),
  "vehicle" => "car",
  "locale" => "en",
  "instructions" => false,
  "calc_points" => true,
  "points_encoded" => true
);

curl_setopt_array($curl, [
  CURLOPT_HTTPHEADER => [
    "Content-Type: application/json"
  ],
  CURLOPT_POSTFIELDS => json_encode($payload),
  CURLOPT_URL => "https://graphhopper.com/api/1/route?" . http_build_query($query),
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_CUSTOMREQUEST => "POST",
]);

$response = curl_exec($curl);
$error = curl_error($curl);

curl_close($curl);

if ($error) {
  echo "cURL Error #:" . $error;
} else {
  $data_out = json_decode($response, true);
  echo $data_out["paths"][0]["points"];
}
?>