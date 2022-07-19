<?php
/**
 * Snippet English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */
$_lang['example_tag_snippet_name'] = 'НазваниеСниппета';
$_lang['snippet'] = 'Сниппет';
$_lang['snippets_available'] = 'Сниппеты доступные вам для включения в вашу страницу';
$_lang['snippet_category_desc'] = 'Используйте для группировки сниппетов в дереве элементов.';
$_lang['snippet_code'] = 'Код сниппета (PHP)';
$_lang['snippet_delete_confirm'] = 'Вы уверены, что хотите удалить этот сниппет?';
$_lang['snippet_description_desc'] = 'Информация о сниппете, используется в результатах поиска и в подсказках для дерева элементов.';
$_lang['snippet_duplicate_confirm'] = 'Вы уверены, что хотите сделать копию этого сниппета?';
$_lang['snippet_duplicate_error'] = 'Произошла ошибка в процессе копирования сниппета.';
$_lang['snippet_err_create'] = 'Произошла ошибка в процессе создания сниппета.';
$_lang['snippet_err_delete'] = 'Произошла ошибка при попытке удалить сниппет.';
$_lang['snippet_err_duplicate'] = 'Произошла ошибка при попытке копировать сниппет.';
$_lang['snippet_err_ae'] = 'Сниппет с названием «[[+name]]» уже существует.';
$_lang['snippet_err_invalid_name'] = 'Название сниппета недопустимо.';
$_lang['snippet_err_locked'] = 'Этот сниппет заблокирован для редактирования.';
$_lang['snippet_err_nf'] = 'Сниппет не найден!';
$_lang['snippet_err_ns'] = 'Сниппет не указан.';
$_lang['snippet_err_ns_name'] = 'Пожалуйста, укажите название сниппета.';
$_lang['snippet_err_remove'] = 'Произошла ошибка при попытке удалить сниппет.';
$_lang['snippet_err_save'] = 'Произошла ошибка в процессе сохранения сниппета.';
$_lang['snippet_execonsave'] = 'Запустить сниппет после сохранения.';
$_lang['snippet_lock'] = 'Заблокировать сниппет для редактирования';
$_lang['snippet_lock_desc'] = 'Только пользователи с правами "edit_locked" могут редактировать этот сниппет.';
$_lang['snippet_management_msg'] = 'Здесь вы можете выбрать сниппет для редактирования.';
$_lang['snippet_name_desc'] = 'Разместить содержимое, созданное этим сниппетом в ресурсе, шаблоне или чанке, используя следующий тег MODX: [[+tag]]';
$_lang['snippet_new'] = 'Создать сниппет';
$_lang['snippet_properties'] = 'Параметры по умолчанию';
$_lang['snippet_tab_general_desc'] = 'Здесь вы можете задать основные параметры <em>сниппета</em>, а также его содержимое. Содержимое должно быть PHP, либо помещенное в поле <em>Код сниппета (PHP)</em> ниже, либо в статичном внешнем файле. Чтобы получать результат работы сниппета в его вызове (в шаблоне или чанке), результат должен возвращаться в коде.';
$_lang['snippet_tag_copied'] = 'Тег сниппета скопирован!';
$_lang['snippets'] = 'Сниппеты';

// Temporarily match old keys to new ones to ensure compatibility
// --fields
$_lang['snippet_desc_category'] = $_lang['snippet_category_desc'];
$_lang['snippet_desc_description'] = $_lang['snippet_description_desc'];
$_lang['snippet_desc_name'] = $_lang['snippet_name_desc'];
$_lang['snippet_lock_msg'] = $_lang['snippet_lock_desc'];

// --tabs
$_lang['snippet_msg'] = $_lang['snippet_tab_general_desc'];
