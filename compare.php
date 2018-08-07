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

# поиск посетителя в таблице
$user_data_query=mysql_query("select * from user where(login='$_COOKIE[login_user]' and password='$_COOKIE[password_user]')");
$num_of_user_data=mysql_num_rows($user_data_query);

# начало, если посетитель авторизован
if ($num_of_user_data) {

# получение данных пользователя
$user_data=mysql_fetch_assoc($user_data_query);
# определяем timezone пользователя для мониторинга
$timezone = $user_data["timezone"];
# устанавливаем временной пояс
date_default_timezone_set($timezone);

# подключение файла верхней части дизайна страницы"
include("templates/".name_template_project."/index/header.php");

###################################################################################################
# начало, функция, заносим все записи онлайн лога в массив  #######################################
###################################################################################################
function add_all_online_log_in_mass($id_monitoring_user, $date_from, $date_to) {
# запрос на выборку логов "от" и "сколько"
$select_record_query=mysql_query("select time_in_online from vkontakte_user_online_log where (id_monitoring_user='$id_monitoring_user' && time_in_online>='$date_from' && time_in_online<='$date_to') order by time_in_online");
# заносим в массив все отобранные записи
while ($get_record_time_in_online=mysql_fetch_array($select_record_query)) {
$mass_time_in_online[]=$get_record_time_in_online["time_in_online"];
 }
return $mass_time_in_online;
}
###################################################################################################
# конец, функция, заносим все записи онлайн лога в массив #########################################
###################################################################################################

###################################################################################################
# начало, функция, объединяем временные промежутки в массив  ######################################
###################################################################################################
function merge_time_part($mass_time_in_online) {
# сортируем в обратном порядке для правильной работы алгоритм слияния дат и времени
$mass_time_in_online=array_reverse($mass_time_in_online);
# самый первый элемент массива
$first_time=$mass_time_in_online[0];

# начало, перебираем в цикле все значения
for ($i=0; $i < count($mass_time_in_online); $i++) {

# начало, если элемент массива последний
if ($i==(count($mass_time_in_online)-1)) {
# если нет временных промежутков, т.е пользователь зашел и вышел
if ($first_time==$mass_time_in_online[$i]) {
$mass_edit_time_in_online[]=date("d.m.Y, H:i", $mass_time_in_online[$i]);
$mass_edit_time_in_online_not_formatted_prev[]=$mass_time_in_online[$i];
$mass_edit_time_in_online_not_formatted_next[]=$mass_time_in_online[$i];
} else {
# если дни двух дат равны, то объединяем их в одну
if (date("d.m.Y", $first_time) == date("d.m.Y", $mass_time_in_online[$i])) {
$mass_edit_time_in_online[]=date("d.m.Y, H:i", $first_time)." - ".date("H:i", $mass_time_in_online[$i]);
$mass_edit_time_in_online_not_formatted_prev[]=$mass_time_in_online[$i];
$mass_edit_time_in_online_not_formatted_next[]=$first_time;
} else {
$mass_edit_time_in_online[]=date("d.m.Y, H:i", $first_time)." - ".date("d.m.Y, H:i", $mass_time_in_online[$i]);
$mass_edit_time_in_online_not_formatted_prev[]=$mass_time_in_online[$i];
$mass_edit_time_in_online_not_formatted_next[]=$first_time;
  }
 }
} else {
# если разница между элементами более 10 минут
if (($mass_time_in_online[$i] + 600) < $mass_time_in_online[$i+1]) {
# если нет временных промежутков, т.е пользователь зашел и вышел
if ($first_time==$mass_time_in_online[$i]) {
$mass_edit_time_in_online[]=date("d.m.Y, H:i", $mass_time_in_online[$i]);
$mass_edit_time_in_online_not_formatted_prev[]=$mass_time_in_online[$i];
$mass_edit_time_in_online_not_formatted_next[]=$mass_time_in_online[$i];
} else {
# если дни двух дат равны, то объединяем их в одну
if (date("d.m.Y", $first_time) == date("d.m.Y", $mass_time_in_online[$i])) {
$mass_edit_time_in_online[]=date("d.m.Y, H:i", $first_time)." - ".date("H:i", $mass_time_in_online[$i]);
$mass_edit_time_in_online_not_formatted_prev[]=$mass_time_in_online[$i];
$mass_edit_time_in_online_not_formatted_next[]=$first_time;
} else {
$mass_edit_time_in_online[]=date("d.m.Y, H:i", $first_time)." - ".date("d.m.Y, H:i", $mass_time_in_online[$i]);
$mass_edit_time_in_online_not_formatted_prev[]=$mass_time_in_online[$i];
$mass_edit_time_in_online_not_formatted_next[]=$first_time;
 }
}

# начало следующего временного промежутка
$first_time=$mass_time_in_online[$i+1];
  }
 }
# конец, если элемент массива последний
}
# конец, перебираем в цикле все значения

# сортируем в обратном порядке, т.к вывод дат идет сверху вниз
$mass_edit_time_in_online=array_reverse($mass_edit_time_in_online);
$mass_edit_time_in_online_not_formatted_prev=array_reverse($mass_edit_time_in_online_not_formatted_prev);
$mass_edit_time_in_online_not_formatted_next=array_reverse($mass_edit_time_in_online_not_formatted_next);
# заносим в многомерный массив полученные значения
$mass_edit_time_in_online_all[0][0]=$mass_edit_time_in_online_not_formatted_prev;
$mass_edit_time_in_online_all[0][1]=$mass_edit_time_in_online_not_formatted_next;
$mass_edit_time_in_online_all[0][2]=$mass_edit_time_in_online;

return $mass_edit_time_in_online_all;
}
###################################################################################################
# конец, функция, объединяем временные промежутки в массив  #######################################
###################################################################################################

###################################################################################################
# начало, получаем данные для сравнения ###########################################################
###################################################################################################
if (($_SERVER['REQUEST_METHOD']=='POST') && (isset($_POST['submit_compare']))) {
# получения данных с формы
foreach($_POST as $key => $_POST['key']) {
# приведение к безопасному виду
$value=convert_post($_POST['key'], "0");
$$key=$value;
 }
}
###################################################################################################
# конец, получаем данные для сравнения ############################################################
###################################################################################################

###################################################################################################
# начало, вывод шапки с возможность выбора для сравнения ##########################################
###################################################################################################
if (isset($_GET['id_vk_user'])) {

# определяем id_vk_user пользователя для мониторинга
$id_vk_user_a = convert_post($_GET['id_vk_user'], "0");
# проверяем, существует ли такой пользователь
$monitoring_user_query_a=mysql_query("select * from vkontakte_user_to_monitoring where id_vk_user='$id_vk_user_a'");
# начало, если ссылка с id_vk_user верная, то далее
if (mysql_num_rows($monitoring_user_query_a)) {
$vk_user_data_a=mysql_fetch_assoc($monitoring_user_query_a);

?>
<table border="0" cellpadding="7" cellspacing="0" align="center" width="80%">
  <tr>
    <td height="33" valign="top">&nbsp;<a href="/<? echo $language_code ?>/index.html"><? echo compare_vkontakte_main_page ?></a>&nbsp;&nbsp;→&nbsp;&nbsp;<font class="title"><? echo compare_vkontakte_workspace_title ?></font></td>
  </tr>
</table>

<table border="0" cellpadding="5" cellspacing="1" align="center" width="80%" class="data_box">
<form action="/<? echo $language_code ?>/compare/id_vk_user/<? echo $id_vk_user_a ?>" method="post">
  <tr>
    <td align="right" width="40%"><nobr><b><u><? echo $vk_user_data_a["fio_vk_user"] ?></u></b></nobr></td>
    <td align="center">&nbsp;<b><nobr><? echo compare_vkontakte_width ?></b></nobr>&nbsp;</td>
    <td width="60%">
<nobr>
<select name="id_vk_user_b" class="select_user">
<?
# начало, считываем по порядку из таблицы всех добавленных пользователей
$vkontakte_user_to_monitoring_data = mysql_query("select id_monitoring_user from vkontakte_user_monitoring_in_profile where id_registered_user='$id_registered_user'");
while ($get_vkontakte_user_to_monitoring_data = mysql_fetch_array($vkontakte_user_to_monitoring_data)) {
# Фио пользователя
$fio_vk_user_b_db=mysql_result(mysql_query("select fio_vk_user from vkontakte_user_to_monitoring where (id_monitoring_user='$get_vkontakte_user_to_monitoring_data[id_monitoring_user]')"), 0);
$id_vk_user_b_db=mysql_result(mysql_query("select id_vk_user from vkontakte_user_to_monitoring where (id_monitoring_user='$get_vkontakte_user_to_monitoring_data[id_monitoring_user]')"), 0);

if ($id_vk_user_a <> $id_vk_user_b_db) {
if ($id_vk_user_b == $id_vk_user_b_db) {
?>
<option selected value="<? echo $id_vk_user_b_db ?>"><? echo $fio_vk_user_b_db ?></option>
<?
} else {
?>
<option value="<? echo $id_vk_user_b_db ?>"><? echo $fio_vk_user_b_db ?></option>
<?
  }
 }
}
?>
</select>
&nbsp;&nbsp;
<input type="submit" name="submit_compare" value="<? echo compare_button ?>">
</nobr>
    </td>
  </tr>
</form>
</table>
<br>
<?

} else {
exit;
}

}
###################################################################################################
# конец, вывод шапки с возможность выбора для сравнения ###########################################
###################################################################################################

# начало, если есть данные id_vk_user
if (isset($id_vk_user_a) && isset($id_vk_user_b)) {

###################################################################################################
# начало, набираем временные промежутки в массив ##################################################
###################################################################################################

# дата и время сегодня
$date_to = mktime(date("H"), date("i"), date("s"), date("m"), date("j"), date("Y"));
# дата и время месяц назад
$date_from = $date_and_time_today - 2592000;

# данные для первого
$monitoring_user_query_a=mysql_query("select * from vkontakte_user_to_monitoring where id_vk_user='$id_vk_user_a'");
# начало, если ссылка с $id_monitoring_user_a верная
if (mysql_num_rows($monitoring_user_query_a)) {
$vk_user_data_a=mysql_fetch_assoc($monitoring_user_query_a);
$id_monitoring_user_a=$vk_user_data_a["id_monitoring_user"];
}

# данные для второго
$monitoring_user_query_b=mysql_query("select * from vkontakte_user_to_monitoring where id_vk_user='$id_vk_user_b'");
# начало, если ссылка с $id_monitoring_user_b верная
if (mysql_num_rows($monitoring_user_query_b)) {
$vk_user_data_b=mysql_fetch_assoc($monitoring_user_query_b);
$id_monitoring_user_b=$vk_user_data_b["id_monitoring_user"];
}

# получаем все записи для первого
$mass_time_in_online_all_a = add_all_online_log_in_mass($id_monitoring_user_a, $date_from, $date_to);

# избавимся от секунд для первого
foreach ($mass_time_in_online_all_a as $element) {
$hour  = date("H", $element);
$minut = date("i", $element);
$day   = date("d", $element);
$month = date("m", $element);
$year  = date("Y", $element);
# массив для первого без секунд
$mass_time_in_online_all_a_new[] = mktime($hour, $minut, 0, $month, $day, $year);
}
# удаляем повторяющиеся элементы в массиве для первого
$mass_time_in_online_all_a_new_u = array_unique($mass_time_in_online_all_a_new);

# получаем все записи для второго
$mass_time_in_online_all_b = add_all_online_log_in_mass($id_monitoring_user_b, $date_from, $date_to);
# избавимся от секунд для второго
foreach ($mass_time_in_online_all_b as $element) {
$hour  = date("H", $element);
$minut = date("i", $element);
$day   = date("d", $element);
$month = date("m", $element);
$year  = date("Y", $element);
# массив для второго без секунд
$mass_time_in_online_all_b_new[] = mktime($hour, $minut, 0, $month, $day, $year);
}
# удаляем повторяющиеся элементы в массиве для второго
$mass_time_in_online_all_b_new_u = array_unique($mass_time_in_online_all_b_new);

# объединяем два массива
$mass_time_in_online_all = array_merge($mass_time_in_online_all_a_new_u, $mass_time_in_online_all_b_new_u);

# пересортируем массив
rsort($mass_time_in_online_all);

# находим повторяющиеся записи - т.е в одно время находились данные люди.
$mass_time_in_online_compare = array_count_values($mass_time_in_online_all);

# начало, заносим в массив совпавшие записи
while (list ($key, $val) = each ($mass_time_in_online_compare)) {
if ($val > 1) {
$mass_time_in_online_compare_selected[]=$key;
 }
}
# конец, заносим в массив совпавшие записи

#  начало, если пользователи не находились в одно время в онлайн
if (!count($mass_time_in_online_compare_selected)) {
echo "<div align=\"center\"><b>".compare_not_data."</b></div>";
} else {

# получаем массив с объединенными временными промежутками
$mass_edit_time_in_online_all=merge_time_part($mass_time_in_online_compare_selected);
# массив отформатированных объединенных временных промежутков
$mass_edit_time_in_online=$mass_edit_time_in_online_all[0][2];
###################################################################################################
# конец, набираем временные промежутки в массив ###################################################
###################################################################################################

# начало, если есть записи в массиве временных промежутков
if (count($mass_edit_time_in_online)) {

###################################################################################################
# начало, подсчитываем за сколько дней набралось записей ##########################################
###################################################################################################
for ($i=0; $i < count($mass_edit_time_in_online); $i++) {
$mass_date=explode(",", $mass_edit_time_in_online[$i]);
$mass_day_date[]=$mass_date[0];
}
###################################################################################################
# конец, подсчитываем за сколько дней набралось записей ###########################################
###################################################################################################

# массив со всем элементами
$mass_day_date_all=$mass_day_date;
# избавляемся от повторных элементов
$mass_day_date=array_unique($mass_day_date);

?>
<table border="0" cellpadding="5" cellspacing="1" align="center" width="80%" class="data_box">
  <tr>
    <td>
<br>
<table border="0" cellpadding="0" cellspacing="0" align="center" width="40%">
  <tr>
    <td width="50%" align="center">
<?
###################################################################################################
# начало, определяем в данный момент пользователь в онлайн или оффлайн ############################
###################################################################################################
if (is_online(0, $id_monitoring_user_a)) {
?>
<table border="0" cellpadding="5" cellspacing="0" class="user_online"><tr><td align="center"><? echo get_avatar($vk_user_data_a["avatar_vk_user"]) ?><br><nobr>&nbsp;<font class="font_small"><? echo monitor_online_vk_online_now ?></font>&nbsp;<a href="http://vk.com/id<? echo $vk_user_data_a[id_vk_user] ?>" target="_blank"><img src="/templates/<? echo name_template_project ?>/index/images/vk.png"></a></nobr></td></tr></table>
<?
} else {
?>
<table border="0" cellpadding="5" cellspacing="0" class="user_offline"><tr><td align="center"><? echo get_avatar($vk_user_data_a["avatar_vk_user"]) ?><br><nobr>&nbsp;<font class="font_small"><? echo monitor_online_vk_offline_now ?></font>&nbsp;<a href="http://vk.com/id<? echo $vk_user_data_a[id_vk_user] ?>" target="_blank"><img src="/templates/<? echo name_template_project ?>/index/images/vk.png"></a></nobr></td></tr></table>
<?
}
###################################################################################################
# конец, определяем в данный момент пользователь в онлайн или оффлайн #############################
###################################################################################################
?>
<nobr>
<a target="_blank" href='<? echo "/".$language_code."/monitor_online_vk/id_vk_user/".$id_vk_user_a ?>'>
<b><? echo $vk_user_data_a["fio_vk_user"] ?></b>
</a>
</nobr>
    </td>
    <td align="center">&nbsp;<b><? echo compare_vkontakte_and ?></b>&nbsp;</td>
    <td width="50%" align="center">
<?
###################################################################################################
# начало, определяем в данный момент пользователь в онлайн или оффлайн ############################
###################################################################################################
if (is_online(0, $id_monitoring_user_b)) {
?>
<table border="0" cellpadding="5" cellspacing="0" class="user_online"><tr><td align="center"><? echo get_avatar($vk_user_data_b["avatar_vk_user"]) ?><br><nobr>&nbsp;<font class="font_small"><? echo monitor_online_vk_online_now ?></font>&nbsp;<a href="http://vk.com/id<? echo $vk_user_data_b[id_vk_user] ?>" target="_blank"><img src="/templates/<? echo name_template_project ?>/index/images/vk.png"></a></nobr></td></tr></table>
<?
} else {
?>
<table border="0" cellpadding="5" cellspacing="0" class="user_offline"><tr><td align="center"><? echo get_avatar($vk_user_data_b["avatar_vk_user"]) ?><br><nobr>&nbsp;<font class="font_small"><? echo monitor_online_vk_offline_now ?></font>&nbsp;<a href="http://vk.com/id<? echo $vk_user_data_b[id_vk_user] ?>" target="_blank"><img src="/templates/<? echo name_template_project ?>/index/images/vk.png"></a></nobr></td></tr></table>
<?
}
###################################################################################################
# конец, определяем в данный момент пользователь в онлайн или оффлайн #############################
###################################################################################################
?>
<a target="_blank" href='<? echo "/".$language_code."/monitor_online_vk/id_vk_user/".$id_vk_user_b ?>'>
<b><? echo $vk_user_data_b["fio_vk_user"] ?></b>
</a>
    </td>
  </tr>
</table>

	</td>
  </tr>

  <tr>
    <td valign="top">
<?
###################################################################################################
# начало, вывод по дням временных промежутков #####################################################
###################################################################################################
foreach ($mass_day_date as $element) {
$view_element=false;
$view_element_b=false;
$num=0;
# подсчитываем сколько раз повторяется текущая дата в массиве со всеми элементами
$num_elements=num_element_in_mass($element, $mass_day_date_all);
?>
<br>
<table border="0" cellpadding="5" cellspacing="0" width="70%" align="center">
<?
# выводим временные промежутки за данный день
for ($n=0; $n < count($mass_edit_time_in_online); $n++) {
if ($element == $mass_day_date_all[$n]) {
$num++;
?>
  <tr>
    <td width="20%" class="data_box_dark" align="center">
<?
if ($view_element_b==false) {
unset($DaysOfWeek);
$DaysOfWeek = array();
unset($date_array);
$date_array = array();
unset($arr_date);
$arr_date = array();
$DaysOfWeek = array(voskr, poned, vtornik, sreda, chetverg, pyatnica, subbota);
$date_array = explode(".", $element);
$arr_date = getdate(mktime(0, 0, 0, $date_array[1], $date_array[0], $date_array[2]));
echo "<p>".$DaysOfWeek[$arr_date['wday']]."</p>";
$view_element_b=true;
} else {
echo "&nbsp;";
}
?>
    </td>
    <td width="10%" class="data_box_green" align="center">
<?
if ($view_element==false) {
echo $element;
$view_element=true;
} else {
echo "&nbsp;";
}
?>
    </td>
<?
# массив временных промежутков за день
$time_range_today[]=$mass_edit_time_in_online[$n];

if ($num==$num_elements) {
?>
    <td class="time_day_date_not_line"><nobr><? echo $mass_edit_time_in_online[$n] ?></nobr></td>
<?
} else {
?>
    <td class="time_day_date"><nobr><? echo $mass_edit_time_in_online[$n] ?></nobr></td>
<?
}
?>
  </tr>
<?
 }
}

?>
  <tr>
    <td colspan="3" align="right" valign="top" class="font_time_today">
<?
###################################################################################################
# начало, определяем сколько времени online провели данные пользователи за сегодня ################
###################################################################################################
$today=0;
for ($b=0; $b < count($time_range_today); $b++) {
# разбиваем по запятую в массив
$time_range_today_exp=explode(",", $time_range_today[$b]);
# разбиваем на день-месяц-год
$date_d_m_y=explode(".", $time_range_today_exp[0]);
$day_today=$date_d_m_y[0];
$month_today=$date_d_m_y[1];
$year_today=$date_d_m_y[2];
# если есть временной промежуток
if (strpos($time_range_today_exp[1], " - ")) {
# разбиваем время в массив по " - "
$time_range=explode(" - ", trim($time_range_today_exp[1]));
# разбиваем на час и минуту
$time_hour_min_a=explode(":", $time_range[0]);
$time_hour_min_b=explode(":", $time_range[1]);
# заносим в массив временные промежутки
$time_hour_min_a_mktime=mktime($time_hour_min_a[0], $time_hour_min_a[1], 0, $month_today, $day_today, $year_today);
$time_hour_min_b_mktime=mktime($time_hour_min_b[0], $time_hour_min_b[1], 0, $month_today, $day_today, $year_today);
# разница в секундах менжду промежутками
$today_new=$time_hour_min_b_mktime-$time_hour_min_a_mktime;
$today=$today+$today_new;
} else {
$today=$today + 60;
 }
}

# конвертируем в дни-часы-мин-сек
$today_converted=Sec2Time($today);

echo compare_today_time_online." ";

# годы
if ($today_converted["year"]) {
echo $today_converted["year"]." лет. ";
}

# дни
if ($today_converted["day"]) {
echo $today_converted["day"]." дней. ";
}

# часы
if ($today_converted["hour"]) {
echo $today_converted["hour"]." ч. ";
}

# минуты
echo $today_converted["min"]." мин. ";

unset($time_range_today);
$time_range_today = array();
unset($time_range_today_exp);
$time_range_today_exp = array();
unset($date_d_m_y);
$date_d_m_y = array();
unset($time_range);
$time_range = array();
unset($time_hour_min_a);
$time_hour_min_a = array();
unset($time_hour_min_b);
$time_hour_min_b = array();
###################################################################################################
# конец, определяем сколько времени online провели данные пользователи за сегодня #################
###################################################################################################
?>
    </td>
  </tr>
</table>
<?
 }
###################################################################################################
# конец, вывод по дням временных промежутков ######################################################
###################################################################################################
?>
    <br>
	</td>
  </tr>
</table>
<?

   }
# конец, если есть записи в массиве временных промежутков
  }
# конец, если пользователи не находились в одно время в онлайн
 }
# конец, если есть данные id_vk_user
}
# конец, если посетитель авторизован

# подключение файла нижней части дизайна страницы"
include("templates/".name_template_project."/index/footer.php");
?>