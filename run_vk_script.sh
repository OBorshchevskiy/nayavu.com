#!/bin/sh
/usr/bin/php5 /var/www/core/vkontakte/cron_monitor_online_vkontakte.php
wait
sleep_time=3
/usr/bin/php5 /var/www/core/vkontakte/cron_get_status_vkontakte.php