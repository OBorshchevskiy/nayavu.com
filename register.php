<?
# защита от флуда
include("antiddos/core/antiddos.php");

# инициализирум механизм сессий
session_start();

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

# начало, если пользователь не авторизован, то далее
if (!auth_check_cookie("user", $_COOKIE['login_user'], $_COOKIE['password_user'])) {

# получение ip адреса посетителя
$ip=ip_detect();

# начало, если пользователь уже регистрировался с таким же ip
if (mysql_num_rows(mysql_query("select * from user where(ip='$ip')"))) {
echo "Access to registration is closed! <br> You have already registered with ip = ".$ip;
} else {

# для проверки статуса регистрации
$value["class"] = "";
# ставим начальную временную зону
$timezone = "Europe/Moscow=+4";

# начало, post
if (($_SERVER['REQUEST_METHOD']=='POST') && (isset($_POST['submit_register']))) {

# получения данных с формы
foreach($_POST as $key => $_POST['key']) {
# приведение к безопасному виду
$value=convert_post($_POST['key'], "0");
$$key=$value;
}

# заполнено ли поле логина
if (!$login) {
# заносим в массив сообщений текст ошибки
$result_message[]=array("message" => register_login_empty, "class" => bad);
} else {
# проверка логина на правильность
if (!preg_match("/^[a-z_-]+$/i", $login) || (utf8_count_chars($login) < 3)) {
# заносим в массив сообщений текст ошибки
$result_message[]=array("message" => register_login_error, "class" => bad);
 } else {
# смотрим регистрировался ли уже пользователь с таким логином
$num_of_such_login=mysql_query("select login from user where (login='$login')");
if (mysql_num_rows($num_of_such_login)) {
# заносим в массив сообщений текст ошибки
$result_message[]=array("message" => register_login_dublicate, "class" => bad);
  }
 }
}

# смотрим заполнено ли первое поле пароля
if (!$password1) {
# заносим в массив сообщений текст ошибки
$result_message[]=array("message" => register_password1_empty, "class" => bad);
} else {
# смотрим заполнено ли второе поле пароля
if (!$password2) {
# заносим в массив сообщений текст ошибки
$result_message[]=array("message" => register_password2_empty, "class" => bad);
} else {
# смотрим совпадают ли пароли
if ($password1 <> $password2) {
$result_message[]=array("message" => register_password1_password2_error, "class" => bad);
} else {
# проверка пароля на длину
if (utf8_count_chars($password1) < 6) {
# заносим в массив сообщений текст ошибки
$result_message[]=array("message" => register_password_error, "class" => bad);
   }
  }
 }
}

# смотрим заполнено ли поле email
if (!$email) {
$result_message[]=array("message" => register_email_empty, "class" => bad);
} else {
# проверка e-mail на правильность
if (!preg_match('/^[\.\-_A-Za-z0-9]+?@[\.\-A-Za-z0-9]+?\.[A-Za-z0-9]{2,6}$/', $email)) {
# заносим в массив сообщений текст ошибки
$result_message[]=array("message" => register_email_error, "class" => bad);
} else {
# смотрим регистрировался ли уже пользователь с таким e-mail
$num_of_such_email=mysql_query("select email from user where (email='$email')");
if (mysql_num_rows($num_of_such_email)) {
# заносим в массив сообщений текст ошибки
$result_message[]=array("message" => register_email_dublicate, "class" => bad);
  }
 }
}

# проверяем, есть ли timezone в массиве
if (!$timezone) {
# заносим в массив сообщений текст ошибки
$result_message[]=array("message" => register_timezone_error, "class" => bad);
}

# верно ли ввел антиспам-код пользователь
if ($secretcode <> $_SESSION["secret_number_register"]) {
# заносим в массив сообщений текст ошибки
$result_message[]=array("message" => register_secret_code_bad, "class" => bad);
}

# если ошибок в процессе проверки данных нет, то продолжаем операции с данными
if (!isset($result_message)) {

# удаляем "+"
$timezone_edit=str_replace("+", "", $timezone);
# разбиваем на название временной зоны и ее номер
$timezone_arr=explode("=", $timezone_edit);

# преобразуем пароль в md5 хэш
$md5password=md5($password1);

# регистрация пользователя
exec("/usr/bin/php5 /var/www/register_in_forum.php $login $md5password $email $timezone_arr[1] $ip", $retarr);
sleep(0.1);
exec('sudo -u ejabberd /usr/sbin/ejabberdctl register '.$login.' nayavu.com '.$password1);
sleep(0.1);
exec("sudo -u ejabberd /usr/sbin/ejabberdctl add-rosteritem admin nayavu.com ".$login." nayavu.com ".$login."@nayavu.com Message none");
sleep(0.1);
exec("sudo -u ejabberd /usr/sbin/ejabberdctl add-rosteritem ".$login." nayavu.com admin nayavu.com admin@nayavu.com Message none");

$ret_data_array=explode("---", $retarr[0]);
setcookie($ret_data_array[0], $ret_data_array[1], $ret_data_array[2], "/", "", 0, true);

# записываем cookie логина и пароля пользователя
SetCookie("login_user", $login, time()+60*60*24*30, '/', "", 0, true);
SetCookie("password_user", $md5password, time()+60*60*24*30, '/', "", 0, true);
$login_from_db=$login;
# ставим флаг того, что пользователь авторизован, существует
$num_of_such_register_user = true;
# добавляем новые данные в таблицу пользователей
mysql_query("insert into user (login, password, email, ip, timezone, lostpasscode) values ('$login', '$md5password', '$email', '$ip', '$timezone_arr[0]', '')");
# выдаем сообщение об успешной регистрации пользователя
$result_message[]=array("message" => register_process_complete, "class" => good);
}

# конец, post
}

# регистрируем сессию и заносим данные нового секретного кода
$_SESSION["secret_number_register"]=rand(100000,999999);

# подключение файла верхней части дизайна страницы
require(dirname(__FILE__)."/templates/".name_template_project."/index/header.php");

# если есть сообщения, то выводим их
if (isset($result_message)) {
foreach($result_message as $key => $value) {
view_message($value["message"], $value["class"]);
 }
}

# начало, проверка статуса регистрации
if ($value["class"] == "good") {
?>
<p align="center"><a href="/<? echo $language_code ?>/index.html"><? echo register_link_next ?></a></p><br><br>
<?
} else {
?>
<!-- ##### начало, главная форма ################################# -->

<table border="0" cellpadding="7" cellspacing="0" align="center" width="60%">

  <tr>
    <td height="55" valign="top">&nbsp;<a href="/<? echo $language_code ?>/index.html"><? echo register_main_page ?></a>&nbsp;&nbsp;→&nbsp;&nbsp;<font class="title"><? echo register_workspace_title ?></font></td>
  </tr>

  <tr>
    <td>

<table border="0" cellspacing="0" cellpadding="8" align="center" width="100%">
<form action="/<? echo $language_code ?>/register.html" method="post">

  <tr>
    <td><b><? echo register_login ?>:</b></td>
    <td><nobr><input name="login" type="text" size="30" maxlength="30" value="<? if (isset($login)) { echo $login; } ?>">&nbsp;<font class="important">*</font></nobr></td>
    <td><? echo register_login_notice ?></td>
  </tr>

  <tr>
    <td><b><? echo register_password1 ?>:</b></td>
    <td><nobr><input name="password1" type="password" size="30" maxlength="50">&nbsp;<font class="important">*</font></nobr></td>
    <td><? echo register_password_notice ?></td>
  </tr>

  <tr>
    <td><b><? echo register_password2 ?>:</b></td>
    <td><nobr><input name="password2" type="password" size="30" maxlength="50">&nbsp;<font class="important">*</font></nobr></td>
    <td></td>
  </tr>

  <tr>
    <td><b><? echo register_email ?>:</b></td>
    <td><nobr><input name="email" type="text" size="30" maxlength="50" value="<? if (isset($email)) { echo $email; } ?>">&nbsp;<font class="important">*</font></nobr></td>
    <td><? echo register_email_notice ?></td>
  </tr>

  <tr>
    <td><b><? echo register_timezone ?>:</b></td>
    <td>
<select name="timezone">
<?
$timezone_identifiers = DateTimeZone::listIdentifiers();
foreach( $timezone_identifiers as $value ){
if ( preg_match( '/^(Africa|America|Antarctica|Arctic|Asia|Atlantic|Australia|Europe|Indian|Pacific|Others)\//', $value ) ) {
$ex=explode("/",$value);
if ($continent!=$ex[0]){
if ($continent!="") echo '</optgroup>';
echo '<optgroup label="'.$ex[0].'">';
}
$city=$ex[1];
$continent=$ex[0];
date_default_timezone_set($value);

# +0400
$timez_sm_a=substr(date("O"), 0, 3);
$timez_sm_b=substr(date("O"), 3, 2);
$timez_sm=$timez_sm_a.":".$timez_sm_b;
if (strpos($timez_sm, "10")) {
$timez_sm_int=str_replace("0", "", $timez_sm_b);
$timez_sm_int=$timez_sm_a.$timez_sm_int;
} else {
if ($timez_sm_b > 0) {
$timez_sm_int=str_replace("0", "", $timez_sm_a.".".$timez_sm_b);
  } else {
$timez_sm_int=str_replace("0", "", $timez_sm_a.$timez_sm_b);
 }
}
if (strlen($timez_sm_int)==1) {
$timez_sm_int=0;
}

if ( (strstr($timezone, $value)) || ($timezone == $value) ) {
echo '<option selected value="'.$value.'='.$timez_sm_int.'">('.date("d.m.Y, H:i").') '.$city.' ('.$timez_sm_int.')</option>';
} else {
echo '<option value="'.$value.'='.$timez_sm_int.'">('.date("d.m.Y, H:i").') '.$city.' ('.$timez_sm_int.')</option>';
  }       
 }
}
?>
</optgroup>
</select>
    </td>
    <td><? echo register_timezone_notice ?></td>
  </tr>

  <tr>
    <td><b><? echo lostpass_secretcode ?></b></td>
    <td>
      <img src="/core/imagecode.html?secret_number_name=secret_number_register"><br><br>
      <input type="text" size="6" maxlength="6" name="secretcode">&nbsp;<font class="important">*</font>
    </td>
    <td></td>
  </tr>

  <tr>
    <td valign="top"><b><? echo register_rules ?></b></td>
    <td colspan="2"><textarea cols="80" rows="5" name="rule"><? echo register_rules_text ?></textarea></td>
  </tr>

  <tr>
    <td colspan="3" align="center" height="70"><input type="submit" name="submit_register" value="<? echo register_button ?>"></td>
  </tr>

  <tr>
    <td colspan="3" align="center">&nbsp;<font class="important">*</font> - <? echo register_field_important ?></td>
  </tr>

</form>
</table>

    </td>
  </tr>
</table>
<!-- ##### конец, главная форма ################################## -->
<?

# конец, проверка статуса регистрации
}

# подключение файла нижней части дизайна страницы
require(dirname(__FILE__)."/templates/".name_template_project."/index/footer.php");

# конец, если пользователь уже регистрировался с таким же ip
 }
# конец, если пользователь авторизован
}
?>