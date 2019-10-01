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
$_lang['system_events.desc'] = 'A rendszeresemények olyan események a MODX-ben, amelyekhez a beépülők be vannak jegyezve. A MODX futása indítja el őket, hogy a beépülők kapcsolódhassanak a MODX kódjához, és egyedi működéseket adjanak hozzá az alapkód módosítása nélkül. Itt adhat hozzá saját eseményeket is a saját fejlesztéséhez. A rendszereseményeket nem távolíthatja el, csak a sajátjait.';
$_lang['system_events.search_by_name'] = 'Keresés esemény neve szerint';
$_lang['system_events.create'] = 'Új esemény létrehozása';
$_lang['system_events.name_desc'] = 'Az esemény neve, amit &dollar;modx->invokeEvent(név, tulajdonságok) formában hívhat meg.';
$_lang['system_events.groupname'] = 'Csoport';
$_lang['system_events.groupname_desc'] = 'A csoport neve, ahová az új esemény tartozik. Válasszon egy meglevőt, vagy írjon be egy új csoportnevet.';
$_lang['system_events.plugins'] = 'Beépülők';
$_lang['system_events.plugins_desc'] = 'Az eseményhez csatolt beépülők felsorolása. Válassza ki azokat a beépülőket, amelyeket hozzá akar kapcsolni az eseményhez.';

$_lang['system_events.service'] = 'Szolgáltatás';
$_lang['system_events.service_1'] = 'Feldolgozó-szolgáltatási események';
$_lang['system_events.service_2'] = 'Kezelő-hozzáférési események';
$_lang['system_events.service_3'] = 'Webes elérési szolgáltatási események';
$_lang['system_events.service_4'] = 'Gyorsítótár-szolgáltatási események';
$_lang['system_events.service_5'] = 'Sablonszolgáltatási események';
$_lang['system_events.service_6'] = 'Felhasználó által létrehozott események';

$_lang['system_events.remove'] = 'Esemény eltávolítása';
$_lang['system_events.remove_confirm'] = 'Biztosan eltávolítja a <b>[[+name]]</b> eseményt? A törlés végleges!';

$_lang['system_events_err_ns'] = 'A rendszeresemény neve nincs megadva.';
$_lang['system_events_err_ae'] = 'A rendszeresemény neve már létezik.';
$_lang['system_events_err_startint'] = 'Az elnevezés nem kezdődhet számmal.';
$_lang['system_events_err_remove_not_allowed'] = 'Nincs jogosultsága a rendszeresemény eltávolítására.';
