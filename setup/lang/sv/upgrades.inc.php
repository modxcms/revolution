<?php
/**
 * Swedish Upgrades Lexicon Topic for Revolution setup.
 *
 * @package setup
 * @subpackage lexicon
 */
$_lang['add_column'] = 'Lade till ny `[[+column]]`-kolumn till `[[+table]]`.';
$_lang['add_index'] = 'Lade till nytt index på `[[+index]]` i tabellen `[[+table]]`.';
$_lang['add_moduser_classkey'] = 'Lade till ett class_key-fält för att stödja modUser-derivat.';
$_lang['added_cachepwd'] = 'Lade till fältet cachepwd som saknades i tidiga versioner av Revolution.';
$_lang['added_content_ft_idx'] = 'Lade till nytt heltext-index `content_ft_idx` på fälten `pagetitle`, `longtitle`, `description`, `introtext`, `content`.';
$_lang['allow_null_properties'] = 'Fixar tillåt null för `[[+class]]`.`properties`.';
$_lang['alter_activeuser_action'] = 'Ändrade `action`-fältet i modActiveUser för att tillåta längre händelseetiketter.';
$_lang['alter_usermessage_messageread'] = 'Ändrade `messageread`-fältet i modUserMessage till `läs`.';
$_lang['alter_usermessage_postdate'] = 'Bytte namn på `postdate`-fältet i modUserMessage till `date_sent` och ändrade från INT till DATETIME.';
$_lang['alter_usermessage_subject'] = 'Ändrade `subject`-fältet i modUserMessage från VARCHAR(60) till VARCHAR(255).';
$_lang['change_column'] = 'Ändrade fältet `[[+old]]` till `[[+new]]` i tabellen `[[+table]]`.';
$_lang['change_default_value'] = 'Ändrade standardvärdet för kolumnen `[[+column]]` till [[+value]] i tabellen `[[+table]]`.';
$_lang['connector_acls_removed'] = 'Tog bort kopplingskontextens ACL:er.';
$_lang['connector_acls_not_removed'] = 'Kunde inte ta bort kopplingskontextens ACL:er.';
$_lang['connector_ctx_removed'] = '';
$_lang['connector_ctx_not_removed'] = 'Kunde inte ta bort kopplingskontext.';
$_lang['data_remove_error'] = 'Ett fel inträffade data skulle tas bort från klassen `[[+class]]`.';
$_lang['data_remove_success'] = 'Tog bort data från tabellen för klass `[[+class]]`.';
$_lang['drop_column'] = 'Tog bort kolumnen `[[+column]]` i tabellen `[[+table]]`.';
$_lang['drop_index'] = 'Tog bort indexet `[[+index]]` i tabellen `[[+table]]`.';
$_lang['lexiconentry_createdon_null'] = 'Ändrade `createdon` i modLexiconEntry för att tillåta NULL.';
$_lang['lexiconentry_focus_alter'] = 'Ändrade `focus` i modLexiconEntry från VARCHAR(100) till INT(10).';
$_lang['lexiconentry_focus_alter_int'] = 'Uppdaterade modLexiconEntry-tabellens `focus`-kolumns datatyp från sträng till int från den nya externa nyckeln från modLexiconTopic.';
$_lang['lexiconfocus_add_id'] = 'Lade till `id`-kolumn i modLexiconFocus.';
$_lang['lexiconfocus_add_pk'] = 'Lade till PRIMARY KEY till `id`-kolumnen i modLexiconFocus.';
$_lang['lexiconfocus_alter_pk'] = 'Ändrade `name` från PRIMARY KEY till UNIQUE KEY i modLexiconFocus.';
$_lang['lexiconfocus_drop_pk'] = 'Tog bort PRIMARY KEY från modLexiconFocus.';
$_lang['modify_column'] = 'Modifierade kolumnen `[[+column]]` från `[[+old]]` till `[[+new]]` i tabellen `[[+table]]`';
$_lang['rename_column'] = 'Ändrade namn på kolumnen `[[+old]]` till `[[+new]]` i tabellen `[[+table]]`.';
$_lang['rename_table'] = 'Ändrade namn på tabellen `[[+old]]` till `[[+new]]`.';
$_lang['remove_fulltext_index'] = 'Tog bort heltext-indexet `[[+index]]`.';
$_lang['systemsetting_xtype_fix'] = 'Rättade till xtypes för modSystemSettings.';
$_lang['transportpackage_manifest_text'] = 'Modifierade kolumnen `manifest` till TEXT från MEDIUMTEXT i `[[+class]]`.';
$_lang['update_closure_table'] = 'Uppdaterar stängningstabellens data för klassen `[[+class]]`.';
$_lang['update_table_column_data'] = 'Uppdaterade data i kolumnen [[+column]] i tabellen [[+table]] ( [[+class]] )';