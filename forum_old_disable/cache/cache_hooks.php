<?php

define('FORUM_HOOKS_LOADED', 1);

$forum_hooks = array (
  'agr_start' => 
  array (
    0 => '$GLOBALS[\'ext_info_stack\'][] = array(
\'id\'				=> \'pun_attachment\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_attachment\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_attachment\',
\'dependencies\'	=> array (
)
);
$ext_info = $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];

require $ext_info[\'path\'].\'/include/attach_func.php\';
if (file_exists($ext_info[\'path\'].\'/lang/\'.$forum_user[\'language\'].\'/\'.$ext_info[\'id\'].\'.php\'))
	require $ext_info[\'path\'].\'/lang/\'.$forum_user[\'language\'].\'/\'.$ext_info[\'id\'].\'.php\';
else
	require $ext_info[\'path\'].\'/lang/English/\'.$ext_info[\'id\'].\'.php\';

array_pop($GLOBALS[\'ext_info_stack\']);
$ext_info = empty($GLOBALS[\'ext_info_stack\']) ? array() : $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];
',
  ),
  'agr_add_edit_group_flood_fieldset_end' => 
  array (
    0 => '$GLOBALS[\'ext_info_stack\'][] = array(
\'id\'				=> \'pun_attachment\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_attachment\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_attachment\',
\'dependencies\'	=> array (
)
);
$ext_info = $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];

?>

	<div class="content-head">
		<h3 class="hn"><span><?php echo $lang_attach[\'Group attach part\'] ?></span></h3>
	</div>
	<fieldset class="mf-set set<?php echo ++$forum_page[\'item_count\'] ?>">
		<legend><span><?php echo $lang_attach[\'Attachment rules\'] ?></span></legend>
		<div class="mf-box">
			<div class="mf-item">
				<span class="fld-input"><input type="checkbox" id="fld<?php echo ++$forum_page[\'fld_count\'] ?>" name="download" value="1"<?php if ($group[\'g_pun_attachment_allow_download\'] == \'1\') echo \' checked="checked"\' ?> /></span>
				<label for="fld<?php echo $forum_page[\'fld_count\'] ?>"><?php echo $lang_attach[\'Download\']?></label>
			</div>
			<div class="mf-item">
				<span class="fld-input"><input type="checkbox" id="fld<?php echo ++$forum_page[\'fld_count\'] ?>" name="upload" value="1"<?php if ($group[\'g_pun_attachment_allow_upload\'] == \'1\') echo \' checked="checked"\' ?> /></span>
				<label for="fld<?php echo $forum_page[\'fld_count\'] ?>"><?php echo $lang_attach[\'Upload\'] ?></label>
			</div>
			<div class="mf-item">
				<span class="fld-input"><input type="checkbox" id="fld<?php echo ++$forum_page[\'fld_count\'] ?>" name="delete" value="1"<?php if ($group[\'g_pun_attachment_allow_delete\'] == \'1\') echo \' checked="checked"\' ?> /></span>
				<label for="fld<?php echo $forum_page[\'fld_count\'] ?>"><?php echo $lang_attach[\'Delete\'] ?></label>
			</div>
			<div class="mf-item">
				<span class="fld-input"><input type="checkbox" id="fld<?php echo ++$forum_page[\'fld_count\'] ?>" name="owner_delete" value="1"<?php if ($group[\'g_pun_attachment_allow_delete_own\'] == \'1\') echo \' checked="checked"\' ?> /></span>
				<label for="fld<?php echo $forum_page[\'fld_count\'] ?>"><?php echo $lang_attach[\'Owner delete\'] ?></label>
			</div>
		</div>
	</fieldset>
	<div class="sf-set set<?php echo ++$forum_page[\'item_count\'] ?>">
		<div class="sf-box text">
			<label for="fld<?php echo ++$forum_page[\'fld_count\'] ?>"><span><?php echo $lang_attach[\'Size\'] ?></span> <small><?php echo $lang_attach[\'Size comment\'] ?></small></label><br />
			<span class="fld-input"><input type="text" id="fld<?php echo $forum_page[\'fld_count\'] ?>" name="max_size" size="15" maxlength="15" value="<?php echo $group[\'g_pun_attachment_upload_max_size\'] ?>" /></span>
		</div>
		<div class="sf-box text">
			<label for="fld<?php echo ++$forum_page[\'fld_count\'] ?>"><span><?php echo $lang_attach[\'Per post\'] ?></span></label><br />
			<span class="fld-input"><input type="text" id="fld<?php echo $forum_page[\'fld_count\'] ?>" name="per_post" size="4" maxlength="5" value="<?php echo $group[\'g_pun_attachment_files_per_post\'] ?>" /></span>
		</div>
		<div class="sf-box text">
			<label for="fld<?php echo ++$forum_page[\'fld_count\'] ?>"><span><?php echo $lang_attach[\'Allowed files\'] ?></span><small><?php echo $lang_attach[\'Allowed comment\'] ?></small></label><br />
			<span class="fld-input"><input type="text" id="fld<?php echo $forum_page[\'fld_count\'] ?>" name="file_ext" size="80" maxlength="80" value="<?php echo $group[\'g_pun_attachment_disallowed_extensions\'] ?>" /></span>
		</div>
	</div>

<?php

array_pop($GLOBALS[\'ext_info_stack\']);
$ext_info = empty($GLOBALS[\'ext_info_stack\']) ? array() : $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];
',
  ),
  'agr_add_edit_end_validation' => 
  array (
    0 => '$GLOBALS[\'ext_info_stack\'][] = array(
\'id\'				=> \'pun_attachment\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_attachment\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_attachment\',
\'dependencies\'	=> array (
)
);
$ext_info = $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];

$group_id = isset($_POST[\'group_id\']) ? intval($_POST[\'group_id\']) : \'\';
if ($_POST[\'mode\'] == \'add\' || (!empty($group_id) && $group_id != FORUM_ADMIN))
{
	$allow_down = isset($_POST[\'download\']) && $_POST[\'download\'] == \'1\' ? \'1\' : \'0\';
	$allow_upl = isset($_POST[\'upload\']) && $_POST[\'upload\'] == \'1\' ? \'1\' : \'0\';
	$allow_del = isset($_POST[\'delete\']) && $_POST[\'delete\'] == \'1\' ? \'1\' : \'0\';
	$allow_del_own = isset($_POST[\'owner_delete\']) && $_POST[\'owner_delete\'] == \'1\' ? \'1\' : \'0\';

	$size = isset($_POST[\'max_size\']) ? intval($_POST[\'max_size\']) : \'0\';
	$upload_max_filesize = get_bytes(ini_get(\'upload_max_filesize\'));
	$post_max_size = get_bytes(ini_get(\'post_max_size\'));
	if ($size > $upload_max_filesize ||  $size > $post_max_size)
		$size = min($upload_max_filesize, $post_max_size);

	$per_post = isset($_POST[\'per_post\']) ? intval($_POST[\'per_post\']) : \'1\';
	$file_ext = isset($_POST[\'file_ext\']) ? trim($_POST[\'file_ext\']) : \'\';

	if (!empty($file_ext))
	{
		$file_ext = preg_replace(\'/\\s/\', \'\', $file_ext);
		$match = preg_match(\'/(^[a-zA-Z0-9])+(([a-zA-Z0-9]+\\,)|([a-zA-Z0-9]))+([a-zA-Z0-9]+$)/\', $file_ext);

		if (!$match)
			message($lang_attach[\'Wrong allowed\']);
	}
}

array_pop($GLOBALS[\'ext_info_stack\']);
$ext_info = empty($GLOBALS[\'ext_info_stack\']) ? array() : $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];
',
  ),
  'agr_add_end_qr_add_group' => 
  array (
    0 => '$GLOBALS[\'ext_info_stack\'][] = array(
\'id\'				=> \'pun_attachment\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_attachment\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_attachment\',
\'dependencies\'	=> array (
)
);
$ext_info = $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];

$query[\'INSERT\'] .= \', g_pun_attachment_allow_download, g_pun_attachment_allow_upload, g_pun_attachment_allow_delete, g_pun_attachment_allow_delete_own, g_pun_attachment_upload_max_size, g_pun_attachment_files_per_post, g_pun_attachment_disallowed_extensions\';
$query[\'VALUES\'] .= \', \'.implode(\',\', array($allow_down, $allow_upl, $allow_del, $allow_del_own, $size, $per_post, \'\\\'\'.$forum_db->escape($file_ext).\'\\\'\'));

array_pop($GLOBALS[\'ext_info_stack\']);
$ext_info = empty($GLOBALS[\'ext_info_stack\']) ? array() : $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];
',
  ),
  'agr_edit_end_qr_update_group' => 
  array (
    0 => '$GLOBALS[\'ext_info_stack\'][] = array(
\'id\'				=> \'pun_attachment\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_attachment\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_attachment\',
\'dependencies\'	=> array (
)
);
$ext_info = $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];

if (isset($allow_down))
	$query[\'SET\'] .= \', g_pun_attachment_allow_download = \'.$allow_down.\', g_pun_attachment_allow_upload = \'.$allow_upl.\', g_pun_attachment_allow_delete = \'.$allow_del.\', g_pun_attachment_allow_delete_own = \'.$allow_del_own.\', g_pun_attachment_upload_max_size = \'.$size.\', g_pun_attachment_files_per_post = \'.$per_post.\', g_pun_attachment_disallowed_extensions = \\\'\'.$forum_db->escape($file_ext).\'\\\'\';

array_pop($GLOBALS[\'ext_info_stack\']);
$ext_info = empty($GLOBALS[\'ext_info_stack\']) ? array() : $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];
',
  ),
  'hd_head' => 
  array (
    0 => '$GLOBALS[\'ext_info_stack\'][] = array(
\'id\'				=> \'pun_attachment\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_attachment\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_attachment\',
\'dependencies\'	=> array (
)
);
$ext_info = $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];

if (!$forum_config[\'attach_disable_attach\'] && in_array(FORUM_PAGE, array(\'viewtopic\', \'postedit\', \'attachment-preview\')))
{
	if (is_dir($ext_info[\'path\'].\'/styles/\'.$forum_user[\'style\']))
	{
		$forum_head[\'style_attch\'] = \'<link rel="stylesheet" type="text/css" media="screen" href="\'.$ext_info[\'url\'].\'/style/\'.$forum_user[\'style\'].\'/\'.$forum_user[\'style\'].\'.css" />\';
		$forum_head[\'style_attch_css\'] = \'<link rel="stylesheet" type="text/css" media="screen" href="\'.$ext_info[\'url\'].\'/style/\'.$forum_user[\'style\'].\'/\'.$forum_user[\'style\'].\'_cs.css" />\';
	}
	else
	{
		$forum_head[\'style_attch\'] = \'<link rel="stylesheet" type="text/css" media="screen" href="\'.$ext_info[\'url\'].\'/style/Nayavu/Nayavu.css" />\';
		$forum_head[\'style_attch_css\'] = \'<link rel="stylesheet" type="text/css" media="screen" href="\'.$ext_info[\'url\'].\'/style/Nayavu/Nayavu_cs.css" />\';
	}
}

array_pop($GLOBALS[\'ext_info_stack\']);
$ext_info = empty($GLOBALS[\'ext_info_stack\']) ? array() : $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];
',
    1 => '$GLOBALS[\'ext_info_stack\'][] = array(
\'id\'				=> \'pun_bbcode\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_bbcode\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_bbcode\',
\'dependencies\'	=> array (
)
);
$ext_info = $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];

if ($forum_user[\'pun_bbcode_enabled\'] && ((FORUM_PAGE == \'viewtopic\' && $forum_config[\'o_quickpost\']) || in_array(FORUM_PAGE, array(\'post\', \'postedit\'))))
{
	if (!defined(\'FORUM_PARSER_LOADED\'))
		require FORUM_ROOT.\'include/parser.php\';

	$forum_head[\'style_pun_bbcode\'] = \'<link rel="stylesheet" type="text/css" media="screen" href="\'.$ext_info[\'url\'].\'/styles.css" />\';
	$forum_head[\'js_pun_bbcode\'] = \'<script type="text/javascript" src="\'.$ext_info[\'url\'].\'/scripts.js"></script>\';
}

array_pop($GLOBALS[\'ext_info_stack\']);
$ext_info = empty($GLOBALS[\'ext_info_stack\']) ? array() : $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];
',
    2 => '$GLOBALS[\'ext_info_stack\'][] = array(
\'id\'				=> \'pun_pm\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_pm\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_pm\',
\'dependencies\'	=> array (
)
);
$ext_info = $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];

// Incuding styles for pun_pm
if (defined(\'FORUM_PAGE\') && \'pun_pm\' == substr(FORUM_PAGE, 0, 6))
{
	if (file_exists($ext_info[\'path\'].\'/styles/\'.$forum_user[\'style\'].\'/\'))
		$forum_head[\'style_pun_pm\'] = \'<link rel="stylesheet" type="text/css" media="screen" href="\'.$ext_info[\'url\'].\'/styles/\'.$forum_user[\'style\'].\'/style.css" />\';
	else
		$forum_head[\'style_pun_pm\'] = \'<link rel="stylesheet" type="text/css" media="screen" href="\'.$ext_info[\'url\'].\'/styles/Nayavu/style.css" />\';
}

array_pop($GLOBALS[\'ext_info_stack\']);
$ext_info = empty($GLOBALS[\'ext_info_stack\']) ? array() : $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];
',
    3 => '$GLOBALS[\'ext_info_stack\'][] = array(
\'id\'				=> \'pun_quote\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_quote\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_quote\',
\'dependencies\'	=> array (
)
);
$ext_info = $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];

if (!$forum_user[\'is_guest\'] && FORUM_PAGE == \'viewtopic\')
				$forum_head[\'quote_js\'] = \'<script type="text/javascript" src="\'.$ext_info[\'url\'].\'/scripts.js"></script>\';

array_pop($GLOBALS[\'ext_info_stack\']);
$ext_info = empty($GLOBALS[\'ext_info_stack\']) ? array() : $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];
',
  ),
  'po_start' => 
  array (
    0 => '$GLOBALS[\'ext_info_stack\'][] = array(
\'id\'				=> \'pun_attachment\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_attachment\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_attachment\',
\'dependencies\'	=> array (
)
);
$ext_info = $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];

if (!$forum_config[\'attach_disable_attach\'])
{
	require $ext_info[\'path\'].\'/include/attach_func.php\';
	if (file_exists($ext_info[\'path\'].\'/lang/\'.$forum_user[\'language\'].\'/\'.$ext_info[\'id\'].\'.php\'))
		require $ext_info[\'path\'].\'/lang/\'.$forum_user[\'language\'].\'/\'.$ext_info[\'id\'].\'.php\';
	else
		require $ext_info[\'path\'].\'/lang/English/\'.$ext_info[\'id\'].\'.php\';
	require $ext_info[\'path\'].\'/url.php\';
}

array_pop($GLOBALS[\'ext_info_stack\']);
$ext_info = empty($GLOBALS[\'ext_info_stack\']) ? array() : $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];
',
  ),
  'po_qr_get_topic_forum_info' => 
  array (
    0 => '$GLOBALS[\'ext_info_stack\'][] = array(
\'id\'				=> \'pun_attachment\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_attachment\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_attachment\',
\'dependencies\'	=> array (
)
);
$ext_info = $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];

if (!$forum_config[\'attach_disable_attach\'])
{
	$query[\'SELECT\'] .= \', g_pun_attachment_allow_upload, g_pun_attachment_upload_max_size, g_pun_attachment_files_per_post, g_pun_attachment_disallowed_extensions, g_pun_attachment_allow_delete_own\';
	$query[\'JOINS\'][] = array(\'LEFT JOIN\' => \'groups AS g\', \'ON\' => \'g.g_id = \'.$forum_user[\'g_id\']);
}

array_pop($GLOBALS[\'ext_info_stack\']);
$ext_info = empty($GLOBALS[\'ext_info_stack\']) ? array() : $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];
',
  ),
  'po_qr_get_forum_info' => 
  array (
    0 => '$GLOBALS[\'ext_info_stack\'][] = array(
\'id\'				=> \'pun_attachment\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_attachment\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_attachment\',
\'dependencies\'	=> array (
)
);
$ext_info = $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];

if (!$forum_config[\'attach_disable_attach\'])
{
	$query[\'SELECT\'] .= \', g_pun_attachment_allow_upload, g_pun_attachment_upload_max_size, g_pun_attachment_files_per_post, g_pun_attachment_disallowed_extensions, g_pun_attachment_allow_delete_own\';
	$query[\'JOINS\'][] = array(\'LEFT JOIN\' => \'groups AS g\', \'ON\' => \'g.g_id = \'.$forum_user[\'g_id\']);
}

array_pop($GLOBALS[\'ext_info_stack\']);
$ext_info = empty($GLOBALS[\'ext_info_stack\']) ? array() : $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];
',
  ),
  'po_form_submitted' => 
  array (
    0 => '$GLOBALS[\'ext_info_stack\'][] = array(
\'id\'				=> \'pun_attachment\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_attachment\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_attachment\',
\'dependencies\'	=> array (
)
);
$ext_info = $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];

if (!$forum_config[\'attach_disable_attach\'])
{
	$attach_secure_str = $forum_user[\'id\'].($tid ? \'t\'.$tid : \'f\'.$fid);
	$uploaded_list = array();
	$attach_query = array(
		\'SELECT\'	=>	\'id, owner_id, post_id, topic_id, filename, file_ext, file_mime_type, file_path, size, download_counter, uploaded_at, secure_str\',
		\'FROM\'		=>	\'attach_files\',
		\'WHERE\'		=>	\'secure_str = \\\'\'.$forum_db->escape($attach_secure_str).\'\\\'\'
	);
	
	$attach_result = $forum_db->query_build($attach_query) or error(__FILE__, __LINE__);
	if ($forum_db->num_rows($attach_result) > 0)
	{
		while ($cur_attach = $forum_db->fetch_assoc($attach_result))
			$uploaded_list[] = $cur_attach;	
	}
}

array_pop($GLOBALS[\'ext_info_stack\']);
$ext_info = empty($GLOBALS[\'ext_info_stack\']) ? array() : $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];
',
  ),
  'po_end_validation' => 
  array (
    0 => '$GLOBALS[\'ext_info_stack\'][] = array(
\'id\'				=> \'pun_attachment\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_attachment\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_attachment\',
\'dependencies\'	=> array (
)
);
$ext_info = $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];

if (!$forum_config[\'attach_disable_attach\'])
{
	foreach (array_keys($_POST) as $key)
	{
		if (preg_match(\'~delete_(\\d+)~\', $key, $matches))
		{
			$attach_delete_id = $matches[1];
			break;
		}
	}
	if (isset($attach_delete_id))
	{
		foreach ($uploaded_list as $attach_index => $attach)
			if ($attach[\'id\'] == $attach_delete_id)
			{
				$delete_attach = $attach;
				$attach_delete_index = $attach_index;
				break;
			}
		if (isset($delete_attach) && ($forum_user[\'g_id\'] == FORUM_ADMIN || $cur_posting[\'g_pun_attachment_allow_delete_own\']))
		{
			$attach_query = array(
				\'DELETE\'	=>	\'attach_files\',
				\'WHERE\'		=>	\'id = \'.$delete_attach[\'id\']
			);
			$forum_db->query_build($attach_query) or error(__FILE__, __LINE__);
			unset($uploaded_list[$attach_delete_index]);
			if ($forum_config[\'attach_create_orphans\'] == \'0\')
				unlink($forum_config[\'attach_basefolder\'].$delete_attach[\'file_path\']);
		}
		else
			$errors[] = $lang_attach[\'Del perm error\'];
		$_POST[\'preview\'] = 1;
	}
	else if (isset($_POST[\'add_file\']))
	{
		attach_create_attachment($attach_secure_str, $cur_posting);
		$_POST[\'preview\'] = 1;
	}
}

array_pop($GLOBALS[\'ext_info_stack\']);
$ext_info = empty($GLOBALS[\'ext_info_stack\']) ? array() : $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];
',
  ),
  'po_pre_redirect' => 
  array (
    0 => '$GLOBALS[\'ext_info_stack\'][] = array(
\'id\'				=> \'pun_attachment\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_attachment\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_attachment\',
\'dependencies\'	=> array (
)
);
$ext_info = $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];

if (!$forum_config[\'attach_disable_attach\'] && isset($_POST[\'submit\']))
{
	$attach_query = array(
		\'UPDATE\'	=>	\'attach_files\',
		\'SET\'		=>	\'owner_id = \'.$forum_user[\'id\'].\', topic_id = \'.(isset($new_tid) ? $new_tid : $tid).\', post_id = \'.$new_pid.\', secure_str = NULL\',
		\'WHERE\'		=>	\'secure_str = \\\'\'.$forum_db->escape($attach_secure_str).\'\\\'\'
	);
	$forum_db->query_build($attach_query) or error(__FILE__, __LINE__);
}

array_pop($GLOBALS[\'ext_info_stack\']);
$ext_info = empty($GLOBALS[\'ext_info_stack\']) ? array() : $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];
',
  ),
  'po_pre_header_load' => 
  array (
    0 => '$GLOBALS[\'ext_info_stack\'][] = array(
\'id\'				=> \'pun_attachment\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_attachment\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_attachment\',
\'dependencies\'	=> array (
)
);
$ext_info = $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];

if (!$forum_config[\'attach_disable_attach\'])
	$forum_page[\'form_attributes\'][\'enctype\'] = \'enctype="multipart/form-data"\';

array_pop($GLOBALS[\'ext_info_stack\']);
$ext_info = empty($GLOBALS[\'ext_info_stack\']) ? array() : $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];
',
  ),
  'po_pre_req_info_fieldset_end' => 
  array (
    0 => '$GLOBALS[\'ext_info_stack\'][] = array(
\'id\'				=> \'pun_attachment\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_attachment\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_attachment\',
\'dependencies\'	=> array (
)
);
$ext_info = $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];

if (!$forum_config[\'attach_disable_attach\'])
	show_attachments(isset($uploaded_list) ? $uploaded_list : array(), $cur_posting);

array_pop($GLOBALS[\'ext_info_stack\']);
$ext_info = empty($GLOBALS[\'ext_info_stack\']) ? array() : $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];
',
  ),
  'vt_start' => 
  array (
    0 => '$GLOBALS[\'ext_info_stack\'][] = array(
\'id\'				=> \'pun_attachment\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_attachment\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_attachment\',
\'dependencies\'	=> array (
)
);
$ext_info = $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];

if (!$forum_config[\'attach_disable_attach\'])
{
	require $ext_info[\'path\'].\'/include/attach_func.php\';
	if (file_exists($ext_info[\'path\'].\'/lang/\'.$forum_user[\'language\'].\'/\'.$ext_info[\'id\'].\'.php\'))
		require $ext_info[\'path\'].\'/lang/\'.$forum_user[\'language\'].\'/\'.$ext_info[\'id\'].\'.php\';
	else
		require $ext_info[\'path\'].\'/lang/English/\'.$ext_info[\'id\'].\'.php\';
	require $ext_info[\'path\'].\'/url.php\';
}

array_pop($GLOBALS[\'ext_info_stack\']);
$ext_info = empty($GLOBALS[\'ext_info_stack\']) ? array() : $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];
',
  ),
  'vt_qr_get_topic_info' => 
  array (
    0 => '$GLOBALS[\'ext_info_stack\'][] = array(
\'id\'				=> \'pun_attachment\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_attachment\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_attachment\',
\'dependencies\'	=> array (
)
);
$ext_info = $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];

if (!$forum_config[\'attach_disable_attach\'])
{
	$query[\'SELECT\'] .= \', g_pun_attachment_allow_download\';
	$query[\'JOINS\'][] = array(\'LEFT JOIN\' => \'groups AS g\', \'ON\' => \'g.g_id = \'.$forum_user[\'g_id\']);
}

array_pop($GLOBALS[\'ext_info_stack\']);
$ext_info = empty($GLOBALS[\'ext_info_stack\']) ? array() : $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];
',
  ),
  'vt_main_output_start' => 
  array (
    0 => '$GLOBALS[\'ext_info_stack\'][] = array(
\'id\'				=> \'pun_attachment\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_attachment\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_attachment\',
\'dependencies\'	=> array (
)
);
$ext_info = $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];

if (!$forum_config[\'attach_disable_attach\'])
{
	$attach_query = array(
		\'SELECT\'	=>	\'id, post_id, filename, file_ext, file_mime_type, size, download_counter, uploaded_at, file_path\',
		\'FROM\'		=>	\'attach_files\',
		\'WHERE\'		=>	\'topic_id = \'.$id,
		\'ORDER BY\'	=>	\'filename\'
	);
	$attach_result = $forum_db->query_build($attach_query) or error(__FILE__, __LINE__);
	$attach_list = array();
	while ($cur_attach = $forum_db->fetch_assoc($attach_result))
	{
		if (!isset($attach_list[$cur_attach[\'post_id\']]))
			$attach_list[$cur_attach[\'post_id\']] = array();
		$attach_list[$cur_attach[\'post_id\']][] = $cur_attach;
	}
}

array_pop($GLOBALS[\'ext_info_stack\']);
$ext_info = empty($GLOBALS[\'ext_info_stack\']) ? array() : $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];
',
  ),
  'vt_row_pre_display' => 
  array (
    0 => '$GLOBALS[\'ext_info_stack\'][] = array(
\'id\'				=> \'pun_attachment\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_attachment\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_attachment\',
\'dependencies\'	=> array (
)
);
$ext_info = $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];

if (!$forum_config[\'attach_disable_attach\'] && isset($attach_list[$cur_post[\'id\']]))
{
	if (isset($forum_page[\'message\'][\'signature\']))
		$forum_page[\'message\'][\'signature\'] = show_attachments_post($attach_list[$cur_post[\'id\']], $cur_post[\'id\'], $cur_topic).$forum_page[\'message\'][\'signature\'];
	else
		$forum_page[\'message\'][\'attachments\'] = show_attachments_post($attach_list[$cur_post[\'id\']], $cur_post[\'id\'], $cur_topic);
}

array_pop($GLOBALS[\'ext_info_stack\']);
$ext_info = empty($GLOBALS[\'ext_info_stack\']) ? array() : $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];
',
  ),
  'ed_start' => 
  array (
    0 => '$GLOBALS[\'ext_info_stack\'][] = array(
\'id\'				=> \'pun_attachment\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_attachment\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_attachment\',
\'dependencies\'	=> array (
)
);
$ext_info = $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];

if (!$forum_config[\'attach_disable_attach\'])
{
	require $ext_info[\'path\'].\'/include/attach_func.php\';
	if (file_exists($ext_info[\'path\'].\'/lang/\'.$forum_user[\'language\'].\'/\'.$ext_info[\'id\'].\'.php\'))
		require $ext_info[\'path\'].\'/lang/\'.$forum_user[\'language\'].\'/\'.$ext_info[\'id\'].\'.php\';
	else
		require $ext_info[\'path\'].\'/lang/English/\'.$ext_info[\'id\'].\'.php\';
	require $ext_info[\'path\'].\'/url.php\';
}

array_pop($GLOBALS[\'ext_info_stack\']);
$ext_info = empty($GLOBALS[\'ext_info_stack\']) ? array() : $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];
',
  ),
  'ed_qr_get_post_info' => 
  array (
    0 => '$GLOBALS[\'ext_info_stack\'][] = array(
\'id\'				=> \'pun_attachment\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_attachment\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_attachment\',
\'dependencies\'	=> array (
)
);
$ext_info = $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];

if (!$forum_config[\'attach_disable_attach\'])
{
	$query[\'SELECT\'] .= \', g_pun_attachment_allow_upload, g_pun_attachment_upload_max_size, g_pun_attachment_files_per_post, g_pun_attachment_disallowed_extensions, g_pun_attachment_allow_delete_own, g_pun_attachment_allow_delete\';
	$query[\'JOINS\'][] = array(\'LEFT JOIN\' => \'groups AS g\', \'ON\' => \'g.g_id = \'.$forum_user[\'g_id\']);
}

array_pop($GLOBALS[\'ext_info_stack\']);
$ext_info = empty($GLOBALS[\'ext_info_stack\']) ? array() : $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];
',
  ),
  'ed_post_selected' => 
  array (
    0 => '$GLOBALS[\'ext_info_stack\'][] = array(
\'id\'				=> \'pun_attachment\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_attachment\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_attachment\',
\'dependencies\'	=> array (
)
);
$ext_info = $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];

if (!$forum_config[\'attach_disable_attach\'])
{
	$attach_secure_str = $forum_user[\'id\'].\'t\'.$cur_post[\'tid\'];
	$uploaded_list = array();
	$attach_query = array(
		\'SELECT\'	=>	\'id, owner_id, post_id, topic_id, filename, file_ext, file_mime_type, file_path, size, download_counter, uploaded_at, secure_str\',
		\'FROM\'		=>	\'attach_files\',
		\'WHERE\'		=>	\'post_id = \'.$id.\' OR secure_str = \\\'\'.$attach_secure_str.\'\\\'\',
		\'ORDER BY\'	=>	\'filename\'
	);

	$attach_result = $forum_db->query_build($attach_query) or error(__FILE__, __LINE__);
	if ($forum_db->num_rows($attach_result) > 0)
	{
		while ($cur_attach = $forum_db->fetch_assoc($attach_result))
			$uploaded_list[] = $cur_attach;	
	}
}

array_pop($GLOBALS[\'ext_info_stack\']);
$ext_info = empty($GLOBALS[\'ext_info_stack\']) ? array() : $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];
',
  ),
  'ed_end_validation' => 
  array (
    0 => '$GLOBALS[\'ext_info_stack\'][] = array(
\'id\'				=> \'pun_attachment\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_attachment\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_attachment\',
\'dependencies\'	=> array (
)
);
$ext_info = $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];

if (!$forum_config[\'attach_disable_attach\'])
{
	foreach (array_keys($_POST) as $key)
	{
		if (preg_match(\'~delete_(\\d+)~\', $key, $matches))
		{
			$attach_delete_id = $matches[1];
			break;
		}
	}
	if (isset($attach_delete_id))
	{
		foreach ($uploaded_list as $attach_index => $attach)
			if ($attach[\'id\'] == $attach_delete_id)
			{
				$delete_attach = $attach;
				$attach_delete_index = $attach_index;
				break;
			}
		if (isset($delete_attach) && ($forum_user[\'g_id\'] == FORUM_ADMIN || $cur_post[\'g_pun_attachment_allow_delete\'] || ($cur_post[\'g_pun_attachment_allow_delete_own\'] && $forum_user[\'id\'] == $delete_attach[\'owner_id\'])))
		{
			$attach_query = array(
				\'DELETE\'	=>	\'attach_files\',
				\'WHERE\'		=>	\'id = \'.$delete_attach[\'id\']
			);
			$forum_db->query_build($attach_query) or error(__FILE__, __LINE__);
			unset($uploaded_list[$attach_delete_index]);
			if ($forum_config[\'attach_create_orphans\'] == \'0\')
				unlink($forum_config[\'attach_basefolder\'].$delete_attach[\'file_path\']);
		}
		else
			$errors[] = $lang_attach[\'Del perm error\'];
		$_POST[\'preview\'] = 1;
	}
	else if (isset($_POST[\'add_file\']))
	{
		attach_create_attachment($attach_secure_str, $cur_post);
		$_POST[\'preview\'] = 1;
	}
}

array_pop($GLOBALS[\'ext_info_stack\']);
$ext_info = empty($GLOBALS[\'ext_info_stack\']) ? array() : $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];
',
  ),
  'ed_pre_redirect' => 
  array (
    0 => '$GLOBALS[\'ext_info_stack\'][] = array(
\'id\'				=> \'pun_attachment\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_attachment\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_attachment\',
\'dependencies\'	=> array (
)
);
$ext_info = $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];

if (!$forum_config[\'attach_disable_attach\'] && isset($_POST[\'submit\']))
{
	$attach_query = array(
		\'UPDATE\'	=>	\'attach_files\',
		\'SET\'		=>	\'owner_id = \'.$forum_user[\'id\'].\', topic_id = \'.$cur_post[\'tid\'].\', post_id = \'.$id.\', secure_str = NULL\',
		\'WHERE\'		=>	\'secure_str = \\\'\'.$forum_db->escape($attach_secure_str).\'\\\'\'
	);
	$forum_db->query_build($attach_query) or error(__FILE__, __LINE__);
}

array_pop($GLOBALS[\'ext_info_stack\']);
$ext_info = empty($GLOBALS[\'ext_info_stack\']) ? array() : $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];
',
  ),
  'ed_pre_header_load' => 
  array (
    0 => '$GLOBALS[\'ext_info_stack\'][] = array(
\'id\'				=> \'pun_attachment\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_attachment\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_attachment\',
\'dependencies\'	=> array (
)
);
$ext_info = $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];

if (!$forum_config[\'attach_disable_attach\'])
	$forum_page[\'form_attributes\'][\'enctype\'] = \'enctype="multipart/form-data"\';

array_pop($GLOBALS[\'ext_info_stack\']);
$ext_info = empty($GLOBALS[\'ext_info_stack\']) ? array() : $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];
',
  ),
  'ed_pre_main_fieldset_end' => 
  array (
    0 => '$GLOBALS[\'ext_info_stack\'][] = array(
\'id\'				=> \'pun_attachment\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_attachment\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_attachment\',
\'dependencies\'	=> array (
)
);
$ext_info = $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];

if (!$forum_config[\'attach_disable_attach\'])
	show_attachments(isset($uploaded_list) ? $uploaded_list : array(), $cur_post);

array_pop($GLOBALS[\'ext_info_stack\']);
$ext_info = empty($GLOBALS[\'ext_info_stack\']) ? array() : $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];
',
  ),
  'aop_start' => 
  array (
    0 => '$GLOBALS[\'ext_info_stack\'][] = array(
\'id\'				=> \'pun_attachment\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_attachment\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_attachment\',
\'dependencies\'	=> array (
)
);
$ext_info = $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];

require $ext_info[\'path\'].\'/include/attach_func.php\';
if (file_exists($ext_info[\'path\'].\'/lang/\'.$forum_user[\'language\'].\'/\'.$ext_info[\'id\'].\'.php\'))
	require $ext_info[\'path\'].\'/lang/\'.$forum_user[\'language\'].\'/\'.$ext_info[\'id\'].\'.php\';
else
	require $ext_info[\'path\'].\'/lang/English/\'.$ext_info[\'id\'].\'.php\';
require $ext_info[\'path\'].\'/url.php\';

$section = isset($_GET[\'section\']) ? $_GET[\'section\'] : null;

if (isset($_POST[\'apply\']) && ($section == \'list_attach\') && isset($_POST[\'form_sent\']))
	unset($_POST[\'form_sent\']);

array_pop($GLOBALS[\'ext_info_stack\']);
$ext_info = empty($GLOBALS[\'ext_info_stack\']) ? array() : $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];
',
  ),
  'aop_new_section' => 
  array (
    0 => '$GLOBALS[\'ext_info_stack\'][] = array(
\'id\'				=> \'pun_attachment\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_attachment\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_attachment\',
\'dependencies\'	=> array (
)
);
$ext_info = $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];

if ($section == \'pun_attach\')
	require $ext_info[\'path\'].\'/pun_attach.php\';
else if ($section == \'pun_list_attach\')
	require $ext_info[\'path\'].\'/pun_list_attach.php\';

array_pop($GLOBALS[\'ext_info_stack\']);
$ext_info = empty($GLOBALS[\'ext_info_stack\']) ? array() : $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];
',
  ),
  'ca_fn_generate_admin_menu_new_sublink' => 
  array (
    0 => '$GLOBALS[\'ext_info_stack\'][] = array(
\'id\'				=> \'pun_attachment\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_attachment\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_attachment\',
\'dependencies\'	=> array (
)
);
$ext_info = $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];

require $ext_info[\'path\'].\'/url.php\';
if (file_exists($ext_info[\'path\'].\'/lang/\'.$forum_user[\'language\'].\'/\'.$ext_info[\'id\'].\'.php\'))
	require $ext_info[\'path\'].\'/lang/\'.$forum_user[\'language\'].\'/\'.$ext_info[\'id\'].\'.php\';
else
	require $ext_info[\'path\'].\'/lang/English/\'.$ext_info[\'id\'].\'.php\';

if ((FORUM_PAGE_SECTION == \'management\') && ($forum_user[\'g_id\'] == FORUM_ADMIN))
	$forum_page[\'admin_submenu\'][\'pun_attachment_management\'] = \'<li class="\'.((FORUM_PAGE == \'admin-attachment-manage\') ? \'active\' : \'normal\').((empty($forum_page[\'admin_menu\'])) ? \' first-item\' : \'\').\'"><a href="\'.forum_link($attach_url[\'admin_attachment_manage\']).\'">\'.$lang_attach[\'Attachment\'].\'</a></li>\';
if ((FORUM_PAGE_SECTION == \'settings\') && ($forum_user[\'g_id\'] == FORUM_ADMIN))
	$forum_page[\'admin_submenu\'][\'pun_attachment_settings\'] = \'<li class="\'.((FORUM_PAGE == \'admin-options-attach\') ? \'active\' : \'normal\').((empty($forum_page[\'admin_menu\'])) ? \' first-item\' : \'\').\'"><a href="\'.forum_link($attach_url[\'admin_options_attach\']).\'">\'.$lang_attach[\'Attachment\'].\'</a></li>\';

array_pop($GLOBALS[\'ext_info_stack\']);
$ext_info = empty($GLOBALS[\'ext_info_stack\']) ? array() : $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];
',
  ),
  'aop_pre_update_configuration' => 
  array (
    0 => '$GLOBALS[\'ext_info_stack\'][] = array(
\'id\'				=> \'pun_attachment\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_attachment\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_attachment\',
\'dependencies\'	=> array (
)
);
$ext_info = $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];

if ($section == \'pun_attach\')
{
	while (list($key, $input) = @each($form))
	{
		if ($forum_config[\'attach_\'.$key] != $input)
		{
			if ($input != \'\' || is_int($input))
				$value = \'\\\'\'.$forum_db->escape($input).\'\\\'\';
			else
				$value = \'NULL\';

			$query = array(
				\'UPDATE\'	=> \'config\',
				\'SET\'		=> \'conf_value=\'.$value,
				\'WHERE\'		=> \'conf_name=\\\'attach_\'.$key.\'\\\'\'
			);

			$forum_db->query_build($query) or error(__FILE__,__LINE__);
		}
	}

	require_once FORUM_ROOT.\'include/cache.php\';
	generate_config_cache();

	redirect(forum_link($attach_url[\'admin_options_attach\']), $lang_admin_settings[\'Settings updated\'].\' \'.$lang_admin_common[\'Redirect\']);
}

array_pop($GLOBALS[\'ext_info_stack\']);
$ext_info = empty($GLOBALS[\'ext_info_stack\']) ? array() : $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];
',
  ),
  'aop_pre_redirect' => 
  array (
    0 => '$GLOBALS[\'ext_info_stack\'][] = array(
\'id\'				=> \'pun_attachment\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_attachment\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_attachment\',
\'dependencies\'	=> array (
)
);
$ext_info = $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];

if ($section == \'pun_attach\')
	redirect(forum_link($attach_url[\'admin_options_attach\']), $lang_admin_settings[\'Settings updated\'].\' \'.$lang_admin_common[\'Redirect\']);

array_pop($GLOBALS[\'ext_info_stack\']);
$ext_info = empty($GLOBALS[\'ext_info_stack\']) ? array() : $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];
',
  ),
  'aop_new_section_validation' => 
  array (
    0 => '$GLOBALS[\'ext_info_stack\'][] = array(
\'id\'				=> \'pun_attachment\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_attachment\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_attachment\',
\'dependencies\'	=> array (
)
);
$ext_info = $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];

if ($section == \'pun_attach\')
{
	if (!isset($form[\'use_icon\']) || $form[\'use_icon\'] != \'1\') $form[\'use_icon\'] = \'0\';
	if (!isset($form[\'create_orphans\']) || $form[\'create_orphans\'] != \'1\') $form[\'create_orphans\'] = \'0\';
	if (!isset($form[\'disable_attach\']) || $form[\'disable_attach\'] != \'1\') $form[\'disable_attach\'] = \'0\';
	if (!isset($form[\'disp_small\']) || $form[\'disp_small\'] != \'1\') $form[\'disp_small\'] = \'0\';
	
	if ($form[\'always_deny\'])
	{
		$form[\'always_deny\'] = preg_replace(\'/\\s/\',\'\',$form[\'always_deny\']);
		$match = preg_match(\'/(^[a-zA-Z0-9])+(([a-zA-Z0-9]+\\,)|([a-zA-Z0-9]))+([a-zA-Z0-9]+$)/\',$form[\'always_deny\']);
	
		if (!$match)
			message($lang_attach[\'Wrong deny\']);
	}
	
	if (preg_match(\'/^[0-9]+$/\', $form[\'small_height\']))
		$form[\'small_height\'] = intval($form[\'small_height\']);
	else
		$form[\'small_height\'] = $forum_config[\'attach_small_height\'];
	
	if (preg_match(\'/^[0-9]+$/\',$form[\'small_width\']))
		$form[\'small_width\'] = intval($form[\'small_width\']);
	else
		$form[\'small_width\'] = $forum_config[\'attach_small_width\'];
	
	$names = explode(\',\', $forum_config[\'attach_icon_name\']);
	$icons = explode(\',\', $forum_config[\'attach_icon_extension\']);
	
	$num_icons = count($icons);
	for ($i = 0; $i < $num_icons; $i++)
	{
		if (!empty($_POST[\'attach_ext_\'.$i]) && !empty($_POST[\'attach_ico_\'.$i]))
		{
			if (!preg_match("/^[a-zA-Z0-9]+$/", forum_trim($_POST[\'attach_ext_\'.$i])) && !preg_match("/^([a-zA-Z0-9]+\\.+(png|gif|jpeg|jpg|ico))+$/", forum_trim($_POST[\'attach_ico_\'.$i])))
				message($lang_attach[\'Wrong icon/name\']);
	
			$icons[$i] = trim($_POST[\'attach_ext_\'.$i]);
			$names[$i] = trim($_POST[\'attach_ico_\'.$i]);
		}
	}
	
	if (isset($_POST[\'add_field_icon\']) && isset($_POST[\'add_field_file\']))
	{
		if (!empty($_POST[\'add_field_icon\']) && !empty($_POST[\'add_field_file\']))
		{
			if (!(preg_match("/^[a-zA-Z0-9]+$/",trim($_POST[\'add_field_icon\'])) && preg_match("/^([a-zA-Z0-9]+\\.+(png|gif|jpeg|jpg|ico))+$/",trim($_POST[\'add_field_file\']))))
				message ($lang_attach[\'Wrong icon/name\']);
	
			$icons[] = trim($_POST[\'add_field_icon\']);
			$names[] = trim($_POST[\'add_field_file\']);
		}
	}
	
	$icons = implode(\',\', $icons);
	$icons = preg_replace(\'/\\,{2,}/\',\',\',$icons);
	$icons = preg_replace(\'/\\,{1,}+$/\',\'\',$icons);
	
	$names = implode(\',\', $names);
	$names = preg_replace(\'/\\,{2,}/\',\',\',$names);
	$names = preg_replace(\'/\\,{1,}+$/\',\'\',$names);
	
	$query = array(
		\'UPDATE\'	=> \'config\',
		\'SET\'		=> \'conf_value=\\\'\'.$forum_db->escape($icons).\'\\\'\',
		\'WHERE\'		=> \'conf_name = \\\'attach_icon_extension\\\'\'
	);
	$result = $forum_db->query_build($query) or error (__FILE__, __LINE__);
	
	$query = array(
		\'UPDATE\'	=> \'config\',
		\'SET\'		=> \'conf_value=\\\'\'.$forum_db->escape($names).\'\\\'\',
		\'WHERE\'		=> \'conf_name=\\\'attach_icon_name\\\'\'
	);
	$result = $forum_db->query_build($query) or error (__FILE__, __LINE__);
	}
	
	if ($section == \'list_attach\')
	{
	$query = array(
		\'SELECT\'	=> \'count(id) as num_attach\',
		\'FROM\'		=> \'attach_files\'
	);
	
	$result = $forum_db->query_build($query) or error(__FILE__, __LINE__);
	
	if ($forum_db->num_rows($result))
	{
		$num_attach = $forum_db->fetch_assoc($result);
		for ($i = 0; $i < $num_attach[\'num_attach\']; $i++)
		{
			if (isset($_POST[\'attach_\'.$i]))
			{
				if (isset($_POST[\'attach_to_post_\'.$i]) && !empty($_POST[\'attach_to_post_\'.$i]))
				{
					$post_id = intval($_POST[\'attach_to_post_\'.$i]);
					$attach_id = intval($_POST[\'attachment_\'.$i]);
					$query = array(
						\'SELECT\'	=> \'id, topic_id, poster_id\',
						\'FROM\'		=> \'posts\',
						\'WHERE\'		=> \'id=\'.$post_id
					);
					$result = $forum_db->query_build($query) or error(__FILE__, __LINE__);
	
	
					if (!$forum_db->num_rows($result))
						message ($lang_attach[\'Wrong post\']);
					$info = $forum_db->fetch_assoc($result);
	
					$query = array(
						\'UPDATE\'	=> \'attach_files\',
						\'SET\'		=> \'post_id=\'.intval($info[\'id\']).\', topic_id=\'.intval($info[\'topic_id\']).\', owner_id=\'.intval($info[\'poster_id\']),
						\'WHERE\'		=> \'id=\'.$attach_id
					);
					$result = $forum_db->query_build($query) or error(__FILE__, __LINE__);
	
					redirect(forum_link($attach_url[\'admin_attachment_manage\']), $lang_attach[\'Attachment added\']);
				}
				else
					message ($lang_attach[\'Wrong post\']);
			}
		}
	}
}

array_pop($GLOBALS[\'ext_info_stack\']);
$ext_info = empty($GLOBALS[\'ext_info_stack\']) ? array() : $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];
',
  ),
  'mi_new_action' => 
  array (
    0 => '$GLOBALS[\'ext_info_stack\'][] = array(
\'id\'				=> \'pun_attachment\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_attachment\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_attachment\',
\'dependencies\'	=> array (
)
);
$ext_info = $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];

if (!$forum_config[\'attach_disable_attach\'] && isset($_GET[\'item\']))
{
	$attach_item = intval($_GET[\'item\']);
	if ($attach_item < 1)
		message($lang_common[\'Bad request\']);

	if (isset($_GET[\'secure_str\']))
	{
		preg_match(\'~(\\d+)f(\\d+)~\', $_GET[\'secure_str\'], $match);
		if (isset($match[0]))
		{
			$query = array(
				\'SELECT\'	=>	\'a.id, post_id, topic_id, owner_id, filename, file_ext, file_mime_type, size, file_path, secure_str\',
				\'FROM\'		=>	\'attach_files AS a\',
				\'JOINS\'		=>	array(
					array(
						\'INNER JOIN\' => \'forums AS f\',
						\'ON\'		=> \'f.id = \'.$match[2]
					),
					array(
						\'LEFT JOIN\'	=> \'forum_perms AS fp\',
						\'ON\'		=> \'(fp.forum_id = f.id AND fp.group_id = \'.$forum_user[\'g_id\'].\')\'
					)
				),
				\'WHERE\'		=> \'a.id = \'.$attach_item.\' AND (fp.read_forum IS NULL OR fp.read_forum = 1) AND secure_str = \\\'\'.$match[0].\'\\\'\'
			);
		}
		else
		{
			preg_match(\'~(\\d+)t(\\d+)~\', $_GET[\'secure_str\'], $match);
			if (isset($match[0]))
			{
				$query = array(
					\'SELECT\'	=>	\'a.id, post_id, topic_id, owner_id, filename, file_ext, file_mime_type, size, file_path, secure_str\',
					\'FROM\'		=>	\'attach_files AS a\',
					\'JOINS\'		=>	array(
						array(
							\'INNER JOIN\'	=> \'topics AS t\',
							\'ON\'		=> \'t.id = \'.$match[2]
						),
						array(
							\'INNER JOIN\'	=> \'forums AS f\',
							\'ON\'		=> \'f.id = t.forum_id\'
						),
						array(
							\'LEFT JOIN\'		=> \'forum_perms AS fp\',
							\'ON\'		=> \'(fp.forum_id = f.id AND fp.group_id = \'.$forum_user[\'g_id\'].\')\'
						)
					),
					\'WHERE\'		=> \'a.id = \'.$attach_item.\' AND (fp.read_forum IS NULL OR fp.read_forum = 1) AND secure_str = \\\'\'.$match[0].\'\\\'\'
				);
			}
			else
				message($lang_common[\'Bad request\']);
		}
		if ($forum_user[\'id\'] != $match[1])
			message($lang_common[\'Bad request\']);
	}
	else
		$query = array(
			\'SELECT\'	=>	\'a.id, post_id, topic_id, owner_id, filename, file_ext, file_mime_type, size, file_path, secure_str\',
			\'FROM\'		=>	\'attach_files AS a\',
			\'JOINS\'		=>	array(
				array(
					\'INNER JOIN\'	=> \'topics AS t\',
					\'ON\'		=> \'t.id = a.topic_id\'
				),
				array(
					\'INNER JOIN\'	=> \'forums AS f\',
					\'ON\'	=> \'f.id = t.forum_id\'
				),
				array(
					\'LEFT JOIN\'		=> \'forum_perms AS fp\',
					\'ON\'		=> \'(fp.forum_id = f.id AND fp.group_id = \'.$forum_user[\'g_id\'].\')\'
				)
			),
			\'WHERE\'		=> \'a.id = \'.$attach_item.\' AND (fp.read_forum IS NULL OR fp.read_forum = 1)\'
		);

	$result = $forum_db->query_build($query) or error(__FILE__, __LINE__);
	if (!$forum_db->num_rows($result))
		message($lang_common[\'Bad request\']);
	$attach_info = $forum_db->fetch_assoc($result);

	$query = array(
		\'SELECT\'	=> \'g_pun_attachment_allow_download\',
		\'FROM\'		=> \'groups\',
		\'WHERE\'		=> \'g_id = \'.$forum_user[\'group_id\']
	);
	$result = $forum_db->query_build($query) or error(__FILE__, __LINE__);

	if (!$forum_db->num_rows($result))
		message($lang_common[\'Bad request\']);

	$perms = $forum_db->fetch_assoc($result);
	if ($forum_user[\'g_id\'] != FORUM_ADMIN && !$perms[\'g_pun_attachment_allow_download\'])
		message($lang_common[\'Bad request\']);
	if (isset($_GET[\'preview\']) && in_array($attach_info[\'file_ext\'], array(\'png\', \'jpg\', \'gif\', \'tiff\')))
	{
		if (file_exists($ext_info[\'path\'].\'/lang/\'.$forum_user[\'language\'].\'/\'.$ext_info[\'id\'].\'.php\'))
			require $ext_info[\'path\'].\'/lang/\'.$forum_user[\'language\'].\'/\'.$ext_info[\'id\'].\'.php\';
		else
			require $ext_info[\'path\'].\'/lang/English/\'.$ext_info[\'id\'].\'.php\';
		require $ext_info[\'path\'].\'/url.php\';

		$forum_page = array();
		$forum_page[\'download_link\'] = !empty($attach_info[\'secure_str\']) ? forum_link($attach_url[\'misc_download_secure\'], array($attach_item, $attach_info[\'secure_str\'])) : forum_link($attach_url[\'misc_download\'], $attach_item);
		$forum_page[\'view_link\'] = !empty($attach_info[\'secure_str\']) ? forum_link($attach_url[\'misc_view_secure\'], array($attach_item, $attach_info[\'secure_str\'])) : forum_link($attach_url[\'misc_view\'], $attach_info[\'id\']);

		// Setup breadcrumbs
		$forum_page[\'crumbs\'] = array(
			array($forum_config[\'o_board_title\'], forum_link($forum_url[\'index\'])),
			$lang_attach[\'Image preview\']
		);

		define(\'FORUM_PAGE\', \'attachment-preview\');
		require FORUM_ROOT.\'header.php\';

		// START SUBST - <!-- forum_main -->
		ob_start();

		?>
		<div class="main-head">
			<h2 class="hn"><span><?php echo $lang_attach[\'Image preview\']; ?></span></h2>
		</div>

		<div class="main-content main-frm">
			<div class="content-head">
				<h2 class="hn"><span><?php echo $attach_info[\'filename\']; ?></span></h2>
			</div>
			<fieldset class="frm-group group1">
				<span class="show-image"><img src="<?php echo $forum_page[\'view_link\']; ?>" alt="<?php echo forum_htmlencode($attach_info[\'filename\']); ?>" /></span>
				<p><?php echo $lang_attach[\'Download:\']; ?> <a href="<?php echo $forum_page[\'download_link\']; ?>"><?php echo forum_htmlencode($attach_info[\'filename\']); ?></a></p>
			</fieldset>
		</div>
		<?php

		$tpl_temp = trim(ob_get_contents());
		$tpl_main = str_replace(\'<!-- forum_main -->\', $tpl_temp, $tpl_main);
		ob_end_clean();
		// END SUBST - <!-- forum_main -->

		require FORUM_ROOT.\'footer.php\';
	}
	else
	{
		$fp = fopen($forum_config[\'attach_basefolder\'].$attach_info[\'file_path\'], \'rb\');

		if (!$fp)
			message($lang_common[\'Bad request\']);
		else
		{
			header(\'Content-Disposition: attachment; filename="\'.$attach_info[\'filename\'].\'"\');
			header(\'Content-Type: \'.$attach_info[\'file_mime_type\']);
			header(\'Pragma: no-cache\');
			header(\'Expires: 0\');
			header(\'Connection: close\');
			header(\'Content-Length: \'.$attach_info[\'size\']);

			fpassthru ($fp);

			if (isset($_GET[\'download\']) && intval($_GET[\'download\']) == 1 && $attach_info[\'owner_id\'] != 0 && $forum_user[\'id\'] != $attach_info[\'owner_id\'])
			{
				$query = array(
					\'UPDATE\'	=> \'attach_files\',
					\'SET\'		=> \'download_counter = download_counter + 1\',
					\'WHERE\'		=> \'id = \'.$attach_item
				);
				$result = $forum_db->query_build($query) or error(__FILE__, __LINE__);
			}
			exit();
		}
	}
}

array_pop($GLOBALS[\'ext_info_stack\']);
$ext_info = empty($GLOBALS[\'ext_info_stack\']) ? array() : $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];
',
    1 => '$GLOBALS[\'ext_info_stack\'][] = array(
\'id\'				=> \'pun_pm\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_pm\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_pm\',
\'dependencies\'	=> array (
)
);
$ext_info = $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];

if ($action == \'pun_pm_send\' && !$forum_user[\'is_guest\'])
{
	if(!defined(\'PUN_PM_FUNCTIONS_LOADED\'))
		require $ext_info[\'path\'].\'/functions.php\';

	if (!isset($lang_pun_pm))
	{
		if (file_exists($ext_info[\'path\'].\'/lang/\'.$forum_user[\'language\'].\'/\'.$ext_info[\'id\'].\'.php\'))
			include $ext_info[\'path\'].\'/lang/\'.$forum_user[\'language\'].\'/\'.$ext_info[\'id\'].\'.php\';
		else
			include $ext_info[\'path\'].\'/lang/English/\'.$ext_info[\'id\'].\'.php\';
	}

	$pun_pm_body = isset($_POST[\'req_message\']) ? $_POST[\'req_message\'] : \'\';
	$pun_pm_subject = isset($_POST[\'pm_subject\']) ? $_POST[\'pm_subject\'] : \'\';
	$pun_pm_receiver_username = isset($_POST[\'pm_receiver\']) ? $_POST[\'pm_receiver\'] : \'\';
	$pun_pm_message_id = isset($_POST[\'message_id\']) ? (int) $_POST[\'message_id\'] : false;

	if (isset($_POST[\'send_action\']) && in_array($_POST[\'send_action\'], array(\'send\', \'draft\', \'delete\', \'preview\')))
		$pun_pm_send_action = $_POST[\'send_action\'];
	elseif (isset($_POST[\'pm_draft\']))
		$pun_pm_send_action = \'draft\';
	elseif (isset($_POST[\'pm_send\']))
		$pun_pm_send_action = \'send\';
	elseif (isset($_POST[\'pm_delete\']))
		$pun_pm_send_action = \'delete\';
	else
		$pun_pm_send_action = \'preview\';

	($hook = get_hook(\'pun_pm_after_send_action_set\')) ? eval($hook) : null;

	if ($pun_pm_send_action == \'draft\')
	{
		// Try to save the message as draft
		// Inside this function will be a redirect, if everything is ok
		$pun_pm_errors = pun_pm_save_message($pun_pm_body, $pun_pm_subject, $pun_pm_receiver_username, $pun_pm_message_id);
		// Remember $pun_pm_message_id = false; inside this function if $pun_pm_message_id is incorrect

		// Well... Go processing errors

		// We need no preview
		$pun_pm_msg_preview = false;
	}
	elseif ($pun_pm_send_action == \'send\')
	{
		// Try to send the message
		// Inside this function will be a redirect, if everything is ok
		$pun_pm_errors = pun_pm_send_message($pun_pm_body, $pun_pm_subject, $pun_pm_receiver_username, $pun_pm_message_id);
		// Remember $pun_pm_message_id = false; inside this function if $pun_pm_message_id is incorrect

		// Well... Go processing errors

		// We need no preview
		$pun_pm_msg_preview = false;
	}
	elseif ($pun_pm_send_action == \'delete\' && $pun_pm_message_id !== false)
	{
		pun_pm_delete_from_outbox(array($pun_pm_message_id));
		redirect(forum_link($forum_url[\'pun_pm_outbox\']), $lang_pun_pm[\'Message deleted\']);
	}
	elseif ($pun_pm_send_action == \'preview\')
	{
		// Preview message
		$pun_pm_errors = array();
		$pun_pm_msg_preview = pun_pm_preview($pun_pm_receiver_username, $pun_pm_subject, $pun_pm_body, $pun_pm_errors);
	}

	($hook = get_hook(\'pun_pm_new_send_action\')) ? eval($hook) : null;

	$pun_pm_page_text = pun_pm_send_form($pun_pm_receiver_username, $pun_pm_subject, $pun_pm_body, $pun_pm_message_id, false, false, $pun_pm_msg_preview);

	// Setup navigation menu
	$forum_page[\'main_menu\'] = array(
		\'inbox\'		=> \'<li class="first-item"><a href="\'.forum_link($forum_url[\'pun_pm_inbox\']).\'"><span>\'.$lang_pun_pm[\'Inbox\'].\'</span></a></li>\',
		\'outbox\'	=> \'<li><a href="\'.forum_link($forum_url[\'pun_pm_outbox\']).\'"><span>\'.$lang_pun_pm[\'Outbox\'].\'</span></a></li>\',
		\'write\'		=> \'<li class="active"><a href="\'.forum_link($forum_url[\'pun_pm_write\']).\'"><span>\'.$lang_pun_pm[\'Compose message\'].\'</span></a></li>\',
	);

	// Setup breadcrumbs
	$forum_page[\'crumbs\'] = array(
		array($forum_config[\'o_board_title\'], forum_link($forum_url[\'index\'])),
		array($lang_pun_pm[\'Private messages\'], forum_link($forum_url[\'pun_pm\'])),
		array($lang_pun_pm[\'Compose message\'], forum_link($forum_url[\'pun_pm_write\']))
	);

	($hook = get_hook(\'pun_pm_pre_send_output\')) ? eval($hook) : null;

	define(\'FORUM_PAGE\', \'pun_pm-write\');
	require FORUM_ROOT.\'header.php\';

	// START SUBST - <!-- forum_main -->
	ob_start();

	echo $pun_pm_page_text;

	$tpl_temp = trim(ob_get_contents());
	$tpl_main = str_replace(\'<!-- forum_main -->\', $tpl_temp, $tpl_main);
	ob_end_clean();
	// END SUBST - <!-- forum_main -->

	require FORUM_ROOT.\'footer.php\';
}

$section = isset($_GET[\'section\']) ? $_GET[\'section\'] : null;

if ($section == \'pun_pm\' && !$forum_user[\'is_guest\'])
{
	if (!isset($lang_pun_pm))
	{
		if (file_exists($ext_info[\'path\'].\'/lang/\'.$forum_user[\'language\'].\'/\'.$ext_info[\'id\'].\'.php\'))
			include $ext_info[\'path\'].\'/lang/\'.$forum_user[\'language\'].\'/\'.$ext_info[\'id\'].\'.php\';
		else
			include $ext_info[\'path\'].\'/lang/English/\'.$ext_info[\'id\'].\'.php\';
	}

	if(!defined(\'PUN_PM_FUNCTIONS_LOADED\'))
		require $ext_info[\'path\'].\'/functions.php\';

	$pun_pm_page = isset($_GET[\'pmpage\']) ? $_GET[\'pmpage\'] : \'\';

	($hook = get_hook(\'pun_pm_pre_page_building\')) ? eval($hook) : null;

	// pun_pm_get_page() performs everything :)
	// Remember $pun_pm_page correction inside pun_pm_get_page() if this variable is incorrect
	$pun_pm_page_text = pun_pm_get_page($pun_pm_page);

	// Setup navigation menu
	$forum_page[\'main_menu\'] = array(
		\'inbox\'		=> \'<li class="first-item\'.($pun_pm_page == \'inbox\' ? \' active\' : \'\').\'"><a href="\'.forum_link($forum_url[\'pun_pm_inbox\']).\'"><span>\'.$lang_pun_pm[\'Inbox\'].\'</span></a></li>\',
		\'outbox\'	=> \'<li\'.(($pun_pm_page == \'outbox\') ? \' class="active"\' : \'\').\'><a href="\'.forum_link($forum_url[\'pun_pm_outbox\']).\'"><span>\'.$lang_pun_pm[\'Outbox\'].\'</span></a></li>\',
		\'write\'		=> \'<li\'.(($pun_pm_page == \'write\') ? \' class="active"\' : \'\').\'><a href="\'.forum_link($forum_url[\'pun_pm_write\']).\'"><span>\'.$lang_pun_pm[\'Compose message\'].\'</span></a></li>\',
	);

	// Setup breadcrumbs
	$forum_page[\'crumbs\'] = array(
		array($forum_config[\'o_board_title\'], forum_link($forum_url[\'index\'])),
		array($lang_pun_pm[\'Private messages\'], forum_link($forum_url[\'pun_pm\']))
	);
	if ($pun_pm_page == \'inbox\')
		$forum_page[\'crumbs\'][] = array($lang_pun_pm[\'Inbox\'], forum_link($forum_url[\'pun_pm_inbox\']));
	else if ($pun_pm_page == \'outbox\')
		$forum_page[\'crumbs\'][] = array($lang_pun_pm[\'Outbox\'], forum_link($forum_url[\'pun_pm_outbox\']));
	else if ($pun_pm_page == \'write\')
		$forum_page[\'crumbs\'][] = array($lang_pun_pm[\'Compose message\'], forum_link($forum_url[\'pun_pm_write\']));

	($hook = get_hook(\'pun_pm_pre_page_output\')) ? eval($hook) : null;

	define(\'FORUM_PAGE\', \'pun_pm-\'.$pun_pm_page);
	require FORUM_ROOT.\'header.php\';

	// START SUBST - <!-- forum_main -->
	ob_start();

	echo $pun_pm_page_text;

	$tpl_temp = trim(ob_get_contents());
	$tpl_main = str_replace(\'<!-- forum_main -->\', $tpl_temp, $tpl_main);
	ob_end_clean();
	// END SUBST - <!-- forum_main -->

	require FORUM_ROOT.\'footer.php\';
}

array_pop($GLOBALS[\'ext_info_stack\']);
$ext_info = empty($GLOBALS[\'ext_info_stack\']) ? array() : $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];
',
  ),
  'dl_start' => 
  array (
    0 => '$GLOBALS[\'ext_info_stack\'][] = array(
\'id\'				=> \'pun_attachment\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_attachment\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_attachment\',
\'dependencies\'	=> array (
)
);
$ext_info = $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];

require $ext_info[\'path\'].\'/include/attach_func.php\';
if (file_exists($ext_info[\'path\'].\'/lang/\'.$forum_user[\'language\'].\'/\'.$ext_info[\'id\'].\'.php\'))
	require $ext_info[\'path\'].\'/lang/\'.$forum_user[\'language\'].\'/\'.$ext_info[\'id\'].\'.php\';
else
	require $ext_info[\'path\'].\'/lang/English/\'.$ext_info[\'id\'].\'.php\';

array_pop($GLOBALS[\'ext_info_stack\']);
$ext_info = empty($GLOBALS[\'ext_info_stack\']) ? array() : $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];
',
  ),
  'mr_start' => 
  array (
    0 => '$GLOBALS[\'ext_info_stack\'][] = array(
\'id\'				=> \'pun_attachment\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_attachment\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_attachment\',
\'dependencies\'	=> array (
)
);
$ext_info = $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];

require $ext_info[\'path\'].\'/include/attach_func.php\';
if (file_exists($ext_info[\'path\'].\'/lang/\'.$forum_user[\'language\'].\'/\'.$ext_info[\'id\'].\'.php\'))
	require $ext_info[\'path\'].\'/lang/\'.$forum_user[\'language\'].\'/\'.$ext_info[\'id\'].\'.php\';
else
	require $ext_info[\'path\'].\'/lang/English/\'.$ext_info[\'id\'].\'.php\';

array_pop($GLOBALS[\'ext_info_stack\']);
$ext_info = empty($GLOBALS[\'ext_info_stack\']) ? array() : $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];
',
    1 => '$GLOBALS[\'ext_info_stack\'][] = array(
\'id\'				=> \'pun_move_posts\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_move_posts\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_move_posts\',
\'dependencies\'	=> array (
)
);
$ext_info = $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];

if (file_exists($ext_info[\'path\'].\'/lang/\'.$forum_user[\'language\'].\'/\'.$ext_info[\'id\'].\'.php\'))
				require $ext_info[\'path\'].\'/lang/\'.$forum_user[\'language\'].\'/\'.$ext_info[\'id\'].\'.php\';
			else
				require $ext_info[\'path\'].\'/lang/English/\'.$ext_info[\'id\'].\'.php\';

array_pop($GLOBALS[\'ext_info_stack\']);
$ext_info = empty($GLOBALS[\'ext_info_stack\']) ? array() : $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];
',
  ),
  'dl_qr_get_post_info' => 
  array (
    0 => '$GLOBALS[\'ext_info_stack\'][] = array(
\'id\'				=> \'pun_attachment\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_attachment\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_attachment\',
\'dependencies\'	=> array (
)
);
$ext_info = $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];

if (!$forum_config[\'attach_disable_attach\'])
{
	$query[\'SELECT\'] .= \', g_pun_attachment_allow_upload, g_pun_attachment_upload_max_size, g_pun_attachment_files_per_post, g_pun_attachment_disallowed_extensions, g_pun_attachment_allow_delete_own, g_pun_attachment_allow_delete\';
	$query[\'JOINS\'][] = array(\'LEFT JOIN\' => \'groups AS g\', \'ON\' => \'g.g_id = \'.$forum_user[\'g_id\']);
}

array_pop($GLOBALS[\'ext_info_stack\']);
$ext_info = empty($GLOBALS[\'ext_info_stack\']) ? array() : $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];
',
  ),
  'dl_form_submitted' => 
  array (
    0 => '$GLOBALS[\'ext_info_stack\'][] = array(
\'id\'				=> \'pun_attachment\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_attachment\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_attachment\',
\'dependencies\'	=> array (
)
);
$ext_info = $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];

if (!$forum_config[\'attach_disable_attach\'])
{
	$attach_query = array(
		\'SELECT\'	=>	\'id, file_path, owner_id\',
		\'FROM\'		=>	\'attach_files\'
	);
	$attach_query[\'WHERE\'] = $cur_post[\'is_topic\'] ? \'post_id != 0 AND topic_id = \'.$cur_post[\'tid\'] : \'post_id = \'.$id;
}

array_pop($GLOBALS[\'ext_info_stack\']);
$ext_info = empty($GLOBALS[\'ext_info_stack\']) ? array() : $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];
',
  ),
  'dl_topic_deleted_pre_redirect' => 
  array (
    0 => '$GLOBALS[\'ext_info_stack\'][] = array(
\'id\'				=> \'pun_attachment\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_attachment\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_attachment\',
\'dependencies\'	=> array (
)
);
$ext_info = $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];

if (!$forum_config[\'attach_disable_attach\'])
	remove_attachments($attach_query, $cur_post);

array_pop($GLOBALS[\'ext_info_stack\']);
$ext_info = empty($GLOBALS[\'ext_info_stack\']) ? array() : $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];
',
  ),
  'dl_post_deleted_pre_redirect' => 
  array (
    0 => '$GLOBALS[\'ext_info_stack\'][] = array(
\'id\'				=> \'pun_attachment\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_attachment\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_attachment\',
\'dependencies\'	=> array (
)
);
$ext_info = $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];

if (!$forum_config[\'attach_disable_attach\'])
	remove_attachments($attach_query, $cur_post);

array_pop($GLOBALS[\'ext_info_stack\']);
$ext_info = empty($GLOBALS[\'ext_info_stack\']) ? array() : $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];
',
  ),
  'mr_qr_get_forum_data' => 
  array (
    0 => '$GLOBALS[\'ext_info_stack\'][] = array(
\'id\'				=> \'pun_attachment\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_attachment\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_attachment\',
\'dependencies\'	=> array (
)
);
$ext_info = $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];

if (!$forum_config[\'attach_disable_attach\'])
{
	$query[\'SELECT\'] .= \', g_pun_attachment_allow_upload, g_pun_attachment_upload_max_size, g_pun_attachment_files_per_post, g_pun_attachment_disallowed_extensions, g_pun_attachment_allow_delete_own, g_pun_attachment_allow_delete\';
	$query[\'JOINS\'][] = array(\'LEFT JOIN\' => \'groups AS g\', \'ON\' => \'g.g_id = \'.$forum_user[\'g_id\']);
}

array_pop($GLOBALS[\'ext_info_stack\']);
$ext_info = empty($GLOBALS[\'ext_info_stack\']) ? array() : $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];
',
  ),
  'mr_confirm_delete_posts_pre_redirect' => 
  array (
    0 => '$GLOBALS[\'ext_info_stack\'][] = array(
\'id\'				=> \'pun_attachment\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_attachment\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_attachment\',
\'dependencies\'	=> array (
)
);
$ext_info = $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];

if (!$forum_config[\'attach_disable_attach\'])
{
	$attach_query = array(
		\'SELECT\'	=>	\'id, file_path, owner_id\',
		\'FROM\'		=>	\'attach_files\',
		\'WHERE\'		=>	isset($posts) ? \'post_id IN(\'.implode(\',\', $posts).\')\' : \'topic_id IN(\'.implode(\',\', $topics).\')\'
	);
	$forum_page[\'is_admmod\'] = true;
	remove_attachments($attach_query, $cur_forum);
}

array_pop($GLOBALS[\'ext_info_stack\']);
$ext_info = empty($GLOBALS[\'ext_info_stack\']) ? array() : $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];
',
  ),
  'mr_confirm_delete_topics_pre_redirect' => 
  array (
    0 => '$GLOBALS[\'ext_info_stack\'][] = array(
\'id\'				=> \'pun_attachment\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_attachment\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_attachment\',
\'dependencies\'	=> array (
)
);
$ext_info = $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];

if (!$forum_config[\'attach_disable_attach\'])
{
	$attach_query = array(
		\'SELECT\'	=>	\'id, file_path, owner_id\',
		\'FROM\'		=>	\'attach_files\',
		\'WHERE\'		=>	isset($posts) ? \'post_id IN(\'.implode(\',\', $posts).\')\' : \'topic_id IN(\'.implode(\',\', $topics).\')\'
	);
	$forum_page[\'is_admmod\'] = true;
	remove_attachments($attach_query, $cur_forum);
}

array_pop($GLOBALS[\'ext_info_stack\']);
$ext_info = empty($GLOBALS[\'ext_info_stack\']) ? array() : $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];
',
  ),
  'mr_confirm_split_posts_pre_redirect' => 
  array (
    0 => '$GLOBALS[\'ext_info_stack\'][] = array(
\'id\'				=> \'pun_attachment\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_attachment\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_attachment\',
\'dependencies\'	=> array (
)
);
$ext_info = $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];

$attach_query = array(
	\'UPDATE\'	=>	\'attach_files\',
	\'SET\'		=>	\'topic_id=\'.$new_tid,
	\'WHERE\'		=>	\'post_id IN (\'.implode(\',\', $posts).\')\'
);
$forum_db->query_build($attach_query) or error(__FILE__, __LINE__);

array_pop($GLOBALS[\'ext_info_stack\']);
$ext_info = empty($GLOBALS[\'ext_info_stack\']) ? array() : $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];
',
  ),
  'mr_confirm_merge_topics_pre_redirect' => 
  array (
    0 => '$GLOBALS[\'ext_info_stack\'][] = array(
\'id\'				=> \'pun_attachment\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_attachment\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_attachment\',
\'dependencies\'	=> array (
)
);
$ext_info = $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];

$attach_query = array(
	\'UPDATE\'	=>	\'attach_files\',
	\'SET\'		=>	\'topic_id=\'.$merge_to_tid,
	\'WHERE\'		=>	\'topic_id IN(\'.implode(\',\', $topics).\')\'
);
$forum_db->query_build($attach_query) or error(__FILE__, __LINE__);

array_pop($GLOBALS[\'ext_info_stack\']);
$ext_info = empty($GLOBALS[\'ext_info_stack\']) ? array() : $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];
',
  ),
  'co_common' => 
  array (
    0 => '$GLOBALS[\'ext_info_stack\'][] = array(
\'id\'				=> \'pun_attachment\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_attachment\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_attachment\',
\'dependencies\'	=> array (
)
);
$ext_info = $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];

$pun_extensions_used = array_merge(isset($pun_extensions_used) ? $pun_extensions_used : array(), array($ext_info[\'id\']));

array_pop($GLOBALS[\'ext_info_stack\']);
$ext_info = empty($GLOBALS[\'ext_info_stack\']) ? array() : $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];
',
    1 => '$GLOBALS[\'ext_info_stack\'][] = array(
\'id\'				=> \'pun_bbcode\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_bbcode\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_bbcode\',
\'dependencies\'	=> array (
)
);
$ext_info = $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];

$pun_extensions_used = array_merge(isset($pun_extensions_used) ? $pun_extensions_used : array(), array($ext_info[\'id\']));

array_pop($GLOBALS[\'ext_info_stack\']);
$ext_info = empty($GLOBALS[\'ext_info_stack\']) ? array() : $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];
',
    2 => '$GLOBALS[\'ext_info_stack\'][] = array(
\'id\'				=> \'pun_pm\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_pm\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_pm\',
\'dependencies\'	=> array (
)
);
$ext_info = $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];

$pun_extensions_used = array_merge(isset($pun_extensions_used) ? $pun_extensions_used : array(), array($ext_info[\'id\']));

array_pop($GLOBALS[\'ext_info_stack\']);
$ext_info = empty($GLOBALS[\'ext_info_stack\']) ? array() : $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];
',
    3 => '$GLOBALS[\'ext_info_stack\'][] = array(
\'id\'				=> \'pun_quote\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_quote\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_quote\',
\'dependencies\'	=> array (
)
);
$ext_info = $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];

$pun_extensions_used = array_merge(isset($pun_extensions_used) ? $pun_extensions_used : array(), array($ext_info[\'id\']));

array_pop($GLOBALS[\'ext_info_stack\']);
$ext_info = empty($GLOBALS[\'ext_info_stack\']) ? array() : $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];
',
  ),
  'pun_pm_fn_send_form_pre_output' => 
  array (
    0 => '$GLOBALS[\'ext_info_stack\'][] = array(
\'id\'				=> \'pun_bbcode\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_bbcode\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_bbcode\',
\'dependencies\'	=> array (
)
);
$ext_info = $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];

if ($forum_user[\'pun_bbcode_enabled\'])
{
	global $smilies, $base_url;
	if (!defined(\'FORUM_PARSER_LOADED\'))
		require FORUM_ROOT.\'include/parser.php\';

	$forum_head[\'style_pun_bbcode\'] = \'<link rel="stylesheet" type="text/css" media="screen" href="\'.$ext_info[\'url\'].\'/styles.css" />\';
	$forum_head[\'js_pun_bbcode\'] = \'<script type="text/javascript" src="\'.$ext_info[\'url\'].\'/scripts.js"></script>\';
}

array_pop($GLOBALS[\'ext_info_stack\']);
$ext_info = empty($GLOBALS[\'ext_info_stack\']) ? array() : $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];
',
  ),
  'po_pre_post_contents' => 
  array (
    0 => '$GLOBALS[\'ext_info_stack\'][] = array(
\'id\'				=> \'pun_bbcode\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_bbcode\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_bbcode\',
\'dependencies\'	=> array (
)
);
$ext_info = $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];

if ($forum_user[\'pun_bbcode_enabled\']) {
	define(\'PUN_BBCODE_BAR_INCLUDE\', 1);
	echo "\\t\\t\\t".\'<div class="sf-set" id="pun_bbcode_bar"></div>\'."\\n";
}

array_pop($GLOBALS[\'ext_info_stack\']);
$ext_info = empty($GLOBALS[\'ext_info_stack\']) ? array() : $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];
',
  ),
  'vt_quickpost_pre_message_box' => 
  array (
    0 => '$GLOBALS[\'ext_info_stack\'][] = array(
\'id\'				=> \'pun_bbcode\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_bbcode\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_bbcode\',
\'dependencies\'	=> array (
)
);
$ext_info = $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];

if ($forum_user[\'pun_bbcode_enabled\']) {
	define(\'PUN_BBCODE_BAR_INCLUDE\', 1);
	echo "\\t\\t\\t".\'<div class="sf-set" id="pun_bbcode_bar"></div>\'."\\n";
}

array_pop($GLOBALS[\'ext_info_stack\']);
$ext_info = empty($GLOBALS[\'ext_info_stack\']) ? array() : $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];
',
  ),
  'ed_pre_message_box' => 
  array (
    0 => '$GLOBALS[\'ext_info_stack\'][] = array(
\'id\'				=> \'pun_bbcode\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_bbcode\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_bbcode\',
\'dependencies\'	=> array (
)
);
$ext_info = $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];

if ($forum_user[\'pun_bbcode_enabled\']) {
	define(\'PUN_BBCODE_BAR_INCLUDE\', 1);
	echo "\\t\\t\\t".\'<div class="sf-set" id="pun_bbcode_bar"></div>\'."\\n";
}

array_pop($GLOBALS[\'ext_info_stack\']);
$ext_info = empty($GLOBALS[\'ext_info_stack\']) ? array() : $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];
',
  ),
  'pun_pm_fn_send_form_pre_textarea_output' => 
  array (
    0 => '$GLOBALS[\'ext_info_stack\'][] = array(
\'id\'				=> \'pun_bbcode\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_bbcode\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_bbcode\',
\'dependencies\'	=> array (
)
);
$ext_info = $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];

if ($forum_user[\'pun_bbcode_enabled\']) {
	define(\'PUN_BBCODE_BAR_INCLUDE\', 1);
	echo "\\t\\t\\t".\'<div class="sf-set" id="pun_bbcode_bar"></div>\'."\\n";
}

array_pop($GLOBALS[\'ext_info_stack\']);
$ext_info = empty($GLOBALS[\'ext_info_stack\']) ? array() : $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];
',
  ),
  'ft_about_pre_copyright' => 
  array (
    0 => '$GLOBALS[\'ext_info_stack\'][] = array(
\'id\'				=> \'pun_bbcode\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_bbcode\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_bbcode\',
\'dependencies\'	=> array (
)
);
$ext_info = $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];

if (defined(\'PUN_BBCODE_BAR_INCLUDE\')) {
	include $ext_info[\'path\'].\'/bar.php\';
?>
<script type="text/javascript"><!--
var pun_bbcode_bar = document.getElementById("pun_bbcode_bar");
if (pun_bbcode_bar) {
	pun_bbcode_bar.innerHTML = "<?php echo pun_bbcode_bar(); ?>";
	pun_bbcode_bar.style.display = "block";
	pun_bbcode_bar.style.visibility = "visible";
}
--></script>
<?php
}

array_pop($GLOBALS[\'ext_info_stack\']);
$ext_info = empty($GLOBALS[\'ext_info_stack\']) ? array() : $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];
',
    1 => '$GLOBALS[\'ext_info_stack\'][] = array(
\'id\'				=> \'pun_quote\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_quote\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_quote\',
\'dependencies\'	=> array (
)
);
$ext_info = $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];

if (FORUM_PAGE == \'viewtopic\' && !empty($pun_quote_js_arrays))
				echo \'<script type="text/javascript"><!--\'."\\n".\'var pun_quote_posts = new Array(\'.$forum_page[\'item_count\'].\');\'."\\n".\'var pun_quote_authors = new Array(\'.$forum_page[\'item_count\'].\');\'."\\n".$pun_quote_js_arrays.\'--></script>\'."\\n";

array_pop($GLOBALS[\'ext_info_stack\']);
$ext_info = empty($GLOBALS[\'ext_info_stack\']) ? array() : $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];
',
  ),
  'pf_change_details_settings_validation' => 
  array (
    0 => '$GLOBALS[\'ext_info_stack\'][] = array(
\'id\'				=> \'pun_bbcode\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_bbcode\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_bbcode\',
\'dependencies\'	=> array (
)
);
$ext_info = $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];

if (!isset($_POST[\'form\'][\'pun_bbcode_enabled\']) || $_POST[\'form\'][\'pun_bbcode_enabled\'] != \'1\')
	$form[\'pun_bbcode_enabled\'] = \'0\';
else
	$form[\'pun_bbcode_enabled\'] = \'1\';

if (!isset($_POST[\'form\'][\'pun_bbcode_use_buttons\']) || $_POST[\'form\'][\'pun_bbcode_use_buttons\'] != \'1\')
	$form[\'pun_bbcode_use_buttons\'] = \'0\';
else
	$form[\'pun_bbcode_use_buttons\'] = \'1\';

array_pop($GLOBALS[\'ext_info_stack\']);
$ext_info = empty($GLOBALS[\'ext_info_stack\']) ? array() : $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];
',
    1 => '$GLOBALS[\'ext_info_stack\'][] = array(
\'id\'				=> \'pun_pm\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_pm\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_pm\',
\'dependencies\'	=> array (
)
);
$ext_info = $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];

// Validate option \'quote beginning of message\'
if (!isset($_POST[\'form\'][\'pun_pm_long_subject\']) || $_POST[\'form\'][\'pun_pm_long_subject\'] != \'1\')
	$form[\'pun_pm_long_subject\'] = \'0\';
else
	$form[\'pun_pm_long_subject\'] = \'1\';

array_pop($GLOBALS[\'ext_info_stack\']);
$ext_info = empty($GLOBALS[\'ext_info_stack\']) ? array() : $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];
',
  ),
  'pf_change_details_settings_email_fieldset_end' => 
  array (
    0 => '$GLOBALS[\'ext_info_stack\'][] = array(
\'id\'				=> \'pun_bbcode\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_bbcode\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_bbcode\',
\'dependencies\'	=> array (
)
);
$ext_info = $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];

if (file_exists($ext_info[\'path\'].\'/lang/\'.$forum_user[\'language\'].\'/\'.$ext_info[\'id\'].\'.php\'))
	include $ext_info[\'path\'].\'/lang/\'.$forum_user[\'language\'].\'/\'.$ext_info[\'id\'].\'.php\';
else
	include $ext_info[\'path\'].\'/lang/English/pun_bbcode.php\';

$forum_page[\'item_count\'] = 0;

?>
			<fieldset class="frm-group group<?php echo ++$forum_page[\'group_count\'] ?>">
				<div class="sf-set set<?php echo ++$forum_page[\'item_count\'] ?>">
					<div class="sf-box checkbox">
						<span class="fld-input"><input type="checkbox" id="fld<?php echo ++$forum_page[\'fld_count\'] ?>" name="form[pun_bbcode_enabled]" value="1"<?php if ($user[\'pun_bbcode_enabled\'] == \'1\') echo \' checked="checked"\' ?> /></span>
						<label for="fld<?php echo $forum_page[\'fld_count\'] ?>"><span><?php echo $lang_pun_bbcode[\'Pun BBCode Bar\'] ?></span> <?php echo $lang_pun_bbcode[\'Notice BBCode Bar\'] ?></label>
					</div>
				</div>
				<div class="sf-set set<?php echo ++$forum_page[\'item_count\'] ?>">
					<div class="sf-box checkbox">
						<span class="fld-input"><input type="checkbox" id="fld<?php echo ++$forum_page[\'fld_count\'] ?>" name="form[pun_bbcode_use_buttons]" value="1"<?php if ($user[\'pun_bbcode_use_buttons\'] == \'1\') echo \' checked="checked"\' ?> /></span>
						<label for="fld<?php echo $forum_page[\'fld_count\'] ?>"><span><?php echo $lang_pun_bbcode[\'BBCode Graphical\'] ?></span> <?php echo $lang_pun_bbcode[\'BBCode Graphical buttons\'] ?></label>
					</div>
				</div>
			</fieldset>
<?php

array_pop($GLOBALS[\'ext_info_stack\']);
$ext_info = empty($GLOBALS[\'ext_info_stack\']) ? array() : $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];
',
    1 => '$GLOBALS[\'ext_info_stack\'][] = array(
\'id\'				=> \'pun_pm\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_pm\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_pm\',
\'dependencies\'	=> array (
)
);
$ext_info = $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];

// Per-user option \'quote beginning of message\'
if ($forum_config[\'p_message_bbcode\'] == \'1\')
{
	if (!isset($lang_pun_pm))
	{
		if (file_exists($ext_info[\'path\'].\'/lang/\'.$forum_user[\'language\'].\'/\'.$ext_info[\'id\'].\'.php\'))
			include $ext_info[\'path\'].\'/lang/\'.$forum_user[\'language\'].\'/\'.$ext_info[\'id\'].\'.php\';
		else
			include $ext_info[\'path\'].\'/lang/English/\'.$ext_info[\'id\'].\'.php\';
	}

	$forum_page[\'item_count\'] = 0;

?>
			<fieldset class="frm-group group<?php echo ++$forum_page[\'group_count\'] ?>">
				<legend class="group-legend"><strong><?php echo $lang_pun_pm[\'PM settings\'] ?></strong></legend>
				<fieldset class="mf-set set<?php echo ++$forum_page[\'item_count\'] ?>">
					<legend><span><?php echo $lang_pun_pm[\'Private messages\'] ?></span></legend>
					<div class="mf-box">
						<div class="mf-item">
							<span class="fld-input"><input type="checkbox" id="fld<?php echo ++$forum_page[\'fld_count\'] ?>" name="form[pun_pm_long_subject]" value="1"<?php if ($user[\'pun_pm_long_subject\'] == \'1\') echo \' checked="checked"\' ?> /></span>
							<label for="fld<?php echo $forum_page[\'fld_count\'] ?>"><?php echo $lang_pun_pm[\'Begin message quote\'] ?></label>
						</div>
					</div>
				</fieldset>
<?php ($hook = get_hook(\'pun_pm_pf_change_details_settings_pre_pm_settings_fieldset_end\')) ? eval($hook) : null; ?>
			</fieldset>
<?php
}
else
	echo "\\t\\t\\t".\'<input type="hidden" name="form[pun_pm_long_subject]" value="\'.$user[\'pun_pm_long_subject\'].\'" />\'."\\n";

array_pop($GLOBALS[\'ext_info_stack\']);
$ext_info = empty($GLOBALS[\'ext_info_stack\']) ? array() : $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];
',
  ),
  'mr_post_actions_pre_mod_options' => 
  array (
    0 => '$GLOBALS[\'ext_info_stack\'][] = array(
\'id\'				=> \'pun_move_posts\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_move_posts\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_move_posts\',
\'dependencies\'	=> array (
)
);
$ext_info = $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];

$forum_page[\'mod_options\'] = array_merge(array(\'<span class="submit first-item"><input type="submit" name="move_posts" value="\'.$lang_pun_move_posts[\'Move selected\'].\'" /></span>\'), $forum_page[\'mod_options\']);

array_pop($GLOBALS[\'ext_info_stack\']);
$ext_info = empty($GLOBALS[\'ext_info_stack\']) ? array() : $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];
',
  ),
  'mr_post_actions_selected' => 
  array (
    0 => '$GLOBALS[\'ext_info_stack\'][] = array(
\'id\'				=> \'pun_move_posts\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_move_posts\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_move_posts\',
\'dependencies\'	=> array (
)
);
$ext_info = $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];

if (file_exists($ext_info[\'path\'].\'/move_posts.php\'))
				require $ext_info[\'path\'].\'/move_posts.php\';

array_pop($GLOBALS[\'ext_info_stack\']);
$ext_info = empty($GLOBALS[\'ext_info_stack\']) ? array() : $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];
',
  ),
  'aop_features_avatars_fieldset_end' => 
  array (
    0 => '$GLOBALS[\'ext_info_stack\'][] = array(
\'id\'				=> \'pun_pm\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_pm\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_pm\',
\'dependencies\'	=> array (
)
);
$ext_info = $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];

// Admin options
if (!isset($lang_pun_pm))
{
	if (file_exists($ext_info[\'path\'].\'/lang/\'.$forum_user[\'language\'].\'/\'.$ext_info[\'id\'].\'.php\'))
		include $ext_info[\'path\'].\'/lang/\'.$forum_user[\'language\'].\'/\'.$ext_info[\'id\'].\'.php\';
	else
		include $ext_info[\'path\'].\'/lang/English/\'.$ext_info[\'id\'].\'.php\';
}

$forum_page[\'group_count\'] = $forum_page[\'item_count\'] = 0;

?>
			<div class="content-head">
				<h2 class="hn"><span><?php echo $lang_pun_pm[\'Features title\'] ?></span></h2>
			</div>
			<fieldset class="frm-group group<?php echo ++$forum_page[\'group_count\'] ?>">
				<legend class="group-legend"><span><?php echo $lang_pun_pm[\'PM settings\'] ?></span></legend>
				<div class="sf-set set<?php echo ++$forum_page[\'item_count\'] ?>">
					<div class="sf-box text">
						<label for="fld<?php echo ++$forum_page[\'fld_count\'] ?>"><span><?php echo $lang_pun_pm[\'Inbox limit\'] ?></span><small><?php echo $lang_pun_pm[\'Inbox limit info\'] ?></small></label><br />
						<span class="fld-input"><input type="text" id="fld<?php echo $forum_page[\'fld_count\'] ?>" name="form[pun_pm_inbox_size]" size="6" maxlength="6" value="<?php echo $forum_config[\'o_pun_pm_inbox_size\'] ?>" /></span>
					</div>
				</div>
				<div class="sf-set set<?php echo ++$forum_page[\'item_count\'] ?>">
					<div class="sf-box text">
						<label for="fld<?php echo ++$forum_page[\'fld_count\'] ?>"><span><?php echo $lang_pun_pm[\'Outbox limit\'] ?></span><small><?php echo $lang_pun_pm[\'Outbox limit info\'] ?></small></label><br />
						<span class="fld-input"><input type="text" id="fld<?php echo $forum_page[\'fld_count\'] ?>" name="form[pun_pm_outbox_size]" size="6" maxlength="6" value="<?php echo $forum_config[\'o_pun_pm_outbox_size\'] ?>" /></span>
					</div>
				</div>
				<fieldset class="mf-set set<?php echo ++$forum_page[\'item_count\'] ?>">
					<legend><span><?php echo $lang_pun_pm[\'Navigation links\'] ?></span></legend>
					<div class="mf-box">
						<div class="mf-item">
							<span class="fld-input"><input type="checkbox" id="fld<?php echo ++$forum_page[\'fld_count\'] ?>" name="form[pun_pm_show_new_count]" value="1"<?php if ($forum_config[\'o_pun_pm_show_new_count\'] == \'1\') echo \' checked="checked"\' ?> /></span>
							<label for="fld<?php echo $forum_page[\'fld_count\'] ?>"><?php echo $lang_pun_pm[\'Snow new count\'] ?></label>
						</div>
						<div class="mf-item">
							<span class="fld-input"><input type="checkbox" id="fld<?php echo ++$forum_page[\'fld_count\'] ?>" name="form[pun_pm_show_global_link]" value="1"<?php if ($forum_config[\'o_pun_pm_show_global_link\'] == \'1\') echo \' checked="checked"\' ?> /></span>
							<label for="fld<?php echo $forum_page[\'fld_count\'] ?>"><?php echo $lang_pun_pm[\'Show global link\'] ?></label>
						</div>
					</div>
				</fieldset>
<?php ($hook = get_hook(\'pun_pm_aop_features_pre_pm_settings_fieldset_end\')) ? eval($hook) : null; ?>
			</fieldset>
<?php

array_pop($GLOBALS[\'ext_info_stack\']);
$ext_info = empty($GLOBALS[\'ext_info_stack\']) ? array() : $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];
',
  ),
  'aop_features_validation' => 
  array (
    0 => '$GLOBALS[\'ext_info_stack\'][] = array(
\'id\'				=> \'pun_pm\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_pm\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_pm\',
\'dependencies\'	=> array (
)
);
$ext_info = $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];

$form[\'pun_pm_inbox_size\'] = (!isset($form[\'pun_pm_inbox_size\']) || (int) $form[\'pun_pm_inbox_size\'] <= 0) ? \'0\' : (string)(int) $form[\'pun_pm_inbox_size\'];
$form[\'pun_pm_outbox_size\'] = (!isset($form[\'pun_pm_outbox_size\']) || (int) $form[\'pun_pm_outbox_size\'] <= 0) ? \'0\' : (string)(int) $form[\'pun_pm_outbox_size\'];
if (!isset($form[\'pun_pm_show_new_count\']) || $form[\'pun_pm_show_new_count\'] != \'1\')
	$form[\'pun_pm_show_new_count\'] = \'0\';
if (!isset($form[\'pun_pm_show_global_link\']) || $form[\'pun_pm_show_global_link\'] != \'1\')
	$form[\'pun_pm_show_global_link\'] = \'0\';

($hook = get_hook(\'pun_pm_aop_features_validation_end\')) ? eval($hook) : null;

array_pop($GLOBALS[\'ext_info_stack\']);
$ext_info = empty($GLOBALS[\'ext_info_stack\']) ? array() : $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];
',
  ),
  'fn_delete_user_end' => 
  array (
    0 => '$GLOBALS[\'ext_info_stack\'][] = array(
\'id\'				=> \'pun_pm\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_pm\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_pm\',
\'dependencies\'	=> array (
)
);
$ext_info = $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];

$query = array(
	\'DELETE\'	=> \'pun_pm_messages\',
	\'WHERE\'		=> \'receiver_id = \'.$user_id.\' AND deleted_by_sender = 1\'
);
$result = $forum_db->query_build($query) or error(__FILE__, __LINE__);

$query = array(
	\'UPDATE\'	=> \'pun_pm_messages\',
	\'SET\'		=> \'deleted_by_receiver = 1\',
	\'WHERE\'		=> \'receiver_id = \'.$user_id
);
$result = $forum_db->query_build($query) or error(__FILE__, __LINE__);

array_pop($GLOBALS[\'ext_info_stack\']);
$ext_info = empty($GLOBALS[\'ext_info_stack\']) ? array() : $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];
',
  ),
  'hd_visit_elements' => 
  array (
    0 => '$GLOBALS[\'ext_info_stack\'][] = array(
\'id\'				=> \'pun_pm\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_pm\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_pm\',
\'dependencies\'	=> array (
)
);
$ext_info = $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];

// \'New messages (N)\' link
if (!$forum_user[\'is_guest\'] && $forum_config[\'o_pun_pm_show_new_count\'])
{
	global $lang_pun_pm;

	if (!isset($lang_pun_pm))
	{
		if (file_exists($ext_info[\'path\'].\'/lang/\'.$forum_user[\'language\'].\'/\'.$ext_info[\'id\'].\'.php\'))
			include $ext_info[\'path\'].\'/lang/\'.$forum_user[\'language\'].\'/\'.$ext_info[\'id\'].\'.php\';
		else
			include $ext_info[\'path\'].\'/lang/English/\'.$ext_info[\'id\'].\'.php\';
	}

	// TODO: Do not include all functions, divide them into 2 files
	if(!defined(\'PUN_PM_FUNCTIONS_LOADED\'))
		require $ext_info[\'path\'].\'/functions.php\';

	($hook = get_hook(\'pun_pm_hd_visit_elements_pre_change\')) ? eval($hook) : null;

	$visit_elements[\'<!-- forum_visit -->\'] = preg_replace(\'#(<p id="visit-links" class="options">.*?)(</p>)#\', \'$1 <span><a href="\'.forum_link($forum_url[\'pun_pm_inbox\']).\'">\'.pun_pm_unread_messages().\'</a></span>$2\', $visit_elements[\'<!-- forum_visit -->\']);

	($hook = get_hook(\'pun_pm_hd_visit_elements_after_change\')) ? eval($hook) : null;
}

array_pop($GLOBALS[\'ext_info_stack\']);
$ext_info = empty($GLOBALS[\'ext_info_stack\']) ? array() : $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];
',
  ),
  'vt_row_pre_post_contacts_merge' => 
  array (
    0 => '$GLOBALS[\'ext_info_stack\'][] = array(
\'id\'				=> \'pun_pm\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_pm\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_pm\',
\'dependencies\'	=> array (
)
);
$ext_info = $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];

global $lang_pun_pm;

if (!isset($lang_pun_pm))
{
	if (file_exists($ext_info[\'path\'].\'/lang/\'.$forum_user[\'language\'].\'/\'.$ext_info[\'id\'].\'.php\'))
		include $ext_info[\'path\'].\'/lang/\'.$forum_user[\'language\'].\'/\'.$ext_info[\'id\'].\'.php\';
	else
		include $ext_info[\'path\'].\'/lang/English/\'.$ext_info[\'id\'].\'.php\';
}

($hook = get_hook(\'pun_pm_pre_post_contacts_add\')) ? eval($hook) : null;

// Links \'Send PM\' near posts
if (!$forum_user[\'is_guest\'] && $cur_post[\'poster_id\'] > 1 && $forum_user[\'id\'] != $cur_post[\'poster_id\'])
	$forum_page[\'post_contacts\'][\'PM\'] = \'<a class="contact" title="\'.$lang_pun_pm[\'Send PM\'].\'" href="\'.forum_link($forum_url[\'pun_pm_post_link\'], $cur_post[\'poster_id\']).\'">\'.$lang_pun_pm[\'PM\'].\'</a>\';

($hook = get_hook(\'pun_pm_after_post_contacts_add\')) ? eval($hook) : null;

array_pop($GLOBALS[\'ext_info_stack\']);
$ext_info = empty($GLOBALS[\'ext_info_stack\']) ? array() : $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];
',
  ),
  'fn_generate_navlinks_end' => 
  array (
    0 => '$GLOBALS[\'ext_info_stack\'][] = array(
\'id\'				=> \'pun_pm\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_pm\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_pm\',
\'dependencies\'	=> array (
)
);
$ext_info = $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];

// Link \'PM\' in the main nav menu
if (isset($links[\'profile\']) && $forum_config[\'o_pun_pm_show_global_link\'])
{
	global $lang_pun_pm;

	if (!isset($lang_pun_pm))
	{
		if (file_exists($ext_info[\'path\'].\'/lang/\'.$forum_user[\'language\'].\'/\'.$ext_info[\'id\'].\'.php\'))
			include $ext_info[\'path\'].\'/lang/\'.$forum_user[\'language\'].\'/\'.$ext_info[\'id\'].\'.php\';
		else
			include $ext_info[\'path\'].\'/lang/English/\'.$ext_info[\'id\'].\'.php\';
	}

	if (\'pun_pm\' == substr(FORUM_PAGE, 0, 6))
		$links[\'profile\'] = str_replace(\' class="isactive"\', \'\', $links[\'profile\']);

	($hook = get_hook(\'pun_pm_pre_main_navlinks_add\')) ? eval($hook) : null;

	$links[\'profile\'] .= "\\n\\t\\t".\'<li id="nav_pun_pm"\'.(\'pun_pm\' == substr(FORUM_PAGE, 0, 6) ? \' class="isactive"\' : \'\').\'><a href="\'.forum_link($forum_url[\'pun_pm\']).\'"><span>\'.$lang_pun_pm[\'Private messages\'].\'</span></a></li>\';

	($hook = get_hook(\'pun_pm_after_main_navlinks_add\')) ? eval($hook) : null;
}

array_pop($GLOBALS[\'ext_info_stack\']);
$ext_info = empty($GLOBALS[\'ext_info_stack\']) ? array() : $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];
',
  ),
  'pf_view_details_pre_header_load' => 
  array (
    0 => '$GLOBALS[\'ext_info_stack\'][] = array(
\'id\'				=> \'pun_pm\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_pm\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_pm\',
\'dependencies\'	=> array (
)
);
$ext_info = $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];

// Link in the profile 
if (!$forum_user[\'is_guest\'] && $forum_user[\'id\'] != $user[\'id\'])
{
	if (!isset($lang_pun_pm))
	{
		if (file_exists($ext_info[\'path\'].\'/lang/\'.$forum_user[\'language\'].\'/\'.$ext_info[\'id\'].\'.php\'))
			include $ext_info[\'path\'].\'/lang/\'.$forum_user[\'language\'].\'/\'.$ext_info[\'id\'].\'.php\';
		else
			include $ext_info[\'path\'].\'/lang/English/\'.$ext_info[\'id\'].\'.php\';
	}

	($hook = get_hook(\'pun_pm_pre_profile_user_contact_add\')) ? eval($hook) : null;

	$forum_page[\'user_contact\'][\'PM\'] = \'<li><span>\'.$lang_pun_pm[\'PM\'].\': <a href="\'.forum_link($forum_url[\'pun_pm_post_link\'], $id).\'">\'.$lang_pun_pm[\'Send PM\'].\'</a></span></li>\';

	($hook = get_hook(\'pun_pm_after_profile_user_contact_add\')) ? eval($hook) : null;
}

array_pop($GLOBALS[\'ext_info_stack\']);
$ext_info = empty($GLOBALS[\'ext_info_stack\']) ? array() : $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];
',
  ),
  'pf_change_details_about_pre_header_load' => 
  array (
    0 => '$GLOBALS[\'ext_info_stack\'][] = array(
\'id\'				=> \'pun_pm\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_pm\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_pm\',
\'dependencies\'	=> array (
)
);
$ext_info = $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];

// Link in the profile 
if (!$forum_user[\'is_guest\'] && $forum_user[\'id\'] != $user[\'id\'])
{
	if (!isset($lang_pun_pm))
	{
		if (file_exists($ext_info[\'path\'].\'/lang/\'.$forum_user[\'language\'].\'/\'.$ext_info[\'id\'].\'.php\'))
			include $ext_info[\'path\'].\'/lang/\'.$forum_user[\'language\'].\'/\'.$ext_info[\'id\'].\'.php\';
		else
			include $ext_info[\'path\'].\'/lang/English/\'.$ext_info[\'id\'].\'.php\';
	}

	($hook = get_hook(\'pun_pm_pre_profile_user_contact_add\')) ? eval($hook) : null;

	$forum_page[\'user_contact\'][\'PM\'] = \'<li><span>\'.$lang_pun_pm[\'PM\'].\': <a href="\'.forum_link($forum_url[\'pun_pm_post_link\'], $id).\'">\'.$lang_pun_pm[\'Send PM\'].\'</a></span></li>\';

	($hook = get_hook(\'pun_pm_after_profile_user_contact_add\')) ? eval($hook) : null;
}

array_pop($GLOBALS[\'ext_info_stack\']);
$ext_info = empty($GLOBALS[\'ext_info_stack\']) ? array() : $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];
',
  ),
  'co_modify_url_scheme' => 
  array (
    0 => '$GLOBALS[\'ext_info_stack\'][] = array(
\'id\'				=> \'pun_pm\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_pm\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_pm\',
\'dependencies\'	=> array (
)
);
$ext_info = $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];

if (file_exists($ext_info[\'path\'].\'/url/\'.$forum_config[\'o_sef\'].\'.php\'))
	require $ext_info[\'path\'].\'/url/\'.$forum_config[\'o_sef\'].\'.php\';
else
	require $ext_info[\'path\'].\'/url/Default.php\';

array_pop($GLOBALS[\'ext_info_stack\']);
$ext_info = empty($GLOBALS[\'ext_info_stack\']) ? array() : $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];
',
  ),
  're_rewrite_rules' => 
  array (
    0 => '$GLOBALS[\'ext_info_stack\'][] = array(
\'id\'				=> \'pun_pm\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_pm\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_pm\',
\'dependencies\'	=> array (
)
);
$ext_info = $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];

$forum_rewrite_rules[\'/^pun_pm[\\/_-]?send(\\.html?|\\/)?$/i\'] = \'misc.php?action=pun_pm_send\';
$forum_rewrite_rules[\'/^pun_pm[\\/_-]?compose[\\/_-]?([0-9]+)(\\.html?|\\/)?$/i\'] = \'misc.php?section=pun_pm&pmpage=compose&receiver_id=$1\';
$forum_rewrite_rules[\'/^pun_pm(\\.html?|\\/)?$/i\'] = \'misc.php?section=pun_pm\';
$forum_rewrite_rules[\'/^pun_pm[\\/_-]?([0-9a-z]+)(\\.html?|\\/)?$/i\'] = \'misc.php?section=pun_pm&pmpage=$1\';
$forum_rewrite_rules[\'/^pun_pm[\\/_-]?([0-9a-z]+)[\\/_-]?(p|page\\/)([0-9]+)(\\.html?|\\/)?$/i\'] = \'misc.php?section=pun_pm&pmpage=$1&p=$3\';
$forum_rewrite_rules[\'/^pun_pm[\\/_-]?([0-9a-z]+)[\\/_-]?([0-9]+)(\\.html?|\\/)?$/i\'] = \'misc.php?section=pun_pm&pmpage=$1&message_id=$2\';

($hook = get_hook(\'pun_pm_after_rewrite_rules_set\')) ? eval($hook) : null;

array_pop($GLOBALS[\'ext_info_stack\']);
$ext_info = empty($GLOBALS[\'ext_info_stack\']) ? array() : $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];
',
  ),
  'vt_end' => 
  array (
    0 => '$GLOBALS[\'ext_info_stack\'][] = array(
\'id\'				=> \'pun_quote\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_quote\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_quote\',
\'dependencies\'	=> array (
)
);
$ext_info = $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];

if (!$forum_user[\'is_guest\'])
			{

			?>
			<form id="pun_quote_form" action="<?php echo forum_link(\'post.php\'); ?>" method="post">
				<div class="hidden">
					<input type="hidden" value="" id="post_msg" name="post_msg"/>
					<input type="hidden" value="<?php echo forum_link($forum_url[\'quote\'], array($id, $cur_post[\'id\'])) ?>" id="pun_quote_url" name="pun_quote_url" />
				</div>
			</form>
			<?php

			}

array_pop($GLOBALS[\'ext_info_stack\']);
$ext_info = empty($GLOBALS[\'ext_info_stack\']) ? array() : $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];
',
  ),
  'po_qr_get_quote' => 
  array (
    0 => '$GLOBALS[\'ext_info_stack\'][] = array(
\'id\'				=> \'pun_quote\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_quote\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_quote\',
\'dependencies\'	=> array (
)
);
$ext_info = $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];

if(!$forum_user[\'is_guest\'] && isset($_POST[\'post_msg\']))
				$query[\'SELECT\'] = \'p.poster, \\\'\'.$forum_db->escape($_POST[\'post_msg\']).\'\\\'\';

array_pop($GLOBALS[\'ext_info_stack\']);
$ext_info = empty($GLOBALS[\'ext_info_stack\']) ? array() : $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];
',
  ),
  'vt_qr_get_posts' => 
  array (
    0 => '$GLOBALS[\'ext_info_stack\'][] = array(
\'id\'				=> \'pun_quote\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_quote\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_quote\',
\'dependencies\'	=> array (
)
);
$ext_info = $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];

$pun_quote_js_arrays = \'\';

array_pop($GLOBALS[\'ext_info_stack\']);
$ext_info = empty($GLOBALS[\'ext_info_stack\']) ? array() : $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];
',
  ),
  'vt_row_new_post_entry_data' => 
  array (
    0 => '$GLOBALS[\'ext_info_stack\'][] = array(
\'id\'				=> \'pun_quote\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_quote\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_quote\',
\'dependencies\'	=> array (
)
);
$ext_info = $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];

if (!$forum_user[\'is_guest\'])
			{
				$pun_quote_js_arrays .= \'pun_quote_posts[\'.$cur_post[\'id\'].\'] = "\'.str_replace(array(\'\\\\\', "\\n"), array(\'\\\\\\\\\', \'\\n\'), forum_htmlencode($cur_post[\'message\'])).\'";\';
				$pun_quote_js_arrays .= \' pun_quote_authors[\'.$cur_post[\'id\'].\'] = "\'.$cur_post[\'username\'].\'";\'."\\n";
			}

array_pop($GLOBALS[\'ext_info_stack\']);
$ext_info = empty($GLOBALS[\'ext_info_stack\']) ? array() : $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];
',
  ),
  'vt_row_pre_post_actions_merge' => 
  array (
    0 => '$GLOBALS[\'ext_info_stack\'][] = array(
\'id\'				=> \'pun_quote\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_quote\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_quote\',
\'dependencies\'	=> array (
)
);
$ext_info = $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];

if (file_exists($ext_info[\'path\'].\'/lang/\'.$forum_user[\'language\'].\'/\'.$ext_info[\'id\'].\'.php\'))
				require $ext_info[\'path\'].\'/lang/\'.$forum_user[\'language\'].\'/\'.$ext_info[\'id\'].\'.php\';
			else
				require $ext_info[\'path\'].\'/lang/English/\'.$ext_info[\'id\'].\'.php\';

			if (!$forum_user[\'is_guest\'])
			{
				$quote_link = forum_link($forum_url[\'quote\'], array($id, $cur_post[\'id\']));
				$forum_page[\'post_actions\'][\'reply\'] = \'<span class="edit-post first-item"><a href="\'.$quote_link.\'" onclick="Reply(\'.$cur_post[\'id\'].\', this); return false;">\'.$lang_pun_quote[\'Reply\'].\'<span>&#160;\'.$lang_topic[\'Post\'].\' \'.($forum_page[\'start_from\'] + $forum_page[\'item_count\']).\'</span></a></span>\';
				//If quick post is enabled generate Quick Quote link
				if ($forum_config[\'o_quickpost\'] == \'1\')
				{
					unset($forum_page[\'post_actions\'][\'quote\']);
					$forum_page[\'post_actions\'][\'quote\'] = \'<span class="edit-post first-item"><a href="\'.$quote_link.\'" onclick="QuickQuote(\'.$cur_post[\'id\'].\'); return false;">\'.$lang_pun_quote[\'Quote\'].\'<span>&#160;\'.$lang_topic[\'Post\'].\' \'.($forum_page[\'start_from\'] + $forum_page[\'item_count\']).\'</span></a></span>\';
				}
				unset($quote_link);
			}

array_pop($GLOBALS[\'ext_info_stack\']);
$ext_info = empty($GLOBALS[\'ext_info_stack\']) ? array() : $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];
',
  ),
  'fn_generate_avatar_markup_end' => 
  array (
    0 => '$GLOBALS[\'ext_info_stack\'][] = array(
\'id\'				=> \'default_avatar\',
\'path\'			=> FORUM_ROOT.\'extensions/default_avatar\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/default_avatar\',
\'dependencies\'	=> array (
)
);
$ext_info = $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];

if(empty($avatar_markup) && $forum_config[\'o_default_avatar\'])
{
	$img_size = @getimagesize($forum_config[\'o_default_avatar_url\']);
	$avatar_markup = \'<img src="\'.$forum_config[\'o_default_avatar_url\'].\'" \'.$img_size[3].\' alt="" />\';
}

array_pop($GLOBALS[\'ext_info_stack\']);
$ext_info = empty($GLOBALS[\'ext_info_stack\']) ? array() : $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];
',
  ),
  'aop_setup_validation' => 
  array (
    0 => '$GLOBALS[\'ext_info_stack\'][] = array(
\'id\'				=> \'default_avatar\',
\'path\'			=> FORUM_ROOT.\'extensions/default_avatar\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/default_avatar\',
\'dependencies\'	=> array (
)
);
$ext_info = $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];

if (!isset($form[\'default_avatar\']) || $form[\'default_avatar\'] != \'1\')
	$form[\'default_avatar\'] = \'0\';

array_pop($GLOBALS[\'ext_info_stack\']);
$ext_info = empty($GLOBALS[\'ext_info_stack\']) ? array() : $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];
',
  ),
  'aop_features_pre_avatars_fieldset_end' => 
  array (
    0 => '$GLOBALS[\'ext_info_stack\'][] = array(
\'id\'				=> \'default_avatar\',
\'path\'			=> FORUM_ROOT.\'extensions/default_avatar\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/default_avatar\',
\'dependencies\'	=> array (
)
);
$ext_info = $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];

if (file_exists($ext_info[\'path\'].\'/lang/\'.$forum_user[\'language\'].\'/\'.$ext_info[\'id\'].\'.php\'))
	include $ext_info[\'path\'].\'/lang/\'.$forum_user[\'language\'].\'/\'.$ext_info[\'id\'].\'.php\';
else
	include $ext_info[\'path\'].\'/lang/English/default_avatar.php\';


?>
				<div class="sf-set set<?php echo ++$forum_page[\'item_count\'] ?>">
					<div class="sf-box checkbox">
						<span class="fld-input"><input type="checkbox" id="fld<?php echo ++$forum_page[\'fld_count\'] ?>" name="form[default_avatar]" value="1"<?php if ($forum_config[\'o_default_avatar\'] == \'1\') echo \' checked="checked"\' ?> /></span>
						<label for="fld<?php echo $forum_page[\'fld_count\'] ?>"><span><?php echo $lang_default_avatar[\'Default avatar\'] ?></span></label>
					</div>
				</div>
				<div class="sf-set set<?php echo ++$forum_page[\'item_count\'] ?>">
					<div class="sf-box text">
						<label for="fld<?php echo ++$forum_page[\'fld_count\'] ?>"><span><?php echo $lang_default_avatar[\'Default avatar url\'] ?></span></label><br />
						<span class="fld-input"><input type="text" id="fld<?php echo $forum_page[\'fld_count\'] ?>" name="form[default_avatar_url]" size="50" value="<?php echo $forum_config[\'o_default_avatar_url\'] ?>" /></span>
					</div>
				</div>
<?php

array_pop($GLOBALS[\'ext_info_stack\']);
$ext_info = empty($GLOBALS[\'ext_info_stack\']) ? array() : $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];
',
  ),
  'ft_about_end' => 
  array (
    0 => '$GLOBALS[\'ext_info_stack\'][] = array(
\'id\'				=> \'pun_bbcode\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_bbcode\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_bbcode\',
\'dependencies\'	=> array (
)
);
$ext_info = $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];

if (!defined(\'PUN_EXTENSIONS_USED\') && !empty($pun_extensions_used))
{
	define(\'PUN_EXTENSIONS_USED\', 1);
	if (count($pun_extensions_used) == 1)
		echo \'<p style="clear: both; ">The \'.$pun_extensions_used[0].\' official extension is installed. Copyright &copy; 2003&ndash;2009 <a href="http://punbb.informer.com/">PunBB</a>.</p>\';
	else
		echo \'<p style="clear: both; ">Currently installed <span id="extensions-used" title="\'.implode(\', \', $pun_extensions_used).\'.">\'.count($pun_extensions_used).\' official extensions</span>. Copyright &copy; 2003&ndash;2009 <a href="http://punbb.informer.com/">PunBB</a>.</p>\';
}

array_pop($GLOBALS[\'ext_info_stack\']);
$ext_info = empty($GLOBALS[\'ext_info_stack\']) ? array() : $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];
',
    1 => '$GLOBALS[\'ext_info_stack\'][] = array(
\'id\'				=> \'pun_pm\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_pm\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_pm\',
\'dependencies\'	=> array (
)
);
$ext_info = $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];

if (!defined(\'PUN_EXTENSIONS_USED\') && !empty($pun_extensions_used))
{
	define(\'PUN_EXTENSIONS_USED\', 1);
	if (count($pun_extensions_used) == 1)
		echo \'<p style="clear: both; ">The \'.$pun_extensions_used[0].\' official extension is installed. Copyright &copy; 2003&ndash;2009 <a href="http://punbb.informer.com/">PunBB</a>.</p>\';
	else
		echo \'<p style="clear: both; ">Currently installed <span id="extensions-used" title="\'.implode(\', \', $pun_extensions_used).\'.">\'.count($pun_extensions_used).\' official extensions</span>. Copyright &copy; 2003&ndash;2009 <a href="http://punbb.informer.com/">PunBB</a>.</p>\';
}

array_pop($GLOBALS[\'ext_info_stack\']);
$ext_info = empty($GLOBALS[\'ext_info_stack\']) ? array() : $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];
',
    2 => '$GLOBALS[\'ext_info_stack\'][] = array(
\'id\'				=> \'pun_quote\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_quote\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_quote\',
\'dependencies\'	=> array (
)
);
$ext_info = $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];

if (!defined(\'PUN_EXTENSIONS_USED\') && !empty($pun_extensions_used))
			{
				define(\'PUN_EXTENSIONS_USED\', 1);
				if (count($pun_extensions_used) == 1)
					echo \'<p style="clear: both; ">The \'.$pun_extensions_used[0].\' official extension is installed. Copyright &copy; 2003&ndash;2009 <a href="http://punbb.informer.com/">PunBB</a>.</p>\';
				else
					echo \'<p style="clear: both; ">Currently installed <span id="extensions-used" title="\'.implode(\', \', $pun_extensions_used).\'.">\'.count($pun_extensions_used).\' official extensions</span>. Copyright &copy; 2003&ndash;2009 <a href="http://punbb.informer.com/">PunBB</a>.</p>\';
			}

array_pop($GLOBALS[\'ext_info_stack\']);
$ext_info = empty($GLOBALS[\'ext_info_stack\']) ? array() : $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];
',
    3 => '$GLOBALS[\'ext_info_stack\'][] = array(
\'id\'				=> \'pun_attachment\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_attachment\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_attachment\',
\'dependencies\'	=> array (
)
);
$ext_info = $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];

if (!defined(\'PUN_EXTENSIONS_USED\') && !empty($pun_extensions_used))
{
	define(\'PUN_EXTENSIONS_USED\', 1);
	echo \'<p id="extensions-used">Currently used extensions: \'.implode(\', \', $pun_extensions_used).\'. Copyright &copy; 2008 <a href="http://punbb.informer.com/">PunBB</a></p>\';
}

array_pop($GLOBALS[\'ext_info_stack\']);
$ext_info = empty($GLOBALS[\'ext_info_stack\']) ? array() : $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];
',
  ),
);

?>