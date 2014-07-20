<?php
/**
 * Export English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */
$_lang['export_site_cacheable'] = 'Obsáhnout soubory, neuložené v cache:';
$_lang['export_site_exporting_document'] = 'Exportuji soubor <strong>%s</strong> z <strong>%s</strong><br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<small><i>%s</i>, ID %s</small><br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
$_lang['export_site_failed'] = '<span style="color:#990000">Nezdařilo se!</span>';
$_lang['export_site_html'] = 'Exportovat portál do HTML';
$_lang['export_site_maxtime'] = 'Maximální čas exportu:';
$_lang['export_site_maxtime_message'] = 'Na tomto místě můžete určit jakou maximální dobu může trvat export portálu (hodnota je v sekundách a přepíše se tím nastavení PHP). Zadejte 0 pro nekonečno. Pamatujte, že nastavení 0 nebo velké číslo můžete zapříčinit problémy serveru a není doporučeno.';
$_lang['export_site_message'] = '<p>Použitím této funkce můžete celý portál exportovat do HTML souborů. Pamatujte, že přijdete o spoustu funkčních věcí jako:</p><ul><li>Přečtení stránek nebude započítáváno do statistik přečtení.</li><li>Snippety nebudou pracovat.</li><li>Exportovány budou pouze skutečné dokumenty, webové odkazy nebudou exportovány.</li><li>Export může selhat pokud dokumenty obsahují snippety používající přesměrování.</li><li>V závislosti na tom jak jsou psány dokumenty, CSS a kde jsou uloženy obrázky může být poškozen design portálu. Pokud chcete tomuto předejít uložte/přesuňte exportované soubory tam, kde se nachází vlastní instalace MODX.</li></ul><p>Vyplňte formulář a klikněte na "Export" pro zahájení exportování. Vytvořené soubory budou uloženy na místo, které jste definoval a URL aliasy dokumentů budou použity jako názvy souborů. Při exportování portálu je dobré mít v konfiguraci povoleno nastavení "friendly_urls", nejlepší je mít v konfiguraci MODX nastavenu položku "Přátelská URL" na "Ano". V závislosti na rozsáhlosti portálu může export chvíli trvat.</p><p><em>Existující soubory stejných názvů budou přepsány!</em></p>';
$_lang['export_site_numberdocs'] = '<p><strong>Nalezeno %s dokumentů k exportu...</strong></p>';
$_lang['export_site_prefix'] = 'Předpona souboru:';
$_lang['export_site_start'] = 'Začít export';
$_lang['export_site_success'] = '<span style="color:#009900">Hotovo!</span>';
$_lang['export_site_suffix'] = 'Přípona souboru:';
$_lang['export_site_target_unwritable'] = 'Do vybrané složky není možno zapisovat. Ujistěte se, že je do složky možné zapisovat a zkuste to znovu.';
$_lang['export_site_time'] = 'Export dokončen. Export trval %s sekund.';