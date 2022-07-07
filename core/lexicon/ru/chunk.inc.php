<?php
/**
 * Chunk English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */

// Entry out of alpha order because it must come before the entry it's used in below
$_lang['example_tag_chunk_name'] = 'НазваниеЧанка';

$_lang['chunk'] = 'Чанк';
$_lang['chunk_category_desc'] = 'Используйте для группировки чанков в дереве элементов.';
$_lang['chunk_code'] = 'Код чанка (HTML)';
$_lang['chunk_description_desc'] = 'Информация о чанке, используется в результатах поиска и в подсказках для дерева элементов.';
$_lang['chunk_delete_confirm'] = 'Вы уверены, что хотите удалить этот чанк?';
$_lang['chunk_duplicate_confirm'] = 'Вы уверены, что хотите сделать копию этого чанка?';
$_lang['chunk_err_create'] = 'Произошла ошибка при попытке создать чанк.';
$_lang['chunk_err_duplicate'] = 'Ошибка при дублировании чанка.';
$_lang['chunk_err_ae'] = 'Уже существует чанк с названием «[[+name]]».';
$_lang['chunk_err_invalid_name'] = 'Название чанка недопустимо.';
$_lang['chunk_err_locked'] = 'Чанк заблокирован.';
$_lang['chunk_err_remove'] = 'Произошла ошибка при попытке удалить чанк.';
$_lang['chunk_err_save'] = 'Произошла ошибка при сохранении чанка.';
$_lang['chunk_err_nf'] = 'Чанк не найден!';
$_lang['chunk_err_nfs'] = 'Не найден чанк с ID: [[+id]]';
$_lang['chunk_err_ns'] = 'Чанк не указан.';
$_lang['chunk_err_ns_name'] = 'Пожалуйста, укажите название.';
$_lang['chunk_lock'] = 'Заблокировать чанк для редактирования';
$_lang['chunk_lock_desc'] = 'Только пользователи с правами "edit_locked" могут редактировать этот чанк.';
$_lang['chunk_name_desc'] = 'Разместите содержимое, созданное этим чанком в ресурсе, шаблоне или другом чанке, используя следующий тег MODX: [[+tag]]';
$_lang['chunk_new'] = 'Создать чанк';
$_lang['chunk_properties'] = 'Параметры по умолчанию';
$_lang['chunk_tab_general_desc'] = 'Здесь вы можете задать основные параметры <em>чанка</em>, а также его содержимое. Содержимое должно быть HTML, либо помещенное в поле <em>Код чанка (HTML)</em> ниже, либо в статичном внешнем файле, и может содержать теги MODX. Обратите внимание, что PHP-код выполняться не будет.';
$_lang['chunk_tag_copied'] = 'Тег чанка скопирован!';
$_lang['chunks'] = 'Чанки';

// Temporarily match old keys to new ones to ensure compatibility
// --fields
$_lang['chunk_desc_category'] = $_lang['chunk_category_desc'];
$_lang['chunk_desc_description'] = $_lang['chunk_description_desc'];
$_lang['chunk_desc_name'] = $_lang['chunk_name_desc'];
$_lang['chunk_lock_msg'] = $_lang['chunk_lock_desc'];

// --tabs
$_lang['chunk_msg'] = $_lang['chunk_tab_general_desc'];
