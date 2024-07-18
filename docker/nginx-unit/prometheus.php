<?php
$sock = stream_socket_client("unix://".getenv("control_socket"), $errno, $errst);
fwrite($sock, "GET /status HTTP/1.0\r\n\r\n");
$resp = fread($sock, 4096);
fclose($sock);

list($headers, $body) = explode("\r\n\r\n", $resp, 2);
$json = json_decode($body);

$metrics = array();
array_push($metrics, "unit_connections_accepted_total ".$json->connections->accepted);
array_push($metrics, "unit_connections_active ".$json->connections->active);
array_push($metrics, "unit_connections_idle ".$json->connections->idle);
array_push($metrics, "unit_connections_closed_total ".$json->connections->closed);
array_push($metrics, "unit_requests_total ".$json->requests->total);

foreach($json->applications as $application => $data) {
    array_push($metrics, "unit_application_".$application."_processes_running ".$data->processes->running);
    array_push($metrics, "unit_application_".$application."_processes_starting ".$data->processes->starting);
    array_push($metrics, "unit_application_".$application."_processes_idle ".$data->processes->idle);
    array_push($metrics, "unit_application_".$application."_requests_active ".$data->requests->active);
}

include 'connect.inc.php';

$mysqli = new mysqli($dbHost, $dbUser, $dbPass, $dbDb);

if (mysqli_connect_errno()) {
  printf("Cant connect to GRR database", mysqli_connect_error());
  exit();
}

$grr_metrics = array("grr_utilisateurs", "grr_type_area", "grr_entry", "grr_site");
foreach($grr_metrics as $grr_metric) {
  $rowcount = mysqli_num_rows($mysqli->query("SELECT * FROM $grr_metric"));
  array_push($metrics, $grr_metric." ".$rowcount);
}

header("Content-Type: text/plain");
echo join("\n", $metrics)."\n";
?>

