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

?>
<table border="0" cellpadding="5" cellspacing="0" align="center" width="80%">
  <tr>
    <td height="55" valign="top">&nbsp;<a href="/<? echo $language_code ?>/index.html"><? echo qip_xmpp_main_page ?></a>&nbsp;&nbsp;→&nbsp;&nbsp;<font class="title"><? echo qip_xmpp_workspace_title ?></font></td>
  </tr>
  <tr>
    <td>
<p>Саму программу можно скачать с <a target="_blank" href="http://welcome.qip.ru/im?utm_source=mainqip&utm_medium=cpc&utm_content=download&utm_campaign=main_download">официального сайта</a></p>
<p>После того как программа скачалась, устанавливаем её.</p>
<p>При первом запуске программы Вам необходимо ввести уже <b>существующий аккаунт QIP</b> или <b>Зарегистрировать</b> новый.</p>
<p><img src="/templates/<? echo name_template_project ?>/index/images/1.jpg"></p>
<p><b>При регистрации</b> Вам необходимо указать личный номер мобильного телефона и пароль.<br>В течении нескольких минут на указанный номер по СМС прийдет код подтверждения, который следует ввести в запрашиваемое окно.</p>
<p><img src="/templates/<? echo name_template_project ?>/index/images/2.jpg"></p>
<p>После авторизации Вам необходимо добавить свою учетную запись. Для этого кликните по верхней правой <b>кнопке QIP</b>, выберите <b>Добавить учетную запись</b> и далее <b>XMPP (Jabber)</b></p>
<p><img src="/templates/<? echo name_template_project ?>/index/images/3.jpg"></p>
<p>Введите <b>логин@nayavu.com</b> и <b>пароль</b>, где логин и пароль - это ваши учетные данные на сайте nayavu.com, которые вы указывали при регистрации.</p>
<p><img src="/templates/<? echo name_template_project ?>/index/images/4.jpg"></p>
<p>Уведомления по мессенджеру приходят от пользователя <b>admin@nayavu.com</b>, поэтому данный аккаунт уже будет добавлен в Ваш <b>контакт-лист</b> в группу <b>Message</b>, но он может находится в <b>оффлайн</b>, так может быть, но сообщения все равно будут доходить до Вас.</p>
<p><img src="/templates/<? echo name_template_project ?>/index/images/5.jpg"></p>
<p>Пользователя <b>admin@nayavu.com желательно авторизовать</b>, т.к вполне возможно на некоторых сообщениях сработает анти-спам система и они не будут доставлены.</p>
<p><img src="/templates/<? echo name_template_project ?>/index/images/6.jpg"></p>
	</td>
  </tr>
</table>
<?

# подключение файла нижней части дизайна страницы"
include("templates/".name_template_project."/index/footer.php");
?>