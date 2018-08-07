<?
# защита от флуда
include("antiddos/core/antiddos.php");

# подключение файла с настройками конфигурации
require("core/config.php");

# подключение файла с функциями
require("core/function.php");

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
require("core/connect.php");

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

?>
<table border="0" cellpadding="7" cellspacing="0" align="center" width="80%">
  <tr>
    <td height="33" valign="top">&nbsp;<a href="/<? echo $language_code ?>/index.html"><? echo avatar_vkontakte_main_page ?></a>&nbsp;&nbsp;→&nbsp;&nbsp;<font class="title"><? echo avatar_vkontakte_workspace_title ?></font></td>
  </tr>
</table>

<br>

<table width="60%" align="center" cellpadding="5" cellspacing="1" border="0">
<form action="/<? echo $language_code?>/avatar/id_vk_user/<? echo $id_vk_user ?>" method="post">
  <tr>
    <td colspan="2" class="line_blue">&nbsp;&nbsp;<b><? echo avatar_default_header ?></b></td>
  </tr>
  <tr>
    <td align="center">
      <p><br><b><? echo avatar_vkontakte_default_description ?>:</b></p>
      <input type="text" size="45" maxlength="45" value="http://nayavu.com/check/vk/<? echo $id_vk_user ?>.jpg">
    </td>
  </tr>

  <tr>
    <td align="center">
      <p><b><? echo avatar_vkontakte_default_how_to_view ?>:</b></p>
      <img src="http://nayavu.com/check/vk/<? echo $id_vk_user ?>.jpg">
    </td>
  </tr>

  <tr>
    <td align="center">
      <p><b><? echo avatar_vkontakte_default_code_to_forum ?>:</b></p>
      <textarea cols="80" rows="2"><? echo "[url=http://nayavu.com/ru/monitor_online_vk/id_vk_user/".$id_vk_user."][img]http://nayavu.com/check/vk/".$id_vk_user.".jpg[/img][/url]" ?></textarea>
    </td>
  </tr>

  <tr>
    <td align="center">
      <p><b><? echo avatar_vkontakte_default_code_to_vk ?>:</b></p>
      <textarea cols="80" rows="2"><? echo "[url=http://vk.com/id".$id_vk_user."][img]http://nayavu.com/check/vk/".$id_vk_user.".jpg[/img][/url]" ?></textarea>
    </td>
  </tr>

</form>
</table>
<?

} else {
exit;
  }
 }
# конец, если есть данные id_vk_user

# подключение файла нижней части дизайна страницы"
include("templates/".name_template_project."/index/footer.php");
?>