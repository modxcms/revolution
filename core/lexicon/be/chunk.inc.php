<?php
/**
 * Chunk English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */

// Entry out of alpha order because it must come before the entry it's used in below
$_lang['example_tag_chunk_name'] = 'НазваЧанка';

$_lang['chunk'] = 'Чанк';
$_lang['chunk_category_desc'] = 'Выкарыстоўвайце для групоўкі чанкаў у дрэве элементаў.';
$_lang['chunk_code'] = 'Код чанка (HTML)';
$_lang['chunk_description_desc'] = 'Інфармацыя аб чанке выкарыстоўваецца ў выніках пошуку і ў падказках у дрэве элементаў.';
$_lang['chunk_delete_confirm'] = 'Вы сапраўды жадаеце выдаліць гэты чанк?';
$_lang['chunk_duplicate_confirm'] = 'Вы сапраўды жадаеце дубляваць гэты чанк?';
$_lang['chunk_err_create'] = 'Адбылася памылка пры спробе стварыць чанк.';
$_lang['chunk_err_duplicate'] = 'Памылка пры дубляванні чанка.';
$_lang['chunk_err_ae'] = 'Ужо існуе чанк з імем "[[+name]]".';
$_lang['chunk_err_invalid_name'] = 'Такое імя чанка недапушчальна.';
$_lang['chunk_err_locked'] = 'Чанк заблакаваны.';
$_lang['chunk_err_remove'] = 'Адбылася памылка пры спробе выдаліць чанк.';
$_lang['chunk_err_save'] = 'Адбылася памылка пры захаванні чанка.';
$_lang['chunk_err_nf'] = 'Чанк не знойдзены!';
$_lang['chunk_err_nfs'] = 'Не знойдзены чанк з ID: [[+id]]';
$_lang['chunk_err_ns'] = 'Чанк не вызначаны.';
$_lang['chunk_err_ns_name'] = 'Калі ласка, вызначце імя.';
$_lang['chunk_lock'] = 'Заблакаваць чанк для рэдагавання';
$_lang['chunk_lock_desc'] = 'Толькі карыстальнікі з правамі "edit_locked" могуць рэдагаваць гэты чанк.';
$_lang['chunk_name_desc'] = 'Размясціце змесціва, створанае гэтым чанкам у рэсурсе, шаблоне або іншым чанке, выкарыстоўваючы наступны тэг MODX: [[+tag]]';
$_lang['chunk_new'] = 'Стварыць чанк';
$_lang['chunk_properties'] = 'Параметры па змаўчанні';
$_lang['chunk_tab_general_desc'] = 'Тут вы можаце задаць асноўныя параметры <em>чанка</em>, а таксама яго змесціва. Змесціва павінна быць HTML, альбо змешчана ў полі <em>Код чанка(HTML)</em> знізу, або ў статычным знешнім файле і можа змяшчаць тэгі MODX. Звярніце ўвагу, што PHP-код выконвацца не будзе.';
$_lang['chunk_tag_copied'] = 'Тэг чанка скапіраваны!';
$_lang['chunks'] = 'Чанкi';

// Temporarily match old keys to new ones to ensure compatibility
// --fields
$_lang['chunk_desc_category'] = $_lang['chunk_category_desc'];
$_lang['chunk_desc_description'] = $_lang['chunk_description_desc'];
$_lang['chunk_desc_name'] = $_lang['chunk_name_desc'];
$_lang['chunk_lock_msg'] = $_lang['chunk_lock_desc'];

// --tabs
$_lang['chunk_msg'] = $_lang['chunk_tab_general_desc'];
