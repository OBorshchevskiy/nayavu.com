<?
# защита от флуда
include("antiddos/core/antiddos.php");

# подключение файла с настройками конфигурации
require("core/config.php");

# подключение файла с функциями
require("core/function.php");

# подключение файла осуществляющего связь с базой данных
require("core/connect.php");

# подключение файла верхней части дизайна страницы"
include("templates/".name_template_project."/index/header.php");

?>
<table border="0" cellpadding="5" cellspacing="0" align="center" width="60%">

  <tr>
    <td height="55" valign="top">&nbsp;<a href="/<? echo $language_code ?>/index.html"><? echo donate_main_page ?></a>&nbsp;&nbsp;→&nbsp;&nbsp;<font class="title"><? echo donate_workspace_title ?></font></td>
  </tr>

  <tr>
    <td>
    <b>&nbsp;&nbsp;Если Вам понравился наш проект и вы хотите помочь ему развиваться, то Вы можете выразить свою благодарность и оказать помощь следующими способами:</b><br>
    1) Разместить на вашем ресурсе ссылку на наш сайт<br>
    2) Сообщить друзьям и знакомым о нашем сайте<br>
    3) Выразить финансовую помощь следующими способами<br>
    </td>
  </tr>

  <tr>
    <td class="line_donate">
<?
if ($id_registered_user) {
# выбираем из базы данных email
$email_user=mysql_result(mysql_query("select email from user where (id_registered_user='$id_registered_user')"), 0);
  } else {
$email_user="unknown_donate@nayavu.com";
}
?>
<b><h3><a target="_blank" href="http://sprypay.ru"><img src="http://sprypay.ru/templates/users/images/sprypay.ru.png"></a> - система приема платежей на сайте</h3></b>
Система интегрирует под собой множество различных электронных систем интернет-оплаты (QIWI, Элекснет, Quickpay, Банковские карты и т.д.).<br><br>
<table border="0" cellspacing="0" cellpadding="10" align="center" width="60%" class="line_pay">
  <tr>
    <td valign="top" align="right"><nobr><img src="/templates/<? echo name_template_project ?>/index/images/payment.png"></nobr></td>
    <td valign="top" align="center">
<nobr>
<form action='http://sprypay.ru/sppi/' method='post'>
<input type='hidden' name='spShopId' value='7406'>
<input type='hidden' name='spShopPaymentId' value=''>
<input type='text' size="6" maxlength="6" name='spAmount' value='100'>
<select name="spCurrency">
<option selected value="rur"><? echo profile_select_rur ?></option>
<option value="usd"><? echo profile_select_usd ?></option>
<option value="eur"><? echo profile_select_eur ?></option>
<option value="uah"><? echo profile_select_uah ?></option>
</select>
<input type='hidden' name='spPurpose' value='<? echo donate_purpose_where ?>'>
<input type='hidden' name='spUserEmail' value='<? echo $email_user ?>'>
<input type='hidden' name='spUserDataID' value='1'>
<input type='submit' value='<? echo profile_button_pay ?>'>
</form>
</nobr>
    </td>
  </tr>
</table>
<br>
    </td>
  </tr>

  <tr>
    <td class="line_donate">
<b><h3><img src="/templates/<? echo name_template_project ?>/index/images/i_yandex.gif">&nbsp;&nbsp;Яндекс.Деньги</h3></b>
<b>Номер счета:</b>&nbsp;41001237963085
<br><br>
<table cellpadding="0" cellspacing="3" border="0">
  <tr>
    <td valign="top"><table cellpadding="0" cellspacing="0" border="0" style="font: 0.8em Arial, sans-serif"><tr><td width="116" height="77" style="border: 0; background:url(http://img.yandex.net/i/money/top-5rub-default.gif) repeat-y; text-align:center; padding: 0;" align="center" valign="bottom"><form style="margin: 0; padding: 0 0 2px;" action="https://money.yandex.ru/donate.xml" method="post"><input type="hidden" name="to" value="41001237963085"/><input type="hidden" name="s5" value="5rub"/><input type="submit" value="Дай пять"/></form></td></tr><tr><td width="116" height="38" style="font-size:13px; color:black;padding: 0; border: 0; background:url(http://img.yandex.net/i/money/bg-default.gif) repeat-y; text-align:center; padding: 5px 0;" align="center" valign="top"><b>всего 5 рублей!</b></td></tr><tr><td style="padding: 0; border:0;"><img src="http://img.yandex.net/i/money/bottom-default.gif" width="116" height="40" alt="" usemap="#button" border="0" /><map name="button"><area alt="Яндекс" coords="38,2,49,21" href="http://www.yandex.ru"><area alt="Яндекс. Деньги" coords="52,1,84,28" href="https://money.yandex.ru"><area alt="Хочу такую же кнопку" coords="17,29,100,40" href="https://money.yandex.ru/choose-banner.xml"></map></td></tr></table></td>
    <td valign="top" align="center">или сколько считаете нужным: <iframe allowtransparency="true" src="https://money.yandex.ru/embed/small.xml?uid=41001237963085&amp;button-text=01&amp;button-size=m&amp;button-color=orange&amp;targets=%D0%91%D0%BB%D0%B0%D0%B3%D0%BE%D0%B4%D0%B0%D1%80%D0%BD%D0%BE%D1%81%D1%82%D1%8C+%D0%B0%D0%B2%D1%82%D0%BE%D1%80%D1%83+%D0%BF%D1%80%D0%BE%D0%B5%D0%BA%D1%82%D0%B0&amp;default-sum=50" frameborder="0" height="42" scrolling="no" width="auto"></iframe></td>
  </tr>
</table>

    </td>
  <tr>
    <td class="line_donate">
<b><h3><img src="/templates/<? echo name_template_project ?>/index/images/web_money.gif">&nbsp;&nbsp;WebMoney</h3></b>
(RU) <b>(WMR):</b> R152043411624<br>
(US) <b>(WMZ):</b> Z949144780890<br>
(EU) <b>(WME):</b> E154027033842<br>
    </td>
  </tr>

</table>

<?

# подключение файла нижней части дизайна страницы"
include("templates/".name_template_project."/index/footer.php");
?>