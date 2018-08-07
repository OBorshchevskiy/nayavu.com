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

###################################################################################################
# начало, добавление СМС в профиль пользователя ###################################################
###################################################################################################
if (($_SERVER['REQUEST_METHOD']=='POST') && (isset($_POST['send_data_sms']))) {

# приведение к безопасному виду
$data_sms=convert_post($_POST['data_sms'], "0");
$data_check_time_sms=convert_post($_POST['data_check_time_sms'], "0");
$accept_sms=convert_post($_POST['accept_sms'], "0");
$sms_from=convert_post($_POST['sms_from'], "0");
$sms_to=convert_post($_POST['sms_to'], "0");
$id_monitoring_user=convert_post($_POST['id_monitoring_user'], "0");

$flag_good1=false;
$flag_good2=false;
$flag_good3=false;
$flag_good4=false;

# если ничего не ввели в поле номера мобильного телефона
if (!$data_sms) {
view_message(add_vk_messenger_send_data_sms_empty, "bad");
} else {
# проверка номера на правильность
if (!ctype_digit($data_sms) || (strlen($data_sms) > 30) || (strlen($data_sms) < 10)) {
view_message(add_vk_messenger_send_data_sms_bad, "bad");
} else {
$flag_good1=true;
 }
}

# проверка правильности временного промежутка
if ( !ctype_digit($data_check_time_sms) || ($data_check_time_sms < 30) || ($data_check_time_sms > 259200) ) {
view_message(add_vk_messenger_send_check_time_bad, "bad");
} else {
$flag_good2=true;
}

# если выбрано не отправлять сообщения и не заданы временные промежутки(когда не присылать)
if (($accept_sms=="on") && (!strlen($sms_from) || !strlen($sms_to))) {
view_message(add_vk_messenger_send_data_sms_time_empty, "bad");
# если не выбрано не отправлять сообщения и заданы временные промежутки(когда не присылать)
} elseif (!$accept_sms && (strlen($sms_from) || strlen($sms_to))) {
view_message(add_vk_messenger_send_data_sms_time_bad, "bad");
# когда выбрано не отправлять сообщения и заданы временные промежутки(когда не присылать)
} elseif (($accept_sms=="on") && strlen($sms_from) && strlen($sms_to)) {
# проверка правильности временного промежутка
if (!ctype_digit($sms_from) || !ctype_digit($sms_to) || ($sms_from < 0) || ($sms_from > 23) || ($sms_to < 0) || ($sms_to > 23) ) {
view_message(add_vk_messenger_send_data_sms_time_bad, "bad");
} else {
$flag_good3=true;
 }
# если ничего не выбрано
} elseif (!$accept_sms && !strlen($sms_from) && !strlen($sms_to)) {
$flag_good4=true;
}

# если все данные верны
if ( ($flag_good1==true) && ($flag_good2==true) && (($flag_good3==true) || ($flag_good4==true)) ) {
# обновляем данные в таблице
if (!strlen($sms_from) && !strlen($sms_to)) {
mysql_query("update vkontakte_user_monitoring_in_profile set time_filter_from_sms=NULL, time_filter_to_sms=NULL, sms='$data_sms', check_time_sms='$data_check_time_sms' where(id_registered_user='$id_registered_user' && id_monitoring_user='$id_monitoring_user')");
} else {
mysql_query("update vkontakte_user_monitoring_in_profile set time_filter_from_sms='$sms_from', time_filter_to_sms='$sms_to', sms='$data_sms', check_time_sms='$data_check_time_sms' where(id_registered_user='$id_registered_user' && id_monitoring_user='$id_monitoring_user')");
}

view_message(add_vk_messenger_send_data_sms_complete, "good");
}

# для того, чтобы было заполнено поле input
if ($flag_good4==true) {
$sms_from=23;
$sms_to=8;
}

}
###################################################################################################
# конец, добавление СМС в профиль пользователя ####################################################
###################################################################################################


###################################################################################################
# начало, добавление ICQ или Mail.Ru агент в профиль пользователя #################################
###################################################################################################
if (($_SERVER['REQUEST_METHOD']=='POST') && (isset($_POST['send_data_messenger']))) {

# приведение к безопасному виду
$data_check_time_messenger=convert_post($_POST['data_check_time_messenger'], "0");
$accept_messenger=convert_post($_POST['accept_messenger'], "0");
$messenger_from=convert_post($_POST['messenger_from'], "0");
$messenger_to=convert_post($_POST['messenger_to'], "0");
$id_monitoring_user=convert_post($_POST['id_monitoring_user'], "0");

$flag_good1=false;
$flag_good2=false;
$flag_good3=false;

# если время проверки введено неправильно
if ( !ctype_digit($data_check_time_messenger) || ($data_check_time_messenger < 30) || ($data_check_time_messenger > 259200) ) {
view_message(add_vk_messenger_send_check_time_bad, "bad");
} else {
$flag_good1=true;
}

# если выбрано не отправлять сообщения и не заданы временные промежутки(когда не присылать)
if (($accept_messenger=="on") && (!strlen($messenger_from) || !strlen($messenger_to))) {
view_message(add_vk_messenger_send_data_messenger_time_empty, "bad");
# если не выбрано не отправлять сообщения и заданы временные промежутки(когда не присылать)
} elseif (!$accept_messenger && (strlen($messenger_from) || strlen($messenger_to))) {
view_message(add_vk_messenger_send_data_messenger_time_bad, "bad");
# когда выбрано не отправлять сообщения и заданы временные промежутки(когда не присылать)
} elseif (($accept_messenger=="on") && strlen($messenger_from) && strlen($messenger_to)) {
# проверка правильности временного промежутка
if (!ctype_digit($messenger_from) || !ctype_digit($messenger_to) || ($messenger_from < 0) || ($messenger_from > 23) || ($messenger_to < 0) || ($messenger_to > 23) ) {
view_message(add_vk_messenger_send_data_messenger_time_bad, "bad");
} else {
$flag_good2=true;
 }
# если ничего не выбрано
} elseif (!$accept_messenger && !strlen($messenger_from) && !strlen($messenger_to)) {
$flag_good3=true;
}

if ( ($flag_good1==true) && (($flag_good2==true) || ($flag_good3==true)) ) {

if (!strlen($messenger_from) && !strlen($messenger_to)) {
# обновляем данные в таблице
mysql_query("update vkontakte_user_monitoring_in_profile set time_filter_from_messenger=NULL, time_filter_to_messenger=NULL, messenger='1', check_time_messenger='$data_check_time_messenger' where(id_registered_user='$id_registered_user' && id_monitoring_user='$id_monitoring_user')");
} else {
mysql_query("update vkontakte_user_monitoring_in_profile set time_filter_from_messenger='$messenger_from', time_filter_to_messenger='$messenger_to', messenger='1', check_time_messenger='$data_check_time_messenger' where(id_registered_user='$id_registered_user' && id_monitoring_user='$id_monitoring_user')");
}

view_message(add_vk_messenger_send_data_messenger_complete, "good");
}

# для того, чтобы было заполнено поле input
if ($flag_good3==true) {
$messenger_from=23;
$messenger_to=8;
}

}
###################################################################################################
# конец, добавление ICQ или Mail.Ru агент в профиль пользователя ##################################
###################################################################################################

###################################################################################################
# начало, удалить отправку сообщений по СМС, ICQ или Mail.Ru агенту ###############################
###################################################################################################
if ( isset($_GET['id_vk_user']) && ($_GET['mode'] == "stop") && ($_GET['item'])) {
# определяем id_monitoring_user пользователя
$id_vk_user = convert_post($_GET['id_vk_user'], "0");
# проверяем, если ли в базе этот пользователь
$data_monitoring_user_query=mysql_query("select * from vkontakte_user_to_monitoring where id_vk_user='$id_vk_user'");
# начало, если такого пользователя не существует, то ошибка
if (!mysql_num_rows($data_monitoring_user_query)) {
view_message(add_vk_messenger_id_not_exist, "bad");
?>
<p align="center"><a href="/<? echo $language_code ?>/index.html"><? echo add_vk_messenger_link_next ?></a></p><br><br><br>
<?
} else {
$data_monitoring_user=mysql_fetch_assoc($data_monitoring_user_query);
# определяем некоторые данные пользователя
$id_monitoring_user=$data_monitoring_user["id_monitoring_user"];
$fio_vk_user=$data_monitoring_user["fio_vk_user"];
# начало, если пользователь авторизован
if ($num_of_user_data) {
# начало, проверяем находится ли в профиле посетителя данный пользователь
$data_option_user_query=mysql_query("select * from vkontakte_user_monitoring_in_profile where(id_registered_user='$id_registered_user' && id_monitoring_user='$id_monitoring_user')");
if (mysql_num_rows($data_option_user_query)) {

# обнуляем данные для СМС
if ($_GET['item']=="sms") {
mysql_query("update vkontakte_user_monitoring_in_profile set time_filter_from_sms=NULL, time_filter_to_sms=NULL, sms=NULL, check_time_sms=NULL, last_online_sms=NULL where(id_registered_user='$id_registered_user' && id_monitoring_user='$id_monitoring_user')");
view_message(add_vk_messenger_delete_data_sms_complete, "good");
}

# обнуляем данные для ICQ и Mail.Ru Агента
if ($_GET['item']=="messenger") {
mysql_query("update vkontakte_user_monitoring_in_profile set time_filter_from_messenger=NULL, time_filter_to_messenger=NULL, messenger=NULL, check_time_messenger=NULL, last_online_messenger=NULL where(id_registered_user='$id_registered_user' && id_monitoring_user='$id_monitoring_user')");
view_message(add_vk_messenger_delete_data_messenger_complete, "good");
}

   }
# конец, проверяем находится ли в профиле посетителя данный пользователь
  }
# конец, если пользователь авторизован
 }
# конец, если такого пользователя не существует, то ошибка
}
###################################################################################################
# конец, удалить отправку сообщений по СМС, ICQ или Mail.Ru агенту ################################
###################################################################################################

###################################################################################################
# начало, получение запроса на добавление мессенджера #############################################
###################################################################################################
if (isset($_GET['id_vk_user'])) {
# определяем id_monitoring_user пользователя
$id_vk_user = convert_post($_GET['id_vk_user'], "0");
# проверяем, есть ли в базе этот пользователь
$data_monitoring_user_query=mysql_query("select * from vkontakte_user_to_monitoring where id_vk_user='$id_vk_user'");
# начало, если такого пользователя не существует, то ошибка
if (!mysql_num_rows($data_monitoring_user_query)) {
view_message(add_vk_messenger_id_not_exist, "bad");
?>
<p align="center"><a href="/<? echo $language_code ?>/index.html"><? echo add_vk_messenger_link_next ?></a></p><br><br><br>
<?
} else {
$data_monitoring_user=mysql_fetch_assoc($data_monitoring_user_query);
# определяем некоторые данные пользователя
$id_monitoring_user=$data_monitoring_user["id_monitoring_user"];
$fio_vk_user=$data_monitoring_user["fio_vk_user"];
# начало, если пользователь авторизован
if ($num_of_user_data) {
# начало, проверяем находится ли в профиле посетителя данный пользователь
$data_option_user_query=mysql_query("select * from vkontakte_user_monitoring_in_profile where(id_registered_user='$id_registered_user' && id_monitoring_user='$id_monitoring_user')");
if (mysql_num_rows($data_option_user_query)) {
$data_option_user=mysql_fetch_assoc($data_option_user_query);

$time_filter_from_messenger=$data_option_user['time_filter_from_messenger'];
$time_filter_to_messenger=$data_option_user['time_filter_to_messenger'];
$messenger=$data_option_user['messenger'];
$check_time_messenger=$data_option_user['check_time_messenger'];

$time_filter_from_sms=$data_option_user['time_filter_from_sms'];
$time_filter_to_sms=$data_option_user['time_filter_to_sms'];
$sms=$data_option_user['sms'];
$check_time_sms=$data_option_user['check_time_sms'];

# узнаем часовой пояс посетителя
$timezone_user=mysql_result(mysql_query("select timezone from user where (id_registered_user='$id_registered_user')"), 0);
# устанавливаем часовой пояс
date_default_timezone_set($timezone_user);

# определяем когда последний раз пользователь был в онлайне
$last_online_user_query = mysql_query("select time_last_online from vkontakte_user_to_monitoring where (id_monitoring_user='$id_monitoring_user')");
$last_time_vk=mysql_result($last_online_user_query, 0);
?>
<table width="60%" align="center" cellpadding="5" cellspacing="1" border="0">

  <tr>
    <td colspan="2"><br><? echo add_vk_messenger_about ?></td>
  </tr>

  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>

  <tr>
    <td colspan="2" class="line_blue"><img src="/templates/<? echo name_template_project ?>/index/images/sms.png">&nbsp;<b><? echo add_vk_messenger_sms_header ?></b></td>
  </tr>

<!-- ########## начало, настройка смс ##########################################################-->
<form action='<? echo "/".$language_code."/add_vk_messenger/id_vk_user/".$id_vk_user ?>' method="post" name="sms">
  <tr>
    <td colspan="2" align="center" colspan="2"><b><? echo add_vk_messenger_about_sms ?></b></td>
  </tr>

  <tr>
<?
# если отправка смс уже была задана
if ($sms) {
?>
    <td align="right">
      <input type="text" size="30" maxlength="30" name="data_sms" value="<? if (isset($data_sms)) { echo $data_sms; } elseif(isset($sms)) { echo $sms; } else { echo add_vk_sms_value_data_sms; } ?>">&nbsp;<font class="important">*</font>
    </td>
    <td>
      <nobr><a href="/<? echo $language_code ?>/add_vk_messenger/id_vk_user/<? echo $id_vk_user ?>/mode/stop/item/sms"><? echo add_vk_messenger_delete_sms ?></a></nobr>
    </td>
<?
# если отправки смс не задана
} else {
?>
    <td align="center" colspan="2">
      <input type="text" size="30" maxlength="30" name="data_sms" value="<? if (isset($data_sms)) { echo $data_sms; } elseif(isset($sms)) { echo $sms; } else { echo add_vk_sms_value_data_sms; } ?>">&nbsp;<font class="important">*</font>
    </td>
<?
}
?>
  </tr>

  <tr>
<?
# когда последний раз был в онлайне
if ($last_time_vk) {
?>
    <td colspan="2"><b><? echo add_vk_messenger_check_time1.date("d.m.Y, H:i:s", $last_time_vk).add_vk_messenger_check_time2 ?>:</b></td>
<?
} else {
?>
    <td colspan="2"><b><? echo add_vk_messenger_check_time1.add_vk_messenger_not_in_online.add_vk_messenger_check_time2 ?>:</b></td>
<?
}
?>
  </tr>

  <tr>
    <td align="center" colspan="2">
      <input type="text" size="6" maxlength="8" name="data_check_time_sms" value="<? if (isset($data_check_time_sms)) { echo $data_check_time_sms; } elseif(isset($check_time_sms)) { echo $check_time_sms; } else { echo '60'; } ?>">
      <font class="important">*</font>&nbsp;<? echo add_vk_messenger_check_time_notice ?>
    </td>
  </tr>
  <tr>
    <td align="center" colspan="2"><nobr><input type=checkbox name="accept_sms" id="accept_sms" onclick="time_not_send_sms()"><? echo add_vk_messenger_not_time_send ?>&nbsp;<input type="text" size="2" maxlength="2" name="sms_from" id="sms_from" value="<? if (strlen($sms_from)) { echo $sms_from; } elseif(strlen($time_filter_from_sms)) { echo $time_filter_from_sms; } else { echo '23'; } ?>" disabled>&nbsp;<? echo add_vk_messenger_not_time_send_to ?>&nbsp;<input type="text" size="2" maxlength="2" name="sms_to" id="sms_to" value="<? if (strlen($sms_to)) { echo $sms_to; } elseif(strlen($time_filter_to_sms)) { echo $time_filter_to_sms; } else { echo '8'; } ?>" disabled>&nbsp;<? echo add_vk_messenger_not_time_send_hour ?></nobr></td>
  </tr>
  <tr>
    <td align="center" height="40" colspan="2">
      <input type="hidden" name="id_monitoring_user" value="<? echo $id_monitoring_user ?>">
      <input type="submit" name="send_data_sms" value="<? echo add_vk_messenger_button_send_messenger_sms.'&nbsp;-&nbsp;&quot;'.$fio_vk_user ?>&quot;">
    </td>
  </tr>
</form>
<!-- ########## конец, настройка смс ###########################################################-->

<!-- ########## начало, настройка интернет мессенджеров ########################################-->
<form action='<? echo "/".$language_code."/add_vk_messenger/id_vk_user/".$id_vk_user ?>' method="post" name="messenger">
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td class="line_blue" colspan="2"><img src="/templates/<? echo name_template_project ?>/index/images/xmpp.png">&nbsp;<b><? echo add_vk_messenger_xmpp_header ?></b></td>
  </tr>

  <tr>
    <td colspan="2"><? echo add_vk_messenger_about_xmpp ?></td>
  </tr>


  <tr>
<?
if ($messenger) {
?>
    <td colspan="2" height="50" valign="bottom">
      <nobr><img src="/templates/<? echo name_template_project ?>/index/images/remove_send.gif">&nbsp;<a href="/<? echo $language_code ?>/add_vk_messenger/id_vk_user/<? echo $id_vk_user ?>/mode/stop/item/messenger"><? echo add_vk_messenger_delete_xmpp ?></a></nobr>
    </td>
<?
}
?>
  </tr>

  <tr>
<?
# когда последний раз был в онлайне
if ($last_time_vk) {
?>
    <td colspan="2"><b><? echo add_vk_messenger_check_time1.date("d.m.Y, H:i:s", $last_time_vk).add_vk_messenger_check_time2 ?>:</b></td>
<?
} else {
?>
    <td colspan="2"><b><? echo add_vk_messenger_check_time1.add_vk_messenger_not_in_online.add_vk_messenger_check_time2 ?>:</b></td>
<?
}
?>
  </tr>

  <tr>
    <td align="center" colspan="2">
      <input type="text" size="6" maxlength="8" name="data_check_time_messenger" value="<? if (isset($data_check_time_messenger)) { echo $data_check_time_messenger; } elseif(isset($check_time_messenger)) { echo $check_time_messenger; } else { echo '60'; } ?>">
      <font class="important">*</font>&nbsp;<? echo add_vk_messenger_check_time_notice ?>
    </td>
  </tr>
  <tr>
    <td align="center"><nobr><input type=checkbox name="accept_messenger" id="accept_messenger" onclick="time_not_send_messenger()"><? echo add_vk_messenger_not_time_send ?>&nbsp;<input type="text" size="2" maxlength="2" name="messenger_from" id="messenger_from" value="<? if (strlen($messenger_from)) { echo $messenger_from; } elseif(isset($time_filter_from_messenger)) { echo $time_filter_from_messenger; } else { echo '23'; } ?>" disabled>&nbsp;<? echo add_vk_messenger_not_time_send_to ?>&nbsp;<input type="text" size="2" maxlength="2" name="messenger_to" id="messenger_to" value="<? if (strlen($messenger_to)) { echo $messenger_to; } elseif(isset($time_filter_to_messenger)) { echo $time_filter_to_messenger; } else { echo '8'; } ?>" disabled>&nbsp;<? echo add_vk_messenger_not_time_send_hour ?></nobr></td>
  </tr>
  <tr>
    <td align="center" height="40" colspan="2">
      <input type="hidden" name="id_monitoring_user" value="<? echo $id_monitoring_user ?>">
      <input type="submit" name="send_data_messenger" value="<? echo add_vk_messenger_button_send_messenger_xmpp.'&nbsp;-&nbsp;&quot;'.$fio_vk_user ?>&quot;">
    </td>
  </tr>
</form>
<!-- ########## конец, настройка интернет мессенджеров #########################################-->

  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" align="center"><font class="important">*</font> - <? echo add_vk_messenger_important ?></td>
  </tr>
</table>

<script language="JavaScript">
function time_not_send_sms() {
if (document.sms.accept_sms.checked) {
document.getElementById('sms_from').disabled=false;
document.getElementById('sms_to').disabled=false;
  } else {
document.getElementById('sms_from').disabled=true;
document.getElementById('sms_to').disabled=true;
 }
}

<?
if ( ($_POST['send_data_sms'] && $accept_sms) || (strlen($time_filter_from_sms) && strlen($time_filter_to_sms)) ) {
?>
document.getElementById('accept_sms').checked=true;
document.getElementById('sms_from').disabled=false;
document.getElementById('sms_to').disabled=false;
<?
 }
?>

function time_not_send_messenger() {
if (document.messenger.accept_messenger.checked) {
document.getElementById('messenger_from').disabled=false;
document.getElementById('messenger_to').disabled=false;
  } else {
document.getElementById('messenger_from').disabled=true;
document.getElementById('messenger_to').disabled=true;
 }
}

<?
if ( ($_POST['send_data_messenger'] && $accept_messenger) || ($time_filter_from_messenger && $time_filter_to_messenger) ) {
?>
document.getElementById('accept_messenger').checked=true;
document.getElementById('messenger_from').disabled=false;
document.getElementById('messenger_to').disabled=false;
<?
 }
?>
</script>

<?
   }
# конец, проверяем находится ли в профиле посетителя данный пользователь
  }
# конец, если пользователь авторизован
 }
# конец, если такого пользователя не существует, то ошибка
}
###################################################################################################
# конец, получение запроса на добавление мессенджера ##############################################
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