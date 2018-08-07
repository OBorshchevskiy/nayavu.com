<?
$login = $argv[1];
$password = $argv[2];
$email = $argv[3];
$timezone = $argv[4];
$ip = $argv[5];

if (isset($login) && isset($password) && isset($email)) {
###################################################################################################
### начало, регистрируем на форуме ################################################################
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

$initial_group_id = ($forum_config['o_regs_verify'] == '0') ? $forum_config['o_default_user_group'] : FORUM_UNVERIFIED;
$salt = gen_rand_word();
$password_hash = forum_hash($password, $salt);

# данные которые будут добавлены при регистрации
$user_info = array(
'username'				=>	$login,
'group_id'				=>	$initial_group_id,
'salt'					=>	$salt,
'password'				=>	$password,
'password_hash'			=>	$password_hash,
'email'					=>	$email,
'email_setting'			=>	$forum_config['o_default_email_setting'],
'timezone'				=>	$timezone,
'dst'					=>	0,
'language'				=>	"Russian",
'style'					=>	$forum_config['o_default_style'],
'registered'			=>	time(),
'registration_ip'		=>	$ip,
'activate_key'			=>	($forum_config['o_regs_verify'] == '1') ? '\''.random_key(8, true).'\'' : 'NULL',
'require_verification'	=>	($forum_config['o_regs_verify'] == '1'),
'notify_admins'			=>	($forum_config['o_regs_report'] == '1')
);

# добавляем пользователя
add_user($user_info, $new_uid);

$query=null;
$result="";
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

$expire = time()+60*60*24*30;

echo $cookie_name."---".base64_encode($id.'|'.$password_hash.'|'.$expire.'|'.sha1($salt.$password_hash.forum_hash($expire, $salt)))."---".$expire;
###################################################################################################
### конец, регистрируем на форуме #################################################################
###################################################################################################
}