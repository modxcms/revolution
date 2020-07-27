<?php
/**
 * Config Check English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */
$_lang['configcheck_admin'] = 'Пожалуйста, свяжитесь с администратором сайта и предупредите его об этом сообщении!';
$_lang['configcheck_allowtagsinpost_context_enabled'] = 'Настройка контекста allow_tags_in_post включена за пределами `mgr`';
$_lang['configcheck_allowtagsinpost_context_enabled_msg'] = 'Системная настройка allow_tags_in_post включена в вашей установке за пределами контекста mgr. MODX рекомендует отключить эту настройку, если вам не нужно явно разрешать пользователям посылать MODX теги, числовые сущности или HTML теги через метод POST в формах на вашем сайте. Ее следует отключить везде, кроме контекста mgr.';
$_lang['configcheck_allowtagsinpost_system_enabled'] = 'Системная настройка allow_tags_in_post включена';
$_lang['configcheck_allowtagsinpost_system_enabled_msg'] = 'Системная настройка allow_tags_in_post включена в вашей установке. MODX рекомендует отключить эту настройку, если вам не нужно явно разрешать пользователям посылать MODX теги, числовые сущности или HTML теги через метод POST в формах на вашем сайте. Лучше включить эту настройку через настройки контекста для отдельных контекстов.';
$_lang['configcheck_cache'] = 'Невозможна запись в папку кэширования';
$_lang['configcheck_cache_msg'] = 'MODX не может записать в каталог кэша. MODX будет работать без кэширования. Чтобы решить эту проблему, сделайте каталог /_cache/ доступным для записи.';
$_lang['configcheck_configinc'] = 'Конфигурационный файл открыт для записи!';
$_lang['configcheck_configinc_msg'] = 'Ваш сайт является уязвимым для хакеров, которые могут нанести серьёзный вред вашему сайту. Пожалуйста, сделайте конфигурационный файл доступным только для чтения! Если вы не администратор сайта, свяжитесь с ним и предупредите его об этом сообщении! Файл находится в [[+path]]';
$_lang['configcheck_default_msg'] = 'Было найдено неизвестное предупреждение. Это странно.';
$_lang['configcheck_errorpage_unavailable'] = 'Страница ошибки 404 «Документ не найден» недоступна.';
$_lang['configcheck_errorpage_unavailable_msg'] = 'Это означает, что страница ошибки 404 «Документ не найден» недоступна для посетителей вашего сайта. Это может привести к созданию условий для возникновения рекурсивного зацикливания и появлению многочисленных ошибок в логах сайта. Проверьте также не ограничен ли доступ к этой странице группам web-пользователей.';
$_lang['configcheck_errorpage_unpublished'] = 'Страница ошибки 404 «Документ не найден», указанная в настройках системы, не опубликована или не существует.';
$_lang['configcheck_errorpage_unpublished_msg'] = 'Это означает, что страница 404 ошибки «Документ не найден» недоступна для посетителей вашего сайта. Опубликуйте эту страницу или проверьте, что ID страницы «Документ не найден» правильно указан в настройках системы.';
$_lang['configcheck_htaccess'] = 'Каталог ядра в открытом доступе';
$_lang['configcheck_htaccess_msg'] = 'MODX обнаружил, что папка ядра доступна (частично) извне.
<strong>Это не рекомендуется и опасно для безопасности.</strong>
Если ваша установка MODX запущена на веб-сервере Apache, вы должны создать файл .htaccess внутри папки ядра <em>[[+fileLocation]]</em>. Это также можно сделать, переименовав существующий уже файл-пример ht.access в .htaccess.
<p>Также существуют другие методы и веб-серверы, которые вы можете использовать, прочтите <a href="https://docs.modx.com/3.x/en/getting-started/maintenance/securing-modx">Руководство по усилению защиты MODX</a> для информации о дополнительной защите вашего сайта.</p>
Если вы правильно всё настроили, откройте в браузере, например, список изменений - <a href="[[+checkUrl]]" target="_blank">Changelog</a>. Эта ссылка должна дать вам 403 код (доступ запрещён) или 404 (не найдено). Если вы можете видеть список изменений в браузере, что-то все ещё не так и вам нужно изменить конфигурацию или найти специалиста для решения этой проблемы.';
$_lang['configcheck_images'] = 'Папка изображений недоступна для записи';
$_lang['configcheck_images_msg'] = 'Папка изображений недоступна для записи или не существует на сервере. Из этого следует, что управление изображениями работать не будет!';
$_lang['configcheck_installer'] = 'Не удалена папка с файлами, использовавшимися в процессе установки';
$_lang['configcheck_installer_msg'] = 'The setup/ directory contains the installer for MODX. Just imagine what might happen if an evil person finds this folder and runs the installer! They probably won\'t get too far, because they\'ll need to enter some user information for the database, but it\'s still best to delete this folder from your server. It is located at: [[+path]]';
$_lang['configcheck_lang_difference'] = 'Неверное количество записей в языковом файле';
$_lang['configcheck_lang_difference_msg'] = 'Количество записей в выбранном языке отличается от количества записей в языке по умолчанию. Это не является критической ошибкой, однако это повод для обновления языковых файлов.';
$_lang['configcheck_notok'] = 'В настройках системы присутствуют ошибки: ';
$_lang['configcheck_ok'] = 'Проверка прошла успешно — предупреждений нет.';
$_lang['configcheck_phpversion'] = 'Версия PHP устарела';
$_lang['configcheck_phpversion_msg'] = 'Ваша версия PHP [[+phpversion]] больше не поддерживается сообществом PHP-разработчиков. Это означает, что обновления безопасности недоступны. Также есть вероятность, что MODX или дополнительные пакеты уже сейчас или в ближайшем будущем больше не будут поддерживать эту версию. Пожалуйста, обновите среду по крайней мере до PHP [[+phprequired]] как можно скорее для обеспечения безопасности вашего сайта.';
$_lang['configcheck_register_globals'] = '"register_globals" установлен в ON в вашем конфигурационном файле php.ini';
$_lang['configcheck_register_globals_msg'] = 'Эта настройка делает ваш сайт намного более подверженным XSS атакам. Свяжитесь со службой поддержки вашего хостинга и спросите, как устранить эту проблему.';
$_lang['configcheck_title'] = 'Проверка конфигурации';
$_lang['configcheck_unauthorizedpage_unavailable'] = 'Страница ошибки 403 «Доступ запрещен» не опубликована или не существует.';
$_lang['configcheck_unauthorizedpage_unavailable_msg'] = 'Это означает, что страница «Доступ запрещен» недоступна для посетителей вашего сайта. Это может привести к созданию условий для возникновения рекурсивного зацикливания и появлению многочисленных ошибок в логах сайта. Проверьте не назначена ли группа пользователей этой странице.';
$_lang['configcheck_unauthorizedpage_unpublished'] = 'Страница ошибки 403 «Доступ запрещен», указанная в настройках системы, не опубликована.';
$_lang['configcheck_unauthorizedpage_unpublished_msg'] = 'Это означает, что страница «Доступ запрещен» недоступна для посетителей вашего сайта. Опубликуйте эту страницу или проверьте, что ID страницы «Доступ запрещен» правильно указан в настройках системы.';
$_lang['configcheck_warning'] = 'Предупреждения о конфигурации сайта:';
$_lang['configcheck_what'] = 'Что это означает?';
