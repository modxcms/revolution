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
$_lang['system_events.desc'] = 'System Events are the events in MODX that Plugins are registered to. They are "fired" throughout the MODX code, allowing Plugins to interact with MODX code and add custom functionality without hacking core code. You can create your own events for your custom project here too. You cannot delete core events, only your own.';
$_lang['system_events.search_by_name'] = 'Поиск по названию события';
$_lang['system_events.create'] = 'Create Event';
$_lang['system_events.name_desc'] = 'Название события, которое вы должны использовать в вызове &dollar;modx->invoiceEvent(name, properties).';
$_lang['system_events.groupname'] = 'Группа';
$_lang['system_events.groupname_desc'] = 'The name of the group where the event belongs to. Select an existing one or type a new group name.';
$_lang['system_events.plugins'] = 'Плагины';
$_lang['system_events.plugins_desc'] = 'Список плагинов, подключенных к событию. Выберите плагины, которые должны быть подключены к событию.';

$_lang['system_events.service'] = 'Служба';
$_lang['system_events.service_1'] = 'События службы парсера';
$_lang['system_events.service_2'] = 'События службы доступа в систему управления';
$_lang['system_events.service_3'] = 'События службы внешнего доступа';
$_lang['system_events.service_4'] = 'События службы кэша';
$_lang['system_events.service_5'] = 'События службы шаблонов';
$_lang['system_events.service_6'] = 'События определенные пользователем';

$_lang['system_events.remove'] = 'Delete Event';
$_lang['system_events.remove_confirm'] = 'Are you sure you want to delete the <b>[[+name]]</b> event? This is irreversible!';

$_lang['system_events_err_ns'] = 'Не указано название системного события.';
$_lang['system_events_err_ae'] = 'Такое название системного события уже существует.';
$_lang['system_events_err_startint'] = 'Название не должно начинаться с цифры.';
$_lang['system_events_err_remove_not_allowed'] = 'You\'re not allowed to delete this System Event.';
