<?
# подключение файла с настройками конфигурации
require("core/config.php");
# подключение файла с функциями
require("core/function.php");
# подключение файла осуществляющего связь с базой данных
require("core/connect.php");

# подключение файла верхней части дизайна страницы"
include("templates/".name_template_project."/index/header.php");

$select_data_token = mysql_query("select * from vkontakte_access_token order by time_select asc limit 1");
$data_token = mysql_fetch_assoc($select_data_token);

$select_data_token = mysql_query("select * from vkontakte_access_token");
while ($get_select_data_token = mysql_fetch_array($select_data_token)) {

$token = $get_select_data_token["token"];
$url = 'https://api.vk.com/method/execute?v=5.11&access_token='.$token.'&code=return[API.friends.get({"user_id":27747797,"fields":"screen_name,sex,photo_50,photo_200_orig,city,country,bdate"})];';

echo "<p><a href=".$url.">Link</a></p>";
}

# подключение файла нижней части дизайна страницы"
include("templates/".name_template_project."/index/footer.php");
?>