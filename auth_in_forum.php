<?
if (isset($argv[1]) && isset($argv[2])) {
###################################################################################################
### начало, авторизуемся на форуме ################################################################
###################################################################################################
define('FORUM_ROOT', 'forum/');
require_once FORUM_ROOT.'config.php';
require_once FORUM_ROOT.'include/functions.php';
require_once FORUM_ROOT.'include/dblayer/common_db.php';

$username = $argv[1];
$password_receive = $argv[2];
$save = $argv[3];

# получаем данные пользователя
$query = array(
				'SELECT'	=> 'u.id, u.password, u.salt',
				'FROM'		=> 'users AS u',
			    'WHERE'		=> 'u.username=\''.$username.'\' AND u.id>1'
			);
$result = $forum_db->query_build($query) or error(__FILE__, __LINE__);
if ($forum_db->num_rows($result)) {
list($id, $password, $salt) = $forum_db->fetch_row($result);
}

if ($save) {
$expire = time()+60*60*24*30;
} else {
$expire = time()+5400;
}

$form_password_hash = forum_hash($password_receive, $salt);

echo $cookie_name."---".base64_encode($id.'|'.$form_password_hash.'|'.$expire.'|'.sha1($salt.$form_password_hash.forum_hash($expire, $salt)))."---".$expire;
###################################################################################################
### конец, авторизуемся на форуме #################################################################
###################################################################################################
}
?>