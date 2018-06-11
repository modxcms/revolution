<?php
/**
 * English Upgrades Lexicon Topic for Revolution setup.
 *
 * @package setup
 * @subpackage lexicon
 */
$_lang['add_column'] = 'Added new `[[+column]]` column to [[+table]].';
$_lang['add_index'] = 'Added new index on `[[+index]]` for table [[+table]].';
$_lang['alter_column'] = 'Modified column `[[+column]]` in table [[+table]].';
$_lang['add_moduser_classkey'] = 'Se añadió el campo class_key para soportar derivados de modUser.';
$_lang['added_cachepwd'] = 'Se añadió el campo cachepwd que faltaba en las primeras versiones de Revolution.';
$_lang['added_content_ft_idx'] = 'Se añadió el nuevo índice full-text `content_ft_idx` a los campos `pagetitle`, `longtitle`, `description`, `introtext`, `content`.';
$_lang['allow_null_properties'] = 'Arreglando `[[+class]]`.`properties` permitir nulos.';
$_lang['alter_activeuser_action'] = 'Se modificó el campo `action` de modActiveUser para permitir etiquetas de acción más largas.';
$_lang['alter_usermessage_messageread'] = 'Se renombró el campo `messageread` de modUserMessage a `read`.';
$_lang['alter_usermessage_postdate'] = 'Se modificó el campo `postdate` de modUserMessage de INT a DATETIME y el nombre a `date_sent`.';
$_lang['alter_usermessage_subject'] = 'Se cambió el campo `subject` de modUserMessage de VARCHAR(60) a VARCHAR(255).';
$_lang['change_column'] = 'Changed `[[+old]]` field to `[[+new]]` on table [[+table]].';
$_lang['change_default_value'] = 'Changed default value for column `[[+column]]` to "[[+value]]" on table [[+table]].';
$_lang['connector_acls_removed'] = 'Se eliminaron las ACLs del conector de contextos.';
$_lang['connector_acls_not_removed'] = 'No se pudieron eliminar las ACLs del conector de contextos.';
$_lang['connector_ctx_removed'] = '';
$_lang['connector_ctx_not_removed'] = 'No se pudo eliminar el conector de contextos.';
$_lang['data_remove_error'] = 'Error al eliminar datos de la clase `[[+class]]`.';
$_lang['data_remove_success'] = 'Se eliminaron con éxito datos de la tabla para la clase `[[+class]]`.';
$_lang['drop_column'] = 'Dropped column `[[+column]]` on table [[+table]].';
$_lang['drop_index'] = 'Dropped index `[[+index]]` on table [[+table]].';
$_lang['lexiconentry_createdon_null'] = 'Se cambió el campo `createdon` de modLexiconEntry para permitir NULL.';
$_lang['lexiconentry_focus_alter'] = 'Se cambió el campo `focus` de modLexiconEntry de VARCHAR(100) a INT(10).';
$_lang['lexiconentry_focus_alter_int'] = 'Se actualizaron los datos de la columna `focus` de modLexiconEntry de string a un nuevo int foreign key de modLexiconTopic.';
$_lang['lexiconfocus_add_id'] = 'Se añadió la columna `id` en modLexiconFocus.';
$_lang['lexiconfocus_add_pk'] = 'Se modificó la columna `id` como PRIMARY KEY en modLexiconFocus.';
$_lang['lexiconfocus_alter_pk'] = 'Se cambió `name` en modLexiconFocus de PRIMARY KEY a UNIQUE KEY';
$_lang['lexiconfocus_drop_pk'] = 'Se eliminó la PRIMARY KEY de modLexiconFocus.';
$_lang['modify_column'] = 'Modified column `[[+column]]` from `[[+old]]` to `[[+new]]` on table [[+table]]';
$_lang['rename_column'] = 'Renamed column `[[+old]]` to `[[+new]]` on table [[+table]].';
$_lang['rename_table'] = 'Se renombró la tabla `[[+old]]` a `[[+new]]`.';
$_lang['remove_fulltext_index'] = 'Se eliminó el índice full-text `[[+index]]`.';
$_lang['systemsetting_xtype_fix'] = 'Los xtypes para modSystemSettings fueron arreglados con éxito.';
$_lang['transportpackage_manifest_text'] = 'Se modificó la columna `manifest` de MEDIUMTEXT a TEXT en `[[+class]]`.';
$_lang['update_closure_table'] = 'Actualizando los datos de la tabla de clausura para la clase `[[+class]]`.';
$_lang['update_table_column_data'] = 'Actualizados datos en la columna [[+column]] de la tabla [[+table]] ( [[+class]] )';
$_lang['iso_country_code_converted'] = 'Successfully converted user profile country names to ISO codes.';
$_lang['legacy_cleanup_complete'] = 'Legacy file clean up complete.';
$_lang['legacy_cleanup_count'] = 'Removed [[+files]] file(s) and [[+folders]] folder(s).';
