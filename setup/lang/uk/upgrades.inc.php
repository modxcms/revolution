<?php
/**
 * English Upgrades Lexicon Topic for Revolution setup.
 *
 * @package setup
 * @subpackage lexicon
 */
$_lang['add_column'] = 'Додано новий стовпець `[[+column]]` в таблицю `[[+table]]`. ';
$_lang['add_index'] = 'Додано новий індекс `[[+index]]` для таблиці `[[+table]]`. ';
$_lang['alter_column'] = 'Змінено стовпець `[[+column]]` в таблиці `[[+table]]`. ';
$_lang['add_moduser_classkey'] = 'Додано поле `class_key` для підтримки похідних від `modUser` ';
$_lang['added_cachepwd'] = 'Додано поле `cachepwd`, втрачене в попередніх релізах MODX. ';
$_lang['added_content_ft_idx'] = 'Додано новий повнотекстовий індекс `content_ft_idx` в наступні поля: `pagetitle`, `longtitle`,` description`, `introtext`,` content`. ';
$_lang['allow_null_properties'] = 'Виправлено можливе значення NULL у `[[+class]]` .`properties`. ';
$_lang['alter_activeuser_action'] = 'Збільшена можлива довжина значення поля `action` в` modActiveUser`. ';
$_lang['alter_usermessage_messageread'] = 'У таблиці `modUserMessage` поле `messageread` замінено на `read`. ';
$_lang['alter_usermessage_postdate'] = 'У таблиці `modUserMessage` у поля `postdate` змінений тип з INT на DATETIME і перейменовано в `date_sent`. ';
$_lang['alter_usermessage_subject'] = 'У таблиці `modUserMessage` у поля `subject` змінений тип з VARCHAR (60) на VARCHAR (255). ';
$_lang['authority_unique_index_error'] = 'Multiple modUserGroup records with the same authority were found. You will need to update these to have unique authority values and then re-run the upgrade.';
$_lang['change_column'] = 'Змінено поле `[[+old]]` на `[[+new]]` в таблиці `[[+table]]`. ';
$_lang['change_default_value'] = 'Змінено значення за замовчуванням в полі `[[+column]]` на «[[+value]]» в таблиці `[[+table]]`. ';
$_lang['connector_acls_removed'] = 'Прибрано списки доступу (ACL) у контекстного коннектора. ';
$_lang['connector_acls_not_removed'] = 'Прибрати списки доступу (ACL) у контекстного коннектора не вдалося. ';
$_lang['connector_ctx_removed'] = '';
$_lang['connector_ctx_not_removed'] = 'Прибрати контекстний коннектор не вдалося.';
$_lang['data_remove_error'] = 'Помилка видалення даних для класу `[[+class]]`.';
$_lang['data_remove_success'] = 'Дані для класу `[[+class]]` з таблиці успішно видалені. ';
$_lang['drop_column'] = 'Вилучений стовпець `[[+column]]` в таблиці `[[+table]]`. ';
$_lang['drop_index'] = 'Вилучений індекс `[[+index]]` в таблиці `[[+table]]`. ';
$_lang['lexiconentry_createdon_null'] = 'Додана можливість приймати значення NULL для поля `createdon` в `modLexiconEntry`. ';
$_lang['lexiconentry_focus_alter'] = 'Змінено тип поля `focus` в` modLexiconEntry` з VARCHAR (100) на INT (10). ';
$_lang['lexiconentry_focus_alter_int'] = 'Оновлені дані в поле `focus` в `modLexiconEntry` зі строкових на нове ціле значення ключа з `modLexiconTopic`. ';
$_lang['lexiconfocus_add_id'] = 'Додано поле `id` в `modLexiconFocus`. ';
$_lang['lexiconfocus_add_pk'] = 'Додан PRIMARY KEY до полю `id` в `modLexiconFocus`. ';
$_lang['lexiconfocus_alter_pk'] = 'Ключ поля `name` в таблиці `modLexiconFocus` змінений з PRIMARY KEY на UNIQUE KEY ';
$_lang['lexiconfocus_drop_pk'] = 'Вилучений PRIMARY KEY у таблиці `modLexiconFocus`. ';
$_lang['menu_remove_success'] = 'Menu item `[[+text]]` removed.';
$_lang['menu_remove_failed'] = 'Menu item `[[+text]]` could not be removed.';
$_lang['menu_update_success'] = 'Menu item `[[+text]]` updated.';
$_lang['menu_update_failed'] = 'Menu item `[[+text]]` could not be updated.';
$_lang['modify_column'] = 'Змінено стовпець `[[+column]]` з `[[+old]]` на `[[+new]]` в таблиці `[[+table]]` ';
$_lang['rename_column'] = 'Перейменований стовпець `[[+old]]` на `[[+new]]` в таблиці `[[+table]]`.';
$_lang['rename_table'] = 'Таблиця `[[+old]]` перейменована в `[[+new]]`. ';
$_lang['remove_fulltext_index'] = 'Вилучений повнотекстовий індекс `[[+index]]`. ';
$_lang['systemsetting_xtype_fix'] = 'Успішно виправлені xtype в `modSystemSettings`.';
$_lang['transportpackage_manifest_text'] = 'Змінено стовпець `manifest` на TEXT з MEDIUMTEXT в` [[+class]] `. ';
$_lang['update_closure_table'] = 'Оновлення закритою таблиці даних для класу `[[+class]]`.';
$_lang['update_table_column_data'] = 'Оновлені дані в стовпці `[[+column]]` в таблиці `[[+table]]` (`[[+class]]`)';
$_lang['iso_country_code_converted'] = 'Назви країн в профілі користувача успішно перетворені в коди ISO. ';
$_lang['legacy_cleanup_complete'] = 'Очищення застарілих файлів завершена.';
$_lang['legacy_cleanup_count'] = 'Вилучені [[+files]] файл(и) і [[+folders]] каталог(і). ';
$_lang['clipboard_flash_file_unlink_success'] = 'Успішно видалена копія в флеш-файлі в буфері обміну.';
$_lang['clipboard_flash_file_unlink_failed'] = 'Помилка видалення копії в флеш-файлі буфера обміну.';
$_lang['clipboard_flash_file_missing'] = 'Флеш-файл буфера обміну вже видалений.';
$_lang['system_setting_cleanup_success'] = 'Системний параметр `[[+key]]` видалено.';
$_lang['system_setting_cleanup_failed'] = 'Системний параметр `[[+key]]` не вдалося видалити.';
$_lang['system_setting_update_xtype_success'] = 'Успішно змінено системний параметр xtype `[[+key]]` з `[[+old_xtype]]` на `[[+new_xtype]]`.';
$_lang['system_setting_update_xtype_failure'] = 'Не вдалося змінити системний параметр xtype `[[+key]]` з `[[+old_xtype]]` на `[[+new_xtype]]`.';
$_lang['system_setting_update_success'] = 'Системний параметр `[[+key]]` оновлено.';
$_lang['system_setting_update_failed'] = 'Системний параметр `[[+key]]` не вдалося оновити.';
$_lang['system_setting_rename_key_success'] = 'Системний ключ успішно перейменовано з `[[+old_key]]` на `[[+new_key]]`.';
$_lang['system_setting_rename_key_failure'] = 'Не вдалося перейменувати системний ключ з `[[+old_key]]` на `[[+new_key]]`.';
