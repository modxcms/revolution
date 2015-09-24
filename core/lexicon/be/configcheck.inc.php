<?php
/**
 * Config Check English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */
$_lang['configcheck_admin'] = 'Калі ласка, звяжыцеся з сістэмным адміністратарам і папярэдзьце яго аб гэтым паведамленні!';
$_lang['configcheck_allowtagsinpost_context_enabled'] = 'Наладка кантэксту allow_tags_in_post уключаная за межамі \'mgr\'';
$_lang['configcheck_allowtagsinpost_context_enabled_msg'] = 'Наладка allow_tags_in_post дазволена для кантэкстаў ў вашай усталёўцы за межамі кантэксту mgr. MODX рэкамендуе адключыць гэтую наладку, калі вам не трэба відавочна дазваляць карыстальнікам дасылаць MODX-тэгі, лiчбавыя сутнасці або HTML-тэгі праз метад POST у формах на вашым сайце. Яна павінна быць усюды адключаная, за выключэннем кантэксту mgr.';
$_lang['configcheck_allowtagsinpost_system_enabled'] = 'Сістэмная наладка allow_tags_in_post уключаная';
$_lang['configcheck_allowtagsinpost_system_enabled_msg'] = 'Сістэмная наладка allow_tags_in_post уключаная ў вашай ўсталёўцы. MODX рэкамендуе адключыць гэтую наладку, калі вам не трэба відавочна дазваляць карыстальнікам дасылаць MODX-тэгі, лiчбавыя сутнасці або HTML-тэгі праз POST-метад у формах на вашым сайце. Лепш уключыць яе для асобных кантэкстаў праз наладкi кантэксту.';
$_lang['configcheck_cache'] = 'Каталог кэша недаступны для запісу';
$_lang['configcheck_cache_msg'] = 'MODX не можа пісаць у каталог кэша. MODX будзе працаваць без кэшавання. Каб вырашыць гэтую праблему, зрабіце каталог "/_cache/" даступным для запісу.';
$_lang['configcheck_configinc'] = 'Канфігурацыйны файл адкрыты для запісу!';
$_lang['configcheck_configinc_msg'] = 'Ваш сайт з\'яўляецца ўразлівым для хакераў, якія могуць нанесці сур\'ёзную шкоду вашаму сайту. Калі ласка, зрабіце канфігурацыйны файл даступным толькі для чытання! Калі вы не адміністратар сайта, звяжыцеся з сістэмным адміністратарам і папярэдзьце яго аб гэтым паведамленнi! Ён размешчаны ў [[+path]]';
$_lang['configcheck_default_msg'] = 'Невядомае папярэджанне было знойдзена. Гэта дзіўна.';
$_lang['configcheck_errorpage_unavailable'] = 'Старонка "Памылка 404. Дакумент не знойдзены" недаступная.';
$_lang['configcheck_errorpage_unavailable_msg'] = 'Гэта азначае, што старонка "Памылка 404. Дакумент не знойдзены" не даступная для наведвальнікаў сайта. Гэта можа прывесці да ўзнікнення рэкурсіўнага зацыклення і шматлікіх памылак у логах сайта. Пераканайцеся ў тым, цi не абмежаваны доступ да гэтай старонцы групам web-карыстальнікаў.';
$_lang['configcheck_errorpage_unpublished'] = 'Старонка "Памылка 404. Дакумент не знойдзены" вашага сайта не апублікаваная ці не існуе.
';
$_lang['configcheck_errorpage_unpublished_msg'] = 'Гэта азначае, што старонка "Памылка 404. Дакумент не знойдзены" не даступная для наведвальнікаў сайта. Апублікуйце гэтую старонку або пераканайцеся, што яе ідэнтыфікатар правільна пазначаны ў наладках сістэмы.';
$_lang['configcheck_htaccess'] = 'Core folder is accessible by web';
$_lang['configcheck_htaccess_msg'] = 'MODX detected that your core folder is (partially) accessible to the public.
<strong>This is not recommended and a security risk.</strong>
If your MODX installation is running on a Apache webserver
you should at least set up the .htaccess file inside the core folder <em>[[+fileLocation]]</em>.
This can be easily done by renaming the existing ht.access example file there to .htaccess.
<p>There are other methods and webservers you may use, please read the <a href="https://rtfm.modx.com/revolution/2.x/administering-your-site/security/hardening-modx-revolution">Hardening MODX Guide</a>
for further information about securing your site.</p>
If you setup everything correctly, browsing e.g. to the <a href="[[+checkUrl]]" target="_blank">Changelog</a>
should give you a 403 (permission denied) or better a 404 (not found). If you can see the changelog
there in the browser, something is still wrong and you need to reconfigure or call an expert to solve this.';
$_lang['configcheck_images'] = 'Папка выяваў недаступная для запісу';
$_lang['configcheck_images_msg'] = 'Папка выяваў недаступная для запісу або не існуе. Гэта азначае, што кіраванне выявамі працаваць не будзе!';
$_lang['configcheck_installer'] = 'Ня выдаленая папка з файламі ўсталёўкі';
$_lang['configcheck_installer_msg'] = 'Папка "setup/" утрымлівае файлы ўстаноўкі MODX. Толькі ўявіце сабе, што можа адбыцца, калі зламыснік знойдзе яе і запусціць ўстаноўку! Ён, верагодна, не прасунецца далёка, таму што павінен будзе ўвесці некаторую карыстальнiцкую інфармацыю для базы дадзеных, але ўсё ж такі лепш выдаліць гэтую папку з вашага сервера. Яна размешчаны ў: [[+path]]';
$_lang['configcheck_lang_difference'] = 'Няправільная колькасць запісаў у моўным файле';
$_lang['configcheck_lang_difference_msg'] = 'Колькасць запісаў у абранай мове адрозніваецца ад колькасці запісаў у мове па змаўчанні. Гэта не з\'яўляецца крытычнай памылкай, аднак гэта падстава для абнаўлення моўных файлаў.';
$_lang['configcheck_notok'] = 'У наладках сістэмы прысутнічаюць памылкі: ';
$_lang['configcheck_ok'] = 'Праверка прайшла паспяхова — няма папярэджанняў для вываду.';
$_lang['configcheck_phpversion'] = 'PHP version is outdated';
$_lang['configcheck_phpversion_msg'] = 'Your PHP version [[+phpversion]] is no longer maintained by the PHP developers, which means no security updates are available. It is also likely that MODX or an extra package now or in the near future will no longer support this version. Please update your environment at least to PHP [[+phprequired]] as soon as possible to secure your site.';
$_lang['configcheck_register_globals'] = '"register_globals" ўстаноўлена ў становішча ON ў вашым канфігурацыйным файле php.ini';
$_lang['configcheck_register_globals_msg'] = 'Гэтая канфігурацыя робіць ваш сайт значна больш схільным Cross Site Scripting (XSS) нападам. Звяжыцеся са службай падтрымкі вашага хостынгу і спытайце, як ліквідаваць гэтую праблему.';
$_lang['configcheck_title'] = 'Праверка канфігурацыі';
$_lang['configcheck_unauthorizedpage_unavailable'] = 'Старонка "Памылка 403. Доступ забаронены" вашага сайта не апублікаваная ці не існуе.';
$_lang['configcheck_unauthorizedpage_unavailable_msg'] = 'Гэта азначае, што старонка "Памылка 403. Доступ забаронены" не даступная для наведвальнікаў сайта. Гэта можа прывесці да ўзнікнення рэкурсіўнага зацыклення і шматлікіх памылак у логах сайта. Пераканайцеся ў тым, цi не абмежаваны доступ да гэтай старонцы групам web-карыстальнікаў.';
$_lang['configcheck_unauthorizedpage_unpublished'] = 'Старонка "Памылка 403. Доступ забаронены", зададзеная ў наладках канфігурацыі сайта, не апублікаваная.';
$_lang['configcheck_unauthorizedpage_unpublished_msg'] = 'Гэта азначае, што старонка "Памылка 403. Доступ забаронены" не даступная для наведвальнікаў сайта. Апублікуйце гэтую старонку або пераканайцеся, што яе ідэнтыфікатар правільна пазначаны ў наладках сістэмы.';
$_lang['configcheck_warning'] = 'Папярэджанне аб канфігурацыі:';
$_lang['configcheck_what'] = 'Што гэта значыць?';
