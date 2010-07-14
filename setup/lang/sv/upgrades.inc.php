<?php
/**
 * Swedish Upgrades Lexicon Topic for Revolution setup.
 *
 * @package setup
 * @subpackage lexicon
 */
$_lang['add_column'] = 'Lade till ny `%s`-kolumn till `%s`.';
$_lang['add_index'] = 'Lade till nytt index på `%s` i tabellen `%s`.';
$_lang['add_moduser_classkey'] = 'Lade till ett class_key-fält för att stödja modUser-derivat.';
$_lang['added_cachepwd'] = 'Lade till fältet cachepwd som saknades i tidiga versioner av Revolution.';
$_lang['added_content_ft_idx'] = 'Lade till nytt heltext-index `content_ft_idx` på fälten `pagetitle`, `longtitle`, `description`, `introtext`, `content`.';
$_lang['allow_null_properties'] = 'Fixar tillåt null för `%s`.`properties`.';
$_lang['alter_activeuser_action'] = 'Ändrade `action`-fältet i modActiveUser för att tillåta längre händelseetiketter.';
$_lang['alter_usermessage_messageread'] = 'Ändrade `messageread`-fältet i modUserMessage till `läs`.';
$_lang['alter_usermessage_postdate'] = 'Bytte namn på `postdate`-fältet i modUserMessage till `date_sent` och ändrade från INT till DATETIME.';
$_lang['alter_usermessage_subject'] = 'Ändrade `subject`-fältet i modUserMessage från VARCHAR(60) till VARCHAR(255).';
$_lang['change_column'] = 'Ändrade fältet `%s` till `%s` i tabellen `%s`.';
$_lang['change_default_value'] = 'Ändrade standardvärdet för kolumnen `%s` till %s i tabellen `%s`.';
$_lang['connector_acls_removed'] = 'Tog bort kopplingskontextens ACL:er.';
$_lang['connector_acls_not_removed'] = 'Kunde inte ta bort kopplingskontextens ACL:er.';
$_lang['connector_ctx_removed'] = '';
$_lang['connector_ctx_not_removed'] = 'Kunde inte ta bort kopplingskontext.';
$_lang['data_remove_error'] = 'Ett fel inträffade data skulle tas bort från klassen `%s`.';
$_lang['data_remove_success'] = 'Tog bort data från tabellen för klass `%s`.';
$_lang['drop_column'] = 'Tog bort kolumnen `%s` i tabellen `%s`.';
$_lang['drop_index'] = 'Tog bort indexet `%s` i tabellen `%s`.';
$_lang['lexiconentry_createdon_null'] = 'Ändrade `createdon` i modLexiconEntry för att tillåta NULL.';
$_lang['lexiconentry_focus_alter'] = 'Ändrade `focus` i modLexiconEntry från VARCHAR(100) till INT(10).';
$_lang['lexiconentry_focus_alter_int'] = 'Uppdaterade modLexiconEntry-tabellens `focus`-kolumns datatyp från sträng till int från den nya externa nyckeln från modLexiconTopic.';
$_lang['lexiconfocus_add_id'] = 'Lade till `id`-kolumn i modLexiconFocus.';
$_lang['lexiconfocus_add_pk'] = 'Lade till PRIMARY KEY till `id`-kolumnen i modLexiconFocus.';
$_lang['lexiconfocus_alter_pk'] = 'Ändrade `name` från PRIMARY KEY till UNIQUE KEY i modLexiconFocus.';
$_lang['lexiconfocus_drop_pk'] = 'Tog bort PRIMARY KEY från modLexiconFocus.';
$_lang['rename_column'] = 'Ändrade namn på kolumnen `%s` till `%s` i tabellen `%s`.';
$_lang['rename_table'] = 'Ändrade namn på tabellen `%s` till `%s`.';
$_lang['remove_fulltext_index'] = 'Tog bort heltext-indexet `%s`.';
$_lang['systemsetting_xtype_fix'] = 'Rättade till xtypes för modSystemSettings.';
$_lang['transportpackage_manifest_text'] = 'Modifierade kolumnen `manifest` till TEXT från MEDIUMTEXT i `%s`.';
$_lang['update_closure_table'] = 'Uppdaterar stängningstabellens data för klassen `%s`.';