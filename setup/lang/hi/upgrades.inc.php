<?php
/**
 * English Upgrades Lexicon Topic for Revolution setup.
 *
 * @package setup
 * @subpackage lexicon
 */
$_lang['add_column'] = '[[+table]] में नया \'[[+column]]\' कॉलम जोड़ा गया।';
$_lang['add_index'] = 'टेबल [[+table]] के लिए \'[[index]]\' पर नया इंडेक्स जोड़ा गया।';
$_lang['alter_column'] = 'तालिका [[+table]] में संशोधित कॉलम \'[[colomn]]\' ।';
$_lang['add_moduser_classkey'] = 'ModUser डेरिवेटिव का समर्थन करने के लिए जोड़ा गया class_key क्षेत्र।';
$_lang['added_cachepwd'] = 'Early Revolution के रिलीज में लापता cachepwd क्षेत्र जोड़ा गया।';
$_lang['added_content_ft_idx'] = 'जोड़े गए नए `content_ft_idx` full-text index on the fields `pagetitle`, `longtitle`, `description`, `introtext`, `content`.';
$_lang['allow_null_properties'] = 'फिक्सिंग के लिए नल की अनुमति दें \' [[+class]]\'। \'properties\'।';
$_lang['alter_activeuser_action'] = 'अब action labels की अनुमति के लिए संशोधित modActiveUser \'action\' फ़ील्ड।';
$_lang['alter_usermessage_messageread'] = 'बदला modUserMessage `messageread` क्षेत्र के लिए` read`।';
$_lang['alter_usermessage_postdate'] = 'परिवर्तित modUserMessage \'postdate\' फ़ील्ड एक INT से एक दिनांक समय और नाम \'date_sent\' के लिए।';
$_lang['alter_usermessage_subject'] = 'परिवर्तित modUserMessage \'subject\'  के  फ़ील्ड VARCHAR(60) के  लिए  VARCHAR(255) ।';
$_lang['authority_unique_index_error'] = 'Multiple modUserGroup records with the same authority were found. You will need to update these to have unique authority values and then re-run the upgrade.';
$_lang['change_column'] = 'टेबल [[+table]] पर \'[[+old]]\' फील्ड को \'+[[new]]\' में बदला गया।';
$_lang['change_default_value'] = 'टेबल [[table] पर कॉलम \'[[+column]]\' के लिए डिफॉल्ट मान बदलकर "[[value]]" कर दिया गया है।';
$_lang['connector_acls_removed'] = 'निकाले गए कनेक्टर context ACLs.';
$_lang['connector_acls_not_removed'] = 'कनेक्टर context ACLs को दूर नहीं कर सकता।';
$_lang['connector_ctx_removed'] = '';
$_lang['connector_ctx_not_removed'] = 'कनेक्टर context को दूर नहीं किया जा सका।';
$_lang['data_remove_error'] = 'class \'[[+class]]\' के लिए डेटा को निकालने में त्रुटि।';
$_lang['data_remove_success'] = 'Class \'[[+class]]\' के लिए तालिका से डेटा सफलतापूर्वक निकाल दिया।';
$_lang['drop_column'] = 'टेबल [[table]] पर गिरा हुआ कॉलम \'[[column]]\' ।';
$_lang['drop_index'] = 'तालिका [[table]] पर गिराए गए सूचकांक \'[[+Index]]\'';
$_lang['lexiconentry_createdon_null'] = 'परिवर्तित modLexiconEntry \'createdon\' NULL की अनुमति देने के लिए\'।';
$_lang['lexiconentry_focus_alter'] = 'VARCHAR (100) से बदल modLexiconEntry `focus` INT(10) करने के लिए।';
$_lang['lexiconentry_focus_alter_int'] = 'स्ट्रिंग से modLexiconEntry \'focus\' स्तंभ डेटा modLexiconTopic से नया int foreign key को अद्यतन किया।';
$_lang['lexiconfocus_add_id'] = 'जोड़ा गया modLexiconFocus \'आईडी कॉलम।';
$_lang['lexiconfocus_add_pk'] = '`Id` स्तंभ करने के लिए जोड़ा गया modLexiconFocus PRIMARY KEY।';
$_lang['lexiconfocus_alter_pk'] = 'ModLexiconFocus \'name\' से PRIMARY KEY के लिए UNIQUE KEY बदल गया';
$_lang['lexiconfocus_drop_pk'] = 'Dropped modl_exiconFocus PRIMARY KEY.';
$_lang['modify_column'] = 'Modified कॉलम `[[+column]]` from `[[+old]]` to `[[+new]]` on table [[+table]]';
$_lang['rename_column'] = 'टेबल [[+टेबल]] पर नाम बदलकर कॉलम `[[+पुराना]]` से `[[+नया]] कर दिया गया है!';
$_lang['rename_table'] = 'नाम बदली गई तालिका \'[[+old]]\' के लिए \'[[+new]]\'।';
$_lang['remove_fulltext_index'] = 'पूर्ण-पाठ अनुक्रमणिका \'[[+index]]\' हटा दिया।';
$_lang['systemsetting_xtype_fix'] = 'ModSystemSettings के लिए सफलतापूर्वक फिक्स्ड xtypes.';
$_lang['transportpackage_manifest_text'] = 'संशोधित स्तंभ \'manifest\' पाठ करने से MEDIUMTEXT पर \'[[+class]]\'।';
$_lang['update_closure_table'] = 'वर्ग `[[+class]]` के लिए बंद करने की तालिका डेटा को अद्यतन।';
$_lang['update_table_column_data'] = 'अपडेट किया गया डेटा में स्तंभ [[+column]] की तालिका [[+table]] ([[+class]])';
$_lang['iso_country_code_converted'] = 'उपयोगकर्ता प्रोफाइल देश के नामों को iso कोड में सफलतापूर्वक रूपांतरित किया गया.';
$_lang['legacy_cleanup_complete'] = 'लीगेसी फाइल क्लीन अप पूर्ण.';
$_lang['legacy_cleanup_count'] = 'Remove [[+files]]file(s) and [[+folders]] folder(s).';
$_lang['clipboard_flash_file_unlink_success'] = 'कॉपी को क्लिपबोर्ड फ्लैश फाइल में सफलतापूर्वक निकाल दिया गया है |';
$_lang['clipboard_flash_file_unlink_failed'] = 'कॉपी को क्लिपबोर्ड फ्लैश फाइल में सफलतापूर्वक निकाल दिया गया |';
$_lang['clipboard_flash_file_missing'] = 'कॉपी को क्लिपबोर्ड फ्लैश फाइल में सफलतापूर्वक निकाल दिया |';
$_lang['system_setting_cleanup_success'] = 'सिस्टम सेटिंग [[+कुंजी]] हटाई गई |
 ';
$_lang['system_setting_cleanup_failed'] = 'सिस्टम सेटिंग \'[[कुंजी]]\' को हटाया नहीं जा सका |';
$_lang['system_setting_update_xtype_success'] = 'सिस्टम सेटिंग \'\'[[+key]] के लिए एक्सपाइप को \' [[old xrype]]\' से \'[[new xrype]]\' में सफलतापूर्वक बदल दिया गया |';
$_lang['system_setting_update_xtype_failure'] = 'सिस्टम सेटिंग \'[[+key]]\' के लिए xtype को \'[[+old_xtype]]\' से \'[[ new x_type]]\' बदलने में विफल |';
$_lang['system_setting_update_success'] = 'सिस्टम सेटिंग \'[[+कुंजी]]\' अपडेट की गई |';
$_lang['system_setting_update_failed'] = 'सिस्टम सेटिंग \'[[+कुंजी]]\' अपडेट की गई |';
$_lang['system_setting_rename_key_success'] = 'Successfully renamed the System Setting key from `[[+old_key]]` to `[[+new_key]]`.';
$_lang['system_setting_rename_key_failure'] = 'Failed to rename the System Setting key from `[[+old_key]]` to `[[+new_key]]`.';
