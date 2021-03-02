<?php
/**
 * System Events English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */

$_lang['events'] = 'Події';
$_lang['system_event'] = 'Системна Подія';
$_lang['system_events'] = 'Системні події';
$_lang['system_events.desc'] = 'System Events are the events in MODX that Plugins are registered to. They are "fired" throughout the MODX code, allowing Plugins to interact with MODX code and add custom functionality without hacking core code. You can create your own events for your custom project here too. You cannot delete core events, only your own.';
$_lang['system_events.search_by_name'] = 'Пошук за назвою події ';
$_lang['system_events.create'] = 'Create Event';
$_lang['system_events.name_desc'] = 'Назва події, яку ви повинні використовувати у виклику &dollar;modx-> invokeEvent(name, properties). ';
$_lang['system_events.groupname'] = 'Група';
$_lang['system_events.groupname_desc'] = 'The name of the group where the event belongs to. Select an existing one or type a new group name.';
$_lang['system_events.plugins'] = 'Плагіни';
$_lang['system_events.plugins_desc'] = 'Список плагінів, підключених до події. Виберіть плагіни, які повинні бути підключені до події.';

$_lang['system_events.service'] = 'Служба';
$_lang['system_events.service_1'] = 'Події служби парсеру ';
$_lang['system_events.service_2'] = 'Події служби доступу в систему управління';
$_lang['system_events.service_3'] = 'Події служби зовнішнього доступу';
$_lang['system_events.service_4'] = 'Події служби кеша';
$_lang['system_events.service_5'] = 'Події служби шаблонів';
$_lang['system_events.service_6'] = 'Події визначені користувачем';

$_lang['system_events.remove'] = 'Delete Event';
$_lang['system_events.remove_confirm'] = 'Are you sure you want to delete the <b>[[+name]]</b> event? This is irreversible!';

$_lang['system_events_err_ns'] = 'Не вказано назву системної події. ';
$_lang['system_events_err_ae'] = 'Така назва системної події вже існує.';
$_lang['system_events_err_startint'] = 'Назва не повинна починатися з цифри.';
$_lang['system_events_err_remove_not_allowed'] = 'You\'re not allowed to delete this System Event.';
