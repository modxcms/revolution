<?php
/**
 * TV Widget English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */
$_lang['attributes'] = 'Eigenschaften';
$_lang['capitalize'] = 'Alle Worte groß';
$_lang['checkbox'] = 'Checkbox';
$_lang['checkbox_columns'] = 'Spalten';
$_lang['checkbox_columns_desc'] = 'Die Anzahl der Spalten, in denen die Checkboxen angezeigt werden.';
$_lang['class'] = 'CSS-Klasse';
$_lang['combo_allowaddnewdata'] = 'Das Hinzufügen neuer Werte erlauben';
$_lang['combo_allowaddnewdata_desc'] = 'Wenn diese Einstellung auf "Ja" steht, können Werte hinzugefügt werden, die nicht bereits in der Liste vorkommen. Standard ist "Nein".';
$_lang['combo_forceselection'] = 'Auswahl auf die Liste beschränken';
$_lang['combo_forceselection_desc'] = 'Wenn Autovervollständigung verwendet wird und diese Einstellung auf "Ja" steht, ist nur die Eingabe von Werten möglich, die in der Liste vorkommen.';
$_lang['combo_forceselection_multi_desc'] = 'Wenn dies aktiviert ist, sind nur Elemente erlaubt, die in der Liste vorhanden sind. Sonst können auch neue Werte eingegeben werden.';
$_lang['combo_listempty_text'] = 'Text für leere Liste';
$_lang['combo_listempty_text_desc'] = 'Wenn Autovervollständigung aktiviert ist und der Benutzer einen Wert eintippt, der nicht in der Liste vorkommt, wird der hier hinterlegte Text angezeigt.';
$_lang['combo_listheight'] = 'Listen-Höhe';
$_lang['combo_listheight_desc'] = 'Die Höhe der Dropdown-Liste in Pixeln. Standardmäßig wird die Höhe der Combobox verwendet.';
$_lang['combo_listwidth'] = 'Listen-Breite';
$_lang['combo_listwidth_desc'] = 'Die Breite der Dropdown-Liste in Pixeln. Standardmäßig wird die Breite der Combobox verwendet.';
$_lang['combo_maxheight'] = 'Maximale Höhe';
$_lang['combo_maxheight_desc'] = 'Die maximale Höhe der Dropdown-Liste in Pixeln, bevor Bildlaufleisten (Scrollbars) angezeigt werden (Standard: 300).';
$_lang['combo_stackitems'] = 'Ausgewählte Elemente untereinander anzeigen';
$_lang['combo_stackitems_desc'] = 'Wenn diese Einstellung auf "Ja" gesetzt wird, werden die bereits ausgewählten Elemente untereinander angezeigt (1 Element pro Zeile). Die Standardeinstellung ist "Nein", wodurch die Elemente nebeneinander angezeigt werden; ein Zeilenumbruch erfolgt nur, wenn in einer Zeile kein Platz mehr ist.';
$_lang['combo_title'] = 'Erster Listeneintrag';
$_lang['combo_title_desc'] = 'Wird hier etwas eingegeben, so wird an Anfang der Liste ein zusätzliches Element mit dem hier hinterlegten Text eingefügt.';
$_lang['combo_typeahead'] = 'Autovervollständigung aktivieren';
$_lang['combo_typeahead_desc'] = 'Wenn diese Einstellung auf "Ja" steht, wird der voraussichtliche Rest des gerade eingetippten Textes nach einer konfigurierbaren Verzögerung (Autovervollständigungs-Verzögerung) automatisch angezeigt und ausgewählt, wenn der bereits eingegebene Text mit einem bekannten Wert übereinstimmt (Standard: "Nein").';
$_lang['combo_typeahead_delay'] = 'Autovervollständigungs-Verzögerung';
$_lang['combo_typeahead_delay_desc'] = 'Die Zeitspanne in Millisekunden, die gewartet wird, bis der automatisch vervollständigte Text angezeigt wird, sofern Autovervollständigung aktiviert wurde (Standard 250).';
$_lang['date'] = 'Datum';
$_lang['date_format'] = 'Datumsformat';
$_lang['date_use_current'] = 'Wenn ohne Wert, aktuelles Datum verwenden';
$_lang['default'] = 'Standardeigenschaften';
$_lang['delim'] = 'Durch Trennzeichen separierte Werte';
$_lang['delimiter'] = 'Durch Trennzeichen separierte Werte';
$_lang['disabled_dates'] = 'Deaktivierte Daten';
$_lang['disabled_dates_desc'] = 'Eine kommaseparierte Liste von "Daten", die deaktiviert werden sollen, wodurch sie nicht mehr auswählbar sind und bei direkter Eingabe eine Fehlermeldung erzeugen. Die Daten werden als Zeichenketten eingegeben. Diese Zeichenketten werden verwendet, um dynamisch einen regulären Ausdruck zu erzeugen; sie sind also sehr mächtig. Einige Beispiele:<br />
- Genau diese Daten deaktivieren: 2014-03-08,2014-09-16<br />
- Diese Tage in jedem Jahr deaktivieren: 03-08,09-16<br />
- Übereinstimmung mit dem Anfang der Datumsangabe (nützlich, wenn Sie kurze Jahreszahlen verwenden): ^14-08<br />
- Jeden Tag im März 2014 deaktivieren: 03-..-2014<br />
- Jeden Tag im März jedes Jahres deaktivieren: ^03<br />
Bitte beachten Sie, dass das Format der Daten, die in der Liste enthalten sind, exakt mit dem in den Systemeinstellungen eingestellten Manager-Datumsformat (manager_date_format) übereinstimmen muss. Um reguläre Ausdrücke korrekt verwenden zu können, müssen Sie, wenn Sie ein Datumsformat verwenden, in dem Punkte vorkommen (was beim normalen deutschen Datumsformat ja der Fall ist), den Punkt in der Liste der deaktivierten Daten jeweils "escapen", also mit einem vorgestellten Backslash entwerten ("\."), da der Punkt in regulären Ausdrücken für ein beliebiges Zeichen steht.';
$_lang['disabled_days'] = 'Deaktivierte Wochentage';
$_lang['disabled_days_desc'] = 'Eine kommaseparierte Liste von Indizes von Wochentagen, die deaktiviert werden sollen, beginnend bei 0, was für "Sonntag" steht. Deaktivierte Tage sind nicht mehr auswählbar und erzeugen bei direkter Eingabe eine Fehlermeldung. Beispiele:<br />
- Sonntag und Samstag deaktivieren: 0,6<br />
- Werktage (Montag bis Freitag) deaktivieren: 1,2,3,4,5';
$_lang['dropdown'] = 'DropDown-Liste';
$_lang['earliest_date'] = 'Frühestes Datum';
$_lang['earliest_date_desc'] = 'Das früheste erlaubte Datum, das ausgewählt werden kann.';
$_lang['earliest_time'] = 'Früheste Zeit';
$_lang['earliest_time_desc'] = 'Die früheste erlaubte Zeit, die ausgewählt werden kann.';
$_lang['email'] = 'E-Mail';
$_lang['file'] = 'Datei';
$_lang['height'] = 'Höhe';
$_lang['hidden'] = 'Versteckt';
$_lang['htmlarea'] = 'HTML-Area';
$_lang['htmltag'] = 'HTML-Tag';
$_lang['image'] = 'Bild';
$_lang['image_align'] = 'Ausrichtung';
$_lang['image_align_list'] = 'none,baseline,top,middle,bottom,texttop,absmiddle,absbottom,left,right';
$_lang['image_alt'] = 'Alternativtext';
$_lang['image_border_size'] = 'Rahmenbreite';
$_lang['image_hspace'] = 'Horizontaler Abstand';
$_lang['image_vspace'] = 'Vertikaler Abstand';
$_lang['latest_date'] = 'Spätestes Datum';
$_lang['latest_date_desc'] = 'Das späteste erlaubte Datum, das ausgewählt werden kann.';
$_lang['latest_time'] = 'Späteste Zeit';
$_lang['latest_time_desc'] = 'Die späteste erlaubte Zeit, die ausgewählt werden kann.';
$_lang['listbox'] = 'Listbox (einfache Auswahl)';
$_lang['listbox-multiple'] = 'Listbox (Mehrfachauswahl)';
$_lang['list-multiple-legacy'] = 'Hierarchische Listbox (Mehrfachauswahl)';
$_lang['lower_case'] = 'Kleinbuchstaben';
$_lang['max_length'] = 'Maximale Länge';
$_lang['min_length'] = 'Minimale Länge';
$_lang['regex_text'] = 'Fehlermeldung, die ausgegeben wird, wenn die Eingabe nicht dem regulären Ausdruck entspricht';
$_lang['regex'] = 'Regulärer Ausdruck, mit dem die Eingabe validiert wird';
$_lang['name'] = 'Name';
$_lang['number'] = 'Zahl';
$_lang['number_allowdecimals'] = 'Erlaube Dezimalstellen';
$_lang['number_allownegative'] = 'Erlaube negative Zahlen';
$_lang['number_decimalprecision'] = 'Dezimalstellen';
$_lang['number_decimalprecision_desc'] = 'Die maximale Anzahl an Dezimalstellen, die nach dem Dezimaltrennzeichen angezeigt werden (Standard: 2).';
$_lang['number_decimalseparator'] = 'Dezimaltrennzeichen';
$_lang['number_decimalseparator_desc'] = 'Ein oder mehrere Zeichen, die als Dezimaltrennzeichen zulässig sind (der Standardwert ist der Punkt; im Deutschen wird jedoch das Komma verwendet, also ggf. anpassen!)';
$_lang['number_maxvalue'] = 'Höchstwert';
$_lang['number_minvalue'] = 'Mindestwert';
$_lang['option'] = 'Optionsschaltflächen (Radio Buttons)';
$_lang['parent_resources'] = 'Eltern-Ressourcen';
$_lang['radio_columns'] = 'Spalten';
$_lang['radio_columns_desc'] = 'Die Anzahl der Spalten, in denen die Radio-Buttons angezeigt werden.';
$_lang['rawtext'] = 'Raw Text (deprecated)';
$_lang['rawtextarea'] = 'Raw Textarea (deprecated)';
$_lang['required'] = 'Leere Eingabe erlauben';
$_lang['required_desc'] = 'Wenn diese Einstellung auf "Nein" gesetzt wird, erlaubt MODX dem Benutzer so lange das Speichern der Ressource nicht, bis ein gültiger Wert eingegeben wurde; das Feld darf dann nicht leer bleiben.';
$_lang['resourcelist'] = 'Ressourcen-Liste';
$_lang['resourcelist_depth'] = 'Tiefe';
$_lang['resourcelist_depth_desc'] = 'Gibt an, bis zu welcher Tiefe im Ressourcen-Baum die Anzeige der Ressourcen-Liste erfolgen soll (ab der Ebene unter der angegebenen Eltern-Ressource). Der Standardwert ist 10.';
$_lang['resourcelist_includeparent'] = 'Eltern-Ressourcen mit einbeziehen';
$_lang['resourcelist_includeparent_desc'] = 'Wenn diese Einstellung auf "Ja" steht, werden die Ressourcen, deren IDs im Feld "Eltern-Ressourcen" eingegeben wurden, selbst ebenfalls in die Liste aufgenommen.';
$_lang['resourcelist_limitrelatedcontext'] = 'Auf Kontext der aktuellen Ressource beschränken';
$_lang['resourcelist_limitrelatedcontext_desc'] = 'Wenn diese Einstellung auf "Ja" steht, werden nur die Ressourcen einbezogen, die sich im gleichen Kontext befinden wie die aktuelle Ressource.';
$_lang['resourcelist_limit'] = 'Limit';
$_lang['resourcelist_limit_desc'] = 'Die Anzahl von Ressourcen, auf die die Liste beschränkt werden soll. 0 oder ein leerer Eintrag bedeuten, dass keine Beschränkung vorgenommen wird.';
$_lang['resourcelist_parents'] = 'Eltern-Ressourcen';
$_lang['resourcelist_parents_desc'] = 'Eine Liste von Ressourcen-IDs, deren Kind-Ressourcen in der Liste angezeigt werden sollen.';
$_lang['resourcelist_where'] = 'WHERE-Bedingungen';
$_lang['resourcelist_where_desc'] = 'Ein JSON-Objekt mit WHERE-Bedingungen, die in der generierten SQL-Abfrage zur Darstellung der Ressourcen-Liste für die Filterung der angezeigten Ressourcen verwendet werden. (Die Suche in Template-Variablen wird nicht unterstützt.)<br />Beispiele: [{"template:=":"4"}], [{"pagetitle:!=":"Home"}], [{"parent:IN":[34,56]}]';
$_lang['richtext'] = 'Rich Text';
$_lang['sentence_case'] = 'Nur 1. Wort des Satzes groß';
$_lang['shownone'] = 'Leere Auswahl erlauben';
$_lang['shownone_desc'] = 'Erlaubt dem Benutzer, einen leeren Wert auszuwählen.';
$_lang['start_day'] = 'Wochenstart';
$_lang['start_day_desc'] = 'Der Index des Tages, an dem die Woche beginnen soll, beginnend bei 0 (Standard ist 0, was für "Sonntag" steht; in Deutschland gilt der Montag als erster Tag der Woche, also ggf. anpassen!)';
$_lang['string'] = 'Zeichenkette';
$_lang['string_format'] = 'Zeichenkettenformat';
$_lang['style'] = 'CSS-Style';
$_lang['tag_id'] = 'Tag-ID';
$_lang['tag_name'] = 'Tag-Name';
$_lang['target'] = 'Ziel';
$_lang['text'] = 'Text';
$_lang['textarea'] = 'Textarea';
$_lang['textareamini'] = 'Textarea (Mini)';
$_lang['textbox'] = 'Textbox';
$_lang['time_increment'] = 'Zeitintervall';
$_lang['time_increment_desc'] = 'Die Anzahl der Minuten zwischen zwei Zeitwerten in der Liste (Standard: 15).';
$_lang['hide_time'] = 'Zeit-Option vor Benutzer verbergen';
$_lang['title'] = 'Titel';
$_lang['upper_case'] = 'Großbuchstaben';
$_lang['url'] = 'URL';
$_lang['url_display_text'] = 'Link-Text';
$_lang['width'] = 'Breite';
