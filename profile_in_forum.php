<?
$login = $argv[1];
$password = $argv[2];
$timezone_int = $argv[3];

if (isset($argv[1])) {
###################################################################################################
### начало, обновляем пароль на форуме ############################################################
###################################################################################################
define('FORUM_ROOT', '/var/www/forum/');
require FORUM_ROOT.'include/common.php';
require FORUM_ROOT.'lang/'.$forum_user['language'].'/profile.php';

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

# получаем данные пользователя
$query = array(
				'SELECT'	=> 'u.id',
				'FROM'		=> 'users AS u',
			    'WHERE'		=> 'u.username=\''.$login.'\' AND u.id>1'
			);
$result = $forum_db->query_build($query) or error(__FILE__, __LINE__);
if ($forum_db->num_rows($result)) {
list($id) = $forum_db->fetch_row($result);
}

$query=null;
if ($password <> "not") {
# обновляем пароль и временную зону
$query = array(
				'UPDATE'	=> 'users',
				'SET'		=> 'password=\''.$password_hash.'\', salt=\''.$salt.'\', timezone=\''.$timezone_int.'\'',
			    'WHERE'		=> 'username=\''.$login.'\' AND id>1'
              );

} else {
# обновляем временную зону
$query = array(
				'UPDATE'	=> 'users',
				'SET'		=> 'timezone=\''.$timezone_int.'\'',
			    'WHERE'		=> 'username=\''.$login.'\' AND id>1'
              );

}

$forum_db->query_build($query) or error(__FILE__, __LINE__);

$expire = time()+60*60*24*30;

echo $cookie_name."---".base64_encode($id.'|'.$password_hash.'|'.$expire.'|'.sha1($salt.$password_hash.forum_hash($expire, $salt)))."---".$expire;
###################################################################################################
### конец, обновляем пароль на форуме #############################################################
###################################################################################################
}