<?php
/**
 * English language strings for Elements
 *
 * @package modx
 * @subpackage lexicon
 */
$_lang['element'] = 'Element';
$_lang['element_err_nf'] = 'Nie znaleziono elementu.';
$_lang['element_err_ns'] = 'Element nie jest określony.';
$_lang['element_err_staticfile_exists'] = 'A static file already exists within the specified path.';
$_lang['element_lock'] = 'Restrict Editing';
$_lang['element_static_source_immutable'] = 'The static file specified as the element source is not writable! You cannot edit the content of this element in the manager.';
$_lang['element_static_source_protected_invalid'] = 'You cannot point your Element to the MODX configuration directory; this is a protected, non-accessible directory.';
$_lang['is_static'] = 'Jest statyczna';
$_lang['is_static_desc'] = 'Use an external file to store this element’s source code.';
$_lang['quick_create'] = '<abbr title="Otwiera okno edycji podstawowych pól zasobu">Szybko utwórz tutaj</abbr>';
$_lang['quick_create_chunk'] = 'Quick Create Chunk';
$_lang['quick_create_plugin'] = 'Quick Create Plugin';
$_lang['quick_create_snippet'] = 'Quick Create Snippet';
$_lang['quick_create_template'] = 'Quick Create Template';
$_lang['quick_create_tv'] = 'Quick Create TV';
$_lang['quick_update_chunk'] = 'Quick Edit Chunk';
$_lang['quick_update_plugin'] = 'Quick Edit Plugin';
$_lang['quick_update_snippet'] = 'Quick Edit Snippet';
$_lang['quick_update_template'] = 'Quick Edit Template';
$_lang['quick_update_tv'] = 'Quick Edit TV';
$_lang['property_preprocess'] = 'Pre-process tags in Property Values';
$_lang['property_preprocess_msg'] = 'If enabled, tags in Default Property/Property Set values will be processed before they are used for Element processing.';
$_lang['static_file'] = 'Plik statyczny';
$_lang['static_file_desc'] = 'The external file location where the source code for this element is stored.';
$_lang['static_source'] = 'Media Source';
$_lang['static_source_desc'] = 'Sets the basePath for the Static File to the one specified in the chosen Media Source. Choose “None” when specifying an absolute or other custom path to the file.';
$_lang['tv_elements'] = 'Input Option Values';
$_lang['tv_default'] = 'Wartość domyślna';
$_lang['tv_type'] = 'Typ danych wejściowych';
$_lang['tv_output_type'] = 'Typ wyjścia';
$_lang['tv_output_type_properties'] = 'Output Type Properties';
$_lang['static_file_ns'] = 'You have to specify a static file.';

// Temporarily match old keys to new ones to ensure compatibility
$_lang['is_static_msg'] = $_lang['is_static_desc'];
$_lang['static_file_msg'] = $_lang['static_file_desc'];
$_lang['static_source_msg'] = $_lang['static_source_desc'];
