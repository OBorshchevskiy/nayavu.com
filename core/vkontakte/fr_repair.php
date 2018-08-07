<?
require("/var/www/core/config.php");
require("/var/www/core/connect.php");

$vkontakte_user_to_log_data = mysql_query("select distinct id_vk_user from vkontakte_user_friends_log");
while ($get_vkontakte_user_to_log_data = mysql_fetch_array($vkontakte_user_to_log_data)) {
$uid = "";
$uid = $get_vkontakte_user_to_log_data["id_vk_user"];

mysql_query("insert vkontakte_user_friends_cron (id_vk_user, time_update) values ('$uid', '1503062402')");
}
?>