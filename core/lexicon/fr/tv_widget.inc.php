<?php
/**
 * TV Widget English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */
$_lang['attributes'] = 'Attributs';
$_lang['attr_attr_desc'] = 'Un ou plusieurs attributs séparés par des espaces à ajouter à la balise de cet élément (par exemple, <span class="example-input">rel="external" type="application/pdf"</span>).';
$_lang['attr_class_desc'] = 'Un ou plusieurs noms de classes CSS séparés par des espaces.';
$_lang['attr_style_desc'] = 'Styles CSS en ligne (par exemple, <span class="example-input">color:#f36f99; text-decoration:none;</span>).';
$_lang['attr_target_blank'] = 'Blank';
$_lang['attr_target_parent'] = 'Parent';
$_lang['attr_target_self'] = 'Self';
$_lang['attr_target_top'] = 'Top';
$_lang['attr_target_desc'] = 'Indique dans quelle fenêtre/onglet ou cadre l\'URL liée doit s\'ouvrir. Pour cibler un cadre spécifique, entrez son nom à la place de l\'une des options fournies.';
$_lang['capitalize'] = 'Majuscules';
$_lang['checkbox'] = 'Case à cocher';
$_lang['checkbox_columns'] = 'Colonnes';
$_lang['checkbox_columns_desc'] = 'Le nombre de colonnes des cases à cocher';
$_lang['class'] = 'Classe';
$_lang['classes'] = 'Class(es)';
$_lang['combo_allowaddnewdata'] = 'Autorise l\'ajout de nouveaux éléments';
$_lang['combo_allowaddnewdata_desc'] = 'Autorise l\'ajout de nouveaux éléments n\'existant pas déjà dans la liste. Non par défaut.';
$_lang['combo_forceselection'] = 'Exiger une correspondance';
$_lang['combo_forceselection_desc'] = 'Enregistrer l\'option saisie uniquement si elle correspond à une option déjà définie dans la liste.';
$_lang['combo_forceselection_multi_desc'] = 'Si cette valeur est définie à Oui, seuls les éléments déjà dans la liste sont autorisés. Si Non, de nouvelles valeurs peuvent être saisies aussi.';
$_lang['combo_listempty_text'] = 'Message "Option non trouvée"';
$_lang['combo_listempty_text_desc'] = 'Message à afficher lorsque le texte saisi ne correspond pas aux options existantes.';
$_lang['combo_listheight'] = 'Largeur de liste';
$_lang['combo_listheight_desc'] = 'La hauteur, en % ou en pixels, de la liste déroulante. Par défaut, hauteur de la combobox.';
$_lang['combo_listwidth'] = 'Largeur de liste';
$_lang['combo_listwidth_desc'] = 'La largeur, en % ou px, de la liste déroulante elle-même. Par défaut, la largeur de la combobox.';
$_lang['combo_maxheight'] = 'Hauteur maximale';
$_lang['combo_maxheight_desc'] = 'La hauteur maximale en pixels de la liste déroulante avant l\'affichage des barres de défilement. (défaut: 300)';
$_lang['combo_stackitems'] = 'Empiler les éléments sélectionnés';
$_lang['combo_stackitems_desc'] = 'Les éléments sont empilés (1 par ligne). Non par défaut, ce qui affiche les éléments en ligne.';
$_lang['combo_title'] = 'Entête de liste';
$_lang['combo_title_desc'] = 'Un élément d\'entête contenant ce texte est créé et ajouté en haut de la liste.';
$_lang['combo_typeahead'] = 'Activer l\'auto-complétion';
$_lang['combo_typeahead_desc'] = 'Remplir et sélectionner automatiquement les options qui correspondent à votre saisie après un délai configurable. (défaut: No)';
$_lang['combo_typeahead_delay'] = 'Delais';
$_lang['combo_typeahead_delay_desc'] = 'Millisecondes avant l\'affichage d\'une option correspondante. (défaut: 250)';
$_lang['date'] = 'Date';
$_lang['date_format'] = 'Format de date';
$_lang['date_format_desc'] = 'Entrez un format selon la <a href="https://www.php.net/strftime" target="_blank">syntaxe strftime de php</a>.
    <div class="example-list">Quelques exemples courants :
        <ul>
            <li><span class="example-input">[[+example_1a]]</span> ([[+example_1b]]) (format par défaut)</li>
            <li><span class="example-input">[[+example_2a]]</span> ([[+example_2b]])</li>
            <li><span class="example-input">[[+example_3a]]</span> ([[+example_3b]])</li>
            <li><span class="example-input">[[+example_4a]]</span> ([[+example_4b]])</li>
            <li><span class="example-input">[[+example_5a]]</span> ([[+example_5b]])</li>
            <li><span class="example-input">[[+example_6a]]</span> ([[+example_6b]])</li>
            <li><span class="example-input">[[+example_7a]]</span> ([[+example_7b]])</li>
        </ul>
    </div>
';
$_lang['date_use_current'] = 'Utiliser la date du jour comme solution de repli';
$_lang['date_use_current_desc'] = 'Lorsqu\'une valeur n\'est pas requise pour cette TV (Authoriser Blank = "Yes") et qu\'une date par défaut n\'est pas spécifiée, si cette option est réglée sur "Yes", la date actuelle sera utilisée.';
$_lang['default'] = 'Défaut';
$_lang['default_date_now'] = 'Aujourd\'hui avec l\'heure actuelle';
$_lang['default_date_today'] = 'Aujourd\'hui (minuit)';
$_lang['default_date_yesterday'] = 'Hier (minuit)';
$_lang['default_date_tomorrow'] = 'Demain (minuit)';
$_lang['default_date_custom'] = 'Personnalisée (voir la description ci-dessous)';
$_lang['delim'] = 'Délimiteur';
$_lang['delimiter'] = 'Délimiteur';
$_lang['delimiter_desc'] = 'Un ou plusieurs caractères utilisés pour séparer les valeurs (applicable aux TVs prenant en charge plusieurs options à choisir).';
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
$_lang['hide_time'] = 'Option de masquage de l\'heure';
$_lang['hide_time_desc'] = 'Supprime la possibilité de choisir une heure dans le sélecteur de date de la TV.';
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
$_lang['regex_text_desc'] = 'Le message à afficher si l\'utilisateur saisit un texte qui n\'est pas valide selon la norme des <abbr title="expressions régulières">regex</abbr>.';
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
$_lang['number_allownegative'] = 'Autoriser les nombres négatifs';
$_lang['number_decimalprecision'] = 'Précision';
$_lang['number_decimalprecision_desc'] = 'Le nombre maximum de chiffres autorisés après le séparateur décimal. (défaut: 2)';
$_lang['number_decimalprecision_strict'] = 'Précision décimale stricte';
$_lang['number_decimalprecision_strict_desc'] = 'Lorsqu\'il est réglé sur "Oui", conservation des zéros de fin dans les nombres décimaux. (défaut: “Non”).';
/* See note in number inputproperties config re separators */
$_lang['number_decimalseparator'] = 'Séparateur';
$_lang['number_decimalseparator_desc'] = 'Le caractère utilisé comme séparateur décimal. (Default: “.”)';
$_lang['number_maxvalue'] = 'Valeur maximale';
$_lang['number_minvalue'] = 'Valeur minimale';
$_lang['option'] = 'Boutons radio';
$_lang['parent_resources'] = 'Ressources parentes';
$_lang['radio_columns'] = 'Colonnes';
$_lang['radio_columns_desc'] = 'Le nombre de colonnes dans lesquelles les boutons radio sont affichés..';
$_lang['rawtext'] = 'Texte brut (obsolète)';
$_lang['rawtextarea'] = 'Zone de texte brut (obsolète)';
$_lang['required'] = 'Optionnel';
$_lang['required_desc'] = 'Sélectionnez "Non" pour que cette TV soit un champ obligatoire dans les ressources auxquelles elle est affectée. (défaut: Oui)';
$_lang['resourcelist'] = 'Liste de ressources';
$_lang['resourcelist_depth'] = 'Profondeur';
$_lang['resourcelist_depth_desc'] = 'Le nombre de sous-dossiers à explorer pour la requête de recherche de ce lising. (défaut: 10)';
$_lang['resourcelist_forceselection_desc'] = 'Désactivé ; seules les correspondances de liste sont valides.';
$_lang['resourcelist_includeparent'] = 'Inclure les parents';
$_lang['resourcelist_includeparent_desc'] = 'Sélectionnez "Oui" pour inclure dans la liste les ressources spécifiées dans le champ "Parents".';
$_lang['resourcelist_limitrelatedcontext'] = 'Limiter au contexte lié';
$_lang['resourcelist_limitrelatedcontext_desc'] = 'Sélectionnez "Oui" pour inclure uniquement les ressources liées au contexte de la ressource actuelle.';
$_lang['resourcelist_limit'] = 'Limite';
$_lang['resourcelist_limit_desc'] = 'Le nombre maximum de ressources affichées dans la liste de cette TV. (défaut : 0, ce qui signifie illimité)';
$_lang['resourcelist_listempty_text_desc'] = 'Désactivé ; les sélections correspondront toujours à la liste.';
$_lang['resourcelist_parents'] = 'Parents';
$_lang['resourcelist_parents_desc'] = 'Si elle est spécifiée, la liste de cette TV inclura uniquement les ressources enfants de cet ensemble d\'ID de ressources (conteneurs) séparés par des virgules.';
$_lang['resourcelist_where'] = 'Conditions (où)';
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
$_lang['start_day_desc'] = 'Jour affiché en début de semaine dans le sélecteur de date de cette TV. (défaut : "Dimanche")';
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
$_lang['time_increment_desc'] = 'Le nombre de minutes entre chaque valeur de temps dans la liste. (défaut: 15)';
$_lang['title'] = 'Titre';
$_lang['tv_default_checkbox_desc'] = 'A double-pipe-separated set of option(s) selected for this TV if the user does not check one or more. If your options include labels (e.g., Option One==1||Option Two==2||Option Three==3), be sure to enter the value (i.e., “1” for Option One, or “1||3” for Option One and Option Three)';
$_lang['tv_default_date'] = 'Date et heure par défaut';
$_lang['tv_default_date_desc'] = 'La date à afficher si l\'utilisateur n\'en fournit pas. Choisissez une date relative dans la liste ci-dessus ou saisissez une date différente en utilisant l\'un des modèles suivants:
    <div class="example-list">
        <ul>
            <li><span class="example-input">[[+example_1]]</span> (le nombre représente les heures passées)</li>
            <li><span class="example-input">[[+example_2]]</span> (le nombre représente les heures dans le futur)</li>
            <li><span class="example-input">[[+example_3]]</span> (une date spécifique [et l\'heure, si vous le souhaitez] en utilisant le format affiché)</li>
        </ul>
        Note: L\'utilisation du "+" et du "-" illustrée ci-dessus est contre-intuitive, mais correcte ("+" représente un retour en arrière dans le temps)..
    </div>';
$_lang['tv_default_email'] = 'Adresse e-mail par défaut';
$_lang['tv_default_email_desc'] = 'L\'adresse e-mail que cette TV affichera si l\'utilisateur n\'en fournit pas.';
$_lang['tv_default_file'] = 'Fichier par défaut';
$_lang['tv_default_file_desc'] = 'Le chemin d\'accès au fichier que la TV affichera si l\'utilisateur n\'en fournit pas.';
$_lang['tv_default_image'] = 'Image par défaut';
$_lang['tv_default_image_desc'] = 'Le chemin de l\'image que cette TV affichera si l\'utilisateur n\'en fournit pas un.';
$_lang['tv_default_option'] = 'Option par défaut';
$_lang['tv_default_option_desc'] = 'The option selected for this TV if the user does not choose one. If your options include labels (e.g., Option One==1||Option Two==2||Option Three==3), be sure to enter the value (i.e., “1” for Option One)';
$_lang['tv_default_options'] = 'Options(s) par défaut';
$_lang['tv_default_options_desc'] = 'A double-pipe-separated set of option(s) selected for this TV if the user does not choose one or more. If your options include labels (e.g., Option One==1||Option Two==2||Option Three==3), be sure to enter the value (i.e., “1” for Option One, or “1||3” for Option One and Option Three)';
$_lang['tv_default_radio_desc'] = $_lang['tv_default_option_desc'];
$_lang['tv_default_listbox_desc'] = $_lang['tv_default_option_desc'];
$_lang['tv_default_listbox-multiple_desc'] = $_lang['tv_default_options_desc'];
$_lang['tv_default_number'] = 'Nombre par défaut';
$_lang['tv_default_number_desc'] = 'Le nombre que cette TV affichera si l\'utilisateur n\'en choisit pas..';
$_lang['tv_default_resource'] = 'Ressource par défaut (ID)';
$_lang['tv_default_resourcelist_desc'] = 'La ressource que cette TV affichera si l\'utilisateur n\'en choisit pas.';
$_lang['tv_default_tag'] = 'Tag(s) par défaut';
$_lang['tv_default_tag_desc'] = 'A comma-separated set of option(s) selected for this TV if the user does not choose one or more. If your options include labels (e.g., Tag One==1||Tag Two==2||Tag Three==3), be sure to enter the value (i.e., “1” for Tag One, or “1,3” for Tag One and Tag Three)';
$_lang['tv_default_text'] = 'Texte par défaut';
$_lang['tv_default_text_desc'] = 'Le texte que cette TV affichera si l\'utilisateur n\'en choisit pas..';
$_lang['tv_default_url'] = 'URL par défaut';
$_lang['tv_default_url_desc'] = 'L\'URL que cette TV affichera si l\'utilisateur n\'en choisit pas..';
$_lang['tv_elements_checkbox'] = 'Options du choix multiple';
$_lang['tv_elements_listbox'] = 'Options de la liste déroulante';
$_lang['tv_elements_radio'] = 'Options des boutons radio';
$_lang['tv_elements_tag'] = 'Options du Tag';
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
