<?php
/**
 * Import English lexicon entries
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */
$_lang['import_allowed_extensions'] = 'Vydefinujte čárkou oddělený seznam přípon souborů pro import.<br /><small><em>Políčko ponechte prázdné pro import všech souborů. Neznámé typy budou importovány jako plain text.</em></small>';
$_lang['import_base_path'] = 'Zadejte cestu k souborům pro import.<br /><small><em>Políčko ponechte prázdné pro nastavení cesty z daného kontextu.</em></small>';
$_lang['import_duplicate_alias_found'] = 'Dokument [[+id]] již používá alias [[+alias]]. Zadejte ještě nepoužitý, unikátní alias.';
$_lang['import_element'] = 'Zadejte hlavní HTML element pro import:';
$_lang['import_element_help'] = 'Poskytněte JSON se sdružením “pole”:”hodnota”. Pokud hodnota začíná $, jedná se o selektor (podobný jQuery). Pole může být políčko dokumentu nebo název TV.';
$_lang['import_enter_root_element'] = 'Zadejte hlavní element pro import:';
$_lang['import_files_found'] = '<strong>Nalezeno  %s dokumentů pro import...</strong><p/>';
$_lang['import_parent_document'] = 'Nadřazený dokument:';
$_lang['import_parent_document_message'] = 'Použijte strom dokumentů níže k výběru nadřazeného umístění, kam se mají soubory naimportovat.';
$_lang['import_resource_class'] = 'Vyberte typ třídy modResource pro import:<br /><small><em>Použijte modStaticResource pro nalinkování statických zdrojů nebo modDocument pro zkopírování jejich obsahu do databáze.</em></small>';
$_lang['import_site_failed'] = '<span style="color:#990000">Nezdařilo se!</span>';
$_lang['import_site_html'] = 'Importovat portál z HTML';
$_lang['import_site_importing_document'] = 'Importuji soubor <strong>%s</strong> ';
$_lang['import_site_maxtime'] = 'Maximální čas importu:';
$_lang['import_site_maxtime_message'] = 'Na tomto místě můžete určit jakou maximální dobu může trvat import portálu (hodnota je v sekundách a přepíše se tím nastavení PHP). Zadejte 0 pro nekonečno. Pamatujte, že nastavení 0 nebo velké číslo můžete zapříčinit problémy serveru a není doporučeno.';
$_lang['import_site_message'] = '<p>Použitím tohoto nástroje můžete naimportovat obsah portálu ze sady HTML souborů do databáze. <em>Berte na vědomí, že budete muset nakopírovat soubory a složky do složky "core/import".</em></p><p>Vyplňte možnosti níže, případně vyberte nadřazený dokument pro import souborů ze stromu dokumentů a klikněte na "Importovat HTML" . Importované soubory budou uloženy do vybraného umístění, kde budou soubory načteny dle jejich názvu a ten bude použit jako URL alias, zdroje se stejným aliasem budou přepsány.</p>';
$_lang['import_site_resource'] = 'Importovat dokumenty ze statických souborů';
$_lang['import_site_resource_message'] = '<p>Použitím tohoto nástroje můžete naimportovat dokumenty ze statických souborů do databáze. <em>Berte na vědomí, že budete muset nakopírovat soubory a složky do složky "core/import".</em></p><p>Vyplňte možnosti níže, případně vyberte nadřazený dokument pro import souborů ze stromu dokumentů a klikněte na "Importovat dokumenty". Importované soubory budou uloženy do vybraného umístění, kde budou soubory načteny dle jejich názvu a ten bude použit jako URL alias, zdroje se stejným aliasem budou přepsány.</p>';
$_lang['import_site_skip'] = '<span style="color:#990000">Přeskočeno!</span>';
$_lang['import_site_start'] = 'Spustit import';
$_lang['import_site_success'] = '<span style="color:#009900">Hotovo!</span>';
$_lang['import_site_time'] = 'Import dokončen. Import trval %s sekund.';
$_lang['import_use_doc_tree'] = 'Použijte strom dokumentů níže k výběru nadřazeného umístění, kam se mají soubory naimportovat.';