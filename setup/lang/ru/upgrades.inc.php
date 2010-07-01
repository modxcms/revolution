<?php
/**
 * Russian Upgrades Lexicon Topic for Revolution setup.
 *
 * @package setup
 * @subpackage lexicon
 */
$_lang['add_column'] = 'Добавлен новый `%s` столбец к `%s`.';
$_lang['add_index'] = 'Добавлен новый индекс в `%s` для таблицы `%s`.';
$_lang['add_moduser_classkey'] = 'Добавлено поле class_key для поддержки производных от modUser';
$_lang['added_cachepwd'] = 'Добавлено поле cachepwd, пропавшее без вести в начале релиза Revolution.';
$_lang['added_content_ft_idx'] = 'Добавлен новый полнотекстовый индекс  `content_ft_idx` в поля `pagetitle`, `longtitle`, `description`, `introtext`, `content`.';
$_lang['allow_null_properties'] = 'Исправлено возможное значение NULL у `%s`.`properties`.';
$_lang['alter_activeuser_action'] = 'Увеличена возможная длина значения поля `action` в modActiveUser.';
$_lang['alter_usermessage_messageread'] = 'В таблице modUserMessage поле `messageread` заменено на `read`.';
$_lang['alter_usermessage_postdate'] = 'В таблице modUserMessage у поля `postdate` изменен тип с INT на DATETIME и переименовано в `date_sent`.';
$_lang['alter_usermessage_subject'] = 'В таблице modUserMessage у поля `subject` изменен тип с VARCHAR(60) на VARCHAR(255).';
$_lang['change_column'] = 'Изменено поле `%s` на `%s` в таблице `%s`.';
$_lang['change_default_value'] = 'Изменено значение по умолчанию в поле`%s` на %s в таблице `%s`.';
$_lang['connector_acls_removed'] = 'Убраны списки доступа (ACL) у контекстного коннектора.';
$_lang['connector_acls_not_removed'] = 'Убрать списки доступа (ACL) у контекстного коннектора не удалось.';
$_lang['connector_ctx_removed'] = '';
$_lang['connector_ctx_not_removed'] = 'Убрать контекстный коннектор не удалось.';
$_lang['data_remove_error'] = 'Ошибка удаления данных для класса `%s`.';
$_lang['data_remove_success'] = 'Данные для класса `%s` из таблицы успешно удалены.';
$_lang['drop_column'] = 'Dropped column `%s` on table `%s`.';
$_lang['drop_index'] = 'Dropped index `%s` on table `%s`.';
$_lang['lexiconentry_createdon_null'] = 'Добавлена возможность принимать значение NULL для поля `createdon` в modLexiconEntry.';
$_lang['lexiconentry_focus_alter'] = 'Изменен тип поля `focus` в modLexiconEntry с VARCHAR(100) на INT(10).';
$_lang['lexiconentry_focus_alter_int'] = 'Обновлены данные в поле `focus` в modLexiconEntry со строковых на новое целое значение ключа из modLexiconTopic.';
$_lang['lexiconfocus_add_id'] = 'Добавлено поле `id` в modLexiconFocus.';
$_lang['lexiconfocus_add_pk'] = 'Добавлен PRIMARY KEY к полю `id` в modLexiconFocus.';
$_lang['lexiconfocus_alter_pk'] = 'Ключ поля `name` в таблице modLexiconFocus изменен с PRIMARY KEY на UNIQUE KEY';
$_lang['lexiconfocus_drop_pk'] = 'Удален PRIMARY KEY у таблицы modLexiconFocus.';
$_lang['rename_column'] = 'Переименовано поле `%s` на `%s` в таблице `%s`.';
$_lang['rename_table'] = 'Таблица `%s` переименована в `%s`.';
$_lang['remove_fulltext_index'] = 'Удален полнотекстовый индекс `%s`.';
$_lang['systemsetting_xtype_fix'] = 'Успешно исправлены xtype в modSystemSettings.';
$_lang['transportpackage_manifest_text'] = 'Изменен тип поля `manifest` с  TEXT на MEDIUMTEXT в `%s`.';
$_lang['update_closure_table'] = 'Updating closure table data for class `%s`.';
