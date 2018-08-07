<?
# защита от флуда
include("antiddos/core/antiddos.php");

# подключение файла с настройками конфигурации
require(dirname(__FILE__)."/core/config.php");

# подключение файла с функциями
require(dirname(__FILE__)."/core/function.php");

# /////////////////////////////////////////////////////////////////////////////////////////////////////
# /// начало / проверка, с формы текущего ли сайта поступили данные ///////////////////////////////////
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

# подключение файла верхней части дизайна страницы"
include("templates/".name_template_project."/index/header.php");

# начало, если есть данные id_vk_user
if (isset($_GET['id_vk_user'])) {
# определяем id_vk_user пользователя для мониторинга
$id_vk_user = convert_post($_GET['id_vk_user'], "0");
# проверяем, существует ли такой пользователь
$monitoring_user_query=mysql_query("select * from vkontakte_user_to_monitoring where id_vk_user='$id_vk_user'");

# начало, если ссылка с id_vk_user верная, то далее
if (mysql_num_rows($monitoring_user_query)) {

$vk_user_data=mysql_fetch_assoc($monitoring_user_query);
$id_monitoring_user=$vk_user_data["id_monitoring_user"];
?>
<table border="0" cellpadding="7" cellspacing="0" align="center" width="80%">
  <tr>
    <td height="33" valign="top">&nbsp;<a href="/<? echo $language_code ?>/index.html"><? echo friends_vkontakte_hidden_main_page ?></a>&nbsp;&nbsp;→&nbsp;&nbsp;<a href="/<? echo $language_code ?>/friends/id_vk_user/<? echo $id_vk_user ?>"><? echo friends_vkontakte_hidden_monitoring_workspace_title ?></a>&nbsp;&nbsp;→&nbsp;&nbsp;<font class="title"><? echo friends_vkontakte_hidden_where_workspace_title ?></font></td>
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
<?

$data_friends_find_sql=mysql_query("select * from vkontakte_user_friends_hidden_log where id_vk_user='$id_vk_user'");

if (mysql_num_rows($data_friends_find_sql)) {
# получаем данные id по которым ищем
$data_friends_find=mysql_fetch_assoc($data_friends_find_sql);
$count_num_part_id = substr_count($data_friends_find["vk_list_friends_id"], "#");
# на сколько процентов по крону обработано друзей
$percent_job = round(($data_friends_find["status"] * 100) / $count_num_part_id);

?>

<style type="text/css">
  .temnyi { background-color: #666; border: 1px solid #666; height: 30px; width:50%; position: relative;}
  .svetlyi {background-color: #ccc; height: 30px; width: <? echo $percent_job ?>%; position: absolute; left: 0px; top: 0px;}
</style>

<div class="temnyi">
 <div class="svetlyi"></div>
</div>

</div>
</div>
<?

} else {
$vk_user_data=mysql_fetch_assoc($monitoring_user_query);
$id_monitoring_user=$vk_user_data["id_monitoring_user"];

$data_friends_sql=mysql_query("select * from vkontakte_user_friends_log where id_vk_user='$id_vk_user' order by time_add desc");

$num_rows_record = mysql_num_rows($data_friends_sql);
if ( (!$num_rows_record) || ($num_rows_record==1) ) {
echo "<p align=\"center\"><b>Нет сведений о возможных друзьях!</b></p>";
} else {

# начало, заносим всех удаленных друзей в массив и получаем последний список
while ($get_data_friends_sql = mysql_fetch_array($data_friends_sql)) {
$list_friends=$get_data_friends_sql["vk_list_friends_id"];
# вывод первого главного списка друзей
if ($list_friends_array==NULL) {
# в массив заносим id
$list_friends_array=explode("#", $list_friends);
} else {
# вывод изменений между последним и предпоследним состоянием
$list_friends_array_new=explode("#", $list_friends);
# изменения, если удалены друзья
$list_friends_array_diff_delete[] = array_diff($list_friends_array_new, $list_friends_array);
 }
}

foreach ($list_friends_array_diff_delete as $value_a) {
foreach ($value_a as $value_b) {
$friends_delete[] = $value_b;
 }
}
# конец, заносим всех удаленных друзей в массив и получаем последний список

# объединяем два массива в один и удаляем повторяющиеся id
$friends_array_all = array_unique(array_merge($list_friends_array, $friends_delete));

$m=1;
$elem_array_str="";
foreach ($friends_array_all as $value) {
if ($m==1) {
$elem_array_str = $elem_array_str.$value;
} else {
$elem_array_str = $elem_array_str."|".$value;
}
if ($m==25) {
$elem_array_str=$elem_array_str."#";
$m=0;
 }
$m++;
}

if (substr($elem_array_str, -1) == "#") {
$elem_array_str = substr($elem_array_str, 0, -1);
}

# добавляем в базу id, которые будем проверять
mysql_query("insert vkontakte_user_friends_hidden_log (id_vk_user, vk_list_friends_id, status) values ('$id_vk_user', '$elem_array_str', '0')");
  }
# нет сведений о возможных друзьях
  }
# нет данных о начале поиска скрытых друзей
 }
# конец, если ссылка с id_vk_user верная
}
# конец, если есть данные id_vk_user

# подключение файла нижней части дизайна страницы"
include("templates/".name_template_project."/index/footer.php");
?>
