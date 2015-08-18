<?php
/**
 * System Events English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */

$_lang['events'] = 'Události';
$_lang['system_event'] = 'Systémová událost';
$_lang['system_events'] = 'Systémové události';
$_lang['system_events.desc'] = 'Systémové události jsou události v MODX, na které jsou registrovány pluginy. Ty jsou "spouštěny" v rámci MODX kódu, umožňují pluginům interakci s MODX kódem a umožňují přidat vlastní funkce bez zásahu do zdrojového kódu MODX. Můžete zde také vytvořit vlastní události pro váš vlastní projekt. Výchozí události MODX nelze odstranit, odstranit lze pouze vaše vlastní události.';
$_lang['system_events.search_by_name'] = 'Hledat podle názvu události';
$_lang['system_events.create'] = 'Vytvořit novou událost';
$_lang['system_events.name_desc'] = 'Název události. Který byste měli použít v rámci volání &dollar;modx->invokeEvent(název, vlastnosti).';
$_lang['system_events.groupname'] = 'Skupina';
$_lang['system_events.groupname_desc'] = 'Název skupiny, kam tato událost patří. Vyberte existující nebo zadejte nový název skupiny.';

$_lang['system_events.service'] = 'Služba';
$_lang['system_events.service_1'] = 'Události služby Parser';
$_lang['system_events.service_2'] = 'Události přístupu do manageru';
$_lang['system_events.service_3'] = 'Události přístupu na web';
$_lang['system_events.service_4'] = 'Události cache';
$_lang['system_events.service_5'] = 'Události šablon';
$_lang['system_events.service_6'] = 'Uživatelem definované události';

$_lang['system_events.remove'] = 'Odstranit událost';
$_lang['system_events.remove_confirm'] = 'Opravdu chcete odstranit událost <b>[[+name]]</b>? Odstranění je nevratné!';

$_lang['system_events_err_ns'] = 'Název systémové události není zadán.';
$_lang['system_events_err_ae'] = 'Název systémové události již existuje.';
$_lang['system_events_err_startint'] = 'Název nesmí začínat číslicí.';
$_lang['system_events_err_remove_not_allowed'] = 'Nejste oprávněn odstranit tuto systémovou událost.';
