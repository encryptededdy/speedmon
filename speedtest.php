<?php
$darkskyapi = "<removed>"; // needed for tracking precipitation
try {
    $json = file_get_contents("https://api.darksky.net/forecast/".$darkskyapi."/-36.8536,174.776?units=si");
    $response = json_decode($json, true);
    $percipIntensity = $response['currently']['precipIntensity'];
} catch (Exception $exp) {
    $percipIntensity = "ERR";
}
$speedtest = trim(shell_exec("/usr/local/bin/speedtest-cli --csv --server 5749"));
printf("%s,%s\n", $speedtest, $percipIntensity);
?>
