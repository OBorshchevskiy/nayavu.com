<?
# подключение файла с настройками конфигурации
require("../core/config.php");

# подключение файла с функциями
require("../core/function.php");

# подключение файла осуществляющего связь с базой данных
require("../core/connect.php");

# поиск пользователя в таблице
$user_data_query=mysql_query("select * from admin where(login='$_COOKIE[login_admin]' and password='$_COOKIE[password_admin]')");
$num_of_user_data=mysql_num_rows($user_data_query);

# начало, если пользователь авторизован и действительно администратор, то далее
if ($num_of_user_data) {

# получение данных пользователя
$user_data=mysql_fetch_assoc($user_data_query);

# начало, post
if (($_SERVER['REQUEST_METHOD']=='POST') && (isset($_POST['submit_profile']))) {

# получения данных с формы
foreach($_POST as $key => $_POST['key']) {
# приведение к безопасному виду
$value=convert_post($_POST['key'], "0");
$$key=$value;
}

# смотрим заполнено ли первое поле пароля
if ($password1 || $password2) {
if (!$password1) {
# заносим в массив сообщений текст ошибки
$result_message[]=array("message" => profile_password1_empty, "class" => bad);
} else {
# смотрим заполнено ли второе поле пароля
if (!$password2) {
# заносим в массив сообщений текст ошибки
$result_message[]=array("message" => profile_password2_empty, "class" => bad);
} else {
# смотрим совпадают ли пароли
if ($password1 <> $password2) {
$result_message[]=array("message" => profile_password1_password2_error, "class" => bad);
} else {
# проверка пароля на длину
if (utf8_count_chars($password1) < 6) {
# заносим в массив сообщений текст ошибки
$result_message[]=array("message" => profile_password_error, "class" => bad);
    }
   }
  }
 }
}

# заполнено ли поле имени
if (!$firstname) {
# заносим в массив сообщений текст ошибки
$result_message[]=array("message" => profile_firstname_empty, "class" => bad);
} else {
# проверка имени на правильность
if (!preg_match("/^[a-z".chr(0x80)."-".chr(0xFF)."-]+$/i", $firstname) || (utf8_count_chars($firstname) < 2)) {
# заносим в массив сообщений текст ошибки
$result_message[]=array("message" => profile_firstname_error, "class" => bad);
 }
}

# заполнено ли поле фамилии
if (!$lastname) {
$result_message[]=array("message" => profile_lastname_empty, "class" => bad);
} else {
# проверка фамилии на правильность
if (!preg_match("/^[a-z".chr(0x80)."-".chr(0xFF)."-]+$/i", $lastname) || (utf8_count_chars($lastname) < 2)) {
# заносим в массив сообщений текст ошибки
$result_message[]=array("message" => profile_lastname_error, "class" => bad);
 }
}

# смотрим заполнено ли поле email
if (!$email) {
$result_message[]=array("message" => profile_email_empty, "class" => bad);
} else {
# проверка e-mail на правильность
if (!preg_match('/^[\.\-_A-Za-z0-9]+?@[\.\-A-Za-z0-9]+?\.[A-Za-z0-9]{2,6}$/', $email)) {
# заносим в массив сообщений текст ошибки
$result_message[]=array("message" => profile_email_error, "class" => bad);
} else {
# смотрим регистрировался ли уже пользователь с таким e-mail
$num_of_such_email=mysql_query("select email from admin where (email='$email' && login<>'$user_data[login]')");
if (mysql_num_rows($num_of_such_email)) {
# заносим в массив сообщений текст ошибки
$result_message[]=array("message" => profile_email_dublicate, "class" => bad);
  }
 }
}

# если ошибок в процессе проверки данных нет, то продолжаем операции с данными
if (!isset($result_message)) {

# смотрим изменились ли данные по сравнению с теми, которые в таблице
if ( (!empty($password1) && (md5($password1) <> $user_data['password'])) || ($firstname <> $user_data['firstname']) || ($lastname <> $user_data['lastname']) || ($email <> $user_data['email']) ) {

# если пароль изменился, то обновляем cookie
if (!empty($password1)) {
if (md5($password1) <> $user_data['password']) {
# преобразуем пароль в md5 хэш
$md5password=md5($password1);
# ставим флаг того, что пользователь авторизован, существует
$num_of_such_register_user = true;
# обновляем данные в таблице admin, только пароль
mysql_query("update admin set password='$md5password' where (login='$_COOKIE[login_admin]' and password='$user_data[password]')");
# для того, чтобы далее правильно отработал запрос
$user_data[password] = $md5password;
# записываем новые cookie пароля администратора
SetCookie("password_admin", $md5password, time()+60*60*24*30, '/', "", 0, true);
 }
}

# обновляем данные в таблице admin, кроме пароля
mysql_query("update admin set firstname='$firstname', lastname='$lastname', email='$email' where (login='$_COOKIE[login_admin]' and password='$user_data[password]')");
# выдаем сообщение об успешном обновлении данных пользователя
$result_message[]=array("message" => profile_update_complete, "class" => good);
} else {
# выдаем сообщение о том, что данные не изменялись
$result_message[]=array("message" => profile_data_not_change, "class" => bad);
 }
}

# конец, post
} else {
$firstname = $user_data['firstname'];
$lastname = $user_data['lastname'];
$email =  $user_data['email'];
}

# подключение файла верхней части дизайна страницы
require("../templates/".name_template_project."/admin/header.php");

# если есть сообщения, то выводим их
if (isset($result_message)) {
foreach($result_message as $key => $value) {
view_message($value["message"], $value["class"]);
 }
}

?>
<!-- ##### начало, главная форма ################################# -->

<table border="0" cellpadding="3" cellspacing="0" align="center" width="80%">

  <tr>
    <td class="title">&nbsp;&nbsp;<? echo profile_workspace_title ?></td>
  </tr>

  <tr>
    <td height="20">&nbsp;</td>
  </tr>

  <tr>
    <td>

<table border="0" cellspacing="0" cellpadding="8" align="center" width="90%" class="data_box">
<form action="/<? echo $language_code ?>/admin/profile.html" method="post">

  <tr class="data_box">
    <td><? echo profile_login ?>:</td>
    <td><input disabled name="login" type="text" size="30" maxlength="30" value="<? echo $user_data[login] ?>"></td>
    <td><div class="notice"><? echo profile_login_notice ?></div></td>
  </tr>

  <tr class="data_box">
    <td><? echo profile_password1 ?>:</td>
    <td><nobr><input name="password1" type="password" size="50" maxlength="50"></nobr></td>
    <td><div class="notice"><? echo profile_password_notice ?></div></td>
  </tr>

  <tr class="data_box">
    <td><? echo profile_password2 ?>:</td>
    <td><nobr><input name="password2" type="password" size="50" maxlength="50"></nobr></td>
    <td></td>
  </tr>

  <tr class="data_box">
    <td><? echo profile_firstname ?>:</td>
    <td><nobr><input name="firstname" type="text" size="50" maxlength="50" value="<? if (isset($firstname)) { echo $firstname; } ?>">&nbsp;<font class="important">*</font></nobr></td>
    <td><div class="notice"><? echo profile_firstname_notice ?></div></td>
  </tr>

  <tr class="data_box">
    <td><? echo profile_lastname ?>:</td>
    <td><input name="lastname" type="text" size="50" maxlength="50" value="<? if (isset($lastname)) { echo $lastname; } ?>">&nbsp;<font class="important">*</font></nobr></td>
    <td><div class="notice"><? echo profile_lastname_notice ?></div></td>
  </tr>

  <tr class="data_box">
    <td><? echo profile_email ?>:</td>
    <td><input name="email" type="text" size="50" maxlength="50" value="<? if (isset($email)) { echo $email; } ?>">&nbsp;<font class="important">*</font></nobr></td>
    <td><div class="notice"><? echo profile_email_notice ?></div></td>
  </tr>

  <tr class="data_box" >
    <td colspan="3" align="center"><input type="submit" name="submit_profile" value="<? echo profile_button_change ?>"></td>
  </tr>

  <tr class="data_box" >
    <td colspan="3" align="center">&nbsp;<font class="important">*</font> - <font class="notice"><? echo profile_field_important ?></font></td>
  </tr>

</form>
</table>

    </td>
  </tr>
</table>
<!-- ##### конец, главная форма ################################## -->
<?

# подключение файла нижней части дизайна страницы
require("../templates/".name_template_project."/admin/footer.php");

# конец, если пользователь авторизован и действительно администратор, то далее
}
?>