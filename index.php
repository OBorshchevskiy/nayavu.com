<?
# защита от флуда
include("antiddos/core/antiddos.php");

# подключение файла с настройками конфигурации
require("core/config.php");

# подключение файла с функциями
require("core/function.php");

# /////////////////////////////////////////////////////////////////////////////////////////////////////
# /// начало / проверка, с формы текущего ли сайта поступили данные
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
require("core/connect.php");

# инициализирум механизм сесссий
session_start();

# вывод ошибки о том, что была попытка отправки повторных данных
if (isset($_GET['event'])) {
$result_message[]=array("message" => post_refresh_data, "class" => bad);
}

# начало, входящие данные
$news_table = "news";
# если администратор авторизован
if (mysql_num_rows(mysql_query("select * from admin where(login='$_COOKIE[login_admin]' and password='$_COOKIE[password_admin]')"))) {
# флаг того, что администратор авторизован
$userdata_user_level = 1;
} else {
# если пользователь авторизован
$user_data_query = mysql_query("select * from user where(login='$_COOKIE[login_user]' and password='$_COOKIE[password_user]')");
$num_of_user_data = mysql_num_rows($user_data_query);
if ($num_of_user_data) {
# получение данных пользователя
$user_data = mysql_fetch_assoc($user_data_query);
# id пользователя
$userdata_user_id = $user_data["id_registered_user"];
# флаг того, что пользователь авторизован
$userdata_user_level = 2;
# устанавливаем временной пояс
date_default_timezone_set($user_data["timezone"]);
 }
}

# \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
# \\\ начало \ получение данных для добавления комментария от пользователя
# \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\

# получение данных с формы для добавления комментария
if (($_SERVER['REQUEST_METHOD']=='POST') && ($userdata_user_level == 2) && (isset($_POST['add_comment_user_submit']))) {
# преобразуем в безопасный вид поступившие данные
$add_comment_text=convert_post($_POST['add_comment'], "2");
$add_comment_secretcode=convert_post($_POST['secretcode'], "0");
$add_comment_news_id=convert_post($_POST['news_id'], "0");

# md5 хэш данных post
$post_crypt_data=md5($add_comment_text);
# защита от повторного добавления данных через refresh для операций завершенных успешно
if (isset($_SESSION['add_comment_event'])) {
if (($_SESSION['add_comment_event'][md5_hash] == $post_crypt_data) && ((time()-$_SESSION['add_comment_event'][time_create])< 60)) {
Header("Location: http://".$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME']."?event=".time());
exit;
 }
}

# проверяем, верно ли ввел антиспам-код пользователь
if ($add_comment_secretcode<>$_SESSION["secret_number_add_comment"]) {
# заносим в массив сообщений ошибку
$result_message[]=array("message" => index_news_secret_code_bad, "class" => bad);
}

# смотрим заполнено ли поле с комментарием
if (empty($add_comment_text)) {
# выдаем ошибку о том, что поле пустое
$result_message[]=array("message" => index_news_comment_text_empty, "class" => bad);
} else {
# проверка поля комментария на правильность
if ((utf8_count_chars($add_comment_text)<4) || (utf8_count_chars($add_comment_text)>600)) {
# выдаем ошибку о недопустимом количестве символов
$result_message[]=array("message" => index_news_comment_text_bad_count_length, "class" => bad);
 }
}

# если ошибок в процессе проверки данных нет, то продолжаем операции с данными
if (!isset($result_message)) {
# вычисляем дату отправки комментария в формате день.месяц.год
$date_add_comment=mktime(date("H"), date("i"), date("s"), date("m"), date("j"), date("Y"));
# заносим в базу, данные комментарий
$query_add_comment="insert into comment_news(news_id, comment, author, add_date) values ('$add_comment_news_id','$add_comment_text','$userdata_user_id','$date_add_comment')";
mysql_query($query_add_comment);
# выдаем сообщение об успешном добавлении комментария
$result_message[]=array("message" => index_news_comment_add_complete, "class" => good);
$add_comment_text = '';
# конец result
 }
# конец POST
}

# /////////////////////////////////////////////////////////////////////////////////////////////////////
# /// конец / получение данных для добавления комментария от пользователя
# /////////////////////////////////////////////////////////////////////////////////////////////////////

# /////////////////////////////////////////////////////////////////////////////////////////////////////
# /// начало / получение данных для редактирования комментария от пользователя
# /////////////////////////////////////////////////////////////////////////////////////////////////////

# получение данных от пользователя пришедших с формы для редактирования
if (($_SERVER['REQUEST_METHOD']=='POST') && ($userdata_user_level == 2) && (isset($_POST['edit_comment_user_text_submit']))) {
# преобразуем в безопасный вид поступившие данные
$edit_comment_user_text=convert_post($_POST['edit_comment_user_text'], "2");
$edit_comment_user_text_id=convert_post($_POST['edit_comment_user_text_id'], "0");
$edit_comment_user_text_news_id=convert_post($_POST['edit_comment_user_text_news_id'], "0");
$edit_comment_secretcode=convert_post($_POST['secretcode'], "0");

# проверяем, верно ли ввел антиспам-код пользователь
if ($edit_comment_secretcode<>$_SESSION["secret_number_edit_comment"]) {
# заносим в массив сообщений ошибку
$result_message[]=array("message" => index_news_secret_code_bad, "class" => bad);
}

if (empty($edit_comment_user_text)) {
# выдаем ошибку о том, что поле пустое
$result_message[]=array("message" => index_news_comment_text_empty, "class" => bad);
} else {
# проверка поля комментария на правильность
if ((utf8_count_chars($edit_comment_user_text)<4) || (utf8_count_chars($edit_comment_user_text)>600)) {
# выдаем ошибку о недопустимом количестве символов
$result_message[]=array("message" => index_news_comment_text_bad_count_length, "class" => bad);
 } else {
# иначе если ошибок нет, составляем update для комментария
$update_data.="comment='$edit_comment_user_text',";
 }
}

# если ошибок в процессе проверки данных нет, то продолжаем далее
if (!isset($result_message)) {
# удаляем последнюю запятую в строке запроса
$update_data=substr($update_data, 0, (strlen($update_data)-1));
# обновляем данные комментария отредактированные пользователем
$update_query="update comment_news set $update_data where (id='$edit_comment_user_text_id' && news_id='$edit_comment_user_text_news_id' && author='$userdata_user_id')";
mysql_query($update_query);
# выдаем сообщение об успешном обновлении комментария
$result_message[]=array("message" => index_comment_update_complete, "class" => good);
 }
# конец POST
}

# \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
# \\\ конец \ получение данных для редактирования комментария от пользователя
# \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\

# /////////////////////////////////////////////////////////////////////////////////////////////////////
# /// начало / получение данных для редактирования комментария от администратора
# /////////////////////////////////////////////////////////////////////////////////////////////////////

# получение данных от администратора с формы для редактирования
if (($_SERVER['REQUEST_METHOD']=='POST') && ($userdata_user_level == 1) && (isset($_POST['edit_comment_admin_text_submit']))) {
# преобразуем в безопасный вид поступившие данные
$edit_comment_admin_text=convert_post($_POST['edit_comment_admin_text'], "2");
$edit_comment_admin_text_message=convert_post($_POST['edit_comment_admin_text_message'], "2");
$edit_comment_admin_text_id=convert_post($_POST['edit_comment_admin_text_id'], "0");
$edit_comment_admin_text_news_id=convert_post($_POST['edit_comment_admin_text_news_id'], "0");
$delete_comment=convert_post($_POST['delete_comment'], "0");
$delete_message=convert_post($_POST['delete_message'], "0");

# если установлен чекбокс для удаления комментария пользователя
if ($delete_comment=="on" && $edit_comment_admin_text) {
$update_data.="comment='delete',";
} else {
# смотрим заполнено ли поле с комментарием
if (empty($edit_comment_admin_text)) {
# выдаем ошибку о том, что поле пустое
$result_message[]=array("message" => index_news_comment_text_empty, "class" => bad);
} else {
# проверка поля комментария на правильность
if ((utf8_count_chars($edit_comment_admin_text)<4) || (utf8_count_chars($edit_comment_admin_text)>600)) {
# выдаем ошибку о недопустимом количестве символов
$result_message[]=array("message" => index_news_comment_text_bad_count_length, "class" => bad);
# иначе, если ошибок нет, составляем update для комментария
 } else {
$update_data.="comment='$edit_comment_admin_text',";
  }
 }
}

# если комментарий был удален, то обязательно должно быть пояснение от администратора
if ((($delete_comment=="on") && empty($edit_comment_admin_text_message)) || (($delete_comment=="on") && ($delete_message=="on"))) {
$result_message[]=array("message" => index_comment_admin_delete_error, "class" => bad);
} else {
# если была попытка удалить с пустым комментарием от администратора
if (($delete_message=="on") && !$edit_comment_admin_text_message) {
$result_message[]=array("message" => index_comment_admin_delete_empty_error, "class" => bad);
} else {
# если установлен чекбокс удаления сообщения администратора
if ($delete_message=="on") {
$update_data.="message='',";
} else {
# если заполнено поле сообщения пользователю от администратора, то проверяем его
if (!empty($edit_comment_admin_text_message)) {
# проверка поля комментария на правильность
if ((utf8_count_chars($edit_comment_admin_text_message)<4) || (utf8_count_chars($edit_comment_admin_text_message)>600)) {
# выдаем ошибку о недопустимом количестве символов
$result_message[]=array("message" => index_comment_admin_textarea_message_error, "class" => bad);
# иначе составляем update в базу с сообщением
 } else {
$update_data.="message='$edit_comment_admin_text_message',";
    }
   }
  }
 }
}

# если ошибок в процессе проверки данных нет, то продолжаем далее
if (!isset($result_message)) {
# удаляем последнюю запятую в строке запроса
$update_data=substr($update_data, 0, (strlen($update_data)-1));
# обновляем данные комментария пользователя, исправленные администратором
$update_query="update comment_news set $update_data where (id='$edit_comment_admin_text_id' && news_id='$edit_comment_admin_text_news_id')";
mysql_query($update_query);
# выдаем сообщение об успешном обновлении комментария
$result_message[]=array("message" => index_comment_update_complete, "class" => good);
 }
# конец POST
}

# \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
# \\\ конец \ получение данных для редактирования комментария от администратора
# \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\

# подключение файла верхней части дизайна страницы"
include("templates/".name_template_project."/index/header.php");

# если есть сообщения, то выводим их
if (isset($result_message)) {
foreach($result_message as $key => $value) {
if (view_message($value["message"], $value["class"])=="good") {
# заносим в сессию пометку о том, что действие выполнено
$_SESSION['add_comment_event'][md5_hash] = $post_crypt_data;
$_SESSION['add_comment_event'][time_create] = time();
  }
 }
}

# /////////////////////////////////////////////////////////////////////////////////////////////////////
# /// начало / составляем запрос для выборки статьи(данные берем из ссылки)
# /////////////////////////////////////////////////////////////////////////////////////////////////////

# определяем начало запроса
$query_data='';

# если была нажата ссылка для перехода к полному показу статьи
if (isset($_GET['view_news'])) {
# преобразуем запрос в безопасный вид
$view_news_num=convert_post($_GET['view_news'], "0");
# составляем запрос
$query_data = " where (id='$view_news_num')";
}

# /////////////////////////////////////////////////////////////////////////////////////////////////////
# /// конец / составляем запрос для выборки статьи(данные берем из ссылки)
# /////////////////////////////////////////////////////////////////////////////////////////////////////

# запрос на выбор статей, доступных для просмотра
$query_news_ready=mysql_query('select * from '.$news_table."_".$language_code.$query_data.' order by add_date');
$num_news_ready=mysql_num_rows($query_news_ready);
if (!$num_news_ready) {
# выводим сообщение о том, что таких статей не существует
view_message(index_news_not_exist, "bad");
} else {

# получаем данные статьи
$news_data=mysql_fetch_array($query_news_ready);

# /////////////////////////////////////////////////////////////////////////////////////////////////////
# /// начало / вывод полной версии статьи
# /////////////////////////////////////////////////////////////////////////////////////////////////////

# если необходимо показать полную статью, то далее
if (isset($view_news_num)) {
# заносим в массив данные статьи
$array_news_data[] = array("id"=>"$news_data[id]","title"=>"$news_data[title]","short_text"=>"$news_data[short_text]","big_text"=>"$news_data[big_text]","add_date"=>"$news_data[add_date]");

# заносим в массив комментарии статьи
$result_comment=mysql_query("select * from comment_news where news_id='".$array_news_data[0]['id']."' order by id desc");
while ($row_comment=mysql_fetch_array($result_comment)) {
$array_news_comment[]=array("id"=>"$row_comment[id]", "news_id"=>"$row_comment[news_id]", "comment"=>"$row_comment[comment]", "message"=>"$row_comment[message]", "author"=>"$row_comment[author]", "add_date"=>"$row_comment[add_date]");
}

# подсчитываем количество комментариев для статьи
$num_array_news_comment=count($array_news_comment);

# выводим полностью статью
?>
<table border="0" cellspacing="0" cellpadding="7" align="center" width="80%">
  <tr>
    <td>&nbsp;<a href="/index.html"><? echo index_news_main_page ?></a>&nbsp;&nbsp;→&nbsp;&nbsp;<font class="title"><? echo $array_news_data[0]["title"] ?></font>&nbsp;-&nbsp;<font class="font_small"><? echo date("d.m.Y, H:i",$array_news_data[0]["add_date"]) ?></font></td>
  </tr>
  <tr>
    <td valign="top">
<? echo $array_news_data[0]["short_text"]."<br>".$array_news_data[0]["big_text"] ?>
    </td>
  </tr>
</table>
<br>
<?
# если комментарии имеются, то вывод
if ($num_array_news_comment) {

# количество отображаемых комментариев на странице
$perpage_comment=10;

# вычисляем номер страницы
if (empty($_GET['page']) || ($_GET['page'] <= 0)) {
$page=1;
} else {
# cчитывание текущей страницы
$page=(int) $_GET['page'];
}

# количество страниц
$pages_count_comment=ceil($num_array_news_comment / $perpage_comment);
# если номер страницы оказался больше количества страниц
if ($page > $pages_count_comment) $page = $pages_count_comment;
$start_pos_comment = ($page - 1) * $perpage_comment;

# смотрим сколько выводить записей
if ($num_array_news_comment < $perpage_comment) {
$perpage_comment=$num_array_news_comment;
}
?>
<table border="0" cellspacing="0" cellpadding="5" align="center" width="60%">
<?
# увеличиваем порог вывода записей на $start_pos_comment
$perpage_comment = $start_pos_comment + $perpage_comment;
# смотрим какой диапазон для вывода захватывать
if ($perpage_comment > $num_array_news_comment) {
$perpage_comment=$num_array_news_comment;
}

# вывод информации из массива
for ($start_pos_comment; $start_pos_comment < $perpage_comment; $start_pos_comment++) {
?>
  <tr>
    <td class="line_comment">
<?
# выводим имя пользователя добавившего комментарий
echo "<p><b>".mysql_result(mysql_query("select login from user where (id_registered_user='".$array_news_comment[$start_pos_comment]['author']."')"), 0)."</b>";
echo "<font class=\"font_small\">,&nbsp;".date("d.m.Y, H:i",$array_news_comment[$start_pos_comment]["add_date"]) ?></font></p>
    </td>
  </tr>
  <tr>
    <td>
<?
# выводим комментарий
if ($array_news_comment[$start_pos_comment]["comment"]=="delete") {
echo "<i>".index_comment_user_text_delete."</i>";
} else {
echo $array_news_comment[$start_pos_comment]["comment"];
}
# если имеется сообщение от администратора, то выводим его
if ($array_news_comment[$start_pos_comment]["message"]) {
?>
<div>
<b><? echo index_comment_admin_text_title ?></b>
<? echo $array_news_comment[$start_pos_comment]["message"] ?>
</div>
<?
}
# проверяем доступ к редактированию комментариев
if ($userdata_user_level == 1) {
?>
    </td>
  </tr>
  <tr>
    <td align="right">
<font style='cursor: pointer' onClick='vis_BlockEditCommentAdmin<? echo $array_news_comment[$start_pos_comment]["id"] ?>()'><? echo index_comment_edit ?></font>

<!-- начало / показ|скрытие блока формы, для отпраки сообщения администратором к комментарию пользователя -->
<script type="text/javascript">
function vis_BlockEditCommentAdmin<? echo $array_news_comment[$start_pos_comment]['id'] ?>() {
if (document.getElementById('vis_BlockEditCommentAdmin<? echo $array_news_comment[$start_pos_comment]["id"] ?>').style.display=='none') {
document.getElementById('vis_BlockEditCommentAdmin<? echo $array_news_comment[$start_pos_comment]["id"] ?>').style.display='block';
} else {
document.getElementById('vis_BlockEditCommentAdmin<? echo $array_news_comment[$start_pos_comment]["id"] ?>').style.display='none';
 }
}
function NotDeleteMessage() {
if (document.getElementById('delete_comment').checked==true) {
document.getElementById('vis_BlockNotDeleteMessage').style.display='none';
  } else {
document.getElementById('vis_BlockNotDeleteMessage').style.display='block';
 }
}
function NotDeleteComment() {
document.getElementById('vis_BlockNotDeleteComment').style.display='none';
}
</script>
<!-- конец / показ|скрытие блока формы, для отпраки сообщения администратором к комментарию пользователя -->

<!-- начало / редактирование комментария для администратора -->
<div align="left" id="vis_BlockEditCommentAdmin<? echo $array_news_comment[$start_pos_comment]['id'] ?>" style="display:none">
<form action="/<? echo $language_code?>/index/view_news/<? echo $view_news_num ?>/page/<? echo $page ?>" name="edit_comment_admin_text<? echo $array_news_comment[$start_pos_comment]['id'] ?>" method="post">
<input type="hidden" name="edit_comment_admin_text_id" value="<? echo $array_news_comment[$start_pos_comment]['id'] ?>">
<input type="hidden" name="edit_comment_admin_text_news_id" value="<? echo $array_news_comment[$start_pos_comment]['news_id'] ?>">
<b><? echo index_comment_textarea_edit ?>:</b><br>
<textarea cols="80" rows="3" id="edit_comment_admin_text" name="edit_comment_admin_text">
<?
# смотрим какие данные заносить в edit_comment_admin_text
if ($edit_comment_admin_text_message == $array_news_comment[$start_pos_comment]['comment']) {
echo $edit_comment_admin_text;
} else {
if ($array_news_comment[$start_pos_comment]["comment"]<>"delete") {
echo $array_news_comment[$start_pos_comment]["comment"];
  } else {
$hide_checkbox = true;
 }
}
?>
</textarea>
<br>
<div id="vis_BlockNotDeleteComment"><input type="checkbox" name="delete_comment" id="delete_comment" onclick="NotDeleteMessage()">&nbsp;<? echo index_link_and_button_delete ?></div>
<?
if ($hide_checkbox) {
?>
<script type="text/javascript">
NotDeleteComment()
</script>
<?
}
?>
<br><br>
<b><? echo index_comment_admin_textarea_message ?>:</b><br>
<textarea cols="80" rows="2" id="edit_comment_admin_text_message" name="edit_comment_admin_text_message">
<?
# смотрим какие данные заносить в edit_comment_admin_text_message
if ($edit_comment_admin_text_message == $array_news_comment[$start_pos_comment]['comment']) {
echo $edit_comment_admin_text_message;
} else {
echo $array_news_comment[$start_pos_comment]["message"];
}
?>
</textarea>
<div id="vis_BlockNotDeleteMessage"><input type="checkbox" id="delete_message" name="delete_message">&nbsp;<? echo index_link_and_button_delete ?></div>
<br><br><div align="center"><input type="submit" name="edit_comment_admin_text_submit" value="<? echo index_button_send ?>"></div>
</form>
</div>
<!-- конец / редактирование комментария для администратора -->

<!-- начало / редактирование комментария для пользователя -->
<?
} elseif (($array_news_comment[$start_pos_comment]["author"]==$userdata_user_id) && ($array_news_comment[$start_pos_comment]["comment"]<>"delete")) {
# регистрируем сессию и заносим данные нового секретного кода
$_SESSION["secret_number_edit_comment"]=rand(100000,999999);
?>
    </td>
  </tr>
  <tr>
    <td align="right">
<font style='cursor: pointer' onClick='vis_BlockEditCommentUser<? echo $array_news_comment[$start_pos_comment]["id"] ?>()'>| <? echo index_comment_edit ?> |</font>

<!-- начало / показ|скрытие блока формы, для отпраки комментария от пользователя -->
<script type="text/javascript">
function vis_BlockEditCommentUser<? echo $array_news_comment[$start_pos_comment]['id'] ?>() {
if (document.getElementById('vis_BlockEditCommentUser<? echo $array_news_comment[$start_pos_comment]["id"] ?>').style.display=='none') {
document.getElementById('vis_BlockEditCommentUser<? echo $array_news_comment[$start_pos_comment]["id"] ?>').style.display='block';
} else {
document.getElementById('vis_BlockEditCommentUser<? echo $array_news_comment[$start_pos_comment]["id"] ?>').style.display='none';
 }
}
</script>
<!-- конец / показ|скрытие блока формы, для отпраки комментария от пользователя -->

<div align="left" id="vis_BlockEditCommentUser<? echo $array_news_comment[$start_pos_comment]['id'] ?>" style="display:none">
<form action="/<? echo $language_code?>/index/view_news/<? echo $view_news_num ?>/page/<? echo $page ?>" name="edit_comment_user_text<? echo $array_news_comment[$start_pos_comment]['id'] ?>" method="post">
<input type="hidden" name="edit_comment_user_text_id" value="<? echo $array_news_comment[$start_pos_comment]['id'] ?>">
<input type="hidden" name="edit_comment_user_text_news_id" value="<? echo $array_news_comment[$start_pos_comment]['news_id'] ?>">
<b><? echo index_comment_textarea_edit ?>:</b><br>
<textarea cols="80" rows="3" id="edit_comment_user_text" name="edit_comment_user_text">
<?
# смотрим какие данные для edit_comment_user_text выводить
if ($edit_comment_user_text_id == $array_news_comment[$start_pos_comment]['id']) {
echo $edit_comment_user_text;
} else {
echo $array_news_comment[$start_pos_comment]["comment"];
}
?>
</textarea><br><br>
<b><? echo index_news_secret_code ?></b><br>
<img src="/core/imagecode.php?secret_number_name=<? echo secret_number_edit_comment ?>"><br><br>
<input type="text" size="6" maxlength="6" name="secretcode">
<div align="center"><input type="submit" name="edit_comment_user_text_submit" value="<? echo index_button_send ?>"></div>
</form>
</div>
<?
}
?>
<!-- конец / редактирование комментария для пользователя -->
    </td>
  </tr>
<?
# конец цикла foreach
  }
# смотрим, выводить ли переходы по страницам
if ($num_array_news_comment > 10) {
# составление ЧПУ ссылки
$chpu_link = "/".$language_code."/index/view_news/".$view_news_num."/";
# вызов функции, для вывода ссылок на экран
?>
<tr>
  <td height="40" align="center" colspan="4"><? page_link($page, "page", $num_array_news_comment, $pages_count_comment, 10, $chpu_link); ?></td>
</tr>
</table>
<?
 } else {
?>
</table>
<?
 }
# конец проверки есть ли комментарии
}

# /////////////////////////////////////////////////////////////////////////////////////////////////////
# /// начало / вывод формы для добавления комментария
# /////////////////////////////////////////////////////////////////////////////////////////////////////

# выводим форму для пользователя
if ($userdata_user_level == 2) {
# регистрируем сессию и заносим данные нового секретного кода
$_SESSION["secret_number_add_comment"]=rand(100000,999999);
if (!$pages_count_comment) {
$pages_count_comment = 1;
}
?>
<!-- начало / подключение js позволяющего вставлять тэги -->
<script language="javascript" type="text/javascript" src="/core/js/inserttags.js"></script>
<!-- конец / подключение js позволяющего вставлять тэги -->
<table border="0" cellspacing="0" cellpadding="7" align="center" width="10%">
<form action="/<? echo $language_code?>/index/view_news/<? echo $view_news_num ?>/page/<? echo $pages_count_comment ?>" name="add_comment_user" method="post">
<input type="hidden" name="news_id" value="<? echo $array_news_data[0][id] ?>">
  <tr>
    <td colspan="2">
<p align="center">
<a title="<? echo tag_b; ?>" href="javascript:inserttags('<b>','</b>')"><img src="/templates/<? echo name_template_project ?>/index/images/tags/b.gif"></a>
<a title="<? echo tag_i; ?>" href="javascript:inserttags('<i>','</i>')"><img src="/templates/<? echo name_template_project ?>/index/images/tags/i.gif"></a>
<a title="<? echo tag_u; ?>" href="javascript:inserttags('<u>','</u>')"><img src="/templates/<? echo name_template_project ?>/index/images/tags/u.gif"></a>
<a title="<? echo tag_ul; ?>" href="javascript:inserttags('<ul>','</ul>')"><img src="/templates/<? echo name_template_project ?>/index/images/tags/ul.gif"></a>
<a title="<? echo tag_li; ?>" href="javascript:inserttags('<li>','</li>')"><img src="/templates/<? echo name_template_project ?>/index/images/tags/li.gif"></a>
<a title="<? echo tag_a_href; ?>" href="javascript:inserttags('<a%20href=&quot;link&quot;>','</a>')"><img src="/templates/<? echo name_template_project ?>/index/images/tags/url.gif"></a>
</p>
<textarea cols="50" rows="12" id="add_comment" name="add_comment" onfocus="infocus('add_comment');"><? echo $add_comment_text ?></textarea>
    </td>
  </tr>
  <tr>
    <td width="1%">
<b><? echo index_news_secret_code ?></b><br>
<img src="/core/imagecode.php?secret_number_name=<? echo secret_number_add_comment ?>">
    </td>
    <td valign="bottom">
<input type="text" size="6" maxlength="6" name="secretcode">
    </td>
  </tr>
  <tr>
    <td align="center" colspan="2" height="70">
<input type="submit" name="add_comment_user_submit" value="<? echo index_button_send ?>">
    </td>
  </tr>
</form>
</table>
<?
# конец проверки, выводить ли форму добавления комментария
 }

# /////////////////////////////////////////////////////////////////////////////////////////////////////
# /// конец / вывод формы для добавления комментария
# /////////////////////////////////////////////////////////////////////////////////////////////////////

# /////////////////////////////////////////////////////////////////////////////////////////////////////
# /// конец / вывод полной версии статьи
# /////////////////////////////////////////////////////////////////////////////////////////////////////

} else {

# /////////////////////////////////////////////////////////////////////////////////////////////////////
# /// начало / вывод начальной страницы со списком статей
# /////////////////////////////////////////////////////////////////////////////////////////////////////

?>
<table border="0" cellspacing="0" cellpadding="7" align="center" width="80%">
<?
# количество отображаемых заголовков статей на странице
$perpage_news=10;
# вычисляем номер страницы
if (empty($_GET['page']) || ($_GET['page'] <= 0)) {
$page=1;
} else {
# cчитывание текущей страницы
$page=(int) $_GET['page'];
}

# количество страниц
$pages_count_news=ceil($num_news_ready / $perpage_news);
# если номер страницы оказался больше количества страниц
if ($page > $pages_count_news) $page = $pages_count_news;
$start_pos_news = ($page - 1) * $perpage_news;

# смотрим сколько выводить записей
if ($num_news_ready<$perpage_news) {
$perpage_news=$num_news_ready;
}

# помещение информации о статьях в многомерный массив
$result=mysql_query("select * from ".$news_table."_".$language_code.$query_data." order by add_date desc");
while ($row = mysql_fetch_array($result)) {

# выбираем из базы данных количество комментариев
$num_comment_query=mysql_query("select * from comment_news where news_id='".$row['id']."'");
$num_comment_news=mysql_num_rows($num_comment_query);

# заносим в многомерный массив данные статьи
$array_data_news[] = array("id"=>"$row[id]","title"=>"$row[title]","short_text"=>"$row[short_text]","big_text"=>"$row[big_text]","add_date"=>"$row[add_date]", "num_comment_news"=>"$num_comment_news");
}

# увеличиваем порог вывода записей на $start_pos_comment
$perpage_news = $start_pos_news + $perpage_news;
# смотрим какой диапазон для вывода захватывать
if ($perpage_news > $num_news_ready) {
$perpage_news=$num_news_ready;
}

# вывод информации из массива
for ($start_pos_news; $start_pos_news < $perpage_news; $start_pos_news++) {
?>
<tr>
  <td>
<table border="0" cellspacing="0" cellpadding="0" width="100%">
  <tr>
    <td>&nbsp;<a class="title" title="<? echo index_news_view_full ?>" href="/<? echo $language_code ?>/index/view_news/<? echo $array_data_news[$start_pos_news][id] ?>"><? echo $array_data_news[$start_pos_news]["title"] ?></a><nobr><? echo "<font class=\"font_small\">&nbsp;-&nbsp;".date("d.m.Y, H:i",$array_data_news[$start_pos_news]["add_date"])."</font>"; ?></nobr></td>
  </tr>
</table>
  </td>
</tr>
<tr>
  <td><? echo $array_data_news[$start_pos_news]["short_text"] ?></td>
</tr>
<tr>
  <td align="right">
<table border="0" cellspacing="0" cellpadding="0" width="100%">
  <tr>
    <td align="left">
      <a title="<? echo index_news_view_full ?>" href="/<? echo $language_code ?>/index/view_news/<? echo $array_data_news[$start_pos_news][id] ?>"><? echo index_news_view_more ?>&nbsp;→</a>
    </td>
    <td align="right">
      <a title="<? echo index_news_view_full ?>" href="/<? echo $language_code ?>/index/view_news/<? echo $array_data_news[$start_pos_news][id] ?>"><? echo index_news_view_comment ?>
<?
echo "&nbsp;(".$array_data_news[$start_pos_news]["num_comment_news"].")";
?>
    </a>
    </td>
  </tr>
</table>
<br>
 </td>
</tr>
<?
}
# смотрим, выводить ли переходы по страницам
if (($num_news_ready)>10) {
# составление ЧПУ ссылки
$chpu_link = "/".$language_code."/index/";
# вызов функции, для вывода ссылок на экран
?>
<tr>
  <td height="40" align="center" colspan="4"><? page_link($page, "index", $num_news_ready, $pages_count_news, 10, $chpu_link); ?></td>
</tr>
</table>
<?
 } else {
?>
</table>
<?
 }
# конец проверки есть ли статьи
}

# /////////////////////////////////////////////////////////////////////////////////////////////////////
# /// конец / вывод начальной страницы со списком статей
# /////////////////////////////////////////////////////////////////////////////////////////////////////

# конец проверки на существование статей
}

# подключение файла нижней части дизайна страницы"
include("templates/".name_template_project."/index/footer.php");
?>