<?
$language_code = "ru";
# различные сообщения
define('referer_error', 'Вы пытаетесь отправить данные не с формы сайта!');
define('post_refresh_data', 'Защита от повторной отправки данных! Предыдущие данные не сохранены.');

# сообщения для connect.php
define('connect_server_database_error', 'Could not connect to database server!');
define('connect_database_select_error', 'Could not select database!');

# сообщения для header.php - admin, index
define('header_login', 'логин');
define('header_password', 'пароль');
define('header_enter_button', 'войти!');
define('header_remember', 'запомнить');
define('header_password_restore', 'забыли пароль?');
define('header_logout', 'выход');
define('header_link_profile', 'Ваш профиль');

# сообщения для header.php - admin
define('header_admin_link_cp', 'Панель администратора');
define('header_admin_menu_main', 'Меню администратора');
define('header_admin_menu_vkontakte', 'Vk.com');
define('header_admin_menu_news_link', 'Новости');

# сообщения для header.php - index
define('header_index_table_view_profile', 'Добавленные для мониторинга аккаунты Вконтакте.ру');
define('header_index_frequent_requests', 'Разрешено не более одного запроса в 10 секунд! Пожалуйста подождите! Не создавайте большой нагрузки для сервера!');
define('header_index_link_register', 'ЗАРЕГИСТРИРОВАТЬСЯ!');
define('header_index_link_forum', 'ФОРУМ');
define('header_index_link_donate', 'Отблагодарить!');
define('header_index_how_search_id_or_name', 'Как узнать id или имя?');
define('header_index_button_search_id_or_name_user', 'Найти!');
define('header_index_vkontakte_id_user_is_ban', 'Данного пользователя нельзя добавить для мониторинга! Т.к по просьбе одного из посетителей сайта, мониторинг был остановлен и полностью удалена вся информация. Повторное добавление запрещено!');
define('header_index_id_or_name_user', 'ID или ИМЯ пользователя');
define('header_index_vkontakte_id_user_error', 'Недопустимый ID пользователя Вконтакте!');
define('header_index_vkontakte_id_or_name_user_error', 'Вы ввели недопустимый ID или Имя пользователя Вконтакте!');
define('header_index_vkontakte_id_or_name_user_bad', 'Пользователь с данным ID или Именем не найден на сайте Вконтакте!');
define('header_index_vkontakte_add_to_profile', 'Добавить для наблюдения в ваш профиль!');
define('header_index_vkontakte_add_to_profile_complete', 'Пользователь успешно добавлен в ваш профиль!');
define('header_index_vkontakte_not_exist_user', 'У вас пока нет добавленных пользователей для мониторинга!<br>ID или ИМЯ пользователя можно добавить в поле которое находится ниже :-)');
define('header_index_vkontakte_add_to_profile_complete', 'Пользователь успешно добавлен в ваш профиль!');
define('header_index_vkontakte_user_is_in_db', 'За данным пользователем уже ведется наблюдение! Кто-то уже ранее добавил его для мониторинга в свой профиль. Добавьте данного пользователя в свой профиль для удобства в дальнейшем или кликните по аватарке ниже для просмотра накопленной статистики мониторинга!');
define('header_index_vkontakte_user_is_in_profile', 'Данный пользователь уже добавлен в ваш профиль! Кликните по аватарке ниже для просмотра накопленной статистики мониторинга!');
define('header_index_vkontakte_user_is_in_db_not_register', 'За данным пользователем уже ведется наблюдение! Кто-то уже ранее добавил его для мониторинга в свой профиль. Кликните по аватарке ниже для просмотра накопленной статистики мониторинга. Если вы <a href="/'.$language_code.'/register.html">зарегистрируетесь</a> или авторизуетесь через форму которая находится выше, то сможете добавлять пользователей в свой профиль для удобства просмотра!');
define('header_index_vkontakte_user_not_db_not_register', 'За данным пользователем наблюдение не ведется! Если вы <a href="/'.$language_code.'/register.html">зарегистрируетесь</a> или авторизуетесь через форму которая находится выше, то сможете добавлять пользователей в свой профиль и просматривать статистику.');
define('header_index_vkontakte_online_now', 'online');
define('header_index_vkontakte_offline_now', 'offline');
define('header_index_vkontakte_is_mobile', 'Пользователь использует мобильное устройство либо мобильную версию сайта');
define('header_index_vkontakte_stats_info_how', 'Для просмотра статистики онлайн статусов, кликните по аватарке или по ссылке с именем :-)');
define('header_index_vkontakte_num_in_page', 'Отображать на странице');
define('header_index_sort_view_from_up', 'Сортировка по просмотрам (Большее → Меньшее)');
define('header_index_sort_view_from_down', 'Сортировка по просмотрам (Меньшее → Большее)');
define('header_index_sort_abc_from_up', 'Сортировка по алфавиту (Я-А или Z-A)');
define('header_index_sort_abc_from_down', 'Сортировка по алфавиту (А-Я или A-Z)');
define('header_index_sort_date_from_up', 'Сортировка по дате добавления (Последняя → Первая)');
define('header_index_sort_date_from_down', 'Сортировка по дате добавления (Первая → Последняя)');
define('header_index_add_friends_title', 'Добавленных друзей');
define('header_index_del_friends_title', 'Удаленных друзей');
define('header_index_add_status_title', 'Добавленных статусов(текстовых)');
define('header_index_table_find_friends', 'Общие друзья у добавленных для мониторинга аккаунтов Вконтакте.ру');

# сообщения для auth.php - admin, index
define('auth_error', 'Данные для авторизации неверны! Проверьте еще раз вводимый логин и пароль или воспользуйтесь функцией восстановления пароля.');
define('auth_complete', 'Вы успешно авторизованы!');
define('auth_link_next', 'продолжить →');

# сообщения для lostpass.php - admin, index
define('lostpass_login', 'Ваш логин');
define('lostpass_or', 'или');
define('lostpass_email', 'Ваш e-mail');
define('lostpass_no_login_email_error', 'Вы не ввели Логин или Email!');
define('lostpass_secretcode', 'Введите секретный код');
define('lostpass_login_error', 'Недопустимый логин!');
define('lostpass_email_error', 'Недопустимый E-mail!');
define('lostpass_user_not_found_error', 'Такой пользователь не был зарегистрирован, либо вы не верно указали логин или email!');
define('lostpass_secret_code_bad', 'Вы ввели неверный антиспам-код!');
define('lostpass_link_send_notice_topic', 'ссылка для генерации нового пароля пользователя');
define('lostpass_link_send_notice_text', 'Здравствуйте. Для создания нового пароля, вам необходимо перейти по данной ссылке');
define('lostpass_link_send_message', 'На ваш почтовый ящик было отправлено письмо с уникальной ссылкой, по которой необходимо перейти, для генерации нового пароля.');
define('lostpass_secure_code_error', 'Код восстановления пароля неверен!');
define('lostpass_password_send_notice_topic', 'восстановление пароля для пользователя');
define('lostpass_password_send_notice_text', 'Здравствуйте. Ваш новый пароль');
define('lostpass_password_send_message', 'На ваш почтовый ящик было отправлено письмо с новым паролем!');
define('lostpass_button_go', 'выполнить →');
define('lostpass_link_prev', '← назад');
define('lostpass_link_next', 'продолжить →');
define('lostpass_field_important', 'поля обязательные для заполнения или выбора');

# сообщения для lostpass.php - index
define('lostpass_index_workspace_title', 'Восстановление пароля');

# сообщения для cron_monitor_delete_inactive_insert_to_db.php - index
define('cron_monitor_delete_inactive_insert_to_db_delete_user', 'планируется удаление пользователя');
define('cron_monitor_delete_inactive_insert_to_db_notice_text_a', 'Здравствуйте. Статистика добавленной к Вам в профиль анкеты:');
define('cron_monitor_delete_inactive_insert_to_db_notice_text_b', 'не просматривалась более 21 дня. Если в течении 10 дней не будет зарегистрировано просмотров статистики, то все данные по данному пользователю будут удалены.');
define('cron_monitor_delete_inactive_insert_to_db_notice_text_c', 'не просматривалась более 30 дней. Если в течении 1 дня не будет зарегистрировано просмотров статистики, то все данные по данному пользователю будут удалены.');

# сообщения для get_sms.php - index
define('get_sms_not_auth_user', 'Для получения бонусных СМС, вам необходимо авторизоваться на сайте или зарегистрироваться!');
define('get_sms_button', 'Получить 10 смс →');
define('get_sms_bonus_exist', 'Вы уже воспользовались бонусом!<br>Проверьте в вашем <a target=blank href="/'.$language_code.'/profile.html">Профиле</a> должно быть начислено 10 СМС.');
define('get_sms_bonus_ok', 'Вам начислено 10 бонусных СМС! <br>Проверьте в <a target=blank href="/'.$language_code.'/profile.html">Профиле</a> Ваш баланс.<br>Теперь вы можете настроить отправку СМС на странице просмотра статистики пользователя кликнув по иконке <img src="/templates/main/index/images/send_message.gif">');

# сообщения для profile.php - admin, index
define('profile_header_accout', 'Личные данные');
define('profile_workspace_title', 'Ваш профиль');
define('profile_login', 'Логин');
define('profile_login_notice', 'Логин изменять нельзя');
define('profile_password1', 'Пароль');
define('profile_password_notice', 'Если хотите изменить, то введите новый или оставьте пустым для сохранения старого. Не менее 6 символов. Может содержать любые буквы, цифры, знаки');
define('profile_password2', 'Повторите пароль');
define('profile_email', 'E-mail');
define('profile_email_notice', 'Адрес электронной почты в формате name@domain.зона');
define('profile_password1_empty', 'Поле Пароль не заполнено!');
define('profile_password2_empty', 'Поле Подтверждения пароля не заполнено!');
define('profile_password1_password2_error', 'Пароли не совпадают! Введите еще раз внимательнее.');
define('profile_password_error', 'Длина пароля не может быть менее 6 символов!');
define('profile_email_empty', 'Поле E-mail не заполнено!');
define('profile_email_error', 'Недопустимый E-mail! См. примечания к данному полю.');
define('profile_email_dublicate', 'Указанный вами E-mail уже занят! Воспользуйтесь функцией восстановления пароля, на данный ящик, если он принадлежит вам.');
define('profile_update_complete', 'Ваш профиль был успешно обновлен!');
define('profile_data_not_change', 'Вы не изменяли никаких данных в профиле!');
define('profile_button_change', 'Изменить!');
define('profile_field_important', 'поля обязательные для заполнения или выбора');

define('profile_header_pay', 'Оплата СМС уведомлений');
define('profile_text_your_balance', 'Ваш баланс');
define('profile_text_rub', 'руб. =');
define('profile_text_sms_to_send', 'смс');
define('profile_about_pay', 'Включается <u>отдельно</u> для каждого пользователя за которым у вас ведется мониторинг(значок с конвертом). Стоимость одной СМС - <u>1.5 рубля</u> с учетом НДС. Платежи в ин. валюте конвертируются в рубли по курсу ЦБ. Платеж происходит через защищенную платежную систему и автоматически ваш баланс пополняется в течении 10 минут. Безопасность гарантируется. Деньги снимаются только за отправленные СМС.<br>Возможна оплата через систему <u>WebMoney</u>, <u>Yandex.Деньги</u> (координаты <a href="/donate.html">здесь</a>), для этого отправьте необходимую сумму. В основании платежа укажите свой логин или напишите мне на форуме. В течении 10 часов баланс будет пополнен.');
define('profile_purpose_where', 'Оплата СМС уведомлений');
define('profile_button_pay', 'Оплатить!');
define('profile_select_rur', 'рублей');
define('profile_select_usd', 'долларов');
define('profile_select_eur', 'евро');
define('profile_select_uah', 'украинской гривны');

define('profile_header_howto', 'Статистика по отслеживаниям');
define('profile_header_howto_your', 'Число отслеживаемых пользователей в вашем профиле:');
define('profile_header_howto_all', 'Число всех отслеживаемых пользователей сервиса:');

# сообщения для profile.php - index
define('profile_index_main_page', 'Главная');
define('profile_index_timezone', 'Часовой пояс');
define('profile_index_timezone_notice', 'Необходимо выбрать для правильного отображения времени');
define('profile_index_timezone_error', 'Вы не выбрали временную зону!');
define('profile_money_complete_good', 'Платеж был успешно произведен! На счет деньги поступят в течении 10 минут! Спасибо!');
define('profile_money_complete_bad', 'Платеж не был произведен!');

# сообщения для news.php - admin
define('news_workspace_title', 'Новости');
define('news_panel_list', 'Новости сайта');
define('news_add_panel', 'Добавление новости сайта');
define('news_edit_panel', 'Редактирование новости сайта');
define('news_not_exist', 'Нет добавленных новостей для вывода.');
define('news_title', 'Заголовок новости');
define('news_title_notice', 'Разрешены только русские, латинские буквы, цифры, знаки препинания. Не более 100 символов и не менее 3.');
define('news_short_text', 'Начало новости');
define('news_short_text_notice', 'Разрешены только русские, латинские буквы, цифры, знаки препинания. Не более 150.000 символов и не менее 10.');
define('news_big_text', 'Продолжение новости');
define('news_big_text_notice', 'Разрешены только русские, латинские буквы, цифры, знаки препинания. Не более 150.000 символов и не менее 50.');
define('news_title_bad_name', 'Вы ввели недопустимый заголовок новости!');
define('news_short_text_empty', 'Поле начало новости не может быть пустым!');
define('news_short_text_bad_count_length', 'Поле начало новости не может содержать менее 10 или более 150.000 символов!');
define('news_short_text_bad_length_word', 'Поле начало новости не может содержать очень длинные слова!');
define('news_big_text_bad_count_length', 'Поле продолжение новости не может содержать менее 50 или более 150.000 символов!');
define('news_big_text_bad_length_word', 'Поле продолжение новости не может содержать очень длинные слова!');
define('news_add_complete', 'Новость была успешно добавлена!');
define('news_edit_not_exist', 'Невозможно выбрать данную новость для редактирования!');
define('news_edit_complete', 'Новость успешно отредактирована!');
define('news_delete_not_exist', 'Такой новости для удаления не существует!');
define('news_delete_complete', 'Новость успешно удалена!');
define('news_field_important', 'поля обязательные для заполнения или выбора');
define('news_link_and_button_edit', 'правка');
define('news_link_and_button_delete', 'удалить');
define('news_button_add', 'добавить →');

# сообщения для index.php - index
define('index_news_not_exist', 'Статей для вывода не найдено!');
define('index_news_view_full', 'просмотр полной версии статьи...');
define('index_news_view_more', 'Читать дальше');
define('index_news_main_page', 'Главная');
define('index_news_view_comment', 'комментарии');
define('index_news_secret_code', 'антиспам код:');
define('index_news_secret_code_bad', 'Вы ввели неверный антиспам-код!');
define('index_news_comment_text_empty', 'Поле комментария не может быть пустым!');
define('index_news_comment_text_bad_count_length', 'Поле комментария не может содержать менее 4 или более 600 символов!');
define('index_news_comment_add_complete', 'Комментарий успешно добавлен!');
define('index_comment_textarea_edit', 'редактирование комментария');
define('index_comment_admin_textarea_message', 'сообщение администратора');
define('index_comment_edit', 'правка');
define('index_comment_update_complete', 'Комментарий был успешно обновлен!');
define('index_comment_admin_textarea_message_error', 'Поле сообщения пользователю не может содержать менее 4 или более 600 символов!');
define('index_comment_admin_delete_error', 'При удалении сообщения пользователя, обязательно должно быть заполнено сообщение к комментарию от администратора сайта(причина)!');
define('index_comment_admin_delete_empty_error', 'Вы не можете удалить пустое сообщение!');
define('index_comment_admin_text_title', 'Cообщение от администрации:');
define('index_comment_user_text_delete', 'комментарий был удален...');
define('index_link_and_button_delete', 'удалить');
define('index_button_send', 'отправить →');

# сообщения для register.php - index
define('register_access_denied', 'Вы уже регистрировались ранее! Воспользуйтесь функцией восстановления пароля, если данные для авторизации утеряны.');
define('register_main_page', 'Главная');
define('register_workspace_title', 'Регистрация');
define('register_login', 'Логин');
define('register_login_notice', 'Только латинские буквы и знаки (-_). Не менее 3 символов');
define('register_password1', 'Пароль');
define('register_password_notice', 'Не менее 6 символов. Может содержать любые буквы, цифры, знаки');
define('register_password2', 'Повторите пароль');
define('register_email', 'E-mail');
define('register_email_notice', 'Адрес электронной почты в формате name@domain.зона');
define('register_timezone', 'Часовой пояс');
define('register_timezone_notice', 'Необходимо выбрать для правильного отображения времени');
define('register_rules', 'Правила работы');
define('register_login_empty', 'Поле Логин не заполнено!');
define('register_login_error', 'Недопустимый Логин! См. примечания к данному полю.');
define('register_login_dublicate', 'Указанный вами Логин уже занят!');
define('register_password1_empty', 'Поле Пароль не заполнено!');
define('register_password2_empty', 'Поле Подтверждения пароля не заполнено!');
define('register_password1_password2_error', 'Пароли не совпадают! Введите еще раз внимательнее.');
define('register_password_error', 'Длина пароля не может быть менее 6 символов!');
define('register_email_empty', 'Поле E-mail не заполнено!');
define('register_email_error', 'Недопустимый E-mail! См. примечания к данному полю.');
define('register_email_dublicate', 'Указанный вами E-mail уже занят! Воспользуйтесь функцией восстановления пароля, на данный ящик, если он принадлежит вам.');
define('register_timezone_error', 'Вы не выбрали временную зону!');
define('register_secret_code_bad', 'Вы ввели неверный антиспам-код!');
define('register_process_complete', 'Вы успешно зарегистрированы!');
define('register_rules_text', '
Правила которые соблюдает администрация сайта:
 - Отслеживать и удалять спам-регистраций и сообщений: рекламных, нецензурных, противоправных и др.
 - Не распространять третьим лицам e-mail адреса, пароли и другую контактную информацию, которая в дальнейшем может
быть использована для рассылки спама и других противоправных действий.
 - Уведомлять пользователей через новостную ленту обо всех приближающихся и произошедших изменениях, событиях на сайте.

Правила которые необходимо соблюдать пользователям:
 - Не размещать оскорбительных, угрожающих, клеветнических, порнографических данных, призывов к национальной розни и прочих сообщений, которые могут нарушить соответствующие законы.
Попытки размещения таких данных могут привести к немедленному удалению вашего аккаунта.
 - Вы соглашаетесь с тем, что администрация сайта имеет право удалить, изменить ваш аккаунт в любое время по своему усмотрению.
 - Как пользователь вы согласны с тем, что введённая вами информация будет храниться в базе данных. Хотя эта информация не будет открыта третьим лицам, администрация сайта не может быть ответственна за действия хакеров, которые могут привести к несанкционированному доступу к ней.
 - Наш сайт используют cookies для хранения информации на вашем компьютере. Эти cookie служат лишь для улучшения качества работы сервиса(подтверждения регистрации).
 - Ваш e-mail адрес используется только для возможности восстановления пароля и рассылки дополнительной информации касающейся работы сервиса.

Нажатием на кнопку "РЕГИСТРАЦИЯ" вы подтверждаете своё СОГЛАСИЕ с этими условиями.
');
define('register_field_important', 'поля обязательные для заполнения или выбора');
define('register_button', 'Регистрация!');
define('register_link_next', 'продолжить →');

# сообщения для help.php - index
define('help_how_to_id_or_name', 'Определить ID номер или Имя пользователя можно <b>перейдя на его страницу</b>.<br><br>В адресной строке вашего браузера (например Internet Explorer, Opera, Firefox и д.р) вы увидите адрес вида <b>http://vk.com/id*******</b>, где вместо звездочек стоят цифры или адрес <b>http://vk.com/name</b>.<br><br>&nbsp;&nbsp;В первом случае <b>id*******</b> - это ID номер пользователя.<br><br>&nbsp;&nbsp;Во втором <b>name</b> - это Имя пользователя.<br><br>Полученный ID или Имя добавьте в поле <b>"ID или ИМЯ пользователя"</b>.<br><br>На рисунках ниже, для примера, ID и Имя пользователя подчеркнуты.');
define('help_main_page', 'Главная');
define('help_workspace_title', 'Как узнать id или имя?');

# сообщения для donate.php - index
define('donate_main_page', 'Главная');
define('donate_workspace_title', 'Отблагодарить автора проекта :-)');
define('donate_purpose_where', 'Поддержка работы сервиса');

# сообщения для monitor_online_vk.php - index
define('monitor_online_vk_date_error', 'Вы задали некорректный промежуток времени!');
define('monitor_online_vk_main_page', 'Главная');
define('monitor_online_vk_online_now', 'online');
define('monitor_online_vk_offline_now', 'offline');
define('monitor_online_vk_date_day', 'день');
define('monitor_online_vk_date_month', 'месяц');
define('monitor_online_vk_date_year', 'год');
define('monitor_online_vk_date_hour', 'час');
define('monitor_online_vk_date_min', 'мин.');
define('monitor_online_vk_date_sec', 'сек.');
define('monitor_online_vk_date_from', 'с');
define('monitor_online_vk_date_to', 'по');
define('monitor_online_vk_submit_filter_button', 'Изменить!');
define('monitor_online_vk_all_time_online', 'За весь заданный период в фильтре, времени ONLINE:');
define('monitor_online_vk_today_time_online', 'за этот день в online:');
define('monitor_online_vk_not_data', 'Данных пока нет. Попробуйте обновить страницу позднее (5-15 мин).');
define('monitor_online_vk_prev', '← назад');
define('monitor_online_vk_next', 'далее →');
define('monitor_online_vk_workspace_title', 'Статистика проведенного времени Онлайн');
define('monitor_online_vk_timezone', 'Часовой пояс:');
define('monitor_online_vk_sound_user_title', 'Выкл/Вкл звуковое оповещение при выходе в онлайн');
define('monitor_online_vk_delete_user_title', 'Удалить пользователя (прекратить слежку)');
define('monitor_online_vk_add_messenger', 'Отправка уведомлений о появлении пользователя по СМС, XMPP/Jabber');
define('monitor_online_vk_num_in_page', 'Отображать на странице');
define('monitor_online_vk_last_status', 'Последние статусы');
define('monitor_online_vk_filter_date', 'Фильтр по дате для статусов и онлайн состояний');
define('monitor_online_vk_status_not_data', 'Данных пока нет');
define('monitor_online_vk_info', 'Разная информация');
define('monitor_online_vk_online', 'Мониторинг онлайн состояний');
define('monitor_online_vk_not_in_profile', 'Добавлено в профили: 0 человек');
define('monitor_online_vk_in_profile_and_you_1', 'Добавлено в профили:');
define('monitor_online_vk_in_profile_and_you_2', 'человек и у Вас.');
define('monitor_online_vk_in_you_profile', 'Добавлено в профили: только у вас');
define('monitor_online_vk_online_first_record', 'Дата первой записи online');
define('monitor_online_vk_reg_date', 'Дата регистрации вконтакте');
define('monitor_online_vk_reg_date_not_detect', 'не определена');
define('monitor_online_vk_view_title', 'Показывать статистику');
define('monitor_online_vk_all_view', 'компьютер + мобильный');
define('monitor_online_vk_pc_view', 'компьютер');
define('monitor_online_vk_mobile_view', 'мобильный');

# сообщения для compare.php - index
define('compare_vkontakte_button_user_title', 'Совместно проведенное время с другим пользователем ВКонтакте');
define('compare_vkontakte_main_page', 'Главная');
define('compare_vkontakte_workspace_title', 'Статистика совместно проведенного(за месяц) времени для:');
define('compare_vkontakte_and', 'и');
define('compare_vkontakte_width', 'сравнить с');
define('compare_button', 'сравнить →');
define('compare_today_time_online', 'вместе за этот день в online:');
define('compare_not_data', 'Выбранные пользователи пока не находились в online в одно время!');

# сообщения для avatar.php - index
define('avatar_vkontakte_button_user_title', 'Формирование ссылки на аватарку с текущими статусом');
define('avatar_vkontakte_main_page', 'Главная');
define('avatar_vkontakte_workspace_title', 'Формирование ссылки на аватарку с текущими статусом');
define('avatar_default_header', 'Стандартная аватарка(только картинка)');
define('avatar_vkontakte_default_description', 'Прямая ссылка на на аватарку (картинка в формате JPEG)');
define('avatar_vkontakte_default_how_to_view', 'Как будет выглядеть');
define('avatar_vkontakte_default_code_to_forum', 'Код для вставки в подпись на форумы. При клике, переход на статистику Nayavu');
define('avatar_vkontakte_default_code_to_vk', 'Код для вставки в подпись на форумы. При клике, переход на страницу ВКонтакте');

# сообщения для friends.php - index
define('friends_vkontakte_main_page', 'Главная');
define('friends_vkontakte_workspace_title', 'Отслеживание друзей пользователя');
define('friends_default_header', 'Список друзей');
define('friends_vkontakte_button_update', 'Обновить список →');
define('friends_vkontakte_last_time_update', 'последние изменения в друзьях были');
define('friends_message_first_list_get', 'Первоначальный список друзей успешно загружен! Вы можете посмотреть его ниже.');
define('friends_message_change_list', 'Найдены изменения в друзьях(добавлены новые или какие-то удалены)! Изменения можете посмотреть ниже.');
define('friends_message_not_change_list', 'Изменений в списке друзей не было найдено(нет новых добавленных или каких-то удаленных)!');

# сообщения для friends_hidden.php - index
define('friends_vkontakte_hidden_main_page', 'Главная');
define('friends_vkontakte_hidden_monitoring_workspace_title', 'Отслеживание друзей пользователя');
define('friends_vkontakte_hidden_where_workspace_title', 'У кого в друзьях?');

# сообщения для monitor_online_vk.php, compare.php - index
define('voskr', 'воскресенье');
define('poned', 'понедельник');
define('vtornik', 'вторник    ');
define('sreda', 'среда      ');
define('chetverg', 'четверг    ');
define('pyatnica', 'пятница    ');
define('subbota', 'суббота    ');

# сообщения для delete_vk_user.php - index
define('delete_vk_user_in_profile_complete', 'Пользователь был успешно удален из вашего профиля!');
define('delete_vk_user_in_all_complete', 'Пользователь был успешно полностью удален!');
define('delete_vk_user_in_all_send_notice_topic', 'удален пользователь');
define('delete_vk_user_admin_delete_send_notice_topic', 'просьба удалить пользователя');
define('delete_vk_user_in_all_send_notice_text', 'Здравствуйте. Уведомляем вас о том, что был полностью удален пользователь:');
define('delete_vk_user_admin_delete_send_notice_text', 'Здравствуйте. Поступила просьба удалить пользователя. Имеем следующие данные:');
define('delete_vk_user_in_all_desc_why_del1', 'Причины по которым за пользователем прекращен мониторинг и полностью удалена вся история, могут быть следующие:');
define('delete_vk_user_in_all_desc_why_del2', '1) Вы выбрали функцию полного удаления данного пользователя;');
define('delete_vk_user_in_all_desc_why_del3', '2) Один из зарегистрированных участников сайта удалил данного пользователя. Т.к он являлся ПЕРВЫМ кто добавил его для мониторинга и соответственно имел полное право это сделать;');
define('delete_vk_user_in_all_desc_why_del4', '3) Администратору сайта поступило сообщение от незарегистрированного участника с просьбой об удалении данного пользователя по везким причинам.');
define('delete_vk_user_id_not_exist', 'Такого пользователя не существует!');
define('delete_vk_user_id_not_in_profile', 'Данного пользователя нет ни в одном профиле!');
define('delete_vk_user_only_from_profile', '<b>Удалить пользователя только из вашего профиля</b> (при этом мониторинг будет по прежнему активен и вся предыдущая история мониторинга будет сохранена, т.е доступна другим участникам сайта).');
define('delete_vk_user_all', '<b>Удалить пользователя полностью</b> (при этом пользователь удаляется из вашего профиля, останавливается мониторинг за онлайн статусами и удаляется вся сохраненная история мониторинга, т.е полностью все следы).');
define('delete_vk_user_delete_link_profile', 'Удалить из профиля');
define('delete_vk_user_delete_link_all', 'Удалить полностью все данные');
define('delete_vk_user_all_why', '<b>Удалить пользователя полностью</b> (при этом пользователь удаляется из вашего и профилей других пользователей, которые также наблюдали за этим человеком, останавливается мониторинг за онлайн статусами и удаляется вся сохраненная история мониторинга, т.е полностью все следы). Т.к данный пользователь не только в вашем профиле и вы не первый кто начал за ним мониторинг, необходимо решение администрации сайта.');
define('delete_vk_user_all_why_from_user', 'Причины по которым вы хотите удалить данного пользователя(обязательно):');
define('delete_vk_user_delete_button_all', 'Отправить запрос на удаление пользователя');
define('delete_vk_user_why_text_error', 'Вы не написали причину по которой вы хотите удалить пользователя (число символов не менее 5 и не более 1000)!');
define('delete_vk_user_send_message_to_admin', 'Администратору сайта была отправлена просьба с требованием удалить пользователя!');
define('delete_vk_user_link_next', 'продолжить →');
define('delete_vk_user_not_in_profile_all_why', '<b>Удалить пользователя полностью</b> (при этом пользователь удаляется из профилей других посетителей, которые наблюдали за этим человеком, останавливается мониторинг за онлайн статусами и удаляется вся сохраненная история мониторинга, т.е полностью все следы). Т.к данного пользователя нет в вашем профиле, необходимо решение администрации сайта.');
define('delete_vk_user_not_auth_all_why', '<b>Удалить пользователя полностью</b> (при этом пользователь удаляется из профилей других посетителей, которые наблюдали за этим человеком, останавливается мониторинг за онлайн статусами и удаляется вся сохраненная история мониторинга, т.е полностью все следы). Вы не авторизованы на сайте, поэтому необходимо решение администрации сервиса.');
define('delete_vk_user_email_not_auth_user', 'Ваш E-mail для связи(если возникнут вопросы):');
define('delete_vk_user_email_empty', 'Поле E-mail не заполнено!');
define('delete_vk_user_email_error', 'Недопустимый E-mail!');

# сообщения для add_vk_messenger.php - index
define('add_vk_messenger_id_not_exist', 'Такого пользователя не существует!');
define('add_vk_messenger_link_next', 'продолжить →');
define('add_vk_messenger_about', '<b>Вы можете настроить отправку уведомлений о том, что данный пользователь появился Онлайн следующими способами:<br> 1) СМС на Ваш мобильный телефон (Стоимость: <u>1.5 рубля</u> за 1 смс). Оплата производится в вашем <a href="/profile.html">Профиле</a>. Внесите удобную для вас сумму. Как только баланс будет положительный (до 10 минут после оплаты) - начнется отправка СМС уведомлений.<br>2) XMPP сообщения (<u>бесплатно</u>)</b>');

define('add_vk_messenger_sms_header', 'Настройка СМС');
define('add_vk_messenger_about_sms', 'Введите ваш номер мобильного телефона (без "+")');
define('add_vk_sms_value_data_sms', 'например: 79631234567');
define('add_vk_messenger_send_data_sms_empty', 'Вы не заполнили поле с вашим мобильным телефоном!');
define('add_vk_messenger_send_data_sms_bad', 'Вы ввели недопустимый номер мобильного телефона! Разрешены только цифры! Номер обязательно с кодом в начале (например 79631234567, где 7 код)');
define('add_vk_messenger_send_data_sms_time_empty', 'Поля временных промежутков для отправки СМС должны быть обязательно заполнены!');
define('add_vk_messenger_send_data_sms_time_bad', 'Вы неверно заполнили временные промежутки для отправки СМС!');
define('add_vk_messenger_send_data_sms_complete', 'Данные для отправки СМС сообщений приняты!');
define('add_vk_messenger_delete_sms', 'прекратить отправку СМС (x)');
define('add_vk_messenger_button_send_messenger_sms', 'Установить отправку СМС уведомлений');
define('add_vk_messenger_delete_data_sms_complete', 'Рассылка сообщений по СМС для данного пользователя успешно остановлена!');

define('add_vk_messenger_xmpp_header', 'Настройка интернет-мессенджера: XMPP');
define('add_vk_messenger_about_xmpp', 'Вы можете бесплатно и в неограниченных количествах получать сообщения на ваш <b>Jabber/XMPP</b> клиент.<br>Например это может быть <a target="_blank" href="http://welcome.qip.ru/im?utm_source=mainqip&utm_medium=cpc&utm_content=download&utm_campaign=main_download">QIP</a> – бесплатная программа мгновенного обмена сообщениями.<br><br><b><u>Настройки подключения следующие:</u></b><br><u>Сервер</u>: nayavu.com<br><u>Порт</u>: 5222<br><u>Логин</u>: Ваш логин на сайте nayavu.com<br><u>Пароль</u>: Ваш пароль на сайте nayavu.com<br><br><img src="/templates/main/index/images/qip_howto.png">&nbsp;<a href="/'.$language_code.'/qip_xmpp.html" target="_blank">Подробная инструкция по подключению вашей учетной записи на примере QIP →</a>');
define('add_vk_messenger_send_data_messenger_time_empty', 'Поля временных промежутков для отправки сообщений через XMPP должны быть обязательно заполнены!');
define('add_vk_messenger_send_data_messenger_time_bad', 'Вы неверно заполнили временные промежутки для отправки сообщений через XMPP!');
define('add_vk_messenger_send_data_messenger_complete', 'Данные для отправки сообщений по XMPP приняты!');
define('add_vk_messenger_delete_xmpp', 'прекратить отправку уведомлений (x)');
define('add_vk_messenger_button_send_messenger_xmpp', 'Установить отправку уведомлений по XMPP для');
define('add_vk_messenger_delete_data_messenger_complete', 'Рассылка сообщений по XMPP для данного пользователя успешно остановлена!');

define('add_vk_messenger_not_time_send', 'не отправлять сообщения с');
define('add_vk_messenger_not_time_send_to', 'до');
define('add_vk_messenger_not_time_send_hour', 'часов (например ночью)');
define('add_vk_messenger_check_time1', 'Отправлять сообщение, если данный человек появился с Онлайн статусом и с последнего предыдущего посещения(');
define('add_vk_messenger_check_time2', ') прошло (минут)');
define('add_vk_messenger_not_in_online', 'еще не был в онлайне');
define('add_vk_messenger_send_check_time_bad', 'Недопустимое время проверки! Разрешено устанавливать значение минимум 30 минут и максимум 259200 минут(6 месяцев).');
define('add_vk_messenger_check_time_notice', 'минимум 30 минут&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;');
define('add_vk_messenger_important', 'поля обязательные для заполнения');

# сообщения для qip_xmpp.php - index
define('qip_xmpp_main_page', 'Главная');
define('qip_xmpp_workspace_title', 'Инструкция по подключению QIP к аккаунту XMPP');
?>