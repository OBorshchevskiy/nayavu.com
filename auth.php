<?
# защита от флуда
include("antiddos/core/antiddos.php");

# подключение файла с настройками конфигурации
require(dirname(__FILE__)."/core/config.php");

# подключение файла с функциями
require(dirname(__FILE__)."/core/function.php");

# подключение файла осуществляющего связь с базой данных
require(dirname(__FILE__)."/core/connect.php");

# проверка поступивших данных (с текущего ли домена)
if (!http_referer_check()) {

# смотрим, авторизован ли уже пользователь
if (!auth_check_cookie("user", $_COOKIE['login_user'], $_COOKIE['password_user'])) {

# принимаем поступившие данные с логином и паролем
if (($_SERVER['REQUEST_METHOD']=='POST') && (isset($_POST['submit_auth']))) {

# преобразование логина и пароля в безопасный вид
$login=convert_post($_POST['login'], "0");
$password=convert_post($_POST['password'], "0");
# если было выбрано сохранять cookie
if (isset($_POST['save'])) {
$save=convert_post($_POST['save'], "0");
}

# пароль преобразуем в md5 код
$md5password=md5($password);
# смотрим, есть ли такой пользователь в таблице базы
$auth_user_query=mysql_query("select login from user where(login='$login' and password='$md5password')");
$num_of_such_register_user=mysql_num_rows($auth_user_query);

# если такой пользователь не регистрировался, то вывод ошибки
if (!$num_of_such_register_user) {
# заносим в массив сообщений текст ошибки
$result_message[]=array("message" => auth_error, "class" => bad);
} else {
# получаем логин пользователя из таблицы
$login_from_db=mysql_result($auth_user_query,0);

# если пользователя еще нет в базе ejabberd, зарегистрируем его
exec('sudo -u ejabberd /usr/sbin/ejabberdctl check-account '.$login_from_db.' nayavu.com 2>&1', $output, $status);
if ($status == 1) {
sleep(0.1);
exec('sudo -u ejabberd /usr/sbin/ejabberdctl register '.$login_from_db.' nayavu.com '.$password);
sleep(0.1);
exec("sudo -u ejabberd /usr/sbin/ejabberdctl add-rosteritem admin nayavu.com ".$login_from_db." nayavu.com ".$login_from_db."@nayavu.com Message none");
sleep(0.1);
exec("sudo -u ejabberd /usr/sbin/ejabberdctl add-rosteritem ".$login_from_db." nayavu.com admin nayavu.com admin@nayavu.com Message none");
}

# смотрим, сохранять cookie постоянно или до закрытия браузера
if (isset($save)) {
SetCookie("login_user", $login_from_db, time()+60*60*24*30, '/', "", 0, true);
SetCookie("password_user", $md5password, time()+60*60*24*30, '/', "", 0, true);
} else {
SetCookie("login_user", $login_from_db, time()+5400, '/', "", 0, true);
SetCookie("password_user", $md5password, time()+5400, '/', "", 0, true);
 }

# выдаем сообщение об успешной авторизации
$result_message[]=array("message" => auth_complete, "class" => good);
# авторизация пользователя
exec("/usr/bin/php5 /var/www/auth_in_forum.php $login_from_db $md5password $save", $retarr);
$ret_data_array=explode("---", $retarr[0]);
setcookie($ret_data_array[0], $ret_data_array[1], $ret_data_array[2], "/", "", 0, true);
}

# конец проверки поступивших данных
}

# подключение файла верхней части дизайна страницы
require(dirname(__FILE__)."/templates/".name_template_project."/index/header.php");

# если есть сообщения, то выводим их
if (isset($result_message)) {
foreach($result_message as $key => $value) {
view_message($value["message"], $value["class"]);
 }
}

?>
<p align="center"><a href="/<? echo $language_code ?>/index.html"><? echo auth_link_next ?></a></p><br><br><br>
<meta http-equiv="refresh" content="2; url=http://<? echo $url ?>">
<?

# подключение файла нижней части дизайна страницы
require(dirname(__FILE__)."/templates/".name_template_project."/index/footer.php");

# проверка auth_check_cookie
 }
# проверка referer
}
?>