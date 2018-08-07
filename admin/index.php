<?
# подключение файла с настройками конфигурации
require("../core/config.php");

# подключение файла с функциями
require("../core/function.php");

# подключение файла осуществляющего связь с базой данных
require("../core/connect.php");

# подключение файла верхней части дизайна страницы
require("../templates/".name_template_project."/admin/header.php");

# подключение файла нижней части дизайна страницы
require("../templates/".name_template_project."/admin/footer.php");
?>