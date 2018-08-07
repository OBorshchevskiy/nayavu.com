<?
sleep(50);
date_default_timezone_set("Europe/Moscow");
###################################################################################################
### если прошло более 21 дня - добавляем запись в очередь                                       ###
### если прошло более 30 дней, удаляем запись 21 день и ставить новую запись в очередь          ###
###################################################################################################

# подключение файла с настройками конфигурации
require("/var/www/core/config.php");
# подключение файла осуществляющего связь с базой данных
require("/var/www/core/connect.php");
# подключение языкового файла
require("/var/www/core/language.ru.php");

$language_code="ru";
# выбираем из настроек url
$url_query=mysql_query("select data from config_$language_code where (name='url')");
$url=mysql_result($url_query, 0);
# выбираем из настроек e-mail администратора
$email_admin_query=mysql_query("select data from config_$language_code where (name='email_admin')");
$email_admin=mysql_result($email_admin_query, 0);
# выбираем из настроек подпись к письму
$signature_query=mysql_query("select data from config_$language_code where (name='signature')");
$signature=mysql_result($signature_query, 0);

# начало, считываем по порядку из таблицы всех добавленных пользователей
$vkontakte_user_to_monitoring_data = mysql_query("select * from vkontakte_user_to_monitoring order by id_monitoring_user");
while ($get_vkontakte_user_to_monitoring_data = mysql_fetch_array($vkontakte_user_to_monitoring_data)) {

# определяем дату и время
$time_now = mktime(date("H"), date("i"), date("s"), date("m"), date("j"), date("Y"));
# определяем сколько времени прошло с последнего просмотра данной страницы
$time_left = $time_now - $get_vkontakte_user_to_monitoring_data['time_last_access'];
# ФИО
$fio_vk_user=$get_vkontakte_user_to_monitoring_data['fio_vk_user'];
# id вконтакте
$id_vk_user=$get_vkontakte_user_to_monitoring_data['id_vk_user'];
# ФИО с id
$fio_and_id=$fio_vk_user."(id".$id_vk_user.")";

# есть ли уже данная запись в таблице на удаление
$user_delete_query=mysql_query("select * from vkontakte_to_delete where id_monitoring_user='$get_vkontakte_user_to_monitoring_data[id_monitoring_user]'");
$user_delete_query_num=mysql_num_rows($user_delete_query);

$is_send=0;
$how_day=0;

if ($user_delete_query_num) {
$user_delete_query_data=mysql_fetch_assoc($user_delete_query);
# отправлялось ли уже письмо
$is_send=$user_delete_query_data["send"];
# сколько дней прошло
$how_day=$user_delete_query_data["day"];
}

if ($how_day <> 21) {
# если с момента последнего обращения прошло: 21-30 дней
if ( ($time_left >= 1814400) && ($time_left < 2592000) ) {
# выбираем все email людей добавивших данного пользователя
$vkontakte_user_profile = mysql_query("select id_registered_user from vkontakte_user_monitoring_in_profile where id_monitoring_user='$get_vkontakte_user_to_monitoring_data[id_monitoring_user]'");
while ($get_vkontakte_user_profile = mysql_fetch_array($vkontakte_user_profile)) {
$email_user=mysql_result(mysql_query("select email from user where id_registered_user='$get_vkontakte_user_profile[id_registered_user]'"), 0);
echo "<p>21 day - ".$fio_vk_user." - id".$id_vk_user." - ".$email_user."</p>";
# отправка уведомления о том, что прошел 21 день с момента последнего просмотра анкеты
putenv("TMPDIR=/tmp");
@mail("$email_user", "$url, ".cron_monitor_delete_inactive_insert_to_db_delete_user." $fio_vk_user", cron_monitor_delete_inactive_insert_to_db_notice_text_a." $fio_and_id ".cron_monitor_delete_inactive_insert_to_db_notice_text_b."\r\n\r\n".$signature, "from: $email_admin\r\nreply-to: $email_admin\r\ncontent-type: text/plain; charset=utf-8\r\ncontent-transfer-encoding: 8bit");
mysql_query("insert IGNORE into vkontakte_to_delete (id_monitoring_user, id_vk_user, fio_vk_user, day, send, time_send) values ('$get_vkontakte_user_to_monitoring_data[id_monitoring_user]', '$get_vkontakte_user_to_monitoring_data[id_vk_user]', '$get_vkontakte_user_to_monitoring_data[fio_vk_user]', '21', '1', '0')");
# 30 секунд подождем
sleep(30);
}
 }
}

if ($how_day <> 30) {
# если с момента последнего обращения прошло: > 30 дней
if ($time_left >= 2592000) {
# удаляем запись 21 день
if ($how_day == 21) {
mysql_query("delete from vkontakte_to_delete where id_monitoring_user='$get_vkontakte_user_to_monitoring_data[id_monitoring_user]'");
}
# выбираем все email людей добавивших данного пользователя
$vkontakte_user_profile = mysql_query("select id_registered_user from vkontakte_user_monitoring_in_profile where id_monitoring_user='$get_vkontakte_user_to_monitoring_data[id_monitoring_user]'");
while ($get_vkontakte_user_profile = mysql_fetch_array($vkontakte_user_profile)) {
$email_user=mysql_result(mysql_query("select email from user where id_registered_user='$get_vkontakte_user_profile[id_registered_user]'"), 0);
echo "<p>30 day - ".$fio_vk_user." - id".$id_vk_user." - ".$email_user."</p>";
# отправка уведомления о том, что прошел 30 день с момента последнего просмотра анкеты
putenv("TMPDIR=/tmp");
@mail("$email_user", "$url, ".cron_monitor_delete_inactive_insert_to_db_delete_user." $fio_vk_user", cron_monitor_delete_inactive_insert_to_db_notice_text_a." $fio_and_id ".cron_monitor_delete_inactive_insert_to_db_notice_text_c."\r\n\r\n".$signature, "from: $email_admin\r\nreply-to: $email_admin\r\ncontent-type: text/plain; charset=utf-8\r\ncontent-transfer-encoding: 8bit");
mysql_query("insert IGNORE into vkontakte_to_delete (id_monitoring_user, id_vk_user, fio_vk_user, day, send, time_send) values ('$get_vkontakte_user_to_monitoring_data[id_monitoring_user]', '$get_vkontakte_user_to_monitoring_data[id_vk_user]', '$get_vkontakte_user_to_monitoring_data[fio_vk_user]', '30', '1', '$time_now')");
# 30 секунд подождем
sleep(30);
}
 }
}

}
# конец, считываем по порядку из таблицы всех добавленных пользователей
?>