<?php
/**
 * System Events English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */

$_lang['events'] = 'Események';
$_lang['system_event'] = 'Rendszeresemény';
$_lang['system_events'] = 'Rendszeresemények';
$_lang['system_events.desc'] = 'System Events are the events in MODX that Plugins are registered to. They are "fired" throughout the MODX code, allowing Plugins to interact with MODX code and add custom functionality without hacking core code. You can create your own events for your custom project here too. You cannot delete core events, only your own.';
$_lang['system_events.search_by_name'] = 'Keresés esemény neve szerint';
$_lang['system_events.create'] = 'Create Event';
$_lang['system_events.name_desc'] = 'Az esemény neve, amit &dollar;modx->invokeEvent(név, tulajdonságok) formában hívhat meg.';
$_lang['system_events.groupname'] = 'Csoport';
$_lang['system_events.groupname_desc'] = 'The name of the group where the event belongs to. Select an existing one or type a new group name.';
$_lang['system_events.plugins'] = 'Beépülők';
$_lang['system_events.plugins_desc'] = 'Az eseményhez csatolt beépülők felsorolása. Válassza ki azokat a beépülőket, amelyeket hozzá akar kapcsolni az eseményhez.';

$_lang['system_events.service'] = 'Szolgáltatás';
$_lang['system_events.service_1'] = 'Feldolgozó-szolgáltatási események';
$_lang['system_events.service_2'] = 'Kezelő-hozzáférési események';
$_lang['system_events.service_3'] = 'Webes elérési szolgáltatási események';
$_lang['system_events.service_4'] = 'Gyorsítótár-szolgáltatási események';
$_lang['system_events.service_5'] = 'Sablonszolgáltatási események';
$_lang['system_events.service_6'] = 'Felhasználó által létrehozott események';

$_lang['system_events.remove'] = 'Delete Event';
$_lang['system_events.remove_confirm'] = 'Are you sure you want to delete the <b>[[+name]]</b> event? This is irreversible!';

$_lang['system_events_err_ns'] = 'A rendszeresemény neve nincs megadva.';
$_lang['system_events_err_ae'] = 'A rendszeresemény neve már létezik.';
$_lang['system_events_err_startint'] = 'Az elnevezés nem kezdődhet számmal.';
$_lang['system_events_err_remove_not_allowed'] = 'You\'re not allowed to delete this System Event.';
