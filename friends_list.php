<?
# защита от флуда
include("antiddos/core/antiddos.php");

# подключение файла с настройками конфигурации
require("core/config.php");

# подключение файла с функциями
require("core/function.php");

# подключение файла осуществляющего связь с базой данных
require("core/connect.php");

# подключение файла верхней части дизайна страницы"
include("templates/".name_template_project."/index/header.php");

# начало, если есть данные id_vk_user
if (isset($_GET['id_vk_user'])) {
# определяем id_vk_user пользователя
$id_vk_user = convert_post($_GET['id_vk_user'], "0");

# если есть запрос на изменение полного списка друзей
if (isset($_GET['id_friends_list'])) {
$id_friends_list = convert_post($_GET['id_friends_list'], "0");
} else {
# находим самый первый список
$id_friends_list=mysql_result(mysql_query("select id from vkontakte_user_friends_log where id_vk_user='$id_vk_user' order by time_add desc limit 1"), 0);
}

# проверяем, существует ли такой пользователь
if (isset($id_friends_list)) {
$friends_user_query=mysql_query("select vk_list_friends_data, time_add from vkontakte_user_friends_log where (id='$id_friends_list' && id_vk_user='$id_vk_user')");
}

# начало, если ссылка с id_vk_user верная, то далее
if (mysql_num_rows($friends_user_query)) {

###################################################################################################
### начало, для установки часового пояса ##########################################################
###################################################################################################
# поиск посетителя в таблице
$user_data_query=mysql_query("select * from user where(login='$_COOKIE[login_user]' and password='$_COOKIE[password_user]')");
$num_of_user_data=mysql_num_rows($user_data_query);
# начало, если посетитель авторизован, то устанавливаем временной пояс, как у него в профиле
if ($num_of_user_data) {
# получение данных пользователя
$user_data=mysql_fetch_assoc($user_data_query);
# определяем timezone пользователя для мониторинга
$timezone = $user_data["timezone"];
} else {
# устанавливаем временной пояс который по умолчанию
$timezone="Europe/Moscow";
}
# устанавливаем временной пояс
date_default_timezone_set($timezone);
###################################################################################################
### конец, для установки часового пояса ###########################################################
###################################################################################################


# если посетитель авторизован
if ($id_registered_user) {
# смотрим, если данные по количеству выводимых друзей
$select_num_friends_sql = mysql_query("select num_friends from vkontakte_user_friends_view where (id_registered_user='$id_registered_user' && id_vk_user='$id_vk_user')");

# начало, если была нажата ссылка для удаления списка
if (isset($_GET['delete_list'])) {
$delete_list = convert_post($_GET['delete_list'], "0");
# находим id_monitoring_user
$id_monitoring_user = mysql_result(mysql_query("select id_monitoring_user from vkontakte_user_to_monitoring where id_vk_user='$id_vk_user'"), 0);
# смотрим, есть ли данный пользователь у этого посетителя в профиле и первый ли он его добавил
if ( (mysql_result(mysql_query("select id_registered_user from vkontakte_user_monitoring_in_profile where id_monitoring_user='$id_monitoring_user' order by id asc limit 1"), 0)) == $id_registered_user) {
# удаляем список
mysql_query("delete from vkontakte_user_friends_log where (id='$delete_list' && id_vk_user='$id_vk_user')");
if (mysql_affected_rows() == 1) {
view_message("Список успешно удален!", "good");
if (!mysql_num_rows(mysql_query("select * from vkontakte_user_friends_log where id_vk_user='$id_vk_user'"))) {
echo "<p align=\"center\"><b>Полных списков больше нет!</b></p>";
?>
<p align="center"><a href="/<? echo $language_code?>/friends/id_vk_user/<? echo $id_vk_user?>">продолжить →</a></p>
<?
# подключение файла нижней части дизайна страницы"
include("templates/".name_template_project."/index/footer.php");
exit;
 }
} else {
view_message("Не удалось удалить список! Неправильный запрос!", "bad");
}
  } else {
view_message("Удалять списки может только тот, кто первый добавил данного пользователя к себе в профиль!", "bad");
 }
}
# конец, если была нажата ссылка для удаления списка

if (mysql_num_rows($select_num_friends_sql)) {
# сколько друзей на странице выводить
$num_friends_from_db = mysql_result($select_num_friends_sql, 0);
 }
}

if (($_SERVER['REQUEST_METHOD']=='POST') && (isset($_POST['num_friends']))) {
$num_friends=convert_post($_POST['num_friends'], "0");

if ( ($num_friends==10) || ($num_friends==20) || ($num_friends==30) || ($num_friends==40) || ($num_friends==50) || ($num_friends==60) || ($num_friends==70) || ($num_friends==80) || ($num_friends==90) || ($num_friends==100) || ($num_friends==200) ) {

if (!$num_friends_from_db) {
mysql_query("insert vkontakte_user_friends_view (id_registered_user, id_vk_user, num_friends) values ('$id_registered_user', '$id_vk_user', '$num_friends')");  
} else {
mysql_query("update vkontakte_user_friends_view set num_friends='$num_friends' where (id_registered_user='$id_registered_user' && id_vk_user='$id_vk_user')");
}

}

} else {
if ($num_friends_from_db) {
$num_friends=$num_friends_from_db;
} else {
$num_friends=10;
 }
}

# пришли данные для поиска
if (($_SERVER['REQUEST_METHOD']=='POST') && (isset($_POST['search']))) {
$search=mb_strtolower(convert_post($_POST['search'], "0"), "UTF-8");
}

# создаем цикл и заносим в массив все данные друзей
$vk_list_friends_all=mysql_fetch_assoc($friends_user_query);
$vk_list_friends_data = $vk_list_friends_all["vk_list_friends_data"];

$vk_list_friends_data_array_full=explode("|#|", $vk_list_friends_data);

$i=0;
$full_list=false;
$found_full=false;
$how=false;

if (!isset($search)) {
$full_list=true;
} else {
if (strlen($search) < 3) {
view_message("В поиске необходимо использовать не менее 3-х символов! Выведен полный список друзей.", "bad");
$full_list=true;
 } else {
 
foreach ($vk_list_friends_data_array_full as $value) {
if (substr_count(mb_strtolower($value, "UTF-8"), $search)) {
$found_full=true;
 }
}

if ($found_full==false) {
view_message("Не удалось найти друзей удовлетворяющих условиям поиска! Ниже представлен полный список друзей.", "bad");
}

 }
}

$i=0;
foreach ($vk_list_friends_data_array_full as $value) {
$arr_elem_friends_add = explode("-:=", $value);
$found=false;

if ( ($full_list==false) || ($found_full==true) ) {
if ( substr_count(mb_strtolower($arr_elem_friends_add[0], "UTF-8"), $search) || substr_count(mb_strtolower($arr_elem_friends_add[1], "UTF-8"), $search) || substr_count(mb_strtolower($arr_elem_friends_add[2], "UTF-8"), $search) || substr_count(mb_strtolower($arr_elem_friends_add[4], "UTF-8"), $search) || substr_count(mb_strtolower($arr_elem_friends_add[7], "UTF-8"), $search) || substr_count(mb_strtolower($arr_elem_friends_add[8], "UTF-8"), $search) || substr_count(mb_strtolower($arr_elem_friends_add[9], "UTF-8"), $search) ) {
$found=true;
if ($how==false) {
view_message("Найдены друзья удовлетворяющие условиям поиска!", "good");
$how=true;
}
 }
}

if ( ($full_list==true) || ($found==true) || ($found_full==false) ) {
$array_data_all_friends[$i]["id_vk_user"] = $arr_elem_friends_add[0];
$array_data_all_friends[$i]["firstname"]  = $arr_elem_friends_add[1];
$array_data_all_friends[$i]["lastname"]   = $arr_elem_friends_add[2];
$array_data_all_friends[$i]["sex"]        = $arr_elem_friends_add[3];
$array_data_all_friends[$i]["login"]      = $arr_elem_friends_add[4];
$array_data_all_friends[$i]["avatar"]     = $arr_elem_friends_add[5];
$array_data_all_friends[$i]["photo"]      = $arr_elem_friends_add[6];
$array_data_all_friends[$i]["city"]       = $arr_elem_friends_add[7];
$array_data_all_friends[$i]["country"]    = $arr_elem_friends_add[8];
$array_data_all_friends[$i]["bdate"]      = $arr_elem_friends_add[9];
$i++;
}

}

# начало, сортировка массива по полю
if (!empty($_GET['sort'])) {
$sort=convert_post($_GET['sort'], "0");

# функции сортировки по lastname
function cmp_lastname_up($a, $b) {
return strcmp($b["lastname"], $a["lastname"]);
}
function cmp_lastname_down($a, $b) {
return strcmp($a["lastname"], $b["lastname"]);
}
if ($sort=="lastname_up") {
usort($array_data_all_friends, "cmp_lastname_up");
}
if ($sort=="lastname_down") {
usort($array_data_all_friends, "cmp_lastname_down");
}

# функции сортировки по firstname
function cmp_firstname_up($a, $b) {
return strcmp($b["firstname"], $a["firstname"]);
}
function cmp_firstname_down($a, $b) {
return strcmp($a["firstname"], $b["firstname"]);
}
if ($sort=="firstname_up") {
usort($array_data_all_friends, "cmp_firstname_up");
}
if ($sort=="firstname_down") {
usort($array_data_all_friends, "cmp_firstname_down");
}

# функции сортировки по sex
function cmp_sex_up($a, $b) {
return strcmp($b["sex"], $a["sex"]);
}
function cmp_sex_down($a, $b) {
return strcmp($a["sex"], $b["sex"]);
}
if ($sort=="sex_up") {
usort($array_data_all_friends, "cmp_sex_up");
}
if ($sort=="sex_down") {
usort($array_data_all_friends, "cmp_sex_down");
}

# функции сортировки по country
function cmp_country_up($a, $b) {
return strcmp($b["country"], $a["country"]);
}
function cmp_country_down($a, $b) {
return strcmp($a["country"], $b["country"]);
}
if ($sort=="country_up") {
usort($array_data_all_friends, "cmp_country_up");
}
if ($sort=="country_down") {
usort($array_data_all_friends, "cmp_country_down");
}

# функции сортировки по city
function cmp_city_up($a, $b) {
return strcmp($b["city"], $a["city"]);
}
function cmp_city_down($a, $b) {
return strcmp($a["city"], $b["city"]);
}
if ($sort=="city_up") {
usort($array_data_all_friends, "cmp_city_up");
}
if ($sort=="city_down") {
usort($array_data_all_friends, "cmp_city_down");
}

# функции сортировки по bdate
function cmp_bdate_up($a, $b) {
return strcmp(strtotime($b["bdate"]), strtotime($a["bdate"]));
}
function cmp_bdate_down($a, $b) {
return strcmp(strtotime($a["bdate"]), strtotime($b["bdate"]));
}
if ($sort=="bdate_up") {
usort($array_data_all_friends, "cmp_bdate_up");
}
if ($sort=="bdate_down") {
usort($array_data_all_friends, "cmp_bdate_down");
}

}
# конец, сортировка массива по полю

# сколько друзей у данного пользователя
$num_friends_from_db = count($array_data_all_friends);

# вычисляем номер страницы
if (empty($_GET['page_f_user']) || (convert_post($_GET['page_f_user'], "0") <= 0)) {
$page_f_user=1;
} else {
# cчитывание текущей страницы
$page_f_user=(int) convert_post($_GET['page_f_user'], "0");
}

# количество страниц
$pages_count_f_user=ceil($num_friends_from_db / $num_friends);
# если номер страницы оказался больше количества страниц
if ($page_f_user > $pages_count_f_user) $page_f_user = $pages_count_f_user;
$start_pos_f_user = ($page_f_user - 1) * $num_friends;

$monitoring_user_query=mysql_query("select * from vkontakte_user_to_monitoring where id_vk_user='$id_vk_user'");
$vk_user_data=mysql_fetch_assoc($monitoring_user_query);
$id_monitoring_user=$vk_user_data["id_monitoring_user"];
?>
<table border="0" cellpadding="7" cellspacing="0" align="center" width="80%">
  <tr>
    <td height="33" valign="top">&nbsp;<a href="/<? echo $language_code ?>/index.html"><? echo "Главная" ?></a>&nbsp;&nbsp;→&nbsp;&nbsp;<a href="/<? echo $language_code ?>/friends/id_vk_user/<? echo $id_vk_user ?>">Отслеживание друзей</a>&nbsp;&nbsp;→&nbsp;&nbsp;<font class="title"><? echo "Полный список друзей" ?></font></td>
  </tr>
</table>

<br>
<table width="80%" align="center" cellpadding="5" cellspacing="1" border="0">
  <tr>
    <td align="center">
<?
# если пользователь в онлайн
if (is_online(0, $id_monitoring_user)) {
$is_mobile=mysql_result(mysql_query("select online_mobile from vkontakte_user_to_monitoring where id_monitoring_user='$id_monitoring_user'"), 0);
?>
<table border="0" cellpadding="5" cellspacing="0" class="user_online"><tr><td align="center"><? echo get_avatar($vk_user_data["avatar_vk_user"]) ?><br><nobr>&nbsp;<font class="font_small"><? echo monitor_online_vk_online_now ?></font><a href="http://vk.com/id<? echo $id_vk_user ?>" target="_blank"><img src="/templates/<? echo name_template_project ?>/index/images/vk.png"></a><? if ($is_mobile==1){ ?><img title="<? echo header_index_vkontakte_is_mobile ?>" src="/templates/<? echo name_template_project ?>/index/images/mobile.png"><? } ?></nobr></td></tr></table>
<?
# если пользователь оффлайн
} else {
?>
<table border="0" cellpadding="5" cellspacing="0" class="user_offline"><tr><td align="center"><? echo get_avatar($vk_user_data["avatar_vk_user"]) ?><br><nobr>&nbsp;<font class="font_small"><? echo monitor_online_vk_offline_now ?></font><a href="http://vk.com/id<? echo $id_vk_user ?>" target="_blank"><img src="/templates/<? echo name_template_project ?>/index/images/vk.png"></a></nobr></td></tr></table>
<?
}
# выводим его ФИО
echo "<a href='/".$language_code."/monitor_online_vk/id_vk_user/".$id_vk_user."'>".$vk_user_data["fio_vk_user"]."&nbsp;→</a>"; 
?>
	</td>
  </tr>
</table>
<br>

<?
# находим число списков в базе
$num_list_full_friends=mysql_num_rows(mysql_query("select * from vkontakte_user_friends_log where id_vk_user='$id_vk_user' order by time_add desc"));

# находим самый первый список
$first_row_f_friends=mysql_result(mysql_query("select id from vkontakte_user_friends_log where id_vk_user='$id_vk_user' order by time_add desc limit 1"), 0);

# находим самый последний список
$second_row_f_friends=mysql_result(mysql_query("select id from vkontakte_user_friends_log where id_vk_user='$id_vk_user' order by time_add asc limit 1"), 0);

# число выводимых списков
$num_in_page_f = 5;

# вычисляем номер страницы
if (empty($_GET['page_f_list']) || (convert_post($_GET['page_f_list'], "0") <= 0)) {
$page_f_list=1;
} else {
# cчитывание текущей страницы
$page_f_list=(int) convert_post($_GET['page_f_list'], "0");
}

# количество страниц
$pages_count_f_list=ceil($num_list_full_friends / $num_in_page_f);
# если номер страницы оказался больше количества страниц
if ($page_f_list > $pages_count_f_list) $page_f_list = $pages_count_f_list;
$start_pos_f_list = ($page_f_list - 1) * $num_in_page_f;
?>

<table border="0" cellpadding="5" cellspacing="0" align="center" width="90%" class="data_box">
<form action="/<? echo $language_code?>/friends_list/id_vk_user/<? echo $id_vk_user?>/id_friends_list/<? echo $id_friends_list ?>/page_f_list/<? echo $page_f_list; ?>" method="post" id="search">
<tr>
 <td class="search_style_friends" align="right">выполнить <input type="text" size="15" maxlength="50" name="search"></td>
 <td class="search_style_friends" align="left"><input type="submit" name="submit_item_edit" value="Поиск"> по данным друзей</td>
</tr>
</form>
</table>

<br>
<table border="0" cellpadding="3" cellspacing="1" width="90%" align="center" class="data_box">
<tr>
  <td align="center"><p class="title_online_vk">Полные списки друзей за всё время отслеживания</p></td>
</tr>
<?

$data_friends_all_time_sql=mysql_query("select * from vkontakte_user_friends_log where id_vk_user='$id_vk_user' order by time_add desc limit ".$start_pos_f_list.", ".$num_in_page_f);

while ($data_friends_all_time = mysql_fetch_array($data_friends_all_time_sql)) {
$num_friends_in_list = NULL;
$num_friends_in_list = substr_count($data_friends_all_time["vk_list_friends_id"], "#") + 1;

?>
<tr>
  <td align="center">
<?
if ( ($id_friends_list == $data_friends_all_time["id"]) || (($data_friends_all_time["id"]==$first_row_f_friends) && !isset($id_friends_list)) ) {
?>
<b>
<font class="font_small"><? echo $num_friends_in_list." друзей,"; ?></font>&nbsp;<a title="Выберите полный список друзей" class="friends_list" href="/<? echo $language_code?>/friends_list/id_vk_user/<? echo $id_vk_user?>/id_friends_list/<? echo $data_friends_all_time["id"] ?>/page_f_list/<? echo $page_f_list; ?>"><? echo date("d.m.Y, H:i", $data_friends_all_time["time_add"]); ?>&nbsp;→</a>
</b>
<?
} else {
?>
<font class="font_small"><? echo $num_friends_in_list." друзей,"; ?></font>&nbsp;<a title="Выберите полный список друзей" class="friends_list" href="/<? echo $language_code?>/friends_list/id_vk_user/<? echo $id_vk_user?>/id_friends_list/<? echo $data_friends_all_time["id"] ?>/page_f_list/<? echo $page_f_list; ?>"><? echo date("d.m.Y, H:i", $data_friends_all_time["time_add"]); ?>&nbsp;→</a>
<?
}
if ($second_row_f_friends == $data_friends_all_time["id"]) {
?>
&nbsp;<a href="/<? echo $language_code?>/friends_list/id_vk_user/<? echo $id_vk_user?>/delete_list/<? echo $data_friends_all_time["id"]; ?>"><img alt="x" title="Удалить список" src="/templates/<? echo name_template_project ?>/index/images/delete_list.png"></a>
<?
}
?>
  </td>
</tr>
<?
}

if ($num_list_full_friends > $num_in_page_f) {
?>
<tr>
  <td align="center" valign="top">
<font class="friends_vk_next">
<?
# составляем ЧПУ ссылку
$chpu_link = "/".$language_code."/friends_list/id_vk_user/".$id_vk_user."/";
page_link($page_f_list, "page_f_list", $num_list_full_friends, $pages_count_f_list, $num_in_page_f, $chpu_link);
?>
</font>
  </td>
</tr>
<tr>
  <td class="font_small_grey" align="center">удаление списка возможно только с последнего</td>
</tr>
<?
}
?>

</table>

<p style="padding: 4px; margin: 0px;"></p>
<table border="0" cellpadding="2" cellspacing="0" align="center" width="90%">
<form action="/<? echo $language_code?>/friends_list/id_vk_user/<? echo $id_vk_user?>" method="post" id="num_friends">
<tr>
  <td align="right">
<img title="<? echo "Отображать на странице" ?>" src="/templates/<? echo name_template_project ?>/index/images/in_page.png">
  </td>
  <td align="left" width="1%">
<select title="<? echo "Отображать на странице" ?>" class="num_in_page" name="num_friends" onchange='document.forms["num_friends"].submit()'>
<? if ($num_friends==10) { ?><option selected value="10">10</option><? } else { ?> <option value="10">10</option> <? } ?>
<? if ($num_friends==20) { ?><option selected value="20">20</option><? } else { ?> <option value="20">20</option> <? } ?>
<? if ($num_friends==30) { ?><option selected value="30">30</option><? } else { ?> <option value="30">30</option> <? } ?>
<? if ($num_friends==40) { ?><option selected value="40">40</option><? } else { ?> <option value="40">40</option> <? } ?>
<? if ($num_friends==50) { ?><option selected value="50">50</option><? } else { ?> <option value="50">50</option> <? } ?>
<? if ($num_friends==60) { ?><option selected value="60">60</option><? } else { ?> <option value="60">60</option> <? } ?>
<? if ($num_friends==70) { ?><option selected value="70">70</option><? } else { ?> <option value="70">70</option> <? } ?>
<? if ($num_friends==80) { ?><option selected value="80">80</option><? } else { ?> <option value="80">80</option> <? } ?>
<? if ($num_friends==90) { ?><option selected value="90">90</option><? } else { ?> <option value="90">90</option> <? } ?>
<? if ($num_friends==100) { ?><option selected value="100">100</option><? } else { ?> <option value="100">100</option> <? } ?>
<? if ($num_friends==200) { ?><option selected value="200">200</option><? } else { ?> <option value="200">200</option> <? } ?>
</select>
  </td>
</tr>
</form>
</table>

<?
# если массив друзей не пустой
if (count($array_data_all_friends)) {
?>
<table border="0" cellpadding="5" cellspacing="1" width="90%" align="center" class="friends_list">
<tr class="friends_main_header_workspace">
  <td class="friends_main_header_workspace" align="center"><nobr>Id/Короткое имя</nobr></td>
  <td class="friends_main_header_workspace" align="center"><nobr>Фамилия <a class="arrow" href="/<? echo $language_code?>/friends_list/id_vk_user/<? echo $id_vk_user?>/sort/lastname_up">↑</a><a class="arrow" href="/<? echo $language_code?>/friends_list/id_vk_user/<? echo $id_vk_user?>/sort/lastname_down">↓</a></nobr></td>
  <td class="friends_main_header_workspace" align="center"><nobr>Имя <a class="arrow" href="/<? echo $language_code?>/friends_list/id_vk_user/<? echo $id_vk_user?>/sort/firstname_up">↑</a><a class="arrow" href="/<? echo $language_code?>/friends_list/id_vk_user/<? echo $id_vk_user?>/sort/firstname_down">↓</a></nobr></td>
  <td class="friends_main_header_workspace" align="center"><nobr>Пол <a class="arrow" href="/<? echo $language_code?>/friends_list/id_vk_user/<? echo $id_vk_user?>/sort/sex_up">↑</a><a class="arrow" href="/<? echo $language_code?>/friends_list/id_vk_user/<? echo $id_vk_user?>/sort/sex_down">↓</a></nobr></td>
  <td class="friends_main_header_workspace" align="center"><nobr>Страна <a class="arrow" href="/<? echo $language_code?>/friends_list/id_vk_user/<? echo $id_vk_user?>/sort/country_up">↑</a><a class="arrow" href="/<? echo $language_code?>/friends_list/id_vk_user/<? echo $id_vk_user?>/sort/country_down">↓</a> Город <a class="arrow" href="/<? echo $language_code?>/friends_list/id_vk_user/<? echo $id_vk_user?>/sort/city_up">↑</a><a class="arrow" href="/<? echo $language_code?>/friends_list/id_vk_user/<? echo $id_vk_user?>/sort/city_down">↓</a></nobr></td>
  <td class="friends_main_header_workspace" align="center"><nobr>Дата рождения <a class="arrow" href="/<? echo $language_code?>/friends_list/id_vk_user/<? echo $id_vk_user?>/sort/bdate_up">↑</a><a class="arrow" href="/<? echo $language_code?>/friends_list/id_vk_user/<? echo $id_vk_user?>/sort/bdate_down">↓</a></nobr></td>  
</tr>

<?
$num_steps = NULL;
$num_steps = $num_friends + $start_pos_f_user;

for ($m=$start_pos_f_user; $m < $num_steps; $m++) {

$id_vk_user_friends=$array_data_all_friends[$m]["id_vk_user"];

# защита от пустых данных
if ($id_vk_user_friends) {

# показываем правильную дату
$bdate = $array_data_all_friends[$m]["bdate"];
$bdate_arr = explode(".", $bdate);

$bdate = str_pad($bdate_arr[0], 2, '0', STR_PAD_LEFT).".".str_pad($bdate_arr[1], 2, '0', STR_PAD_LEFT).".".str_pad($bdate_arr[2], 2, '0', STR_PAD_LEFT);
$bdate = str_replace(".00", "", $bdate);
$bdate = str_replace("00", "", $bdate);

?>
<tr class="friends_list_grey">

 <td align="center" class="data_friends_id">
<?
if ($array_data_all_friends[$m]["login"]) {
?>
<a target="_blank" href="http://vk.com/<? echo $array_data_all_friends[$m]["login"] ?>"><? echo $array_data_all_friends[$m]["login"] ?></a>
<?
} else {
?>
<a target="_blank" href="http://vk.com/id<? echo $array_data_all_friends[$m]["id_vk_user"] ?>">id<? echo $array_data_all_friends[$m]["id_vk_user"] ?></a>
<?
}
?>
  </td>
  
  <td class="friends_list_grey" align="center"><? echo $array_data_all_friends[$m]["lastname"] ?></td>
  
  <td class="friends_list_grey" align="center"><? echo $array_data_all_friends[$m]["firstname"] ?></td>
  
  <td class="friends_list_grey" align="center"><? if ($array_data_all_friends[$m]["sex"]==1) { echo "ж"; } else { echo "м"; } ?></td>
  
  <td class="friends_list_grey" align="center">
<?
if ($array_data_all_friends[$m]["country"]) {
echo $array_data_all_friends[$m]["country"];
if ($array_data_all_friends[$m]["city"]) {
echo(", ");
}
}
if ($array_data_all_friends[$m]["city"]) {
echo $array_data_all_friends[$m]["city"];
} 
?>
  </td>
  <td class="friends_list_grey" align="center"><? echo $bdate ?></td> 
</tr>
<?
 }
}
?>
</table>
<?
}

if ($num_friends_from_db > $num_friends) {
?>
<div align="center">
<font class="friends_vk_next">
<?
# составляем ЧПУ ссылку
$chpu_link = "/".$language_code."/friends_list/id_vk_user/".$id_vk_user."/";
page_link($page_f_user, "page_f_user", $num_friends_from_db, $pages_count_f_user, $num_friends, $chpu_link);
?>
</font>
</div>
<?
}

} else {
exit;
  }
 }
# конец, если есть данные id_vk_user

# подключение файла нижней части дизайна страницы"
include("templates/".name_template_project."/index/footer.php");
?>