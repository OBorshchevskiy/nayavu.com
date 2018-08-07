<?
###################################################################################################
# вывод оформленного текста сообщения
###################################################################################################
function view_message($message_name, $message_style) {
# проверяем определена ли константа
if (!defined("$message_name")) {
if ($message_style=="good") {
echo "<div class=\"message_good\">".$message_name."</div>";
} elseif ($message_style=="bad") {
echo "<div class=\"message_bad\">".$message_name."</div>";
 }
} else {
if ($message_style=="good") {
echo "<div class=\"message_good\">".constant($message_name)."</div>";
} elseif ($message_style=="bad") {
echo "<div class=\"message_bad\">".constant($message_name)."</div>";
 }
}
return $message_style;
}

###################################################################################################
# проверка поступления данных с формы (с нашего ли домена)
###################################################################################################
function http_referer_check() {
if ((!isset($_SERVER['HTTP_REFERER'])) || (!strpos($_SERVER['HTTP_REFERER'], $_SERVER['HTTP_HOST']))) {
if (!strpos($_SERVER['HTTP_REFERER'], "social-networld.ru")) {
return true;
  }
 }
}

###################################################################################################
# функция проверки существования сookie логина и пароля
###################################################################################################
function auth_check_cookie($table, $login, $password) {
# проверяем действительно ли верны введенные данные логина и пароля
if (isset($login) && isset($password)) {
if (mysql_num_rows(mysql_query("select login, password from $table where (login='$login' and password='$password')"))) {
return true;
  }
 }
}

###################################################################################################
# приведение данных к максимально безопасному и правильному виду
###################################################################################################
function convert_post($data, $level) {
# удаление лишних пробелов по левому и правому краю текста
$data=trim($data);
# удаление опасных апострофов
$data=preg_replace("/'/","",$data);
# удаление \
$data=stripslashes($data);
switch ($level) {
# удаление всех тэгов
case "0":
$data=strip_tags($data);
break;
case "1":
# удаление запрещенных тэгов
$data=strip_tags($data, '<p><br><a><div><font><span><i><strong><b><em><ul><strike><u><li><ol><img><tbody><table><tr><td><address><pre><h1><h2><h3><h4><h5><h6>');
break;
case "2":
# удаление запрещенных тэгов
$data=strip_tags($data, '<b><i><u><ul><li><a><br>');
break;
 }
return $data;
}

###################################################################################################
# функция генерации случайного числа состоящего из 12 символов
###################################################################################################
function gen_rand_str($len='12', $chars='1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz') {
$chars_n=strlen($chars);
for ($i=0; $i<$len; $i++) {
@$str.=$chars[mt_rand(0,$chars_n)];
 }
return $str;
}

###################################################################################################
# подсчет числа символов в utf-8 тексте
###################################################################################################
function utf8_count_chars($str) {
preg_match_all('~[\x09\x0A\x0D\x20-\x7E]
| [\xC2-\xDF][\x80-\xBF]
|  \xE0[\xA0-\xBF][\x80-\xBF]
| [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}
|  \xED[\x80-\x9F][\x80-\xBF]
|  \xF0[\x90-\xBF][\x80-\xBF]{2}
| [\xF1-\xF3][\x80-\xBF]{3}
|  \xF4[\x80-\x8F][\x80-\xBF]{2}
~xs', $str, $m);
return count($m[0]);
}

###################################################################################################
# функция занесения в массив языковых переводов
###################################################################################################
function language_search($dir) {
# считываем файлы из папки с фото пользователя
if ($filedir=opendir($dir)) {
while ($file=readdir($filedir)) {
if (substr_count($file, "language")) {
# число символов в названии файла
$sizeword=utf8_count_chars($file);
$langpart=substr($file,$sizeword-6,2);
$langkeywords[]=$langpart;
  }
 }
}
# закрываем папку
@closedir($filedir);
return @$langkeywords;
}

###################################################################################################
# функция получение количества записей в таблице
###################################################################################################
function count_rows_table($table) {
return mysql_result(mysql_query("select count(*) from ".$table),0);
}

###################################################################################################
# определение ip-адреса посетителя
###################################################################################################
function ip_detect() {
if (getenv('HTTP_X_FORWARDED_FOR')) {
$ip=getenv('HTTP_X_FORWARDED_FOR');
} else {
$ip=getenv('REMOTE_ADDR');
 }
return $ip;
}

###################################################################################################
# количество повторений элемента в массиве
###################################################################################################
function num_element_in_mass($element, $mass_elements) {
$num = 0;
for ($i=0; $i < count($mass_elements); $i++) {
if ($mass_elements[$i] == $element) {
$num++;
  }
 }
return $num;
}

###################################################################################################
# онлайн или оффлайн пользователь
###################################################################################################
function is_online($id_vk_user, $id_monitoring_user) {
# флаг в онлайне или нет
$online_now=false;
# получаем номер пользователя
if ($id_vk_user) {
$id_monitoring_user=mysql_result(mysql_query("select id_monitoring_user from vkontakte_user_to_monitoring where id_vk_user='$id_vk_user'"), 0);
}
# определяем дату и время
$date_and_time_now=mktime(date("H"), date("i"), date("s"), date("m"), date("j"), date("Y"));
# определяем когда последний раз был в онлайн данный пользователь
$time_in_online=mysql_result(mysql_query("select time_last_online from vkontakte_user_to_monitoring where id_monitoring_user='$id_monitoring_user'"), 0);
if ($time_in_online) {
# если последние 2 минуты был в онлайн
if (($date_and_time_now-$time_in_online) <= 170) {
$online_now=true;
  }
 }
return $online_now;
}

###################################################################################################
# получение ссылки на аватарку с нашего сервера
###################################################################################################
function get_avatar($avatar_vk_user) {
# находим имя аватарки
$content_img_path=explode("/", $avatar_vk_user);
$img_name=$content_img_path[count($content_img_path)-1];
$saved_avatars = "<img src='/core/vkontakte/avatars/$img_name'>";
return $saved_avatars;
}

###################################################################################################
### проверка, забанен ли пользователь
###################################################################################################
function is_ban_user($id_vk_user) {
$vkontakte_bad_user_data = mysql_query("select id_vk_user from vkontakte_bad_user");
while ($get_vkontakte_bad_user_data = mysql_fetch_array($vkontakte_bad_user_data)) {
  if ($get_vkontakte_bad_user_data["id_vk_user"] == $id_vk_user) {
   return true;
  }
 }
return false;
}

###################################################################################################
### конвертируем секунды в день-месяц-год-часы-минуты-секунды
###################################################################################################
function Sec2Time($time) {
if (is_numeric($time)) {

$value = array("year" => 0, "day" => 0, "hour" => 0, "min" => 0, "sec" => 0);

if ($time >= 31556926) {
$value["year"] = floor($time/31556926);
$time = ($time%31556926);
}

if ($time >= 86400) {
$value["day"] = floor($time/86400);
$time = ($time%86400);
}

if ($time >= 3600) {
$value["hour"] = floor($time/3600);
$time = ($time%3600);
}

if ($time >= 60) {
$value["min"] = floor($time/60);
$time = ($time%60);
}

$value["sec"] = floor($time);
return (array) $value;
  } else {
return (bool) FALSE;
 }
}

###################################################################################################
### Перевод кода в utf-8 текст ####################################################################
###################################################################################################
function codeToUtf8($code) {
    $code = (int) $code;
 
    if ($code < 0) {
        throw new RangeException("Negative value was passed");
    }
    # 0-------
    elseif ($code <= 0x7F) {
        return chr($code);
    }
    # 110----- 10------
    elseif ($code <= 0x7FF) {
        return chr($code >> 6 | 0xC0)
            . chr($code & 0x3F | 0x80)
        ;
    }
    # 1110---- 10------ 10------
    elseif ($code <= 0xFFFF) {
        return chr($code >> 12 | 0xE0)
            . chr($code >> 6 & 0x3F | 0x80)
            . chr($code & 0x3F | 0x80)
        ;
    }
    # 11110--- 10------ 10------ 10------
    elseif ($code <= 0x1FFFFF) {
        return chr($code >> 18 | 0xF0)
            . chr($code >> 12 & 0x3F | 0x80)
            . chr($code >> 6 & 0x3F | 0x80)
            . chr($code & 0x3F | 0x80)
        ;
    }
    # 111110-- 10------ 10------ 10------ 10------
    elseif ($code <= 0x3FFFFFF) {
        return chr($code >> 24 | 0xF8)
            . chr($code >> 18 & 0x3F | 0x80)
            . chr($code >> 12 & 0x3F | 0x80)
            . chr($code >> 6 & 0x3F | 0x80)
            . chr($code & 0x3F | 0x80)
        ;
    }
    # 1111110- 10------ 10------ 10------ 10------ 10------
    elseif ($code <= 0x7FFFFFFF) {
        return chr($code >> 30 | 0xFC)
            . chr($code >> 24 & 0x3F | 0x80)
            . chr($code >> 18 & 0x3F | 0x80)
            . chr($code >> 12 & 0x3F | 0x80)
            . chr($code >> 6 & 0x3F | 0x80)
            . chr($code & 0x3F | 0x80)
        ;
    }
    else {
        throw new RangeException("Invalid character code");
    }
}

###################################################################################################
### Составления навигации по страницам ############################################################
###################################################################################################
function page_link($page, $page_name, $count, $pages_count, $show_link, $chpu_link) {
# $show_link - это количество отображаемых ссылок
# нагляднее будет, когда это число будет парное
# если страница всего одна, то вообще ничего не выводим
if ($pages_count == 1) return false;
# разделитель ссылок; например, вставить "|" между ссылками
$sperator = ' ';
$begin = $page - intval($show_link / 2);
unset($show_dots);
# сам постраничный вывод
# если количество отображаемых ссылок больше количества страниц
if ($pages_count <= $show_link + 1) $show_dots = 'no';
# вывод ссылки на первую страницу
if (($begin > 2) && !isset($show_dots) && ($pages_count - $show_link > 2)) {
echo '<a href='.$chpu_link.$page_name.'/1> |< </a> ';
}

for ($j = 0; $j < $page; $j++) {
# если страница рядом с концом, то выводить ссылки перед идущих для того, чтобы количество ссылок было постоянным
if (($begin + $show_link - $j > $pages_count) && ($pages_count-$show_link + $j > 0)) {
# номер страницы
$page_link = $pages_count - $show_link + $j;
# если три точки не выводились, то вывести
if (!isset($show_dots) && ($pages_count-$show_link > 1)) {
echo ' <a href='.$chpu_link.$page_name.'/'.($page_link - 1).'><b>...</b></a> ';
# задаем любое значение для того, чтобы больше не выводить в начале "..." (три точки)
$show_dots = "no";
}
# вывод ссылки
echo ' <a href='.$chpu_link.$page_name.'/'.$page_link.'>'.$page_link.'</a> '.$sperator;
 } else continue;
}
# основный цикл вывода ссылок
for ($j = 0; $j <= $show_link; $j++)
{
# номер ссылки
$i = $begin + $j;
# если страница рядом с началом, то увеличить цикл для того,
# чтобы количество ссылок было постоянным
if ($i < 1) {
$show_link++;
continue;
}
# подобное находится в верхнем цикле
if (!isset($show_dots) && $begin > 1) {
echo ' <a href='.$chpu_link.$page_name.'/'.($i-1).'><b>...</b></a> ';
$show_dots = "no";
}
# номер ссылки перевалил за возможное количество страниц
if ($i > $pages_count) break;
if ($i == $page) {
echo ' <a><b>'.$i.'</b></a> ';
 } else {
echo ' <a href='.$chpu_link.$page_name.'/'.$i.'>'.$i.'</a> ';
}
# если номер ссылки не равен количеству страниц и это не последняя ссылка
if (($i != $pages_count) && ($j != $show_link)) echo $sperator;
# вывод "..." в конце
if (($j == $show_link) && ($i < $pages_count)) {
echo ' <a href='.$chpu_link.$page_name.'/'.($i+1).'><b>...</b></a> ';
 }
}
# вывод ссылки на последнюю страницу
if ($begin + $show_link + 1 < $pages_count) {
echo ' <a href='.$chpu_link.$page_name.'/'.$pages_count.'> >| </a>';
 }
return true;
}
?>