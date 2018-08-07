<?
###################################################################################################
# начало, определение ip-адреса посетителя
###################################################################################################
function ip_what() {
if (getenv('HTTP_X_FORWARDED_FOR')) {
$ip=getenv('HTTP_X_FORWARDED_FOR');
} else {
$ip=getenv('REMOTE_ADDR');
 }
return $ip;
}
###################################################################################################
# конец, определение ip-адреса посетителя
###################################################################################################

# время обращения к скрипту, после которого будет(возможно) выдан бан
$config['time'] = 0.9;
# количество возможных нарушений времени обращения, такая возможность введена для более детально точного отделения пользователей от атакующих ботов
$config['countaban'] = 10;
# временна директория, должна существовать, и иметь права на запись
$config['directory'] = '/var/www/antiddos/blocked';
# маска для проверки, не стоит трогать
$config['checkmask'] = 'check_';
# маска для бана, не стоит трогать
$config['banmask'] = 'ban_';

# комманды после нарушения времени и кол-ва обращений. [IP] - IP Бота
$config['commands'][] = 'Netsh Advfirewall Firewall Add rule name=Block_[IP] dir=in action=block protocol=any remoteip=[IP]';

# доверенные ip
$config['white']['ip'][] = '***';

# доверенные useragent
$config['white']['useragent'][] = 'Googlebot';
$config['white']['useragent'][] = 'Yandex';
$config['white']['useragent'][] = 'StackRambler';
$config['white']['useragent'][] = 'Slurp';
$config['white']['useragent'][] = 'Yahoo! Slurp';
$config['white']['useragent'][] = 'MSNBot';

# cообщение для пользователя (предупреждение)
$config['message'] = file_get_contents("/var/www/antiddos/core/ddosmessage.html");

###################################################################################################
$ip = ip_what();
$useragent = $_SERVER['HTTP_USER_AGENT'];

# проверяем, в белом ли списке
if (@in_array($ip, $config['white']['ip']) || @in_array($useragent, $config['white']['useragent'])) {
$white = true;
}

# начало, если не белом списке
if (!$white) {
# начало, если этот ip уже есть в каталоге блокированных
if (file_exists($config['directory']."/".$config['checkmask'].$ip )) {
# время последнего обращения к файлу check_127.0.0.1
$time = filemtime($config['directory']."/".$config['checkmask'].$ip);
# ставим новую метку даты на файл check_127.0.0.1
$f=fopen($config['directory']."/".$config['checkmask'].$ip , 'w');
fclose($f);

# начало, если с момента последнего обращения прошло меньше $config['time'] секунд
if ($time >= time() - $config['time']) {
# начало, если существует файл с количеством банов
if (file_exists($config['directory']."/".$config['banmask'].$ip)) {
# открываем файл с количеством банов
$count = file_get_contents($config['directory']."/".$config['banmask'].$ip);
# если количество банов больше $config['countaban']
if ($count >= $config['countaban']) {
# выполняем команды по блокировке данного адреса через файерволл
for($i = 0; $i <= count($config['commands']) - 1; $i++) {
echo "<div align='center'><h3>Извините, ваш IP заблокирован!<br>Вы более 10 раз проигнорировали предупреждение о частом открытии страниц.<br>Обратитесь к администратору сайта для разблокировки.</h3></div>";
@system(str_replace("[IP]", $ip,$config['commands'][$i]));
 }
# если количество банов меньше $config['countaban']
} else {
# время последнего обращения ban_127.0.0.1
$time = filemtime($config['directory']."/".$config['banmask'].$ip);
# начало, если с момента последнего обращения прошло меньше $config['time'] секунд
if ($time >= time() - $config['time']) {
# увеличиваем счетчик банов
$count++;
# открываем файл ban_127.0.0.1 и увеличиваем счетчик банов
$f=fopen($config['directory']."/".$config['banmask'].$ip, 'w');
fwrite($f, $count);
fclose($f);
chmod($config['directory']."/".$config['banmask'].$ip, 0777);
# конец, если с момента последнего обращения прошло меньше $config['time'] секунд
} else {
# открываем файл ban_127.0.0.1 и ставим счетчик банов равный 1
$f=fopen($config['directory']."/".$config['banmask'].$ip, 'w');
fwrite($f, "1");
fclose($f);
chmod($config['directory']."/".$config['banmask'].$ip, 0777);
 }
}

# конец, если существует файл с количеством банов
} else {
# открываем файл ban_127.0.0.1 и ставим счетчик банов равный 0
$f=fopen($config['directory']."/".$config['banmask'].$ip, 'w');
fwrite($f, "0");
fclose($f);
chmod($config['directory']."/".$config['banmask'].$ip, 0777);
 }
# выдаем сообщение о том, что частые обновления недопустимы
exit($config['message']);
}
# конец, если с момента последнего обращения прошло меньше $config['time'] секунд

# конец, если этот ip уже есть в каталоге блокированных
} else {
# ставим метку даты на файл check_127.0.0.1
$f=fopen($config['directory']."/".$config['checkmask'].$ip, 'w');
fclose($f);
 }
}
# конец, если не белом списке
?>