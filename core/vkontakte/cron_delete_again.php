<?
###################################################################################################
### Очистка базы от случайно оставленных записей ##################################################
###################################################################################################

date_default_timezone_set("Europe/Moscow");

# подключение файла с настройками конфигурации
require("/var/www/core/config.php");
# подключение файла осуществляющего связь с базой данных
require("/var/www/core/connect.php");

###################################################################################################
### 1 #############################################################################################
###################################################################################################
# определяем дату и время
$time_now = mktime(date("H"), date("i"), date("s"), date("m"), date("j"), date("Y"));
# начало, считываем по порядку из таблицы всех добавленных пользователей
$vkontakte_user_to_monitoring_data = mysql_query("select * from vkontakte_user_to_monitoring order by id_monitoring_user");
while ($get_vkontakte_user_to_monitoring_data = mysql_fetch_array($vkontakte_user_to_monitoring_data)) {

# определяем сколько времени прошло с последнего просмотра данной страницы
$time_left = $time_now - $get_vkontakte_user_to_monitoring_data['time_last_access'];

$id_vk_user=$get_vkontakte_user_to_monitoring_data['id_vk_user'];
$id_monitoring_user=$get_vkontakte_user_to_monitoring_data['id_monitoring_user'];

# если прошло более 33 дней, удаляем все упоминания о учетной записи
if ($time_left > 2851200) {
echo $id_vk_user."---".date("d.m.Y, H:i", $get_vkontakte_user_to_monitoring_data['time_last_access'])."<br>";

# удаляем пользователя из всех профилей
mysql_query("delete from vkontakte_user_monitoring_in_profile where id_monitoring_user='$id_monitoring_user'");
# удаляем всю статистику
mysql_query("delete from vkontakte_user_online_log where id_monitoring_user='$id_monitoring_user'");
mysql_query("delete from vkontakte_user_online_log_mobile where id_monitoring_user='$id_monitoring_user'");
mysql_query("delete from vkontakte_user_status_log where id_vk_user='$id_vk_user'");
mysql_query("delete from vkontakte_user_friends_log where id_vk_user='$id_vk_user'");
mysql_query("delete from vkontakte_user_friends_cron where id_vk_user='$id_vk_user'");
mysql_query("delete from vkontakte_user_friends_change where id_vk_user='$id_vk_user'");
mysql_query("delete from vkontakte_user_friends_update where id_vk_user='$id_vk_user'");
mysql_query("delete from vkontakte_user_friends_view where id_vk_user='$id_vk_user'");
# удаляем самого пользователя
mysql_query("delete from vkontakte_user_to_monitoring where id_monitoring_user='$id_monitoring_user'");
}

}

###################################################################################################
### 2 #############################################################################################
###################################################################################################
mysql_query("delete from vkontakte_user_monitoring_in_profile where id_monitoring_user not in (select id_monitoring_user from vkontakte_user_to_monitoring)");
mysql_query("delete from vkontakte_user_friends_log where id_vk_user not in (select id_vk_user from vkontakte_user_to_monitoring)");
mysql_query("delete from vkontakte_user_friends_cron where id_vk_user not in (select id_vk_user from vkontakte_user_to_monitoring)");
mysql_query("delete from vkontakte_user_friends_update where id_vk_user not in (select id_vk_user from vkontakte_user_to_monitoring)");
mysql_query("delete from vkontakte_user_friends_view where id_vk_user not in (select id_vk_user from vkontakte_user_to_monitoring)");
mysql_query("delete from vkontakte_user_online_log where id_monitoring_user not in (select id_monitoring_user from vkontakte_user_to_monitoring)");
mysql_query("delete from vkontakte_user_online_log_mobile where id_monitoring_user not in (select id_monitoring_user from vkontakte_user_to_monitoring)");
mysql_query("delete from vkontakte_user_status_log where id_vk_user not in (select id_vk_user from vkontakte_user_to_monitoring)");
?>