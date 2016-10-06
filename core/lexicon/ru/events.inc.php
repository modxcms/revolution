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
$_lang['system_events.desc'] = '<b>Системные события</b> — это события, регистрируемые MODX с помощью <i>плагинов</i>. Они могут «реагировать» внутри самого кода MODX, позволяя <i>плагинам</i> взаимодействовать с исходным кодом MODX и создавать собственный функционал без изменения кода ядра. Вы также можете создавать собственные события в своих проектах. Системные события ядра MODX нельзя удалить, только созданные вами.';
$_lang['system_events.search_by_name'] = 'Поиск по названию события';
$_lang['system_events.create'] = 'Создать новое событие';
$_lang['system_events.name_desc'] = 'Имя события. Используйте  &dollar;modx->invoiceEvent(<i>имя</i>, <i>параметры</i>) для вызова.';
$_lang['system_events.groupname'] = 'Группа';
$_lang['system_events.groupname_desc'] = 'Имя группы, которой принадлежит новое <i>событие</i>. Выберите существующее или создайте новое.';

$_lang['system_events.service'] = 'Служба';
$_lang['system_events.service_1'] = 'Парсер службы событий';
$_lang['system_events.service_2'] = 'Доступ к событиям административной панели';
$_lang['system_events.service_3'] = 'Доступ к службе событий за пределами административной панели';
$_lang['system_events.service_4'] = 'Кэш службы событий';
$_lang['system_events.service_5'] = 'Шаблон службы событий';
$_lang['system_events.service_6'] = 'Пользовательские события';

$_lang['system_events.remove'] = 'Удалить событие';
$_lang['system_events.remove_confirm'] = 'Вы уверены, что хотите удалить событие <b>[[+name]]</b>? Это действие нельзя отменить!';

$_lang['system_events_err_ns'] = 'Не указано имя системного события.';
$_lang['system_events_err_ae'] = 'Такое имя системного события уже существует.';
$_lang['system_events_err_startint'] = 'Имя не должно начинаться с цифры.';
$_lang['system_events_err_remove_not_allowed'] = 'Вы не можете удалить это системное событие.';
