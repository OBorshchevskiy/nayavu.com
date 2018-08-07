<?
sleep(20);
date_default_timezone_set("Europe/Moscow");
# jabber сервер
$domain = "nayavu.com";

# подключение файла с настройками конфигурации
require("/var/www/core/config.php");
# подключение файла осуществляющего связь с базой данных
require("/var/www/core/connect.php");

###################################################################################################
### начало, входит ли в диапазон ##################################################################
###################################################################################################
function timeInRange($time, $start, $end) {
if ($start < $end) {
return $time >= $start && $time < $end;
 }
return $time >= $start || $time < $end;
}
###################################################################################################
### конец, входит ли в диапазон ###################################################################
###################################################################################################

###################################################################################################
### начало, транслитерация ########################################################################
###################################################################################################
function GetInTranslit($string) {
	$replace=array(
		"'"=>"",
		"`"=>"",
		"а"=>"a","А"=>"a",
		"б"=>"b","Б"=>"b",
		"в"=>"v","В"=>"v",
		"г"=>"g","Г"=>"g",
		"д"=>"d","Д"=>"d",
		"е"=>"e","Е"=>"e",
		"ж"=>"zh","Ж"=>"zh",
		"з"=>"z","З"=>"z",
		"и"=>"i","И"=>"i",
		"й"=>"y","Й"=>"y",
		"к"=>"k","К"=>"k",
		"л"=>"l","Л"=>"l",
		"м"=>"m","М"=>"m",
		"н"=>"n","Н"=>"n",
		"о"=>"o","О"=>"o",
		"п"=>"p","П"=>"p",
		"р"=>"r","Р"=>"r",
		"с"=>"s","С"=>"s",
		"т"=>"t","Т"=>"t",
		"у"=>"u","У"=>"u",
		"ф"=>"f","Ф"=>"f",
		"х"=>"h","Х"=>"h",
		"ц"=>"c","Ц"=>"c",
		"ч"=>"ch","Ч"=>"ch",
		"ш"=>"sh","Ш"=>"sh",
		"щ"=>"sch","Щ"=>"sch",
		"ъ"=>"","Ъ"=>"",
		"ы"=>"y","Ы"=>"y",
		"ь"=>"","Ь"=>"",
		"э"=>"e","Э"=>"e",
		"ю"=>"yu","Ю"=>"yu",
		"я"=>"ya","Я"=>"ya",
		"і"=>"i","І"=>"i",
		"ї"=>"yi","Ї"=>"yi",
		"є"=>"e","Є"=>"e"
 );
return $str=ucwords(iconv("UTF-8","UTF-8//IGNORE",strtr($string,$replace)));
}
###################################################################################################
### конец, транслитерация #########################################################################
###################################################################################################

# выбираются все профили для которых установлена отправка уведомлений
$select_profiles_query=mysql_query("select * from vkontakte_user_monitoring_in_profile where ( (sms <> '') || (messenger <> '') )");

# начало, если есть профили у которых настроена отправка сообщений
if (mysql_num_rows($select_profiles_query)) {
# подключаем класс для отправки смс
include "/var/www/core/vkontakte/sendsms/smsc_api.php";

###################################################################################################
### начало, по порядку перебираем профили #########################################################
###################################################################################################
while ($get_select_profiles=mysql_fetch_array($select_profiles_query)) {
# определяем когда последний раз пользователь был в онлайне
$last_online_now_user_query = mysql_query("select time_last_online from vkontakte_user_to_monitoring where (id_monitoring_user='$get_select_profiles[id_monitoring_user]')");
$last_online_now_user = mysql_result($last_online_now_user_query, 0);
# если записи нет
if (!$last_online_now_user) {
$last_online_user="1";
$last_online_now_user="1";
} else {
# определяем когда предпоследний раз пользователь был в онлайне
$last_online_user_query = mysql_query("select time_before_online from vkontakte_user_to_monitoring where (id_monitoring_user='$get_select_profiles[id_monitoring_user]')");
$last_online_user = mysql_result($last_online_user_query, 0);
# если записи нет
if (!$last_online_user) {
$last_online_user="1";
 }
}

# если данные последнего онлайна изменились для СМС или мессенджеров
if ( ($get_select_profiles["last_online_sms"] <> $last_online_user) || ($get_select_profiles["last_online_messenger"] <> $last_online_user) ) {
# получаем данные посетителя
$user_profile_query=mysql_fetch_assoc(mysql_query("select * from user where (id_registered_user='$get_select_profiles[id_registered_user]')"));
# получаем данные пользователя
$user_data_query=mysql_fetch_assoc(mysql_query("select * from vkontakte_user_to_monitoring where (id_monitoring_user='$get_select_profiles[id_monitoring_user]')"));
# узнаем часовой пояс посетителя
$timezone_user=$user_profile_query["timezone"];
# устанавливаем часовой пояс посетителя
date_default_timezone_set($timezone_user);
# сколько сейчас часов
$today = getdate();
$hour = $today['hours'];
}

###################################################################################################
### начало, если новый выход в онлайн (для СМС) ###################################################
###################################################################################################
if ($get_select_profiles["last_online_sms"] <> $last_online_user) {

# начало, если с момента предпоследнего посещения данного пользователя прошло более или равно минут заданных в профиле - то отправка сообщения
if ( (($last_online_now_user - $last_online_user) >= $get_select_profiles["check_time_sms"]*60) || (!$get_select_profiles["last_online_sms"] && $get_select_profiles["sms"]) ) {

# добавляем новое значение предпоследнего захода
mysql_query("update vkontakte_user_monitoring_in_profile set last_online_sms='$last_online_user' where(id_registered_user='$get_select_profiles[id_registered_user]' && id_monitoring_user='$get_select_profiles[id_monitoring_user]')");

# начало, если есть номер мобильного для отправки смс
if ($get_select_profiles["sms"] && $get_select_profiles["last_online_sms"]) {

# узнаем баланс посетителя
$current_balance=$user_profile_query["balance"];

# начало, если баланс более 1.5 рублей, то отправляем смс
if ($current_balance >= 1.5) {

# начало, если временной промежуток корректен
if ( (!$get_select_profiles["time_filter_from_sms"] && !$get_select_profiles["time_filter_to_sms"]) || (!timeInRange($hour, $get_select_profiles["time_filter_from_sms"], $get_select_profiles["time_filter_to_sms"])) ) {
$balance="";
# уменьшаем баланс
$balance = $current_balance - 1.5;
# обновляем баланс
mysql_query("update user set balance='$balance' where(id_registered_user='$get_select_profiles[id_registered_user]')");

# отправка СМС посетителю
$message = GetInTranslit($user_data_query["fio_vk_user"])." is online ".date("d.m.Y, H:i:s", $last_online_now_user);
list($sms_id, $sms_cnt, $cost, $balance) = send_sms($get_select_profiles["sms"], $message, 0, 0, 0, 0, "Nayavu.com", 0, 0);
    }
# конец, если временной промежуток корректен
   }
# конец, если баланс более 1.5 рублей, то отправляем смс
  }
# конец, если есть номер мобильного для отправки смс
 }
# конец, если с момента предпоследнего посещения данного пользователя прошло более или равно минут заданных в профиле - то отправка сообщения
}
###################################################################################################
### конец, если новый выход в онлайн (для СМС) ####################################################
###################################################################################################


###################################################################################################
### начало, если новый выход в онлайн (для Мессенджеров) ##########################################
###################################################################################################
if ($get_select_profiles["last_online_messenger"] <> $last_online_user) {

# начало, если с момента предпоследнего посещения данного пользователя прошло более или равно минут заданных в профиле - то отправка сообщения
if ( (($last_online_now_user - $last_online_user) > $get_select_profiles["check_time_messenger"]*60) || (!$get_select_profiles["last_online_messenger"] && $get_select_profiles["messenger"]) ) {

# добавляем новое значение предпоследнего захода
mysql_query("update vkontakte_user_monitoring_in_profile set last_online_messenger='$last_online_user' where(id_registered_user='$get_select_profiles[id_registered_user]' && id_monitoring_user='$get_select_profiles[id_monitoring_user]')");

if ($get_select_profiles["messenger"] == 1) {

# составляем запрос отправки
$login_registered_user=mysql_result(mysql_query("select login from user where (id_registered_user='$get_select_profiles[id_registered_user]')"), 0);
$xmpp_send = $login_registered_user.'@'.$domain;
$xmpp_text_a = 'Vkontakte.ru User '.GetInTranslit($user_data_query["fio_vk_user"]).'[id'.$user_data_query["id_vk_user"].'] is NOW MONITORING! Vkontakte page: http://vk.com/id'.$user_data_query["id_vk_user"].' Nayavu page: http://nayavu.com/ru/monitor_online_vk/id_vk_user/'.$user_data_query["id_vk_user"];
$xmpp_text_b = 'Vkontakte.ru User '.GetInTranslit($user_data_query["fio_vk_user"]).'[id'.$user_data_query["id_vk_user"].'] is ONLINE [Detected in '.date("d.m.Y, H:i:s", $last_online_now_user).'] Vkontakte page: http://vk.com/id'.$user_data_query["id_vk_user"].' Nayavu page: http://nayavu.com/ru/monitor_online_vk/id_vk_user/'.$user_data_query["id_vk_user"];

# если первое сообщение
if (!$get_select_profiles["last_online_messenger"] && $get_select_profiles["messenger"]) {
# массивы отправляемых сообщений
$client_messenger[]=$xmpp_send;
$client_text[]=$xmpp_text_a;
} else {
if ( (!$get_select_profiles["time_filter_from_messenger"] && !$get_select_profiles["time_filter_to_messenger"]) || (!timeInRange($hour, $get_select_profiles["time_filter_from_messenger"], $get_select_profiles["time_filter_to_messenger"])) ) {
# массивы отправляемых сообщений
$client_messenger[]=$xmpp_send;
$client_text[]=$xmpp_text_b;
    }
   }
  }
 }
# конец, если с момента предпоследнего посещения данного пользователя прошло более или равно минут заданных в профиле - то отправка сообщения
}
###################################################################################################
### конец, если новый выход в онлайн (для Мессенджеров) ###########################################
###################################################################################################

}
###################################################################################################
### конец, по порядку перебираем профили ##########################################################
###################################################################################################

}
# конец, если есть профили у которых настроена отправка сообщений

# непосредственно сама отправка сообщений на мессенджер в цикле
for ($n=0; $n < count($client_messenger); $n++) {

passthru("/usr/sbin/ejabberdctl send_message_chat admin@nayavu.com ".$client_messenger[$n]." '".$client_text[$n]."'");

echo $client_messenger[$n]."-".$client_text[$n];
sleep(0.1);
}
?>