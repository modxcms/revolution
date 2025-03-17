<?php
/**
 * Azerbaijani Upgrades Lexicon Topic for Revolution setup.
 *
 * @package setup
 * @subpackage lexicon
 */
$_lang['add_column'] = 'Yeni `[[+column]]` sütunu `[[+table]]` cədvəlinə əlavə edildi.';
$_lang['add_index'] = 'Yeni indeks `[[+index]]` cədvəlində `[[+table]]` əlavə edildi.';
$_lang['alter_column'] = '`[[+column]]` sütunu `[[+table]]` cədvəlində dəyişdirildi.';
$_lang['add_moduser_classkey'] = 'modUser törəmələrini dəstəkləmək üçün class_key sahəsi əlavə edildi.';
$_lang['added_cachepwd'] = 'Erkən Revolution versiyalarında itirilmiş cachepwd sahəsi əlavə edildi.';
$_lang['added_content_ft_idx'] = '`content_ft_idx` tam mətn indeksi `pagetitle`, `longtitle`, `description`, `introtext`, `content` sahələrinə əlavə edildi.';
$_lang['allow_null_properties'] = '`[[+class]]`.`properties` üçün NULL icazəsi düzəldildi.';
$_lang['alter_activeuser_action'] = 'modActiveUser `action` sahəsi dəyişdirildi, daha uzun fəaliyyət etiketlərinə icazə verildi.';
$_lang['alter_usermessage_messageread'] = 'modUserMessage `messageread` sahəsi `read` olaraq dəyişdirildi.';
$_lang['alter_usermessage_postdate'] = 'modUserMessage `postdate` sahəsi INT-dən DATETIME-ə dəyişdirildi və `date_sent` olaraq adlandırıldı.';
$_lang['alter_usermessage_subject'] = 'modUserMessage `subject` sahəsi VARCHAR(60)-dan VARCHAR(255)-ə dəyişdirildi.';
$_lang['authority_unique_index_error'] = 'Eyni səlahiyyətə sahib bir neçə modUserGroup qeydi tapıldı. Bu qeydləri unikal səlahiyyət dəyərləri ilə yeniləməlisiniz və sonra yeniləməni yenidən işə salmalısınız.';
$_lang['change_column'] = '`[[+old]]` sahəsi `[[+new]]` olaraq `[[+table]]` cədvəlində dəyişdirildi.';
$_lang['change_default_value'] = '`[[+column]]` sütunu üçün ilkin dəyər `[[+value]]` olaraq dəyişdirildi `[[+table]]` cədvəlində.';
$_lang['connector_acls_removed'] = 'Connector kontekstinin ACL-ləri silindi.';
$_lang['connector_acls_not_removed'] = 'Connector kontekstinin ACL-ləri silinə bilmədi.';
$_lang['connector_ctx_removed'] = '';
$_lang['connector_ctx_not_removed'] = 'Connector konteksti silinə bilmədi.';
$_lang['data_remove_error'] = '`[[+class]]` sinifi üçün məlumat silinərkən xəta baş verdi.';
$_lang['data_remove_success'] = '`[[+class]]` sinifi üçün cədvəldən məlumat uğurla silindi.';
$_lang['drop_column'] = '`[[+column]]` sütunu `[[+table]]` cədvəlindən silindi.';
$_lang['drop_index'] = '`[[+index]]` indeksi `[[+table]]` cədvəlindən silindi.';
$_lang['lexiconentry_createdon_null'] = 'modLexiconEntry `createdon` sahəsi NULL olmasına icazə verildi.';
$_lang['lexiconentry_focus_alter'] = 'modLexiconEntry `focus` sahəsi VARCHAR(100)-dən INT(10)-a dəyişdirildi.';
$_lang['lexiconentry_focus_alter_int'] = 'modLexiconEntry `focus` sütunu məlumatları mətn formatından yeni int xarici açara modLexiconTopic-dən yeniləndi.';
$_lang['lexiconfocus_add_id'] = 'modLexiconFocus `id` sütunu əlavə edildi.';
$_lang['lexiconfocus_add_pk'] = 'modLexiconFocus üçün `id` sütununa ƏSAS AÇAR əlavə edildi.';
$_lang['lexiconfocus_alter_pk'] = 'modLexiconFocus `name` sahəsi ƏSAS AÇAR-dan UNİKAL AÇAR-a dəyişdirildi.';
$_lang['lexiconfocus_drop_pk'] = 'modLexiconFocus ƏSAS AÇAR-ı silindi.';
$_lang['menu_remove_success'] = 'Menyu elementi `[[+text]]` silindi.';
$_lang['menu_remove_failed'] = 'Menyu elementi `[[+text]]` silinə bilmədi.';
$_lang['menu_update_success'] = 'Menyu elementi `[[+text]]` uğurla yeniləndi.';
$_lang['menu_update_failed'] = 'Menyu elementi `[[+text]]` yenilənə bilmədi.';
$_lang['modify_column'] = '`[[+column]]` sütunu `[[+old]]`-dan `[[+new]]`-ə dəyişdirildi `[[+table]]` cədvəlində.';
$_lang['rename_column'] = '`[[+old]]` sütunu `[[+new]]` olaraq adlandırıldı `[[+table]]` cədvəlində.';
$_lang['rename_table'] = '`[[+old]]` cədvəli `[[+new]]` olaraq adlandırıldı.';
$_lang['remove_fulltext_index'] = 'Tam mətn indeksi `[[+index]]` silindi.';
$_lang['systemsetting_xtype_fix'] = 'modSystemSettings üçün xtypes uğurla düzəldildi.';
$_lang['transportpackage_manifest_text'] = '`manifest` sütunu `TEXT`-ə MEDIUMTEXT-dən dəyişdirildi `[[+class]]`-də.';
$_lang['update_closure_table'] = '`[[+class]]` sinifi üçün bağlanma cədvəli məlumatları yenilənir.';
$_lang['update_table_column_data'] = '`[[+table]]` cədvəlindəki `[[+column]]` sütunundakı məlumatlar yeniləndi ( [[+class]] )';
$_lang['iso_country_code_converted'] = 'İstifadəçi profili ölkə adları ISO kodlarına uğurla çevrildi.';
$_lang['legacy_cleanup_complete'] = 'Keçmiş fayl təmizləmə tamamlandı.';
$_lang['legacy_cleanup_count'] = '[[+files]] fayl(lar) və [[+folders]] qovluq(lar) silindi.';
$_lang['clipboard_flash_file_unlink_success'] = 'Kopyalama flaş faylı uğurla silindi.';
$_lang['clipboard_flash_file_unlink_failed'] = 'Kopyalama flaş faylı silinirken xəta baş verdi.';
$_lang['clipboard_flash_file_missing'] = 'Kopyalama flaş faylı artıq silinib.';
$_lang['system_setting_cleanup_success'] = 'Sistem Tənzimləməsi `[[+key]]` silindi.';
$_lang['system_setting_cleanup_failed'] = 'Sistem Tənzimləməsi `[[+key]]` silinə bilmədi.';
$_lang['system_setting_update_xtype_success'] = 'Sistem Tənzimləməsi `[[+key]]` üçün xtype uğurla `[[+old_xtype]]`-dan `[[+new_xtype]]`-ə dəyişdirildi.';
$_lang['system_setting_update_xtype_failure'] = 'Sistem Tənzimləməsi `[[+key]]` üçün xtype `[[+old_xtype]]`-dan `[[+new_xtype]]`-ə dəyişdirilə bilmədi.';
$_lang['system_setting_update_success'] = 'Sistem Tənzimləməsi `[[+key]]` uğurla yeniləndi.';
$_lang['system_setting_update_failed'] = 'Sistem Tənzimləməsi `[[+key]]` yenilənə bilmədi.';
$_lang['system_setting_rename_key_success'] = 'Sistem Tənzimləməsi açarı `[[+old_key]]`-dan `[[+new_key]]`-ə uğurla dəyişdirildi.';
$_lang['system_setting_rename_key_failure'] = 'Sistem Tənzimləməsi açarı `[[+old_key]]`-dan `[[+new_key]]`-ə dəyişdirilə bilmədi.';