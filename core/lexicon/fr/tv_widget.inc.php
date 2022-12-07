<?php
/**
 * TV Widget English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */
$_lang['attributes'] = 'Attributs';
$_lang['attr_attr_desc'] = 'One or more space-separated attributes to add to this element’s tag (for example, <span class="example-input">rel="external" type="application/pdf"</span>).';
$_lang['attr_class_desc'] = 'One or more space-separated CSS class names.';
$_lang['attr_style_desc'] = 'CSS definitions (for example, <span class="example-input">color:#f36f99; text-decoration:none;</span>).';
$_lang['attr_target_blank'] = 'Blank';
$_lang['attr_target_parent'] = 'Parent';
$_lang['attr_target_self'] = 'Self';
$_lang['attr_target_top'] = 'Top';
$_lang['attr_target_desc'] = 'Indicates in which window/tab or frame the linked url should open. To target a specific frame, enter its name in place of one of the provided options.';
$_lang['capitalize'] = 'Majuscules';
$_lang['checkbox'] = 'Case à cocher';
$_lang['checkbox_columns'] = 'Colonnes';
$_lang['checkbox_columns_desc'] = 'Le nombre de colonnes des cases à cocher';
$_lang['class'] = 'Classe';
$_lang['classes'] = 'Class(es)';
$_lang['combo_allowaddnewdata'] = 'Autorise l\'ajout de nouveaux éléments';
$_lang['combo_allowaddnewdata_desc'] = 'Autorise l\'ajout de nouveaux éléments n\'existant pas déjà dans la liste. Non par défaut.';
$_lang['combo_forceselection'] = 'Exiger la correspondance';
$_lang['combo_forceselection_desc'] = 'Only save typed option when it matches one already defined in the list.';
$_lang['combo_forceselection_multi_desc'] = 'Si cette valeur est définie à Oui, seuls les éléments déjà dans la liste sont autorisés. Si Non, de nouvelles valeurs peuvent être saisies aussi.';
$_lang['combo_listempty_text'] = 'Option Message introuvable';
$_lang['combo_listempty_text_desc'] = 'Message to display when typed text does not match existing options.';
$_lang['combo_listheight'] = 'Largeur de liste';
$_lang['combo_listheight_desc'] = 'La hauteur, en % ou en pixels, de la liste déroulante. Par défaut, hauteur de la combobox.';
$_lang['combo_listwidth'] = 'Largeur de liste';
$_lang['combo_listwidth_desc'] = 'La largeur, en % ou px, de la liste déroulante elle-même. Par défaut, la largeur de la combobox.';
$_lang['combo_maxheight'] = 'Hauteur maximale';
$_lang['combo_maxheight_desc'] = 'The maximum height in pixels of the dropdown list before scrollbars are shown. (Default: 300)';
$_lang['combo_stackitems'] = 'Empiler les éléments sélectionnés';
$_lang['combo_stackitems_desc'] = 'Les éléments sont empilés (1 par ligne). Non par défaut, ce qui affiche les éléments en ligne.';
$_lang['combo_title'] = 'Entête de liste';
$_lang['combo_title_desc'] = 'Un élément d\'entête contenant ce texte est créé et ajouté en haut de la liste.';
$_lang['combo_typeahead'] = 'Activer l\'auto-complétion';
$_lang['combo_typeahead_desc'] = 'Populate and autoselect options that match as you type after a configurable delay. (Default: No)';
$_lang['combo_typeahead_delay'] = 'Délai';
$_lang['combo_typeahead_delay_desc'] = 'Milliseconds before a matched option is shown. (Default: 250)';
$_lang['date'] = 'Date';
$_lang['date_format'] = 'Format de date';
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
$_lang['date_use_current'] = 'Utiliser la date actuelle comme valeur de secours';
$_lang['date_use_current_desc'] = 'When a value for this TV is not required (Allow Blank = “Yes”) and a Default Date is not specified, setting this option to “Yes” will display the current date.';
$_lang['default'] = 'Défaut';
$_lang['default_date_now'] = 'Aujourd\'hui avec l\'heure actuelle';
$_lang['default_date_today'] = 'Aujourd\'hui (minuit)';
$_lang['default_date_yesterday'] = 'Hier (minuit)';
$_lang['default_date_tomorrow'] = 'Demain (minuit)';
$_lang['default_date_custom'] = 'Personnalisé (voir description ci-dessous)';
$_lang['delim'] = 'Délimiteur';
$_lang['delimiter'] = 'Délimiteur';
$_lang['delimiter_desc'] = 'One or more characters used to separate values (applicable to TVs supporting multiple chooseable options).';
$_lang['disabled_dates'] = 'Dates désactivées';
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
$_lang['disabled_days'] = 'Jours désactivés';
$_lang['disabled_days_desc'] = '';
$_lang['dropdown'] = 'Menu en liste déroulante';
$_lang['earliest_date'] = 'Date de début';
$_lang['earliest_date_desc'] = 'Date à partir de laquelle on peut autoriser la sélection.';
$_lang['earliest_time'] = 'Heure de début';
$_lang['earliest_time_desc'] = 'Heure à partir de laquelle on peut autoriser la sélection.';
$_lang['email'] = 'E-mail';
$_lang['file'] = 'Enregistrer';
$_lang['height'] = 'Hauteur';
$_lang['hidden'] = 'Masqué';
$_lang['hide_time'] = 'Cacher l\'option de temps';
$_lang['hide_time_desc'] = 'Removes the ability to choose a time from this TV’s date picker.';
$_lang['htmlarea'] = 'Zone HTML';
$_lang['htmltag'] = 'Balise HTML';
$_lang['image'] = 'Image';
$_lang['image_alt'] = 'Texte alternatif';
$_lang['latest_date'] = 'Date de fin';
$_lang['latest_date_desc'] = 'Date jusqu\'à laquelle on peut autoriser la sélection.';
$_lang['latest_time'] = 'Heure de fin';
$_lang['latest_time_desc'] = 'Heure jusqu\'à laquelle on peut autoriser la sélection.';
$_lang['listbox'] = 'Liste à sélection unique';
$_lang['listbox-multiple'] = 'Liste à sélection multiple';
$_lang['lower_case'] = 'Minuscules';
$_lang['max_length'] = 'Longueur maximale';
$_lang['min_length'] = 'Longueur minimale';
$_lang['regex_text'] = 'Erreur sur l\'expression régulière';
$_lang['regex_text_desc'] = 'The message to show if the user enters text that is invalid according to the <abbr title="regular expression">regex</abbr> validator.';
$_lang['regex'] = 'Expression régulière (REGEX) de validation';
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
$_lang['name'] = 'Nom';
$_lang['number'] = 'Nombre';
$_lang['number_allowdecimals'] = 'Autoriser les nombres décimaux';
$_lang['number_allownegative'] = 'Allow Negative';
$_lang['number_decimalprecision'] = 'Précision';
$_lang['number_decimalprecision_desc'] = 'The maximum number of digits allowed after the decimal separator. (Default: 2)';
$_lang['number_decimalprecision_strict'] = 'Précision décimale stricte';
$_lang['number_decimalprecision_strict_desc'] = 'When set to “Yes,” preserves trailing zeros in decimal numbers (defaults to “No”).';
/* See note in number inputproperties config re separators */
$_lang['number_decimalseparator'] = 'Separator';
$_lang['number_decimalseparator_desc'] = 'The character used as the decimal separator. (Default: “.”)';
$_lang['number_maxvalue'] = 'Valeur maximale';
$_lang['number_minvalue'] = 'Valeur minimale';
$_lang['option'] = 'Boutons radio';
$_lang['parent_resources'] = 'Ressources parentes';
$_lang['radio_columns'] = 'Colonnes';
$_lang['radio_columns_desc'] = 'The number of columns the radio buttons are displayed in.';
$_lang['rawtext'] = 'Texte brut (obsolète)';
$_lang['rawtextarea'] = 'Zone de texte brut (obsolète)';
$_lang['required'] = 'Optionnel';
$_lang['required_desc'] = 'Select “No” to make this TV a required field in the Resources it’s assigned to. (Default: “Yes”)';
$_lang['resourcelist'] = 'Liste de ressources';
$_lang['resourcelist_depth'] = 'Profondeur';
$_lang['resourcelist_depth_desc'] = 'The number of subfolders to drill down into for this lising’s search query. (Default: 10)';
$_lang['resourcelist_forceselection_desc'] = 'Disabled; only list matches are valid.';
$_lang['resourcelist_includeparent'] = 'Inclure les parents';
$_lang['resourcelist_includeparent_desc'] = 'Select “Yes” to include the Resources specified in the Parents field in the list.';
$_lang['resourcelist_limitrelatedcontext'] = 'Limiter au contexte lié';
$_lang['resourcelist_limitrelatedcontext_desc'] = 'Select “Yes” to only include the Resources related to the context of the current Resource.';
$_lang['resourcelist_limit'] = 'Limite';
$_lang['resourcelist_limit_desc'] = 'The maximum number of Resources shown in this TV’s listing. (Default: 0, meaning unlimited)';
$_lang['resourcelist_listempty_text_desc'] = 'Disabled; selections will always match the list.';
$_lang['resourcelist_parents'] = 'Parents';
$_lang['resourcelist_parents_desc'] = 'If specified, this TV’s listing will include only the child resources from this comma-separated set of resource IDs (containers).';
$_lang['resourcelist_where'] = 'Conditions (Where)';
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
$_lang['richtext'] = 'Texte riche';
$_lang['sentence_case'] = 'Majuscule en début de phrase';
$_lang['start_day'] = 'Début de semaine';
$_lang['start_day_desc'] = 'Day displayed as the beginning of the week in this TV’s date picker. (Default: “Sunday”)';
$_lang['string'] = 'Chaîne de caractères';
$_lang['string_format'] = 'Format des caractères';
$_lang['style'] = 'Style';
$_lang['tag_name'] = 'Nom de la balise';
$_lang['target'] = 'Cible';
$_lang['text'] = 'Texte';
$_lang['textarea'] = 'Zone de texte';
$_lang['textareamini'] = 'Zone de texte (Mini)';
$_lang['textbox'] = 'Zone de texte';
$_lang['time_increment'] = 'Incrémentation d\'heure';
$_lang['time_increment_desc'] = 'The number of minutes between each time value in the list. (Default: 15)';
$_lang['title'] = 'Titre';
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
$_lang['upper_case'] = 'Majuscules';
$_lang['url'] = 'URL';
$_lang['url_display_text'] = 'Afficher le texte';
$_lang['width'] = 'Largeur';

// Temporarily match old keys to new ones to ensure compatibility
$_lang['tv_default_datetime'] = $_lang['tv_default_date'];

/*
    Refer to default.inc.php for the keys below.
    (Placement in this default file necessary to allow
    quick create/edit panels access to them when opened
    outside the context of their respective element types)

    tv_type
    tv_default
    tv_default_desc
    tv_elements

*/
