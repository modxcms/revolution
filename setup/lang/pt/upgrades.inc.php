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
$_lang['add_moduser_classkey'] = 'Campo adicionado class_key para apoiar derivados modUser.';
$_lang['added_cachepwd'] = 'Campo faltante em versões anteriores do Revolution cachepw adicionado.';
$_lang['added_content_ft_idx'] = 'Adicionado novo `content_ft_idx` índice de texto completo nos campos `pagetitle`, `longtitle`, `description`, `introtext`, `content`.';
$_lang['allow_null_properties'] = 'Corrigindo permissão para nulo para a `[[+class]]`.`properties`.';
$_lang['alter_activeuser_action'] = 'Alterado modActiveUser `action` para permitir maiores rótulos de ações.';
$_lang['alter_usermessage_messageread'] = 'Alterado campo modUserMessage `messageread` para `read`.';
$_lang['alter_usermessage_postdate'] = 'Alteardo campo modUserMessage `postdate` de um INT para um DATETIME e para nome `date_sent`.';
$_lang['alter_usermessage_subject'] = 'Alterado campo modUserMessage `subject` de VARCHAR(60) para VARCHAR(255).';
$_lang['authority_unique_index_error'] = 'Multiple modUserGroup records with the same authority were found. You will need to update these to have unique authority values and then re-run the upgrade.';
$_lang['change_column'] = 'Changed `[[+old]]` field to `[[+new]]` on table [[+table]].';
$_lang['change_default_value'] = 'Changed default value for column `[[+column]]` to "[[+value]]" on table [[+table]].';
$_lang['connector_acls_removed'] = 'Contexto conector Removido ACLs.';
$_lang['connector_acls_not_removed'] = 'Não foi possível remover ACLs contexto de conectores.';
$_lang['connector_ctx_removed'] = '';
$_lang['connector_ctx_not_removed'] = 'Não foi possível remover contexto de conector.';
$_lang['data_remove_error'] = 'Erro ao remover os dados para a classe `[[+class]]`.';
$_lang['data_remove_success'] = 'Dados removido com sucesso da tabela para a classe `[[+class]]`.';
$_lang['drop_column'] = 'Dropped column `[[+column]]` on table [[+table]].';
$_lang['drop_index'] = 'Dropped index `[[+index]]` on table [[+table]].';
$_lang['lexiconentry_createdon_null'] = 'Alterado modLexiconEntry `createdon` para aceitar NULL.';
$_lang['lexiconentry_focus_alter'] = 'Alterado modLexiconEntry `focus` de VARCHAR(100) para INT(10).';
$_lang['lexiconentry_focus_alter_int'] = 'Atualizada coluna de dados modLexiconEntry `focus` de string para nova chave estrangeira int de modLexiconTopic.';
$_lang['lexiconfocus_add_id'] = 'Adicionada coluna modLexiconFocus `id`.';
$_lang['lexiconfocus_add_pk'] = 'Adicionado modLexiconFocus PRIMARY KEY para coluna `id`.';
$_lang['lexiconfocus_alter_pk'] = 'Alterado modLexiconFocus `name` de PRIMARY KEY para UNIQUE KEY';
$_lang['lexiconfocus_drop_pk'] = 'Derrubado modLexiconFocus PRIMARY KEY.';
$_lang['modify_column'] = 'Modified column `[[+column]]` from `[[+old]]` to `[[+new]]` on table [[+table]]';
$_lang['rename_column'] = 'Renamed column `[[+old]]` to `[[+new]]` on table [[+table]].';
$_lang['rename_table'] = 'Renomeada tabela de `[[+old]]` para `[[+new]]`.';
$_lang['remove_fulltext_index'] = 'Removido índice de texto completo `[[+index]]`.';
$_lang['systemsetting_xtype_fix'] = 'Corrigido com sucesso xtypes para modSystemSettings.';
$_lang['transportpackage_manifest_text'] = 'Moficada a coluna `manifest` para TEXT de MEDIUMTEXT em `[[+class]]`.';
$_lang['update_closure_table'] = 'Atualizando os dados da tabela de fechamento para a classe `[[+class]]`.';
$_lang['update_table_column_data'] = 'Dados atualizados em coluna [[+column]] da tabela [[+table]] ( [[+class]] )';
$_lang['iso_country_code_converted'] = 'Convertido com êxito os nomes de país do perfil de usuário para códigos ISO.';
$_lang['legacy_cleanup_complete'] = 'Limpeza de arquivo legado completa.';
$_lang['legacy_cleanup_count'] = 'Removido [[+files]] arquivo(s) e [[+folders]] pasta(s).';
$_lang['clipboard_flash_file_unlink_success'] = 'Arquivo copiado com sucesso para a área de transferência.';
$_lang['clipboard_flash_file_unlink_failed'] = 'Erro ao remover uma cópia do arquivo para a área de transferência.';
$_lang['clipboard_flash_file_missing'] = 'O arquivo copiado para a área de transferência foi removido.';
$_lang['system_setting_cleanup_success'] = 'Configuração `[[+key]]` do sistema removida.';
$_lang['system_setting_cleanup_failed'] = 'A configuração `[[+key]]` do sistema não pôde ser removida.';
$_lang['system_setting_update_xtype_success'] = 'Configuração xtype do sistema, altarado com sucesso!  `[[+key]]` de `[[+old_xtype]]` para `[[+new_xtype]]`.';
$_lang['system_setting_update_xtype_failure'] = 'Falha ao alterar o xtype da configuração do sistema `[[+key]]` de `[[+old_xtype]]` para [[+new_xtype]]`.';
$_lang['system_setting_update_success'] = 'Configuração `[[+key]]` do sistema atualizada.';
$_lang['system_setting_update_failed'] = 'A configuração `[[+key]]` do sistema não pôde ser atualizada.';
$_lang['system_setting_rename_key_success'] = 'Successfully renamed the System Setting key from `[[+old_key]]` to `[[+new_key]]`.';
$_lang['system_setting_rename_key_failure'] = 'Failed to rename the System Setting key from `[[+old_key]]` to `[[+new_key]]`.';
