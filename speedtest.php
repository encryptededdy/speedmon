<?php
$darkskyapi = "63806295f8572e6db8decede5e0549bd"; // needed for tracking precipitation

try {
    $json = file_get_contents("https://api.darksky.net/forecast/".$darkskyapi."/-36.8536,174.776?units=si");
    $response = json_decode($json, true);
    $percipIntensity = $response['currently']['precipIntensity'];
} catch (Exception $exp) {
    $percipIntensity = "ERR";
}
$speedtest = trim(shell_exec("/usr/local/bin/speedtest-cli --csv --server 5749")); // recommended that you use a fixed server instead of autodetect.
printf("%s,%s\n", $speedtest, $percipIntensity);
?>
