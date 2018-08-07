<?php

/**
 * Language file for pun_attacnment extension
 *
 * @copyright (C) 2008-2009 PunBB, partially based on Attachment Mod by Frank Hagstrom
 * @license http://www.gnu.org/licenses/gpl.html GPL version 2 or higher
 * @package pun_attachment
 */

if (!defined('FORUM')) die();

// Language definitions for frequently used strings
$lang_attach = array(
//admin
'Display images'		=>	'Показывать изображения',
'Display small'			=>	'Изображения будут отображаться при просмотре или редактировании темы, если их размеры ниже чем параметры ниже:',
'Disable attachments'	=>	'Отключить вложения',
'Display icons' 		=>	'Включить отображение значков',
'Create orphans'		=>	'Включить опцию, чтобы создавать "сирот".',
'Always deny'			=>	'Всегда запрещено',
'Filesize'				=>	'Размер файла',
'Filename'				=>	'Имя файла',
'Max filesize'			=>	'Максимальный размер файла',
'Max height'			=>	'Максимальная высота',
'Max width'				=>	'Максимальная ширина',
'Manage icons'			=>	'Управление значками',
'Main options'			=>	'Основные настройки',
'Attachment rules'		=>	'Правила вложений',
'Attachment page head'	=>	'Вложение <strong>%s</strong>',
'Delete button'			=>	'Удалить',
'Attach button'			=>	'Прикрепить',
'Rename button'			=>	'Переименовать',
'Detach button'			=>	'Открепить',
'Uploaded date'			=>	'Дата загрузки',
'MIME-type'				=>	'MIME-type',
'Post id'				=>	'Номер сообщения',
'Downloads'				=>	'Загрузок',
'New name'				=>	'Новое имя',
'Ascending'				=>	'По возрастанию',
'Descending'			=>	'По убыванию',

'Create orphans'		=>	'Создание "сирот"',
'Orphans help'			=>	'Если эта опция включена, файлы не будут удаляться из БД, когда пользователь удаляет сообщение или тему с вложениями.',
'Icons help'			=>	'Значки для вложений хранятся в каталоге /extensions/attachment/img/. Чтобы добавить или изменить значки, используйте форму ниже. В первой колонке нужно вводить тип файла, в ячейке напротив следует вводить имя файла значка. Можно использовать следующие форматы: png, gif, jpeg и ico.',


// la
'Attachment'			=>	'Вложения',
'Size:'					=>	'Размер:',
'bytes'					=>	'байт',
'Downloads:'			=>	'Скачиваний:',
'Kbytes'				=>	' килобайт',
'Mbytes'				=>	' мегабайт',
'Bytes'					=>	' байт',
'Kb'					=>	' Кб',
'Mb'					=>	' Мб',
'B'						=>	' b',
'Since'					=>	'%s скачиваний с %s',
'Never download'		=>	'файл не был скачан.',
'Since (title)'			=>	'%s раз скачали с %s',
'Attachment icon'		=>	'Иконка вложений',

'Number existing'		=>	'Файл #<strong>%s</strong>',

//edit.php
'Existing'				=>	'Существующие вложения: ',	//Used in edit.php, before the existing attachments that you're allowed to delete

//attach.php
'Download:'				=>	'Скачать:',
'Attachment added'		=>	'Вложение добавлено. Перенаправление...',
'Attachment delete'		=>	'Вложение удалено. Перенаправление...',

//rules
'Group attach part'		=>	'Права доступа к вложениям',
'Rules'					=>	'Attachment rules',
'Download'				=>	'Разрешить скачивать файлы',
'Upload'				=>	'Разрешить закачивать файлы',
'Delete'				=>	'Разрешить удалять файлы',
'Owner delete'			=>	'Разрешить удалять собственные файлы',
'Size'					=>	'Максимальный размер файла',
'Size comment'			=>	'Максимальный размер загружаемых файлов (в байтах).',
'Per post'				=>	'Вложений в сообщении',
'Allowed files'			=>	'Разрешенные файлы',
'Allowed comment'		=>	'Оставьте пустым, чтобы разрешить все файлы, кроме явно запрещенных.',
'File len err'			=>	'File name can\'t be longer than 255 chars',
'Ext len err'			=>	'File extension can\'t be longer than 64 chars.',

// Notices
'Wrong post'			=>	'You have entered a wrong post id. Please correct it.',
'Too large ini'			=>	'The selected file was too large to upload. The server forbade the upload.',
'Wrong icon/name'		=>	'Введены неверное значение "расширение/имя файла значка"',
'No icons'				=>	'You have entered an empty value of extension/icon name. Please, go back and correct it.',
'Wrong deny'			=>	'You have entered a wrong list of denied extensions. Please, go back and correct it.',
'Wrong allowed'			=>	'You have entered a wrong list of allowed extensions. Please, go back and correct it.',
'Big icon'				=>	'The icon <strong>%s</strong> is too wide/high. Please, select another one.',
'Missing icons'			=>	'The following icons are missing:',
'Big icons'				=>	'The following icons are too wide/high:',

'Error: mkdir'			=>	'Unable to create new the subfolder with the name',
'Error: 0750'			=>	'with mode 0750',
'Error: .htaccess'		=>	'Unable to copy .htaccess file to the new subfolder with name',
'Error: index.html'		=>	'Unable to copy index.html file to the new subfolder with name',
'Some more salt keywords'	=> 'Some more salt keywords, change if you want to',
'Put salt'				=>	'put your salt here',
'Attachment options'	=>	'Attachment options',
'Rename attachment'		=>	'Rename attachment',
'Old name'				=>	'Старое имя',
'New name'				=>	'Новое имя',
'Input new attachment name'	=>	'Input a new attachment name (without extension)',
'Attachments'			=>	'Вложения',
'Start at'				=>	'Начинать с',
'Number to show'		=>	'Результатов на странице',
'to'					=>	'до',
'Owner'					=>	'Владелец',
'Topic'					=>	'Тема',
'Order by'				=>	'Сортировать по',
'Result sort order'		=>	'Сортировка результатов',
'Orphans'				=>	'Сироты',
'Apply'					=>	'Применить фильтр',
'Show only "Orphans"'	=>	'Показать только "сирот"',
'Error creating attachment'	=>	'Error whilecreating attachment, inform the owner of this bulletin board about this problem',
'Use icons'				=>	'Использовать значки',
'Error while deleting attachment'	=>	'Error while deleting attachment. Attachment is not deleted.',
'Salt keyword'			=>	'Salt keyword, replace if you want to',

'Too short filename'	=>	'Please, enter an unempty filename if you want to rename this attachment.',
'Wrong post id'			=>	'You have entered a wrong post id. Please, correct it if you want to attach a file to this post.',
'Empty post id'			=>	'Please, enter an unempty post id if you want to attach this file to the post.',
'Attach error'			=>	'<strong>Предупреждение!</strong> Следующие ошибки должны быть исправлены перед отправкой файлов:',
'Rename error'			=>	'<strong>Предупреждение!</strong> Следующие ошибки должны быть исправлены перед изменением имени файла:',

'Edit attachments'		=>	'Редактировать вложение',
'Post attachments'		=>	'Post attachments',
'Image preview'			=>	'Предпросмотр изображения',

'Manage attahcments'	=>	'Manage attachments',
'Manage id'				=>	'Manage attachment %s',

'Permission denied'		=>	'The directory "FORUM_ROOT/extensions/pun_attachment/attachments" is not writable for a Web server!',
'Htaccess fail'			=>	'File "FORUM_ROOT/extensions/pun_attachment/attachments/.htaccess" does not exist.',
'Index fail'			=>	'File "FORUM_ROOT/extensions/pun_attachment/attachments/index.html" does not exist.',
'Errors notice'			=>	'Обнаружены следующие ошибки:',

'Del perm error'		=>	'Недостаточно прав для удаления этого файла.',
'Up perm error'			=>	'You don\'t have the permission to upload a file to this post.',

'Attach limit error'	=>	'You can add only %s attachments to this post.',
'Ext error'				=>	'You can\'t add an attachment with "%s" extension.',
'Filesize error'		=>	'You can\'t upload a file whose size is more than "%s" bytes.',
'Bad image'				=>	'Bad image! Try uploading it again.',
'Add file'				=>	'Загрузить',
'Post attachs'			=>	'Post\'s attachments',
'Download perm error'	=>	'You don\'t have the permssions to download the attachments of this post.',
'None'					=>	'None',

'Id'					=>	'Id',
'Owner'					=>	'Владелец',
'Up date'				=>	'Дата загрузки',
'Type'					=>	'Тип'

);
