<?
sleep(15);
date_default_timezone_set("Europe/Moscow");
# считываем файлы из папки c check_ и ban_
if ($filedir=opendir("/var/www/antiddos/blocked")) {
while ($file=readdir($filedir)) {

if (($file<>".") && ($file<>"..") && strpos($file, "check_")) {

# считываем время последнего изменения файла
$time = filemtime("/var/www/antiddos/blocked/".$file);

# начало, если с момента последнего обращения больше 12 часов
if ($time + 43200 <= time()) {
# удаляем файлы
unlink("/var/www/antiddos/blocked/".$file);
$ban_file="";
$ban_file="/var/www/antiddos/blocked/".str_replace("check_", "ban_", $file);
if (file_exists($ban_file)) {
unlink($ban_file);
    }
   }
  }
 }
}
# закрываем папку
closedir($filedir);
?>