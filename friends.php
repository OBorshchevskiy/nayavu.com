<?
# защита от флуда
include("antiddos/core/antiddos.php");

# подключение файла с настройками конфигурации
require(dirname(__FILE__)."/core/config.php");

# подключение файла с функциями
require(dirname(__FILE__)."/core/function.php");

# /////////////////////////////////////////////////////////////////////////////////////////////////////
# /// начало / проверка, с формы текущего ли сайта поступили данные ///////////////////////////////////
# /////////////////////////////////////////////////////////////////////////////////////////////////////

# получение данных с формы
if ($_SERVER['REQUEST_METHOD']=='POST') {
# проверка поступивших данных (с текущего ли домена)
if (http_referer_check()) {
# ошибка, если данные поступили не со страницы сайта
echo("Bad REFERER!");
exit;
 }
}
# \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
# \\\ конец \ проверка, с формы текущего ли сайта поступили данные
# \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\

# подключение файла осуществляющего связь с базой данных
require(dirname(__FILE__)."/core/connect.php");

# /////////////////////////////////////////////////////////////////////////////////////////////////////
# /// начало, функция получения city или country по id ////////////////////////////////////////////////
# /////////////////////////////////////////////////////////////////////////////////////////////////////
function get_city_country_by_id($url) {
# соединяемся с вконтакте и отправляем запрос ссылкой и получаем ответ
$ch = curl_init();
curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.2.13) Gecko/20101203 MRA 5.7 (build 03797) Firefox/3.6.13");
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept-Language: ru,en-us'));
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
curl_setopt($ch, CURLOPT_TIMEOUT, 20);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HEADER, 0);
$info_city_country = json_decode(curl_exec($ch), true);
curl_close($ch);
sleep(0.3);
return $info_city_country[response][0][name];
}
# \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
# \\\ конец, функция получения city или country по id \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
# \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\

# /////////////////////////////////////////////////////////////////////////////////////////////////////
# /// начало, функция получения списка друзей /////////////////////////////////////////////////////////
# /////////////////////////////////////////////////////////////////////////////////////////////////////
function get_list_friends($id_vk_user) {

# токен получаем так: https://oauth.vk.com/authorize?client_id=2648257&scope=photos,status,offline&redirect_uri=blank.html&display=page&response_type=token
$access_token='5f7043337de6fcfce66ce27b43d1903ca91aa7b313a52cad8b904621ca3a4947d0a6f0af27fe9d876be74';

# ссылка, запрос друзей
$uid_st = $uid_st."API.friends.get({\"user_id\":".$id_vk_user.",\"fields\":\"screen_name,sex,photo_50,photo_200_orig,city,country,bdate\"})";
$url_to_get_friends = "https://api.vk.com/method/execute?v=5.3&access_token=".$access_token."&code=return[".$uid_st."];";

# соединяемся с вконтакте и отправляем запрос ссылкой и получаем ответ
$ch = curl_init();
curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; rv:25.0) Gecko/20100101 Firefox/25.0");
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept-Language: ru,en-us'));
curl_setopt($ch, CURLOPT_URL, $url_to_get_friends);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
curl_setopt($ch, CURLOPT_TIMEOUT, 20);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HEADER, 0);
$info_friends = json_decode(curl_exec($ch), true);
curl_close($ch);

if ( (!$info_friends) || (!$info_friends[response][0][items][0][id]) ) {
view_message("К сожалению запрос вернул пустой ответ. Скорее всего превышен общий лимит запросов. Попробуйте позднее, через 10-15 мин.", "bad");
} else {
 
# определяем количество друзей
$friends_num = $info_friends[response][0][count];

$vk_list_friends_id = NULL;
$vk_list_friends_data = NULL;
# перебираем всех друзей
for ($m=0; $m < $friends_num; $m++) {

# присваиваем переменным для удобства работы
$id = $info_friends[response][0][items][$m][id];
$first_name = $info_friends[response][0][items][$m][first_name];
$last_name = $info_friends[response][0][items][$m][last_name];
$sex = $info_friends[response][0][items][$m][sex];
$screen_name = $info_friends[response][0][items][$m][screen_name];
$photo_50 = "update";
$photo_200_orig = "update";
$city = $info_friends[response][0][items][$m][city];
$country = $info_friends[response][0][items][$m][country];
$bdate = $info_friends[response][0][items][$m][bdate];

# начало, переводим city, country номера в названия
if ($country) {
$select_country_sql=mysql_query("select name from vkontakte_user_friends_country where(id_country='$country')");
if (mysql_num_rows($select_country_sql)) {
$country=mysql_result($select_country_sql, 0);
} else {
# получаем из вконтакте
$url_country="https://api.vk.com/method/places.getCountryById?cids=".$country;
$country_id = $country;
$country = get_city_country_by_id($url_country);
mysql_query("insert vkontakte_user_friends_country (id_country, name) values ('$country_id', '$country')");  
 }
}

if ($city) {
$select_city_sql=mysql_query("select name from vkontakte_user_friends_city where(id_city='$city')");
if (mysql_num_rows($select_city_sql)) {
$city=mysql_result($select_city_sql, 0);
} else {
# получаем из вконтакте
$url_city="https://api.vk.com/method/places.getCityById?cids=".$city;
$city_id = $city;
$city = get_city_country_by_id($url_city);
mysql_query("insert vkontakte_user_friends_city (id_city, name) values ('$city_id', '$city')");  
 }
}
# конец, переводим city, country номера в названия

$vk_list_friends_id = $vk_list_friends_id.$id."#";
$vk_list_friends_data = $vk_list_friends_data.$id."-:=".$first_name."-:=".$last_name."-:=".$sex."-:=".$screen_name."-:=".$photo_50."-:=".$photo_200_orig."-:=".$city."-:=".$country."-:=".$bdate."|#|";

# обнуляем переменные
$id = NULL; $first_name = NULL; $last_name = NULL; $sex = NULL; $screen_name = NULL; $photo_50 = NULL; $photo_200_orig = NULL; $city = NULL; $country = NULL; $bdate = NULL;
} 

# удаляем последний #
$vk_list_friends_id = substr($vk_list_friends_id, 0, -1);
# удаляем последний |#|
$vk_list_friends_data = mysql_real_escape_string(substr($vk_list_friends_data, 0, -3));

# определяем дату и время
$time_add = mktime(date("H"), date("i"), date("s"), date("m"), date("j"), date("Y"));

# проверяем есть ли уже записи
$vk_list_friends_first = mysql_result(mysql_query("select vk_list_friends_id from vkontakte_user_friends_log where (id_vk_user='$id_vk_user' && status='1')"), 0);

# если запись первая
if (!$vk_list_friends_first) {
mysql_query("insert vkontakte_user_friends_log (id_vk_user, vk_list_friends_data, vk_list_friends_id, status, time_add) values ('$id_vk_user', '$vk_list_friends_data', '$vk_list_friends_id', '1', '$time_add')");  
view_message(friends_message_first_list_get, "good");

if (!mysql_num_rows(mysql_query("select * from vkontakte_user_friends_cron where(id_vk_user='$id_vk_user')"))) {
# добавляем в таблицу последних обновлений по крону
mysql_query("insert vkontakte_user_friends_cron (id_vk_user, time_update) values ('$id_vk_user', '$time_add')");
} else {
mysql_query("update vkontakte_user_friends_cron set time_update='$time_add' where (id_vk_user='$id_vk_user')");
}

} else {
# если запись не первая, находим последнюю запись(по максимальному времени)
$vk_list_friends_max = mysql_result(mysql_query("select vk_list_friends_id from vkontakte_user_friends_log where id_vk_user='$id_vk_user' order by time_add desc limit 1"), 0);
if ($vk_list_friends_max <> $vk_list_friends_id) {
mysql_query("insert vkontakte_user_friends_log (id_vk_user, vk_list_friends_data, vk_list_friends_id, status, time_add) values ('$id_vk_user', '$vk_list_friends_data', '$vk_list_friends_id', '0', '$time_add')");  
view_message(friends_message_change_list, "good");
# добавляем в таблицу последних обновлений по крону
mysql_query("update vkontakte_user_friends_cron set time_update='$time_add' where (id_vk_user='$id_vk_user')");
  } else {
view_message(friends_message_not_change_list, "bad");
   }
  }
 }
}
# \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
# \\\ конец, функция получения списка друзей \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
# \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\



# подключение файла верхней части дизайна страницы"
include("templates/".name_template_project."/index/header.php");

# начало, если есть данные id_vk_user
if (isset($_GET['id_vk_user'])) {
# определяем id_vk_user пользователя для мониторинга
$id_vk_user = convert_post($_GET['id_vk_user'], "0");
# проверяем, существует ли такой пользователь
$monitoring_user_query=mysql_query("select * from vkontakte_user_to_monitoring where id_vk_user='$id_vk_user'");
# начало, если ссылка с id_vk_user верная, то далее
if (mysql_num_rows($monitoring_user_query)) {
$vk_user_data=mysql_fetch_assoc($monitoring_user_query);
$id_monitoring_user=$vk_user_data["id_monitoring_user"];

# удаляем запись уведомляющую о количестве добавленных или удаленных друзей
if (isset($_GET['view'])) {
if ($_GET['view'] == "yes") {
mysql_query("delete from vkontakte_user_friends_change where (id_vk_user='$id_vk_user' && id_registered_user='$id_registered_user')");
 }
}

?>
<table border="0" cellpadding="7" cellspacing="0" align="center" width="80%">
  <tr>
    <td height="33" valign="top">&nbsp;<a href="/<? echo $language_code ?>/index.html"><? echo friends_vkontakte_main_page ?></a>&nbsp;&nbsp;→&nbsp;&nbsp;<font class="title"><? echo friends_vkontakte_workspace_title ?></font></td>
  </tr>
</table>

<br>

<table width="80%" align="center" cellpadding="5" cellspacing="1" border="0">
  <tr>
    <td align="center">
<?
# если пользователь в онлайн
if (is_online(0, $id_monitoring_user)) {
$is_mobile=mysql_result(mysql_query("select online_mobile from vkontakte_user_to_monitoring where id_monitoring_user='$id_monitoring_user'"), 0);
?>
<table border="0" cellpadding="5" cellspacing="0" class="user_online"><tr><td align="center"><? echo get_avatar($vk_user_data["avatar_vk_user"]) ?><br><nobr>&nbsp;<font class="font_small"><? echo monitor_online_vk_online_now ?></font><a href="http://vk.com/id<? echo $id_vk_user ?>" target="_blank"><img src="/templates/<? echo name_template_project ?>/index/images/vk.png"></a><? if ($is_mobile==1){ ?><img title="<? echo header_index_vkontakte_is_mobile ?>" src="/templates/<? echo name_template_project ?>/index/images/mobile.png"><? } ?></nobr></td></tr></table>
<?
# если пользователь оффлайн
} else {
?>
<table border="0" cellpadding="5" cellspacing="0" class="user_offline"><tr><td align="center"><? echo get_avatar($vk_user_data["avatar_vk_user"]) ?><br><nobr>&nbsp;<font class="font_small"><? echo monitor_online_vk_offline_now ?></font><a href="http://vk.com/id<? echo $id_vk_user ?>" target="_blank"><img src="/templates/<? echo name_template_project ?>/index/images/vk.png"></a></nobr></td></tr></table>
<?
}
# выводим его ФИО
echo "<a href='/".$language_code."/monitor_online_vk/id_vk_user/".$id_vk_user."'>".$vk_user_data["fio_vk_user"]."&nbsp;→</a>"; 
?>
	</td>
  </tr>
<form action="/<? echo $language_code?>/friends/id_vk_user/<? echo $id_vk_user ?>" method="post">
  <tr>
    <td class="line_blue">&nbsp;&nbsp;<b><? echo friends_default_header ?></b></td>
  </tr>
  <tr>
    <td align="center">
      <br>
	  <input type="submit" name="submit_update_friends" value="<? echo friends_vkontakte_button_update ?>">
<?
$last_time_max_update=mysql_result(mysql_query("select time_add from vkontakte_user_friends_log where id_vk_user='$id_vk_user' order by time_add desc limit 1"), 0);
if ($last_time_max_update) {
$last_time_cron_check=mysql_result(mysql_query("select time_update from vkontakte_user_friends_cron where id_vk_user='$id_vk_user'"), 0);
?>
	  <br><font class="font_small_grey"><? echo friends_vkontakte_last_time_update ?>:&nbsp;<? echo date("d.m.Y, H:i", $last_time_max_update); ?>
	  <br><font class="font_small_grey"><? echo "последний раз проверялись друзья" ?>:&nbsp;<? echo date("d.m.Y, H:i", $last_time_cron_check); ?>
<?
}
?>
	  </font>
    </td>
  </tr>
</form>
</table>
<?

###################################################################################################
# начало, нажата кнопка обновить ##################################################################
###################################################################################################
if (($_SERVER['REQUEST_METHOD']=='POST') && (isset($_POST['submit_update_friends']))) {
# получения данных с формы
foreach($_POST as $key => $_POST['key']) {
# приведение к безопасному виду
$value=convert_post($_POST['key'], "0");
$$key=$value;
}
 
# определяем дату и время
$time_now = mktime(date("H"), date("i"), date("s"), date("m"), date("j"), date("Y"));

# выбираем последнюю дату,время обновления друзей(любых)
$last_update_sql=mysql_query("select time_update from vkontakte_user_friends_update order by time_update desc limit 1");
if (mysql_num_rows($last_update_sql)) {
# время последнего обновления
$last_update_from_db=mysql_result($last_update_sql, 0);

$how_much_time=$time_now-$last_update_from_db;
$how_wait_time=180-$how_much_time;
# если прошло более 3 минут
if ($how_much_time < 180) {
view_message("К сожалению с момента последнего общего запроса на обновление друзей (данного пользователя или любого другого) прошло менее 3 минут. Пожалуйста повторите позднее(через <u>".$how_wait_time."</u> сек.)!", "bad");
} else {
# смотрим когда последний раз обновлялись друзья для этого id_vk_user
$last_id_update_sql=mysql_query("select time_update from vkontakte_user_friends_update where (id_vk_user='$id_vk_user')");
if (!mysql_num_rows($last_id_update_sql)) {
mysql_query("insert vkontakte_user_friends_update (id_vk_user, time_update) values ('$id_vk_user', '$time_now')"); 
# получение списка друзей и обновление информации в базе
get_list_friends($id_vk_user);
} else {
$last_id_update_from_db=mysql_result($last_id_update_sql, 0);
$how_much_time=""; $how_wait_time="";
$how_much_time=$time_now-$last_id_update_from_db;
$how_wait_time=10800-$how_much_time;

if ($how_much_time < 10800) {
view_message("К сожалению с момента последнего запроса на обновление друзей данного пользователя прошло менее 3 часов. Пожалуйста повторите позднее(через <u>".$how_wait_time."</u> сек.)!", "bad");
} else {
mysql_query("update vkontakte_user_friends_update set time_update='$time_now' where (id_vk_user='$id_vk_user')");
# получение списка друзей и обновление информации в базе
get_list_friends($id_vk_user);
}

  }

 }

} else {
mysql_query("insert vkontakte_user_friends_update (id_vk_user, time_update) values ('$id_vk_user', '$time_now')");  
# получение списка друзей и обновление информации в базе
get_list_friends($id_vk_user);
}

}
###################################################################################################
# конец, нажата кнопка обновить ###################################################################
###################################################################################################

# если есть сообщения, то выводим их
if (isset($result_message)) {
foreach($result_message as $key => $value) {
view_message($value["message"], $value["class"]);
 }
}

# /////////////////////////////////////////////////////////////////////////////////////////////////////
# /// начало / вывод изменений в друзьях //////////////////////////////////////////////////////////////
# /////////////////////////////////////////////////////////////////////////////////////////////////////
$list_friends_array=NULL;
$time_update_st=NULL;

# число выводимых страниц
$num_in_page_friends = 5;
# находим число списков друзей в базе
$num_list_full_friends=mysql_num_rows(mysql_query("select * from vkontakte_user_friends_log where id_vk_user='$id_vk_user'"));

# вычисляем номер страницы
if (empty($_GET['page_friends']) || (convert_post($_GET['page_friends'], "0") <= 0)) {
$page_friends=1;
} else {
# cчитывание текущей страницы
$page_friends=(int) convert_post($_GET['page_friends'], "0");
}

# количество страниц
$pages_count_friends=ceil($num_list_full_friends / $num_in_page_friends);
# если номер страницы оказался больше количества страниц
if ($page_friends > $pages_count_friends) $page_friends = $pages_count_friends;
$start_pos_friends = ($page_friends - 1) * $num_in_page_friends;

$data_friends_sql=mysql_query("select * from vkontakte_user_friends_log where id_vk_user='$id_vk_user' order by time_add desc limit ".$start_pos_friends.", ".$num_in_page_friends);

$num_rows_record = mysql_num_rows($data_friends_sql);
if (!$num_rows_record) {
echo "<p align=\"center\"><b>Нет сведений о друзьях! Нажмите кнопку \"Обновить список\".</b></p>";
} else {

?>
<p align="center">
<a href="/<? echo $language_code?>/friends_list/id_vk_user/<? echo $id_vk_user ?>">
<img title="Просмотр полного списка друзей" src="/templates/<? echo name_template_project ?>/index/images/friends.png"><br>Полный список друзей
</a>
</p>
<?

if ($num_rows_record==1) {
echo "<p align=\"center\"><b>Загружен только полный список друзей! Пока ждите изменений в друзьях: надо нажимать 'Обновить список' или раз в 6 часов это будет сделано сайтом автоматически!</b></p>";
} else {
# поиск посетителя в таблице
$user_data_query=mysql_query("select * from user where(login='$_COOKIE[login_user]' and password='$_COOKIE[password_user]')");
$num_of_user_data=mysql_num_rows($user_data_query);
# начало, если посетитель авторизован, то устанавливаем временной пояс, как у него в профиле
if ($num_of_user_data) {
# получение данных пользователя
$user_data=mysql_fetch_assoc($user_data_query);
# определяем timezone пользователя для мониторинга
$timezone = $user_data["timezone"];
} else {
# устанавливаем временной пояс который по умолчанию
$timezone="Europe/Moscow";
}
# устанавливаем временной пояс
date_default_timezone_set($timezone);

?>
<table border="0" cellpadding="5" cellspacing="1" width="90%" align="center" class="friends_list_top">
<tr>
  <td>

<table border="0" cellpadding="5" cellspacing="1" width="90%" align="center" class="friends_list">
<tr class="friends_main_header_workspace">
  <td class="friends_main_header_workspace" align="center">Id/Короткое имя</td>
  <td class="friends_main_header_workspace" align="center">Фамилия</td>
  <td class="friends_main_header_workspace" align="center">Имя</td>
  <td class="friends_main_header_workspace" align="center">Пол</td>
  <td class="friends_main_header_workspace" align="center">Страна, Город</td>
  <td class="friends_main_header_workspace" align="center">Дата рождения</td>  
</tr>
<tr>
  <td colspan="7" height="20px"></td>
</tr>
<?
while ($get_data_friends_sql = mysql_fetch_array($data_friends_sql)) {
$list_friends=$get_data_friends_sql["vk_list_friends_id"];

# отбираем данные добавленных или удаленных пользователей
$vk_list_friends_data = $get_data_friends_sql["vk_list_friends_data"];
$vk_list_friends_data_array_full=explode("|#|", $vk_list_friends_data);
foreach ($vk_list_friends_data_array_full as $value) {
$vk_list_friends_data_array_element=explode("-:=", $value);
$vk_list_friends_data_array_id[$vk_list_friends_data_array_element[0]]=$value;
}

# вывод первого главного списка друзей
if ($list_friends_array==NULL) {
# в массив заносим id
$list_friends_array=explode("#", $list_friends);
$time_update_st=$get_data_friends_sql["time_add"];
} else {
# вывод изменений между последним и предпоследним состоянием
$list_friends_array_new=explode("#", $list_friends);
$time_update_st_new=$get_data_friends_sql["time_add"];

# изменения, если добавлены новые друзья
$list_friends_array_diff_add = array_diff($list_friends_array, $list_friends_array_new);
# изменения, если удалены друзья
$list_friends_array_diff_delete = array_diff($list_friends_array_new, $list_friends_array);

?>
<tr>
  <td colspan="6" class="friends_list_view_time_top">
<table border="0" cellpadding="2" cellspacing="0">
<tr>
  <td>&nbsp;</td>
  <td><img src="/templates/<? echo name_template_project ?>/index/images/time.png"></td>
  <td class="friends_list_view_time">Время изменения: <font style="font-size: 11px;"><? echo date("d.m.Y, H:i", $time_update_st) ?></font></td>
</tr>
</table>
  </td>   
</tr>
<?

# начало, друзья, которые добавлены
if (count($list_friends_array_diff_add)!=0) {
?>
<tr class="friends_main_add">
  <td class="friends_main_add" colspan="6"><b>Добавленные друзья:</b></td>
</tr>
<?
foreach ($list_friends_array_diff_add as $value) {
$arr_elem_friends_add = explode("-:=", $vk_list_friends_data_array_id[$value]);

# показываем правильную дату
$bdate = $arr_elem_friends_add[9];
$bdate_arr = explode(".", $bdate);

$bdate = str_pad($bdate_arr[0], 2, '0', STR_PAD_LEFT).".".str_pad($bdate_arr[1], 2, '0', STR_PAD_LEFT).".".str_pad($bdate_arr[2], 2, '0', STR_PAD_LEFT);
$bdate = str_replace(".00", "", $bdate);
$bdate = str_replace("00", "", $bdate);

?>
<tr class="friends_list_grey">
  <td class="friends_list_grey" align="center">
<?
if ($arr_elem_friends_add[4]) {
?>
<a target="_blank" href="http://vk.com/<? echo $arr_elem_friends_add[4] ?>"><? echo $arr_elem_friends_add[4] ?></a>
<?
} else {
?>
<a target="_blank" href="http://vk.com/id<? echo $arr_elem_friends_add[0] ?>">id<? echo $arr_elem_friends_add[0] ?></a>
<?
}
?>
  </td>
  <td class="friends_list_grey" align="center"><? echo $arr_elem_friends_add[2] ?></td>
  <td class="friends_list_grey" align="center"><? echo $arr_elem_friends_add[1] ?></td>
  <td class="friends_list_grey" align="center"><? if ($arr_elem_friends_add[3]==1) { echo "ж"; } else { echo "м"; } ?></td>
  <td class="friends_list_grey" align="center">
<?
if ($arr_elem_friends_add[8]) {
echo $arr_elem_friends_add[8];
if ($arr_elem_friends_add[7]) {
echo(", ");
}
}
if ($arr_elem_friends_add[7]) {
echo $arr_elem_friends_add[7];
} 
?>
  </td>
  <td class="friends_list_grey" align="center"><? echo $bdate ?></td>
</tr>
<?
$arr_elem_friends_add=NULL;
 }
}
# конец, друзья, которые добавлены

# начало, друзья, которые удалены
if (count($list_friends_array_diff_delete)!=0) {
?>
<tr class="friends_main_delete">
  <td class="friends_main_delete" colspan="6"><b>Удаленные друзья:</b></td>
</tr>
<?
foreach ($list_friends_array_diff_delete as $value) {
$arr_elem_friends_delete = explode("-:=", $vk_list_friends_data_array_id[$value]);

# показываем правильную дату
$bdate = $arr_elem_friends_delete[9];
$bdate_arr = explode(".", $bdate);

$bdate = str_pad($bdate_arr[0], 2, '0', STR_PAD_LEFT).".".str_pad($bdate_arr[1], 2, '0', STR_PAD_LEFT).".".str_pad($bdate_arr[2], 2, '0', STR_PAD_LEFT);
$bdate = str_replace(".00", "", $bdate);
$bdate = str_replace("00", "", $bdate);

?>
<tr class="friends_list_grey">
  <td class="friends_list_grey" align="center">
<?
if ($arr_elem_friends_delete[4]) {
?>
<a target="_blank" href="http://vk.com/<? echo $arr_elem_friends_delete[4] ?>"><? echo $arr_elem_friends_delete[4] ?></a>
<?
} else {
?>
<a target="_blank" href="http://vk.com/id<? echo $arr_elem_friends_delete[0] ?>">id<? echo $arr_elem_friends_delete[0] ?></a>
<?
}
?>
  </td>
  <td class="friends_list_grey" align="center"><? echo $arr_elem_friends_delete[2] ?></td>
  <td class="friends_list_grey" align="center"><? echo $arr_elem_friends_delete[1] ?></td>
  <td class="friends_list_grey" align="center"><? if ($arr_elem_friends_delete[3]==1) { echo "ж"; } else { echo "м"; } ?></td>
  <td class="friends_list_grey" align="center">
<?
if ($arr_elem_friends_delete[8]) {
echo $arr_elem_friends_delete[8];
if ($arr_elem_friends_delete[7]) {
echo(", ");
}
}
if ($arr_elem_friends_delete[7]) {
echo $arr_elem_friends_delete[7];
} 
?>
  </td>
  <td class="friends_list_grey" align="center"><? echo $bdate ?></td>
</tr>
<?
$arr_elem_friends_delete=NULL;
 }
}
# конец, друзья, которые удалены

?>
<tr>
  <td colspan="6" class="friends_end_line" height="20px"></td>
</tr>
<?

$list_friends_array=NULL;
$list_friends_array=$list_friends_array_new;
$time_update_st=$time_update_st_new;
$list_friends_array_new=NULL;
}

}
?>
</table>

<?
if ($num_list_full_friends > $num_in_page_f) {
?>
<table border="0" cellpadding="2" cellspacing="0" align="center" width="100%">
<tr>
  <td align="center" valign="top">
<font class="friends_vk_next">
<?
# составляем ЧПУ ссылку
$chpu_link = "/".$language_code."/friends/id_vk_user/".$id_vk_user."/";
page_link($page_friends, "page_friends", $num_list_full_friends, $pages_count_friends, $num_in_page_friends, $chpu_link);
?>
</font>
  </td>
</tr>
</table>
<?
}
?>

  </td>
</tr>
</table>
<?
 }
}
# \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
# \\\ конец \ вывод изменений в друзьях \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
# \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\

} else {
exit;
  }
}
# конец, если есть данные id_vk_user

# подключение файла нижней части дизайна страницы"
include("templates/".name_template_project."/index/footer.php");
?>
