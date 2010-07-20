<?php
/**
 * Czech Upgrades Lexicon Topic for Revolution setup.
 *
 * @language cs
 * @package setup
 * @subpackage lexicon
 *
 * @author modxcms.cz
 * @updated 2010-07-18
 */
$_lang['add_column'] = 'Přidán nový sloupec `%s` do tabulky `%s`.';
$_lang['add_index'] = 'Přidán nový index na sloupec `%s` v tabulce `%s`.';
$_lang['add_moduser_classkey'] = 'Přidán sloupec class_key pro podporu odvozenin modUser.';
$_lang['added_cachepwd'] = 'Přidán sloupec cachepwd chybějící v předchozích vydání Revolution.';
$_lang['added_content_ft_idx'] = 'Přidán nový full-textový index `content_ft_idx` na sloupce `pagetitle`, `longtitle`, `description`, `introtext`, `content`.';
$_lang['allow_null_properties'] = 'Opraveno povolení použízí null pro `%s`.`vlastnosti`.';
$_lang['alter_activeuser_action'] = 'Upraven modActiveUser sloupec `action` pro možnost delších názvů akcí.';
$_lang['alter_usermessage_messageread'] = 'Změněn modUserMessage sloupec `messageread` na `read`.';
$_lang['alter_usermessage_postdate'] = 'Změněn modUserMessage sloupec `postdate` z INT na DATETIME a název `date_sent`.';
$_lang['alter_usermessage_subject'] = 'Změněn modUserMessage sloupec `subject` z VARCHAR(60) na VARCHAR(255).';
$_lang['change_column'] = 'Změněn sloupec `%s` na `%s` v tabulce `%s`.';
$_lang['change_default_value'] = 'Změněna výchozí hodnota pro sloupec `%s` na %s v tabulce `%s`.';
$_lang['connector_acls_removed'] = 'Odestraněn konektor kontextu přístupů.';
$_lang['connector_acls_not_removed'] = 'Nepodařilo se odstranit konektor kontextu přístupů.';
$_lang['connector_ctx_removed'] = '';
$_lang['connector_ctx_not_removed'] = 'Nepodařilo se odstranit konektor kontextu.';
$_lang['data_remove_error'] = 'Nastala chyba při odstraňování dat ze třídy `%s`.';
$_lang['data_remove_success'] = 'Úspěšně odstraněna data z tabulky pro třídu `%s`.';
$_lang['drop_column'] = 'Odstraněn sloupec `%s` v tabulce `%s`.';
$_lang['drop_index'] = 'Odstraněn index `%s` v tabulce `%s`.';
$_lang['lexiconentry_createdon_null'] = 'Změněn sloupec modLexiconEntry `createdon` pro možnost NULL.';
$_lang['lexiconentry_focus_alter'] = 'Změněn sloupec modLexiconEntry `focus` z VARCHAR(100) na INT(10).';
$_lang['lexiconentry_focus_alter_int'] = 'Aktualizován sloupec modLexiconEntry `focus` z datového typu STRING na nový INT cizý klíč k modLexiconTopic.';
$_lang['lexiconfocus_add_id'] = 'Přidán sloupec modLexiconFocus `id`.';
$_lang['lexiconfocus_add_pk'] = 'Přidán PRIMARY KEY modLexiconFocus na sloupec `id`.';
$_lang['lexiconfocus_alter_pk'] = 'Změněno pole modLexiconFocus `name` z PRIMARY KEY na UNIQUE KEY';
$_lang['lexiconfocus_drop_pk'] = 'Odstraněn PRIMARY KEY modLexiconFocus.';
$_lang['rename_column'] = 'Přejmenován sloupec `%s` na `%s` v tabulce `%s`.';
$_lang['rename_table'] = 'Přejmenována tabulka `%s` na `%s`.';
$_lang['remove_fulltext_index'] = 'Odstraněn full-textový index `%s`.';
$_lang['systemsetting_xtype_fix'] = 'Úspěšně opraven xtypes pro modSystemSettings.';
$_lang['transportpackage_manifest_text'] = 'Upraven sloupec `manifest` na TEXT z MEDIUMTEXT z tabulky `%s`.';
$_lang['update_closure_table'] = 'Měním uzavírací tabulková data pro třídu `%s`.';