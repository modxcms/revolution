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
$_lang['system_events.desc'] = '<b>Системні події </b> - це події, що реєструються MODX за допомогою <i>плагінів</i>. Вони можуть «реагувати» всередині самого коду MODX, дозволяючи <i>плагинам</i> взаємодіяти з кодом MODX і додавати довільний функціонал без зміни коду ядра. Ви також можете створювати власні події для свого проекту. Системні події ядра MODX не можна видалити, лише створені вами. ';
$_lang['system_events.search_by_name'] = 'Пошук за назвою події ';
$_lang['system_events.create'] = 'Створити нову подію ';
$_lang['system_events.name_desc'] = 'Назва події, яку ви повинні використовувати у виклику &dollar;modx-> invokeEvent(name, properties). ';
$_lang['system_events.groupname'] = 'Група';
$_lang['system_events.groupname_desc'] = 'Назва групи, якій належить нова подія. Виберіть існуючу або створіть нову назву групи.';
$_lang['system_events.plugins'] = 'Плагіни';
$_lang['system_events.plugins_desc'] = 'Список плагінів, підключених до події. Виберіть плагіни, які повинні бути підключені до події.';

$_lang['system_events.service'] = 'Служба';
$_lang['system_events.service_1'] = 'Події служби парсеру ';
$_lang['system_events.service_2'] = 'Події служби доступу в систему управління';
$_lang['system_events.service_3'] = 'Події служби зовнішнього доступу';
$_lang['system_events.service_4'] = 'Події служби кеша';
$_lang['system_events.service_5'] = 'Події служби шаблонів';
$_lang['system_events.service_6'] = 'Події визначені користувачем';

$_lang['system_events.remove'] = 'Видалити подію';
$_lang['system_events.remove_confirm'] = 'Ви впевнені, що хочете видалити подію <b>[[+name]]</b>? Ця дія незворотня!';

$_lang['system_events_err_ns'] = 'Не вказано назву системної події. ';
$_lang['system_events_err_ae'] = 'Така назва системної події вже існує.';
$_lang['system_events_err_startint'] = 'Назва не повинна починатися з цифри.';
$_lang['system_events_err_remove_not_allowed'] = 'Ви не можете  видалили цю системну подію.';
