<?
# начало, если данные пришли
if ($_SERVER['REQUEST_METHOD']=='POST') {

# запись в лог файл событий
function add_to_log($data) {
# запись в файл информации
$f=@fopen("result.txt", "a+") or die("error");
fputs($f, "$data");
fclose($f);
}

$data="";
# получения данных с формы
foreach($_POST as $key => $_POST['key']) {
# приведение к безопасному виду
$value=$_POST['key'];
$$key=$value;
$data = $data." | ".$key.": ".$value;
}

# подключение файла с настройками конфигурации
require("/var/www/core/config.php");
# подключение файла осуществляющего связь с базой данных
require("/var/www/core/connect.php");
# секретный ключ
$seckey = "***";
# вычисляем хэш
$hashstring = md5($spShopId.$spShopPaymentId.$spBalanceAmount.$spAmount.$spCurrency.$spCustomerEmail.$spPurpose.$spPaymentSystemId.$spPaymentSystemAmount.$spPaymentSystemPaymentId.$spEnrollDateTime.$seckey);

# если хэш неверный
if ($hashstring =! $spHashString) {
$data = $data.' | Bad HASH: '. $hashstring . ' - ' . $spHashString;
echo "Bad HASH";
add_to_log($data);
} else {
# обновляем баланс если все верно
if (!mysql_num_rows(mysql_query("select * from user where (id_registered_user='$spUserDataID' && id_transaction='$spPaymentId' && summ_transaction='$spBalanceAmount' && currency_transaction='$spCurrency')"))) {
# корректируем баланс если он null
$balance=mysql_result(mysql_query("select balance from user where (id_registered_user='$spUserDataID')"), 0);
if ($balance==NULL) {
$balance=0;
}
$balance = $balance + $spBalanceAmount;

mysql_query("update user set balance='$balance', id_transaction='$spPaymentId', summ_transaction='$spBalanceAmount', currency_transaction='$spCurrency' where (id_registered_user='$spUserDataID')");
$data = $data.' | Transaction complete';
echo "ok";
add_to_log($data);
 }
}

}
# конец, если данные пришли
?>