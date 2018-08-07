<?
if (isset($argv[1]) && isset($argv[2])) {
###################################################################################################
### начало, меняем пароль на форуме ###############################################################
###################################################################################################
define('FORUM_ROOT', 'forum/');
require_once FORUM_ROOT.'config.php';
require_once FORUM_ROOT.'include/functions.php';
require_once FORUM_ROOT.'include/dblayer/common_db.php';

$username = $argv[1];
$password = $argv[2];

# генерация случайного числа состоящего из 12 символов
function gen_rand_word($len='12', $chars='1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz') {
$chars_n=strlen($chars);
for ($i=0; $i<$len; $i++) {
@$str.=$chars[mt_rand(0,$chars_n)];
 }
return $str;
}

$salt = gen_rand_word();
$password_hash = forum_hash($password, $salt);

# обновляем пароль
$query = array(
				'UPDATE'	=> 'users',
				'SET'		=> 'password=\''.$password_hash.'\', salt=\''.$salt.'\'',
			    'WHERE'		=> 'username=\''.$username.'\' AND id>1'
              );

$forum_db->query_build($query) or error(__FILE__, __LINE__);
###################################################################################################
### конец, меняем пароль на форуме ################################################################
###################################################################################################
}