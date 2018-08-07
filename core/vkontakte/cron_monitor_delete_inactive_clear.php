<?
sleep(50);
date_default_timezone_set("Europe/Moscow");
# подключение файла с настройками конфигурации
require("/var/www/core/config.php");
# подключение файла осуществляющего связь с базой данных
require("/var/www/core/connect.php");
# подключение языкового файла
require("/var/www/core/language.ru.php");

# определяем дату и время
$time_now = mktime(date("H"), date("i"), date("s"), date("m"), date("j"), date("Y"));

###################################################################################################
### начало, удаление пользователя полностью #######################################################
###################################################################################################
function delete_from_all($fio_vk_user, $id_vk_user, $id_monitoring_user, $avatar_link) {

# удаляем пользователя из всех профилей
mysql_query("delete from vkontakte_user_monitoring_in_profile where id_monitoring_user='$id_monitoring_user'");
# удаляем всю статистику
mysql_query("delete from vkontakte_user_online_log where id_monitoring_user='$id_monitoring_user'");
sleep(3);
mysql_query("delete from vkontakte_user_online_log_mobile where id_monitoring_user='$id_monitoring_user'");
sleep(3);
mysql_query("delete from vkontakte_user_status_log where id_vk_user='$id_vk_user'");
sleep(3);
mysql_query("delete from vkontakte_user_friends_log where id_vk_user='$id_vk_user'");
sleep(3);
mysql_query("delete from vkontakte_user_friends_cron where id_vk_user='$id_vk_user'");
sleep(3);
mysql_query("delete from vkontakte_user_friends_change where id_vk_user='$id_vk_user'");
sleep(3);
mysql_query("delete from vkontakte_user_friends_update where id_vk_user='$id_vk_user'");
sleep(3);
mysql_query("delete from vkontakte_user_friends_view where id_vk_user='$id_vk_user'");

# удаляем самого пользователя
mysql_query("delete from vkontakte_user_to_monitoring where id_monitoring_user='$id_monitoring_user'");
# начало, удаляем аватарку

# находим имя аватарки
$content_img_path=explode("/", $avatar_link);
$img_name=$content_img_path[count($content_img_path)-1];
# удаляем
if ((!strpos($img_name, "camera_c")) && (!strpos($img_name, "question")) && (!strpos($img_name, "deactivated"))) {
unlink("/var/www/core/vkontakte/avatars/".$img_name);
}
# конец, удаляем аватарку

sleep(1);

# вывод сообщения о том, что пользователь успешно удален
echo $fio_vk_user." - ".$id_vk_user." - is delete<br>";
}
###################################################################################################
### конец, удаление пользователя полностью ########################################################
###################################################################################################

# начало, считываем по порядку из таблицы всех пользователей назначенных на удаление (30 дней)
$vkontakte_user_to_delete_data = mysql_query("select * from vkontakte_to_delete where day='30'");
while ($get_vkontakte_user_to_delete = mysql_fetch_array($vkontakte_user_to_delete_data)) {

# начало, если с момента отправки прошло более 36 часов
if (($time_now - $get_vkontakte_user_to_delete['time_send']) > 129600) {
# проверяем были ли просмотры после отправки уведомления
$time_last_access=mysql_result(mysql_query("select time_last_access from vkontakte_user_to_monitoring where (id_monitoring_user='$get_vkontakte_user_to_delete[id_monitoring_user]')"), 0);
# если были просмотры и прошло менее 21 дня
if (($time_now - $time_last_access) < 1814400) {
mysql_query("delete from vkontakte_to_delete where id_monitoring_user='$get_vkontakte_user_to_delete[id_monitoring_user]'");
echo $get_vkontakte_user_to_delete['fio_vk_user']." - ".$get_vkontakte_user_to_delete['id_vk_user']." - new view<br>";
} else {
# иначе удаляем все данные о пользователе
$avatar_link=mysql_result(mysql_query("select avatar_vk_user from vkontakte_user_to_monitoring where (id_monitoring_user='$get_vkontakte_user_to_delete[id_monitoring_user]')"), 0);
delete_from_all($get_vkontakte_user_to_delete['fio_vk_user'], $get_vkontakte_user_to_delete['id_vk_user'], $get_vkontakte_user_to_delete['id_monitoring_user'], $avatar_link);
mysql_query("delete from vkontakte_to_delete where id_monitoring_user='$get_vkontakte_user_to_delete[id_monitoring_user]'");
}

}
# конец, если с момента отправки прошло более 36 часов

}
# конец, считываем по порядку из таблицы всех пользователей назначенных на удаление (30 дней)
?>