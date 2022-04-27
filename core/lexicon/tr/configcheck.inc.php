<?php
/**
 * Config Check English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */
$_lang['configcheck_admin'] = 'Lütfen sistem yöneticisine başvurarak onları bu mesaj hakkında uyarın!';
$_lang['configcheck_allowtagsinpost_context_enabled'] = 'izin vermek_etiket_için_posta Durum Ayarı dışında Etkin `mgr`';
$_lang['configcheck_allowtagsinpost_context_enabled_msg'] = 'Allow_tags_in_post içerik ayarı, kurulumunuzda mgr içeriği dışında etkinleştirilmiştir. MODX, sitenizdeki bir forma POST yöntemi aracılığıyla MODX etiketleri, sayısal öğeler veya HTML komut dosyası etiketleri göndermesine kullanıcıların açıkça izin vermediği sürece bu ayarı devre dışı bırakmanızı önerir. Bu genellikle mgr içeriği dışında devre dışı bırakılmalıdır.';
$_lang['configcheck_allowtagsinpost_system_enabled'] = 'allow_tags_in_post Sistem Ayarı Etkin';
$_lang['configcheck_allowtagsinpost_system_enabled_msg'] = 'Sistem Ayarı allow_tags_in_post yükleme işleminde etkinleştirilmiştir. MODX sitenizdeki bir forma POST yöntemi aracılığıyla MODX etiketleri, sayısal öğeler veya HTML komut dosyası etiketleri kullanıcıların göndermesine açıkça izin vermedikleri sürece bu ayarı devre dışı bırakmanızı önerir. Belirli içerikler için İçerik Ayarları aracılığıyla etkinleştirmek daha iyidir.';
$_lang['configcheck_cache'] = 'Önbellek dizini yazılabilir değil';
$_lang['configcheck_cache_msg'] = 'MODX cannot write to the cache directory. MODX will still function as expected, but no caching will take place. To solve this, make the /cache/ directory writable.';
$_lang['configcheck_configinc'] = 'Yapılandırma dosyası hala yazılabilir!';
$_lang['configcheck_configinc_msg'] = 'Sitenize çok fazla zarar verebilecek hacker\'lara karşı savunmasızsınız. Lütfen yapılandırma dosyanızı salt-okunur yapın! Eğer site yöneticisi değilseniz, lütfen bir sistem yöneticisine başvurun ve onları bu mesaj hakkında uyarın! [[+path]]\'da bulunmakta';
$_lang['configcheck_default_msg'] = 'Belirtilmemiş bir uyarı bulundu. Bu garip.';
$_lang['configcheck_errorpage_unavailable'] = 'Sitenizin Hata sayfası kullanılamıyor.';
$_lang['configcheck_errorpage_unavailable_msg'] = 'Bunun anlamı, hata sayfanıza normal web sörfçüleri erişemiyor veya mevcut değil. Bu yinelemeli bir döngüye neden olabilir ve sitenizdeki günlüklerde birçok hata oluşabilir. Sayfaya atanmış web kullanıcı grubunun olmadığından emin olun.';
$_lang['configcheck_errorpage_unpublished'] = 'Sitenizin hata sayfası yayınlanmamış veya mevcut değil.';
$_lang['configcheck_errorpage_unpublished_msg'] = 'Bunun anlamı hata sayfanızın kamu tarafından erişilemediği. Sayfayı yayınlayın veya Sistem &gt; Sistem Ayarları menüsündeki site ağacınızdaki mevcut bir belgeye atandığından emin olun.';
$_lang['configcheck_htaccess'] = 'Çekirdek klasöre web üzerinden erişilebilir';
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
$_lang['configcheck_images'] = 'Resimler dizini yazılabilir değil';
$_lang['configcheck_images_msg'] = 'Resimler dizini yazılabilir değil, veya mevcut değil. Bu düzenleyicideki yönetici işlevleri çalışmayacak anlamına gelir!';
$_lang['configcheck_installer'] = 'Yükleyici hala mevcut';
$_lang['configcheck_installer_msg'] = 'The setup/ directory contains the installer for MODX. Just imagine what might happen if an evil person finds this folder and runs the installer! They probably won\'t get too far, because they\'ll need to enter some user information for the database, but it\'s still best to delete this folder from your server. It is located at: [[+path]]';
$_lang['configcheck_lang_difference'] = 'Dil dosyasındaki girdilerin sayısı yanlış';
$_lang['configcheck_lang_difference_msg'] = 'Seçili dil varsayılan dilden farklı sayıda girdi içeriyor. Her ne kadar bir sorun değilse de, bu dil dosyasının güncelleneceği anlamına gelebilir.';
$_lang['configcheck_notok'] = 'Bir veya birkaç yapılandırma detayları kontrol edilmedi:';
$_lang['configcheck_phpversion'] = 'PHP sürümü güncel değil';
$_lang['configcheck_phpversion_msg'] = 'Kullandığın PHP sürümü [[+phpversion]] artık PHP geliştiricileri tarafından sürdürülmemektedir. buda güvenlik güncelleştirmeleri olmayacak manasına gelmekte. Ayrıca MODX veya bir extra pakette şimdi veya yakın gelecekte bu sürümü desteklemeyecek. Lütfen sitenizin güvenliğini en çabuk şekilde sağlamak için ortamınızı en az PHP [[+phprequired]] olacak şekilde güncelleyin.';
$_lang['configcheck_register_globals'] = 'php.ini yapılandırma dosyanızda register_globals AÇIK olarak ayarlanmıştır';
$_lang['configcheck_register_globals_msg'] = 'Bu yapılandırma, sitenizin Cross Site Scripting (XSS) saldırılarına karşı daha hassas olmasını sağlar. Bu ayarı devre dışı bırakmak için neler yapabileceği konusunda ana bilgisayarınızla temas kurmalısınız.';
$_lang['configcheck_title'] = 'Yapılandırma kontrolü';
$_lang['configcheck_unauthorizedpage_unavailable'] = 'Sitenizin Yetkisiz sayfası yayınlanmadı veya bulunmamaktadır.';
$_lang['configcheck_unauthorizedpage_unavailable_msg'] = 'Bu Yetkili olmayan sayfanızın normal web sörfçülerince erişilebilir veya mevcut olmadığı anlamına gelir. Bu özyinelemeli bir döngüye neden olabilir ve sitenizdeki günlüklerinde birçok hata oluşturabilir. Sayfaya atanmış web kullanıcı grubu olmadığından emin olun.';
$_lang['configcheck_unauthorizedpage_unpublished'] = 'Yetkisiz sayfa tanımlaması site yapılandırması ayarlarında yayımlanamaz.';
$_lang['configcheck_unauthorizedpage_unpublished_msg'] = 'Bunun anlamı yetkisiz sayfanızın kamu tarafından erişilemediği. Sayfayı yayınlayın veya Sistem &gt; Sistem Ayarları menüsündeki site ağacınızdaki mevcut bir belgeye atandığından emin olun.';
$_lang['configcheck_warning'] = 'Yapılandırma uyarısı:';
$_lang['configcheck_what'] = 'Ne anlama geliyor?';
