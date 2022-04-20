<?php
/**
 * English language strings for Elements
 *
 * @package modx
 * @subpackage lexicon
 */
$_lang['element'] = 'Element';
$_lang['element_err_nf'] = 'Elementet kunde inte hittas.';
$_lang['element_err_ns'] = 'Element ej angivet.';
$_lang['element_err_staticfile_exists'] = 'En statisk fil finns redan i den angivna sökvägen.';
$_lang['element_lock'] = 'Restrict Editing';
$_lang['element_static_source_immutable'] = 'Den statiska fil som är angiven som elementkälla är inte skrivbar! Du kan inte redigera innehållet i detta element i hanteraren.';
$_lang['element_static_source_protected_invalid'] = 'Du kan inte peka ditt element mot MODX konfigurationsmapp. Den mappen är skyddad och går inte att nå.';
$_lang['is_static'] = 'Är statisk';
$_lang['is_static_desc'] = 'Använd en extern fil för att lagra det här elementets källkod.';
$_lang['quick_create'] = 'Snabbskapa';
$_lang['quick_create_chunk'] = 'Snabbskapa chunk';
$_lang['quick_create_plugin'] = 'Snabbskapa plugin';
$_lang['quick_create_snippet'] = 'Snabbskapa snippet';
$_lang['quick_create_template'] = 'Snabbskapa mall';
$_lang['quick_create_tv'] = 'Snabbskapa mallvariabel';
$_lang['quick_update_chunk'] = 'Snabbredigera chunk';
$_lang['quick_update_plugin'] = 'Snabbredigera plugin';
$_lang['quick_update_snippet'] = 'Snabbredigera snippet';
$_lang['quick_update_template'] = 'Snabbredigera mall';
$_lang['quick_update_tv'] = 'Snabbredigera mallvariabel';
$_lang['property_preprocess'] = 'Förprocessa taggar i egenskapsvärden';
$_lang['property_preprocess_msg'] = 'Om denna aktiveras kommer standardegenskaper/egenskapsuppsättningar att processas innan de används för elementets process.';
$_lang['static_file'] = 'Statisk fil';
$_lang['static_file_desc'] = 'Den externa filplatsen där källkoden för detta element lagras.';
$_lang['static_source'] = 'Mediakälla';
$_lang['static_source_desc'] = 'Sets the basePath for the Static File to the one specified in the chosen Media Source. Choose “None” when specifying an absolute or other custom path to the file.';
$_lang['tv_elements'] = 'Alternativvärden för indata';
$_lang['tv_default'] = 'Standardvärde';
$_lang['tv_type'] = 'Inmatningstyp';
$_lang['tv_output_type'] = 'Utdatatyp';
$_lang['tv_output_type_properties'] = 'Egenskaper för utdatatyp';
$_lang['static_file_ns'] = 'Du måste ange en statisk fil.';

// Temporarily match old keys to new ones to ensure compatibility
$_lang['is_static_msg'] = $_lang['is_static_desc'];
$_lang['static_file_msg'] = $_lang['static_file_desc'];
$_lang['static_source_msg'] = $_lang['static_source_desc'];
