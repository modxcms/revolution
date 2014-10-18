<?php
/**
 * English Upgrades Lexicon Topic for Revolution setup.
 *
 * @package setup
 * @subpackage lexicon
 */
$_lang['add_column'] = 'Добавена нова `[[+column]]` колона към `[[+table]]`.';
$_lang['add_index'] = 'Добавен нов `[[+index]]` за таблица `[[+table]]`.';
$_lang['add_moduser_classkey'] = 'Добавено class_key поле за съпорт на modUser производни.';
$_lang['added_cachepwd'] = 'Добавеното поле cachepwd липсва в ранните издания на Revolution.';
$_lang['added_content_ft_idx'] = 'Добавен нов `content_ft_idx` пълен текстови индекс на полетата `pagetitle`, `longtitle`, `description`, `introtext`, `content`.';
$_lang['allow_null_properties'] = 'Фиксиране позволява нула за `[[+class]]`.`properties`.';
$_lang['alter_activeuser_action'] = 'Модифицирано modActiveUser `action` поле за позволяване на етикети с по-дълго действие.';
$_lang['alter_usermessage_messageread'] = 'Променено modUserMessage поле `messageread` на `read`.';
$_lang['alter_usermessage_postdate'] = 'Променено modUserMessage `postdate` поле от INT на DATETIME и с името `date_sent`.';
$_lang['alter_usermessage_subject'] = 'Променено modUserMessage `subject` поле от VARCHAR(60) на VARCHAR(255).';
$_lang['change_column'] = 'Променено `[[+old]]` поле на`[[+new]]` в таблица `[[+table]]`.';
$_lang['change_default_value'] = 'Променена стойност по подразбиране за колона `[[+column]]` на "[[+value]]" в таблица `[[+table]]`.';
$_lang['connector_acls_removed'] = 'Премахнат конектор конткест ACLs.';
$_lang['connector_acls_not_removed'] = 'Не може да се премахне конектор контекста ACLs.';
$_lang['connector_ctx_removed'] = '';
$_lang['connector_ctx_not_removed'] = 'Не може да се премахне конектор контекст.';
$_lang['data_remove_error'] = 'Грешка при премахване на данни за клас `[[+class]]`.';
$_lang['data_remove_success'] = 'Успешно премахнати данни от таблица за клас `[[+class]]`.';
$_lang['drop_column'] = 'Отпаднала колона `[[+column]]` за таблица `[[+table]]`.';
$_lang['drop_index'] = 'Отпаднал индекс `[[+index]]` за таблица `[[+table]]`.';
$_lang['lexiconentry_createdon_null'] = 'Променен modLexiconEntry `createdon` за разрешаване на NULL.';
$_lang['lexiconentry_focus_alter'] = 'Променен  modLexiconEntry `focus` от VARCHAR(100) на INT(10).';
$_lang['lexiconentry_focus_alter_int'] = 'Актуализирана modLexiconEntry `focus`колона данни от низ в нов int външен ключ от modLexiconTopic.';
$_lang['lexiconfocus_add_id'] = 'Добавена колона modLexiconFocus `id`.';
$_lang['lexiconfocus_add_pk'] = 'Добавен modLexiconFocus PRIMARY KEY към `id` колона.';
$_lang['lexiconfocus_alter_pk'] = 'Променено modLexiconFocus `name` от PRIMARY KEY на UNIQUE KEY';
$_lang['lexiconfocus_drop_pk'] = 'Отпаднал modLexiconFocus PRIMARY KEY.';
$_lang['modify_column'] = 'Променена колона `[[+column]]` от `[[+old]]` на `[[+new]]` за таблица `[[+table]]`';
$_lang['rename_column'] = 'Преименувана колона `[[+old]]` на`[[+new]]` за таблица `[[+table]]`.';
$_lang['rename_table'] = 'Преименувана таблица `[[+old]]` на `[[+new]]`.';
$_lang['remove_fulltext_index'] = 'Премахнат пълен текстови индекс `[[+index]]`.';
$_lang['systemsetting_xtype_fix'] = 'Успешно фиксирани xtypes за modSystemSettings.';
$_lang['transportpackage_manifest_text'] = 'Променена колона `manifest` на TEXT от MEDIUMTEXT за `[[+class]]`.';
$_lang['update_closure_table'] = 'Актуализиране на  затворени таблични данни за клас `[[+class]]`.';
$_lang['update_table_column_data'] = 'Актуализирани данни [[+column]] на таблица [[+table]] ( [[+class]] )';