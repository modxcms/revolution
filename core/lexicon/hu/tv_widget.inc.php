<?php
/**
 * TV Widget English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */
$_lang['attributes'] = 'Jellemzők';
$_lang['attr_attr_desc'] = 'Egy vagy több, szóközzel elválasztott jellemző ehhez az elemhez (pl. <span class="example-input">rel="external" type="application/pdf"</span>).';
$_lang['attr_class_desc'] = 'Egy vagy több, szóközzel elválasztott CSS osztály neve.';
$_lang['attr_style_desc'] = 'CSS szabályok (például <span class="example-input">color:#f36f99; text-decoration:none;</span>).';
$_lang['attr_target_blank'] = 'Üres';
$_lang['attr_target_parent'] = 'Szülő';
$_lang['attr_target_self'] = 'Saját';
$_lang['attr_target_top'] = 'Legfelső';
$_lang['attr_target_desc'] = 'Indicates in which window/tab or frame the linked url should open. To target a specific frame, enter its name in place of one of the provided options.';
$_lang['capitalize'] = 'Nagy kezdőbetű';
$_lang['checkbox'] = 'Jelölőnégyzet';
$_lang['checkbox_columns'] = 'Oszlopok';
$_lang['checkbox_columns_desc'] = 'Hány oszlopban jelenjenek meg a jelölőnégyzetek.';
$_lang['class'] = 'Osztály';
$_lang['classes'] = 'Osztály(ok)';
$_lang['combo_allowaddnewdata'] = 'Új elemek hozzáadásának engedélyezése';
$_lang['combo_allowaddnewdata_desc'] = 'Ha igen, hozzá lehet adni új elemeket a felsoroláshoz. Alapértelmezetten nem lehet.';
$_lang['combo_forceselection'] = 'Egyezés megkövetelése';
$_lang['combo_forceselection_desc'] = 'Only save typed option when it matches one already defined in the list.';
$_lang['combo_forceselection_multi_desc'] = 'Ha Igen van beállítva, csak a felsorolásban már szereplő elemek engedélyezettek. Ha Nem van beállítva, új értékek is bevihetők.';
$_lang['combo_listempty_text'] = 'Üzenet, ha nincs meg a választható érték';
$_lang['combo_listempty_text_desc'] = 'Message to display when typed text does not match existing options.';
$_lang['combo_listheight'] = 'Felsorolás magassága';
$_lang['combo_listheight_desc'] = 'A lenyíló választék magassága százalékban vagy képpontban. Alapértéke a vegyes szövegbeviteli doboz magassága.';
$_lang['combo_listwidth'] = 'Felsorolás szélessége';
$_lang['combo_listwidth_desc'] = 'A lenyíló választék szélessége százalékban vagy képpontban. Alapértéke a vegyes szövegbeviteli doboz szélessége.';
$_lang['combo_maxheight'] = 'Legnagyobb magasság';
$_lang['combo_maxheight_desc'] = 'A lenyíló választék legnagyobb magassága képpontban, mielőtt megjelennek a gördítősávok (Alapérték: 300).';
$_lang['combo_stackitems'] = 'Kiválasztott tételek halmozása';
$_lang['combo_stackitems_desc'] = 'Ha Igen van beállítva, soronként 1 tétel jelenik meg. Alapértéke Nem, amikor a tételeket egy sorban jeleníti meg.';
$_lang['combo_title'] = 'Felsorolás fejléce';
$_lang['combo_title_desc'] = 'Ha meg van adva, egy fejléc elem jön létre, ami tartalmazza ezt a szöveget, és a lenyíló választék tetején szerepel.';
$_lang['combo_typeahead'] = 'Gépeléskiegészítés engedélyezése';
$_lang['combo_typeahead_desc'] = 'Töltse be és jelölje is ki a lehetőségeket a begépelt szöveg alapján egy beállított késleltetéssel. (Alapérték: Nem)';
$_lang['combo_typeahead_delay'] = 'Késleltetés';
$_lang['combo_typeahead_delay_desc'] = 'Ezredmásodperc az illeszkedő lehetőség megjelenítése előtt. (Alapérték: 250)';
$_lang['date'] = 'Dátum';
$_lang['date_format'] = 'Dátum formátuma';
$_lang['date_format_desc'] = 'Adjon meg egy formátumot a <a href="https://www.php.net/strftime" target="_blank">php strftime szabályrendszere</a> használatával.
    <div class="example-list">Common examples include:
        <ul>
            <li><span class="example-input">[[+example_1a]]</span> ([[+example_1b]]) (alapértelmezett)</li>
            <li><span class="example-input">[[+example_2a]]</span> ([[+example_2b]])</li>
            <li><span class="example-input">[[+example_3a]]</span> ([[+example_3b]])</li>
            <li><span class="example-input">[[+example_4a]]</span> ([[+example_4b]])</li>
            <li><span class="example-input">[[+example_5a]]</span> ([[+example_5b]])</li>
            <li><span class="example-input">[[+example_6a]]</span> ([[+example_6b]])</li>
            <li><span class="example-input">[[+example_7a]]</span> ([[+example_7b]])</li>
        </ul>
    </div>
';
$_lang['date_use_current'] = 'Használja a jelenlegi dátumot tartaléknak.';
$_lang['date_use_current_desc'] = 'Ha egy érték ebben a sablonváltozóban nem kötelező (Üres engedélyezése = “Igen“), és az alapértelmezett dátum nincs megadva, ezt a beállítást “Igen“-re állítva megjeleníti a jelenlegi dátumot.';
$_lang['default'] = 'Alapértelmezett';
$_lang['default_date_now'] = 'Ma a jelenlegi időponttal';
$_lang['default_date_today'] = 'Ma (éjfél)';
$_lang['default_date_yesterday'] = 'Tegnap (éjfél)';
$_lang['default_date_tomorrow'] = 'Holnap (éjfél)';
$_lang['default_date_custom'] = 'Egyéni (lásd a leírást alább)';
$_lang['delim'] = 'Határolójel';
$_lang['delimiter'] = 'Határolójel';
$_lang['delimiter_desc'] = 'Egy vagy több írásjel az értékek elválasztására (a több lehetőséget is támogató sablonváltozókhoz)';
$_lang['disabled_dates'] = 'Nem választható dátumok';
$_lang['disabled_dates_desc'] = 'A kezelőben levő dátumformátumok vesszővel elválasztott, javascript <abbr title="reguláris kifejezés">regex</abbr>-alapú felsorolása (határolók nélkül)  (jelenleg “[[+format_current]]”).
    <p>Példák az alapértelmezett formátum (“[[+format_default]]”) használatára:</p>
    <div class="example-list">
        <ul>
            <li><span class="example-input">[[+example_1]]</span> (egyedi dátumok kiválasztása)</li>
            <li><span class="example-input">[[+example_2a]]</span> ([[+example_2b]] és [[+example_2c]] kiválasztása minden évben)</li>
            <li><span class="example-input">[[+example_3a]]</span> (“^” a betűsor elejére illeszkedik; az összes [[+example_3b]] kiválasztása)</li>
            <li><span class="example-input">[[+example_4a]]</span> (kiválasztja [[+example_4b]] minden napját)</li>
            <li><span class="example-input">[[+example_5]]</span> (“$” a betűsor végére illeszkedik; kiválasztja március minden napját minden évben)</li>
        </ul>
        Megjegyzés: A dátumformátumban használt pont elválasztókat hatástalanítani kell (pl., “[[+example_6a]]” helyett “[[+example_6b]]”).
    </div>
';
$_lang['disabled_days'] = 'Nem választható napok';
$_lang['disabled_days_desc'] = '';
$_lang['dropdown'] = 'Legördülő menü';
$_lang['earliest_date'] = 'Legkorábbi dátum';
$_lang['earliest_date_desc'] = 'A legkorábbi választható dátum.';
$_lang['earliest_time'] = 'Legkorábbi időpont';
$_lang['earliest_time_desc'] = 'A legkorábbi választható időpont.';
$_lang['email'] = 'E-mail';
$_lang['file'] = 'Állomány';
$_lang['height'] = 'Magasság';
$_lang['hidden'] = 'Rejtett';
$_lang['hide_time'] = 'Az időbeállítás elrejtése';
$_lang['hide_time_desc'] = 'Removes the ability to choose a time from this TV’s date picker.';
$_lang['htmlarea'] = 'HTML-terület';
$_lang['htmltag'] = 'HTML jelölő';
$_lang['image'] = 'Kép';
$_lang['image_alt'] = 'Kép hiánya esetén megjelenítendő szöveg';
$_lang['latest_date'] = 'Legkésőbbi dátum';
$_lang['latest_date_desc'] = 'A legkésőbbi választható dátum.';
$_lang['latest_time'] = 'Legkésőbbi időpont';
$_lang['latest_time_desc'] = 'A legkésőbbi választható időpont.';
$_lang['listbox'] = 'Lista (egyetlen választással)';
$_lang['listbox-multiple'] = 'Lista (több választási lehetőség)';
$_lang['lower_case'] = 'Kisbetűs';
$_lang['max_length'] = 'Legnagyobb hossz';
$_lang['min_length'] = 'Legkisebb hossz';
$_lang['regex_text'] = 'Reguláris kifejezés hiba';
$_lang['regex_text_desc'] = 'The message to show if the user enters text that is invalid according to the <abbr title="regular expression">regex</abbr> validator.';
$_lang['regex'] = 'Reguláris kifejezés ellenőrző';
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
$_lang['name'] = 'Név';
$_lang['number'] = 'Szám';
$_lang['number_allowdecimals'] = 'Tizedesek engedélyezése';
$_lang['number_allownegative'] = 'Negatív engedélyezése';
$_lang['number_decimalprecision'] = 'Pontosság';
$_lang['number_decimalprecision_desc'] = 'The maximum number of digits allowed after the decimal separator. (Default: 2)';
$_lang['number_decimalprecision_strict'] = 'Szigorú tizedes pontosság';
$_lang['number_decimalprecision_strict_desc'] = 'When set to “Yes,” preserves trailing zeros in decimal numbers (defaults to “No”).';
/* See note in number inputproperties config re separators */
$_lang['number_decimalseparator'] = 'Elválasztó';
$_lang['number_decimalseparator_desc'] = 'The character used as the decimal separator. (Default: “.”)';
$_lang['number_maxvalue'] = 'Legnagyobb érték';
$_lang['number_minvalue'] = 'Legkisebb érték';
$_lang['option'] = 'Választókapcsoló';
$_lang['parent_resources'] = 'Szülő erőforrások';
$_lang['radio_columns'] = 'Oszlopok';
$_lang['radio_columns_desc'] = 'The number of columns the radio buttons are displayed in.';
$_lang['rawtext'] = 'Nyers szöveg (elavult)';
$_lang['rawtextarea'] = 'Nyers szövegmező (elavult)';
$_lang['required'] = 'Üres engedélyezése';
$_lang['required_desc'] = 'Select “No” to make this TV a required field in the Resources it’s assigned to. (Default: “Yes”)';
$_lang['resourcelist'] = 'Erőforrások felsorolása';
$_lang['resourcelist_depth'] = 'Mélység';
$_lang['resourcelist_depth_desc'] = 'The number of subfolders to drill down into for this lising’s search query. (Default: 10)';
$_lang['resourcelist_forceselection_desc'] = 'Letiltva; csak a felsorolásban levő egyezések érvényesek.';
$_lang['resourcelist_includeparent'] = 'Tartalmazza a szülőket';
$_lang['resourcelist_includeparent_desc'] = 'Select “Yes” to include the Resources specified in the Parents field in the list.';
$_lang['resourcelist_limitrelatedcontext'] = 'Szűkítse a kapcsolódó környezetre';
$_lang['resourcelist_limitrelatedcontext_desc'] = 'Select “Yes” to only include the Resources related to the context of the current Resource.';
$_lang['resourcelist_limit'] = 'Korlátoz';
$_lang['resourcelist_limit_desc'] = 'The maximum number of Resources shown in this TV’s listing. (Default: 0, meaning unlimited)';
$_lang['resourcelist_listempty_text_desc'] = 'Disabled; selections will always match the list.';
$_lang['resourcelist_parents'] = 'Szülők';
$_lang['resourcelist_parents_desc'] = 'If specified, this TV’s listing will include only the child resources from this comma-separated set of resource IDs (containers).';
$_lang['resourcelist_where'] = 'Keresési feltételek';
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
$_lang['richtext'] = 'Formázott szöveg';
$_lang['sentence_case'] = 'Nagybetűs mondatkezdet';
$_lang['start_day'] = 'Kezdőnap';
$_lang['start_day_desc'] = 'Day displayed as the beginning of the week in this TV’s date picker. (Default: “Sunday”)';
$_lang['string'] = 'Karakterlánc';
$_lang['string_format'] = 'Karakterlánc alakja';
$_lang['style'] = 'Stílus';
$_lang['tag_name'] = 'Jelölő neve';
$_lang['target'] = 'Cél';
$_lang['text'] = 'Szöveg';
$_lang['textarea'] = 'Szövegmező';
$_lang['textareamini'] = 'Szövegmező (mini)';
$_lang['textbox'] = 'Szövegdoboz';
$_lang['time_increment'] = 'Idő növelése';
$_lang['time_increment_desc'] = 'A felsorolt időértékek közötti különbség percben. (Alapérték: 15)';
$_lang['title'] = 'Felirat';
$_lang['tv_default_checkbox_desc'] = 'A double-pipe-separated set of option(s) selected for this TV if the user does not check one or more. If your options include labels (e.g., Option One==1||Option Two==2||Option Three==3), be sure to enter the value (i.e., “1” for Option One, or “1||3” for Option One and Option Three)';
$_lang['tv_default_date'] = 'Alapértelmezett dátum és idő';
$_lang['tv_default_date_desc'] = 'The date to show if the user does not provide one. Choose a relative date from the list above or enter a different date using one of the following patterns:
    <div class="example-list">
        <ul>
            <li><span class="example-input">[[+example_1]]</span> (number respresents hours ago)</li>
            <li><span class="example-input">[[+example_2]]</span> (number represents hours in the future)</li>
            <li><span class="example-input">[[+example_3]]</span> (a specific date [and time if desired] using the format shown)</li>
        </ul>
        Note: The use of the “+” and “-” shown above is counter-intuitive, but correct (“+” represents backward in time).
    </div>';
$_lang['tv_default_email'] = 'Alapértelmezett email cím';
$_lang['tv_default_email_desc'] = 'The email address this TV will show if the user does not provide one.';
$_lang['tv_default_file'] = 'Alapértelmezett állomány';
$_lang['tv_default_file_desc'] = 'The file path this TV will show if the user does not provide one.';
$_lang['tv_default_image'] = 'Alapértelmezett kép ';
$_lang['tv_default_image_desc'] = 'The image path this TV will show if the user does not provide one.';
$_lang['tv_default_option'] = 'Alapértelmezett beállítás';
$_lang['tv_default_option_desc'] = 'The option selected for this TV if the user does not choose one. If your options include labels (e.g., Option One==1||Option Two==2||Option Three==3), be sure to enter the value (i.e., “1” for Option One)';
$_lang['tv_default_options'] = 'Alapértelmezett beállítás(ok)';
$_lang['tv_default_options_desc'] = 'A double-pipe-separated set of option(s) selected for this TV if the user does not choose one or more. If your options include labels (e.g., Option One==1||Option Two==2||Option Three==3), be sure to enter the value (i.e., “1” for Option One, or “1||3” for Option One and Option Three)';
$_lang['tv_default_radio_desc'] = $_lang['tv_default_option_desc'];
$_lang['tv_default_listbox_desc'] = $_lang['tv_default_option_desc'];
$_lang['tv_default_listbox-multiple_desc'] = $_lang['tv_default_options_desc'];
$_lang['tv_default_number'] = 'Alapértelmezett szám';
$_lang['tv_default_number_desc'] = 'The number this TV will show if the user does not provide one.';
$_lang['tv_default_resource'] = 'Alapértelmezett erőforrás (ID)';
$_lang['tv_default_resourcelist_desc'] = 'The resource this TV will show if the user does not choose one.';
$_lang['tv_default_tag'] = 'Alapértelmezett címké(k)';
$_lang['tv_default_tag_desc'] = 'A comma-separated set of option(s) selected for this TV if the user does not choose one or more. If your options include labels (e.g., Tag One==1||Tag Two==2||Tag Three==3), be sure to enter the value (i.e., “1” for Tag One, or “1,3” for Tag One and Tag Three)';
$_lang['tv_default_text'] = 'Alapértelmezett szöveg';
$_lang['tv_default_text_desc'] = 'The text content this TV will show if the user does not provide it.';
$_lang['tv_default_url'] = 'Alapértelmezett URL';
$_lang['tv_default_url_desc'] = 'The URL this TV will show if the user does not provide one.';
$_lang['tv_elements_checkbox'] = 'Jelölőnégyzet beállításai';
$_lang['tv_elements_listbox'] = 'Lenyíló felsorolás beállításai';
$_lang['tv_elements_radio'] = 'Rádiógomb beállításai';
$_lang['tv_elements_tag'] = 'Címke beállításai';
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
$_lang['upper_case'] = 'Nagybetű';
$_lang['url'] = 'Webcím';
$_lang['url_display_text'] = 'Szöveg megjelenítése';
$_lang['width'] = 'Szélesség';

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
