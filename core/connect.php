<?
# подключение к серверу баз данных
if (@!$connectdb=mysql_connect(host_server_database, host_server_database_login, host_server_database_password)) {
# выводим ошибку в случае проблем подключения
echo connect_server_database_error;
exit;
}

# выбор базы данных
if (@!mysql_select_db(name_database_project)) {
# выводим ошибку в случае проблем выбора базы данных
echo connect_database_select_error;
exit;
}

# выбор кодировки базы по умолчанию
mysql_query("set character set utf8");
?>