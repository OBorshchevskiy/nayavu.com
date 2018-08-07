<?php

if (!defined('FORUM')) exit;
define('FORUM_QJ_LOADED', 1);
$forum_id = isset($forum_id) ? $forum_id : 0;

?><form id="qjump" method="get" accept-charset="utf-8" action="http://nayavu.com/forum/viewforum.php">
	<div class="frm-fld frm-select">
		<label for="qjump-select"><span><?php echo $lang_common['Jump to'] ?></span></label><br />
		<span class="frm-input"><select id="qjump-select" name="id">
			<optgroup label="Главный">
				<option value="1"<?php echo ($forum_id == 1) ? ' selected="selected"' : '' ?>>Проблемы и их решения</option>
				<option value="2"<?php echo ($forum_id == 2) ? ' selected="selected"' : '' ?>>Пожелания к работе сайта</option>
				<option value="3"<?php echo ($forum_id == 3) ? ' selected="selected"' : '' ?>>Жалобы и замечания</option>
			</optgroup>
			<optgroup label="Дальнейшая разработка">
				<option value="4"<?php echo ($forum_id == 4) ? ' selected="selected"' : '' ?>>Процесс разработки</option>
			</optgroup>
		</select>
		<input type="submit" value="<?php echo $lang_common['Go'] ?>" onclick="return Forum.doQuickjumpRedirect(forum_quickjump_url, sef_friendly_url_array);" /></span>
	</div>
</form>
<script type="text/javascript">
		var forum_quickjump_url = "http://nayavu.com/forum/viewforum.php?id=$1";
		var sef_friendly_url_array = new Array(4);
	sef_friendly_url_array[1] = "problemy-i-ikh-resheniya";
	sef_friendly_url_array[2] = "pozhelaniya-k-rabote-saita";
	sef_friendly_url_array[3] = "zhaloby-i-zamechaniya";
	sef_friendly_url_array[4] = "protsess-razrabotki";
</script>
