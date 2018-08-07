<?
/*
sleep(30);
date_default_timezone_set("Europe/Moscow");
require("/var/www/core/config.php");
require("/var/www/core/connect.php");

function getRandomUserAgent() {
$userAgents=array(
        "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-GB; rv:1.8.1.6) Gecko/20070725 Firefox/2.0.0.6",
        "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1)",
        "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; .NET CLR 1.1.4322; .NET CLR 2.0.50727; .NET CLR 3.0.04506.30)",
        "Opera/9.20 (Windows NT 6.0; U; en)",
        "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; en) Opera 8.50",
        "Mozilla/4.0 (compatible; MSIE 6.0; MSIE 5.5; Windows NT 5.1) Opera 7.02 [en]",
        "Mozilla/5.0 (Macintosh; U; PPC Mac OS X Mach-O; fr; rv:1.7) Gecko/20040624 Firefox/0.9",
        "Mozilla/5.0 (Macintosh; U; PPC Mac OS X; en) AppleWebKit/48 (like Gecko) Safari/48"       
);
$random = rand(0, count($userAgents)-1);
return $userAgents[$random];
}

function get_reg_date($id, $proxy) {

$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, "http://shostak.ru/vk");
curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 20);
curl_setopt($curl, CURLOPT_TIMEOUT, 20);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, "user=".$id);
curl_setopt($curl, CURLOPT_USERAGENT, getRandomUserAgent());
$reg_date = strip_tags(curl_exec($curl), "<h1>");
curl_close($curl);

$reg_date = substr($reg_date, 250);

$first = strpos($reg_date, "Регистрация");
$second = strpos($reg_date, "Замороженные", $first);

$date_get = substr($reg_date, $first + 22, $second-($first + 22));

echo "=".$id."-".$date_get."=<br>";

if ( substr($date_get, -4) < 2006) {
return "none";
} else {
return $date_get;
 }
}



$fp = @fopen("/var/www/core/vkontakte/proxy.txt", 'r');
if ($fp) {
$arr_proxy = explode("\r\n", fread($fp, filesize("/var/www/core/vkontakte/proxy.txt")));
}
shuffle($arr_proxy);

$vkontakte_user_to_monitoring_data = mysql_query("select * from vkontakte_user_to_monitoring order by id_monitoring_user");
while ($get_vkontakte_user_to_monitoring_data = mysql_fetch_array($vkontakte_user_to_monitoring_data)) {

# сколько сейчас времени
$time_now = mktime(date("H"), date("i"), date("s"), date("m"), date("j"), date("Y"));

# для повторных проверок даты регистрации - если дата не определены, число проверок менее 100 и прошло больше 30 минут с последней проверки
# или для новых проверок, у которых time_check_none - еще не определено
if (
( ($get_vkontakte_user_to_monitoring_data['reg_date'] == "none") && ($get_vkontakte_user_to_monitoring_data['reg_none_num'] < 100) && (($time_now-$get_vkontakte_user_to_monitoring_data['time_check_none']) > 1800) ) ||
!$get_vkontakte_user_to_monitoring_data['reg_date']
   ) {

$reg_none_num = 0;

$reg_date = get_reg_date($get_vkontakte_user_to_monitoring_data['id_vk_user'], $arr_proxy[0]);

echo "<p>".$get_vkontakte_user_to_monitoring_data['id_vk_user']." - ".$reg_date."</p>";

# если дата регистрации не определена, увеличиваем счетчик проверок на 1
if ($reg_date == "none") {
# время последней проверки
$last_check_time = mktime(date("H"), date("i"), date("s"), date("m"), date("j"), date("Y"));
$reg_none_num = $get_vkontakte_user_to_monitoring_data['reg_none_num'] + 1;
mysql_query("update vkontakte_user_to_monitoring set reg_date='$reg_date', reg_none_num='$reg_none_num', time_check_none='$last_check_time' where id_monitoring_user=$get_vkontakte_user_to_monitoring_data[id_monitoring_user]");
} else {
mysql_query("update vkontakte_user_to_monitoring set reg_date='$reg_date' where id_monitoring_user=$get_vkontakte_user_to_monitoring_data[id_monitoring_user]");
}

sleep(5);
shuffle($arr_proxy);
 }
}
*/
?>