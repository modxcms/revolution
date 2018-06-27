<?php
/**
 * English Upgrades Lexicon Topic for Revolution setup.
 *
 * @package setup
 * @subpackage lexicon
 */
$_lang['add_column'] = 'Neue Spalte `[[+column]]` zur Tabelle [[+table]] hinzugefügt.';
$_lang['add_index'] = 'Neuen Index für `[[+index]]` zur Tabelle [[+table]] hinzugefügt.';
$_lang['alter_column'] = 'Spalte `[[+column]]` der Tabelle [[+table]] geändert.';
$_lang['add_moduser_classkey'] = 'Feld `class_key` hinzugefügt, um von modUser abgeleitete Klassen zu unterstützen.';
$_lang['added_cachepwd'] = 'Feld `cachepwd` hinzugefügt, das in frühen Revolution-Releases fehlte.';
$_lang['added_content_ft_idx'] = 'Neuen Volltext-Index `content_ft_idx` hinzugefügt für die Felder `pagetitle`, `longtitle`, `description`, `introtext`, `content`.';
$_lang['allow_null_properties'] = 'Passe "allow null" für `[[+class]]`.`properties` an.';
$_lang['alter_activeuser_action'] = 'modActiveUser: Feld `action` geändert, um längere Aktionsbezeichnungen zu erlauben.';
$_lang['alter_usermessage_messageread'] = 'modUserMessage: Feld `messageread` in `read` geändert.';
$_lang['alter_usermessage_postdate'] = 'modUserMessage: Feld `postdate` von INT in DATETIME und den Namen in `date_sent` geändert.';
$_lang['alter_usermessage_subject'] = 'modUserMessage: Feld `subject` von VARCHAR(60) in VARCHAR(255) geändert.';
$_lang['change_column'] = 'Feld `[[+old]]` in `[[+new]]` in der Tabelle [[+table]] geändert.';
$_lang['change_default_value'] = 'Standardwert für Spalte `[[+column]]` der Tabelle [[+table]] auf "[[+value]]" geändert.';
$_lang['connector_acls_removed'] = 'ACLs des Connector-Kontexts gelöscht.';
$_lang['connector_acls_not_removed'] = 'Konnte ACLs des Connector-Kontexts nicht löschen.';
$_lang['connector_ctx_removed'] = '';
$_lang['connector_ctx_not_removed'] = 'Konnte Connector-Kontext nicht löschen.';
$_lang['data_remove_error'] = 'Fehler beim Löschen der Daten für die Klasse `[[+class]]`.';
$_lang['data_remove_success'] = 'Daten aus der Tabelle für die Klasse `[[+class]]` erfolgreich gelöscht.';
$_lang['drop_column'] = 'Spalte `[[+column]]` von der Tabelle [[+table]] entfernt.';
$_lang['drop_index'] = 'Index `[[+index]]` von der Tabelle [[+table]] entfernt.';
$_lang['lexiconentry_createdon_null'] = 'modLexiconEntry: Feld `createdon` geändert, um NULL zu erlauben.';
$_lang['lexiconentry_focus_alter'] = 'modLexiconEntry: Feld `focus` von VARCHAR(100) in INT(10) geändert.';
$_lang['lexiconentry_focus_alter_int'] = 'modLexiconEntry: Daten der Spalte `focus` geändert von string in neuen int-Fremdschlüssel von modLexiconTopic.';
$_lang['lexiconfocus_add_id'] = 'modLexiconFocus: Spalte `id` hinzugefügt.';
$_lang['lexiconfocus_add_pk'] = 'modLexiconFocus: PRIMARY KEY zur Spalte `id` hinzugefügt.';
$_lang['lexiconfocus_alter_pk'] = 'modLexiconFocus: `name` von PRIMARY KEY in UNIQUE KEY geändert.';
$_lang['lexiconfocus_drop_pk'] = 'modLexiconFocus: PRIMARY KEY entfernt.';
$_lang['modify_column'] = 'Spalte `[[+column]]` von `[[+old]]` in `[[+new]]` in der Tabelle [[+table]] geändert.';
$_lang['rename_column'] = 'Spalte `[[+old]]` in `[[+new]]` in der Tabelle [[+table]] umbenannt.';
$_lang['rename_table'] = 'Tabelle `[[+old]]` umbenannt in `[[+new]]`.';
$_lang['remove_fulltext_index'] = 'Volltext-Index `[[+index]]` entfernt.';
$_lang['systemsetting_xtype_fix'] = 'xtypes für modSystemSettings erfolgreich angepasst.';
$_lang['transportpackage_manifest_text'] = 'Spalte `manifest` von MEDIUMTEXT in TEXT geändert in `[[+class]]`.';
$_lang['update_closure_table'] = 'Daten der Closure-Tabelle für Klasse `[[+class]]` aktualisiert.';
$_lang['update_table_column_data'] = 'Daten in der Spalte [[+column]] der Tabelle [[+table]] ([[+class]]) aktualisiert.';
$_lang['iso_country_code_converted'] = 'Ländernamen in Benutzer-Profilen erfolgreich in ISO-Codes konvertiert.';
$_lang['legacy_cleanup_complete'] = 'Bereinigung der vorhandenen Dateien abgeschlossen.';
$_lang['legacy_cleanup_count'] = '[[+files]] Datei(en) and [[+folders]] Verzeichnis(se) gelöscht.';
