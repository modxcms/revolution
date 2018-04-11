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
$_lang['system_events.desc'] = 'Сістэмныя падзеі з\'яўляюцца падзеямі ў MODX, да якіх прыстасаваны плагіны. Яны здараюцца (вызываюцца) ў кодзе MODX, дазваляючы плагінам ўзаемадзейнічаць з MODX кодам і дадаваць карыстацкія функцыі бяз змены асноўнага кода. Вы можаце ствараць свае ўласныя падзеі для вашага праекта, але вы не можаце выдаліць асноўныя падзеі, толькі свае ўласныя.';
$_lang['system_events.search_by_name'] = 'Пошук па назве падзеі';
$_lang['system_events.create'] = 'Стварыць новую падзею';
$_lang['system_events.name_desc'] = 'Назва падзеі. Тая, якую вы павінны выкарыстоўваць у выкліку &dollar;modx->invokeEvent(name, properties).';
$_lang['system_events.groupname'] = 'Група';
$_lang['system_events.groupname_desc'] = 'Назва групы, да якой належыць новая падзея. Выберыце існуючую або ўвядзіце новае імя групы.';
$_lang['system_events.plugins'] = 'Плагiны';
$_lang['system_events.plugins_desc'] = 'Спіс плагінаў прыстасаваных да падзеі. Выберыце плагіны якія павінны быць прыстасаваныя да падзеі.';

$_lang['system_events.service'] = 'Служба';
$_lang['system_events.service_1'] = 'Падзеі паслугі парсера';
$_lang['system_events.service_2'] = 'Падзеі доступа да сістэмы кіравання';
$_lang['system_events.service_3'] = 'Падзеі доступа да сайта';
$_lang['system_events.service_4'] = 'Падзеі паслугі кэша';
$_lang['system_events.service_5'] = 'Падзеі паслугі шаблонаў';
$_lang['system_events.service_6'] = 'Падзеі, вызначаныя карыстальнікам';

$_lang['system_events.remove'] = 'Выдаліць падзею';
$_lang['system_events.remove_confirm'] = 'Вы сапраўды жадаеце выдаліць падзею <b>[[+name]]</b>? Гэта незваротна!';

$_lang['system_events_err_ns'] = 'Назва сістэмнай падзеі не пазначана.';
$_lang['system_events_err_ae'] = 'Назва сістэмнай падзеі ўжо існуе.';
$_lang['system_events_err_startint'] = 'Імя не павінна пачынацца з лічбы.';
$_lang['system_events_err_remove_not_allowed'] = 'Вам не дазволена выдаляць гэтую сістэмную падзею.';
