<?php
/**
 * Chunk English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */

// Entry out of alpha order because it must come before the entry it's used in below
$_lang['example_tag_chunk_name'] = 'НазваЧанка';

$_lang['chunk'] = 'Чанк';
$_lang['chunk_category_desc'] = 'Використовуйте для угрупування чанків в дереві елементів.';
$_lang['chunk_code'] = 'Код чанка (HTML)';
$_lang['chunk_description_desc'] = 'Інформація про чанк, використовується в результатах пошуку та у підказках для дерева елементів.';
$_lang['chunk_delete_confirm'] = 'Ви впевнені, що хочете видалити цей чанк?';
$_lang['chunk_duplicate_confirm'] = 'Ви впевнені, що хочете дублювати цей чанк?';
$_lang['chunk_err_create'] = 'Сталася помилка при спробі створення чанку.';
$_lang['chunk_err_duplicate'] = 'Помилка при дублюванні чанку.';
$_lang['chunk_err_ae'] = 'Вже існує чанк з іменем "[[+name]]".';
$_lang['chunk_err_invalid_name'] = 'Некоректне ім\'я чанку.';
$_lang['chunk_err_locked'] = 'Чанк заблоковано.';
$_lang['chunk_err_remove'] = 'Сталася помилка при спробі видалення чанку.';
$_lang['chunk_err_save'] = 'Сталася помилка при спробі збереження чанку.';
$_lang['chunk_err_nf'] = 'Чанк не знайдено!';
$_lang['chunk_err_nfs'] = 'Не знайдено чанк з id: [[+id]]';
$_lang['chunk_err_ns'] = 'Чанк не вказаний.';
$_lang['chunk_err_ns_name'] = 'Будь ласка, вкажіть ім\'я.';
$_lang['chunk_lock'] = 'Заблокувати чанк для редагування';
$_lang['chunk_lock_desc'] = 'Лише користувачі з правами "edit_locked" можуть редагувати цей чанк.';
$_lang['chunk_name_desc'] = 'Розмістіть контент, створений цим чанком у ресурсі, шаблоні або іншому чанці, використовуючи наступний тег MODX: [[+tag]]';
$_lang['chunk_new'] = 'Створити чанк';
$_lang['chunk_properties'] = 'Параметри за замовчуванням';
$_lang['chunk_tab_general_desc'] = 'Тут ви можете ввести основні атрибути для цього <em>чанка</em> а також його контента, вiн повинен бути HTML, поміщений в поле <em>Код чанка</em> нижче або в статичному зовнішньому файлі, і може включати теги MODX. Зверніть увагу, однак, що PHP код не буде виконуватися в цьому елементі.';
$_lang['chunk_tag_copied'] = 'Тег чанка скопійований!';
$_lang['chunks'] = 'Чанки';

// Temporarily match old keys to new ones to ensure compatibility
// --fields
$_lang['chunk_desc_category'] = $_lang['chunk_category_desc'];
$_lang['chunk_desc_description'] = $_lang['chunk_description_desc'];
$_lang['chunk_desc_name'] = $_lang['chunk_name_desc'];
$_lang['chunk_lock_msg'] = $_lang['chunk_lock_desc'];

// --tabs
$_lang['chunk_msg'] = $_lang['chunk_tab_general_desc'];
