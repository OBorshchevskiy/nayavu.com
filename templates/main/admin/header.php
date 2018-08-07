<?
# запрет кэширования
Header("Expires: Mon, 1 Jul 2000 05:00:00 GMT");
Header("Cache-Control: no-cache, must-revalidate");
Header("Pragma: no-cache");
Header("Last-Modified: ".gmdate("D, d M Y H:i:s")."GMT");

# если была нажата ссылка с кодом места
if (isset($_GET['language'])) {
# преобразуем в безопасный вид поступившие данные
$language_code=convert_post($_GET['language'], 0);
} else {
# язык интерфейса по умолчанию
$language_code=language_interface_project;
}

# если была нажата ссылка "выход"
if (isset($_GET['exit'])) {
if ($_GET['exit']=='yes') {
# удаление cookie логина и пароля
SetCookie ("login_admin","0", time()-1, "/");
SetCookie ("password_admin","0", time()-1, "/");
# выход и перенаправление на главную страницу
header('Location: /'.$language_code.'/admin/index.html');
 }
}

# выбираем из базы данных title страницы
$title_query=mysql_query("select data from config_$language_code where (name='title')");
$title=mysql_result($title_query,0);

# выбираем из базы данных description страницы
$description_query=mysql_query("select data from config_$language_code where (name='description')");
$description=mysql_result($description_query,0);

# выбираем из базы данных keywords страницы
$keywords_query=mysql_query("select data from config_$language_code where (name='keywords')");
$keywords=mysql_result($keywords_query,0);

# выбираем из базы данных url сайта
$url_query=mysql_query("select data from config_$language_code where (name='url')");
$url=mysql_result($url_query,0);

# подключаем языковой файл
require("../core/language.$language_code.php");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title><? echo $title ?></title>
<meta name="description" content="<? echo $description ?>">
<meta name="keywords" content="<? echo $keywords ?>">
<link href="/templates/<? echo name_template_project ?>/admin/style/style.css" rel="stylesheet">
</head>
<body>

<!-- начало, блок вывода языковых флагов  -->
<table border="0" cellpadding="2" cellspacing="0" align="right">
  <tr>
<?
$language_search = language_search("../core/");
for ($n=0; $n < count($language_search); $n++) {
?>
    <td>
<a href="/<? echo $language_search[$n] ?>/admin">
<img src="/templates/<? echo name_template_project ?>/all/images/<? echo $language_search[$n] ?>.png">
</a>
    </td>
<?
}
?>
  </tr>
</table>
<br><br>
<!-- конец, блок вывода языковых флагов -->

<?
if ($not_view_workspace) {
?>
<table border="0" cellspacing="0" cellpadding="3" align="center">
  <tr>
    <td>
<?
} else {
if (!auth_check_cookie("admin", $_COOKIE['login_admin'], $_COOKIE['password_admin']) && !$num_of_such_register_user) {
?>
<!-- начало, вывод формы для авторизации в админ. панели -->
<form action="/admin/auth.html?language=<? echo $language_code ?>" method="post">
<table border="0" cellspacing="0" cellpadding="7" align="center" class="data_box">
  <tr class="data_box">
    <td><b><? echo header_login ?></b></td>
    <td width="30"><input type="text" size="12" maxlength="30" name="login"></td>
  </tr>
  <tr class="data_box">
    <td><b><? echo header_password ?></b></td>
    <td><input type="password" size="12" maxlength="30" name="password"></td>
  </tr>
  <tr class="data_box">
    <td colspan="2" align="center"><input type="submit" name="submit_auth" value="<? echo header_enter_button ?>"></td>
  </tr>
  <tr class="data_box">
    <td colspan="2"><input type="checkbox" name="save"><? echo header_remember; ?></td>
  </tr>
  <tr class="data_box">
    <td colspan="2"><a href="/<? echo $language_code ?>/admin/lostpass.html"><? echo header_password_restore ?></a></td>
  </tr>
</table>
</form>
<!-- конец, вывод формы для авторизации в админ. панели -->
<table border="0" cellspacing="0" cellpadding="3" align="center">
  <tr>
    <td>
<?
} else {
?>
<table border="0" cellpadding="10" cellspacing="1" width="100%">

  <tr>
    <td width="15%"><a href="/<? echo $language_code ?>/admin/index.html" class="title"><nobr><? echo header_admin_link_cp ?>&nbsp;...&nbsp;&raquo;</nobr></a></td>
    <td>

<!-- начало, показываем меню профиля администратора -->
<table border="0" cellspacing="0" cellpadding="3" align="right">
  <tr>
    <td>→</td>
    <td>
<?
if (isset($login_from_db)) {
echo $login_from_db;
} else {
echo $_COOKIE['login_admin'];
}
?>
&nbsp;<a href="/<? echo $language_code ?>/admin/index/exit/yes">(<? echo header_logout ?>)</a>
    </td>
  </tr>
  <tr>
    <td>→</td>
    <td><a href="/<? echo $language_code ?>/admin/profile.html"><? echo header_link_profile ?></a></td>
  </tr>
</table>
<!-- конец, показываем меню профиля администратора -->

    </td>
  </tr>

  <tr>
    <td valign="top">

<!-- начало, блоки панели управления администратора -->
<table width="100%" border="0" cellpadding="7" cellspacing="4">
 <tr>
    <td class="data_box" align="center"><nobr><? echo header_admin_menu_main ?></nobr></td>
 </tr>
 <tr class="data_box">
    <td><a href="/<? echo $language_code ?>/admin/news.html"><? echo header_admin_menu_news_link ?></a></td>
 </tr>
 <tr>
    <td class="data_box" align="center"><nobr><b>«&nbsp;<? echo header_admin_menu_vkontakte ?>&nbsp;»</b></nobr></td>
 </tr>
 <tr class="data_box">
    <td><a href="/<? echo $language_code ?>/admin/index.html"><? echo none ?></a></td>
 </tr>
</table>
<!-- конец, блоки панели управления администратора -->

    </td>
    <td valign="top">
<?
 }
}