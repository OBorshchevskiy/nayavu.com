<?
# подключение файла с настройками конфигурации
require("../core/config.php");

# подключение файла с функциями
require("../core/function.php");

# подключение файла осуществляющего связь с базой данных
require("../core/connect.php");

# проверка поступивших данных (с текущего ли домена)
if (!http_referer_check()) {

# смотрим, авторизован ли уже пользователь
if (!auth_check_cookie("admin", $_COOKIE['login_admin'], $_COOKIE['password_admin'])) {

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
$auth_user_query=mysql_query("select login from admin where(login='$login' and password='$md5password')");
$num_of_such_register_user=mysql_num_rows($auth_user_query);

# если такой пользователь не регистрировался, то вывод ошибки
if (!$num_of_such_register_user) {
# заносим в массив сообщений текст ошибки
$result_message[]=array("message" => auth_error, "class" => bad);
} else {
# получаем логин пользователя из таблицы
$login_from_db=mysql_result($auth_user_query,0);
# смотрим, сохранять cookie постоянно или до закрытия браузера
if (isset($save)) {
SetCookie("login_admin", $login_from_db, time()+60*60*24*30, '/', "", 0, true);
SetCookie("password_admin", $md5password, time()+60*60*24*30, '/', "", 0, true);
} else {
SetCookie("login_admin", $login_from_db, time()+5400, '/', "", 0, true);
SetCookie("password_admin", $md5password, time()+5400, '/', "", 0, true);
 }
# выдаем сообщение об успешной авторизации
$result_message[]=array("message" => auth_complete, "class" => good);
}

# конец проверки поступивших данных
}

# подключение файла верхней части дизайна страницы
require("../templates/".name_template_project."/admin/header.php");

# если есть сообщения, то выводим их
if (isset($result_message)) {
foreach($result_message as $key => $value) {
view_message($value["message"], $value["class"]);
 }
}

?>
<p align="center"><a href="/<? echo $language_code ?>/admin/index.html"><? echo auth_link_next ?></a></p>
<?

# подключение файла нижней части дизайна страницы
require("../templates/".name_template_project."/admin/footer.php");

# проверка auth_check_cookie
 }
# проверка referer
}
?>