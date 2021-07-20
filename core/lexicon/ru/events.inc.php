<?php
/**
 * System Events English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */

$_lang['events'] = 'События';
$_lang['system_event'] = 'Системное событие';
$_lang['system_events'] = 'Системные события';
$_lang['system_events.desc'] = '<b>Системные события</b> — это события, регистрируемые MODX с помощью <i>плагинов</i>. Они могут «реагировать» внутри самого кода MODX, позволяя <i>плагинам</i> взаимодействовать с исходным кодом MODX и добавлять дополнительный функционал без изменения кода ядра. Вы также можете создавать собственные события для своего проекта. Системные события ядра MODX удалить нельзя, можно удалить только созданные вами.';
$_lang['system_events.search_by_name'] = 'Поиск по названию события';
$_lang['system_events.name_desc'] = 'Название события, которое вы должны использовать в вызове &dollar;modx->invoiceEvent(name, properties).';
$_lang['system_events.groupname'] = 'Группа';
$_lang['system_events.groupname_desc'] = 'Название группы, которой принадлежит новое событие. Выберите существующее или создайте новое.';
$_lang['system_events.plugins'] = 'Плагины';
$_lang['system_events.plugins_desc'] = 'Список плагинов, подключенных к событию. Выберите плагины, которые должны быть подключены к событию.';

$_lang['system_events.service'] = 'Служба';
$_lang['system_events.service_1'] = 'События службы парсера';
$_lang['system_events.service_2'] = 'События службы доступа в систему управления';
$_lang['system_events.service_3'] = 'События службы внешнего доступа';
$_lang['system_events.service_4'] = 'События службы кэша';
$_lang['system_events.service_5'] = 'События службы шаблонов';
$_lang['system_events.service_6'] = 'События определенные пользователем';

$_lang['system_events.remove_confirm'] = 'Вы уверены, что хотите удалить событие <b>[[+name]]</b>? Это действие необратимо!';

$_lang['system_events_err_ns'] = 'Не указано название системного события.';
$_lang['system_events_err_ae'] = 'Такое название системного события уже существует.';
$_lang['system_events_err_startint'] = 'Название не должно начинаться с цифры.';
$_lang['system_events_err_remove_not_allowed'] = 'Вы не можете удалить это системное событие.';
