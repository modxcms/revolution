<?php
/**
 * Italian Upgrades Lexicon Topic for Revolution setup.
 *
 * @package setup
 * @subpackage lexicon
 */
$_lang['add_column'] = 'Aggiunte `[[+column]]` nuove colonne a `[[+table]]`.';
$_lang['add_index'] = 'Aggiunto nuovo indice su `[[+index]]` per la tabella `[[+table]]`.';
$_lang['add_moduser_classkey'] = 'Aggiunto il campo class_key per supportare derivati di modUser.';
$_lang['added_cachepwd'] = 'Aggiunto campo cachepwd mancante nelle prime releases di Revolution.';
$_lang['added_content_ft_idx'] = 'Aggiunto nuovo indice full-text `content_ft_idx` sui campi `pagetitle`, `longtitle`, `description`, `introtext`, `content`.';
$_lang['allow_null_properties'] = 'Correzione per consentire null per `[[+class]]`.`properties`.';
$_lang['alter_activeuser_action'] = 'Modificato il campo modActiveUser `action` per consentire etichette azione piu\' lunghe.';
$_lang['alter_usermessage_messageread'] = 'Cambiato il campo modUserMessage `messageread` in `read`.';
$_lang['alter_usermessage_postdate'] = 'Cambiato il campo modUserMessage `postdate` da INT a DATETIME e rinominato `date_sent`.';
$_lang['alter_usermessage_subject'] = 'Cambiato il campo modUserMessage `subject` da VARCHAR(60) a VARCHAR(255).';
$_lang['change_column'] = 'Cambiato il campo `[[+old]]` in `[[+new]]` nella tabella `[[+table]]`.';
$_lang['change_default_value'] = 'Cambiato il valore di default per la colonna `[[+column]]` in "[[+value]]" nella tabella `[[+table]]`.';
$_lang['connector_acls_removed'] = 'Rimosso connettore contesto ACLs.';
$_lang['connector_acls_not_removed'] = 'Non e\' stato possibile rimuovere connettore contesto ACLs.';
$_lang['connector_ctx_removed'] = '';
$_lang['connector_ctx_not_removed'] = 'Non e\' stato possibile rimuovere connettore contesto.';
$_lang['data_remove_error'] = 'Errore durante la rimozione dei dati per la classe `[[+class]]`.';
$_lang['data_remove_success'] = 'Rimossi con successo i dati dalla tabella per la classe `[[+class]]`.';
$_lang['drop_column'] = 'Eliminata colonna `[[+column]]` dalla tabella `[[+table]]`.';
$_lang['drop_index'] = 'Eliminato indice `[[+index]]` nella tabella `[[+table]]`.';
$_lang['lexiconentry_createdon_null'] = 'Cambiato il campo modLexiconEntry `createdon` per consentire NULL.';
$_lang['lexiconentry_focus_alter'] = 'Cambiato modLexiconEntry `focus` da VARCHAR(100) a INT(10).';
$_lang['lexiconentry_focus_alter_int'] = 'Aggiornati i dati della colonna modLexiconEntry `focus` da stringa a nuova chiave esterna intera da modLexiconTopic.';
$_lang['lexiconfocus_add_id'] = 'Aggiunta colonna modLexiconFocus `id`.';
$_lang['lexiconfocus_add_pk'] = 'Aggiunta chiave primaria modLexiconFocus PRIMARY KEY alla colonna `id`.';
$_lang['lexiconfocus_alter_pk'] = 'Cambiato modLexiconFocus `name` da PRIMARY KEY a UNIQUE KEY';
$_lang['lexiconfocus_drop_pk'] = 'Eliminata la chiave primaria modLexiconFocus PRIMARY KEY.';
$_lang['modify_column'] = 'Colonna `[[+column]]` modificata da `[[+old]]` a `[[+new]]` nella tabella `[[+table]]`';
$_lang['rename_column'] = 'Rinominata colonna `[[+old]]` in `[[+new]]` nella tabella `[[+table]]`.';
$_lang['rename_table'] = 'Rinominata la tabella `[[+old]]` in `[[+new]]`.';
$_lang['remove_fulltext_index'] = 'Rimosso indice full-text `[[+index]]`.';
$_lang['systemsetting_xtype_fix'] = 'Corretto con successo xtypes per modSystemSettings.';
$_lang['transportpackage_manifest_text'] = 'Modificata colonna `manifest` in TEXT da MEDIUMTEXT su `[[+class]]`.';
$_lang['update_closure_table'] = 'Aggiornamento closure table data per la classe `[[+class]]`.';
$_lang['update_table_column_data'] = 'Aggiornati i dati nella colonna [[+column]] della tabella [[+table]] ( [[+class]] )';