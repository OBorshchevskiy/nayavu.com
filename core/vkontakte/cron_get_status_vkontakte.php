<?php
date_default_timezone_set("Europe/Moscow");
# подключение файла с настройками конфигурации
require("/var/www/core/config.php");
# подключение файла осуществляющего связь с базой данных
require("/var/www/core/connect.php");
# библиотека мультикурла
require("/var/www/core/vkontakte/RollingCurl.php");

###################################################################################################
# начало, функции #################################################################################
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

function utf8ToCode($utf8Char) {
    $utf8Char = (string) $utf8Char;
 
    if ("" == $utf8Char) {
        throw new InvalidArgumentException("Empty string is not valid character");
    }
 
    # [a, b, c, d, e, f]
    $bytes = array_map('ord', str_split(substr($utf8Char, 0, 6), 1));
 
    # a, [b, c, d, e, f]
    $first = array_shift($bytes);
 
    # 0-------
    if ($first <= 0x7F) {
        return $first;
    }
    # 110----- 10------
    elseif ($first >= 0xC0 && $first <= 0xDF) {
        $tail = 1;
    }
    # 1110---- 10------ 10------
    elseif ($first >= 0xE0 && $first <= 0xEF) {
        $tail = 2;
    }
    # 11110--- 10------ 10------ 10------
    elseif ($first >= 0xF0 && $first <= 0xF7) {
        $tail = 3;
    }
    # 111110-- 10------ 10------ 10------ 10------
    elseif ($first >= 0xF8 && $first <= 0xFB) {
        $tail = 4;
    }
    # 1111110- 10------ 10------ 10------ 10------ 10------
    elseif ($first >= 0xFC && $first <= 0xFD) {
        $tail = 5;
    }
    else {
        throw new InvalidArgumentException("First byte is not valid");
    }
 
    if (count($bytes) < $tail) {
        throw new InvalidArgumentException("Corrupted character: $tail tail bytes required");
    }
 
    $code = ($first & (0x3F >> $tail)) << ($tail * 6);
 
    $tails = array_slice($bytes, 0, $tail);
    foreach ($tails as $i => $byte) {
        $code |= ($byte & 0x3F) << (($tail - 1 - $i) * 6);
    }
 
    return $code;
}
###################################################################################################
# конец, функции #################################################################################
###################################################################################################

# токен получаем так: https://oauth.vk.com/authorize?client_id=2648257&scope=photos,status,offline&redirect_uri=blank.html&display=page&response_type=token
$access_token='5a0cc492e6bcc1b96c79f492b26b160a45a2f17d0ea53f6dae34f9140e367f120844e973128d7cf14dfcc';

# начало, функция обрабатывает возвращемые ответы
function request_callback($response, $info, $request) {

# начало, выводим для отладки
echo "<br><br>@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@<br>";
print_r($info);
echo "<br>@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@<br>";

echo "$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$<br>";
print_r($response);
echo "<br>$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$<br>";
# конец, выводим для отладки

# определяем дату и время
$time_log = mktime(date("H"), date("i"), date("s"), date("m"), date("j"), date("Y"));

$response = str_replace("\/", "/", $response);
$json = json_decode($response, 1);

# получаем все id из ответа сервера
$template="~uid\"\:(.*?)\}\)~";
preg_match_all($template, $info[url], $uid);

$n=0;
foreach ($json[response] as $key => $value) {

if (strlen($value[text]) >= 1) {

# находим последний статус пользователя
$last_status_sql = mysql_query("select text from vkontakte_user_status_log where id_vk_user=".$uid[1][$n]." order by time_log desc limit 1");
if (mysql_num_rows($last_status_sql)) {
$last_status = mysql_result($last_status_sql, 0);
} else {
$last_status_sql = "";
}

$utf8=0;
$text_status = $value[text];
$enc = mb_detect_encoding($text_status);
# если статус в кодировке utf-8, то преобразуем в соответствующие символы
if ($enc == "UTF-8") {
$utf8=1;
$text_status_convert="";
$chars_array = preg_split("//u", $text_status, -1, PREG_SPLIT_NO_EMPTY);
for ($a=0; $a < count($chars_array); $a++) {
$text_status_convert = $text_status_convert."|".utf8ToCode($chars_array[$a]);
 }
$text_status = $text_status_convert;
}

# начало, выводим для отладки
echo "<br>~1~".$text_status."~1~";
echo "<br>~2~";
if ($utf8 == 1) {
$text_code_array = explode("|", $text_status);
for ($a=0; $a < count($text_code_array); $a++) {
//echo codeToUtf8($text_code_array[$a]);
 }
} else {
//echo "not utf8";
}
echo "~2~<br>";
# конец, выводим для отладки

# если это аудиозапись
if ($value[audio][url]) {

//echo "mp3: ".$value[audio][url]."--- uid: ".$uid[1][$n]."--- text: ".$value[text]."--- last_status: ".$last_status."<br>";

if (strcasecmp($text_status, $last_status) <> 0) {
# добавляем данные в таблицу статусов
mysql_query("insert IGNORE into vkontakte_user_status_log (id_vk_user, text, audio, id_audio, time_log, utf8) values ('".$uid[1][$n]."', '".mysql_real_escape_string($text_status)."', '".$value[audio][url]."', '".$value[audio][aid]."', '".$time_log."', '".$utf8."')");
}

# обычный текст
} else {

//echo "uid: ".$uid[1][$n]."--- text: ".$value[text]."--- last_status: ".$last_status."<br>";
if (strcasecmp($text_status, $last_status) <> 0) {
# добавляем данные в таблицу статусов
mysql_query("insert IGNORE into vkontakte_user_status_log (id_vk_user, text, audio, id_audio, time_log, utf8) values ('".$uid[1][$n]."', '".mysql_real_escape_string($text_status)."', '', '0', '".$time_log."', '".$utf8."')");

###################################################################################################
### начало, добавляем данные в таблицу количества изменений в статусах ############################
###################################################################################################
$id_vk_user = $uid[1][$n];
# находим id пользователя
$id_m_user = mysql_result(mysql_query("select id_monitoring_user from vkontakte_user_to_monitoring where id_vk_user='$id_vk_user'"), 0);

$vk_user_in_profile = mysql_query("select id_registered_user from vkontakte_user_monitoring_in_profile where id_monitoring_user='$id_m_user'");
while ($get_vkontakte_user_in_profile = mysql_fetch_array($vk_user_in_profile)) {
$id_registered_user = "";
$id_registered_user = $get_vkontakte_user_in_profile["id_registered_user"];

$num_add_status_sql=mysql_query("select * from vkontakte_user_status_change where(id_vk_user='$id_vk_user' && id_registered_user='$id_registered_user')");

# если записей нет
if (!mysql_num_rows($num_add_status_sql)) {
mysql_query("insert vkontakte_user_status_change (id_vk_user, id_registered_user, add_status, num_view) values ('$id_vk_user', '$id_registered_user', '1', '0')");  
} else {
$num_add_status=mysql_fetch_assoc($num_add_status_sql);
$add_status = $num_add_status["add_status"];
$num_view = $num_add_status["num_view"];

if ($num_view == 0) {
$add_status_new = $add_status + 1;
mysql_query("update vkontakte_user_status_change set add_status='$add_status_new' where (id_vk_user='$id_vk_user' && id_registered_user='$id_registered_user')");
} else {
mysql_query("update vkontakte_user_status_change set add_status='1', num_view='0' where (id_vk_user='$id_vk_user' && id_registered_user='$id_registered_user')");
  }
 }
}
###################################################################################################
### конец, добавляем данные в таблицу количества изменений в статусах #############################
###################################################################################################
}

 }
}

$n++;
}

}
# конец, функция обрабатывает возвращемые ответы

# число строк в таблице
$num_rows_table = mysql_result(mysql_query("select count(*) from vkontakte_user_to_monitoring"), 0);
# сколько заходов выборки из таблицы (по 25 записей за раз)
$num_step = ceil($num_rows_table / 25);

# начало, выбираем информацию(id) из профилей пользователей за определенное количество шагов
$num_user_select = 0;
for ($n=0; $n < $num_step; $n++) {

$uid_st="";
# составляем запрос
$vkontakte_user_to_monitoring_data = mysql_query("select * from vkontakte_user_to_monitoring order by id_monitoring_user LIMIT ".$num_user_select.", 25");
while ($get_vkontakte_user_to_monitoring_data = mysql_fetch_array($vkontakte_user_to_monitoring_data)) {
$uid_st = $uid_st."API.status.get({\"uid\":".$get_vkontakte_user_to_monitoring_data["id_vk_user"]."}),";
}
# удаляем запятую
$uid_st = substr($uid_st, 0, -1);
# конечный запрос, ссылку, заносим в массив
$urls[] = "https://api.vk.com/method/execute?v=5.73&access_token=".$access_token."&code=return[".$uid_st."];";

# для следующего шага
$num_user_select = $num_user_select + 25;
}

# по 9 запросов(в каждом запросе 25 вложенных запросов) за раз (3 секунды пауза)
$count_urls = ceil(count($urls) / 9);

$num_sql_select=0;
for ($m=0; $m < $count_urls; $m++) {

$urls_cut = null;
$urls_cut = array_slice($urls, $num_sql_select, 9);

# мультипоточный вызов
$rc = new RollingCurl("request_callback");
$rc->window_size = 3;
foreach ($urls_cut as $url) {
$request = new RollingCurlRequest($url);
$rc->add($request);
}
$rc->execute();

# для следующего шага
$num_sql_select = $num_sql_select + 9;

sleep(3);
//echo "<p><b>##################################################</b></p>";
}

# удаляем заодно устаревшие ссылки, которым более суток(id аудиозаписи на всякий случай оставляем)
$get_time = mktime(date("H"), date("i"), date("s"), date("m"), date("j"), date("Y")) - 86400;
mysql_query("update vkontakte_user_status_log set audio='' where( (time_log < $get_time) && audio <> '')");
//echo "<br>";
printf("Udaleno ustarevshih ssylok: %d\n", mysql_affected_rows());
