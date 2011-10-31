<?php
/**
 * Export Czech lexicon topic
 *
 * @language cs
 * @package modx
 * @subpackage lexicon
 *
 * @author modxcms.cz
 * @updated 2011-10-23
 */
// $_lang['export_site_cacheable'] = 'Include non-cacheable files:';
$_lang['export_site_cacheable'] = 'Obsáhnout soubory, neuložené v cache:';

// $_lang['export_site_exporting_document'] = 'Exporting file <strong>%s</strong> of <strong>%s</strong><br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<small><i>%s</i>, id %s</small><br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
$_lang['export_site_exporting_document'] = 'Exportuji soubor <strong>%s</strong> z <strong>%s</strong><br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<small><i>%s</i>, ID %s</small><br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';

// $_lang['export_site_failed'] = '<span style="color:#990000">Failed!</span>';
$_lang['export_site_failed'] = '<span style="color:#990000">Nezdařilo se!</span>';

// $_lang['export_site_html'] = 'Export site to HTML';
$_lang['export_site_html'] = 'Exportovat portál do HTML';

// $_lang['export_site_maxtime'] = 'Max export time:';
$_lang['export_site_maxtime'] = 'Maximální čas exportu:';

// $_lang['export_site_maxtime_message'] = 'Here you can specify the number of seconds MODX can take to export the site (overriding PHP settings). Enter 0 for unlimited time. Please note, setting 0 or a really high number can do weird things to your server and is not recommended.';
$_lang['export_site_maxtime_message'] = 'Na tomto místě můžete určit jakou maximální dobu může trvat export portálu (hodnota je v sekundách a přepíše se tím nastavení PHP). Zadejte 0 pro nekonečno. Pamatujte, že nastavení 0 nebo velké číslo můžete zapříčinit problémy serveru a není doporučeno.';

// $_lang['export_site_message'] = '<p>Using this function you can export the entire site to HTML files. Please note, however, that you will lose a lot of the MODX functionality should you do so:</p><ul><li>Page reads on the exported files will not be recorded.</li><li>Interactive snippets will NOT work in exported files</li><li>Only regular documents will be exported, Weblinks will not be exported.</li><li>The export process may fail if your documents contain snippets which send redirection headers.</li><li>Depending on how you've written your documents, style sheets and images, the design of your site may be broken. To fix this, you can save/move your exported files to the same directory where the main MODX index.php file is located.</li></ul><p>Please fill out the form and press 'Export' to start the export process. The files created will be saved in the location you specify, using, where possible, the document's aliases as filenames. While exporting your site, it's best to have the MODX configuration item 'Friendly aliases' set to 'yes'. Depending on the size of your site, the export may take a while.</p><p><em>Any existing files will be overwritten by the new files if their names are identical!</em></p>';
$_lang['export_site_message'] = '<p>Použitím této funkce můžete celý portál exportovat do HTML souborů. Pamatujte, že přijdete o spoustu funkčních věcí jako:</p><ul><li>Přečtení stránek nebude započítáváno do statistik přečtení.</li><li>Snippety nebudou pracovat.</li><li>Exportovány budou pouze skutečné dokumenty, webové odkazy nebudou exportovány.</li><li>Export může selhat pokud dokumenty obsahují snippety používající přesměrování.</li><li>V závislosti na tom jak jsou psány dokumenty, CSS a kde jsou uloženy obrázky může být poškozen design portálu. Pokud chcete tomuto předejít uložte/přesuňte exportované soubory tam, kde se nachází vlastní instalace MODX.</li></ul><p>Vyplňte formulář a klikněte na "Export" pro zahájení exportování. Vytvořené soubory budou uloženy na místo, které jste definoval a URL aliasy dokumentů budou použity jako názvy souborů. Při exportování portálu je dobré mít v konfiguraci povoleno nastavení "friendly_urls", nejlepší je mít v konfiguraci MODX nastavenu položku "Přátelská URL" na "Ano". V závislosti na rozsáhlosti portálu může export chvíli trvat.</p><p><em>Existující soubory stejných názvů budou přepsány!</em></p>';

// $_lang['export_site_numberdocs'] = '<p><strong>Found %s documents to export...</strong></p>';
$_lang['export_site_numberdocs'] = '<p><strong>Nalezeno %s dokumentů k exportu...</strong></p>';

// $_lang['export_site_prefix'] = 'File prefix:';
$_lang['export_site_prefix'] = 'Předpona souboru:';

// $_lang['export_site_start'] = 'Start export';
$_lang['export_site_start'] = 'Začít export';

// $_lang['export_site_success'] = '<span style="color:#009900">Success!</span>';
$_lang['export_site_success'] = '<span style="color:#009900">Hotovo!</span>';

// $_lang['export_site_suffix'] = 'File suffix:';
$_lang['export_site_suffix'] = 'Přípona souboru:';

// $_lang['export_site_target_unwritable'] = 'Target directory isn't writable. Please ensure the directory is writable, and try again.';
$_lang['export_site_target_unwritable'] = 'Do vybrané složky není možno zapisovat. Ujistěte se, že je do složky možné zapisovat a zkuste to znovu.';

// $_lang['export_site_time'] = 'Export finished. Export took %s seconds to complete.';
$_lang['export_site_time'] = 'Export dokončen. Export trval %s sekund.';
