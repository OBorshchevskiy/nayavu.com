<?
error_reporting(E_ALL);

date_default_timezone_set("Europe/Moscow");
# подключение файла с настройками конфигурации
require("/var/www/core/config.php");
# подключение файла осуществляющего связь с базой данных
require("/var/www/core/connect.php");

###################################################################################################
### начало, функция обрабатывает возвращемые ответы ###############################################
###################################################################################################
function get_info_friends($url, $access_token) {

# определяем дату и время
$time_add = mktime(date("H"), date("i"), date("s"), date("m"), date("j"), date("Y"));

# соединяемся с вконтакте и отправляем запрос ссылкой и получаем ответ
$ch = curl_init();
curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; rv:25.0) Gecko/20100101 Firefox/25.0");
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept-Language: ru,en-us'));
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
curl_setopt($ch, CURLOPT_TIMEOUT, 20);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HEADER, 0);
$get_info = curl_exec($ch);
curl_close($ch);

$info_friends = json_decode($get_info, true);

# echo $url."<br><br><br>";

# echo "##########<br>";
# echo $get_info;
# echo "##########<br>";

# если заблокировали учетку токена, деактивируем токен
if (substr_count($get_info, "invalid access_token") == 1) {
mysql_query("update vkontakte_access_token set active='0' where (token='$access_token')");
} else {

# получаем все id из ответа сервера
$template="~user_id\"\:(\d+)\,\"~";
preg_match_all($template, $url, $uid);

# если есть ответы в массиве (25 штук) и первое значение массива - номер id
if (count($info_friends[response]) == 25) {

$n=0;
# перебираем всех 25 друзей
foreach ($info_friends[response] as $key => $friends_array) {

# находим id пользователя
$id_vk_user = $uid[1][$n];

# echo "id: ".$id_vk_user."<br>";

# определяем количество друзей
$friends_num = $friends_array[count]."<br>";

$vk_list_friends_id = NULL;
$vk_list_friends_data = NULL;

for ($m=0; $m < $friends_num; $m++) {
# присваиваем переменным для удобства работы
$id = $friends_array[items][$m][id];
$first_name = $friends_array[items][$m][first_name];
$last_name = $friends_array[items][$m][last_name];
$sex = $friends_array[items][$m][sex];
$screen_name = $friends_array[items][$m][screen_name];
$photo_50 = "update";
$photo_200_orig = "update";
$city = $friends_array[items][$m][city][title];
$country = $friends_array[items][$m][country][title];
$bdate = $friends_array[items][$m][bdate];

$vk_list_friends_id = $vk_list_friends_id.$id."#";
$vk_list_friends_data = $vk_list_friends_data.$id."-:=".$first_name."-:=".$last_name."-:=".$sex."-:=".$screen_name."-:=".$photo_50."-:=".$photo_200_orig."-:=".$city."-:=".$country."-:=".$bdate."|#|";

# обнуляем переменные
$id = NULL; $first_name = NULL; $last_name = NULL; $sex = NULL; $screen_name = NULL; $photo_50 = NULL; $photo_200_orig = NULL; $city = NULL; $country = NULL; $bdate = NULL;
}

# удаляем последний #
$vk_list_friends_id = substr($vk_list_friends_id, 0, -1);
# удаляем последний |#|
$vk_list_friends_data = mysql_real_escape_string(substr($vk_list_friends_data, 0, -3));

$n++;
# echo "<br>=============================<br>";
# данные для вставки в таблицу
# echo $vk_list_friends_id."<br>";
# echo $vk_list_friends_data."<br>";
# echo "<br>=============================<br>";

# проверяем есть ли уже записи
$vk_list_friends_first = mysql_result(mysql_query("select vk_list_friends_id from vkontakte_user_friends_log where (id_vk_user='$id_vk_user' && status='1')"), 0);

# если запись первая
if (!$vk_list_friends_first) {
if (strlen($vk_list_friends_id) > 3) {
mysql_query("insert vkontakte_user_friends_log (id_vk_user, vk_list_friends_data, vk_list_friends_id, status, time_add) values ('$id_vk_user', '$vk_list_friends_data', '$vk_list_friends_id', '1', '$time_add')");  
}

if (!mysql_num_rows(mysql_query("select * from vkontakte_user_friends_cron where(id_vk_user='$id_vk_user')"))) {
# добавляем в таблицу последних обновлений по крону
mysql_query("insert vkontakte_user_friends_cron (id_vk_user, time_update) values ('$id_vk_user', '$time_add')");
} else {
mysql_query("update vkontakte_user_friends_cron set time_update='$time_add' where (id_vk_user='$id_vk_user')");
}

} else {
if (strlen($vk_list_friends_id) > 3) {
# если запись не первая, находим последнюю запись(по максимальному времени)
$vk_list_friends_max = mysql_result(mysql_query("select vk_list_friends_id from vkontakte_user_friends_log where id_vk_user='$id_vk_user' order by time_add desc limit 1"), 0);
if ($vk_list_friends_max <> $vk_list_friends_id) {
mysql_query("insert vkontakte_user_friends_log (id_vk_user, vk_list_friends_data, vk_list_friends_id, status, time_add) values ('$id_vk_user', '$vk_list_friends_data', '$vk_list_friends_id', '0', '$time_add')");  

# в массив заносим id из предыдущего списка
$list_friends_array=explode("#", $vk_list_friends_max);
# в массив заносим id из нового списка
$list_friends_array_new=explode("#", $vk_list_friends_id);
# изменения, если удалены друзья
$list_friends_array_diff_delete = count(array_diff($list_friends_array, $list_friends_array_new));
# изменения, если добавлены новые друзья
$list_friends_array_diff_add = count(array_diff($list_friends_array_new, $list_friends_array));

# находим id пользователя
$id_m_user = mysql_result(mysql_query("select id_monitoring_user from vkontakte_user_to_monitoring where id_vk_user='$id_vk_user'"), 0);
echo "id_m_user: ".$id_m_user."<br>"; 

$vk_user_in_profile = mysql_query("select id_registered_user from vkontakte_user_monitoring_in_profile where id_monitoring_user='$id_m_user'");
while ($get_vkontakte_user_in_profile = mysql_fetch_array($vk_user_in_profile)) {
$id_registered_user = "";
$id_registered_user = $get_vkontakte_user_in_profile["id_registered_user"];
echo $id_registered_user."<br>";

$num_add_del_friends_sql=mysql_query("select * from vkontakte_user_friends_change where(id_vk_user='$id_vk_user' && id_registered_user='$id_registered_user')");
# если записей нет
if (!mysql_num_rows($num_add_del_friends_sql)) {
mysql_query("insert vkontakte_user_friends_change (id_vk_user, id_registered_user, add_friends, delete_friends, num_view) values ('$id_vk_user', '$id_registered_user', '$list_friends_array_diff_add', '$list_friends_array_diff_delete', '0')");  
} else {
$num_add_del_friends=mysql_fetch_assoc($num_add_del_friends_sql);
$add_friends = $num_add_del_friends["add_friends"];
$del_friends = $num_add_del_friends["delete_friends"];
$num_view = $num_add_del_friends["num_view"];

if ($num_view == 0) {
$add_friends_new = $list_friends_array_diff_add + $add_friends;
$del_friends_new = $list_friends_array_diff_delete + $del_friends;
mysql_query("update vkontakte_user_friends_change set add_friends='$add_friends_new', delete_friends='$del_friends_new' where (id_vk_user='$id_vk_user' && id_registered_user='$id_registered_user')");
} else {
mysql_query("update vkontakte_user_friends_change set add_friends='$list_friends_array_diff_add', delete_friends='$list_friends_array_diff_delete', num_view='0' where (id_vk_user='$id_vk_user' && id_registered_user='$id_registered_user')");
}

 }
}

 }
}
# добавляем в таблицу последних обновлений по крону
mysql_query("update vkontakte_user_friends_cron set time_update='$time_add' where (id_vk_user='$id_vk_user')");
    }
   }
  }
 }
}
###################################################################################################
### конец, функция обрабатывает возвращемые ответы ################################################
###################################################################################################

###################################################################################################
### начало, выбираем токен ########################################################################
###################################################################################################
function select_token($time_now) {
# выбираем последнее минимальное значение
$select_data_token = mysql_query("select * from vkontakte_access_token where active='1' order by time_select asc limit 1");
$data_token = mysql_fetch_assoc($select_data_token);

$access_token = $data_token["token"];
$time_select = $data_token["time_select"];

$time_last_update = $time_now + rand(60, 300);

# если минимальный токен использовался менее 20 минут назад, то обрываем скрипт
if (($time_select + 1200) > $time_now) {
$access_token="none";
return $access_token;
} else {
# обновляем время задействования этого токена и возвращаем его
mysql_query("update vkontakte_access_token set time_select='$time_last_update' where (token = '$access_token')");
return $access_token;
 }
}
###################################################################################################
### конец, выбираем токен #########################################################################
###################################################################################################



# echo "https://oauth.vk.com/authorize?client_id=2648257&scope=photos,status,offline&redirect_uri=blank.html&display=page&response_type=tokhttps://oauth.vk.com/authorize?client_id=2648257&scope=photos,status,offline&redirect_uri=blank.html&display=page&response_type=token<br><br>";
# сам запрос выглядит так: https://api.vk.com/method/execute?access_token=042e5c387d4de2eee5744b23026fdf8a6f4377103d15f79fda9bd54a6a072f700a2712542cfd1c10874ce&code=return[API.friends.get%28{%22uid%22:%221176751%22}%29];

###################################################################################################
### начало, по очереди перебираем всех пользователей, для которых нужно обновлять друзей ##########
###################################################################################################

# определяем дату и время
$time_now = mktime(date("H"), date("i"), date("s"), date("m"), date("j"), date("Y"));

# находим свободный токен
$access_token = select_token($time_now);

if ((!empty($access_token)) && $access_token <> "none") {

# как часто обновлять список друзей в секундах (6 часов)
$time_update_friends =  21600;

$uid_st="";
# составляем запрос
$vkontakte_user_to_monitoring_data = mysql_query("select * from vkontakte_user_friends_cron where ( ($time_now - time_update) > $time_update_friends ) ORDER BY time_update ASC LIMIT 0,25");
while ($get_vkontakte_user_to_monitoring_data = mysql_fetch_array($vkontakte_user_to_monitoring_data)) {
$uid_st = $uid_st."API.friends.get({\"user_id\":".$get_vkontakte_user_to_monitoring_data["id_vk_user"].",\"fields\":\"screen_name,sex,photo_50,photo_200_orig,city,country,bdate\"}),";
}

# удаляем запятую
$uid_st = substr($uid_st, 0, -1);

# конечный запрос, ссылку, заносим в переменную
$url = "https://api.vk.com/method/execute?v=5.27&access_token=".$access_token."&code=return[".$uid_st."];";
echo $url;

# вызов функции получения данных
get_info_friends($url, $access_token);
}
###################################################################################################
### конец, по очереди перебираем всех пользователей, для которых нужно обновлять друзей ###########
###################################################################################################
?>
