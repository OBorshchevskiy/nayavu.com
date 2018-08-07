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

# стартовать сессию только тем, у кого уже стартовала сессия
if (isset($_REQUEST[session_name()])) {
session_start();
}

# подключение файла осуществляющего связь с базой данных
require("core/connect.php");

###################################################################################################
### начало, удаление пользователя только из профиля посетителя ####################################
###################################################################################################
function delete_from_profile($id_registered_user, $id_monitoring_user) {
# удаляем пользователя из профиля
mysql_query("delete from vkontakte_user_monitoring_in_profile where (id_registered_user='$id_registered_user' && id_monitoring_user='$id_monitoring_user')");
sleep(1);
# вывод сообщения о том, что пользователь успешно удален
view_message(delete_vk_user_in_profile_complete, "good");
}
###################################################################################################
### конец, удаление пользователя только из профиля посетителя #####################################
###################################################################################################

###################################################################################################
### начало, удаление пользователя полностью #######################################################
###################################################################################################
function delete_from_all($id_vk_user, $id_monitoring_user, $avatar_link) {

# удаляем пользователя из всех профилей
mysql_query("delete from vkontakte_user_monitoring_in_profile where id_monitoring_user='$id_monitoring_user'");
# удаляем всю статистику
mysql_query("delete from vkontakte_user_online_log where id_monitoring_user='$id_monitoring_user'");
mysql_query("delete from vkontakte_user_online_log_mobile where id_monitoring_user='$id_monitoring_user'");
mysql_query("delete from vkontakte_user_status_log where id_vk_user='$id_vk_user'");
mysql_query("delete from vkontakte_user_friends_log where id_vk_user='$id_vk_user'");
mysql_query("delete from vkontakte_user_friends_cron where id_vk_user='$id_vk_user'");
mysql_query("delete from vkontakte_user_friends_change where id_vk_user='$id_vk_user'");
mysql_query("delete from vkontakte_user_friends_update where id_vk_user='$id_vk_user'");
mysql_query("delete from vkontakte_user_friends_view where id_vk_user='$id_vk_user'");
# удаляем самого пользователя
mysql_query("delete from vkontakte_user_to_monitoring where id_monitoring_user='$id_monitoring_user'");
# начало, удаляем аватарку
if ( (!strpos($avatar_link, "question")) && (!strpos($avatar_link, "deactivated")) && (!strpos($avatar_link, "camera_c")) )  {
# находим имя аватарки
$content_img_path=explode("/", $avatar_link);
$img_name=$content_img_path[count($content_img_path)-1];
# удаляем
unlink("core/vkontakte/avatars/".$img_name);
}
# конец, удаляем аватарку

# вывод сообщения о том, что пользователь успешно удален
view_message(delete_vk_user_in_all_complete, "good");
}
###################################################################################################
### конец, удаление пользователя полностью ########################################################
###################################################################################################

###################################################################################################
### начало, отправка по почте уведомления об удалении посетителя всем пользователям ###############
###################################################################################################
function send_message_all_delete_user($id_monitoring_user, $id_vk_user, $fio_vk_user, $language_code) {

# выбираем из настроек url
$url_query=mysql_query("select data from config_$language_code where (name='url')");
$url=mysql_result($url_query, 0);
# выбираем из настроек e-mail администратора
$email_admin_query=mysql_query("select data from config_$language_code where (name='email_admin')");
$email_admin=mysql_result($email_admin_query, 0);
# выбираем из настроек подпись к письму
$signature_query=mysql_query("select data from config_$language_code where (name='signature')");
$signature=mysql_result($signature_query, 0);

# выбираем посетителей которым был добавлен данный пользователь
$select_users_query=mysql_query("select * from vkontakte_user_monitoring_in_profile where id_monitoring_user='$id_monitoring_user'");

# начало, по порядку перебираем пользователей у которых был данный пользователь
while ($get_select_users=mysql_fetch_array($select_users_query)) {

$user_info_email="";
$user_info_email=mysql_result(mysql_query("select email from user where (id_registered_user='$get_select_users[id_registered_user]')"), 0);

# отсылаем пользователю на почту письмо
putenv("TMPDIR=/tmp");
@mail("$user_info_email", "$url, ".delete_vk_user_in_all_send_notice_topic." $fio_vk_user", delete_vk_user_in_all_send_notice_text." $fio_vk_user (http://vk.com/id$id_vk_user). \r\n\r\n".delete_vk_user_in_all_desc_why_del1."\r\n".delete_vk_user_in_all_desc_why_del2."\r\n".delete_vk_user_in_all_desc_why_del3."\r\n".delete_vk_user_in_all_desc_why_del4."\r\n\r\n $signature", "from: $email_admin\r\nreply-to: $email_admin\r\ncontent-type: text/plain; charset=utf-8\r\ncontent-transfer-encoding: 8bit");
 }
# конец, по порядку перебираем пользователей у которых был данный пользователь
}
###################################################################################################
### конец, отправка по почте уведомления об удалении посетителя всем пользователям ################
###################################################################################################

###################################################################################################
### начало, отправка администратору сайта сообщения с просьбой об удалении пользователя ###########
###################################################################################################
function send_message_admin_delete_user($login_user, $ip_address, $id_monitoring_user, $id_vk_user, $fio_vk_user, $why_delete, $language_code) {

# защита от частых запросов
if (isset($_SESSION['submit_mail_to_admin'])) {
if ((time()-$_SESSION['submit_mail_to_admin'])< 60) {
exit;
 }
}

# устанавливаем в сессии время последнего обращения
$_SESSION['submit_mail_to_admin'] = time();

# выбираем из настроек url
$url_query=mysql_query("select data from config_$language_code where (name='url')");
$url=mysql_result($url_query, 0);
# выбираем из настроек e-mail администратора
$email_admin_query=mysql_query("select data from config_$language_code where (name='email_admin')");
$email_admin=mysql_result($email_admin_query, 0);
# выбираем из настроек подпись к письму
$signature_query=mysql_query("select data from config_$language_code where (name='signature')");
$signature=mysql_result($signature_query, 0);
# куда отсылать письма
$user_info_email="***";
# отсылаем пользователю на почту письмо
putenv("TMPDIR=/tmp");
@mail("$user_info_email", "$url, ".delete_vk_user_admin_delete_send_notice_topic." $fio_vk_user", delete_vk_user_admin_delete_send_notice_text." $fio_vk_user (http://vk.com/id$id_vk_user).\r\n Login User: $login_user \r\n IP Address: $ip_address \r\n  ID monitoring User: $id_monitoring_user \r\n Why Delete: $why_delete \r\n\r\n $signature", "from: $email_admin\r\nreply-to: $email_admin\r\ncontent-type: text/plain; charset=utf-8\r\ncontent-transfer-encoding: 8bit");
# вывод сообщения о том, что письмо успешно отправлено
view_message(delete_vk_user_send_message_to_admin, "good");
}
###################################################################################################
### конец, отправка администратору сайта сообщения с просьбой об удалении пользователя ############
###################################################################################################

# подключение файла верхней части дизайна страницы"
include("templates/".name_template_project."/index/header.php");

###################################################################################################
# начало, удалить из профиля пользователя #########################################################
###################################################################################################
if ( isset($_GET['id_vk_user']) && ($_GET['mode'] == "profile") ) {
# определяем id_monitoring_user пользователя
$id_vk_user = convert_post($_GET['id_vk_user'], "0");
# проверяем, если ли в базе этот пользователь
$data_monitoring_user_query=mysql_query("select * from vkontakte_user_to_monitoring where id_vk_user='$id_vk_user'");
# начало, если такого пользователя не существует, то ошибка
if (!mysql_num_rows($data_monitoring_user_query)) {
view_message(delete_vk_user_id_not_exist, "bad");
?>
<p align="center"><a href="/<? echo $language_code ?>/index.html"><? echo delete_vk_user_link_next ?></a></p><br><br><br>
<?
} else {
$data_monitoring_user=mysql_fetch_assoc($data_monitoring_user_query);
# определяем некоторые данные пользователя
$id_monitoring_user=$data_monitoring_user["id_monitoring_user"];
$fio_vk_user=$data_monitoring_user["fio_vk_user"];
$avatar_link=$data_monitoring_user["avatar_vk_user"];

# начало, если пользователь авторизован
if ($num_of_user_data) {
# начало, если данный пользователь есть в профиле текущего посетителя
if (mysql_num_rows(mysql_query("select * from vkontakte_user_monitoring_in_profile where(id_registered_user='$id_registered_user' && id_monitoring_user='$id_monitoring_user')"))) {
delete_from_profile($id_registered_user, $id_monitoring_user);
?>
<p align="center"><a href="/<? echo $language_code ?>/index.html"><? echo delete_vk_user_link_next ?></a></p><br><br><br>
<?
   }
# конец, если данный пользователь есть в профиле текущего посетителя
  }
# конец, если пользователь авторизован
 }
# конец, если такого пользователя не существует, то ошибка
}
###################################################################################################
# конец, удалить из профиля пользователя ##########################################################
###################################################################################################

###################################################################################################
# начало, удалить полностью пользователя ##########################################################
###################################################################################################
if ( isset($_GET['id_vk_user']) && ($_GET['mode'] == "all") ) {
# определяем id_monitoring_user пользователя
$id_vk_user = convert_post($_GET['id_vk_user'], "0");
# проверяем, если ли в базе этот пользователь
$data_monitoring_user_query=mysql_query("select * from vkontakte_user_to_monitoring where id_vk_user='$id_vk_user'");
# начало, если такого пользователя не существует, то ошибка
if (!mysql_num_rows($data_monitoring_user_query)) {
view_message(delete_vk_user_id_not_exist, "bad");
?>
<p align="center"><a href="/<? echo $language_code ?>/index.html"><? echo delete_vk_user_link_next ?></a></p><br><br><br>
<?
} else {
$data_monitoring_user=mysql_fetch_assoc($data_monitoring_user_query);
# определяем некоторые данные пользователя
$id_monitoring_user=$data_monitoring_user["id_monitoring_user"];
$fio_vk_user=$data_monitoring_user["fio_vk_user"];
$avatar_link=$data_monitoring_user["avatar_vk_user"];

# начало, если пользователь авторизован
if ($num_of_user_data) {

# смотрим, у скольких посетителей данный пользователь в профиле
$num_profiles_with_user=mysql_num_rows(mysql_query("select * from vkontakte_user_monitoring_in_profile where(id_monitoring_user='$id_monitoring_user')"));

###################################################################################################
# начало, если данный пользователь только в профиле текущего посетителя ###########################
###################################################################################################
if (($num_profiles_with_user == 1) && mysql_num_rows(mysql_query("select * from vkontakte_user_monitoring_in_profile where(id_registered_user='$id_registered_user' && id_monitoring_user='$id_monitoring_user')"))) {
# удаляем полностью пользователя
delete_from_all($id_vk_user, $id_monitoring_user, $avatar_link);
?>
<p align="center"><a href="/<? echo $language_code ?>/index.html"><? echo delete_vk_user_link_next ?></a></p><br><br><br>
<?

###################################################################################################
# если пользователь в нескольких профилях посетителей и в том числе и у текущего ##################
###################################################################################################
} elseif ( ($id_registered_user == 1) || (($num_profiles_with_user > 1) && mysql_num_rows(mysql_query("select * from vkontakte_user_monitoring_in_profile where(id_monitoring_user='$id_monitoring_user' && id_registered_user='$id_registered_user')"))) ) {

###################################################################################################
# если текущий пользователь первый добавил данного посетителя #####################################
###################################################################################################
if ( ($id_registered_user == 1) || ((mysql_result(mysql_query("select id_registered_user from vkontakte_user_monitoring_in_profile where id_monitoring_user='$id_monitoring_user' order by id"), 0)) == $id_registered_user) ) {
# рассылаем всем уведомление о удалении пользователя
send_message_all_delete_user($id_monitoring_user, $id_vk_user, $fio_vk_user, $language_code);
# удаляем полностью пользователя
delete_from_all($id_vk_user, $id_monitoring_user, $avatar_link);
?>
<p align="center"><a href="/<? echo $language_code ?>/index.html"><? echo delete_vk_user_link_next ?></a></p><br><br><br>
<?

###################################################################################################
# если текущий пользователь НЕ первый добавил данного посетителя ##################################
###################################################################################################
} else {
# приводим к безопасному виду
$why_delete_vk_user=convert_post($_POST['why_delete_vk_user'], "0");
if ((utf8_count_chars($why_delete_vk_user)<5) || (utf8_count_chars($why_delete_vk_user)>1000)) {
# вывод сообщения о том, что недопустимая причина
view_message(delete_vk_user_why_text_error, "bad");
# для повтора
$_GET['mode']=null;
} else {
# отправка администратору уведомления с просьбой об удалении пользователя
send_message_admin_delete_user($user_data["login"], ip_detect(), $id_monitoring_user, $id_vk_user, $fio_vk_user, $why_delete_vk_user, $language_code);
?>
<p align="center"><a href="/<? echo $language_code ?>/index.html"><? echo delete_vk_user_link_next ?></a></p><br><br><br>
<?
 }
}
###################################################################################################
# если у посетителя нет такого пользователя в профиле #############################################
###################################################################################################
} elseif ($num_profiles_with_user && !mysql_num_rows(mysql_query("select * from vkontakte_user_monitoring_in_profile where(id_monitoring_user='$id_monitoring_user' && id_registered_user='$id_registered_user')"))) {
# приводим к безопасному виду
$why_delete_vk_user=convert_post($_POST['why_delete_vk_user'], "0");
if ((utf8_count_chars($why_delete_vk_user)<5) || (utf8_count_chars($why_delete_vk_user)>1000)) {
# вывод сообщения о том, что недопустимая причина
view_message(delete_vk_user_why_text_error, "bad");
# для повтора
$_GET['mode']=null;
} else {
# отправка администратору уведомления с просьбой об удалении пользователя
send_message_admin_delete_user($user_data["login"], ip_detect(), $id_monitoring_user, $id_vk_user, $fio_vk_user, $why_delete_vk_user, $language_code);
?>
<p align="center"><a href="/<? echo $language_code ?>/index.html"><? echo delete_vk_user_link_next ?></a></p><br><br><br>
<?
 }
}
# конец, если данный пользователь только в профиле текущего посетителя

# если пользователь неавторизован
} else {
# приводим к безопасному виду
$email_not_auth_user=convert_post($_POST['email_not_auth_user'], "0");
$why_delete_vk_user=convert_post($_POST['why_delete_vk_user'], "0");

# смотрим заполнено ли поле email
if (!$email_not_auth_user) {
$message_style = view_message(delete_vk_user_email_empty, "bad");
} else {
# проверка e-mail на правильность
if (!preg_match('/^[\.\-_A-Za-z0-9]+?@[\.\-A-Za-z0-9]+?\.[A-Za-z0-9]{2,6}$/', $email_not_auth_user)) {
# заносим в массив сообщений текст ошибки
$message_style = view_message(delete_vk_user_email_error, "bad");
 }
}

if ((utf8_count_chars($why_delete_vk_user)<5) || (utf8_count_chars($why_delete_vk_user)>1000)) {
# вывод сообщения о том, что недопустимая причина
$message_style = view_message(delete_vk_user_why_text_error, "bad");
# для повтора
$_GET['mode']=null;
}
if ($message_style<>"bad") {
# отправка администратору уведомления с просьбой об удалении пользователя
send_message_admin_delete_user("Анонимный посетитель", ip_detect(), $id_monitoring_user, $id_vk_user, $fio_vk_user, "Email: ".$email_not_auth_user." - ".$why_delete_vk_user, $language_code);
?>
<p align="center"><a href="/<? echo $language_code ?>/index.html"><? echo delete_vk_user_link_next ?></a></p><br><br><br>
<?
   }
  }
# конец, если пользователь авторизован
 }
# конец, если такого пользователя не существует, то ошибка
}
###################################################################################################
# конец, удалить полностью пользователя ###########################################################
###################################################################################################

###################################################################################################
# начало, запрос на удаление пользователя #########################################################
###################################################################################################
if ( isset($_GET['id_vk_user']) && (!isset($_GET['mode'])) ) {
# определяем id_monitoring_user пользователя
$id_vk_user = convert_post($_GET['id_vk_user'], "0");
# проверяем, если ли в базе этот пользователь
$data_monitoring_user_query=mysql_query("select * from vkontakte_user_to_monitoring where id_vk_user='$id_vk_user'");
# начало, если такого пользователя не существует, то ошибка
if (!mysql_num_rows($data_monitoring_user_query)) {
view_message(delete_vk_user_id_not_exist, "bad");
?>
<p align="center"><a href="/<? echo $language_code ?>/index.html"><? echo delete_vk_user_link_next ?></a></p><br><br><br>
<?
} else {
$data_monitoring_user=mysql_fetch_assoc($data_monitoring_user_query);
# определяем некоторые данные пользователя
$id_monitoring_user=$data_monitoring_user["id_monitoring_user"];
$fio_vk_user=$data_monitoring_user["fio_vk_user"];
$avatar_link=$data_monitoring_user["avatar_vk_user"];
# начало, если пользователь авторизован
if ($num_of_user_data) {

# смотрим, у скольких посетителей данный пользователь в профиле
$num_profiles_with_user=mysql_num_rows(mysql_query("select * from vkontakte_user_monitoring_in_profile where(id_monitoring_user='$id_monitoring_user')"));

###################################################################################################
# начало, если данный пользователь только в профиле текущего посетителя ###########################
###################################################################################################
if (($num_profiles_with_user == 1) && mysql_num_rows(mysql_query("select * from vkontakte_user_monitoring_in_profile where(id_registered_user='$id_registered_user' && id_monitoring_user='$id_monitoring_user')"))) {

?>
<table width="60%" align="center">
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><? echo delete_vk_user_only_from_profile ?></td>
  </tr>
  <tr>
    <td align="center">→&nbsp;<a href='<? echo "/".$language_code."/delete_vk_user/id_vk_user/".$id_vk_user ?>/mode/profile'><b><? echo delete_vk_user_delete_link_profile ?>&nbsp;-&nbsp;"<? echo $fio_vk_user ?>"</b></a></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><? echo delete_vk_user_all ?></td>
  </tr>
  <tr>
    <td align="center">→&nbsp;<a href='<? echo "/".$language_code."/delete_vk_user/id_vk_user/".$id_vk_user ?>/mode/all'><b><? echo delete_vk_user_delete_link_all ?>&nbsp;-&nbsp;"<? echo $fio_vk_user ?>"</b></a></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>
<?

###################################################################################################
# если пользователь в нескольких профилях посетителей и в том числе и у текущего ##################
###################################################################################################
} elseif ( ($id_registered_user == 1) || (($num_profiles_with_user > 1) && mysql_num_rows(mysql_query("select * from vkontakte_user_monitoring_in_profile where(id_monitoring_user='$id_monitoring_user' && id_registered_user='$id_registered_user')"))) ) {
?>
<table width="60%" align="center">
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><? echo delete_vk_user_only_from_profile ?></td>
  </tr>
  <tr>
    <td align="center">→&nbsp;<a href='<? echo "/".$language_code."/delete_vk_user/id_vk_user/".$id_vk_user ?>/mode/profile'><b><? echo delete_vk_user_delete_link_profile ?>&nbsp;-&nbsp;"<? echo $fio_vk_user ?>"</b></a></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>

<?
# если текущий пользователь первый добавил данного посетителя
if ( ($id_registered_user == 1) || ((mysql_result(mysql_query("select id_registered_user from vkontakte_user_monitoring_in_profile where id_monitoring_user='$id_monitoring_user' order by id"), 0)) == $id_registered_user) ) {
?>
  <tr>
    <td><? echo delete_vk_user_all ?></td>
  </tr>
  <tr>
    <td align="center">→&nbsp;<a href='<? echo "/".$language_code."/delete_vk_user/id_vk_user/".$id_vk_user ?>/mode/all'><b><? echo delete_vk_user_delete_link_all ?>&nbsp;-&nbsp;"<? echo $fio_vk_user ?>"</b></a></td>
  </tr>
<?
# если текущий пользователь НЕ первый добавил данного посетителя
} else {
?>
  <tr>
    <td><? echo delete_vk_user_all_why ?></td>
  </tr>
<form action='<? echo "/".$language_code."/delete_vk_user/id_vk_user/".$id_vk_user ?>/mode/all' method="post">
  <tr>
    <td align="center"><b><? echo delete_vk_user_all_why_from_user ?></b></td>
  </tr>
  <tr>
    <td align="center"><textarea cols="60" rows="5" name="why_delete_vk_user" id="why_delete_vk_user"></textarea></td>
  </tr>
  <tr>
    <td align="center" height="40"><input type="submit" name="delete_vk_user_all" value="<? echo delete_vk_user_delete_button_all.'&nbsp;-&nbsp;&quot;'.$fio_vk_user ?>&quot;"></td>
  </tr>
</form>
<?
}
?>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>
<?

###################################################################################################
# если у посетителя нет такого пользователя в профиле #############################################
###################################################################################################
} elseif ($num_profiles_with_user && !mysql_num_rows(mysql_query("select * from vkontakte_user_monitoring_in_profile where(id_monitoring_user='$id_monitoring_user' && id_registered_user='$id_registered_user')"))) {

?>
<table width="60%" align="center">
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><? echo delete_vk_user_not_in_profile_all_why ?></td>
  </tr>
<form action='<? echo "/".$language_code."/delete_vk_user/id_vk_user/".$id_vk_user ?>/mode/all' method="post">
  <tr>
    <td align="center"><b><? echo delete_vk_user_all_why_from_user ?></b></td>
  </tr>
  <tr>
    <td align="center"><textarea cols="60" rows="5" name="why_delete_vk_user" id="why_delete_vk_user"></textarea></td>
  </tr>
  <tr>
    <td align="center" height="40"><input type="submit" name="delete_vk_user_all" value="<? echo delete_vk_user_delete_button_all.'&nbsp;-&nbsp;&quot;'.$fio_vk_user ?>&quot;"></td>
  </tr>
</form>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>
<?

# если данного пользователя нет ни в одном профиле
} elseif (!$num_profiles_with_user) {
view_message(delete_vk_user_id_not_in_profile, "bad");
?>
<p align="center"><a href="/<? echo $language_code ?>/index.html"><? echo delete_vk_user_link_next ?></a></p><br><br><br>
<?
}
# конец, если данный пользователь только в профиле текущего посетителя

# если пользователь неавторизован
} else {
?>
<table width="60%" align="center">
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><? echo delete_vk_user_not_auth_all_why ?></td>
  </tr>
<form action='<? echo "/".$language_code."/delete_vk_user/id_vk_user/".$id_vk_user ?>/mode/all' method="post">
  <tr>
    <td align="center" height="40"><b><? echo delete_vk_user_email_not_auth_user ?></b></td>
  </tr>
  <tr>
    <td align="center"><input type="text" size="30" maxlength="50" name="email_not_auth_user"></td>
  </tr>
  <tr>
    <td align="center"><b><? echo delete_vk_user_all_why_from_user ?></b></td>
  </tr>
  <tr>
    <td align="center"><textarea cols="60" rows="5" name="why_delete_vk_user" id="why_delete_vk_user"></textarea></td>
  </tr>
  <tr>
    <td align="center" height="40"><input type="submit" name="delete_vk_user_all" value="<? echo delete_vk_user_delete_button_all.'&nbsp;-&nbsp;&quot;'.$fio_vk_user ?>&quot;"></td>
  </tr>
</form>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>
<?
  }
 }
# конец, если такого пользователя не существует, то ошибка
}
###################################################################################################
# конец, запрос на удаление пользователя ##########################################################
###################################################################################################

# если есть сообщения, то выводим их
if (isset($result_message)) {
foreach($result_message as $key => $value) {
view_message($value["message"], $value["class"]);
 }
}

# подключение файла нижней части дизайна страницы"
include("templates/".name_template_project."/index/footer.php");
?>