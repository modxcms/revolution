<?php
/**
 * System Events English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */

$_lang['events'] = 'Падзеі';
$_lang['system_event'] = 'Сістэмная падзея';
$_lang['system_events'] = 'Сістэмныя падзеі';
$_lang['system_events.desc'] = 'System Events are the events in MODX that Plugins are registered to. They are "fired" throughout the MODX code, allowing Plugins to interact with MODX code and add custom functionality without hacking core code. You can create your own events for your custom project here too. You cannot remove core events, only your own.';
$_lang['system_events.search_by_name'] = 'Пошук па назве падзеі';
$_lang['system_events.create'] = 'Стварыць новую падзею';
$_lang['system_events.name_desc'] = 'Назва падзеі. Тая, якую вы павінны выкарыстоўваць у выкліку &dollar;modx->invokeEvent(name, properties).';
$_lang['system_events.groupname'] = 'Група';
$_lang['system_events.groupname_desc'] = 'The name of the group where the new event belongs to. Select an existing one or type in a new group name.';
$_lang['system_events.plugins'] = 'Плагiны';
$_lang['system_events.plugins_desc'] = 'Спіс плагінаў прыстасаваных да падзеі. Выберыце плагіны якія павінны быць прыстасаваныя да падзеі.';

$_lang['system_events.service'] = 'Служба';
$_lang['system_events.service_1'] = 'Parser Service Events';
$_lang['system_events.service_2'] = 'Manager Access Events';
$_lang['system_events.service_3'] = 'Web Access Service Events';
$_lang['system_events.service_4'] = 'Cache Service Events';
$_lang['system_events.service_5'] = 'Template Service Events';
$_lang['system_events.service_6'] = 'User Defined Events';

$_lang['system_events.remove'] = 'Выдаліць падзею';
$_lang['system_events.remove_confirm'] = 'Вы сапраўды жадаеце выдаліць падзею <b>[[+name]]</b>? Гэта незваротна!';

$_lang['system_events_err_ns'] = 'Назва сістэмнай падзеі не пазначана.';
$_lang['system_events_err_ae'] = 'Назва сістэмнай падзеі ўжо існуе.';
$_lang['system_events_err_startint'] = 'It\'s not allowed to start the name with a digit.';
$_lang['system_events_err_remove_not_allowed'] = 'Вам не дазволена выдаляць гэтую сістэмную падзею.';
