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

# как найти id
?>
<table border="0" cellpadding="5" cellspacing="0" align="center" width="60%">
  <tr>
    <td height="55" valign="top">&nbsp;<a href="/<? echo $language_code ?>/index.html"><? echo help_main_page ?></a>&nbsp;&nbsp;→&nbsp;&nbsp;<font class="title"><? echo help_workspace_title ?></font></td>
  </tr>
  <tr>
    <td>&nbsp;<? echo help_how_to_id_or_name ?></td>
  </tr>
  <tr>
    <td><img src="/templates/<? echo name_template_project ?>/index/images/id_number.gif"></td>
  </tr>
  <tr>
    <td></td>
  </tr>
  <tr>
    <td><img src="/templates/<? echo name_template_project ?>/index/images/name.gif"></td>
  </tr>
</table>
<?

# подключение файла нижней части дизайна страницы"
include("templates/".name_template_project."/index/footer.php");
?>