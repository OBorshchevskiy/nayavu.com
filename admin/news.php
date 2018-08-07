<?
# подключение файла с настройками конфигурации
require("../core/config.php");

# подключение файла с функциями
require("../core/function.php");

# подключение файла осуществляющего связь с базой данных
require("../core/connect.php");

# поиск пользователя в таблице
$user_data_query=mysql_query("select * from admin where(login='$_COOKIE[login_admin]' and password='$_COOKIE[password_admin]')");
$num_of_user_data=mysql_num_rows($user_data_query);

# начало, если пользователь авторизован, то далее
if ($num_of_user_data) {

# начало, входящие данные
$news_table = "news";
$file_name = "news";
# конец, входящие данные

# подключение файла верхней части дизайна страницы
require("../templates/".name_template_project."/admin/header.php");

###################################################################################################
# начало, получение данных с формы для обработки
###################################################################################################
if ($_SERVER['REQUEST_METHOD']=='POST') {

# обрабатываем полученные данные с формы
foreach($_POST as $key => $_POST['key']) {
# приведение к безопасному и правильному виду
$value=convert_post($_POST['key'], "0");
$$key=$value;
}

if (isset($_POST['news_add_short_text'])) {
$news_add_short_text = convert_post($_POST['news_add_short_text'], "1");
}
if (isset($_POST['news_add_big_text'])) {
$news_add_big_text = convert_post($_POST['news_add_big_text'], "1");
}

if (isset($_POST['news_edit_short_text'])) {
$news_edit_short_text = convert_post($_POST['news_edit_short_text'], "1");
}
if (isset($_POST['news_edit_big_text'])) {
$news_edit_big_text = convert_post($_POST['news_edit_big_text'], "1");
}

###################################################################################################
# начало, если данные поступили для добавления новости
###################################################################################################
if (isset($submit_news_add)) {

# если заголовок новости не введен, то вывод ошибки
if ($news_add_title) {
if (!preg_match("/^(?:&quot;|[a-zA-Za-яА-Я])(?:&quot;|&amp;|[0-9a-zA-Za-яА-Я!?—)(:\-\.\,\040])*$/", $news_add_title) || utf8_count_chars($news_add_title)<3 || utf8_count_chars($news_add_title)>100) {
$result_message[]=array("message" => news_title_bad_name, "class" => bad);
 }
}

# смотрим заполнено ли поле начала новости
if (empty($news_add_short_text)) {
# выдаем ошибку о том, что поле пустое
$result_message[]=array("message" => news_short_text_empty, "class" => bad);
} else {
# проверка поля начала новости на правильность
if ((utf8_count_chars($news_add_short_text)<10) || (utf8_count_chars($news_add_short_text)>150000)) {
# выдаем ошибку о недопустимом количестве символов
$result_message[]=array("message" => news_short_text_bad_count_length, "class" => bad);
} else {
# смотрим, нет ли слишком длинных слов для русских 50, латинских 100 символов.
if (preg_match("|(\w{100,})|",$news_add_short_text,$matches)) {
# выдаем ошибку о недопустимой длине слова
$result_message[]=array("message" => news_short_text_bad_length_word, "class" => bad);
  }
 }
}

# смотрим заполнено ли поле продолжение новости
if (!empty($news_add_big_text)) {
# проверка поля продолжение новости на правильность
if ((utf8_count_chars($news_add_big_text)<50) || (utf8_count_chars($news_add_big_text)>150000)) {
# выдаем ошибку о недопустимом количестве символов
$result_message[]=array("message" => news_big_text_bad_count_length, "class" => bad);
} else {
# смотрим, нет ли слишком длинных слов для русских 20, латинских 40 символов.
if (preg_match("|(\w{40,})|",$news_add_big_text,$matches)) {
# выдаем ошибку о недопустимой длине слова
$result_message[]=array("message" => news_big_text_bad_length_word, "class" => bad);
  }
 }
}

# если ошибок в процессе проверки данных нет, то добавляем новость
if (!isset($result_message)) {

# вычисляем дату отправки новости
$date_add_news=mktime(date("H"), date("i"), date("s"), date("m"), date("j"), date("Y"));

mysql_query("insert into ".$news_table."_".$language_code."(title, short_text, big_text, add_date) values ('$news_add_title', '$news_add_short_text', '$news_add_big_text', '$date_add_news')");
# выдаем сообщение об успешном добавлении статьи
$result_message[]=array("message" => news_add_complete, "class" => good);
 }
}
###################################################################################################
# конец, если данные поступили для добавления новости
###################################################################################################

###################################################################################################
# начало, если данные поступили для редактирования новости
###################################################################################################
if (isset($submit_news_edit)) {

# если заголовок новости не введен, то вывод ошибки
if ($news_edit_title) {
if (!preg_match("/^(?:&quot;|[a-zA-Za-яА-Я])(?:&quot;|&amp;|[0-9a-zA-Za-яА-Я!?—)(:\-\.\,\040])*$/", $news_edit_title) || utf8_count_chars($news_edit_title)<3 || utf8_count_chars($news_edit_title)>100) {
$result_message[]=array("message" => news_title_bad_name, "class" => bad);
 }
}

# смотрим заполнено ли поле начала новости
if (empty($news_edit_short_text)) {
# выдаем ошибку о том, что поле пустое
$result_message[]=array("message" => news_short_text_empty, "class" => bad);
} else {
# проверка поля начала новости на правильность
if ((utf8_count_chars($news_edit_short_text)<10) || (utf8_count_chars($news_edit_short_text)>150000)) {
# выдаем ошибку о недопустимом количестве символов
$result_message[]=array("message" => news_short_text_bad_count_length, "class" => bad);
} else {
# смотрим, нет ли слишком длинных слов для русских 50, латинских 100 символов.
if (preg_match("|(\w{100,})|",$news_edit_short_text,$matches)) {
# выдаем ошибку о недопустимой длине слова
$result_message[]=array("message" => news_short_text_bad_length_word, "class" => bad);
  }
 }
}

# смотрим заполнено ли поле продолжение новости
if (!empty($news_edit_big_text)) {
# проверка поля продолжение новости на правильность
if ((utf8_count_chars($news_edit_big_text)<50) || (utf8_count_chars($news_edit_big_text)>150000)) {
# выдаем ошибку о недопустимом количестве символов
$result_message[]=array("message" => news_big_text_bad_count_length, "class" => bad);
} else {
# смотрим, нет ли слишком длинных слов для русских 20, латинских 40 символов.
if (preg_match("|(\w{40,})|",$news_edit_big_text,$matches)) {
# выдаем ошибку о недопустимой длине слова
$result_message[]=array("message" => news_big_text_bad_length_word, "class" => bad);
  }
 }
}

# если ошибок в процессе проверки данных нет, то добавляем новость
if (!isset($result_message)) {

mysql_query("update ".$news_table."_".$language_code." set title='$news_edit_title', short_text='$news_edit_short_text', big_text='$news_edit_big_text' where (id='$edit_news_id')");
# выдаем сообщение об успешном добавлении статьи
$result_message[]=array("message" => news_edit_complete, "class" => good);
 }
}
###################################################################################################
# конец, если данные поступили для редактирования новости
###################################################################################################

}
###################################################################################################
# конец, получение данных с формы для обработки
###################################################################################################

# если есть сообщения, то выводим их
if (isset($result_message)) {
foreach($result_message as $key => $value) {
view_message($value["message"], $value["class"]);
 }
}

?>

<table border="0" cellpadding="3" cellspacing="0" align="center" width="90%">

  <tr>
    <td class="title">&nbsp;&nbsp;<? echo news_workspace_title ?></td>
  </tr>

  <tr>
    <td height="20">&nbsp;</td>
  </tr>

  <tr>
    <td>

<?
###################################################################################################
# начало, редактирование новости
###################################################################################################
if (isset($_GET['edit'])) {
# преобразуем в безопасный и правильный вид номер новости
if (!isset($news_num)) {
$news_num=convert_post($_GET['edit'], "0");
}
# проверка, существует ли такая новость
$select_news_query=mysql_query("select * from ".$news_table."_".$language_code." where id='$news_num'");
$num_news=mysql_num_rows($select_news_query);
# если такой новости не существует, то ошибка
if (!$num_news) {
# вывод сообщения о том, что такой новости не существует
view_message(news_edit_not_exist, "bad");
} else {
# получение данных новости
$news_data=mysql_fetch_assoc($select_news_query);
# форма вывода новости для редактирования
?>

<form action="/<? echo $language_code ?>/admin/<? echo $file_name ?>/edit/<? echo $news_num ?>" method="post">
<input type="hidden" name="edit_news_id" value="<? echo $news_data[id]; ?>">

<table border="0" cellspacing="5" cellpadding="4" align="center" class="data_box" width="100%">

  <tr>
    <td colspan="2"><nobr><b><? echo news_edit_panel ?></b></td>
      <td>
      <table border="0" cellspacing="0" cellpadding="4" align="right">
        <tr>
          <td class="close_box" width="10"><b><a href="/<? echo $language_code ?>/admin/<? echo $file_name ?>.html"><? echo X; ?></a></b></td>
        </tr>
      </table>
       </nobr>
    </td>
  </tr>

  <tr class="data_box">
    <td align="right"><nobr><? echo news_title ?></nobr></td>
    <td><input type="text" size="50" maxlength="100" name="news_edit_title" value="<? if (isset($news_edit_title)) { echo $news_edit_title; } else { echo $news_data[title]; } ?>"></td>
    <td><font class="notice"><? echo news_title_notice ?></font></td>
  </tr>

  <tr class="data_box">
    <td align="right"><nobr><? echo news_short_text ?></nobr></td>
    <td valign="top"><div align="right"><font class="notice">*</font></div><textarea cols="90" rows="10" onfocus="infocus('news_edit_short_text')" name="news_edit_short_text" id="news_edit_short_text"><? if (isset($news_edit_short_text)) { echo $news_edit_short_text; } else { echo $news_data["short_text"]; } ?></textarea></td>
    <td valign="top"><font class="notice"><? echo news_short_text_notice ?></font></td>
  </tr>

  <tr class="data_box">
    <td align="right"><nobr><? echo news_big_text ?></nobr></td>
    <td valign="top"><textarea cols="90" rows="10" name="news_edit_big_text" onfocus="infocus('news_edit_big_text')" id="news_edit_big_text"><? if (isset($news_edit_big_text)) { echo $news_edit_big_text; } else { echo $news_data["big_text"]; } ?></textarea></td>
    <td valign="top"><font class="notice"><? echo news_big_text_notice ?></font></td>
  </tr>

  <tr>
    <td colspan="3" align="center"><input type="submit" name="submit_news_edit" value="<? echo news_link_and_button_edit ?>"></td>
  </tr>
  <tr>
    <td colspan="3" align="center"><font class="important">*</font> - <font class="notice"><? echo news_field_important ?></font></td>
  </tr>
</table>
</form>
<br>
<?
 }
}
###################################################################################################
# конец, редактирование новости
###################################################################################################

###################################################################################################
# начало, удаление новости
###################################################################################################
if (isset($_GET['delete'])) {
# преобразуем в безопасный и правильный вид номер новости
$news_num=convert_post($_GET['delete'], "0");
# смотрим, есть ли уже данные в таблице для данной новости
$select_news_query=mysql_query("select id from ".$news_table."_".$language_code." where (id='$news_num')");
$num_news_delete_edit=mysql_num_rows($select_news_query);
# если такой новости не существует, то ошибка
if (!$num_news_delete_edit) {
# вывод сообщения о том, что такой новости не существует
view_message(news_delete_not_exist, "bad");
} else {
# удаляем выбранную новость
mysql_query("delete from ".$news_table."_".$language_code." where (id='$news_num')");
# вывод сообщения о том, что новость успешно удалена
view_message(news_delete_complete, "good");
 }
}
###################################################################################################
# конец, удаление новости
###################################################################################################

?>

<!-- ##### начало, вывод всех добавленных новостей ##### -->
<table border="0" cellspacing="1" cellpadding="7" align="center" class="data_box" width="100%">

  <tr>
    <td colspan="4"><b><? echo news_panel_list; ?></b></td>
  </tr>

<?
# проверяем, есть ли добавленные новости
if (count_rows_table($news_table."_".$language_code)) {

# выводим список новостей
$news_data=mysql_query("select * from ".$news_table."_".$language_code." order by id desc");
while ($get_news_data=@mysql_fetch_array($news_data)) {
?>
  <tr valign="top" class="data_box">
    <td>&nbsp;&nbsp;&nbsp;<? echo $get_news_data['title'] ?></td>
    <td>
<?
$part_of_text = convert_post(mb_substr(convert_post($get_news_data['short_text'],0), 0, 300, utf8), 0);
echo $part_of_text." ...";
?>
    </td>
    <td width="1%"><a href="/<? echo $language_code ?>/admin/<? echo $file_name ?>/edit/<? echo $get_news_data['id'] ?>"><b><i><? echo news_link_and_button_edit ?></b></i></a></td>
    <td width="1%"><a href="/<? echo $language_code ?>/admin/<? echo $file_name ?>/delete/<? echo $get_news_data['id'] ?>"><b><i><? echo news_link_and_button_delete ?></b></i></a></td>
  </tr>
<?
 }
} else {
?>
  <tr class="data_box">
    <td colspan="4" align="center"><? echo news_not_exist ?></td>
  </tr>
<?
}
?>
</table>
<!-- ##### конец, вывод всех добавленных новостей ##### -->

    </td>
  </tr>
</table>
<br><br>

<?
if (!$news_num) {
?>
<!-- ##### начало, форма добавления новостей ##### -->
<form action="/<? echo $language_code ?>/admin/<? echo $file_name ?>.html" method="post">
<table border="0" cellspacing="5" cellpadding="4" align="center" class="data_box" width="90%">

  <tr>
    <td colspan="3"><nobr><b><? echo news_add_panel ?></b></nobr></td>
  </tr>

  <tr class="data_box">
    <td align="right"><nobr><? echo news_title ?></nobr></td>
    <td><input type="text" size="50" maxlength="100" name="news_add_title" value="<? if (isset($news_add_title)) { echo $news_add_title; } ?>"></td>
    <td><font class="notice"><? echo news_title_notice ?></font></td>
  </tr>

  <tr class="data_box">
    <td align="right"><nobr><? echo news_short_text ?></nobr></td>
    <td valign="top"><div align="right"><font class="notice">*</font></div><textarea cols="90" rows="10" onfocus="infocus('news_add_short_text')" name="news_add_short_text" id="news_add_short_text"><? if (isset($news_add_short_text)) { echo $news_add_short_text; } ?></textarea></td>
    <td valign="top"><font class="notice"><? echo news_short_text_notice ?></font></td>
  </tr>

  <tr class="data_box">
    <td align="right"><nobr><? echo news_big_text ?></nobr></td>
    <td valign="top"><textarea cols="90" rows="10" onfocus="infocus('news_add_big_text')" name="news_add_big_text" id="news_add_big_text"><? if (isset($news_add_big_text)) { echo $news_add_big_text; } ?></textarea></td>
    <td valign="top"><font class="notice"><? echo news_big_text_notice ?></font></td>
  </tr>

  <tr>
    <td colspan="3" align="center"><input type="submit" name="submit_news_add" value="<? echo news_button_add ?>"></td>
  </tr>
  <tr>
    <td colspan="3" align="center"><font class="important">*</font> - <font class="notice"><? echo news_field_important ?></font></td>
  </tr>
</table>
</form>
<!-- ##### конец, форма добавления новостей ##### -->

<?
}

# подключение файла нижней части дизайна страницы
require("../templates/".name_template_project."/admin/footer.php");

# проверка auth_check_cookie
}
?>