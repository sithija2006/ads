<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$apiKey = "b377552d666849c7893077acfb8688b8";

$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$pageSize = isset($_GET['pageSize']) ? intval($_GET['pageSize']) : 9;
$country = isset($_GET['country']) ? $_GET['country'] : "us";

$url = "https://newsapi.org/v2/top-headlines?country=$country&pageSize=$pageSize&page=$page&apiKey=$apiKey";

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

$result = curl_exec($ch);

if ($result === false) {
  echo json_encode([
    "status" => "error",
    "message" => curl_error($ch)
  ]);
  exit;
}

curl_close($ch);
echo $result;
