<?php
include "../personnalisation/connect.inc.php";
error_reporting(0);
try {
$mysqli = new mysqli($dbHost, $dbUser, $dbPass, $dbDb);
}
catch (Exception $e) {
    http_response_code(503);
    echo "Fail";
    exit;
}
if (mysqli_connect_errno()) {
  http_response_code(503);
  echo "Fail";
  exit;
}
else {
header("Content-Type: text/plain");
echo "OK";
}
?>

