<?php
/**
 * Snippet English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */
$_lang['example_tag_snippet_name'] = 'NameOfSnippet';
$_lang['snippet'] = 'Snippet';
$_lang['snippets_available'] = 'Snippets du kan medtage på din side';
$_lang['snippet_category_desc'] = 'Use to group Snippets within the Elements tree.';
$_lang['snippet_code'] = 'Snippet Code (PHP)';
$_lang['snippet_delete_confirm'] = 'Er du sikker på du vil slette denne snippet?';
$_lang['snippet_description_desc'] = 'Usage information for this Snippet shown in search results and as a tooltip in the Elements tree.';
$_lang['snippet_duplicate_confirm'] = 'Er du sikker på at du vil kopiere denne snippet?';
$_lang['snippet_duplicate_error'] = 'Der opstod en fejl mens snippet blev forsøgt kopieret.';
$_lang['snippet_err_create'] = 'Der opstod en fejl under oprettelse af snippet.';
$_lang['snippet_err_delete'] = 'An error occurred while trying to delete the snippet.';
$_lang['snippet_err_duplicate'] = 'An error occurred while trying to duplicate the snippet.';
$_lang['snippet_err_ae'] = 'Der findes allerede en snippet med navnet "[[+ navn]]".';
$_lang['snippet_err_invalid_name'] = 'Snippets navn er ugyldigt.';
$_lang['snippet_err_locked'] = 'Denne snippet er låst for redigering.';
$_lang['snippet_err_nf'] = 'Snippet blev ikke fundet!';
$_lang['snippet_err_ns'] = 'Snippet ikke angivet.';
$_lang['snippet_err_ns_name'] = 'Angiv venligst et navn til snippet.';
$_lang['snippet_err_remove'] = 'An error occurred while trying to delete the snippet.';
$_lang['snippet_err_save'] = 'Der opstod en fejl mens snippet blev forsøgt gemt.';
$_lang['snippet_execonsave'] = 'Kør snippet efter den er blevet gemt.';
$_lang['snippet_lock'] = 'Lås snippet for redigering';
$_lang['snippet_lock_desc'] = 'Only users with “edit_locked” permissions can edit this Snippet.';
$_lang['snippet_management_msg'] = 'Her kan du vælge hvilken snippet du ønsker at redigere.';
$_lang['snippet_name_desc'] = 'Place the content generated by this Snippet in a Resource, Template, or Chunk using the following MODX tag: [[+tag]]';
$_lang['snippet_new'] = 'Create Snippet';
$_lang['snippet_properties'] = 'Standardegenskaber';
$_lang['snippet_tab_general_desc'] = 'Here you can enter the basic attributes for this <em>Snippet</em> as well as its content. The content must be PHP, either placed in the <em>Snippet Code</em> field below or in a static external file. To receive output from your Snippet at the point where it is called (within a Template or Chunk), a value must be returned from within the code.';
$_lang['snippet_tag_copied'] = 'Snippet tag copied!';
$_lang['snippets'] = 'Snippets';

// Temporarily match old keys to new ones to ensure compatibility
// --fields
$_lang['snippet_desc_category'] = $_lang['snippet_category_desc'];
$_lang['snippet_desc_description'] = $_lang['snippet_description_desc'];
$_lang['snippet_desc_name'] = $_lang['snippet_name_desc'];
$_lang['snippet_lock_msg'] = $_lang['snippet_lock_desc'];

// --tabs
$_lang['snippet_msg'] = $_lang['snippet_tab_general_desc'];
