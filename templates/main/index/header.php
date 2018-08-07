<?
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

# вывод ошибки о том, что была попытка отправки повторных данных
if (isset($_GET['frequent_requests'])) {
$result_message[]=array("message" => header_index_frequent_requests, "class" => bad);
}

# если была нажата ссылка с кодом языка ###########################################################
if (isset($_GET['language'])) {
# преобразуем в безопасный вид поступившие данные
$language_code=convert_post($_GET['language'], 0);
} else {
# язык интерфейса по умолчанию
$language_code=language_interface_project;
}
###################################################################################################

# если была нажата ссылка "выход" #################################################################
if (isset($_GET['exit'])) {
if ($_GET['exit']=='yes') {
# удаление cookie логина и пароля
SetCookie ("login_user", "0", time()-1, "/");
SetCookie ("password_user", "0", time()-1, "/");
# удаление cookie для форума
SetCookie ("forum_cookie_c16b4b", "0", time()-1, "/");
sleep(1);
# выход и перенаправление на главную страницу
header('Location: /'.$language_code.'/index.html');
 }
}
###################################################################################################

###################################################################################################
# выбираем из базы данных title страницы
$title_query=mysql_query("select data from config_$language_code where (name='title')");
$title=mysql_result($title_query, 0);
# выбираем из базы данных description страницы
$description_query=mysql_query("select data from config_$language_code where (name='description')");
$description=mysql_result($description_query, 0);
# выбираем из базы данных keywords страницы
$keywords_query=mysql_query("select data from config_$language_code where (name='keywords')");
$keywords=mysql_result($keywords_query, 0);
# выбираем из базы данных url сайта
$url_query=mysql_query("select data from config_$language_code where (name='url')");
$url=mysql_result($url_query, 0);
###################################################################################################

# подключаем языковой файл ########################################################################
require("core/language.$language_code.php");
###################################################################################################
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title><? echo $title ?></title>
<meta name="description" content="<? echo $description ?>">
<meta name="keywords" content="<? echo $keywords ?>">
<link rel="icon" href="http://<? echo $url ?>/favicon.ico" type="image/x-icon">
<link href="/templates/<? echo name_template_project ?>/index/style/style.css" rel="stylesheet">
<script src="/ajax/jquery/jquery-latest.min.js" type="text/javascript"></script>
<script src="/ajax/jquery/jquery.masonry.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
$('#container').masonry({
// указываем элемент-контейнер в котором расположены блоки для динамической верстки
itemSelector: '.item',
// true - если у вас все блоки одинаковой ширины
isResizable: false,
// перестраивает блоки при изменении размеров окна
isAnimated: true,
// по центру
isFitWidth: true,
// анимируем перестроение блоков
animationOptions: {
queue: false,
duration: 500
}
});
  });
</script>

<?
if (basename($_SERVER['SCRIPT_NAME']) == "monitor_online_vk.php") {
?>
<link rel="stylesheet" type="text/css" href="/ajax/soundmanager/demo/mp3-player-button/css/mp3-player-button.css" />
<script type="text/javascript" src="/ajax/soundmanager/script/soundmanager2.js"></script>
<script type="text/javascript" src="/ajax/soundmanager/demo/mp3-player-button/script/mp3-player-button.js"></script>
<script>
soundManager.setup({
  url: '/ajax/soundmanager/swf'
});
</script>
<script type="text/javascript">
$(document).ready(function(){
$("#show_hide_2, #show_hide_3").hide();
$(".showbox_filter_2").click(function(){
$("#show_hide_2, #show_hide_3").toggle(500);
});
  });
</script>
<?
}
?>
<script type="text/javascript">
$(document).ready(function(){
$("#show_hide_4").hide();
$(".showbox_filter_4").click(function(){
$("#show_hide_4").toggle(500);
});
  });
</script>
</head>
<body>

<table border="0" cellpadding="5" cellspacing="0" align="center" width="100%">
  <tr>
    <td align="center" width="40%" class="logo"><a title="<? echo $title ?>" href="/index.html"><img src="/templates/<? echo name_template_project ?>/all/images/logo_nayavu.gif"></a></td>
    <td align="left" width="30%">
<?
###################################################################################################
# начало, выводим форму авторизации если посетитель не заходил ####################################
###################################################################################################
if (!auth_check_cookie("user", $_COOKIE['login_user'], $_COOKIE['password_user']) && !$num_of_such_register_user) {
?>
<form action="/auth.html" method="post">
<table border="0" cellspacing="0" cellpadding="7">
  <tr>
    <td><b><? echo header_login ?></b></td>
    <td><input type="text" size="12" maxlength="30" name="login"></td>
    <td colspan="1" rowspan="2"><input type="submit" name="submit_auth" value="<? echo header_enter_button ?>"></td>
  </tr>
  <tr>
    <td><b><? echo header_password ?></b></td>
    <td><input type="password" size="12" maxlength="30" name="password"></td>
  </tr>
  <tr>
    <td colspan="3" rowspan="1" align="center"><a href="/<? echo $language_code ?>/lostpass.html"><? echo header_password_restore ?></a>&nbsp;|&nbsp;<input type="checkbox" name="save"><? echo header_remember; ?></td>
  </tr>
</table>
</form>
<?
###################################################################################################
# конец, выводим форму авторизации если посетитель не заходил #####################################
###################################################################################################
} else {
###################################################################################################
# начало, если посетитель авторизован, то выводим меню к личным настройкам ########################
###################################################################################################
?>
<table border="0" cellspacing="0" cellpadding="3" align="right">
  <tr>
    <td>→</td>
    <td>
<?
if (isset($login_from_db)) {
echo $login_from_db;
} else {
echo $_COOKIE['login_user'];
}
?>
&nbsp;<a href="/<? echo $language_code ?>/index/exit/yes">(<? echo header_logout ?>)</a>
    </td>
  </tr>
  <tr>
    <td>→</td>
    <td><a href="/<? echo $language_code ?>/profile.html"><? echo header_link_profile ?></a></td>
  </tr>
</table>
<?
}
###################################################################################################
# конец, если посетитель авторизован, то выводим меню к личным настройкам #########################
###################################################################################################
?>
    </td>
    <td align="center" width="20%">
<table border="0" cellpadding="5" cellspacing="0">
 <tr>
   <td align="center"><!-- <a href="http://<? echo $url ?>/forum"><img src="/templates/<? echo name_template_project ?>/index/images/forum.png"></a> --></td>
   <td align="center"><a href="http://<? echo $url ?>/donate.html"><img src="/templates/<? echo name_template_project ?>/index/images/donate.png"></a></td>
 </tr>
   <td align="center"><!-- <a href="http://<? echo $url ?>/forum"><b><? echo header_index_link_forum ?></b></a> --></td>
   <td align="center"><a href="http://<? echo $url ?>/donate.html"><b><? echo header_index_link_donate ?></b></a></td>
 <tr>
</table>
    </td>
    <td width="10%" valign="top">
<!-- ########################################################################################## -->
<!-- # начало, блок вывода языковых флагов #################################################### -->
<!-- ########################################################################################## -->
<table border="0" cellpadding="2" cellspacing="0" align="right">
  <tr>
<?
$language_search=language_search("core/");
for ($n=0; $n < count($language_search); $n++) {
?>
    <td>
<a href="/<? echo $language_search[$n] ?>">
<img src="/templates/<? echo name_template_project ?>/all/images/<? echo $language_search[$n] ?>.png">
</a>
    </td>
<?
}
?>
  </tr>
</table>
<!-- ########################################################################################## -->
<!-- # конец, блок вывода языковых флагов ##################################################### -->
<!-- ########################################################################################## -->
    </td>
  </tr>
  <tr>
    <td colspan="4" align="center">
<?
if (!isset($num_of_user_data)) {
# поиск пользователя в таблице
$user_data_query=mysql_query("select * from user where(login='$_COOKIE[login_user]' and password='$_COOKIE[password_user]')");
$num_of_user_data=mysql_num_rows($user_data_query);
if ($num_of_user_data) {
# получение данных пользователя
$user_data=mysql_fetch_assoc($user_data_query);
 }
}

# начало, если пользователь авторизован, то далее
if ($num_of_user_data) {

# определяем текущего посетителя
if (!isset($id_registered_user)) {
$id_registered_user = $user_data["id_registered_user"];
}

# начало, получение данных, вид отображения списка пользователей
if (isset($_GET['view'])) {
$mode_view=convert_post($_GET['view'], "0");
if ( ($mode_view==1) || ($mode_view==2) || ($mode_view==3) || ($mode_view==4) || ($mode_view==5) || ($mode_view==6) ) {

if ($mode_view==1) {
$mode_view=2;
} elseif ($mode_view==2) {
$mode_view=3;
} elseif ($mode_view==3) {
$mode_view=4;
} elseif ($mode_view==4) {
$mode_view=5;
} elseif ($mode_view==5) {
$mode_view=6;
} elseif ($mode_view==6) {
$mode_view=1;
}

# обновляем данные в таблице - вид отображения
mysql_query("update user set mode_view='$mode_view' where (id_registered_user='$id_registered_user')");
 }
} else {
# выбираем из базы данных mode_view
$mode_view=mysql_result(mysql_query("select mode_view from user where (id_registered_user='$id_registered_user')"), 0);
}
# начало, получение данных, вид отображения списка пользователей

# начало, получение данных, число профилей на странице
if (($_SERVER['REQUEST_METHOD']=='POST') && (isset($_POST['num_in_page']))) {
$num_in_page=convert_post($_POST['num_in_page'], "0");
# обновляем данные в таблице - число профилей на странице
mysql_query("update user set num_in_page='$num_in_page' where (id_registered_user='$id_registered_user')");
} else {
# выбираем из базы данных num_in_page
$num_in_page=mysql_result(mysql_query("select num_in_page from user where (id_registered_user='$id_registered_user')"), 0);
}
# конец, получение данных, число профилей на странице

###################################################################################################
###################################################################################################
### начало, выводим всех пользователей за которыми ведется мониторинг #############################
###################################################################################################
###################################################################################################
?>
<table border="0" cellpadding="0" cellspacing="0" align="center" width="60%">
  <tr>
    <td>
<div>
  <b class="spiffy">
  <b class="spiffy1"><b></b></b>
  <b class="spiffy2"><b></b></b>
  <b class="spiffy3"></b>
  <b class="spiffy4"></b>
  <b class="spiffy5"></b></b>
  <div class="spiffyfg">

<table border="0" cellpadding="2" cellspacing="0" width="98%" align="center">
  <tr>
    <td align="center" class="background_monitoring_table"><? echo header_index_table_view_profile ?></td>
  </tr>
</table>

<div align="right">
<table border="0" cellpadding="2" cellspacing="0">
<form action="/<? echo $language_code?>" method="post" id="num_in_page">
  <tr>
    <td>
<!-- сортируем по просмотрам - онлайн сначала и потом тех кого больше всего просматривает -->
<!-- сортируем по алфавиту всех - онлайн сначала и оффлайн потом -->
<!-- сортируем по дате добавления - онлайн сначала и потом оффлайн в порядке добавления в базу -->
<?
if       ($mode_view==1) {
?>
<a title="<? echo header_index_sort_view_from_up ?>" href='<? echo "/".$language_code."/index/view/1" ?>'><img src="/templates/<? echo name_template_project ?>/index/images/sort_view_from_up.png"></a>&nbsp;
<?
} elseif ($mode_view==2) {
?>
<a title="<? echo header_index_sort_view_from_down ?>" href='<? echo "/".$language_code."/index/view/2" ?>'><img src="/templates/<? echo name_template_project ?>/index/images/sort_view_from_down.png"></a>&nbsp;
<?
} elseif ($mode_view==3) {
?>
<a title="<? echo header_index_sort_abc_from_up ?>" href='<? echo "/".$language_code."/index/view/3" ?>'><img src="/templates/<? echo name_template_project ?>/index/images/sort_abc_from_up.png"></a>&nbsp;
<?
} elseif ($mode_view==4) {
?>
<a title="<? echo header_index_sort_abc_from_down ?>" href='<? echo "/".$language_code."/index/view/4" ?>'><img src="/templates/<? echo name_template_project ?>/index/images/sort_abc_from_down.png"></a>&nbsp;
<?
} elseif ($mode_view==5) {
?>
<a title="<? echo header_index_sort_date_from_up ?>" href='<? echo "/".$language_code."/index/view/5" ?>'><img src="/templates/<? echo name_template_project ?>/index/images/sort_date_from_up.png"></a>&nbsp;
<?
} elseif ($mode_view==6) {
?>
<a title="<? echo header_index_sort_date_from_down ?>" href='<? echo "/".$language_code."/index/view/6" ?>'><img src="/templates/<? echo name_template_project ?>/index/images/sort_date_from_down.png"></a>&nbsp;
<?
}
?>
	</td>
    <td><img title="<? echo header_index_vkontakte_num_in_page ?>" src="/templates/<? echo name_template_project ?>/index/images/in_page.png"></td>
    <td>
<select title="<? echo header_index_vkontakte_num_in_page ?>" class="num_in_page" name="num_in_page" onchange='document.forms["num_in_page"].submit()'>
<? if ($num_in_page==8) { ?><option selected value="8">8</option><? } else { ?> <option value="8">8</option> <? } ?>
<? if ($num_in_page==12) { ?><option selected value="12">12</option><? } else { ?> <option value="12">12</option> <? } ?>
<? if ($num_in_page==16) { ?><option selected value="16">16</option><? } else { ?> <option value="16">16</option> <? } ?>
<? if ($num_in_page==20) { ?><option selected value="20">20</option><? } else { ?> <option value="20">20</option> <? } ?>
<? if ($num_in_page==24) { ?><option selected value="24">24</option><? } else { ?> <option value="24">24</option> <? } ?>
<? if ($num_in_page==28) { ?><option selected value="28">28</option><? } else { ?> <option value="28">28</option> <? } ?>
<? if ($num_in_page==32) { ?><option selected value="32">32</option><? } else { ?> <option value="32">32</option> <? } ?>
<? if ($num_in_page==64) { ?><option selected value="64">64</option><? } else { ?> <option value="64">64</option> <? } ?>
<? if ($num_in_page==99999) { ?><option selected value="99999">&infin;</option><? } else { ?> <option value="99999">&infin;</option> <? } ?>
</select>
    </td>
    <td>&nbsp;&nbsp;</td>
  </tr>
</form>
</table>
</div>

<table border="0" cellpadding="7" cellspacing="0" align="center" width="100%">
<?
# сортируем по просмотрам - онлайн сначала и потом тех кого больше всего просматривает -->
# сортируем по алфавиту всех - онлайн сначала и оффлайн потом -->
# сортируем по дате добавления - онлайн сначала и потом оффлайн в порядке добавления в базу -->
if ($mode_view==1) {
$select_monitoring_user_query=mysql_query("select * from vkontakte_user_monitoring_in_profile where id_registered_user='$id_registered_user' order by num_view asc");
} elseif ($mode_view==2) {
$select_monitoring_user_query=mysql_query("select * from vkontakte_user_monitoring_in_profile where id_registered_user='$id_registered_user' order by num_view desc");
} elseif (($mode_view==3) || ($mode_view==4)) {
$select_monitoring_user_query=mysql_query("select * from vkontakte_user_monitoring_in_profile where id_registered_user='$id_registered_user'");
} elseif ($mode_view==5) {
$select_monitoring_user_query=mysql_query("select * from vkontakte_user_monitoring_in_profile where id_registered_user='$id_registered_user' order by id asc");
} elseif ($mode_view==6) {
$select_monitoring_user_query=mysql_query("select * from vkontakte_user_monitoring_in_profile where id_registered_user='$id_registered_user' order by id desc");
} else {
$select_monitoring_user_query=mysql_query("select * from vkontakte_user_monitoring_in_profile where id_registered_user='$id_registered_user'");
}

# определяем количество добавленных пользователей в профиль посетителя
$num_monitoring_user=mysql_num_rows($select_monitoring_user_query);
# если есть добавленные пользователи
if ($num_monitoring_user) {

# вычисляем номер страницы
if (empty($_GET['page_m_user']) || (convert_post($_GET['page_m_user'], "0") <= 0)) {
$page_m_user=1;
} else {
# cчитывание текущей страницы
$page_m_user=(int) convert_post($_GET['page_m_user'], "0");
}

# количество страниц
$pages_count_m_user=ceil($num_monitoring_user / $num_in_page);
# если номер страницы оказался больше количества страниц
if ($page_m_user > $pages_count_m_user) $page_m_user = $pages_count_m_user;
$start_pos_m_user = ($page_m_user - 1) * $num_in_page;
?>
  <tr>
    <td align="center">
<?
###################################################################################################
# начало, заносим данные пользователей в массив ###################################################
###################################################################################################
while ($get_monitoring_user=mysql_fetch_array($select_monitoring_user_query)) {
# флаг в онлайне или нет
$online_now=false;

# получаем данные пользователя
$vk_user_query=mysql_query("select * from vkontakte_user_to_monitoring where id_monitoring_user='$get_monitoring_user[id_monitoring_user]'");
$vk_user_data=mysql_fetch_assoc($vk_user_query);

# определяем в онлайн ли пользователь
$online_now=is_online(0, $get_monitoring_user["id_monitoring_user"]);

# выбираем пользователей которые в онлайне
if ($online_now==true) {
$online_user_mass[]=array("id_monitoring_user" => $vk_user_data["id_monitoring_user"], "id_vk_user" => $vk_user_data["id_vk_user"], "avatar_vk_user" => $vk_user_data["avatar_vk_user"], "fio_vk_user" => $vk_user_data["fio_vk_user"], "online" => "yes");
# выбираем тех кто в оффлайн
} else {
$offline_user_mass[]=array("id_monitoring_user" => $vk_user_data["id_monitoring_user"], "id_vk_user" => $vk_user_data["id_vk_user"], "avatar_vk_user" => $vk_user_data["avatar_vk_user"], "fio_vk_user" => $vk_user_data["fio_vk_user"], "online" => "no");
 }
}

# сравнение по убыванию или возрастанию (алфавит)
function cmp_a($a, $b) {
return strncasecmp($b["fio_vk_user"], $a["fio_vk_user"], 2);
}
function cmp_b($a, $b) {
return strncasecmp($a["fio_vk_user"], $b["fio_vk_user"], 2);
}

# сортируем в обратном порядке массивы
if (isset($online_user_mass)) {
$online_user_mass=array_reverse($online_user_mass);
if ($mode_view==3) {
usort($online_user_mass, "cmp_a");
 }
if ($mode_view==4) {
usort($online_user_mass, "cmp_b");
 }
}

if (isset($offline_user_mass)) {
$offline_user_mass=array_reverse($offline_user_mass);
if ($mode_view==3) {
usort($offline_user_mass, "cmp_a");
 }
if ($mode_view==4) {
usort($offline_user_mass, "cmp_b");
 }
}

###################################################################################################
# конец, заносим данные пользователей в массив ####################################################
###################################################################################################

###################################################################################################
# начало, выводим аватарку и ФИО для тех кто в онлайн и оффлайн ###################################
###################################################################################################

# объединяем массивы онлайн и оффлайн пользователей в один, или оставляем какой-то один
if ( (isset($online_user_mass)) && (isset($offline_user_mass)) ) {
$online_offline_user_mass=array_merge($online_user_mass, $offline_user_mass);
} elseif ( (!isset($online_user_mass)) && (isset($offline_user_mass)) ) {
$online_offline_user_mass=$offline_user_mass;
} elseif ( (isset($online_user_mass)) && (!isset($offline_user_mass)) ) {
$online_offline_user_mass=$online_user_mass;
}

# узнаем часовой пояс посетителя
$timezone_user=mysql_result(mysql_query("select timezone from user where (id_registered_user='$id_registered_user')"), 0);
# устанавливаем часовой пояс
date_default_timezone_set($timezone_user);

?>
<div id="container">
<?
# начальное значение величины "от"
$start_pos_m_user_first = $start_pos_m_user;
# начало, выводим из массива "от" и "до"
for ($start_pos_m_user; $start_pos_m_user<($start_pos_m_user_first+$num_in_page); $start_pos_m_user++) {
# начало, если есть запись, то выводим
if ($online_offline_user_mass[$start_pos_m_user]) {

$last_online_user_query="";
$last_time_vk_get="";
$last_time_vk="";
$is_mobile="";
# определяем когда последний раз пользователь был в онлайне и с мобильного ли устройства
$last_online_user_query = mysql_query("select time_last_online, online_mobile from vkontakte_user_to_monitoring where (id_monitoring_user=".$online_offline_user_mass[$start_pos_m_user][id_monitoring_user].")");
$last_time_vk_get=mysql_fetch_assoc($last_online_user_query);
# последний раз в онлайне
$last_time_vk=$last_time_vk_get["time_last_online"];
# с мобильного ли
$is_mobile=$last_time_vk_get["online_mobile"];

if ($last_time_vk==NULL) {
$last_time_vk="last online: not detected";
} else {
# определяем дату и время
$time_today = mktime(date("H"), date("i"), date("s"), date("m"), date("j"), date("Y"));
# если последний раз онлайн был сегодня
if (date("d.m.Y", $time_today) == date("d.m.Y", $last_time_vk)) {
$last_time_vk="last online: сегодня ".date("H:i", $last_time_vk);
# последний раз онлайн вчера
} elseif (date("d.m.Y", strtotime('-1 day')) == date("d.m.Y", $last_time_vk)) {
$last_time_vk="last online: вчера ".date("H:i", $last_time_vk);
} else {
$last_time_vk="last online: ".date("d.m.Y, H:i", $last_time_vk);
 }
}
?>
<div class="item">
<table border="0" cellpadding="0" cellspacing="10">
<?
# определяем число добавленных статусов
$num_add_status_sql=mysql_query("select * from vkontakte_user_status_change where(id_vk_user='".$online_offline_user_mass[$start_pos_m_user][id_vk_user]."' && id_registered_user='$id_registered_user')");
if (mysql_num_rows($num_add_status_sql)) {
$num_add_status=mysql_fetch_assoc($num_add_status_sql);
$add_status = $num_add_status["add_status"];
$num_view=0;
$num_view_next=0;
$num_view = $num_add_status["num_view"];
$num_view_next = $num_view + 1;
# удаляем запись о количестве добавленных статусов при определенном количестве просмотров титульной страницы 
if ($num_view_next == 3) {
mysql_query("delete from vkontakte_user_status_change where (id_vk_user='".$online_offline_user_mass[$start_pos_m_user][id_vk_user]."' && id_registered_user='$id_registered_user')");
} else {
# увеличиваем счетчик просмотров
mysql_query("update vkontakte_user_status_change set num_view='$num_view_next' where (id_vk_user='".$online_offline_user_mass[$start_pos_m_user][id_vk_user]."' && id_registered_user='$id_registered_user')");
 }
} else {
$add_status="";
}

# определяем число добавленных или удаленных пользователей
$num_add_del_friends_sql=mysql_query("select * from vkontakte_user_friends_change where(id_vk_user='".$online_offline_user_mass[$start_pos_m_user][id_vk_user]."' && id_registered_user='$id_registered_user')");
if (mysql_num_rows($num_add_del_friends_sql)) {
$num_add_del_friends=mysql_fetch_assoc($num_add_del_friends_sql);
$add_friends = $num_add_del_friends["add_friends"];
$del_friends = $num_add_del_friends["delete_friends"];
$num_view=0;
$num_view_next=0;
$num_view = $num_add_del_friends["num_view"];
$num_view_next = $num_view + 1;
# удаляем запись о количестве добавленных или удаленных друзей при определенном количестве просмотров титульной страницы 
if ($num_view_next == 3) {
mysql_query("delete from vkontakte_user_friends_change where (id_vk_user='".$online_offline_user_mass[$start_pos_m_user][id_vk_user]."' && id_registered_user='$id_registered_user')");
} else {
# увеличиваем счетчик просмотров
mysql_query("update vkontakte_user_friends_change set num_view='$num_view_next' where (id_vk_user='".$online_offline_user_mass[$start_pos_m_user][id_vk_user]."' && id_registered_user='$id_registered_user')");
 }
} else {
$add_friends="";
$del_friends="";
}

# если пользователь онлайн - выделяем зеленым
if ($online_offline_user_mass[$start_pos_m_user]["online"]=="yes") {
?>
  <tr>
    <td align="center">

<table border="0" cellpadding="1" cellspacing="0">
<tr>
  <td valign="top">
	
      <table border="0" cellpadding="2" cellspacing="5" class="user_online">
        <tr>
          <td align="center">
            <a href="/<? echo $language_code ?>/monitor_online_vk/page_m_user/<? echo $page_m_user ?>/id_vk_user/<? echo $online_offline_user_mass[$start_pos_m_user][id_vk_user] ?>"><? echo get_avatar($online_offline_user_mass[$start_pos_m_user]["avatar_vk_user"]) ?></a><br><nobr>&nbsp;<font class="font_small"><? echo header_index_vkontakte_online_now ?></font><a href="http://vk.com/id<? echo $online_offline_user_mass[$start_pos_m_user][id_vk_user] ?>" target="_blank"><img src="/templates/<? echo name_template_project ?>/index/images/vk.png"></a><? if ($is_mobile==1){ ?><img title="<? echo header_index_vkontakte_is_mobile ?>" src="/templates/<? echo name_template_project ?>/index/images/mobile.png"><? } ?></nobr>
          </td>
        </tr>
      </table>

  </td>

<?
if ($add_status || $add_friends || $del_friends) {
?>
  <td valign="top">
<?
if ($add_status) {
?>
<table border="0" cellpadding="0" cellspacing="0" style="margin-bottom: 2px;" width="17px">
<tr>
  <td align="center" class="status_add_style"><a target="_blank" title="<? echo header_index_add_status_title ?>" class="status_add" href="/<? echo $language_code ?>/monitor_online_vk/id_vk_user/<? echo $online_offline_user_mass[$start_pos_m_user][id_vk_user] ?>/view/yes">+<? echo $add_status ?></a></td>
</tr>
</table>
<?
}
if ($add_friends) {
?>
<table border="0" cellpadding="0" cellspacing="0" style="margin-bottom: 2px;" width="17px">
<tr>
  <td align="center" class="friends_add_style"><a target="_blank" title="<? echo header_index_add_friends_title ?>" class="friends_add_delete" href="/<? echo $language_code ?>/friends/id_vk_user/<? echo $online_offline_user_mass[$start_pos_m_user][id_vk_user] ?>/view/yes">+<? echo $add_friends ?></a></td>
</tr>
</table>
<?
}
if ($del_friends) {
?>
<table border="0" cellpadding="0" cellspacing="0" style="margin-bottom: 2px;" width="17px">
<tr>
  <td align="center" class="friends_delete_style"><a target="_blank" title="<? echo header_index_del_friends_title ?>" class="friends_add_delete" href="/<? echo $language_code ?>/friends/id_vk_user/<? echo $online_offline_user_mass[$start_pos_m_user][id_vk_user] ?>/view/yes">-<? echo $del_friends ?></a></td>
</tr>
</table>
<?
 }
?>
  </td>
<?
}
?>
  
</tr>
</table>

    </td>
  </tr>
<?
# если пользователь оффлайн - выделяем красным
} elseif ($online_offline_user_mass[$start_pos_m_user]["online"]=="no") {
?>
  <tr class="user_offline">
    <td align="center">
<table border="0" cellpadding="1" cellspacing="0">
<tr>
  <td>	
      <table border="0" cellpadding="2" cellspacing="5" class="user_offline"><tr><td align="center"><a href="/<? echo $language_code ?>/monitor_online_vk/page_m_user/<? echo $page_m_user ?>/id_vk_user/<? echo $online_offline_user_mass[$start_pos_m_user][id_vk_user] ?>"><? echo get_avatar($online_offline_user_mass[$start_pos_m_user]["avatar_vk_user"]) ?></a><br><nobr>&nbsp;<font class="font_small"><? echo header_index_vkontakte_offline_now ?></font><a href="http://vk.com/id<? echo $online_offline_user_mass[$start_pos_m_user][id_vk_user] ?>" target="_blank"><img src="/templates/<? echo name_template_project ?>/index/images/vk.png"></a></nobr></td></tr></table>
  </td>

<?
if ($add_friends || $del_friends) {
?>
  <td valign="top">
<?
if ($add_friends) {
?>
<table border="0" cellpadding="0" cellspacing="0" style="margin-bottom: 2px;" width="17px">
<tr>
  <td align="center" class="friends_add_style"><a target="_blank" title="<? echo header_index_add_friends_title ?>" class="friends_add_delete" href="/<? echo $language_code ?>/friends/id_vk_user/<? echo $online_offline_user_mass[$start_pos_m_user][id_vk_user] ?>/view/yes">+<? echo $add_friends ?></a></td>
</tr>
</table>
<?
}
if ($del_friends) {
?>
<table border="0" cellpadding="0" cellspacing="0" style="margin-bottom: 2px;" width="17px">
<tr>
  <td align="center" class="friends_delete_style"><a target="_blank" title="<? echo header_index_del_friends_title ?>" class="friends_add_delete" href="/<? echo $language_code ?>/friends/id_vk_user/<? echo $online_offline_user_mass[$start_pos_m_user][id_vk_user] ?>/view/yes">-<? echo $del_friends ?></a></td>
</tr>
</table>
<?
 }
?>
</td>
<?
}
?>  

</tr>
</table>
	  </td>
  </tr>
<?
}
?>
  <tr>
    <td align="center">
<?
if ($online_offline_user_mass[$start_pos_m_user]["online"]=="no") {
?>
      <font class="font_small_grey"><? echo $last_time_vk ?></font><br>
<?
}
?>
      <a href="/<? echo $language_code ?>/monitor_online_vk/page_m_user/<? echo $page_m_user ?>/id_vk_user/<? echo $online_offline_user_mass[$start_pos_m_user][id_vk_user] ?>"><? echo $online_offline_user_mass[$start_pos_m_user]["fio_vk_user"] ?>&nbsp;→</a>
    </td>
  </tr>
</table>
</div>
<?
 }
# конец, если есть запись, то выводим
}
# конец, выводим из массива "от" и "до"
###################################################################################################
# конец, выводим аватарку и ФИО для тех кто в онлайн и оффлайн ####################################
###################################################################################################
?>
</div>
    </td>
  </tr>
  <tr>
    <td align="center"><? echo header_index_vkontakte_stats_info_how ?></td>
  </tr>
<?
# если нет добавленных пользователей в профиле, то выдаем сообщение
} else {
?>
  <tr>
    <td align="center" colspan="5"><? echo header_index_vkontakte_not_exist_user ?></td>
  </tr>
<?
}

# смотрим, выводить ли переходы по страницам
if ($num_monitoring_user > $num_in_page) {
# составляем ЧПУ ссылку
$chpu_link = "/".$language_code."/monitor_online_vk/";
?>
  <tr>
    <td align="center" colspan="5">
<?
page_link($page_m_user, "page_m_user", $num_monitoring_user, $pages_count_m_user, $num_in_page, $chpu_link);
?>
    </td>
  </tr>
<?
}
?>
</table>
</div>

  <b class="spiffy">
  <b class="spiffy5"></b>
  <b class="spiffy4"></b>
  <b class="spiffy3"></b>
  <b class="spiffy2"><b></b></b>
  <b class="spiffy1"><b></b></b></b>
</div>
    </td>
  </tr>
</table>
<?
###################################################################################################
###################################################################################################
### конец, выводим всех пользователей за которыми ведется мониторинг ##############################
###################################################################################################
###################################################################################################

###################################################################################################
###################################################################################################
### начало, выводим общих друзей ##################################################################
###################################################################################################
###################################################################################################

# выбираем всех добавленных пользователей
$select_all_monitoring_user_query=mysql_query("select * from vkontakte_user_monitoring_in_profile where id_registered_user='$id_registered_user'");
# число добавленных пользователей
$num_all_monitoring_user=mysql_num_rows($select_all_monitoring_user_query);
# если добавлено больше двух, то можно тогда искать общих друзей
if ($num_all_monitoring_user >= 2) {

# перебираем всех добавленных пользователей
while ($get_all_monitoring_user=mysql_fetch_array($select_all_monitoring_user_query)) {
$id_mon_user = $get_all_monitoring_user["id_monitoring_user"];
# получаем id_vk этого пользователя и ФИО
$get_info_this_user_sql=mysql_query("select id_vk_user,fio_vk_user from vkontakte_user_to_monitoring where id_monitoring_user='$id_mon_user'");
$get_info_this_user=mysql_fetch_assoc($get_info_this_user_sql);
$id_vk_this_user=$get_info_this_user["id_vk_user"];
$fio_this_user=$get_info_this_user["fio_vk_user"];
# получаем список друзей этого пользователя и дату списка
$user_data_friends_list_query=mysql_query("select vk_list_friends_id, time_add from vkontakte_user_friends_log where id_vk_user='$id_vk_this_user' order by time_add desc limit 1");
$user_data_friends_list=mysql_fetch_assoc($user_data_friends_list_query);
$select_friends_this_user = $user_data_friends_list["vk_list_friends_id"];
# заносим в массив всех друзей
$array_all[] = explode("#", $select_friends_this_user);
# для какого пользователя заносятся эти друзья, а также дата списка
$array_id_vk_user_friends[] = $id_vk_this_user;
$array_fio_vk_user_friends[] = $fio_this_user;
$array_id_vk_user_date_list[] = $user_data_friends_list["time_add"];
}

# Алгоритм для проверки
# $array_all[0]=array("10","20","30","40","50");
# $array_all[1]=array("12","22","11","00","52");
# $array_all[2]=array("13","23","33","43","53");
# $array_all[3]=array("14","24","34","44","54");
# $array_all[4]=array("15","25","35","45","55");
# $array_all[5]=array("16","26","36","46","56");
# $array_all[6]=array("17","22","37","00","57");
# $array_all[7]=array("18","28","38","22","11");

function compare_friends($array_all, $array_id_vk_user_friends, $array_id_vk_user_date_list, $array_fio_vk_user_friends) {

$array_item = count($array_all);
$array_compare[] = null;

for ($n=0; $n < $array_item; $n++) {
$m=$n+1;
for ($m; $m < $array_item + 1; $m++) {
$intersect_elem = array_intersect($array_all[$n], $array_all[$m]);
if ($intersect_elem) {
foreach ($intersect_elem as $value_a) {
if (!in_array($value_a, $array_compare)) {
foreach ($array_all as $key => $value_b) {
if (in_array($value_a, $value_b)) {
$array_all_value[] = array($key, $value_a);
      }
     }
    }
$array_compare[] = $value_a;
   }
  }
 }
}

# уникальные индексы
$array_final_index_repeat = array_unique($array_compare);
foreach ($array_final_index_repeat as $value_f) {
if ($value_f) {
$array_final_index_un[] = $value_f;
 }
}

# id2 - 22
# id7 - 22
# id8 - 22
# id2 - 00
# id7 - 00
# id2 - 11
# id8 - 11
# ---------
# 22,00,11
# ---------
# array[] = array(array[2], array[22]);
# array[] = array(array[7], array[22]);
# array[] = array(array[8], array[22]);

# ищем 22, если есть записываем в массив array[]  =  array(22, "id2,id7,id8");
# перед этим ищем в массиве 22, если есть то дописываем, если нет - новая запись

# ищем 00, если есть записываем в массив array[]  =  array(00, "id2,id7");
# перед этим ищем в массиве 22

# ищем 11, если есть записываем в массив array[]  =  array(11, "id2,id8");
# перед этим ищем в массиве 22

# перебираем 22,00,11
foreach ($array_final_index_un as $value_c) {

# перебираем массив со всеми значениями
foreach ($array_all_value as $value_d) {

$in_massv = false;

# если есть совпадение
if ($value_c == $value_d[1]) {

# есть ли уже массив с этой записью
foreach ($array_group as $key => $value_e) {
if ($value_e[0] == $value_c) {
$exist_data = $key;
$in_massv = true;
 }
}

# если еще нет в массиве
if ($in_massv == false) {
$array_group[] = array($value_c, $array_id_vk_user_friends[$value_d[0]]." - <a target='_blank' href='http://vk.com/id".$array_id_vk_user_friends[$value_d[0]]."'>".$array_fio_vk_user_friends[$value_d[0]]."</a> <font class='font_small_grey'>(".date("d.m.Y, H:i", $array_id_vk_user_date_list[$value_d[0]]).")</font>");
} elseif ($in_massv == true) {
$value_d_new = $array_group[$exist_data][1];
$value_d_new = $value_d_new."<br>".$array_id_vk_user_friends[$value_d[0]]." - <a target='_blank' href='http://vk.com/id".$array_id_vk_user_friends[$value_d[0]]."'>".$array_fio_vk_user_friends[$value_d[0]]."</a> <font class='font_small_grey'>(".date("d.m.Y, H:i", $array_id_vk_user_date_list[$value_d[0]]).")</font>";
$array_group[$exist_data] = array($value_c, $value_d_new);

   }
  }
 }
}

# сравнение элементов многомерного массива
function cmp($a, $b) {
if ($a[1] == $b[1]) {
return 0;
 }
return ($a[1] < $b[1]) ? -1 : 1;
}
# сортировка массива по первому значению
uasort($array_group, 'cmp');
# пересортируем ключи
$m=0;
$array_all_friends=null;
foreach ($array_group as $key => $value) {
$array_group_new[$m] = array($value[0], $value[1]);
# составляем список друзей
if (!in_array($value[0], $array_all_friends)) {
$array_all_friends[] = $value[0];
}
$m++;
}

# группируем значения в массиве
foreach ($array_group_new as $key => $value) {
if (!$id_vk_this_users) {
$id_vk_this_users = $value[0];
}
# если следующий равен текущему
if ($value[1] == $array_group_new[$key + 1][1]) {
$id_vk_this_users = $id_vk_this_users.",".$array_group_new[$key + 1][0];
 } else {
$array_group_finall[] = array($value[1], $id_vk_this_users);
$id_vk_this_users=null;
 }
}

# соответствие id друзей, тому у кого они есть
foreach ($array_all_friends as $value_y) {
foreach ($array_group_finall as $value_h) {
$array_friends_in_line=explode(",", $value_h[1]);
if (in_array($value_y, $array_friends_in_line)) {
$array_data_ids=explode(" - ", $value_h[0]);
$in_what_user_search[]= $array_data_ids[0];
  }
 }
}

# повторы убираем
$in_what_user_search = array_unique($in_what_user_search);

# заносим в массив данные о друзьях
foreach ($in_what_user_search as $value_g) {
$get_friends_data_info_query=mysql_query("select vk_list_friends_data from vkontakte_user_friends_log where id_vk_user='$value_g' order by time_add desc limit 1");
$friends_data_info=mysql_fetch_assoc($get_friends_data_info_query);
$array_data_all_this_friends=explode("|#|", $friends_data_info["vk_list_friends_data"]);
foreach ($array_data_all_this_friends as $value_i) {
$array_data_all_this_friends_detail[] = explode("-:=", $value_i);
 }
}

# заносим в массив данные по друзьям(только выбранных)
foreach ($array_all_friends as $value_v) {
foreach ($array_data_all_this_friends_detail as $value_p) {
if ($value_v==$value_p[0]) {

# показываем правильную дату
$bdate = $value_p[9];
$bdate_arr = explode(".", $bdate);
$bdate = str_pad($bdate_arr[0], 2, '0', STR_PAD_LEFT).".".str_pad($bdate_arr[1], 2, '0', STR_PAD_LEFT).".".str_pad($bdate_arr[2], 2, '0', STR_PAD_LEFT);
$bdate = str_replace(".00", "", $bdate);
$bdate = str_replace("00", "", $bdate);

# определяем пол
if ($value_p[3]==1) {
$sex="ж";
} else { 
$sex="м"; 
}

$mass_user_info[$value_v] = "<tr class='friends_list_grey'><td class='friends_list_grey' align='center'><a target='_blank' href='http://vk.com/".$value_p[4]."'>".$value_p[4]."</a></td><td class='friends_list_grey' align='center'>".$value_p[2]."</td><td class='friends_list_grey' align='center'>".$value_p[1]."</td><td class='friends_list_grey' align='center'>".$sex."</td><td class='friends_list_grey' align='center'>".$value_p[8].", ".$value_p[7]."</td><td class='friends_list_grey' align='center'>".$bdate."</td></tr>";
break;
  } 
 }
}
?>

<br>
<table border="0" cellpadding="5" cellspacing="1" align="center" width="90%" class="friends_list">
<?
foreach ($array_group_finall as $key => $value_m) {
$array_ids_friends=explode(",", $value_m[1]);

$num=0;
$part_table_friends=null;
foreach ($array_ids_friends as $value_x) {
$num++;
$part_table_friends=$part_table_friends.$mass_user_info[$value_x];
}
$num++;

if ($key==0) {
?>
<tr class="friends_main_header_workspace">
  <td class="friends_main_header_workspace" align="center">Пользователь (дата списка друзей)</td>
  <td class="friends_main_header_workspace" align="center">Id/Короткое имя</td>
  <td class="friends_main_header_workspace" align="center">Фамилия</td>
  <td class="friends_main_header_workspace" align="center">Имя</td>
  <td class="friends_main_header_workspace" align="center">Пол</td>
  <td class="friends_main_header_workspace" align="center">Страна, Город</td>
  <td class="friends_main_header_workspace" align="center">Дата рождения</td>  
</tr>
<?
}

$friends_finall = null;
$numb=0;
$friends_arr_all=explode("<br>", $value_m[0]);
foreach ($friends_arr_all as $value_n) {
$friends_arr=explode(" - ", $value_n);
if ($numb==0) {
$friends_finall = $friends_arr[1];
} else {
$friends_finall = $friends_finall."<br>".$friends_arr[1];
}
$numb++;
}
?>
<tr><td class="friends_list_all" valign="top" colspan="1" rowspan="<? echo $num; ?>"><? echo $friends_finall; ?></td></tr>
<?

echo $part_table_friends;

}
?>
</table>
<br>
<?
return $key;
}

# вызов функции для сравнения друзей
?>
<br>
<div id="show_hide_5">
<table border="0" cellpadding="0" cellspacing="0" align="center" width="80%">
<tr>
  <td>
<div>
  <b class="spiffy">
  <b class="spiffy1"><b></b></b>
  <b class="spiffy2"><b></b></b>
  <b class="spiffy3"></b>
  <b class="spiffy4"></b>
  <b class="spiffy5"></b></b>
  <div class="spiffyfg">
<table border="0" cellpadding="2" cellspacing="0" width="98%" align="center">
  <tr>
    <td align="center" class="background_monitoring_table">
<? echo header_index_table_find_friends ?>
<div class="showbox_filter_4" align="center"><img src="/templates/<? echo name_template_project ?>/index/images/arrow_down.png"></div>
	</td>
  </tr>
</table>
<div id="show_hide_4">
<?
$to_view=compare_friends($array_all, $array_id_vk_user_friends, $array_id_vk_user_date_list, $array_fio_vk_user_friends);
if (strlen($to_view)==0) {
echo "<div align='center'><b>Общих друзей не найдено!</b></div>";
}
?>
</div>
  </div>
  <b class="spiffy">
  <b class="spiffy5"></b>
  <b class="spiffy4"></b>
  <b class="spiffy3"></b>
  <b class="spiffy2"><b></b></b>
  <b class="spiffy1"><b></b></b></b>
</div>
  </td>
</tr>
</table>
</div>
<?

if (strlen($to_view)==0) {
?>
<script type="text/javascript">
$(document).ready(function(){
$("#show_hide_5").hide();
});
</script>
<?
}

}
###################################################################################################
###################################################################################################
### конец, выводим общих друзей ##################################################################
###################################################################################################
###################################################################################################
}
# конец, если пользователь авторизован, то далее
?>
<nobr>
<?
###################################################################################################
# начало, показываем ссылку Зарегистироваться, если посетитель не авторизован #####################
###################################################################################################
if (!auth_check_cookie("user", $_COOKIE['login_user'], $_COOKIE['password_user']) && !$num_of_such_register_user) {
?>
→&nbsp;<a href="/<? echo $language_code ?>/register.html"><b><? echo header_index_link_register ?></b></a>
<?
}
###################################################################################################
# конец, показываем ссылку Зарегистироваться, если посетитель не авторизован ######################
###################################################################################################
?>
</nobr>
    </td>
  </tr>
  <tr>
    <td colspan="4" align="center">
<!-- ########################################################################################## -->
<!-- # начало, форма для ввода id или короткого имени для поиска пользователя ################# -->
<!-- ########################################################################################## -->
<table border="0" cellpadding="10" cellspacing="0">
<form action="/<? echo $language_code?>/index.html" method="post">
  <tr class="bg_form_id_or_name">
    <td><b><? echo header_index_id_or_name_user ?>:</b></td>
    <td><input type="text" size="30" maxlength="50" name="id_or_name_user" value="<? if (isset($id_or_name_user)) { echo $id_or_name_user; } ?>"></td>
    <td><input type="submit" name="submit_id_or_name_user" value="<? echo header_index_button_search_id_or_name_user ?>"></td>
  </tr>
  <tr>
  	<td align="center" valign="top"><a href="/<? echo $language_code ?>/help.html"><font class="font_small"><? echo header_index_how_search_id_or_name ?></font></a></td>
  	<td></td>
  	<td></td>
  </tr>
</form>
</table>
<!-- ########################################################################################## -->
<!-- # конец, форма для ввода id или короткого имени для поиска пользователя ################## -->
<!-- ########################################################################################## -->
    </td>
  </tr>
  <tr>
    <td colspan="4">
<?
###################################################################################################
###################################################################################################
### начало, добавление пользователя в профиль посетителя для мониторинга ##########################
###################################################################################################
###################################################################################################
if (($_SERVER['REQUEST_METHOD']=='POST') && (isset($_POST['submit_add_user']))) {
# преобразование id пользователя и hash_fave в безопасный вид
$id_user=convert_post($_POST['id_user'], "0");
$fio_user=convert_post($_POST['fio_user'], "0");
$avatar=convert_post($_POST['avatar'], "0");
$online_offline=convert_post($_POST['online_offline'], "0");
$time_in_online=convert_post($_POST['time_in_online'], "0");

# начало, проверяем на корректность id пользователя
if (!preg_match("/^[0-9]+$/i", $id_user)) {
$result_message[]=array("message" => header_index_vkontakte_id_user_error, "class" => bad);
} else {
# определяем дату и время
$time_add=mktime(date("H"), date("i"), date("s"), date("m"), date("j"), date("Y"));
# определяем id текущего посетителя
$id_registered_user_query=mysql_query("select id_registered_user from user where (login='$_COOKIE[login_user]' and password='$_COOKIE[password_user]')");
$id_registered_user=mysql_result($id_registered_user_query, 0);

###################################################################################################
# начало, если пользователя еще нет в таблице всех пользователей, то добавляем его ################
###################################################################################################
$id_monitoring_user_query=mysql_query("select id_monitoring_user from vkontakte_user_to_monitoring where id_vk_user='$id_user'");
if (!mysql_num_rows($id_monitoring_user_query)) {
# если в онлайне
if ($online_offline) {
# добавляем в таблицу всех пользователей (vkontakte_user_to_monitoring)
mysql_query("insert into vkontakte_user_to_monitoring (id_vk_user, fio_vk_user, avatar_vk_user, time_last_access, time_last_online) values ('$id_user', '$fio_user', '$avatar', '$time_add', '$time_in_online')");
} else {
# добавляем в таблицу всех пользователей (vkontakte_user_to_monitoring)
mysql_query("insert into vkontakte_user_to_monitoring (id_vk_user, fio_vk_user, avatar_vk_user, time_last_access, time_last_online) values ('$id_user', '$fio_user', '$avatar', '$time_add', NULL)");
}
# определяем номер добавленного пользователя
$id_monitoring_user=mysql_result(mysql_query("select id_monitoring_user from vkontakte_user_to_monitoring where id_vk_user='$id_user'"), 0);
# находим имя аватарки
$content_img_path=explode("/", $avatar);
$img_name=$content_img_path[count($content_img_path)-1];
# сохраняем аватарку на сервере
$file = file_get_contents($avatar);
$openedfile = fopen("/var/www/core/vkontakte/avatars/".$img_name, "w");
fwrite($openedfile, $file);
fclose($openedfile);
chmod("/var/www/core/vkontakte/avatars/".$img_name, 0777);
} else {
###################################################################################################
# конец, если пользователя еще нет в таблице всех пользователей, то добавляем его #################
# на страницу закладок ############################################################################

# иначе, определяем номер пользователя в таблице
$id_monitoring_user=mysql_result($id_monitoring_user_query, 0);
}

# для предотвращения повторов добавления
if (!mysql_num_rows(mysql_query("select id_monitoring_user from vkontakte_user_monitoring_in_profile where (id_registered_user='$id_registered_user' && id_monitoring_user='$id_monitoring_user')"))) {
# добавляем в профиль посетителя
mysql_query("insert into vkontakte_user_monitoring_in_profile (id_registered_user, id_monitoring_user, time_filter_from, time_filter_to) values ('$id_registered_user', '$id_monitoring_user', '1293829199', '1548979199')");
if ($online_offline) {
# добавляем также в таблицу онлайн состояний запись
mysql_query("insert IGNORE into vkontakte_user_online_log (id_monitoring_user, time_in_online) values ('$id_monitoring_user', '$time_in_online')");
}
$result_message[]=array("message" => header_index_vkontakte_add_to_profile_complete, "class" => good);
?>
<meta http-equiv='Refresh' content='2'>
<?
  }
 }
# конец, проверяем на корректность id пользователя
}
###################################################################################################
###################################################################################################
### конец, добавление пользователя в профиль посетителя для мониторинга ###########################
###################################################################################################
###################################################################################################

###################################################################################################
###################################################################################################
### начало, принимаем данные id или короткое имя пользователя #####################################
###################################################################################################
###################################################################################################
if (($_SERVER['REQUEST_METHOD']=='POST') && (isset($_POST['submit_id_or_name_user']))) {

# если еще не стартовала сессия
if (!isset($_SESSION)) {
# инициализирум механизм сесссий
session_start();
}

# защита от частых запросов
if (isset($_SESSION['submit_id_or_name_user'])) {
if ((time()-$_SESSION['submit_id_or_name_user'][time_last_use])< 10) {
?>
<meta http-equiv="refresh" content="0; url=http://<? echo $url ?>/<? echo $language_code ?>/index/frequent_requests/1">
<?
exit;
 }
}

# устанавливаем в сессии время последнего обращения
$_SESSION['submit_id_or_name_user'][time_last_use] = time();

# преобразование id или короткое имя пользователя в безопасный вид
$id_or_name_user=convert_post($_POST['id_or_name_user'], "0");

# начало, проверяем на корректность id или имя пользователя
if (!preg_match("/^[a-z0-9_.]+$/i", $id_or_name_user)) {
$result_message[]=array("message" => header_index_vkontakte_id_or_name_user_error, "class" => bad);
} else {

# подключение файла с функцией
require("core/vkontakte/function_get_profiles_vk.php");

# приводим id или короткое имя пользователя к нижнему регистру
$id_or_name_user = strtolower($id_or_name_user);
# если начинается с "id" и далее все символы - цифры, то получаем только цифровой номер id
if ((substr($id_or_name_user, 0, 2) == "id")  && preg_match("/^[0-9]+$/i", substr($id_or_name_user, 2))) {
$ind_to_search = substr($id_or_name_user, 2);
# если состоит только из цифр, то ничего не делаем, id правильный
} elseif (preg_match("/^[0-9]+$/i", $id_or_name_user)) {
$ind_to_search = $id_or_name_user;
} else {
# получается, что введено короткое имя
$ind_to_search=$id_or_name_user;
}

# получаем данные пользователя
$res_profile_data[] = get_vk_data_users($ind_to_search, "online, photo, photo_big");
# массив результатов сохраняем в строку
$result_str = serialize($res_profile_data);

# начало, если страницы пользователя не существует, то ошибка
if (strpos($result_str, "Invalid user")) {
$result_message[]=array("message" => header_index_vkontakte_id_or_name_user_bad, "class" => bad);
} else {

# сохраняем все данные этого пользователя
foreach ($res_profile_data[0][response] as $key => $value) {
# находим id
$id_user=$value[user][uid];
# находим Имя и Отчество
$fio=$value[user][first_name]." ".$value[user][last_name];
# находим аватарку
$avatar=$value[user][photo];
# находим среднее фото
$photo_big=$value[user][photo_big];
# статус онлайн или оффлайн
$online_offline=$value[user][online];
}

# определяем дату и время
$time_in_online = mktime(date("H"), date("i"), date("s"), date("m"), date("j"), date("Y"));

# начальные данные флагов
$user_in_db=false;
$user_in_profile=false;

# начало, если пользователь в черном списке - стоп с выводом ошибки
if (is_ban_user($id_user) == true) {
$result_message[]=array("message" => header_index_vkontakte_id_user_is_ban, "class" => bad);
} else {

###################################################################################################
# начало, находим данные пользователя и проверяем есть ли он в профиле посетителя #################
###################################################################################################
# начало, определяем данные пользователя по id, если он уже есть в базе
$data_monitoring_user_query=mysql_query("select * from vkontakte_user_to_monitoring where id_vk_user='$id_user'");
if (mysql_num_rows($data_monitoring_user_query)) {
# флаг того, что пользователь есть в базе
$user_in_db=true;
# получаем все данные пользователя
$vk_user_data=mysql_fetch_assoc($data_monitoring_user_query);
# получаем номер пользователя
$id_monitoring_user=$vk_user_data["id_monitoring_user"];

# начало, смотрим, есть ли данный пользователь в профиле посетителя
if (isset($id_registered_user)) {
if (mysql_num_rows(mysql_query("select * from vkontakte_user_monitoring_in_profile where (id_registered_user='$id_registered_user' && id_monitoring_user='$vk_user_data[id_monitoring_user]')"))) {
# флаг того, что пользователь в профиле посетителя
$user_in_profile=true;
  }
 }
# конец, смотрим, есть ли данный пользователь в профиле посетителя
}
###################################################################################################
# конец, находим данные пользователя и проверяем есть ли он в профиле посетителя ##################
###################################################################################################

###################################################################################################
# начало, если посетитель авторизован #############################################################
###################################################################################################
if (isset($id_registered_user)) {

###################################################################################################
# начало, если пользователя нет в профиле, но пользователь есть в базе -> сообщаем о том что за ###
# пользователем ведется наблюдение, предлагаем добавить в профиль, показываем аватарку со #########
# статистикой #####################################################################################
###################################################################################################
if (($user_in_profile==false) && ($user_in_db==true)) {
?>
<table border="0" cellpadding="0" cellspacing="10" align="center" width="60%">
  <tr>
    <td><b><? echo header_index_vkontakte_user_is_in_db ?></b></td>
  </tr>
  <tr>
    <td align="center" style="padding:2px"><a title="<? echo monitor_online_vk_delete_user_title ?>" href='<? echo "/".$language_code."/delete_vk_user/id_vk_user/".$id_user ?>'><img src="/templates/<? echo name_template_project ?>/index/images/delete_user.png"></a></td>
  </tr>
<?
# если пользователь онлайн - выделяем зеленым
if (is_online(0, $id_monitoring_user)==true) {
?>
  <tr>
    <td align="center">
      <table border="0" cellpadding="5" cellspacing="0" class="user_online"><tr><td align="center"><a href="/<? echo $language_code ?>/monitor_online_vk/id_vk_user/<? echo $id_user ?>"><? echo get_avatar($vk_user_data["avatar_vk_user"]) ?></a><br><font class="font_small"><? echo header_index_vkontakte_online_now ?></font></td></tr></table>
    </td>
  </tr>
<?
# если пользователь оффлайн - выделяем красным
} elseif (is_online(0, $id_monitoring_user)==false) {
?>
  <tr>
    <td align="center">
      <table border="0" cellpadding="5" cellspacing="0" class="user_offline"><tr><td align="center"><a href="/<? echo $language_code ?>/monitor_online_vk/id_vk_user/<? echo $id_user ?>"><? echo get_avatar($vk_user_data["avatar_vk_user"]) ?></a><br><font class="font_small"><? echo header_index_vkontakte_offline_now ?></font></td></tr></table>
    </td>
  </tr>
<?
}
?>
  <tr>
    <td align="center">
      <a href="/<? echo $language_code ?>/monitor_online_vk/id_vk_user/<? echo $id_user ?>"><? echo $vk_user_data["fio_vk_user"] ?></a>
    </td>
  </tr>
</table>
<!-- ########################################################################################## -->
<!-- # начало, кнопка для добавления пользователя в профиль ################################### -->
<!-- ########################################################################################## -->
<form action="/<? echo $language_code?>/index.html" method="post">
<table border="0" cellpadding="0" cellspacing="0" align="center">
  <tr>
    <td>
      <input type="hidden" name="id_user" value="<? echo $id_user ?>">
      <input type="hidden" name="fio_user" value="<? echo $fio ?>">
      <input type="hidden" name="avatar" value="<? echo $avatar ?>">
      <input type="hidden" name="online_offline" value="<? echo $online_offline ?>">
      <input type="hidden" name="time_in_online" value="<? echo $time_in_online ?>">
      <input type="submit" name="submit_add_user" value="<? echo header_index_vkontakte_add_to_profile ?>">
    </td>
  </tr>
</table>
</form>
<br>
<!-- ########################################################################################## -->
<!-- # конец, кнопка для добавления пользователя в профиль #################################### -->
<!-- ########################################################################################## -->
<?
}
###################################################################################################
# конец, если пользователя нет в профиле, но пользователь есть в базе -> сообщаем о том что за ####
# пользователем ведется наблюдение, предлагаем добавить в профиль, показываем аватарку со #########
# статистикой #####################################################################################
###################################################################################################

###################################################################################################
# начало, пользователь есть в профиле, т.е и есть в базе -> сообщаем что уже добавлен в профиль, ##
# показываем аватарку со статистикой ##############################################################
###################################################################################################
if (($user_in_profile==true) && ($user_in_db==true)) {
?>
<table border="0" cellpadding="0" cellspacing="10" align="center" width="60%">
  <tr>
    <td><b><? echo header_index_vkontakte_user_is_in_profile ?></b></td>
  </tr>
<?
# если пользователь онлайн - выделяем зеленым
if (is_online($id_user, 0)==true) {
?>
  <tr>
    <td align="center">
      <table border="0" cellpadding="5" cellspacing="0" class="user_online"><tr><td align="center"><a href="/<? echo $language_code ?>/monitor_online_vk/id_vk_user/<? echo $id_user ?>"><? echo get_avatar($vk_user_data["avatar_vk_user"]) ?></a><br><font class="font_small"><? echo header_index_vkontakte_online_now ?></font></td></tr></table>
    </td>
  </tr>
<?
# если пользователь оффлайн - выделяем красным
} elseif (is_online($id_user, 0)==false) {
?>
  <tr>
    <td align="center">
      <table border="0" cellpadding="5" cellspacing="0" class="user_offline"><tr><td align="center"><a href="/<? echo $language_code ?>/monitor_online_vk/id_vk_user/<? echo $id_user ?>"><? echo get_avatar($vk_user_data["avatar_vk_user"]) ?></a><br><font class="font_small"><? echo header_index_vkontakte_offline_now ?></font></td></tr></table>
    </td>
  </tr>
<?
}
?>
  <tr>
    <td align="center">
      <a href="/<? echo $language_code ?>/monitor_online_vk/id_vk_user/<? echo $id_user ?>"><? echo $vk_user_data["fio_vk_user"] ?>&nbsp;→</a>
    </td>
  </tr>
</table>
<?
}
###################################################################################################
# конец, пользователь есть в профиле, т.е и есть в базе -> сообщаем что уже добавлен в профиль, ###
# показываем аватарку со статистикой ##############################################################
###################################################################################################

###################################################################################################
# начало, пользователя нет в профиле, пользователя нет в базе -> предлагаем добавить в профиль, ###
# показываем только аватарку ######################################################################
###################################################################################################
if (($user_in_profile==false) && ($user_in_db==false)) {
?>
<!-- ########################################################################################## -->
<!-- # начало, кнопка для добавления пользователя в профиль ################################### -->
<!-- ########################################################################################## -->
<form action="/<? echo $language_code?>/index.html" method="post">
<table border="0" cellpadding="0" cellspacing="0" align="center">
  <tr>
    <td>
      <input type="hidden" name="id_user" value="<? echo $id_user ?>">
      <input type="hidden" name="avatar" value="<? echo $avatar ?>">
      <input type="hidden" name="fio_user" value="<? echo $fio ?>">
      <input type="hidden" name="online_offline" value="<? echo $online_offline ?>">
      <input type="hidden" name="time_in_online" value="<? echo $time_in_online ?>">
      <input type="submit" name="submit_add_user" value="<? echo header_index_vkontakte_add_to_profile ?>">
    </td>
  </tr>
</table>
</form>
<!-- ########################################################################################## -->
<!-- # конец, кнопка для добавления пользователя в профиль #################################### -->
<!-- ########################################################################################## -->

<!-- ########################################################################################## -->
<!-- # начало, выводим ФИО и Аватарку ######################################################### -->
<!-- ########################################################################################## -->
<p align="center">
<?
# выводим ФИО
echo "<b>".$fio."</b><br>";
# выводим Аватарку

# если пользователь онлайн - выделяем зеленым
if ($online_offline==1) {
echo "<table border=\"0\" cellpadding=\"5\" cellspacing=\"0\" class=\"user_online\"><tr><td align=\"center\"><img src=".$photo_big."></td></tr></table>";
} elseif ($online_offline==0) {
echo "<table border=\"0\" cellpadding=\"5\" cellspacing=\"0\" class=\"user_offline\"><tr><td align=\"center\"><img src=".$photo_big."></td></tr></table>";
}
?>
</p><br>
<!-- ########################################################################################## -->
<!-- # конец, выводим ФИО и Аватарку ########################################################## -->
<!-- ########################################################################################## -->
<?
}
###################################################################################################
# конец, пользователя нет в профиле, пользователя нет в базе -> предлагаем добавить в профиль, ####
# показываем только аватарку ######################################################################
###################################################################################################

} else {
###################################################################################################
# конец, если посетитель авторизован ##############################################################
###################################################################################################

###################################################################################################
# начало, если посетитель неавторизован ###########################################################
###################################################################################################

###################################################################################################
# начало, пользователь есть в базе -> предлагаем авторизоваться или зарегистрироваться, ###########
# показываем аватарку со статистикой ##############################################################
###################################################################################################
if ($user_in_db==true) {
?>
<table border="0" cellpadding="0" cellspacing="10" align="center" width="60%">
  <tr>
    <td><b><? echo header_index_vkontakte_user_is_in_db_not_register ?></b></td>
  </tr>
  <tr>
    <td align="center" style="padding:2px"><a title="<? echo monitor_online_vk_delete_user_title ?>" href='<? echo "/".$language_code."/delete_vk_user/id_vk_user/".$id_user ?>'><img src="/templates/<? echo name_template_project ?>/index/images/delete_user.png"></a></td>
  </tr>
<?
# если пользователь онлайн - выделяем зеленым
if (is_online($id_user, 0)==true) {
?>
  <tr>
    <td align="center">
      <table border="0" cellpadding="5" cellspacing="0" class="user_online"><tr><td align="center"><a href="/<? echo $language_code ?>/monitor_online_vk/id_vk_user/<? echo $id_user ?>"><? echo get_avatar($vk_user_data["avatar_vk_user"]) ?></a><br><font class="font_small"><? echo header_index_vkontakte_online_now ?></font></td></tr></table>
    </td>
  </tr>
<?
# если пользователь оффлайн - выделяем красным
} elseif (is_online($id_user, 0)==false) {
?>
  <tr class="user_offline">
    <td align="center">
      <table border="0" cellpadding="5" cellspacing="0" class="user_offline"><tr><td align="center"><a href="/<? echo $language_code ?>/monitor_online_vk/id_vk_user/<? echo $id_user ?>"><? echo get_avatar($vk_user_data["avatar_vk_user"]) ?></a><br><font class="font_small"><? echo header_index_vkontakte_offline_now ?></font></td></tr></table>
    </td>
  </tr>
<?
}
?>
  <tr>
    <td align="center">
      <a href="/<? echo $language_code ?>/monitor_online_vk/id_vk_user/<? echo $id_user ?>"><? echo $vk_user_data["fio_vk_user"] ?>&nbsp;→</a>
    </td>
  </tr>
</table>
<?
}
###################################################################################################
# конец, пользователь есть в базе -> предлагаем авторизоваться или зарегистрироваться, ############
# показываем аватарку со статистикой ##############################################################
###################################################################################################

###################################################################################################
# начало, пользователя нет в базе -> предлагаем авторизоваться или зарегистрироваться, ############
# показываем аватарку #############################################################################
###################################################################################################
if ($user_in_db==false) {
?>
<table border="0" cellpadding="0" cellspacing="5" align="center" width="60%">
  <tr>
    <td><b><? echo header_index_vkontakte_user_not_db_not_register ?></b></td>
  </tr>
  <tr>
    <td align="center">
<!-- ########################################################################################## -->
<!-- # начало, выводим ФИО и Аватарку ######################################################### -->
<!-- ########################################################################################## -->
<?
# выводим ФИО
echo "<b>".$fio."</b><br>";
# выводим Аватарку

# если пользователь онлайн - выделяем зеленым
if ($online_offline==1) {
echo "<table border=\"0\" cellpadding=\"5\" cellspacing=\"0\" class=\"user_online\"><tr><td align=\"center\"><img src=".$photo_big."></td></tr></table>";
} elseif ($online_offline==0) {
echo "<table border=\"0\" cellpadding=\"5\" cellspacing=\"0\" class=\"user_offline\"><tr><td align=\"center\"><img src=".$photo_big."></td></tr></table>";
}
?>
<!-- ########################################################################################## -->
<!-- # конец, выводим ФИО и Аватарку ########################################################## -->
<!-- ########################################################################################## -->
    </td>
  </tr>
</table>
<br>
<?
}
###################################################################################################
# конец, пользователя нет в базе -> предлагаем авторизоваться или зарегистрироваться, #############
# показываем аватарку #############################################################################
###################################################################################################

###################################################################################################
# конец, если посетитель неавторизован ############################################################
###################################################################################################
    }
# конец, определяем данные пользователя по id, если он уже есть в базе
   }
# конец, если пользователь в черном списке - стоп с выводом ошибки
  }
# конец, если страницы пользователя не существует, то ошибка
 }
# конец, проверяем на корректность id или имя пользователя
}
###################################################################################################
###################################################################################################
### конец, принимаем данные id или короткое имя пользователя ######################################
###################################################################################################
###################################################################################################
?>
<table border="0" cellpadding="5" cellspacing="0" align="center" width="100%">
  <tr>
    <td width="15%" valign="top" align="center">
    </td>
    <td width="70%" valign="top">
