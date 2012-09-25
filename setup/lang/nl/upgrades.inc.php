<?php
/**
 * Dutch Upgrades Lexicon Topic for Revolution setup.
 *
 * @package setup
 * @subpackage lexicon
 * @author Bert Oost, <bertoost85@gmail.com>
 */
$_lang['add_column'] = 'Nieuwe kolom `[[+column]]` toegevoegd in `[[+table]]`.';
$_lang['add_index'] = 'Nieuwe index `[[+index]]` toegevoegd voor tabel `[[+table]]`.';
$_lang['add_moduser_classkey'] = 'Het veld class_key toegevoegd om modUser derivaten te ondersteunen.';
$_lang['added_cachepwd'] = 'Het veld cachepwd toegevoegd welke miste in oudere Revolution releases.';
$_lang['added_content_ft_idx'] = 'Nieuwe full-text index `content_ft_idx` op de volgende velden toegevoegd `pagetitle`, `longtitle`, `description`, `introtext`, `content`.';
$_lang['allow_null_properties'] = 'Toestaan NULL opgelost in `[[+class]]`.`properties`.';
$_lang['alter_activeuser_action'] = 'Het veld `action` voor modActiveUser aangepast om lange actie labels toe te staan.';
$_lang['alter_usermessage_messageread'] = 'Het veld van modUserMessage `messageread` veranderd in `read`.';
$_lang['alter_usermessage_postdate'] = 'Het veld van modUserMessage `postdate` veranderd van INT naar DATETIME en hernoemd naar `date_sent`.';
$_lang['alter_usermessage_subject'] = 'Het veld van modUserMessage `subject` veranderd van VARCHAR(60) naar VARCHAR(255).';
$_lang['change_column'] = 'Het veld `[[+old]]` veranderd naar `[[+new]]` in tabel `[[+table]]`.';
$_lang['change_default_value'] = 'Standaard waarde van kolom `[[+column]]` veranderd naar "[[+value]]" in tabel `[[+table]]`.';
$_lang['connector_acls_removed'] = 'Connector context ACLs verwijderd.';
$_lang['connector_acls_not_removed'] = 'Kan de connector context ACLs niet verwijderen.';
$_lang['connector_ctx_removed'] = 'De connector context is succesvol verwijderd.';
$_lang['connector_ctx_not_removed'] = 'Kan de connector context niet verwijderen.';
$_lang['data_remove_error'] = 'Fout bij verwijderen van de data voor class `[[+class]]`.';
$_lang['data_remove_success'] = 'Met succes de data van tabel `[[+class]]` verwijderd.';
$_lang['drop_column'] = 'Kolom `[[+column]]` verwijderd uit tabel `[[+table]]`.';
$_lang['drop_index'] = 'Index `[[+index]]` verwijderd uit tabel `[[+table]]`.';
$_lang['lexiconentry_createdon_null'] = 'De modLexiconEntry `createdon` veranderd naar toestaan NULL.';
$_lang['lexiconentry_focus_alter'] = 'De modLexiconEntry `focus` van VARCHAR(100) naar INT(10) aangepast.';
$_lang['lexiconentry_focus_alter_int'] = 'De modLexiconEntry `focus` kolom data van string naar een nieuw int relatie met modLexiconTopic aangepast.';
$_lang['lexiconfocus_add_id'] = 'Kolom modLexiconFocus `id` toegevoegd.';
$_lang['lexiconfocus_add_pk'] = 'Primaire sleutel op modLexiconFocus `id` kolom toegevoegd.';
$_lang['lexiconfocus_alter_pk'] = 'Het veld modLexiconFocus `name` van primaire sleutel naar unieke waarde aangepast';
$_lang['lexiconfocus_drop_pk'] = 'De modLexiconFocus primaire sleutel verwijderd.';
$_lang['update_closure_table'] = 'Gegevens in kolom [[+column]] van tabel [[+table]] ([[+class]]) geupdate.';
$_lang['rename_column'] = 'Kolom `[[+old]]` hernoemd naar `[[+new]]` in tabel `[[+table]]`.';
$_lang['rename_table'] = 'Tabel `[[+old]]` hernoemd naar `[[+new]]`.';
$_lang['remove_fulltext_index'] = 'Volledige-tekst index `[[+index]]` verwijderd.';
$_lang['systemsetting_xtype_fix'] = 'Succesvol de xtypes voor modSystemSettings opgelost.';
$_lang['transportpackage_manifest_text'] = 'Kolom `manifest` naar TEXT van MEDIUMTEXT veranderd in `[[+class]]`.';
$_lang['update_closure_table'] = 'Update afsluiting tabel data voor class `[[+class]]`.';
