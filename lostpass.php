<?
# инициализирум механизм сессий
session_start();

# защита от флуда
include("antiddos/core/antiddos.php");

# подключение файла с настройками конфигурации
require(dirname(__FILE__)."/core/config.php");

# подключение файла с функциями
require(dirname(__FILE__)."/core/function.php");

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
require(dirname(__FILE__)."/core/connect.php");

# флаг, не показывать форму авторизации
$not_view_workspace = true;

# подключение файла верхней части дизайна страницы
require(dirname(__FILE__)."/templates/".name_template_project."/index/header.php");

# выбираем из настроек url
$url_query=mysql_query("select data from config_$language_code where (name='url')");
$url=mysql_result($url_query, 0);

# выбираем из настроек e-mail администратора
$email_admin_query=mysql_query("select data from config_$language_code where (name='email_admin')");
$email_admin=mysql_result($email_admin_query, 0);

# выбираем из настроек подпись к письму
$signature_query=mysql_query("select data from config_$language_code where (name='signature')");
$signature=mysql_result($signature_query, 0);

# если была нажата ссылка с кодом для восстановления пароля
if (isset($_GET['secure_code'])) {
# преобразуем в безопасный вид поступившие данные
$secure_code=convert_post($_GET['secure_code'], "0");
# получение данных из таблицы для этого пользователя
$user_info_query=mysql_query("select login, email, lostpasscode from user where(lostpasscode ='$secure_code')");
if (!mysql_num_rows($user_info_query)) {
# если такого пользователя нет в таблице, то ошибка
$result_message[]=array("message" => lostpass_secure_code_error, "class" => bad);
} else {
# получаем данные пользователя из таблицы
$user_info=mysql_fetch_assoc($user_info_query);
$user_info_login=$user_info["login"];
$user_info_email=$user_info["email"];

# генерация случайного пароля состоящего из 7 символов
function gen_rand_password($len='7', $chars='1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz') {
$chars_n=strlen($chars);
for ($i=0; $i<$len; $i++) {
@$str.=$chars[mt_rand(0,$chars_n)];
 }
return $str;
}
$generate_new_password=gen_rand_password();
$new_password=md5($generate_new_password);
# вставка нового пароля в формате md5 в таблицу и обнуление кода восстановления пароля
$update_password_query="update user set password='$new_password', lostpasscode='' where (login='$user_info_login' && lostpasscode='$secure_code')";
mysql_query($update_password_query);

# отсылаем пользователю на почту новый пароль
putenv("TMPDIR=/tmp");
@mail("$user_info_email", "$url, ".lostpass_password_send_notice_topic." $user_info_login", lostpass_password_send_notice_text.": $generate_new_password \r\n\r\n".$signature, "from: $email_admin\r\nreply-to: $email_admin\r\ncontent-type: text/plain; charset=utf-8\r\ncontent-transfer-encoding: 8bit");

# выводим сообщение о том, что новый пароль был отправлен на почту
$result_message[]=array("message" => lostpass_password_send_message, "class" => good);
# ставим флаг того, чтобы не показывать главную форму
$not_view_lospass_form=true;

###################################################################################################
### начало, обновляем пароль на форуме ############################################################
###################################################################################################
exec("/usr/bin/php5 /var/www/lostpass_in_forum.php $user_info_login $new_password");
###################################################################################################
### конец, обновляем пароль на форуме #############################################################
###################################################################################################
 }
# конец $_GET[secure_code]
}

# если данные логина и e-mail получены
if (($_SERVER['REQUEST_METHOD']=='POST') && (isset($_POST['submit_lostpass']))) {
# преобразуем в безопасный вид поступившие данные
$login=convert_post($_POST['login'], "0");
$email=convert_post($_POST['email'], "0");
$secretcode=convert_post($_POST['secretcode'], "0");

# начало, проверка: введен ли логин или пароль
if (!$login && !$email) {
# заносим в массив сообщений текст ошибки
$result_message[]=array("message" => lostpass_no_login_email_error, "class" => bad);
} else {

# заполнено ли поле логина
if ($login) {
# проверка логина на правильность
if (!preg_match('/^[a-z0-9_-]{3,30}+$/i',$login)) {
# заносим в массив сообщений текст ошибки
$result_message[]=array("message" => lostpass_login_error, "class" => bad);
 }
}

# заполнено ли поле email
if ($email) {
# проверка e-mail на правильность
if (!preg_match('/^[\.\-_A-Za-z0-9]+?@[\.\-A-Za-z0-9]+?\.[A-Za-z0-9]{2,6}$/', $email)) {
# заносим в массив сообщений текст ошибки
$result_message[]=array("message" => lostpass_email_error, "class" => bad);
 }
}

}
# конец, проверка: введен ли логин или пароль

# верно ли ввел антиспам-код пользователь
if ($secretcode <> $_SESSION["secret_number_lostpass"]) {
# заносим в массив сообщений текст ошибки
$result_message[]=array("message" => lostpass_secret_code_bad, "class" => bad);
}

# если все введенные данные верны, отправляем ссылку для генерации нового пароля
if (!isset($result_message)) {
# проверка по базе, есть ли такой пользователь
$user_select_query=mysql_query("select email from user where (login='$login' or email='$email')");
# если нет таких, то вывод ошибки
if (!mysql_num_rows($user_select_query)) {
$result_message[]=array("message" => lostpass_user_not_found_error, "class" => bad);
} else {
# генерация случайного числа из 12 символов
$lost_pass_code=gen_rand_str();
# вставка проверочного кода в таблицу для пользователя
mysql_query("update user set lostpasscode='$lost_pass_code' where (login='$login' or email='$email')");

# получаем email пользователя
$user_info_email=mysql_result($user_select_query, 0);
# отсылаем ссылку с кодом для восстановления пароля
putenv("TMPDIR=/tmp");
@mail("$user_info_email", "$url, ".lostpass_link_send_notice_topic." $login", lostpass_link_send_notice_text.":\r\n\r\n http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']."?secure_code=".$lost_pass_code."\r\n\r\n".$signature, "from: $email_admin\r\nreply-to: $email_admin\r\ncontent-type: text/plain; charset=utf-8\r\ncontent-transfer-encoding: 8bit");

# заносим в массив сообщений сообщение о том, что ссылка отправлена
$result_message[]=array("message" => lostpass_link_send_message, "class" => good);
# ставим флаг того, чтобы не показывать главную форму
$not_view_lospass_form=true;
 }
}

# конец посылки post
}

# регистрируем сессию и заносим данные нового секретного кода
$_SESSION["secret_number_lostpass"]=rand(100000,999999);

# если есть сообщения, то выводим их
if (isset($result_message)) {
foreach($result_message as $key => $value) {
view_message($value["message"], $value["class"]);
 }
}

if (!isset($not_view_lospass_form)) {
?>
<!-- ############################################################# -->
<!-- ##### начало, главная форма для восстановления пароля ####### -->
<!-- ############################################################# -->
<table border="0" cellpadding="7" cellspacing="0" align="center">

  <tr>
    <td height="55" valign="top">&nbsp;<a href="/<? echo $language_code ?>/index.html"><? echo register_main_page ?></a>&nbsp;&nbsp;→&nbsp;&nbsp;<font class="title"><? echo lostpass_index_workspace_title ?></font></td>
  </tr>

  <tr>
    <td>

<table border="0" cellspacing="0" cellpadding="8" align="center">
<form action="/<? echo $language_code ?>/lostpass.html" method="post">

  <tr>
    <td>
      <b><? echo lostpass_login ?></b><br>
      <input type="text" name="login" size="30" maxlength="30">
    </td>
  </tr>
  <tr>
    <td>
      <b><? echo lostpass_or ?></b>
    </td>
  </tr>
  <tr>
    <td>
      <b><? echo lostpass_email ?></b><br>
      <input type="text" name="email" size="30" maxlength="50">
    </td>
  </tr>
  <tr>
    <td>
      <b><? echo lostpass_secretcode ?></b><br>
      <img src="/core/imagecode.html?secret_number_name=secret_number_lostpass"><br><br>
      <input type="text" size="6" maxlength="6" name="secretcode">&nbsp;<font class="important">*</font>
    </td>
  </tr>
  <tr>
    <td align="center" height="70"><input type="submit" name="submit_lostpass" value="<? echo lostpass_button_go ?>"></td>
  </tr>

</form>
</table>

    </td>
  </tr>
</table>

<p align="center"><font class="important">*</font> - <? echo lostpass_field_important ?></p>
<p align="center"><a href="/<? echo $language_code ?>/index.html"><? echo lostpass_link_prev ?></a></p>
<!-- ############################################################# -->
<!-- ##### конец, главная форма для восстановления пароля ######## -->
<!-- ############################################################# -->
<?
} else {
?>
<p align="center"><a href="/<? echo $language_code ?>/index.html"><? echo lostpass_link_next ?></a></p>
<?
}

# подключение файла нижней части дизайна страницы
require(dirname(__FILE__)."/templates/".name_template_project."/index/footer.php");
?>