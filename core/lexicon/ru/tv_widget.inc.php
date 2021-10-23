<?php
/**
 * TV Widget English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */
$_lang['attributes'] = 'Атрибуты';
$_lang['attr_attr_desc'] = 'One or more space-separated attributes to add to this element’s tag (for example, <span class="example-input">rel="external" type="application/pdf"</span>).';
$_lang['attr_class_desc'] = 'One or more space-separated CSS class names.';
$_lang['attr_style_desc'] = 'CSS definitions (for example, <span class="example-input">color:#f36f99; text-decoration:none;</span>).';
$_lang['attr_target_blank'] = 'Blank';
$_lang['attr_target_parent'] = 'Parent';
$_lang['attr_target_self'] = 'Self';
$_lang['attr_target_top'] = 'Top';
$_lang['attr_target_desc'] = 'Indicates in which window/tab or frame the linked url should open. To target a specific frame, enter its name in place of one of the provided options.';
$_lang['capitalize'] = 'Прописные';
$_lang['checkbox'] = 'Флажки (checkbox)';
$_lang['checkbox_columns'] = 'Столбцы';
$_lang['checkbox_columns_desc'] = 'Количество колонок, в которых будут отображаться флажки (checkbox).';
$_lang['class'] = 'Класс';
$_lang['classes'] = 'Class(es)';
$_lang['combo_allowaddnewdata'] = 'Разрешить добавлять новые элементы';
$_lang['combo_allowaddnewdata_desc'] = 'Если выбрано «Да», разрешает добавлять элементы, которые еще не в списке. По умолчанию «Нет».';
$_lang['combo_forceselection'] = 'Require Match';
$_lang['combo_forceselection_desc'] = 'Only save typed option when it matches one already defined in the list.';
$_lang['combo_forceselection_multi_desc'] = 'Если выбрано «Да», то будет разрешен выбор элементов только из списка. Если выбрано «Нет», то будет разрешен ввод пользовательских значений.';
$_lang['combo_listempty_text'] = 'Option Not Found Message';
$_lang['combo_listempty_text_desc'] = 'Message to display when typed text does not match existing options.';
$_lang['combo_listheight'] = 'Высота списка';
$_lang['combo_listheight_desc'] = 'Высота выпадающего списка, в % или px. По умолчанию высота как у поля с выпадающим списком.';
$_lang['combo_listwidth'] = 'Ширина списка';
$_lang['combo_listwidth_desc'] = 'Ширина выпадающего списка, в % или px. По умолчанию ширина как у поля с выпадающим списком.';
$_lang['combo_maxheight'] = 'Максимальная высота';
$_lang['combo_maxheight_desc'] = 'The maximum height in pixels of the dropdown list before scrollbars are shown. (Default: 300)';
$_lang['combo_stackitems'] = 'Выбранные значения в столбик';
$_lang['combo_stackitems_desc'] = 'Если выбрать «Да», выбранные значения будут выстраиваться вертикально. По умолчанию «Нет», при этом значения выстраиваются в одну строку.';
$_lang['combo_title'] = 'Заголовок списка';
$_lang['combo_title_desc'] = 'Если указано, в начале выпадающего списка будет создан заголовок, содержащий указанный текст.';
$_lang['combo_typeahead'] = 'Включить автозавершение';
$_lang['combo_typeahead_desc'] = 'Populate and autoselect options that match as you type after a configurable delay. (Default: No)';
$_lang['combo_typeahead_delay'] = 'Delay';
$_lang['combo_typeahead_delay_desc'] = 'Milliseconds before a matched option is shown. (Default: 250)';
$_lang['date'] = 'Дата';
$_lang['date_format'] = 'Формат даты';
$_lang['date_format_desc'] = 'Enter a format using <a href="https://www.php.net/strftime" target="_blank">php’s strftime syntax</a>.
    <div class="example-list">Common examples include:
        <ul>
            <li><span class="example-input">[[+example_1a]]</span> ([[+example_1b]]) (default format)</li>
            <li><span class="example-input">[[+example_2a]]</span> ([[+example_2b]])</li>
            <li><span class="example-input">[[+example_3a]]</span> ([[+example_3b]])</li>
            <li><span class="example-input">[[+example_4a]]</span> ([[+example_4b]])</li>
            <li><span class="example-input">[[+example_5a]]</span> ([[+example_5b]])</li>
            <li><span class="example-input">[[+example_6a]]</span> ([[+example_6b]])</li>
            <li><span class="example-input">[[+example_7a]]</span> ([[+example_7b]])</li>
        </ul>
    </div>
';
$_lang['date_use_current'] = 'Use Current Date as Fallback';
$_lang['date_use_current_desc'] = 'When a value for this TV is not required (Allow Blank = “Yes”) and a Default Date is not specified, setting this option to “Yes” will display the current date.';
$_lang['default'] = 'По умолчанию';
$_lang['default_date_now'] = 'Today with Current Time';
$_lang['default_date_today'] = 'Today (midnight)';
$_lang['default_date_yesterday'] = 'Yesterday (midnight)';
$_lang['default_date_tomorrow'] = 'Tomorrow (midnight)';
$_lang['default_date_custom'] = 'Custom (see description below)';
$_lang['delim'] = 'Разделитель';
$_lang['delimiter'] = 'Разделитель';
$_lang['delimiter_desc'] = 'One or more characters used to separate values (applicable to TVs supporting multiple chooseable options).';
$_lang['disabled_dates'] = 'Неактивные даты';
$_lang['disabled_dates_desc'] = 'A comma-separated, javascript <abbr title="regular expression">regex</abbr>-compatible list (minus delimiters) of dates in the manager’s date format (currently “[[+format_current]]”).
    <p>Examples using the default format (“[[+format_default]]”) include:</p>
    <div class="example-list">
        <ul>
            <li><span class="example-input">[[+example_1]]</span> (selects individual dates)</li>
            <li><span class="example-input">[[+example_2a]]</span> (selects [[+example_2b]] and [[+example_2c]] of every year)</li>
            <li><span class="example-input">[[+example_3a]]</span> (“^” to match beginning of string; this selects all of [[+example_3b]])</li>
            <li><span class="example-input">[[+example_4a]]</span> (selects every day in [[+example_4b]])</li>
            <li><span class="example-input">[[+example_5]]</span> (“$” to match end of string; this selects every day in March of every year)</li>
        </ul>
        Note: If your date format uses dot separators they will need to be escaped (e.g., “[[+example_6a]]” should be entered above as “[[+example_6b]]”).
    </div>
';
$_lang['disabled_days'] = 'Неактивные дни';
$_lang['disabled_days_desc'] = '';
$_lang['dropdown'] = 'Выпадающий список';
$_lang['earliest_date'] = 'Ранняя дата';
$_lang['earliest_date_desc'] = 'Самая ранняя дата, которую можно выбрать.';
$_lang['earliest_time'] = 'Раннее время';
$_lang['earliest_time_desc'] = 'Самое раннее время, которое можно выбрать.';
$_lang['email'] = 'Электронная почта';
$_lang['file'] = 'Файл';
$_lang['height'] = 'Высота';
$_lang['hidden'] = 'Скрытый';
$_lang['hide_time'] = 'Hide Time Option';
$_lang['hide_time_desc'] = 'Removes the ability to choose a time from this TV’s date picker.';
$_lang['htmlarea'] = 'HTML-поле';
$_lang['htmltag'] = 'HTML-тег';
$_lang['image'] = 'Изображение';
$_lang['image_alt'] = 'Альтернативный текст';
$_lang['latest_date'] = 'Поздняя дата';
$_lang['latest_date_desc'] = 'Самая поздняя дата, которую можно выбрать.';
$_lang['latest_time'] = 'Позднее время';
$_lang['latest_time_desc'] = 'Самое позднее время, которое можно выбрать.';
$_lang['listbox'] = 'Список (одиночный выбор)';
$_lang['listbox-multiple'] = 'Список (множественный выбор)';
$_lang['lower_case'] = 'Нижний регистр';
$_lang['max_length'] = 'Максимальная длина';
$_lang['min_length'] = 'Минимальная длина';
$_lang['regex_text'] = 'Регулярное выражение содержит ошибку';
$_lang['regex_text_desc'] = 'The message to show if the user enters text that is invalid according to the <abbr title="regular expression">regex</abbr> validator.';
$_lang['regex'] = 'Проверка регулярного выражения';
$_lang['regex_desc'] = 'A javascript <abbr title="regular expression">regex</abbr>-compatible string (minus delimiters) to restrict the content of this TV. Some examples:
    <div class="example-list">
        <ul>
            <li><span class="example-input">[[+example_1]]</span> (pattern for U.S. zip codes)</li>
            <li><span class="example-input">[[+example_2]]</span> (allow only letters)</li>
            <li><span class="example-input">[[+example_3]]</span> (allow all characters except numbers)</li>
            <li><span class="example-input">[[+example_4]]</span> (must end with the string “-XP”)</li>
        </ul>
    </div>
';
$_lang['name'] = 'Название';
$_lang['number'] = 'Число';
$_lang['number_allowdecimals'] = 'Разрешить десятичные';
$_lang['number_allownegative'] = 'Allow Negative';
$_lang['number_decimalprecision'] = 'Precision';
$_lang['number_decimalprecision_desc'] = 'The maximum number of digits allowed after the decimal separator. (Default: 2)';
/* See note in number inputproperties config re separators */
$_lang['number_decimalseparator'] = 'Separator';
$_lang['number_decimalseparator_desc'] = 'The character used as the decimal separator. (Default: “.”)';
$_lang['number_maxvalue'] = 'Максимальное значение';
$_lang['number_minvalue'] = 'Минимальное значение';
$_lang['option'] = 'Переключатели (radio)';
$_lang['parent_resources'] = 'Родительские ресурсы';
$_lang['radio_columns'] = 'Столбцы';
$_lang['radio_columns_desc'] = 'The number of columns the radio buttons are displayed in.';
$_lang['rawtext'] = 'Сырой текст (устарело)';
$_lang['rawtextarea'] = 'Область сырого текста (устарело)';
$_lang['required'] = 'Может быть незаполненным';
$_lang['required_desc'] = 'Select “No” to make this TV a required field in the Resources it’s assigned to. (Default: “Yes”)';
$_lang['resourcelist'] = 'Список ресурсов';
$_lang['resourcelist_depth'] = 'Глубина';
$_lang['resourcelist_depth_desc'] = 'The number of subfolders to drill down into for this lising’s search query. (Default: 10)';
$_lang['resourcelist_forceselection_desc'] = 'Disabled; only list matches are valid.';
$_lang['resourcelist_includeparent'] = 'Включать родителей';
$_lang['resourcelist_includeparent_desc'] = 'Select “Yes” to include the Resources specified in the Parents field in the list.';
$_lang['resourcelist_limitrelatedcontext'] = 'Ограничить текущим контекстом';
$_lang['resourcelist_limitrelatedcontext_desc'] = 'Select “Yes” to only include the Resources related to the context of the current Resource.';
$_lang['resourcelist_limit'] = 'Лимит';
$_lang['resourcelist_limit_desc'] = 'The maximum number of Resources shown in this TV’s listing. (Default: 0, meaning unlimited)';
$_lang['resourcelist_listempty_text_desc'] = 'Disabled; selections will always match the list.';
$_lang['resourcelist_parents'] = 'Родители';
$_lang['resourcelist_parents_desc'] = 'If specified, this TV’s listing will include only the child resources from this comma-separated set of resource IDs (containers).';
$_lang['resourcelist_where'] = 'Where условие';
$_lang['resourcelist_where_desc'] = '
    <p>A JSON object of one or more Resource fields to filter this TV’s listing of Resources.</p>
    <div class="example-list">Some examples:
        <ul>
            <li><span class="example-input">[[+example_1]]</span> (only include Resources with template 4 applied)</li>
            <li><span class="example-input">[[+example_2]]</span> (include all Resources, except for those named “Home”)</li>
            <li><span class="example-input">[[+example_3]]</span> (include only Resources whose Resource Type is Weblink or Symlink)</li>
            <li><span class="example-input">[[+example_4]]</span> (include only Resources that are published and are not containers)</li>
        </ul>
    </div>
    <p>Note: Filtering by TV values is not supported.</p>
';
$_lang['richtext'] = 'Текстовый редактор';
$_lang['sentence_case'] = 'Чувствительный к регистру';
$_lang['start_day'] = 'Первый день недели';
$_lang['start_day_desc'] = 'Day displayed as the beginning of the week in this TV’s date picker. (Default: “Sunday”)';
$_lang['string'] = 'Строка';
$_lang['string_format'] = 'Строковый формат';
$_lang['style'] = 'Стиль';
$_lang['tag_name'] = 'Название тега';
$_lang['target'] = 'Цель';
$_lang['text'] = 'Текст';
$_lang['textarea'] = 'Текстовая область';
$_lang['textareamini'] = 'Текстовая область (мини)';
$_lang['textbox'] = 'Текстовый блок';
$_lang['time_increment'] = 'Приращение времени';
$_lang['time_increment_desc'] = 'The number of minutes between each time value in the list. (Default: 15)';
$_lang['title'] = 'Заголовок';
$_lang['tv_default'] = 'Default Value';
$_lang['tv_default_desc'] = 'The content this TV will show if user-entered content is not provided.';
$_lang['tv_default_checkbox_desc'] = 'A double-pipe-separated set of option(s) selected for this TV if the user does not check one or more. If your options include labels (e.g., Option One==1||Option Two==2||Option Three==3), be sure to enter the value (i.e., “1” for Option One, or “1||3” for Option One and Option Three)';
$_lang['tv_default_date'] = 'Default Date and Time';
$_lang['tv_default_date_desc'] = 'The date to show if the user does not provide one. Choose a relative date from the list above or enter a different date using one of the following patterns:
    <div class="example-list">
        <ul>
            <li><span class="example-input">[[+example_1]]</span> (number respresents hours ago)</li>
            <li><span class="example-input">[[+example_2]]</span> (number represents hours in the future)</li>
            <li><span class="example-input">[[+example_3]]</span> (a specific date [and time if desired] using the format shown)</li>
        </ul>
        Note: The use of the “+” and “-” shown above is counter-intuitive, but correct (“+” represents backward in time).
    </div>';
$_lang['tv_default_email'] = 'Default Email Address';
$_lang['tv_default_email_desc'] = 'The email address this TV will show if the user does not provide one.';
$_lang['tv_default_file'] = 'Default File';
$_lang['tv_default_file_desc'] = 'The file path this TV will show if the user does not provide one.';
$_lang['tv_default_image'] = 'Default Image';
$_lang['tv_default_image_desc'] = 'The image path this TV will show if the user does not provide one.';
$_lang['tv_default_option'] = 'Default Option';
$_lang['tv_default_option_desc'] = 'The option selected for this TV if the user does not choose one. If your options include labels (e.g., Option One==1||Option Two==2||Option Three==3), be sure to enter the value (i.e., “1” for Option One)';
$_lang['tv_default_options'] = 'Default Option(s)';
$_lang['tv_default_options_desc'] = 'A double-pipe-separated set of option(s) selected for this TV if the user does not choose one or more. If your options include labels (e.g., Option One==1||Option Two==2||Option Three==3), be sure to enter the value (i.e., “1” for Option One, or “1||3” for Option One and Option Three)';
$_lang['tv_default_radio_desc'] = $_lang['tv_default_option_desc'];
$_lang['tv_default_listbox_desc'] = $_lang['tv_default_option_desc'];
$_lang['tv_default_listbox-multiple_desc'] = $_lang['tv_default_options_desc'];
$_lang['tv_default_number'] = 'Default Number';
$_lang['tv_default_number_desc'] = 'The number this TV will show if the user does not provide one.';
$_lang['tv_default_resource'] = 'Default Resource (ID)';
$_lang['tv_default_resourcelist_desc'] = 'The resource this TV will show if the user does not choose one.';
$_lang['tv_default_tag'] = 'Default Tag(s)';
$_lang['tv_default_tag_desc'] = 'A comma-separated set of option(s) selected for this TV if the user does not choose one or more. If your options include labels (e.g., Tag One==1||Tag Two==2||Tag Three==3), be sure to enter the value (i.e., “1” for Tag One, or “1,3” for Tag One and Tag Three)';
$_lang['tv_default_text'] = 'Default Text';
$_lang['tv_default_text_desc'] = 'The text content this TV will show if the user does not provide it.';
$_lang['tv_default_url'] = 'Default URL';
$_lang['tv_default_url_desc'] = 'The URL this TV will show if the user does not provide one.';
$_lang['tv_elements'] = 'Input Option Values';
$_lang['tv_elements_checkbox'] = 'Checkbox Options';
$_lang['tv_elements_listbox'] = 'Dropdown List Options';
$_lang['tv_elements_radio'] = 'Radio Button Options';
$_lang['tv_elements_tag'] = 'Tag Options';
$_lang['tv_elements_desc'] = 'Defines the selectable options for this TV, which may be manually entered or built with a one-line <a href="https://docs.modx.com/current/en/building-sites/elements/template-variables/bindings/select-binding" target="_blank">database query</a>. Some examples:
    <div class="example-list">
        <ul>
            <li><span class="example-input">Bird||Cat||Dog</span> (shorthand for Bird==Bird||Cat==Cat||Dog==Dog)</li>
            <li><span class="example-input">White==#ffffff||Black==#000000</span> (where label==value)</li>
            <li><span class="example-input">[[+example_1]]</span> (builds a list of published Resources whose assigned template id is 1)</li>
            <li><span class="example-input">[[+example_2]]</span> (builds the same list as the previous example, including a blank option)</li>
        </ul>
    </div>
    ';
$_lang['tv_elements_checkbox_desc'] = $_lang['tv_elements_desc'];
$_lang['tv_elements_listbox_desc'] = $_lang['tv_elements_desc'];
$_lang['tv_elements_listbox-multiple_desc'] = $_lang['tv_elements_listbox_desc'];
$_lang['tv_elements_radio_desc'] = $_lang['tv_elements_option_desc'] = $_lang['tv_elements_desc'];
$_lang['tv_elements_tag_desc'] = $_lang['tv_elements_desc'];
$_lang['tv_daterange_elements_desc'] = 'Test options desc for daterange with example ph: [[+ex1]]';
$_lang['tv_daterange_default_text_desc'] = 'Test default text desc for daterange with example ph: [[+ex1]]';
$_lang['tv_type'] = 'Input Type';
$_lang['upper_case'] = 'Верхний регистр';
$_lang['url'] = 'URL';
$_lang['url_display_text'] = 'Показать текст';
$_lang['width'] = 'Ширина';

// Temporarily match old keys to new ones to ensure compatibility
$_lang['tv_default_datetime'] = $_lang['tv_default_date'];
