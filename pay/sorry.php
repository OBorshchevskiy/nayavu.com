<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8"></head>
<body>
<?
# начало, если данные пришли
if ($_SERVER['REQUEST_METHOD']=='POST') {

$data="";
# получения данных с формы
foreach($_POST as $key => $_POST['key']) {
# приведение к безопасному виду
$value=$_POST['key'];
$$key=$value;
$data = $data." | ".$key.": ".$value;
}

if ($data) {
# запись в файл информации
$f=@fopen("sorry.txt", "a+") or die("error");
fputs($f, "$data");
fclose($f);
}

}
?>
</body>
</html>