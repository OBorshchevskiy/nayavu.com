<?
sleep(40);
date_default_timezone_set("Europe/Moscow");
include_once('/var/www/core/vkontakte/simplehtmldom/simple_html_dom.php');
# require_once("/var/www/core/vkontakte/geoip/geoip.inc");
# $gi = geoip_open("/var/www/core/vkontakte/geoip/GeoIP.dat", GEOIP_STANDARD);

$proxy_list_arr[] = file_get_html('http://proxylists.net/http.txt');
$proxy_list_arr[] = file_get_html('http://www.proxylists.net/http.txt');
$proxy_list_arr[] = file_get_html('http://www.proxylists.net/http_highanon.txt');
$proxy_list_arr[] = file_get_html('http://www.tubeincreaser.com/proxylist.txt');
$proxy_list_arr[] = file_get_html('http://multiproxy.org/txt_anon/proxy.txt');
$proxy_list_arr[] = file_get_html('http://www.searchlores.org/pxylist1.txt');
$proxy_list_arr[] = file_get_html('http://www.searchlores.org/pxylist2.txt');
$proxy_list_arr[] = file_get_html('http://www.freeproxy.ch/proxy.txt');
$proxy_list_arr[] = file_get_html('http://foxtools.ru/Proxy?page=1');
$proxy_list_arr[] = file_get_html('http://foxtools.ru/Proxy?page=2');
$proxy_list_arr[] = file_get_html('http://foxtools.ru/Proxy?page=3');
$proxy_list_arr[] = file_get_html('http://foxtools.ru/Proxy?page=4');
$proxy_list_arr[] = file_get_html('http://foxtools.ru/Proxy?page=5');
$proxy_list_arr[] = file_get_html('http://foxtools.ru/Proxy?page=6');
$proxy_list_arr[] = file_get_html('http://foxtools.ru/Proxy?page=7');
$proxy_list_arr[] = file_get_html('http://foxtools.ru/Proxy?page=8');
$proxy_list_arr[] = file_get_html('http://foxtools.ru/Proxy?page=9');
$proxy_list_arr[] = file_get_html('http://foxtools.ru/Proxy?page=10');
$proxy_list_arr[] = file_get_html('http://foxtools.ru/Proxy?page=11');
$proxy_list_arr[] = file_get_html('http://foxtools.ru/Proxy?page=12');
$proxy_list_arr[] = file_get_html('http://foxtools.ru/Proxy?page=13');
$proxy_list_arr[] = file_get_html('http://foxtools.ru/Proxy?page=14');
$proxy_list_arr[] = file_get_html('http://foxtools.ru/Proxy?page=15');
$proxy_list_arr[] = file_get_html('http://foxtools.ru/Proxy?page=16');
$proxy_list_arr[] = file_get_html('http://foxtools.ru/Proxy?page=17');
$proxy_list_arr[] = file_get_html('http://foxtools.ru/Proxy?page=18');
$proxy_list_arr[] = file_get_html('http://foxtools.ru/Proxy?page=19');
$proxy_list_arr[] = file_get_html('http://foxtools.ru/Proxy?page=20');
$proxy_list_arr[] = file_get_html('http://www.freeproxylists.net/ru/?page=1');
$proxy_list_arr[] = file_get_html('http://www.freeproxylists.net/ru/?page=2');
$proxy_list_arr[] = file_get_html('http://www.freeproxylists.net/ru/?page=3');
$proxy_list_arr[] = file_get_html('http://www.freeproxylists.net/ru/?page=4');
$proxy_list_arr[] = file_get_html('http://www.freeproxylists.net/ru/?page=5');
$proxy_list_arr[] = file_get_html('http://www.freeproxylists.net/ru/?page=6');
$proxy_list_arr[] = file_get_html('http://www.freeproxylists.net/ru/?page=7');
$proxy_list_arr[] = file_get_html('http://www.freeproxylists.net/ru/?page=8');
$proxy_list_arr[] = file_get_html('http://www.freeproxylists.net/ru/?page=9');
$proxy_list_arr[] = file_get_html('http://www.freeproxylists.net/ru/?page=10');
$proxy_list_arr[] = file_get_html('http://www.freeproxylists.net/ru/?page=11');
$proxy_list_arr[] = file_get_html('http://www.freeproxylists.net/ru/?page=12');
$proxy_list_arr[] = file_get_html('http://www.freeproxylists.net/ru/?page=13');
$proxy_list_arr[] = file_get_html('http://www.freeproxylists.net/ru/?page=14');
$proxy_list_arr[] = file_get_html('http://www.freeproxylists.net/ru/?page=15');
$proxy_list_arr[] = file_get_html('http://www.freeproxylists.net/ru/?page=16');
$proxy_list_arr[] = file_get_html('http://www.freeproxylists.net/ru/?page=17');
$proxy_list_arr[] = file_get_html('http://www.freeproxylists.net/ru/?page=18');
$proxy_list_arr[] = file_get_html('http://www.freeproxylists.net/ru/?page=19');
$proxy_list_arr[] = file_get_html('http://www.freeproxylists.net/ru/?page=20');

$proxy_list = "";
foreach ($proxy_list_arr as $value_proxy) {
$proxy_list = $proxy_list.$value_proxy;
}

preg_match_all('/\b\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}:\d{1,6}\b/', $proxy_list, $all_proxy_list);

$all_proxy_list = array_unique($all_proxy_list[0]);

foreach ($all_proxy_list as $value_a) {
$all_proxy_list_ip_port = explode(':', $value_a);

$ip_proxy = $all_proxy_list_ip_port[0];

# if (geoip_country_code_by_addr($gi, $ip_proxy) == "RU") {
$all_proxy_list_ip[] = $ip_proxy;
# }

}
$all_proxy_list_ip = array_unique($all_proxy_list_ip);

foreach ($all_proxy_list_ip as $value_d) {
$all_proxy_list_ip_short = explode('.', $value_d);
$all_proxy_list_ip_three_num[] = $all_proxy_list_ip_short[0].".".$all_proxy_list_ip_short[1].".".$all_proxy_list_ip_short[2];
}

foreach ($all_proxy_list_ip_three_num as $value_b) {
foreach ($all_proxy_list as $value_c) {
if (strstr($value_c, $value_b)) {
$all_proxy_list_un[] = $value_c;
break;
  }
 }
}

$all_proxy_list_un = array_unique($all_proxy_list_un);
$all_proxy_list_un = array_slice($all_proxy_list_un, 0, 2000);
shuffle($all_proxy_list_un);

sleep(2);

function checkProxies($proxies) {
$url = 'http://nayavu.com/return_ok/index.php';
$return = 'ok';

$count = count($proxies);
echo 'Number of proxies in list: ' . $count . '<br />';

$curl_arr = array();
$master = curl_multi_init();

for($i = 0; $i < $count; $i++) {
$proxy = $proxies[$i];
$cproxy = explode(':', $proxy);

$curl_arr[$i] = curl_init();
curl_setopt($curl_arr[$i], CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($curl_arr[$i], CURLOPT_HEADER, FALSE);
curl_setopt($curl_arr[$i], CURLOPT_URL, $url);
curl_setopt($curl_arr[$i], CURLOPT_CONNECTTIMEOUT, 10);
curl_setopt($curl_arr[$i], CURLOPT_TIMEOUT, 10);
curl_setopt($curl_arr[$i], CURLOPT_PROXY, $cproxy[0]);
curl_setopt($curl_arr[$i], CURLOPT_PROXYPORT, $cproxy[1]);
curl_multi_add_handle($master, $curl_arr[$i]);
}

$running = null;
do {
curl_multi_exec($master,$running);
} while($running > 0);

echo 'Results: <br />';
$a = 0;
for($i = 0; $i < $count; $i++) {
$rawdata = curl_multi_getcontent($curl_arr[$i]);

if($rawdata == $return){
echo $i . '. Good Proxy: ' . $proxies[$i] . '<br /><br />';
$proxylist[$a] = $proxies[$i]."\r\n";
$a++;
} else echo $i . '. Bad Proxy: ' . $proxies[$i] . '<br /><br />';
}
echo 'Number of good proxies: ' . count($proxylist);

curl_multi_close($master);
return $proxylist;
}

# geoip_close($gi);

$proxies = checkProxies($all_proxy_list_un);
file_put_contents ("/var/www/core/vkontakte/proxy.txt", $proxies);
?>