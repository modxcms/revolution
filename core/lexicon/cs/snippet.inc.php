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
$_lang['snippets_available'] = 'Dostupné snippety pro použití ve stránkách.';
$_lang['snippet_category_desc'] = 'Use to group Snippets within the Elements tree.';
$_lang['snippet_code'] = 'Snippet Code (PHP)';
$_lang['snippet_delete_confirm'] = 'Opravdu chcete odstranit tento snippet?';
$_lang['snippet_description_desc'] = 'Usage information for this Snippet shown in search results and as a tooltip in the Elements tree.';
$_lang['snippet_duplicate_confirm'] = 'Opravdu chcete tento snippet zkopírovat?';
$_lang['snippet_duplicate_error'] = 'Nastala chyba při kopírování snippetu.';
$_lang['snippet_err_create'] = 'Nastala chyba při vytváření snippetu.';
$_lang['snippet_err_delete'] = 'Nastala chyba při odstraňování snippetu.';
$_lang['snippet_err_duplicate'] = 'Nastala chyba při kopírování snippetu.';
$_lang['snippet_err_ae'] = 'Snippet s názvem "[[+name]]" již existuje.';
$_lang['snippet_err_invalid_name'] = 'Název snippetu je chybný.';
$_lang['snippet_err_locked'] = 'Snippet je uzamčen.';
$_lang['snippet_err_nf'] = 'Snippet nenalezen!';
$_lang['snippet_err_ns'] = 'Nespecifikovaný snippet.';
$_lang['snippet_err_ns_name'] = 'Zadejte název snippetu.';
$_lang['snippet_err_remove'] = 'An error occurred while trying to delete the snippet.';
$_lang['snippet_err_save'] = 'Nastala chyba při ukládání snippetu.';
$_lang['snippet_execonsave'] = 'Spustit snippet po uložení.';
$_lang['snippet_lock'] = 'Uzamknout snippet pro úpravy';
$_lang['snippet_lock_desc'] = 'Only users with “edit_locked” permissions can edit this Snippet.';
$_lang['snippet_management_msg'] = 'Vyberte si, který snippet chcete upravovat.';
$_lang['snippet_name_desc'] = 'Umístěte obsah generovaný tímto snippetem do dokumentu, šablony nebo chunku pomocí následující MODX značky: [[+tag]]';
$_lang['snippet_new'] = 'Create Snippet';
$_lang['snippet_properties'] = 'Výchozí vlastnosti';
$_lang['snippet_tab_general_desc'] = 'Here you can enter the basic attributes for this <em>Snippet</em> as well as its content. The content must be PHP, either placed in the <em>Snippet Code</em> field below or in a static external file. To receive output from your Snippet at the point where it is called (within a Template or Chunk), a value must be returned from within the code.';
$_lang['snippet_tag_copied'] = 'Snippet tag copied!';
$_lang['snippets'] = 'Snippety';

// Temporarily match old keys to new ones to ensure compatibility
// --fields
$_lang['snippet_desc_category'] = $_lang['snippet_category_desc'];
$_lang['snippet_desc_description'] = $_lang['snippet_description_desc'];
$_lang['snippet_desc_name'] = $_lang['snippet_name_desc'];
$_lang['snippet_lock_msg'] = $_lang['snippet_lock_desc'];

// --tabs
$_lang['snippet_msg'] = $_lang['snippet_tab_general_desc'];
