<?
# подключение файла с настройками конфигурации
require("../core/config.php");
# подключение файла осуществляющего связь с базой данных
require("../core/connect.php");
# подключение файла с функциями
require("../core/function.php");

# если пользователь в онлайне
if (is_online(0, $_POST['id_monitoring_user'])) {

# id пользователя
$id_monitoring_user = $_POST['id_monitoring_user'];

# определяем когда последний раз пользователь был в онлайне, date("d.m.Y, H:i:s", $last_online_user)
$last_online_now_user_query = mysql_query("select time_in_online from vkontakte_user_online_log where (id_monitoring_user='$id_monitoring_user') order by time_in_online desc LIMIT 1");
$last_online_now_user=mysql_result($last_online_now_user_query, 0);

# определяем когда предпоследний раз пользователь был в онлайне, date("d.m.Y, H:i:s", $last_online_user)
$last_online_user_query = mysql_query("select time_in_online from vkontakte_user_online_log where (id_monitoring_user='$id_monitoring_user') order by time_in_online desc LIMIT 1,1");
$last_online_user=mysql_result($last_online_user_query, 0);

# если с момента предпоследнего посещения данного пользователя прошло более 3 минут
if (($last_online_now_user - $last_online_user) >= 170) {
echo true;
}

}
?>