<?php
/**
 * Config Check Russian lexicon topic
 *
 * @language ru
 * @package modx
 * @subpackage lexicon
 */
$_lang['configcheck_admin'] = 'Пожалуйста свяжитесь с системным администратором и предупредите его об этом сообщении!';
$_lang['configcheck_cache'] = 'невозможна запись в папку кэширования';
$_lang['configcheck_cache_msg'] = 'MODX не может писать в каталог кэша. MODX будет работать без кэширования. Чтобы решить эту проблему, сделайте каталог /_cache/ доступным для записи.';
$_lang['configcheck_configinc'] = 'Конфигурационный файл открыт для записи!';
$_lang['configcheck_configinc_msg'] = 'Ваш сайт является уязвимым для хакеров, которые могут нанести серьёзный вред вашему сайту. Пожалуйста, сделайте конфигурационный файл доступным только для чтения! Если вы не администратор сайта, свяжитесь с системным администратором и предупредите его об этом сообщение! Файл находится в [[+path]]';
$_lang['configcheck_default_msg'] = 'Неизвестное предупреждение было найдено. Это странно.';
$_lang['configcheck_errorpage_unavailable'] = 'Страница ошибки 404 «Документ не найден» недоступна.';
$_lang['configcheck_errorpage_unavailable_msg'] = 'Это означает, что страница ошибки 404 «Документ не найден» не доступна для посетителей вашего сайта. Это может привести к созданию условий для возникновения рекурсивного зацикливания и появлению многочисленных ошибок в логах сайта. Проверьте также не ограничен ли доступ к этой странице группам web-пользователей.';
$_lang['configcheck_errorpage_unpublished'] = 'Страница ошибки 404 «Документ не найден», указанная в настройках системы, не опубликована или не существует';
$_lang['configcheck_errorpage_unpublished_msg'] = 'Это означает, что страница 404 ошибки «Документ не найден» не доступна для посетителей вашего сайта. Опубликуйте эту страницу или проверьте что идентификатор страницы «Документ не найден» правильно указан в настройках системы.';
$_lang['configcheck_images'] = 'Папка изображений недоступна для записи';
$_lang['configcheck_images_msg'] = 'Папка изображений недоступна для записи или не существует на сервере. Из этого следует, что управление изображениями работать не будет!';
$_lang['configcheck_installer'] = 'Не удалена папка с файлами, использовавшимися в процессе установки';
$_lang['configcheck_installer_msg'] = 'Каталог setup/ содержит файлы установки для MODX. Только представьте себе, что может произойти, если злой человек найдёт его и запустит установку! Удалите этот каталог с вашего сервера. It is located at: [[+path]]';
$_lang['configcheck_lang_difference'] = 'Неверное количество записей в языковом файле';
$_lang['configcheck_lang_difference_msg'] = 'Количество записей в выбранном языке отличается от количества записей в языке по умолчанию. Это не является критической ошибкой, однако это повод для обновления языковых файлов.';
$_lang['configcheck_notok'] = 'В настройках системы присутствуют ошибки: ';
$_lang['configcheck_ok'] = 'Проверка прошла успешно — нет предупреждений для вывода.';
$_lang['configcheck_register_globals'] = '"register_globals" установлен в ON в вашем конфигурационном файле php.ini';
$_lang['configcheck_register_globals_msg'] = 'Эта настройка делает ваш сайт намного более подверженным XSS атакам. Свяжитесь со службой поддержки вашего хостинга и спросите как устранить эту проблему.';
$_lang['configcheck_title'] = 'Проверка конфигурации';
$_lang['configcheck_unauthorizedpage_unavailable'] = 'Страница ошибки 401 «Доступ запрещен» не опубликована или не существует.';
$_lang['configcheck_unauthorizedpage_unavailable_msg'] = 'Это означает что страница «Доступ запрещен» не доступна для посетителей вашего сайта. Это может привести к созданию условий для возникновения рекурсивного зацикливания и появлению многочисленных ошибок в логах сайта. Проверьте не назначена ли группа пользователей этой странице.';
$_lang['configcheck_unauthorizedpage_unpublished'] = 'Страница ошибки 401 «Доступ запрещен», указанная в настройках системы не опубликована.';
$_lang['configcheck_unauthorizedpage_unpublished_msg'] = 'Это означает что страница «Доступ запрещен» не доступна для посетителей вашего сайта. Опубликуйте эту страницу или проверьте что идентификатор страницы «Доступ запрещен" правильно указан в настройках системы.';
$_lang['configcheck_warning'] = 'Предупреждения о конфигурации сайта:';
$_lang['configcheck_what'] = 'Что это означает?';