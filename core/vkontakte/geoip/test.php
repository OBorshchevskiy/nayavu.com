<?php
require_once("geoip.inc");
$gi = geoip_open("GeoIP.dat", GEOIP_STANDARD);
echo geoip_country_code_by_addr($gi, "194.165.61.2");
geoip_close($gi);
?>