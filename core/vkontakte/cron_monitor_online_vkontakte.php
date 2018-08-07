<?php
# sleep(30);
date_default_timezone_set("Europe/Moscow");
# подключение файла с настройками конфигурации
require("/var/www/core/config.php");
# подключение файла осуществляющего связь с базой данных
require("/var/www/core/connect.php");
# подключение файла с функциями
require("/var/www/core/function.php");
# подключение файла осуществляющего получение данных с профилей пользователей
require("/var/www/core/vkontakte/function_get_profiles_vk.php");
$str_insert_log = NULL;
$str_insert_log_mobile = NULL;

# число строк в таблице
$num_rows_table = mysql_result(mysql_query("select count(*) from vkontakte_user_to_monitoring"), 0);
# сколько заходов выборки из таблицы (по 500 записей за раз)
$num_step = ceil($num_rows_table / 500);

echo "Vsego chelovek v DB: ".$num_rows_table."<br>";
echo "Zahodov vyborki iz DB: ".$num_step."<br>";

# начало, выбираем информацию из профилей пользователей за определенное количество шагов
$num_user_select = 0;
for ($n=0; $n < $num_step; $n++) {

# начало, считываем по порядку из таблицы добавленных пользователей (по 500)
$vkontakte_user_to_monitoring_data = mysql_query("select * from vkontakte_user_to_monitoring order by id_monitoring_user LIMIT ".$num_user_select.", 500");
while ($get_vkontakte_user_to_monitoring_data = mysql_fetch_array($vkontakte_user_to_monitoring_data)) {
$all_data_user[] = $get_vkontakte_user_to_monitoring_data["id_vk_user"];
}
# конец, считываем по порядку из таблицы добавленных пользователей (по 500)

# преобразуем массив в строку, в котором элементы разделяются ","
$all_data_user_str = implode(",", $all_data_user);
# обнуляем массив
unset($all_data_user);
$all_data_user = array();

# получаем данные пользователя
$res_profile_data[] = get_vk_data_users($all_data_user_str, "online, online_mobile");

print_r($res_profile_data);

# для следующего шага
$num_user_select = $num_user_select + 500;
# 1 секунду ожидаем
sleep(1);
}
# конец, выбираем информацию из профилей пользователей за определенное количество шагов

# начало, обрабатываем полученную информацию
$x=0;
for ($m=0; $m < count($res_profile_data); $m++) {
foreach ($res_profile_data[$m][response] as $key => $value) {

# если пользователь в онлайн
if ($value[user][online] == 1) {

# сколько в онлайне
$x = $x + 1;
# находим данные пользователя
$user_monitoring_query=mysql_query("select * from vkontakte_user_to_monitoring where id_vk_user='".$value[user][uid]."'");
$user_monitoring_data=mysql_fetch_assoc($user_monitoring_query);

# id пользователя
$id_monitoring_user=$user_monitoring_data["id_monitoring_user"];
# последнее состояние online
$time_last_online=$user_monitoring_data["time_last_online"];
# Имя и Фамилия
$fio_vk_user=$value[user][first_name]." ".$value[user][last_name];
$fio_vk_user=convert_post($fio_vk_user, 0);

# определяем дату и время
$time_in_online = mktime(date("H"), date("i"), date("s"), date("m"), date("j"), date("Y"));

# добавляем данные в таблицу лога онлайн посещений
# mysql_query("insert IGNORE into vkontakte_user_online_log (id_monitoring_user, time_in_online) values ('$id_monitoring_user', '$time_in_online')");

# составляем insert запрос для vkontakte_user_online_log
$str_insert_log = $str_insert_log."('".$id_monitoring_user."','".$time_in_online."'),";

# если пользователь находится с мобильного устройства
if ($value[user][online_mobile] == 1) {

# добавляем данные в таблицу лога онлайн посещений с мобильного
# mysql_query("insert IGNORE into vkontakte_user_online_log_mobile (id_monitoring_user, time_in_online) values ('$id_monitoring_user', '$time_in_online')");

# составляем insert запрос для vkontakte_user_online_log_mobile
$str_insert_log_mobile = $str_insert_log_mobile."('".$id_monitoring_user."','".$time_in_online."'),";

# добавляем в таблицу данные о последнем и предпоследнем состоянии Онлайн
mysql_query("update vkontakte_user_to_monitoring set fio_vk_user='$fio_vk_user', time_last_online='$time_in_online', time_before_online='$time_last_online', online_mobile='1' where id_monitoring_user='$id_monitoring_user'");
} else {
# добавляем в таблицу данные о последнем и предпоследнем состоянии Онлайн
mysql_query("update vkontakte_user_to_monitoring set fio_vk_user='$fio_vk_user', time_last_online='$time_in_online', time_before_online='$time_last_online', online_mobile='0' where id_monitoring_user='$id_monitoring_user'");
   }
  }
 }
}

$str_insert_log =  substr($str_insert_log, 0, -1);
mysql_query("insert IGNORE into vkontakte_user_online_log (id_monitoring_user, time_in_online) values ".$str_insert_log."");

$str_insert_log_mobile =  substr($str_insert_log_mobile, 0, -1);
mysql_query("insert IGNORE into vkontakte_user_online_log_mobile (id_monitoring_user, time_in_online) values ".$str_insert_log_mobile."");

echo "Vsego v online: ".$x."<br>";
# конец, обрабатываем полученную информацию
?>
