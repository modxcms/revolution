<?php
/**
 * Template English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */

$_lang['access'] = 'Доступ';
$_lang['filter_by_category'] = 'Фильтр по категории...';
$_lang['rank'] = 'Сортировка';
$_lang['template'] = 'Шаблон';
$_lang['template_assignedtv_tab'] = 'Назначенные TV';
$_lang['template_category_desc'] = 'Используйте для группировки шаблонов в дереве элементов.';
$_lang['template_code'] = 'Код шаблона (HTML)';
$_lang['template_delete_confirm'] = 'Вы уверены, что хотите удалить этот шаблон?';
$_lang['template_description_desc'] = 'Информация о шаблоне, используется в результатах поиска и в подсказках для дерева элементов.';
$_lang['template_duplicate_confirm'] = 'Вы уверены, что хотите сделать копию этого шаблона?';
$_lang['template_edit_tab'] = 'Редактировать шаблон';
$_lang['template_empty'] = '(пустой шаблон)';
$_lang['template_err_default_template'] = 'Этот шаблон выбран в качестве шаблона по умолчанию. Пожалуйста, выберите другой шаблон по умолчанию в настройках MODX перед удалением этого шаблона.<br />';
$_lang['template_err_delete'] = 'Произошла ошибка при попытке удалить шаблон.';
$_lang['template_err_duplicate'] = 'Произошла ошибка при копировании шаблона.';
$_lang['template_err_ae'] = 'Шаблон с названием «[[+name]]» уже существует.';
$_lang['template_err_in_use'] = 'Этот шаблон используется. Пожалуйста, установите ресурсам, использующим этот шаблон, другой шаблон. Ресурсы, использующие этот шаблон:<br />';
$_lang['template_err_invalid_name'] = 'Название шаблона недопустимо.';
$_lang['template_err_locked'] = 'Шаблон заблокирован для редактирования.';
$_lang['template_err_nf'] = 'Шаблон не найден!';
$_lang['template_err_ns'] = 'Шаблон не указан.';
$_lang['template_err_ns_name'] = 'Пожалуйста, укажите название шаблона.';
$_lang['template_err_remove'] = 'Произошла ошибка при попытке удалить шаблон.';
$_lang['template_err_save'] = 'Произошла ошибка при сохранении шаблона.';
$_lang['template_icon'] = 'Класс иконки';
$_lang['template_icon_desc'] = 'CSS-класс (Font Awesome Free 5) иконки отображаемой в дереве для ресурсов, использующих этот шаблон, например, "fa-home" или "icon-home".';
$_lang['template_lock'] = 'Блокировка шаблона для редактирования';
$_lang['template_lock_desc'] = 'Только пользователи с правами "edit_locked" могут редактировать этот шаблон.';
$_lang['template_locked_message'] = 'Этот шаблон заблокирован.';
$_lang['template_management_msg'] = 'Здесь вы можете выбрать шаблон, который вы хотели бы редактировать.';
$_lang['template_name_desc'] = 'Название шаблона.';
$_lang['template_new'] = 'Создать шаблон';
$_lang['template_no_tv'] = 'Для этого шаблона ещё не назначены TV.';
$_lang['template_preview'] = 'Изображение превью';
$_lang['template_preview_desc'] = 'Используется для превью шаблона при создании ресурса (минимальный размер: 335x236).';
$_lang['template_preview_source'] = 'Источник файлов для превью';
$_lang['template_preview_source_desc'] = 'Устанавливает базовый путь выбранного источника файлов. Выберите «Не указано» для использования абсолютного пути.';
$_lang['template_properties'] = 'Параметры по умолчанию';
$_lang['template_reset_all'] = 'Сброс шаблона всех страниц, установить шаблон по умолчанию';
$_lang['template_reset_specific'] = 'Сбросить только \'%s\' страниц';
$_lang['template_tab_general_desc'] = 'Здесь вы можете задать основные параметры <em>шаблона</em>, а также его содержимое. Содержимое должно быть HTML, либо помещенное в поле <em>Код шаблона (HTML)</em> ниже, либо в статичном внешнем файле, и может содержать теги MODX. Обратите внимание, что изменения в шаблоне не будут отображаться в кэшированных страницах вашего сайта до тех пор, пока не будет очищен кэш.';
$_lang['template_tv_edit'] = 'Редактировать порядок TV';
$_lang['template_tv_msg'] = ' <abbr title="Дополнительные поля">TV</abbr>, назначенные этому шаблону.';
$_lang['templates'] = 'Шаблоны';
$_lang['tvt_err_nf'] = 'TV не имеет доступа к указанному шаблону.';
$_lang['tvt_err_remove'] = 'Произошла ошибка при попытке удалить TV из шаблона.';

// Temporarily match old keys to new ones to ensure compatibility
// --fields
$_lang['template_desc_category'] = $_lang['template_category_desc'];
$_lang['template_desc_description'] = $_lang['template_description_desc'];
$_lang['template_desc_name'] = $_lang['template_name_desc'];
$_lang['template_lock_msg'] = $_lang['template_lock_desc'];

// --tabs
$_lang['template_msg'] = $_lang['template_tab_general_desc'];
