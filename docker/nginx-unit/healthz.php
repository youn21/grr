<?php
include "../personnalisation/connect.inc.php";

try {
$mysqli = new mysqli($dbHost, $dbUser, $dbPass, $dbDb);
}
catch (Exception $e) {
    http_response_code(503);
    printf("Cant connect to GRR database", $e);
    exit;
}
if (mysqli_connect_errno()) {
  http_response_code(503);
  printf("Cant connect to GRR database", mysqli_connect_error());
  exit;
}
else {
header("Content-Type: text/plain");
echo "OK";
}
?>

