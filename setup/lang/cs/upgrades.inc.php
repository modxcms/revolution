<?php
/**
 * Czech Upgrades Lexicon Topic for Revolution setup.
 *
 * @language cs
 * @package setup
 * @subpackage lexicon
 *
 * @author modxcms.cz
 * @updated 2011-11-19
 */
// $_lang['add_column'] = 'Added new `[[+column]]` column to `[[+table]]`.';
$_lang['add_column'] = 'Přidán nový sloupec `[[+column]]` do tabulky `[[+table]]`.';

// $_lang['add_index'] = 'Added new index on `[[+index]]` for table `[[+table]]`.';
$_lang['add_index'] = 'Přidán nový index na sloupec `[[+index]]` v tabulce `[[+table]]`.';

// $_lang['add_moduser_classkey'] = 'Added class_key field to support modUser derivatives.';
$_lang['add_moduser_classkey'] = 'Přidán sloupec class_key pro podporu odvozenin modUser.';

// $_lang['added_cachepwd'] = 'Added cachepwd field missing in early Revolution releases.';
$_lang['added_cachepwd'] = 'Přidán sloupec cachepwd chybějící v předchozích vydání Revolution.';

// $_lang['added_content_ft_idx'] = 'Added new `content_ft_idx` full-text index on the fields `pagetitle`, `longtitle`, `description`, `introtext`, `content`.';
$_lang['added_content_ft_idx'] = 'Přidán nový full-textový index `content_ft_idx` na sloupce `pagetitle`, `longtitle`, `description`, `introtext`, `content`.';

// $_lang['allow_null_properties'] = 'Fixing allow null for `[[+class]]`.`properties`.';
$_lang['allow_null_properties'] = 'Opraveno povolení použízí null pro `[[+class]]`.`properties`.';

// $_lang['alter_activeuser_action'] = 'Modified modActiveUser `action` field to allow longer action labels.';
$_lang['alter_activeuser_action'] = 'Upraven modActiveUser sloupec `action` pro možnost delších názvů akcí.';

// $_lang['alter_usermessage_messageread'] = 'Changed modUserMessage `messageread` field to `read`.';
$_lang['alter_usermessage_messageread'] = 'Změněn modUserMessage sloupec `messageread` na `read`.';

// $_lang['alter_usermessage_postdate'] = 'Changed modUserMessage `postdate` field from an INT to a DATETIME and to name `date_sent`.';
$_lang['alter_usermessage_postdate'] = 'Změněn modUserMessage sloupec `postdate` z INT na DATETIME a název `date_sent`.';

// $_lang['alter_usermessage_subject'] = 'Changed modUserMessage `subject` field from VARCHAR(60) to VARCHAR(255).';
$_lang['alter_usermessage_subject'] = 'Změněn modUserMessage sloupec `subject` z VARCHAR(60) na VARCHAR(255).';

// $_lang['change_column'] = 'Changed `[[+old]]` field to `[[+new]]` on table `[[+table]]`.';
$_lang['change_column'] = 'Změněn sloupec `[[+old]]` na `[[+new]]` v tabulce `[[+table]]`.';

// $_lang['change_default_value'] = 'Changed default value for column `[[+column]]` to "[[+value]]" on table `[[+table]]`.';
$_lang['change_default_value'] = 'Změněna výchozí hodnota pro sloupec `[[+column]]` na "[[+value]]" v tabulce `[[+table]]`.';

// $_lang['connector_acls_removed'] = 'Removed connector context ACLs.';
$_lang['connector_acls_removed'] = 'Odestraněn konektor kontextu přístupů.';

// $_lang['connector_acls_not_removed'] = 'Could not remove connector context ACLs.';
$_lang['connector_acls_not_removed'] = 'Nepodařilo se odstranit konektor kontextu přístupů.';

// $_lang['connector_ctx_removed'] = '';
$_lang['connector_ctx_removed'] = '';

// $_lang['connector_ctx_not_removed'] = 'Could not remove connector context.';
$_lang['connector_ctx_not_removed'] = 'Nepodařilo se odstranit konektor kontextu.';

// $_lang['data_remove_error'] = 'Error removing data for class `[[+class]]`.';
$_lang['data_remove_error'] = 'Nastala chyba při odstraňování dat ze třídy `[[+class]]`.';

// $_lang['data_remove_success'] = 'Successfully removed data from table for class `[[+class]]`.';
$_lang['data_remove_success'] = 'Úspěšně odstraněna data z tabulky pro třídu `[[+class]]`.';

// $_lang['drop_column'] = 'Dropped column `[[+column]]` on table `[[+table]]`.';
$_lang['drop_column'] = 'Odstraněn sloupec `[[+column]]` v tabulce `[[+table]]`.';

// $_lang['drop_index'] = 'Dropped index `[[+index]]` on table `[[+table]]`.';
$_lang['drop_index'] = 'Odstraněn index `[[+index]]` v tabulce `[[+table]]`.';

// $_lang['lexiconentry_createdon_null'] = 'Changed modLexiconEntry `createdon` to allow NULL.';
$_lang['lexiconentry_createdon_null'] = 'Změněn sloupec modLexiconEntry `createdon` pro možnost NULL.';

// $_lang['lexiconentry_focus_alter'] = 'Changed modLexiconEntry `focus` from VARCHAR(100) to INT(10).';
$_lang['lexiconentry_focus_alter'] = 'Změněn sloupec modLexiconEntry `focus` z VARCHAR(100) na INT(10).';

// $_lang['lexiconentry_focus_alter_int'] = 'Updated modLexiconEntry `focus` column data from string to new int foreign key from modLexiconTopic.';
$_lang['lexiconentry_focus_alter_int'] = 'Aktualizován sloupec modLexiconEntry `focus` z datového typu STRING na nový INT cizý klíč k modLexiconTopic.';

// $_lang['lexiconfocus_add_id'] = 'Added modLexiconFocus `id` column.';
$_lang['lexiconfocus_add_id'] = 'Přidán sloupec modLexiconFocus `id`.';

// $_lang['lexiconfocus_add_pk'] = 'Added modLexiconFocus PRIMARY KEY to `id` column.';
$_lang['lexiconfocus_add_pk'] = 'Přidán PRIMARY KEY modLexiconFocus na sloupec `id`.';

// $_lang['lexiconfocus_alter_pk'] = 'Changed modLexiconFocus `name` from PRIMARY KEY to UNIQUE KEY';
$_lang['lexiconfocus_alter_pk'] = 'Změněno pole modLexiconFocus `name` z PRIMARY KEY na UNIQUE KEY';

// $_lang['lexiconfocus_drop_pk'] = 'Dropped modLexiconFocus PRIMARY KEY.';
$_lang['lexiconfocus_drop_pk'] = 'Odstraněn PRIMARY KEY modLexiconFocus.';

// $_lang['modify_column'] = 'Modified column `[[+column]]` from `[[+old]]` to `[[+new]]` on table `[[+table]]`';
$_lang['modify_column'] = 'V tabulce `[[+table]]` byl změněn sloupec `[[+column]]` z `[[+old]]` na `[[+new]]`';

// $_lang['rename_column'] = 'Renamed column `[[+old]]` to `[[+new]]` on table `[[+table]]`.';
$_lang['rename_column'] = 'Přejmenován sloupec `[[+old]]` na `[[+new]]` v tabulce `[[+table]]`.';

// $_lang['rename_table'] = 'Renamed table `[[+old]]` to `[[+new]]`.';
$_lang['rename_table'] = 'Přejmenována tabulka `[[+old]]` na `[[+new]]`.';

// $_lang['remove_fulltext_index'] = 'Removed full-text index `[[+index]]`.';
$_lang['remove_fulltext_index'] = 'Odstraněn full-textový index `[[+index]]`.';

// $_lang['systemsetting_xtype_fix'] = 'Successfully fixed xtypes for modSystemSettings.';
$_lang['systemsetting_xtype_fix'] = 'Úspěšně opraven xtypes pro modSystemSettings.';

// $_lang['transportpackage_manifest_text'] = 'Modified column `manifest` to TEXT from MEDIUMTEXT on `[[+class]]`.';
$_lang['transportpackage_manifest_text'] = 'Upraven sloupec `manifest` na TEXT z MEDIUMTEXT z tabulky `[[+class]]`.';

// $_lang['update_closure_table'] = 'Updating closure table data for class `[[+class]]`.';
$_lang['update_closure_table'] = 'Měním uzavírací tabulková data pro třídu `[[+class]]`.';

// $_lang['update_table_column_data'] = 'Updated data in column [[+column]] of table [[+table]] ( [[+class]] )';
$_lang['update_table_column_data'] = 'V tabulce [[+table]] ( [[+class]] ) byla aktualizována data ve sloupci [[+column]]';
