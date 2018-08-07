<?
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
if ( ($_SERVER['REQUEST_METHOD']=='POST') && (!isset($_GET['success'])) ) {
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

# поиск пользователя в таблице
$user_data_query=mysql_query("select * from user where(login='$_COOKIE[login_user]' and password='$_COOKIE[password_user]')");
$num_of_user_data=mysql_num_rows($user_data_query);

# начало, если пользователь авторизован, то далее
if ($num_of_user_data) {

# получение данных пользователя
$user_data=mysql_fetch_assoc($user_data_query);

# определяем текущего посетителя
$id_registered_user = $user_data["id_registered_user"];
# определяем баланс посетителя
$balance = $user_data['balance'];
if ($balance == NULL) {
$balance = 0;
}

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
$num_of_such_email=mysql_query("select email from user where (email='$email' && login<>'$user_data[login]')");
if (mysql_num_rows($num_of_such_email)) {
# заносим в массив сообщений текст ошибки
$result_message[]=array("message" => profile_email_dublicate, "class" => bad);
  }
 }
}

# проверяем, есть ли timezone в массиве
if (!$timezone) {
# заносим в массив сообщений текст ошибки
$result_message[]=array("message" => profile_index_timezone_error, "class" => bad);
}

# если ошибок в процессе проверки нет, то продолжаем операции с данными
if (!isset($result_message)) {

# удаляем "+"
$timezone_edit=str_replace("+", "", $timezone);
# разбиваем на название временной зоны и ее номер
$timezone_arr=explode("=", $timezone_edit);

# логин
$login=$user_data["login"];

# смотрим изменились ли данные по сравнению с теми, которые в таблице
if ( (!empty($password1) && (md5($password1) <> $user_data['password'])) || ($email <> $user_data['email']) || ($timezone_arr[0] <> $user_data['timezone']) ) {

$update_pass=false;
# если пароль изменился, то обновляем cookie
if (!empty($password1)) {
if (md5($password1) <> $user_data['password']) {
# преобразуем пароль в md5 хэш
$md5password=md5($password1);
# ставим флаг того, что пользователь авторизован, существует
$num_of_such_register_user = true;
# обновляем данные в таблице user, только пароль
mysql_query("update user set password='$md5password' where (login='$_COOKIE[login_user]' and password='$user_data[password]')");
# для того, чтобы далее правильно отработал запрос
$user_data[password] = $md5password;
$update_pass=true;
# записываем новые cookie пароля пользователя
SetCookie("login_user", $login, time()+60*60*24*30, '/', "", 0, true);
SetCookie("password_user", $md5password, time()+60*60*24*30, '/', "", 0, true);
 }
}

if ($update_pass==true) {
# обновление пароля на форуме
exec("/usr/bin/php5 /var/www/profile_in_forum.php $login $md5password $timezone_arr[1]", $retarr);
$ret_data_array=explode("---", $retarr[0]);
setcookie($ret_data_array[0], $ret_data_array[1], $ret_data_array[2], "/", "", 0, true);
# обновляем пароль на jabber сервере для этого пользователя
exec('sudo -u ejabberd /usr/sbin/ejabberdctl change-password '.$login.' nayavu.com '.$password1);
} elseif ($update_pass==false) {
$password_null="not";
exec("/usr/bin/php5 /var/www/profile_in_forum.php $login $password_null $timezone_arr[1]", $retarr);
}

# обновляем данные в таблице user
mysql_query("update user set email='$email', timezone='$timezone_arr[0]' where (login='$_COOKIE[login_user]' and password='$user_data[password]')");
# выдаем сообщение об успешном обновлении данных пользователя
$result_message[]=array("message" => profile_update_complete, "class" => good);
} else {
# выдаем сообщение о том, что данные не изменялись
$result_message[]=array("message" => profile_data_not_change, "class" => bad);
 }
}

# конец, post
} else {
$email = $user_data['email'];
$timezone =  $user_data['timezone'];
}

# подключение файла верхней части дизайна страницы
require(dirname(__FILE__)."/templates/".name_template_project."/index/header.php");

# начало, если пришли данные о платеже
if (isset($_GET['success'])) {

# получения данных с формы
foreach($_POST as $key => $_POST['key']) {
# приведение к безопасному виду
$value=$_POST['key'];
$$key=$value;
}

# вывод сообщения посетителю
if ($status == 1) {
$result_message[]=array("message" => profile_money_complete_good, "class" => good);
} else {
$result_message[]=array("message" => profile_money_complete_bad, "class" => bad);
}

}
# конец, если пришли данные о платеже

# если есть сообщения, то выводим их
if (isset($result_message)) {
foreach($result_message as $key => $value) {
view_message($value["message"], $value["class"]);
 }
}

$user_num_for_this_user=mysql_num_rows(mysql_query("select * from vkontakte_user_monitoring_in_profile where id_registered_user='$id_registered_user'"));
if (!$user_num_for_this_user) {
$user_num_for_this_user=0;
}

$user_num_all=mysql_num_rows(mysql_query("select * from vkontakte_user_to_monitoring"));

?>
<!-- ##### начало, главная форма ################################# -->

<table border="0" cellpadding="7" cellspacing="0" align="center" width="60%">

  <tr>
    <td height="65" valign="top">&nbsp;<a href="/<? echo $language_code ?>/index.html"><? echo profile_index_main_page ?></a>&nbsp;&nbsp;→&nbsp;&nbsp;<font class="title"><? echo profile_workspace_title ?></font></td>
  </tr>
  
  <tr>
    <td class="line_blue"><b><? echo profile_header_howto ?></b></td>
  </tr>
 
 <tr>
    <td><? echo "<b>".profile_header_howto_your."</b> ".$user_num_for_this_user; ?></td>
  </tr>
  <tr>
    <td><? echo "<b>".profile_header_howto_all."</b> ".$user_num_all; ?></td>
  </tr>
  <tr>
    <td height="15"></td>
  </tr>

  <tr>
    <td class="line_blue"><b><? echo profile_header_pay ?></b></td>
  </tr>

  <tr>
    <td valign="top">
<table border="0" cellspacing="0" cellpadding="3" align="center">
  <tr>
    <td><b><? echo profile_text_your_balance ?></b></td>
    <td><img src="/templates/<? echo name_template_project ?>/index/images/balance.png"></td>
    <td><b><u><? echo $balance ?></u></b></td>
    <td><? echo profile_text_rub ?></td>
    <td><b><u><? echo floor($balance/1.5) ?></u></b></td>
    <td><? echo profile_text_sms_to_send ?></td>
  </tr>
</table>
    </td>
  </tr>

  <tr>
    <td><? echo profile_about_pay ?></td>
  </tr>

  <tr>
    <td align="center">
<table border="0" cellspacing="0" cellpadding="10" align="center" width="60%" class="line_pay">
  <tr>
    <td valign="top" align="right"><nobr><img src="/templates/<? echo name_template_project ?>/index/images/payment.png"></nobr></td>
    <td valign="top" align="center">
<nobr>
<form action='http://sprypay.ru/sppi/' method='post'>
<input type='hidden' name='spShopId' value='7406'>
<input type='hidden' name='spShopPaymentId' value=''>
<input type='text' size="6" maxlength="6" name='spAmount' value='100'>
<select name="spCurrency">
<option selected value="rur"><? echo profile_select_rur ?></option>
<option value="usd"><? echo profile_select_usd ?></option>
<option value="eur"><? echo profile_select_eur ?></option>
<option value="uah"><? echo profile_select_uah ?></option>
</select>
<input type='hidden' name='spPurpose' value='<? echo profile_purpose_where ?>'>
<input type='hidden' name='spUserEmail' value='<? echo $email ?>'>
<input type='hidden' name='spUserDataID' value='<? echo $id_registered_user ?>'>
<input type='submit' value='<? echo profile_button_pay ?>'>
</form>
</nobr>
    </td>
  </tr>
</table>
    </td>
  </tr>

  <tr>
    <td>&nbsp;</td>
  </tr>

  <tr>
    <td class="line_blue"><b><? echo profile_header_accout ?></b></td>
  </tr>

  <tr>
    <td>

<table border="0" cellspacing="0" cellpadding="8" align="center" width="100%">
<form action="/<? echo $language_code ?>/profile.html" method="post">

  <tr>
    <td><b><? echo profile_login ?>:</b></td>
    <td><input disabled name="login" type="text" size="30" maxlength="30" value="<? echo $user_data[login] ?>"></td>
    <td><? echo profile_login_notice ?></td>
  </tr>

  <tr>
    <td><b><? echo profile_password1 ?>:</b></td>
    <td><input name="password1" type="password" size="30" maxlength="50"></td>
    <td><? echo profile_password_notice ?></td>
  </tr>

  <tr>
    <td><nobr><b><? echo profile_password2 ?>:</b></nobr></td>
    <td><input name="password2" type="password" size="30" maxlength="50"></td>
    <td></td>
  </tr>

  <tr>
    <td><b><? echo profile_email ?>:</b></td>
    <td><nobr><input name="email" type="text" size="30" maxlength="50" value="<? if (isset($email)) { echo $email; } ?>">&nbsp;<font class="important">*</font></nobr></td>
    <td><? echo profile_email_notice ?></td>
  </tr>

  <tr>
    <td><nobr><b><? echo profile_index_timezone ?>:</b></nobr></td>
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
    <td><? echo profile_index_timezone_notice ?></td>
  </tr>

  <tr>
    <td colspan="3" align="center" height="70"><input type="submit" name="submit_profile" value="<? echo profile_button_change ?>"></td>
  </tr>

  <tr>
    <td colspan="3" align="center">&nbsp;<font class="important">*</font> - <? echo profile_field_important ?></td>
  </tr>

</form>
</table>

    </td>
  </tr>
</table>
<!-- ##### конец, главная форма ################################## -->
<?

# подключение файла нижней части дизайна страницы
require(dirname(__FILE__)."/templates/".name_template_project."/index/footer.php");

# конец, если пользователь авторизован
}
?>