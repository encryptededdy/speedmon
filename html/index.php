<?php
require 'vendor/autoload.php';
$data = array_slice(file('/home/edward/netspeed.csv'), -144);
date_default_timezone_set('Pacific/Auckland');
$tablestr = "";
$download = array();
$upload = array();
$percip = array();

foreach (array_reverse($data) as $line) {
	$linedata = explode("," ,$line);
	if ($linedata[0] != 5749) {
		$time = "Connection Lost";
		$linedata[5] = 999.9;
	} else {
		$time = date('l h:i A', strtotime($linedata[3]));
	}
	if ($linedata[8] == "ERR") {
		$pcp = "Error / Timeout";
		array_unshift($percip, 100);
	} elseif ($linedata[8] == null) {
		$pcp = "No data";
		array_unshift($percip, 0);
	} else {
		$pcp = $linedata[8]*100;
		array_unshift($percip, round($linedata[8]*100, 0));
	}
	// highlight red if we exceed values
	if ($linedata[0] != 5749) { // if no connection
		$tablestr .= "<tr class=\"danger\">\n";
	} elseif ($linedata[6]/1000000 < 20) { // if slow
		$tablestr .= "<tr class=\"warning\">\n";
	} elseif ($linedata[6]/1000000 < 10) { // if relaly slow
		$tablestr .= "<tr class=\"danger\">\n";
 	} elseif ($linedata[5] > 200) {// if ping > 200
		$tablestr .= "<tr class=\"warning\">\n";
	} else { //proceed as usual
		$tablestr .= "<tr>\n";
	}
	$tablestr .= sprintf("<td>%s</td>\n<td>%.1f</td>\n<td>%.2f</td>\n<td>%.2f</td>\n<td>%s</td>\n", $time, $linedata[5], $linedata[6]/1000000, $linedata[7]/1000000, $pcp);
	array_unshift($download, round($linedata[6]/1000000, 1));
	array_unshift($upload, round($linedata[7]/1000000, 1));
	$tablestr .= "</tr>\n";
}
// generate graph
$speedChart = new gchart\gLineChart(1000,300);
$speedChart->addDataSet($download);
$speedChart->addDataSet($upload);
$speedChart->setVisibleAxes(array('r', 'x'));
$speedChart->setDataRange(0,50);
$speedChart->addAxisRange(0, 0, 50);
$speedChart->addAxisRange(1, 24, 0, 1);
$speedChart->setLegend(array("Download", "Upload", "Ping (ms)"));
$speedChart->setColors(array("ff3344", "11ff11", "22aacc"));

$pingChart = new gchart\gLineChart(1000,300);
$pingChart->addDataSet($percip);
$pingChart->setVisibleAxes(array('r', 'x'));
$pingChart->setDataRange(0,100);
$pingChart->addAxisRange(0, 0, 100);
$pingChart->addAxisRange(1, 24, 0, 1);
$pingChart->setLegend(array("Percip int"));
$pingChart->setColors(array("22aacc"));

?>

<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content 
must come *after* these tags -->
    <title>Speedtest</title>
    <!-- Bootstrap -->
    <link href="../css/bootstrap.css" rel="stylesheet">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries 
-->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
<div class="container">
<h1>CarlawNet Speed Logger</h1>
<div class="alert alert-warning" role="alert">Note: Precipitation logging was just added so the data may be incomplete</div>
<h3>Showing last 24 hours</h3>
<img src="<?php print $speedChart->getUrl();  ?>" /> <br>
<img src="<?php print $pingChart->getUrl();  ?>" /> <br><br>
<table class="table table-condensed table-hover">
	<thead>
		<tr>
			<th>Time</th>
			<th>Ping (ms)</th>
			<th>Download (mbit/s)</th>
			<th>Upload (mbit/s)</th>
			<th>Precipitation Intensity (%)</th>
		</tr>
	</thead>
	<tbody>
		<?php
		echo ($tablestr);
		?>
	</tbody>
</table>
</div>
</html>
