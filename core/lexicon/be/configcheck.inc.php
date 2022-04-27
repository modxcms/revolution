<?php
/**
 * Config Check English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */
$_lang['configcheck_admin'] = 'Калі ласка, звяжыцеся з сістэмным адміністратарам і папярэдзьце яго аб гэтым паведамленні!';
$_lang['configcheck_allowtagsinpost_context_enabled'] = 'Налада кантэксту allow_tags_in_post уключана за межамі \'mgr\'';
$_lang['configcheck_allowtagsinpost_context_enabled_msg'] = 'Налада allow_tags_in_post дазволена для кантэкстаў ў вашай усталёўцы за межамі кантэксту mgr. MODX рэкамендуе адключыць гэтую наладу, калі вам не трэба відавочна дазваляць карыстальнікам дасылаць MODX-тэгі, лiчбавыя сутнасці або HTML-тэгі праз метад POST у формах на вашым сайце. Яна павінна быць усюды адключана, за выключэннем кантэксту mgr.';
$_lang['configcheck_allowtagsinpost_system_enabled'] = 'Сістэмная налада allow_tags_in_post уключана';
$_lang['configcheck_allowtagsinpost_system_enabled_msg'] = 'Сістэмная налада allow_tags_in_post уключана ў вашай ўсталёўцы. MODX рэкамендуе адключыць гэтую наладу, калі вам не патрэбна відавочна дазваляць карыстальнікам дасылаць MODX-тэгі, лiчбавыя сутнасці або HTML-тэгі праз POST-метад у формах на вашым сайце. Лепш уключыць яе для асобных кантэкстаў праз налады кантэксту.';
$_lang['configcheck_cache'] = 'Каталог кэша недаступны для запісу';
$_lang['configcheck_cache_msg'] = 'MODX cannot write to the cache directory. MODX will still function as expected, but no caching will take place. To solve this, make the /cache/ directory writable.';
$_lang['configcheck_configinc'] = 'Канфігурацыйны файл адкрыты для запісу!';
$_lang['configcheck_configinc_msg'] = 'Ваш сайт з\'яўляецца ўразлівым для хакераў, якія могуць нанесці сур\'ёзную шкоду вашаму сайту. Калі ласка, зрабіце канфігурацыйны файл даступным толькі для чытання! Калі вы не адміністратар сайта, звяжыцеся з сістэмным адміністратарам і папярэдзьце яго аб гэтым паведамленнi! Файл размешчаны ў [[+path]]';
$_lang['configcheck_default_msg'] = 'Невядомае папярэджанне было знойдзена. Гэта дзіўна.';
$_lang['configcheck_errorpage_unavailable'] = 'Старонка "Памылка 404. Дакумент не знойдзены" недаступная.';
$_lang['configcheck_errorpage_unavailable_msg'] = 'Гэта азначае, што старонка "Памылка 404. Дакумент не знойдзены" не даступная для наведвальнікаў сайта або не існуе. Гэта можа прывесці да ўзнікнення рэкурсіўнага зацыклення і шматлікіх памылак у журнале памылак сайта. Пераканайцеся ў тым, што доступ для web-карыстальнікаў не абмежаваны да гэтай старонкі.';
$_lang['configcheck_errorpage_unpublished'] = 'Старонка "Памылка 404. Дакумент не знойдзены" вашага сайта не апублікаваная ці не існуе.
';
$_lang['configcheck_errorpage_unpublished_msg'] = 'Гэта азначае, што старонка "Памылка 404. Дакумент не знойдзены" не даступна для наведвальнікаў сайта. Апублікуйце гэтую старонку або пераканайцеся, што яе ідэнтыфікатар правільна пазначаны ў наладах сістэмы.';
$_lang['configcheck_htaccess'] = 'Каталог ядра даступны для ўсіх';
$_lang['configcheck_htaccess_msg'] = 'MODX detected that your core folder is (partially) accessible to the public.
<strong>This is not recommended and a security risk.</strong>
If your MODX installation is running on a Apache webserver
you should at least set up the .htaccess file inside the core folder <em>[[+fileLocation]]</em>.
This can be easily done by renaming the existing ht.access example file there to .htaccess.
<p>There are other methods and webservers you may use, please read the <a href="https://docs.modx.com/3.x/en/getting-started/maintenance/securing-modx">Hardening MODX Guide</a>
for further information about securing your site.</p>
If you setup everything correctly, browsing e.g. to the <a href="[[+checkUrl]]" target="_blank">Changelog</a>
should give you a 403 (permission denied) or better a 404 (not found). If you can see the changelog
there in the browser, something is still wrong and you need to reconfigure or call an expert to solve this.';
$_lang['configcheck_images'] = 'Папка выяваў недаступная для запісу';
$_lang['configcheck_images_msg'] = 'Каталог для выяваў недаступны для запісу або не існуе. Гэта азначае, што кіраванне выявамі працаваць не будзе!';
$_lang['configcheck_installer'] = 'Не выдалена папка з файламі ўсталёўкі';
$_lang['configcheck_installer_msg'] = 'The setup/ directory contains the installer for MODX. Just imagine what might happen if an evil person finds this folder and runs the installer! They probably won\'t get too far, because they\'ll need to enter some user information for the database, but it\'s still best to delete this folder from your server. It is located at: [[+path]]';
$_lang['configcheck_lang_difference'] = 'Няправільная колькасць запісаў у моўным файле';
$_lang['configcheck_lang_difference_msg'] = 'Колькасць запісаў у абранай мове адрозніваецца ад колькасці запісаў у мове па змаўчанні. Гэта не з\'яўляецца крытычнай памылкай, аднак гэта падстава для абнаўлення моўных файлаў.';
$_lang['configcheck_notok'] = 'У наладах сістэмы прысутнічаюць памылкі: ';
$_lang['configcheck_phpversion'] = 'Версія PHP састарэла';
$_lang['configcheck_phpversion_msg'] = 'Ваша версія PHP [[+phpversion]] больш не падтрымліваецца распрацоўнікамі PHP, што азначае аніякіх абнаўленняў бяспекі. Таксама верогадна, што MODX ці пакет дадання цяпер ці ў бліжэйшай будучыні больш не будзе падтрымлівать гэтую версію. Калі ласка абнавіце атачэнне па меншай меры да PHP [[+phprequired]] як мага хутчэй каб забяспечыць бяспеку сайта.';
$_lang['configcheck_register_globals'] = '"register_globals" ўстаноўлена ў становішча ON ў вашым канфігурацыйным файле php.ini';
$_lang['configcheck_register_globals_msg'] = 'Такая канфігурацыя робіць ваш сайт значна больш схільным да Cross Site Scripting (XSS) нападаў. Звяжыцеся са службай падтрымкі вашага хостынгу і спытайцеся, як ліквідаваць гэтую праблему.';
$_lang['configcheck_title'] = 'Праверка канфігурацыі';
$_lang['configcheck_unauthorizedpage_unavailable'] = 'Старонка "Памылка 403. Доступ забаронены" вашага сайта не апублікавана ці не існуе.';
$_lang['configcheck_unauthorizedpage_unavailable_msg'] = 'Гэта азначае, што старонка "Памылка 403. Доступ забаронены" не даступна для наведвальнікаў сайта. Гэта можа прывесці да ўзнікнення рэкурсіўнага зацыклення і шматлікіх памылак у логах сайта. Пераканайцеся ў тым, цi не абмежаваны доступ да гэтай старонкі групам web-карыстальнікаў.';
$_lang['configcheck_unauthorizedpage_unpublished'] = 'Старонка "Памылка 403. Доступ забаронены", пазначаная ў наладах канфігурацыі сайта, не апублікавана.';
$_lang['configcheck_unauthorizedpage_unpublished_msg'] = 'Гэта азначае, што старонка "Памылка 403. Доступ забаронены" не даступна для наведвальнікаў сайта. Апублікуйце гэтую старонку або пераканайцеся, што яе ідэнтыфікатар правільна пазначаны ў наладах сістэмы.';
$_lang['configcheck_warning'] = 'Папярэджанне аб канфігурацыі:';
$_lang['configcheck_what'] = 'Што гэта значыць?';
