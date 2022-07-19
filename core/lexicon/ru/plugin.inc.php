<?php
/**
 * Plugin English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */
$_lang['event'] = 'Событие';
$_lang['events'] = 'События';
$_lang['plugin'] = 'Плагин';
$_lang['plugin_add'] = 'Добавить плагин';
$_lang['plugin_category_desc'] = 'Используйте для группировки плагинов в дереве элементов.';
$_lang['plugin_code'] = 'Код плагина (PHP)';
$_lang['plugin_config'] = 'Конфигурация плагина';
$_lang['plugin_description_desc'] = 'Информация о плагине, используется в результатах поиска и в подсказках для дерева элементов.';
$_lang['plugin_delete_confirm'] = 'Вы уверены, что хотите удалить плагин?';
$_lang['plugin_disabled'] = 'Отключить плагин';
$_lang['plugin_disabled_msg'] = 'Когда отключен, этот плагин не будет реагировать на события.';
$_lang['plugin_duplicate_confirm'] = 'Вы уверены, что хотите сделать копию этого плагина?';
$_lang['plugin_err_create'] = 'При создании плагина произошла ошибка.';
$_lang['plugin_err_ae'] = 'Плагин с названием «[[+name]]» уже существует.';
$_lang['plugin_err_invalid_name'] = 'Название плагина недопустимо.';
$_lang['plugin_err_duplicate'] = 'Произошла ошибка при попытке скопировать плагин.';
$_lang['plugin_err_nf'] = 'Плагин не найден!';
$_lang['plugin_err_ns'] = 'Плагин не указан.';
$_lang['plugin_err_ns_name'] = 'Пожалуйста, укажите название плагина.';
$_lang['plugin_err_remove'] = 'Произошла ошибка при попытке удалить плагин.';
$_lang['plugin_err_save'] = 'При сохранении плагина произошла ошибка.';
$_lang['plugin_event_err_duplicate'] = 'При попытке скопировать системные события плагина произошла ошибка';
$_lang['plugin_event_err_nf'] = 'Системные события плагина не найдены.';
$_lang['plugin_event_err_ns'] = 'Системные события плагина не указаны.';
$_lang['plugin_event_err_remove'] = 'Произошла ошибка при попытке удалить событие плагина.';
$_lang['plugin_event_err_save'] = 'При сохранении системного события плагина произошла ошибка.';
$_lang['plugin_event_msg'] = 'Выберите события, которые должен отслеживать плагин.';
$_lang['plugin_event_plugin_remove_confirm'] = 'Вы уверены, что хотите удалить этот плагин из этого события?';
$_lang['plugin_lock'] = 'Плагин заблокирован для редактирования';
$_lang['plugin_lock_desc'] = 'Только пользователи с правами "edit_locked" могут редактировать этот плагин.';
$_lang['plugin_locked_message'] = 'Доступ к этому плагину закрыт.';
$_lang['plugin_management_msg'] = 'Здесь вы можете выбрать плагин для редактирования.';
$_lang['plugin_name_desc'] = 'Название плагина.';
$_lang['plugin_new'] = 'Создать плагин';
$_lang['plugin_priority'] = 'Изменить порядок вызова плагинов при наступлении системного события';
$_lang['plugin_properties'] = 'Свойства плагина';
$_lang['plugin_tab_general_desc'] = 'Здесь вы можете задать основные параметры <em>плагина</em>, а также его содержимое. Содержимое должно быть PHP, либо помещенное в поле <em>Код плагина (PHP)</em> ниже, либо в статичном внешнем файле. Введенный PHP-код выполняется в ответ на одно или несколько системных событий MODX.';
$_lang['plugins'] = 'Плагины';

// Temporarily match old keys to new ones to ensure compatibility
// --fields
$_lang['plugin_desc_category'] = $_lang['plugin_category_desc'];
$_lang['plugin_desc_description'] = $_lang['plugin_description_desc'];
$_lang['plugin_desc_name'] = $_lang['plugin_name_desc'];
$_lang['plugin_lock_msg'] = $_lang['plugin_lock_desc'];

// --tabs
$_lang['plugin_msg'] = $_lang['plugin_tab_general_desc'];
