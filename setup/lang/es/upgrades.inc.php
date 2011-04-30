<?php
/**
 * Spanish Upgrades Lexicon Topic for Revolution setup.
 *
 * @package setup
 * @subpackage lexicon
 */
$_lang['add_column'] = 'Se añadió la nueva columna `[[+column]]` a la tabla `[[+table]]`.';
$_lang['add_index'] = 'Se añadió el nuevo índice `[[+index]]` para la tabla `[[+table]]`.';
$_lang['add_moduser_classkey'] = 'Se añadió el campo class_key para soportar derivativas de modUser.';
$_lang['added_cachepwd'] = 'Se añadió el campo cachepwd que faltaba en las versiones tempranas de Revolution.';
$_lang['added_content_ft_idx'] = 'Se añadió el nuevo índice de texto-completo `content_ft_idx` en los campos `pagetitle`, `longtitle`, `description`, `introtext`, `content`.';
$_lang['allow_null_properties'] = 'Arreglando permitir null para `[[+class]]`.`properties`.';
$_lang['alter_activeuser_action'] = 'Se modificó el campo modActiveUser `action` para permitir etiquetas de acción más largas.';
$_lang['alter_usermessage_messageread'] = 'Se cambió el campo modUserMessage `messageread` a `read`.';
$_lang['alter_usermessage_postdate'] = 'Se cambió el campo modUserMessage `postdate` de un INT a un DATETIME y el nombre a `date_sent`.';
$_lang['alter_usermessage_subject'] = 'Se cambió el campo modUserMessage `subject` de VARCHAR(60) a VARCHAR(255).';
$_lang['change_column'] = 'Se cambió el campo `[[+old]]` a `[[+new]]` en la tabla `[[+table]]`.';
$_lang['change_default_value'] = 'Se cambió el valor prefijado para la columna `[[+column]]` a "[[+value]]" en la tabla `[[+table]]`.';
$_lang['connector_acls_removed'] = 'Se removieron los ACLs del conector de contextos.';
$_lang['connector_acls_not_removed'] = 'No se pudieron remover los ACLs del conector de contextos.';
$_lang['connector_ctx_removed'] = '';
$_lang['connector_ctx_not_removed'] = 'No se pudo remover el conector de contextos.';
$_lang['data_remove_error'] = 'Error al remover datos de la clase `[[+class]]`.';
$_lang['data_remove_success'] = 'Se removieron datos de la tabla para la clase `[[+class]]` exitosamente.';
$_lang['drop_column'] = 'Se removió la columna `[[+column]]` en la tabla `[[+table]]`.';
$_lang['drop_index'] = 'Se removió el índice `[[+index]]` en la tabla `[[+table]]`.';
$_lang['lexiconentry_createdon_null'] = 'Se cambió modLexiconEntry `createdon` para permitir NULL.';
$_lang['lexiconentry_focus_alter'] = 'Se cambió modLexiconEntry `focus` de VARCHAR(100) a INT(10).';
$_lang['lexiconentry_focus_alter_int'] = 'Se actualizaron los datos de la columna ó modLexiconEntry `focus` de string a un nuevo int foreign key de modLexiconTopic.';
$_lang['lexiconfocus_add_id'] = 'Se añadió la columna modLexiconFocus `id`.';
$_lang['lexiconfocus_add_pk'] = 'Se añadió modLexiconFocus PRIMARY KEY a la columna `id`.';
$_lang['lexiconfocus_alter_pk'] = 'Se cambió modLexiconFocus `name` de PRIMARY KEY a UNIQUE KEY';
$_lang['lexiconfocus_drop_pk'] = 'Se removió modLexiconFocus PRIMARY KEY.';
$_lang['rename_column'] = 'Se renombró la columna `[[+old]]` a `[[+new]]` en la tabla `[[+table]]`.';
$_lang['rename_table'] = 'Se renombró la tabla `[[+old]]` a `[[+new]]`.';
$_lang['remove_fulltext_index'] = 'Se removió el índice de texto-completo `[[+index]]`.';
$_lang['systemsetting_xtype_fix'] = 'Se arreglaron los xtypes para modSystemSettings exitosamente.';
$_lang['transportpackage_manifest_text'] = 'Se modificó la columna `manifest` a TEXT de MEDIUMTEXT en `[[+class]]`.';
$_lang['update_closure_table'] = 'Actualizando los datos de la tabla de closure para la clase `[[+class]]`.';