<?php
/**
 * English Upgrades Lexicon Topic for Revolution setup.
 *
 * @package setup
 * @subpackage lexicon
 */
$_lang['add_column'] = 'Új `[[+column]]` oszlop hozzáadva a [[+table]] táblához.';
$_lang['add_index'] = 'Új `[[+index]]` index hozzáadva a [[+table]] táblához.';
$_lang['alter_column'] = 'Módosított oszlop `[[+column]]` a [[+table]] táblában.';
$_lang['add_moduser_classkey'] = 'Hozzáadtuk a class_key mezőt a modUser származékainak támogatásához.';
$_lang['added_cachepwd'] = 'Hozzáadtuk a korai Revolution kiadásokból hiányzó cachepwd mezőt.';
$_lang['added_content_ft_idx'] = 'Hozzáadtuk a `content_ft_idx` teljes szövegű mutatót a `pagetitle`, `longtitle`, `description`, `introtext`, `content` mezőkre.';
$_lang['allow_null_properties'] = 'Javítottuk a `[[+class]]`.`properties` mezőre null engedélyezését.';
$_lang['alter_activeuser_action'] = 'Módosítottuk a modActiveUser `action` mezőt, hogy hosszabb műveleti címkét lehessen megadni.';
$_lang['alter_usermessage_messageread'] = 'Módosult a modUserMessage `messageread` mező `read`-re.';
$_lang['alter_usermessage_postdate'] = 'Módosult a modUserMessage `postdate` mező INT típusról DATETIME típusra és `date_sent` névre.';
$_lang['alter_usermessage_subject'] = 'Módosult a modUserMessage `subject` mező VARCHAR(60) típusról VARCHAR(255)-re.';
$_lang['change_column'] = '`[[+old]]` mező módosítva`[[+new]]` mezőre a [[+table]] táblában.';
$_lang['change_default_value'] = '`[[+column]]` oszlop új alapértéke "[[+value]]" a [[+table]] táblában.';
$_lang['connector_acls_removed'] = 'A csatlakozó környezet hozzáférési listája eltávolítva.';
$_lang['connector_acls_not_removed'] = 'Nem sikerült eltávolítani a csatlakozó környezet hozzáférési listáját.';
$_lang['connector_ctx_removed'] = '';
$_lang['connector_ctx_not_removed'] = 'Nem sikerült eltávolítani a csatlakozó környezetet.';
$_lang['data_remove_error'] = 'Hiba a(z) `[[+class]]` osztály adatainak eltávolítása közben.';
$_lang['data_remove_success'] = 'Sikeresen töröltük az adatokat a(z) `[[+class]]` osztály táblájából.';
$_lang['drop_column'] = '`[[+column]]` oszlop törölve a [[+table]] táblából.';
$_lang['drop_index'] = '`[[+index]]` index törölve a [[+table]] táblából.';
$_lang['lexiconentry_createdon_null'] = 'A modLexiconEntry `createdon` módosítása, hogy lehessen NULL.';
$_lang['lexiconentry_focus_alter'] = 'A modLexiconEntry `focus` módosítása VARCHAR(100)-ról INT(10)-re.';
$_lang['lexiconentry_focus_alter_int'] = 'A modLexiconEntry `focus` mező módosítása szövegről egész szám külső kulcsra a modLexiconTopic táblához.';
$_lang['lexiconfocus_add_id'] = 'A modLexiconFocus `id` oszlop hozzáadva.';
$_lang['lexiconfocus_add_pk'] = 'A modLexiconFocus PRIMARY KEY hozzáadva az `id` oszlophoz.';
$_lang['lexiconfocus_alter_pk'] = 'A modLexiconFocus `name` módosítva PRIMARY KEY helyett UNIQUE KEY-re';
$_lang['lexiconfocus_drop_pk'] = 'A modLexiconFocus PRIMARY KEY törölve.';
$_lang['modify_column'] = '`[[+column]]` oszlop módosítva `[[+old]]`-ról `[[+new]]`-ra a [[+table]] táblában';
$_lang['rename_column'] = '`[[+old]]` oszlop átnevezve `[[+new]]`-ra a [[+table]] táblában.';
$_lang['rename_table'] = '`[[+old]]` tábla átnevezve `[[+new]]`-ra.';
$_lang['remove_fulltext_index'] = '`[[+index]]` teljes szövegű mutató eltávolítva.';
$_lang['systemsetting_xtype_fix'] = 'Az xtypes sikeresen javítva a modSystemSettings-hez.';
$_lang['transportpackage_manifest_text'] = '`manifest` oszlop módosítva TEXT formátumról MEDIUMTEXT-re a(z) `[[+class]]` osztályban.';
$_lang['update_closure_table'] = 'A `[[+class]]` osztály zárlati táblájának frissítése.';
$_lang['update_table_column_data'] = 'Módosított adat a [[+table]] tábla [[+column]] oszlopában ( [[+class]] )';
$_lang['iso_country_code_converted'] = 'Elkészült a felhasználói adatlap országneveinek átalakítása ISO kódokká.';
$_lang['legacy_cleanup_complete'] = 'Korábbi állományok törlése kész.';
$_lang['legacy_cleanup_count'] = '[[+files]] állomány és [[+folders]] mappa eltávolítva.';
