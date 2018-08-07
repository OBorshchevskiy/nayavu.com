<?php
###################################################################################################
### получаем данные из профилей пользователей #####################################################
###################################################################################################
function get_vk_data_users($uids, $fields) {

# id приложения
$vk_api_app_id = "***";
# секретный ключ
$vk_api_app_secret = "***";

$request = array(
'random'      => rand(100000, 999999),
'timestamp'   => time(),
'format'      =>'JSON',
'api_id'      => $vk_api_app_id,
'client_secret' => '***',
'fields'      => $fields,
'uids'        => $uids,
'method'      => 'users.get',
);

ksort($request);
foreach ($request as $key => $value) {
$str .= trim($key)."=".trim($value);
}

$request['sig'] = md5(trim($str.$vk_api_app_secret));
$vars = http_build_query($request);

$ch = curl_init();
curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.2.13) Gecko/20101203 MRA 5.7 (build 03797) Firefox/3.6.13");
curl_setopt($ch, CURLOPT_URL, "https://api.vk.com/api.php");
curl_setopt($ch, CURLOPT_TIMEOUT, 300);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS,  $vars);
$info = json_decode(curl_exec($ch), true);
curl_close($ch);
return $info;
}
?>