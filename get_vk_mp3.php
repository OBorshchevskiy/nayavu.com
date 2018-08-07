<?
# подключение файла с настройками конфигурации
require("/var/www/core/config.php");
# подключение файла осуществляющего связь с базой данных
require("/var/www/core/connect.php");
# подключение файла с функциями
require("/var/www/core/function.php");

$file = convert_post($_GET['url'], 0);

if (!mysql_num_rows(mysql_query("select * from vkontakte_user_status_log where audio='$file'"))) {
echo "Bad URL!";
} else {

/*
Set Headers
Get total size of file
Then loop through the total size incrementing a chunck size
*/
function download($file, $chunks){
set_time_limit(0);
header('Content-Description: File Transfer');
header('Content-Type: audio/mpeg');
header('Content-disposition: attachment; filename='.basename($file));
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Expires: 0');
header('Pragma: public');
$size = get_size($file);
header('Content-Length: '.$size);
header('Accept-Ranges: bytes');

$i = 0;
while ($i <= $size) {
//Output the chunk
get_chunk($file,(($i==0)?$i:$i+1),((($i+$chunks)>$size)?$size:$i+$chunks));
$i = ($i+$chunks);
 }
}

//Callback function for CURLOPT_WRITEFUNCTION, This is what prints the chunk
function chunk($ch, $str) {
print($str);
return strlen($str);
}

//Function to get a range of bytes from the remote file
function get_chunk($file,$start,$end){
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $file);
curl_setopt($ch, CURLOPT_RANGE, $start.'-'.$end);
curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
curl_setopt($ch, CURLOPT_WRITEFUNCTION, 'chunk');
$result = curl_exec($ch);
curl_close($ch);
}

//Get total size of file
function get_size($url){
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_NOBODY, true);
curl_exec($ch);
$size = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
return intval($size);
}
download($file, 20000);
 }
?>