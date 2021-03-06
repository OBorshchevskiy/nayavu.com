<?
# инициализирум механизм сессий
session_start();

# подключение файла с настройками конфигурации
require("../core/config.php");

# подключение файла с функциями
require("../core/function.php");

# подключение файла осуществляющего связь с базой данных
require("../core/connect.php");

# флаг, не показывать форму авторизации
$not_view_workspace = true;

# подключение файла верхней части дизайна страницы
require("../templates/".name_template_project."/admin/header.php");

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
$user_info_query=mysql_query("select login, email, lostpasscode from admin where(lostpasscode ='$secure_code')");
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
$update_password_query="update admin set password='$new_password', lostpasscode='' where (login='$user_info_login' && lostpasscode='$secure_code')";
mysql_query($update_password_query);

# отсылаем пользователю на почту новый пароль
putenv("TMPDIR=/tmp");
@mail("$user_info_email", "$url, ".lostpass_password_send_notice_topic." $user_info_login", lostpass_password_send_notice_text.": $generate_new_password \r\n\r\n".$signature, "from: $email_admin\r\nreply-to: $email_admin\r\ncontent-type: text/plain; charset=utf-8\r\ncontent-transfer-encoding: 8bit");

# выводим сообщение о том, что новый пароль был отправлен на почту
$result_message[]=array("message" => lostpass_password_send_message, "class" => good);
# ставим флаг того, чтобы не показывать главную форму
$not_view_lospass_form=true;
 }
# конец $_GET[secure_code]
}

# если данные логина и e-mail получены
if (($_SERVER['REQUEST_METHOD']=='POST') && (isset($_POST['submit_lostpass']))) {
# преобразуем в безопасный вид поступившие данные
$login=convert_post($_POST['login'], "0");
$email=convert_post($_POST['email'], "0");
$secretcode=convert_post($_POST['secretcode'], "0");

# заполнено ли поле логина
if (!$login) {
# заносим в массив сообщений текст ошибки
$result_message[]=array("message" => lostpass_login_empty, "class" => bad);
} else {
# проверка логина на правильность
if (!preg_match('/^[a-z0-9_-]{3,30}+$/i',$login)) {
# заносим в массив сообщений текст ошибки
$result_message[]=array("message" => lostpass_login_error, "class" => bad);
 }
}

# заполнено ли поле email
if (!$email) {
# заносим в массив сообщений текст ошибки
$result_message[]=array("message" => lostpass_email_empty, "class" => bad);
} else {
# проверка e-mail на правильность
if (!preg_match('/^[\.\-_A-Za-z0-9]+?@[\.\-A-Za-z0-9]+?\.[A-Za-z0-9]{2,6}$/', $email)) {
# заносим в массив сообщений текст ошибки
$result_message[]=array("message" => lostpass_email_error, "class" => bad);
 }
}

# верно ли ввел антиспам-код пользователь
if ($secretcode <> $_SESSION["secret_number_lostpass"]) {
# заносим в массив сообщений текст ошибки
$result_message[]=array("message" => lostpass_secret_code_bad, "class" => bad);
}

# если все введенные данные верны, отправляем ссылку для генерации нового пароля
if (!isset($result_message)) {
# проверка по базе, есть ли такой пользователь
$user_select_query=mysql_query("select email from admin where (login='$login' && email='$email')");
# если нет таких, то вывод ошибки
if (!mysql_num_rows($user_select_query)) {
$result_message[]=array("message" => lostpass_user_not_found_error, "class" => bad);
} else {
# генерация случайного числа из 12 символов
$lost_pass_code=gen_rand_str();
# вставка проверочного кода в таблицу для пользователя
mysql_query("update admin set lostpasscode='$lost_pass_code' where (login='$login' and email='$email')");

# получаем email пользователя
$email_user=mysql_result($user_select_query, 0);
# отсылаем ссылку с кодом для восстановления пароля
putenv("TMPDIR=/tmp");
@mail("$email_user", "$url, ".lostpass_link_send_notice_topic." $login", lostpass_link_send_notice_text.":\r\n\r\n http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']."?secure_code=".$lost_pass_code."\r\n\r\n".$signature, "from: $email_admin\r\nreply-to: $email_admin\r\ncontent-type: text/plain; charset=utf-8\r\ncontent-transfer-encoding: 8bit");

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
<table border="0" cellspacing="0" cellpadding="7" align="center" class="data_box">
  <tr class="data_box">
<form action="/admin/lostpass.html" method="post">
    <td>
      <b><? echo lostpass_login ?></b><br>
      <input type="text" name="login" size="30" maxlength="30">&nbsp;<font class="important">*</font>
    </td>
  </tr>
  <tr class="data_box">
    <td>
      <b><? echo lostpass_email ?></b><br>
      <input type="text" name="email" size="30" maxlength="50">&nbsp;<font class="important">*</font>
    </td>
  </tr>
  <tr class="data_box">
    <td>
      <b><? echo lostpass_secretcode ?></b><br>
      <img src="/core/imagecode.html?secret_number_name=secret_number_lostpass"><br><br>
      <input type="text" size="6" maxlength="6" name="secretcode">&nbsp;<font class="important">*</font>
    </td>
  </tr>
  <tr class="data_box">
    <td align="center"><input type="submit" name="submit_lostpass" value="<? echo lostpass_button_go ?>"></td>
  </tr>
</form>
</table>
<p align="center"><font class="important">*</font> - <font class="notice"><? echo lostpass_field_important ?></font></p>
<p align="center"><a href="/<? echo $language_code ?>/admin/index.html"><? echo lostpass_link_prev ?></a></p>
<!-- ############################################################# -->
<!-- ##### конец, главная форма для восстановления пароля ######## -->
<!-- ############################################################# -->
<?
} else {
?>
<p align="center"><a href="/<? echo $language_code ?>/admin/index.html"><? echo lostpass_link_next ?></a></p>
<?
}

# подключение файла нижней части дизайна страницы
require("../templates/".name_template_project."/admin/footer.php");
?>