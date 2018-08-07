<?
//sleep(25);
date_default_timezone_set("Europe/Moscow");
# аватарки нет в базе и нет на сервере            - обновляем в базе и сохраняем на сервере
# аватарка есть в базе, но она неправильная       - обновляем в базе и сохраняем на сервере

# аватарки нет в базе, но она есть на сервере     - обновляем в базе
# аватарка есть в базе, но ее нет на сервере      - сохраняем на сервере

# аватарка нет в базе, есть на сервере и          - удаляем
# никуда не привязана

# подключение файла с настройками конфигурации
require("/var/www/core/config.php");
# подключение файла осуществляющего связь с базой данных
require("/var/www/core/connect.php");
# подключение файла с функциями
require("/var/www/core/function.php");
# подключение файла осуществляющего получение данных с профилей пользователей
require("/var/www/core/vkontakte/function_get_profiles_vk.php");

function urlGetContents($url) {
$cUrl = curl_init();
curl_setopt($cUrl, CURLOPT_URL, $url);
curl_setopt($cUrl, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($cUrl, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 2.0.50727)");
curl_setopt($cUrl, CURLOPT_TIMEOUT, 5);
$content = curl_exec($cUrl);
if (curl_getinfo($cUrl, CURLINFO_HTTP_CODE) == 200) {
$array_image = getimagesizefromstring($content);
if ( ($array_image[mime] == "image/jpeg") || ($array_image[mime] == "image/gif") ) {
$array_image = "";
return $content;
  } else {
return false;
  }
 } else {
return false;
 }
}

# число строк в таблице
$num_rows_table = mysql_result(mysql_query("select count(*) from vkontakte_user_to_monitoring"), 0);
# сколько заходов выборки из таблицы (по 500 записей за раз)
$num_step = ceil($num_rows_table / 500);

# начало, выбираем информацию из профилей пользователей за определенное количество шагов
$num_user_select = 0;
for ($n=0; $n < $num_step; $n++) {

# начало, считываем по порядку из таблицы добавленных пользователей (по 500)
$vkontakte_user_to_monitoring_data = mysql_query("select * from vkontakte_user_to_monitoring order by id_monitoring_user LIMIT ".$num_user_select.", 500");
while ($get_vkontakte_user_to_monitoring_data = mysql_fetch_array($vkontakte_user_to_monitoring_data)) {
$all_data_user[] = $get_vkontakte_user_to_monitoring_data["id_vk_user"];
}
# конец, считываем по порядку из таблицы добавленных пользователей (по 500)

# преобразуем массив в строку, в котором элементы разделяются ","
$all_data_user_str = implode(",", $all_data_user);
# обнуляем массив
unset($all_data_user);
$all_data_user = array();

# получаем данные пользователя
$res_profile_data[] = get_vk_data_users($all_data_user_str, "photo");

# для следующего шага
$num_user_select = $num_user_select + 500;
# 10 секунд ожидаем
//sleep(10);
}
# конец, выбираем информацию из профилей пользователей за определенное количество шагов

$x=0;
# начало, обрабатываем полученную информацию
for ($m=0; $m < count($res_profile_data); $m++) {
foreach ($res_profile_data[$m][response] as $key => $value) {
$x=$x+1;
# находим данные пользователя
$user_monitoring_query=mysql_query("select * from vkontakte_user_to_monitoring where id_vk_user='".$value[user][uid]."'");
$user_monitoring_data=mysql_fetch_assoc($user_monitoring_query);

# id пользователя
$id_monitoring_user=$user_monitoring_data["id_monitoring_user"];

# ссылка на аватарку из базы
$avatar_vk_user=$user_monitoring_data["avatar_vk_user"];
# находим имя аватарки из базы
$content_img_path_db=explode("/", $avatar_vk_user);
$img_name_db=$content_img_path_db[count($content_img_path_db)-1];

# ссылка на аватарку вконтакте
$avatar_link=$value[user][photo];
# находим имя аватарки из вконтакте
$content_img_path_vk=explode("/", $avatar_link);
$img_name_vk=$content_img_path_vk[count($content_img_path_vk)-1];
if ( !strpos($avatar_link, ".") ) {
$img_name_vk = "";
echo "Ne opredelena ssylka dlya: ".$id_monitoring_user."<br>";
}

if (!empty($img_name_vk)) {
###################################################################################################
# начало, аватарки нет в базе и нет на сервере ####################################################
###################################################################################################
if ( (!$avatar_vk_user) && (!file_exists("/var/www/core/vkontakte/avatars/".$img_name_vk)) ) {
# сохраняем аватарку в папку на нашем сервере
if ($file = urlGetContents($avatar_link)) {
$openedfile = fopen("/var/www/core/vkontakte/avatars/".$img_name_vk, "w");
$written = fwrite($openedfile, $file);
fclose($openedfile);
if ($written && (file_exists("/var/www/core/vkontakte/avatars/".$img_name_vk))) {
chmod("/var/www/core/vkontakte/avatars/".$img_name_vk, 0777);
# добавляем в таблицу данные об аватарке
mysql_query("update vkontakte_user_to_monitoring set avatar_vk_user='$avatar_link' where id_monitoring_user='$id_monitoring_user'");
echo "Ne byla v base i na diske. Sohranyaem v base i na diske: ".$img_name_vk."<br>";
  }
 }
}
###################################################################################################
# конец, аватарки нет в базе и нет на сервере #####################################################
###################################################################################################

###################################################################################################
# начало, аватарка есть в базе, но она неправильная ###############################################
###################################################################################################
elseif ($avatar_vk_user && ($img_name_db <> $img_name_vk)) {
# сохраняем аватарку в папку на нашем сервере
if ($file = urlGetContents($avatar_link)) {
$openedfile = fopen("/var/www/core/vkontakte/avatars/".$img_name_vk, "w");
$written = fwrite($openedfile, $file);
fclose($openedfile);
if ($written && (file_exists("/var/www/core/vkontakte/avatars/".$img_name_vk))) {
chmod("/var/www/core/vkontakte/avatars/".$img_name_vk, 0777);
# добавляем в таблицу данные об аватарке
mysql_query("update vkontakte_user_to_monitoring set avatar_vk_user='$avatar_link' where id_monitoring_user='$id_monitoring_user'");
echo "Nepravilnaya. Sohranyaem novuyu v base i na diske: ".$img_name_vk."<br>";
  }
 }
}
###################################################################################################
# конец, аватарка есть в базе, но она неправильная ################################################
###################################################################################################

###################################################################################################
# начало, аватарки нет в базе, но она есть на сервере #############################################
###################################################################################################
elseif ( (!$avatar_vk_user) && (file_exists("/var/www/core/vkontakte/avatars/".$img_name_vk)) ) {
# добавляем в таблицу данные об аватарке
mysql_query("update vkontakte_user_to_monitoring set avatar_vk_user='$avatar_link' where id_monitoring_user='$id_monitoring_user'");
echo "Net v base, no est' na diske. Sohranyaem v base: ".$img_name_vk."<br>";
}
###################################################################################################
# конец, аватарки нет в базе, но она есть на сервере ##############################################
###################################################################################################

###################################################################################################
# начало, аватарка есть в базе, но ее нет на сервере ##############################################
###################################################################################################
elseif ( $avatar_vk_user && ((!file_exists("/var/www/core/vkontakte/avatars/".$img_name_vk)) || (filesize("/var/www/core/vkontakte/avatars/".$img_name_vk) < 50)) ) {
# сохраняем аватарку в папку на нашем сервере
if ($file = urlGetContents($avatar_link)) {
$openedfile = fopen("/var/www/core/vkontakte/avatars/".$img_name_vk, "w");
$written = fwrite($openedfile, $file);
fclose($openedfile);
if ($written && (file_exists("/var/www/core/vkontakte/avatars/".$img_name_vk))) {
chmod("/var/www/core/vkontakte/avatars/".$img_name_vk, 0777);
echo "Est' v base, no net na diske ili nulevoy razmer. Sohranyaem na diske: ".$img_name_vk."<br>";
  }
 }
}
###################################################################################################
# конец, аватарка есть в базе, но ее нет на сервере ###############################################
###################################################################################################
  }
 }
}
# конец, обрабатываем полученную информацию

echo "<br>Vsego obrabotano: ".$x."<br>";

//sleep(30);

###################################################################################################
# начало, просматриваем все аватарки с сервера и проверяем есть ли они в базе (непривязанные) #####
###################################################################################################
$vkontakte_avatars_sql = mysql_query("select avatar_vk_user from vkontakte_user_to_monitoring");
while ($get_vkontakte_avatars_data = mysql_fetch_row($vkontakte_avatars_sql)) {
# находим имя аватарки из базы
$content_img_path_db=NULL;
$img_name_db=NULL;
$content_img_path_db=explode("/", $get_vkontakte_avatars_data[0]);
$img_name_db=$content_img_path_db[count($content_img_path_db)-1];
$arr_vkontakte_avatars[] = $img_name_db;
}

$avatar_arr = scandir("/var/www/core/vkontakte/avatars");
for ($x=0; $x < count($avatar_arr); $x++) {
if ( ($avatar_arr[$x] <> ".") && ($avatar_arr[$x] <> "..") && (!substr_count($avatar_arr[$x], "camera")) && (!substr_count($avatar_arr[$x], "question")) && (!substr_count($avatar_arr[$x], "deactivated")) ) {
if (!in_array($avatar_arr[$x], $arr_vkontakte_avatars)) {
unlink("/var/www/core/vkontakte/avatars/".$avatar_arr[$x]);
echo "Udalena. Ne byla v base, no byla na diske i ne privyazana: ".$avatar_arr[$x]."<br>";
//sleep(0.2);
  }
 }
}
###################################################################################################
# конец, просматриваем все аватарки с сервера и проверяем есть ли они в базе (непривязанные) ######
###################################################################################################
?>