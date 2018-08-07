<?
# защита от флуда
include("antiddos/core/antiddos.php");

# подключение файла с настройками конфигурации
require(dirname(__FILE__)."/core/config.php");

# подключение файла с функциями
require(dirname(__FILE__)."/core/function.php");

# /////////////////////////////////////////////////////////////////////////////////////////////////////
# /// начало / проверка, с формы текущего ли сайта поступили данные
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

# поиск посетителя в таблице
$user_data_query=mysql_query("select * from user where(login='$_COOKIE[login_user]' and password='$_COOKIE[password_user]')");
$num_of_user_data=mysql_num_rows($user_data_query);

# начало, если посетитель авторизован, то далее
if ($num_of_user_data) {

# получение данных пользователя
$user_data=mysql_fetch_assoc($user_data_query);
# определяем текущего посетителя
$id_registered_user = $user_data["id_registered_user"];
# определяем timezone пользователя для мониторинга
$timezone = $user_data["timezone"];

} else {

# отправка данных временной зоны для неавторизованного посетителя
if (($_SERVER['REQUEST_METHOD']=='POST') && (isset($_POST['timezone']))) {
$timezone=convert_post($_POST['timezone'], "0");
# устанавливаем cookie временного пояса
SetCookie("timezone", $timezone, time()+60*60*24*30, '/', "", 0, true);
 } else {
if (isset($_COOKIE["timezone"])) {
# устанавливаем временной пояс сохраненный в cookie
$timezone=$_COOKIE["timezone"];
} else {
# устанавливаем временной пояс который по умолчанию
$timezone="Europe/Moscow";
  }
 }
}
# конец, если посетитель авторизован, то далее

# устанавливаем временной пояс
date_default_timezone_set($timezone);

###################################################################################################
###################################################################################################
### начало, получение данных, вид отображаемой статистики #########################################
###################################################################################################
###################################################################################################
if (($_SERVER['REQUEST_METHOD']=='POST') && (isset($_POST['stat_view']))) {
$stat_view=convert_post($_POST['stat_view'], "0");
} else {
$stat_view = "all";
}
###################################################################################################
###################################################################################################
### конец, получение данных, вид отображаемой статистики ##########################################
###################################################################################################
###################################################################################################

###################################################################################################
###################################################################################################
# начало, получение данных, число статусов на странице ############################################
###################################################################################################
###################################################################################################
if (($_SERVER['REQUEST_METHOD']=='POST') && (isset($_POST['num_status']))) {

$num_status=convert_post($_POST['num_status'], "0");
$id_monitoring_user_status=convert_post($_POST['id_monitoring_user_status'], "0");

$auth_and_in_profile = false;
# если посетитель авторизован
if ($num_of_user_data) {
# если пользователь добавлен в профиль посетителя
if (mysql_num_rows(mysql_query("select * from vkontakte_user_monitoring_in_profile where (id_registered_user='$id_registered_user' && id_monitoring_user='$id_monitoring_user_status')"))) {
$auth_and_in_profile=true;
 }
}

# если посетитель авторизован и этот пользователь у него в профиле
if ($auth_and_in_profile == true) {
# обновляем данные в таблице - число статусов на странице
mysql_query("update vkontakte_user_monitoring_in_profile set num_status='$num_status' where (id_registered_user='$id_registered_user' && id_monitoring_user='$id_monitoring_user_status')");
 }
 
} else {

# если посетитель авторизован
if ($num_of_user_data) {

if (isset($_GET['id_vk_user'])) {
# определяем id_vk_user пользователя для мониторинга
$id_vk_user = convert_post($_GET['id_vk_user'], "0");
$select_id_monitoring_user = mysql_query("select id_monitoring_user from vkontakte_user_to_monitoring where id_vk_user='$id_vk_user'");
if (mysql_num_rows($select_id_monitoring_user)) {

$id_monitoring_user_status=mysql_result($select_id_monitoring_user, 0);

if (mysql_num_rows(mysql_query("select * from vkontakte_user_monitoring_in_profile where (id_registered_user='$id_registered_user' && id_monitoring_user='$id_monitoring_user_status')"))) {
# выбираем из базы данных num_status
$num_status=mysql_result(mysql_query("select num_status from vkontakte_user_monitoring_in_profile where (id_registered_user='$id_registered_user' && id_monitoring_user='$id_monitoring_user_status')"), 0);
} else {
$num_status = 10;
}

} else {
$num_status = 10;
}

} else {
$num_status = 10;
}

 } else {
$num_status = 10;
 }
}
###################################################################################################
###################################################################################################
# конец, получение данных, число статусов на странице #############################################
###################################################################################################
###################################################################################################

###################################################################################################
###################################################################################################
### начало, принимаем данные для выбора фильтра даты ##############################################
###################################################################################################
###################################################################################################
if (($_SERVER['REQUEST_METHOD']=='POST') && (isset($_POST['submit_filter']))) {
# получения данных с формы
foreach($_POST as $key => $_POST['key']) {
# приведение к безопасному виду
$value=convert_post($_POST['key'], "0");
$$key=$value;
}

# проверяем даты
if ((!checkdate($month_from, $day_from, $year_from)) || (!checkdate($month_to, $day_to, $year_to))) {
# выдаем сообщение о некорректных данных
$result_message[]=array("message" => monitor_online_vk_date_error, "class" => bad);
} else {
# проверяем время
if (($hour_from >= 0 && $hour_from <= 23 && $min_from >= 0 && $min_from <= 59 && $sec_from >= 0 && $sec_from <= 59) &&
    ($hour_to >= 0 && $hour_to <= 23 && $min_to >= 0 && $min_to <= 59 && $sec_to >= 0 && $sec_to <= 59)) {
# переводим в unix формат и проверяем
if ((!@$date_from = mktime($hour_from, $min_from, $sec_from, $month_from, $day_from, $year_from)) ||
   (!@$date_to = mktime($hour_to, $min_to, $sec_to, $month_to, $day_to, $year_to))) {
# выдаем сообщение о некорректных данных
$result_message[]=array("message" => monitor_online_vk_date_error, "class" => bad);
    } else {
if ($date_from > $date_to) {
# выдаем сообщение о некорректных данных
$result_message[]=array("message" => monitor_online_vk_date_error, "class" => bad);
} else {
# только если посетитель авторизован
if ($num_of_user_data) {
# обновляем данные
mysql_query("update vkontakte_user_monitoring_in_profile set time_filter_from='$date_from', time_filter_to='$date_to' where (id_registered_user='$id_registered_user' && id_monitoring_user='$id_monitoring_user')");
} else {
# запоминаем в cookie данные date_from и date_to для неавторизованного пользователя
SetCookie("date_from", $date_from, time()+60*60*24*30, '/', "", 0, true);
SetCookie("date_to", $date_to, time()+60*60*24*30, '/', "", 0, true);
}
    }
   }
  } else {
# выдаем сообщение о некорректных данных
$result_message[]=array("message" => monitor_online_vk_date_error, "class" => bad);
  }
 }
}
###################################################################################################
###################################################################################################
### конец, принимаем данные для выбора фильтра даты ###############################################
###################################################################################################
###################################################################################################

# подключение файла верхней части дизайна страницы
require(dirname(__FILE__)."/templates/".name_template_project."/index/header.php");

?>
<script type="text/javascript" src="/ajax/titlealert/jquery.titlealert.js"></script>
<?

# если есть сообщения, то выводим их
if (isset($result_message)) {
foreach($result_message as $key => $value) {
view_message($value["message"], $value["class"]);
 }
}

###################################################################################################
###################################################################################################
### начало, выводим статистику по пользователю ####################################################
###################################################################################################
###################################################################################################
if (isset($_GET['id_vk_user'])) {

# определяем id_vk_user пользователя для мониторинга
$id_vk_user = convert_post($_GET['id_vk_user'], "0");
# определяем sound
$sound = convert_post($_GET['sound'], "0");
# проверяем, существует ли такой пользователь
$monitoring_user_query=mysql_query("select * from vkontakte_user_to_monitoring where id_vk_user='$id_vk_user'");
# начало, если ссылка с id_vk_user верная, то далее
if (mysql_num_rows($monitoring_user_query)) {

# удаляем запись уведомляющую о количестве добавленных статусов
if (isset($_GET['view'])) {
if ($_GET['view'] == "yes") {
mysql_query("delete from vkontakte_user_status_change where (id_vk_user='$id_vk_user' && id_registered_user='$id_registered_user')");
 }
}

$vk_user_data=mysql_fetch_assoc($monitoring_user_query);

# определяем id пользователя для мониторинга
$id_monitoring_user=$vk_user_data["id_monitoring_user"];
# ФИО пользователя
$fio_monitoring_user=$vk_user_data["fio_vk_user"];

if ($sound) {
# включаем или отключаем звук
$sound_on_or_off=mysql_result(mysql_query("select sound from vkontakte_user_monitoring_in_profile where (id_registered_user='$id_registered_user' && id_monitoring_user='$id_monitoring_user')"), 0);
if ($sound_on_or_off) {
# отключаем
mysql_query("update vkontakte_user_monitoring_in_profile set sound='0' where (id_registered_user='$id_registered_user' && id_monitoring_user='$id_monitoring_user')");
} else {
# включаем
mysql_query("update vkontakte_user_monitoring_in_profile set sound='1' where (id_registered_user='$id_registered_user' && id_monitoring_user='$id_monitoring_user')");
 }
}

# определяем дату и время последнего обращения
$time_add=mktime(date("H"), date("i"), date("s"), date("m"), date("j"), date("Y"));
# обновляем дату последнего обращения
mysql_query("update vkontakte_user_to_monitoring set time_last_access='$time_add' where id_monitoring_user='$id_monitoring_user'");
# увеличиваем счетчик просмотров
mysql_query("update vkontakte_user_monitoring_in_profile set num_view=num_view+1 where (id_registered_user='$id_registered_user' && id_monitoring_user='$id_monitoring_user')");

if (!isset($_POST['submit_filter'])) {

# начало, если посетитель авторизован, то далее
if ($num_of_user_data) {

# определяем time_filter_from и time_filter_to из профиля посетителя для выбранного пользователя
$vk_user_in_profile_query=mysql_query("select time_filter_from, time_filter_to, sound from vkontakte_user_monitoring_in_profile where (id_registered_user='$id_registered_user' && id_monitoring_user='$id_monitoring_user')");
# если пользователь добавлен в профиль посетителя, то можем узнать time_filter
if (mysql_num_rows($vk_user_in_profile_query)) {
$vk_user_in_profile=mysql_fetch_assoc($vk_user_in_profile_query);
# настройки звука
$sound_on_off=$vk_user_in_profile['sound'];
# производим преобразования даты
$date_from = $vk_user_in_profile['time_filter_from'];
$date_to = $vk_user_in_profile['time_filter_to'];
# если данного пользователя нет в профиле посетителя
} else {
# если есть в cookie date_from и date_to
if (isset($_COOKIE["date_from"]) && isset($_COOKIE["date_to"])) {
$date_from = $_COOKIE["date_from"];
$date_to = $_COOKIE["date_to"];
} else {
# задаем временные промежутки по умолчанию
$date_from = "1293829199";
$date_to = "1548979199";
 }
}
# если посетитель неавторизован
} else {
# если есть в cookie date_from и date_to
if (isset($_COOKIE["date_from"]) && isset($_COOKIE["date_to"])) {
$date_from = $_COOKIE["date_from"];
$date_to = $_COOKIE["date_to"];
} else {
# задаем временные промежутки
$date_from = "1293829199";
$date_to = "1548979199";
 }
}
# конец, если посетитель авторизован, то далее

$day_from = date("d", $date_from);
$month_from = date("m", $date_from);
$year_from = substr(date("Y", $date_from), 2, 2);
$hour_from = date("H", $date_from);
$min_from = date("i", $date_from);
$sec_from = date("s", $date_from);
$day_to = date("d", $date_to);
$month_to = date("m", $date_to);
$year_to = substr(date("Y", $date_to), 2, 2);
$hour_to = date("H", $date_to);
$min_to = date("i", $date_to);
$sec_to = date("s", $date_to);
}

# определяем количество записей в статистике для выбранного пользователя
if ($stat_view=="all") {
$vkontakte_user_online_query=mysql_query("select time_in_online from vkontakte_user_online_log where (id_monitoring_user='$id_monitoring_user' && time_in_online>='$date_from' && time_in_online<='$date_to') order by time_in_online");
} elseif ($stat_view=="mobile") {
$vkontakte_user_online_query=mysql_query("select time_in_online from vkontakte_user_online_log_mobile where (id_monitoring_user='$id_monitoring_user' && time_in_online>='$date_from' && time_in_online<='$date_to') order by time_in_online");
}

$num_rec_vkontakte_online=mysql_num_rows($vkontakte_user_online_query);

###################################################################################################
# начало, определяем сколько времени online провел пользователь за заданный промежуток ############
###################################################################################################
$alltime = 0;
while ($get_record_data=@mysql_fetch_array($vkontakte_user_online_query)) {
$MassTime[] = $get_record_data["time_in_online"];
}

if ($num_rec_vkontakte_online == 1) {
$alltime = $alltime + 60;
}

for ($n=0; $n < $num_rec_vkontakte_online; $n++) {
if ($n == $num_rec_vkontakte_online-1) {
$alltime = $alltime + 60;
} else {
if (($MassTime[$n] + 600) > $MassTime[$n + 1]) {
$diftime = $MassTime[$n+1] - $MassTime[$n];
$alltime = $alltime + $diftime;
  }
 }
}

$day = floor($alltime/86400);
$hours = floor(($alltime/3600)-$day*24);
$min = floor(($alltime-$hours*3600-$day*86400)/60);
$sec = $alltime-($min*60+$hours*3600+$day*86400);

$alltime = $day.' дн. '.$hours.' ч. '.$min.' мин. '.$sec.' сек.';

unset($MassTime);
$MassTime = array();
###################################################################################################
# конец, определяем сколько времени online провел пользователь за заданный промежуток #############
###################################################################################################
if ($sound_on_off) {
?>
<script type="text/javascript">
soundManager.url = '/ajax/soundmanager/swf';
soundManager.useFlashBlock = false;

setInterval(function() {

$(function() {
$.post("/ajax/online_offline.php",
{
   id_monitoring_user: "<? echo $id_monitoring_user ?>"
},
   function(data, textStatus)
     {

if (data != null)
soundManager.onready(function() {
var mySound = soundManager.createSound({
id: 'aSound',
url: '/ajax/sound.mp3'
 });
mySound.play();

$.titleAlert("<? echo $fio_monitoring_user ?> is ONLINE!", {
requireBlur:true,
stopOnFocus:false,
duration:0,
interval:500
 });

});
     }, "JSON");
});

}, 60000);
</script>
<?
}
?>

<table border="0" cellpadding="7" cellspacing="0" align="center" width="80%">
  <tr>
    <td height="55" valign="top">&nbsp;<a href="/<? echo $language_code ?>/index.html"><? echo monitor_online_vk_main_page ?></a>&nbsp;&nbsp;→&nbsp;&nbsp;<font class="title"><? echo monitor_online_vk_workspace_title ?></font></td>
  </tr>
</table>

<table border="0" cellpadding="5" cellspacing="1" align="center" width="80%" class="data_box">

  <tr>
    <td align="right" style="padding:3px">
<a title="Мониторинг изменений в друзьях" href='<? echo "/".$language_code."/friends/id_vk_user/".$id_vk_user ?>'><img src="/templates/<? echo name_template_project ?>/index/images/mfriends.png"></a>
<?
$user_in_profile=false;
# если данный человек есть в профиле
if (mysql_num_rows(mysql_query("select id_monitoring_user from vkontakte_user_monitoring_in_profile where (id_registered_user='$id_registered_user' && id_monitoring_user='$id_monitoring_user')"))) {
$user_in_profile=true;
}

# если авторизован посетитель и этот пользователь есть в профиле
if ( ($num_of_user_data) && ($user_in_profile==true) ) {
# включен или отключен звук
if ($sound_on_off) {
?>
      <a title="<? echo monitor_online_vk_sound_user_title ?>" href='<? echo "/".$language_code."/monitor_online_vk/id_vk_user/".$id_vk_user ?>/sound/do'><img src="/templates/<? echo name_template_project ?>/index/images/sound_on.png"></a>&nbsp;
<?
  } else {
?>
      <a title="<? echo monitor_online_vk_sound_user_title ?>" href='<? echo "/".$language_code."/monitor_online_vk/id_vk_user/".$id_vk_user ?>/sound/do'><img src="/templates/<? echo name_template_project ?>/index/images/sound_off.png"></a>&nbsp;
<?
 }
}

?>
      <a title="<? echo avatar_vkontakte_button_user_title ?>" href='<? echo "/".$language_code."/avatar/id_vk_user/".$id_vk_user ?>'><img src="/templates/<? echo name_template_project ?>/index/images/avatar.png"></a>&nbsp;
<?

# если авторизован посетитель и этот пользователь есть в профиле
if ( ($num_of_user_data) && ($user_in_profile==true) ) {
?>
      <a title="<? echo monitor_online_vk_add_messenger ?>" href='<? echo "/".$language_code."/add_vk_messenger/id_vk_user/".$id_vk_user ?>'><img src="/templates/<? echo name_template_project ?>/index/images/send_message.gif"></a>
      &nbsp;
<?
}
# показывать
# если открытый пользователь не в профиле и всего людей в профиле больше или равно 1 ($num_monitoring_user)
# если текущий пользователь в профиле и всего людей в профиле больше или равно 2
if ( (($user_in_profile==false) && ($num_monitoring_user >= 1)) || (($user_in_profile==true) && ($num_monitoring_user >= 2)) ) {
?>
      <a title="<? echo compare_vkontakte_button_user_title ?>" href='<? echo "/".$language_code."/compare/id_vk_user/".$id_vk_user ?>'><img src="/templates/<? echo name_template_project ?>/index/images/compare.png"></a>&nbsp;
<?
}
?>
      <a title="<? echo monitor_online_vk_delete_user_title ?>" href='<? echo "/".$language_code."/delete_vk_user/id_vk_user/".$id_vk_user ?>'><img src="/templates/<? echo name_template_project ?>/index/images/delete_user.png"></a>
     </td>
  </tr>

  <tr class="data_box">
    <td align="center">
<?
###################################################################################################
# начало, определяем в данный момент пользователь в онлайн или оффлайн ############################
###################################################################################################
if (is_online(0, $id_monitoring_user)) {
$is_mobile=mysql_result(mysql_query("select online_mobile from vkontakte_user_to_monitoring where id_monitoring_user='$id_monitoring_user'"), 0);
?>
<table border="0" cellpadding="5" cellspacing="0" class="user_online"><tr><td align="center"><? echo get_avatar($vk_user_data["avatar_vk_user"]) ?><br><nobr>&nbsp;<font class="font_small"><? echo monitor_online_vk_online_now ?></font><a href="http://vk.com/id<? echo $id_vk_user ?>" target="_blank"><img src="/templates/<? echo name_template_project ?>/index/images/vk.png"></a><? if ($is_mobile==1){ ?><img title="<? echo header_index_vkontakte_is_mobile ?>" src="/templates/<? echo name_template_project ?>/index/images/mobile.png"><? } ?></nobr></td></tr></table>
<?
} else {
?>
<table border="0" cellpadding="5" cellspacing="0" class="user_offline"><tr><td align="center"><? echo get_avatar($vk_user_data["avatar_vk_user"]) ?><br><nobr>&nbsp;<font class="font_small"><? echo monitor_online_vk_offline_now ?></font><a href="http://vk.com/id<? echo $id_vk_user ?>" target="_blank"><img src="/templates/<? echo name_template_project ?>/index/images/vk.png"></a></nobr></td></tr></table>
<?
}
###################################################################################################
# конец, определяем в данный момент пользователь в онлайн или оффлайн #############################
###################################################################################################
?>
<b><? echo $vk_user_data["fio_vk_user"] ?></b>
   	</td>
  </tr>
  <tr>
    <td><div class="showbox_filter_2" align="right"><img title="<? echo monitor_online_vk_filter_date ?>" src="/templates/<? echo name_template_project ?>/index/images/filter.png"></div></td>
  </tr>
  <tr class="data_box_blue" id="show_hide_2">
    <td align="center">
<table border="0" cellpadding="5" cellspacing="0">
<form action="/<? echo $language_code?>/monitor_online_vk/id_vk_user/<? echo $id_vk_user ?>" method="post">
  <tr>
  	<td></td>
    <td><? echo monitor_online_vk_date_day ?></td>
    <td><? echo monitor_online_vk_date_month ?></td>
    <td><? echo monitor_online_vk_date_year ?></td>
    <td><? echo monitor_online_vk_date_hour ?></td>
    <td><? echo monitor_online_vk_date_min ?></td>
    <td><? echo monitor_online_vk_date_sec ?></td>
    <td></td>
    <td><? echo monitor_online_vk_date_day ?></td>
    <td><? echo monitor_online_vk_date_month ?></td>
    <td><? echo monitor_online_vk_date_year ?></td>
    <td><? echo monitor_online_vk_date_hour ?></td>
    <td><? echo monitor_online_vk_date_min ?></td>
    <td><? echo monitor_online_vk_date_sec ?></td>
  </tr>
  <tr>
  	<td><b><? echo monitor_online_vk_date_from ?></b><input name="id_monitoring_user" type="hidden" value="<? echo $id_monitoring_user ?>"></td>
    <td><input class="filter_date" name="day_from" type="text" size="2" maxlength="2" value="<? if (isset($day_from)) { echo $day_from; } ?>"></td>
    <td><input class="filter_date" name="month_from" type="text" size="2" maxlength="2" value="<? if (isset($month_from)) { echo $month_from; } ?>"></td>
    <td><input class="filter_date" name="year_from" type="text" size="2" maxlength="2" value="<? if (isset($year_from)) { echo $year_from; } ?>"></td>
    <td><input class="filter_date" name="hour_from" type="text" size="2" maxlength="2" value="<? if (isset($hour_from)) { echo $hour_from; } ?>"></td>
    <td><input class="filter_date" name="min_from" type="text" size="2" maxlength="2" value="<? if (isset($min_from)) { echo $min_from; } ?>"></td>
    <td><input class="filter_date" name="sec_from" type="text" size="2" maxlength="2" value="<? if (isset($sec_from)) { echo $sec_from; } ?>"></td>
    <td><b><? echo monitor_online_vk_date_to ?></b></td>
    <td><input class="filter_date" name="day_to" type="text" size="2" maxlength="2" value="<? if (isset($day_to)) { echo $day_to; } ?>"></td>
    <td><input class="filter_date" name="month_to" type="text" size="2" maxlength="2" value="<? if (isset($month_to)) { echo $month_to; } ?>"></td>
    <td><input class="filter_date" name="year_to" type="text" size="2" maxlength="2" value="<? if (isset($year_to)) { echo $year_to; } ?>"></td>
    <td><input class="filter_date" name="hour_to" type="text" size="2" maxlength="2" value="<? if (isset($hour_to)) { echo $hour_to; } ?>"></td>
    <td><input class="filter_date" name="min_to" type="text" size="2" maxlength="2" value="<? if (isset($min_to)) { echo $min_to; } ?>"></td>
    <td><input class="filter_date" name="sec_to" type="text" size="2" maxlength="2" value="<? if (isset($sec_to)) { echo $sec_to; } ?>"></td>
  </tr>
  <tr>
    <td colspan="14" align="center"><input type="submit" name="submit_filter" value="<? echo monitor_online_vk_submit_filter_button ?>"></td>
  </tr>
</form>
</table>
    </td>
  </tr>
  <tr>
    <td align="center">
<p class="title_online_vk"><? echo monitor_online_vk_last_status ?></p>

<?
$num_status_query = mysql_query("select * from vkontakte_user_status_log where (id_vk_user='$id_vk_user' && time_log>='$date_from' && time_log<='$date_to') order by time_log desc");
$num_status_from_db = mysql_num_rows($num_status_query);

if (!$num_status_from_db) {
echo "<p align=\"center\"><font class=\"monitor_online_vk_status_not_data\">".monitor_online_vk_status_not_data."</font></p>";
} else {
?>
<table border="0" cellpadding="2" cellspacing="0">
<form action="/<? echo $language_code?>/monitor_online_vk/id_vk_user/<? echo $id_vk_user?>" method="post" id="num_status">
<input name="id_monitoring_user_status" type="hidden" value="<? echo $id_monitoring_user ?>">
<tr>
  <td>
<img title="<? echo header_index_vkontakte_num_in_page ?>" src="/templates/<? echo name_template_project ?>/index/images/in_page.png">
  </td>
  <td>
<select title="<? echo monitor_online_vk_num_in_page ?>" class="num_in_page" name="num_status" onchange='document.forms["num_status"].submit()'>
<? if ($num_status==5) { ?><option selected value="5">5</option><? } else { ?> <option value="5">5</option> <? } ?>
<? if ($num_status==10) { ?><option selected value="10">10</option><? } else { ?> <option value="10">10</option> <? } ?>
<? if ($num_status==20) { ?><option selected value="20">20</option><? } else { ?> <option value="20">20</option> <? } ?>
<? if ($num_status==30) { ?><option selected value="30">30</option><? } else { ?> <option value="30">30</option> <? } ?>
<? if ($num_status==40) { ?><option selected value="40">40</option><? } else { ?> <option value="40">40</option> <? } ?>
<? if ($num_status==50) { ?><option selected value="50">50</option><? } else { ?> <option value="50">50</option> <? } ?>
<? if ($num_status==60) { ?><option selected value="60">60</option><? } else { ?> <option value="60">60</option> <? } ?>
<? if ($num_status==70) { ?><option selected value="70">70</option><? } else { ?> <option value="70">70</option> <? } ?>
<? if ($num_status==80) { ?><option selected value="80">80</option><? } else { ?> <option value="80">80</option> <? } ?>
<? if ($num_status==90) { ?><option selected value="90">90</option><? } else { ?> <option value="90">90</option> <? } ?>
<? if ($num_status==100) { ?><option selected value="100">100</option><? } else { ?> <option value="100">100</option> <? } ?>
<? if ($num_status==200) { ?><option selected value="200">200</option><? } else { ?> <option value="200">200</option> <? } ?>
</select>
  </td>
</tr>
</form>
</table>

<?
# вычисляем номер страницы
if (empty($_GET['page_s_user']) || (convert_post($_GET['page_s_user'], "0") <= 0)) {
$page_s_user=1;
} else {
# cчитывание текущей страницы
$page_s_user=(int) convert_post($_GET['page_s_user'], "0");
}

# количество страниц
$pages_count_s_user=ceil($num_status_from_db / $num_status);
# если номер страницы оказался больше количества страниц
if ($page_s_user > $pages_count_s_user) $page_s_user = $pages_count_s_user;
$start_pos_s_user = ($page_s_user - 1) * $num_status;

$status_query = mysql_query("select * from vkontakte_user_status_log where (id_vk_user='$id_vk_user' && time_log>='$date_from' && time_log<='$date_to') order by time_log desc limit ".$start_pos_s_user.", ".$num_status);

$number=0;
while ($get_record_data=mysql_fetch_array($status_query)) {
$get_data_from_status[$number]["text"] = $get_record_data["text"];
$get_data_from_status[$number]["audio"] = $get_record_data["audio"];
$get_data_from_status[$number]["id_audio"] = $get_record_data["id_audio"];
$get_data_from_status[$number]["time_log"] = $get_record_data["time_log"];
$get_data_from_status[$number]["time_log_formatted"] = date("d", $get_record_data["time_log"]).".".date("m", $get_record_data["time_log"]).".".date("Y", $get_record_data["time_log"]);;
$get_data_from_status[$number]["utf8"] = $get_record_data["utf8"];
$day_month_year_arr[$number] = date("d", $get_record_data["time_log"]).".".date("m", $get_record_data["time_log"]).".".date("Y", $get_record_data["time_log"]);
$number++;
}

# массив дней недели
$DaysOfWeek = array("воскресенье", "понедельник", "вторник", "среда", "четверг", "пятница", "суббота");
# избавляемся от повторных элементов
$day_month_year_un_arr=array_unique($day_month_year_arr);

echo "<table width=\"90%\" border=\"0\" cellpadding=\"4\" cellspacing=\"1\">";
# перебираем по порядку массив уникальных день.месяц.год
foreach ($day_month_year_un_arr as $elem_un_arr) {

# начало, узнаем день недели
$date_formatted = explode(".", $elem_un_arr);
$mkt_date = mktime(0, 0, 0, $date_formatted[1], $date_formatted[0], $date_formatted[2]);
$arr_date = getdate($mkt_date);
$day_week = $DaysOfWeek[$arr_date['wday']];
# конец, узнаем день недели

echo "<tr>";
echo "<td width=\"5%\" class=\"status_day_week\" valign=\"top\" align=\"center\"><font class=\"last_status_small\"><b>".$day_week."</b></font></td><td valign=\"top\" align=\"center\" width=\"10%\" class=\"status_time\"><nobr><font class=\"last_status_small\"><b>".$elem_un_arr."</b></font></nobr></td><td style=\"padding: 0px; margin: 0px;\" valign=\"top\"><table width=\"100%\" border=\"0\" cellpadding=\"3\" cellspacing=\"1\">";

foreach ($get_data_from_status as $data_status) {
if ($elem_un_arr == $data_status["time_log_formatted"]) {

# начало, текст статуса
if ($data_status["utf8"] == 1) {
$text_status_from_db = $data_status["text"];
$text_status = "";
$text_code_array = explode("|", $text_status_from_db);
for ($a=0; $a < count($text_code_array); $a++) {
if ($text_code_array[$a]) {
$text_status = $text_status."&#".$text_code_array[$a].";";
  }
 }
} else {
$text_status = $data_status["text"];
}
# конец, текст статуса

if ($data_status["audio"] && $data_status["id_audio"]) {
echo "<tr><td valign=\"top\" class=\"status_time_full\" align=\"center\" width=\"7%\"><nobr>".date("H:i", $data_status["time_log"])."</nobr></td><td class=\"status_audio_text\" valign=\"top\"><a href='http://$url/get_vk_mp3.php?url=".$data_status["audio"]."' class=\"sm2_button\">Play</a>&nbsp;<font class=\"last_status_small\">".$text_status."</font></td></tr>";
} elseif ($data_status["id_audio"]) {
echo "<tr><td valign=\"top\" class=\"status_time_full\" align=\"center\" width=\"7%\"><nobr>".date("H:i", $data_status["time_log"])."</nobr></td><td class=\"status_audio_text\" valign=\"top\"><a target=\"_blank\" href='http://vk.com/audio?q=".$text_status."'><span class=\"play-icon\"><span></span></span></a><font class=\"last_status_small\">".$text_status."</font></td></tr>";
} else {
echo "<tr><td valign=\"top\" class=\"status_time_full\" align=\"center\" width=\"7%\"><nobr>".date("H:i", $data_status["time_log"])."</nobr></td><td class=\"status_audio_text\" valign=\"top\"><font class=\"last_status_small\">".$text_status."</font></td></tr>";
}


 }
}

echo "</table></td></tr>";
}
echo "</table>";




if ($num_status_from_db > $num_status) {
?>
<div align="center">
<font class="monitor_online_vk_next">
<?
# составляем ЧПУ ссылку
$chpu_link = "/".$language_code."/monitor_online_vk/id_vk_user/".$id_vk_user."/";
page_link($page_s_user, "page_s_user", $num_status_from_db, $pages_count_s_user, $num_status, $chpu_link);
?>
</font>
</div>
<?
 }
}
?>

<p class="title_online_vk"><? echo monitor_online_vk_info ?></p>
<?
$in_profile=false;
$nump=0;
# сколько человек наблюдают за данным пользователем
$num_profiles_to_mon_query = mysql_query("select id_registered_user from vkontakte_user_monitoring_in_profile where id_monitoring_user='$id_monitoring_user'");
if ($num_profiles_to_mon = mysql_num_rows($num_profiles_to_mon_query)) {
while ($get_id_register_user = mysql_fetch_array($num_profiles_to_mon_query)) {
if ($get_id_register_user["id_registered_user"] == $id_registered_user) {
$in_profile=true;
 }
# счетчик количества профилей у которых добавлен данный пользователь
$nump=$nump+1;
}

# для того чтобы написать "вы" и еще nump человек
if (($in_profile==true) && ($nump > 1)) {
$nump=$nump-1;
 }
}

echo "<font class=\"monitor_online_vk_info\">";
if (!$nump) {
echo monitor_online_vk_not_in_profile;
} elseif (($in_profile==true) && ($num_profiles_to_mon > 1)) {
echo monitor_online_vk_in_profile_and_you_1." ".$nump." ".monitor_online_vk_in_profile_and_you_2;
} elseif (($in_profile==true) && ($num_profiles_to_mon==1)) {
echo monitor_online_vk_in_you_profile;
}
echo "</font>";

# выводим дату регистрации первой записи online
$vkontakte_user_first_query=mysql_query("select time_in_online from vkontakte_user_online_log where (id_monitoring_user='$id_monitoring_user') limit 1");
if (mysql_num_rows($vkontakte_user_first_query)) {
$vkontakte_user_first=mysql_result($vkontakte_user_first_query, 0);
} else {$vkontakte_user_first="";
}
if ($vkontakte_user_first) {
?>
<table border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td><font class="monitor_online_vk_info"><? echo monitor_online_vk_online_first_record ?>:&nbsp;</font></td>
    <td><font class="monitor_online_vk_info"><? echo date("d.m.Y", $vkontakte_user_first) ?></font></td>
  </tr>
</table>
<?
}
# выводим дату регистрации вконтакте
?>
<table border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td><font class="monitor_online_vk_info"><? echo monitor_online_vk_reg_date ?>:&nbsp;</font></td>
    <td>
<font class="monitor_online_vk_info">
<?
if ( ($vk_user_data["reg_date"] == "none") || !$vk_user_data["reg_date"] ) {
echo monitor_online_vk_reg_date_not_detect;
} else {
echo $vk_user_data["reg_date"];
}
?>
</font>
    </td>
  </tr>
</table>

<p class="title_online_vk"><? echo monitor_online_vk_online ?></p>
    </td>
  </tr>  
<?
# если посетитель неавторизован, выдаем возможность изменения часового пояса
if (!$num_of_user_data) {
?>
  <tr>
    <td align="center">
<table border="0" cellpadding="5" cellspacing="0">
<form action="/<? echo $language_code?>/monitor_online_vk/id_vk_user/<? echo $id_vk_user ?>" method="post" id="form_timezone">
  <tr>
    <td><? echo monitor_online_vk_timezone ?></td>
    <td>
<select name="timezone" onchange='document.forms["form_timezone"].submit()'>
<?
$timezone_identifiers = DateTimeZone::listIdentifiers();
foreach( $timezone_identifiers as $value ){
if ( preg_match( '/^(Africa|America|Antarctica|Arctic|Asia|Atlantic|Australia|Europe|Indian|Pacific|Others)\//', $value ) ) {
$ex=explode("/",$value);
if ($continent!=$ex[0]){
if ($continent!="") echo '</optgroup>';
echo '<optgroup label="'.$ex[0].'">';
}
$city=$ex[1];
$continent=$ex[0];

# +0400
$timez_sm_a=substr(date("O"), 0, 3);
$timez_sm_b=substr(date("O"), 3, 2);
$timez_sm=$timez_sm_a.":".$timez_sm_b;
if (strpos($timez_sm, "10")) {
$timez_sm_int=str_replace("0", "", $timez_sm_b);
$timez_sm_int=$timez_sm_a.$timez_sm_int;
} else {
if ($timez_sm_b > 0) {
$timez_sm_int=str_replace("0", "", $timez_sm_a.".".$timez_sm_b);
  } else {
$timez_sm_int=str_replace("0", "", $timez_sm_a.$timez_sm_b);
 }
}
if (strlen($timez_sm_int)==1) {
$timez_sm_int=0;
}

if ( (strstr($timezone, $value)) || ($timezone == $value) ) {
echo '<option selected value="'.$value.'">('.date("d.m.Y, H:i").') '.$city.' ('.$timez_sm_int.')</option>';
} else {
echo '<option value="'.$value.'">('.date("d.m.Y, H:i").') '.$city.' ('.$timez_sm_int.')</option>';
  }
 }
}
?>
</optgroup>
</select>
    </td>
  </tr>
</form>
</table>
    </td>
  </tr>
<?
}
?>
  <tr id="show_hide_3">
    <td align="center" height="50" valign="bottom"><b><? echo monitor_online_vk_all_time_online ?></b>&nbsp;<u><? echo $alltime ?></u></td>
  </tr>
  <tr>
    <td align="center" valign="bottom">
<table border="0" cellpadding="2" cellspacing="0" align="center">
<form action="" method="post" id="stat_view">
<tr>
  <td><b><? echo monitor_online_vk_view_title ?>:</b>
  </td>
  <td>
<select class="stat_view" name="stat_view" onchange='document.forms["stat_view"].submit()'>
<? if ($stat_view=="all") { ?><option selected value="all"><? echo monitor_online_vk_all_view ?></option><? } else { ?> <option value="all"><? echo monitor_online_vk_all_view ?></option> <? } ?>
<? if ($stat_view=="mobile") { ?><option selected value="mobile"><? echo monitor_online_vk_mobile_view ?></option><? } else { ?> <option value="mobile"><? echo monitor_online_vk_mobile_view ?></option> <? } ?>
</select>
   </td>
</tr>
</form>
</table>
	</td>
  </tr>
<?
# начало, проверка наличия записей
if ($num_rec_vkontakte_online) {
?>
  <tr>
    <td>
<?
###################################################################################################
# начало, функция, узнаем последний или первый элемент time_in_online #############################
###################################################################################################
function next_or_prev_exist($id_monitoring_user, $date_from, $date_to, $where_record, $first_record, $stat_view) {
//$next_or_prev_query=mysql_query("select max(time_in_online) from vkontakte_user_online_log where (id_monitoring_user='$id_monitoring_user' && time_in_online>='$date_from' && time_in_online<='$date_to' $where_record)");

if ($stat_view == "all") {
$next_or_prev_query=mysql_query("select time_in_online from vkontakte_user_online_log where (id_monitoring_user='$id_monitoring_user' && time_in_online>='$date_from' && time_in_online<='$date_to' $where_record) order by time_in_online desc limit 1");
} elseif ($stat_view == "mobile") {
$next_or_prev_query=mysql_query("select time_in_online from vkontakte_user_online_log_mobile where (id_monitoring_user='$id_monitoring_user' && time_in_online>='$date_from' && time_in_online<='$date_to' $where_record) order by time_in_online desc limit 1");
}

if ($first_record) {
# если элемент является первым, то prev не выводим
if ((mysql_result($next_or_prev_query, 0))==$first_record) {
return false;
} else {
return true;
 }
}

# если есть еще записи после последнего элемента на странице, то next выводим
if ($num_col=mysql_num_rows($next_or_prev_query)) {
return true;
} else {
return false;
 }
}
###################################################################################################
# конец, функция, узнаем последний или первый элемент time_in_online ##############################
###################################################################################################

###################################################################################################
# начало, функция, заносим все записи онлайн лога в массив  #######################################
###################################################################################################
function add_all_online_log_in_mass($device, $id_monitoring_user, $date_from, $date_to, $where_record, $from_record, $num_record, $stat_view) {
# запрос на выборку логов "от" и "сколько"
if ($stat_view=="all") {

if ($device=="pc") {
$select_record_query=mysql_query("select time_in_online from vkontakte_user_online_log where (id_monitoring_user='$id_monitoring_user' && time_in_online>='$date_from' && time_in_online<='$date_to' $where_record) order by time_in_online desc limit ".$from_record.", ".$num_record);
} elseif($device=="mobile") {
$select_record_query=mysql_query("select time_in_online from vkontakte_user_online_log_mobile where (id_monitoring_user='$id_monitoring_user' && time_in_online>='$date_from' && time_in_online<='$date_to' $where_record) order by time_in_online desc limit ".$from_record.", ".$num_record);
}

} elseif ($stat_view=="mobile") {
$select_record_query=mysql_query("select time_in_online from vkontakte_user_online_log_mobile where (id_monitoring_user='$id_monitoring_user' && time_in_online>='$date_from' && time_in_online<='$date_to' $where_record) order by time_in_online desc limit ".$from_record.", ".$num_record);
}

# заносим в массив все отобранные записи
while ($get_record_time_in_online=mysql_fetch_array($select_record_query)) {
$mass_time_in_online[]=$get_record_time_in_online["time_in_online"];
 }
return $mass_time_in_online;
}
###################################################################################################
# конец, функция, заносим все записи онлайн лога в массив #########################################
###################################################################################################

###################################################################################################
# начало, функция, объединяем временные промежутки в массив  ######################################
###################################################################################################
function merge_time_part($mass_time_in_online) {
# сортируем в обратном порядке для правильной работы алгоритм слияния дат и времени
$mass_time_in_online=array_reverse($mass_time_in_online);
# самый первый элемент массива
$first_time=$mass_time_in_online[0];

# начало, перебираем в цикле все значения
for ($i=0; $i < count($mass_time_in_online); $i++) {

# начало, если элемент массива последний
if ($i==(count($mass_time_in_online)-1)) {
# если нет временных промежутков, т.е пользователь зашел и вышел
if ($first_time==$mass_time_in_online[$i]) {
$mass_edit_time_in_online[]=date("d.m.Y, H:i:s", $mass_time_in_online[$i]);
$mass_edit_time_in_online_not_formatted_prev[]=$mass_time_in_online[$i];
$mass_edit_time_in_online_not_formatted_next[]=$mass_time_in_online[$i];
} else {
# если дни двух дат равны, то объединяем их в одну
if (date("d.m.Y", $first_time) == date("d.m.Y", $mass_time_in_online[$i])) {
$mass_edit_time_in_online[]=date("d.m.Y, H:i:s", $first_time)." - ".date("H:i:s", $mass_time_in_online[$i]);
$mass_edit_time_in_online_not_formatted_prev[]=$mass_time_in_online[$i];
$mass_edit_time_in_online_not_formatted_next[]=$first_time;
} else {
$mass_edit_time_in_online[]=date("d.m.Y, H:i:s", $first_time)." - ".date("d.m.Y, H:i:s", $mass_time_in_online[$i]);
$mass_edit_time_in_online_not_formatted_prev[]=$mass_time_in_online[$i];
$mass_edit_time_in_online_not_formatted_next[]=$first_time;
  }
 }
} else {
# если разница между элементами более 4 минут
if (($mass_time_in_online[$i] + 240) < $mass_time_in_online[$i+1]) {
# если нет временных промежутков, т.е пользователь зашел и вышел
if ($first_time==$mass_time_in_online[$i]) {
$mass_edit_time_in_online[]=date("d.m.Y, H:i:s", $mass_time_in_online[$i]);
$mass_edit_time_in_online_not_formatted_prev[]=$mass_time_in_online[$i];
$mass_edit_time_in_online_not_formatted_next[]=$mass_time_in_online[$i];
} else {
# если дни двух дат равны, то объединяем их в одну
if (date("d.m.Y", $first_time) == date("d.m.Y", $mass_time_in_online[$i])) {
$mass_edit_time_in_online[]=date("d.m.Y, H:i:s", $first_time)." - ".date("H:i:s", $mass_time_in_online[$i]);
$mass_edit_time_in_online_not_formatted_prev[]=$mass_time_in_online[$i];
$mass_edit_time_in_online_not_formatted_next[]=$first_time;
} else {
$mass_edit_time_in_online[]=date("d.m.Y, H:i:s", $first_time)." - ".date("d.m.Y, H:i:s", $mass_time_in_online[$i]);
$mass_edit_time_in_online_not_formatted_prev[]=$mass_time_in_online[$i];
$mass_edit_time_in_online_not_formatted_next[]=$first_time;
 }
}

# начало следующего временного промежутка
$first_time=$mass_time_in_online[$i+1];
  }
 }
# конец, если элемент массива последний
}
# конец, перебираем в цикле все значения

# сортируем в обратном порядке, т.к вывод дат идет сверху вниз
$mass_edit_time_in_online=array_reverse($mass_edit_time_in_online);
$mass_edit_time_in_online_not_formatted_prev=array_reverse($mass_edit_time_in_online_not_formatted_prev);
$mass_edit_time_in_online_not_formatted_next=array_reverse($mass_edit_time_in_online_not_formatted_next);
# заносим в многомерный массив полученные значения
$mass_edit_time_in_online_all[0][0]=$mass_edit_time_in_online_not_formatted_prev;
$mass_edit_time_in_online_all[0][1]=$mass_edit_time_in_online_not_formatted_next;
$mass_edit_time_in_online_all[0][2]=$mass_edit_time_in_online;

return $mass_edit_time_in_online_all;
}
###################################################################################################
# конец, функция, объединяем временные промежутки в массив  #######################################
###################################################################################################

###################################################################################################
# начало, формируем правильные запросы на выборку в соответствии с next и prev ####################
###################################################################################################
if (convert_post($_GET['next'], "0") == true) {
$where_record=convert_post($_GET['page'], "0");
$where_record="&& time_in_online < ".$where_record;
} elseif(convert_post($_GET['prev'], "0") == true) {
$where_record=convert_post($_GET['page'], "0");
$where_record="&& time_in_online > ".$where_record;
} else {
$where_record = "";
}
###################################################################################################
# конец, формируем правильные запросы на выборку в соответствии с next и prev #####################
###################################################################################################

###################################################################################################
# начало, набираем 30 временных промежутков в массив ##############################################
###################################################################################################
$from_record = 0;
$num_record = 30;
$num_mass_time_in_online = 0;
$mass_edit_time_in_online = null;

while (count($mass_edit_time_in_online) < 30) {

# получаем массив с количеством записей и со всеми записями онлайн лога
$mass_time_in_online=add_all_online_log_in_mass("pc", $id_monitoring_user, $date_from, $date_to, $where_record, $from_record, $num_record, $stat_view);
$mass_time_in_online_mobile=add_all_online_log_in_mass("mobile", $id_monitoring_user, $date_from, $date_to, $where_record, $from_record, $num_record, $stat_view);

# получаем массив с объединенными временными промежутками
$mass_edit_time_in_online_all=merge_time_part($mass_time_in_online);
$mass_edit_time_in_online_all_mobile=merge_time_part($mass_time_in_online_mobile);

# массив отформатированных объединенных временных промежутков
$mass_edit_time_in_online=$mass_edit_time_in_online_all[0][2];
$mass_edit_time_in_online_mobile=$mass_edit_time_in_online_all_mobile[0][2];

# увеличиваем количество отбираемых элементов
$num_record = $num_record + 10000;
# если уже больше нет записей удовлетворяющих заданным условиям, прерываем цикл
if ( (count($mass_time_in_online)==$num_rec_vkontakte_online) || (!count($mass_time_in_online)) || (count($mass_time_in_online) == $num_mass_time_in_online) ) {
break;
 }

# запоминаем сколько было записей в массиве
$num_mass_time_in_online = count($mass_time_in_online);
}

# оставляем в массиве только 30 записей
if (count($mass_edit_time_in_online) > 30) {
$mass_edit_time_in_online=array_slice($mass_edit_time_in_online, 0, 30);
}
if (count($mass_edit_time_in_online_mobile) > 30) {
$mass_edit_time_in_online_mobile=array_slice($mass_edit_time_in_online_mobile, 0, 30);
}
###################################################################################################
# конец, набираем 30 временных промежутков в массив ###############################################
###################################################################################################

# находим первую или последнюю запись от которой отталкиваться при next или prev
$mass_edit_time_in_online_not_formatted_prev = $mass_edit_time_in_online_all[0][0];
$mass_edit_time_in_online_not_formatted_next = $mass_edit_time_in_online_all[0][1];
# первая запись
$first_record = $mass_edit_time_in_online_not_formatted_prev[0];
# последняя запись
$end_record = $mass_edit_time_in_online_not_formatted_next[count($mass_edit_time_in_online)-1];

# начало, если есть записи в массиве временных промежутков
if (count($mass_edit_time_in_online)) {
###################################################################################################
# начало, подсчитываем за сколько дней набралось записей ##########################################
###################################################################################################
for ($i=0; $i < count($mass_edit_time_in_online); $i++) {
$mass_date=explode(",", $mass_edit_time_in_online[$i]);
$mass_day_date[]=$mass_date[0];
}
###################################################################################################
# конец, подсчитываем за сколько дней набралось записей ###########################################
###################################################################################################

# массив со всем элементами
$mass_day_date_all=$mass_day_date;
# избавляемся от повторных элементов
$mass_day_date=array_unique($mass_day_date);

###################################################################################################
# начало, вывод по дням временных промежутков #####################################################
###################################################################################################
foreach ($mass_day_date as $element) {
$view_element=false;
$view_element_b=false;
$num=0;
# подсчитываем сколько раз повторяется текущая дата в массиве со всеми элементами
$num_elements=num_element_in_mass($element, $mass_day_date_all);
?>
<table border="0" cellpadding="5" cellspacing="0" width="90%" align="center">
<?
# выводим временные промежутки за данный день
for ($n=0; $n < count($mass_edit_time_in_online); $n++) {
if ($element == $mass_day_date_all[$n]) {
$num++;
?>
  <tr>
    <td width="20%" class="data_box_dark" align="center">
<?
if ($view_element_b==false) {
unset($DaysOfWeek);
$DaysOfWeek = array();
unset($date_array);
$date_array = array();
unset($arr_date);
$arr_date = array();
$DaysOfWeek = array("воскресенье", "понедельник", "вторник    ", "среда      ", "четверг    ", "пятница    ", "суббота    ");
$date_array = explode(".", $element);
$arr_date = getdate(mktime(0, 0, 0, $date_array[1], $date_array[0], $date_array[2]));
echo "<p>".$DaysOfWeek[$arr_date['wday']]."</p>";
$view_element_b=true;
} else {
echo "&nbsp;";
}
?>
    </td>
    <td width="10%" class="data_box_green" align="center">
<?
if ($view_element==false) {
echo $element;
$view_element=true;
} else {
echo "&nbsp;";
}
?>
    </td>
<?
# начало, подсчитываем сколько времени провел пользователь за одну сессию

if  (substr_count($mass_edit_time_in_online[$n], " - ") == 0) {
$session = "менее 2-x минут";
} else {
# если не было перехода на следующий день
if (substr_count($mass_edit_time_in_online[$n], ",") == 1) {
# удаляем дату
$only_time=null;
$only_time = explode(", ", $mass_edit_time_in_online[$n]);
# разбиваем число на день, месяц, год
$date_mass_start=explode(".", $only_time[0]);
$date_mass_end=explode(".", $only_time[0]);
# получаем время "от" - время "до"
$time_mass=null;
$time_mass = explode(" - ", $only_time[1]);
} elseif(substr_count($mass_edit_time_in_online[$n], ",") == 2) {
# разбиваем на две даты
$two_date = explode(" - ", $mass_edit_time_in_online[$n]);
# дата и время "от"
$date_time_start=explode(", ", $two_date[0]);
# дата и время "до"
$date_time_end=explode(", ", $two_date[1]);

# получаем время "от" - время "до"
$time_mass=null;
$time_mass[0] = $date_time_start[1];
$time_mass[1] = $date_time_end[1];

# получаем дату "от" - дату "до"
$date_mass=null;
$date_mass_start=explode(".", $date_time_start[0]);
$date_mass_end=explode(".", $date_time_end[0]);
}

# час, минута, секунда время "от"
$time_start=null;
$time_start=explode(":", $time_mass[0]);
# час
$hour_start=$time_start[0];
# минут
$min_start=$time_start[1];
# секунд
$sec_start=$time_start[2];

# час, минута, секунда время "до"
$time_end=null;
$time_end=explode(":", $time_mass[1]);
# час
$hour_end=$time_end[0];
# минут
$min_end=$time_end[1];
# секунд
$sec_end=$time_end[2];

$how_much_sec_session = ( mktime($hour_end, $min_end, $sec_end, $date_mass_end[1], $date_mass_end[0], $date_mass_end[2]) - mktime($hour_start, $min_start, $sec_start, $date_mass_start[1], $date_mass_start[0], $date_mass_start[2]) );

$day_session = floor($how_much_sec_session/86400);
$hours_session = floor(($how_much_sec_session/3600)-$day_session*24);
$min_session = floor(($how_much_sec_session-$hours_session*3600-$day_session*86400)/60);
$sec_session = $how_much_sec_session-($min_session*60+$hours_session*3600+$day_session*86400);

if ($hours_session > 0) {
$hours = $hours_session.' ч. ';
} else {
$hours="";
}

if ($min_session > 0) {
$minute = $min_session.' мин. ';
} else {
$minute="";
}

if ($sec_session > 0) {
$sec = $sec_session.' сек. ';
} else {
$sec="";
}

$session = $hours.$minute.$sec;
}
# конец, подсчитываем сколько времени провел пользователь за одну сессию

if ($num==$num_elements) {
?>
    <td class="time_day_date_not_line">
<nobr><? echo $mass_edit_time_in_online[$n] ?>
<?
if ($stat_view == "all") {
if (in_array($mass_edit_time_in_online[$n], $mass_edit_time_in_online_mobile)) {
?>
&nbsp;<img title="<? echo header_index_vkontakte_is_mobile ?>" src="/templates/<? echo name_template_project ?>/index/images/mobile.png">
<?
 }
}
?>
</nobr>
	</td>
    <td class="time_day_date_session_not_line"><nobr><font class="font_session"><? echo $session; ?></font></nobr></td>
<?
} else {
?>
    <td class="time_day_date">
<nobr><? echo $mass_edit_time_in_online[$n] ?>
<?
if ($stat_view == "all") {
if (in_array($mass_edit_time_in_online[$n], $mass_edit_time_in_online_mobile)) {
?>
&nbsp;<img title="<? echo header_index_vkontakte_is_mobile ?>" src="/templates/<? echo name_template_project ?>/index/images/mobile.png">
<?
 }
}
?>
</nobr>
    </td>
    <td class="time_day_date_session"><nobr><font class="font_session"><? echo $session; ?></font></nobr></td>
<?
}
?>
  </tr>
<?
 }
}

?>
  <tr>
    <td colspan="4" align="right" valign="top" class="font_time_today">
<?
###################################################################################################
# начало, определяем сколько времени online провел пользователь за сегодня ########################
###################################################################################################

# определяем сегодняшнюю дату и время
$time_in_online_today_from = mktime(0, 0, 0, $date_array[1], $date_array[0], $date_array[2]);
$time_in_online_today_to = mktime(23, 59, 59, $date_array[1], $date_array[0], $date_array[2]);

# определяем количество записей в статистике для выбранного пользователя
if ($stat_view == "all") {
$vkontakte_user_online_query_today=mysql_query("select time_in_online from vkontakte_user_online_log where (id_monitoring_user='$id_monitoring_user' && time_in_online>='$time_in_online_today_from' && time_in_online<='$time_in_online_today_to') order by time_in_online");
} elseif ($stat_view == "mobile") {
$vkontakte_user_online_query_today=mysql_query("select time_in_online from vkontakte_user_online_log_mobile where (id_monitoring_user='$id_monitoring_user' && time_in_online>='$time_in_online_today_from' && time_in_online<='$time_in_online_today_to') order by time_in_online");
}

$num_rec_vkontakte_online_today=mysql_num_rows($vkontakte_user_online_query_today);

$today = 0;
while ($get_record_data_today=@mysql_fetch_array($vkontakte_user_online_query_today)) {
$MassTime_today[] = $get_record_data_today["time_in_online"];
}

if ($num_rec_vkontakte_online_today == 1) {
$today = $today + 60;
}

for ($k=0; $k < $num_rec_vkontakte_online_today; $k++) {
if ($k == $num_rec_vkontakte_online_today-1) {
$today = $today + 60;
} else {
if (($MassTime_today[$k] + 600) > $MassTime_today[$k + 1]) {
$diftime_today = $MassTime_today[$k+1] - $MassTime_today[$k];
$today = $today + $diftime_today;
  }
 }
}

$day_today = floor($today/86400);
$hours_today = floor(($today/3600)-$day_today*24);
$min_today = floor(($today-$hours_today*3600-$day_today*86400)/60);
$sec_today = $today-($min_today*60+$hours_today*3600+$day_today*86400);

$today = $hours_today.' ч. '.$min_today.' мин. '.$sec_today.' сек.';
echo "<div class=\"today_time_online\">".monitor_online_vk_today_time_online." ".$today."</div>";

unset($MassTime_today);
$MassTime_today = array();
###################################################################################################
# конец, определяем сколько времени online провел пользователь за сегодня #########################
###################################################################################################
?>
    </td>
  </tr>
</table>
<?
}
###################################################################################################
# конец, вывод по дням временных промежутков ######################################################
###################################################################################################
}
# конец, если есть записи в массиве временных промежутков
?>
    </td>
  </tr>
<?
###################################################################################################
# начало, вывод переходов по страницам ############################################################
###################################################################################################
# составляем ЧПУ ссылку
$chpu_link = "/".$language_code."/monitor_online_vk/id_vk_user/".$id_vk_user."/";
?>
  <tr>
<?
if (next_or_prev_exist($id_monitoring_user, $date_from, $date_to, "", $first_record, $stat_view)) {
?>
<td align="center" valign="top"><a href="<? echo $chpu_link.'page/'.$end_record.'/prev/true' ?>"><? echo monitor_online_vk_prev ?></a></td>
<?
}
if (next_or_prev_exist($id_monitoring_user, $date_from, $date_to, "&& time_in_online < ".$end_record, "", $stat_view)) {
?>
<td align="center" valign="top" style="padding-top: 0px; margin-top: 0px;"><a href="<? echo $chpu_link.'page/'.$end_record.'/next/true' ?>"><? echo monitor_online_vk_next ?></a></td>
<?
}
?>
  </tr>
<?
###################################################################################################
# конец, вывод переходов по страницам #############################################################
###################################################################################################

# конец, проверка наличия записей
} else {
?>
  <tr>
    <td align="center" height="60"><? echo monitor_online_vk_not_data ?></td>
  </tr>
<?
}
?>
</table>
<?
}
# конец, если ссылка с id_vk_user верная, то далее
}
###################################################################################################
###################################################################################################
### конец, выводим статистику по пользователю #####################################################
###################################################################################################
###################################################################################################

# подключение файла нижней части дизайна страницы
require(dirname(__FILE__)."/templates/".name_template_project."/index/footer.php");
?>
