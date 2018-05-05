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
$_lang['configcheck_cache_msg'] = 'MODX önbellek dizinine yazamaz. MODX yine de beklendiği gibi işlevini yerine getirecek, ancak önbelleğe alma gerçekleşmeyecek. Bunu çözmek için /_cache/ dizinini yazılabilir yapın.';
$_lang['configcheck_configinc'] = 'Yapılandırma dosyası hala yazılabilir!';
$_lang['configcheck_configinc_msg'] = 'Sitenize çok fazla zarar verebilecek hacker\'lara karşı savunmasızsınız. Lütfen yapılandırma dosyanızı salt-okunur yapın! Eğer site yöneticisi değilseniz, lütfen bir sistem yöneticisine başvurun ve onları bu mesaj hakkında uyarın! [[+path]]\'da bulunmakta';
$_lang['configcheck_default_msg'] = 'Belirtilmemiş bir uyarı bulundu. Bu garip.';
$_lang['configcheck_errorpage_unavailable'] = 'Sitenizin Hata sayfası kullanılamıyor.';
$_lang['configcheck_errorpage_unavailable_msg'] = 'Bunun anlamı, hata sayfanıza normal web sörfçüleri erişemiyor veya mevcut değil. Bu yinelemeli bir döngüye neden olabilir ve sitenizdeki günlüklerde birçok hata oluşabilir. Sayfaya atanmış web kullanıcı grubunun olmadığından emin olun.';
$_lang['configcheck_errorpage_unpublished'] = 'Sitenizin hata sayfası yayınlanmamış veya mevcut değil.';
$_lang['configcheck_errorpage_unpublished_msg'] = 'Bunun anlamı hata sayfanızın kamu tarafından erişilemediği. Sayfayı yayınlayın veya Sistem &gt; Sistem Ayarları menüsündeki site ağacınızdaki mevcut bir belgeye atandığından emin olun.';
$_lang['configcheck_htaccess'] = 'Çekirdek klasöre web üzerinden erişilebilir';
$_lang['configcheck_htaccess_msg'] = 'MODX senin çekirdek dosyanın(bir kısmına) dışarıdan erişilebildiğini fark etti.<strong>Bu önerilmez ve güvenlik riski taşır.</strong>
MODX yüklemesi bir Aphace web sunucusunu üzerinde kuruluyor ise en azından çekirdek klasörünün <em>[[+fileLocation]]</em> içindeki .htaccess dosyasını ayarlamalısın.
Bu kolay bir şekilde ht.access dosyasının ismini .htaccess olarak değiştirilerek ayarlanabilir.
<p>Kullanabileceğiniz diğer yöntemler ve web sunucuları vardır. Lütfen sitenizin güvenliğini hakkında daha fazla bilgi almak için burayı okuyun <a href="https://rtfm.modx.com/revolution/2.x/administering-your-site/security/hardening-modx-revolution"> MODX Sertleştirme Rehberi</a>.</p>
Eğer her şeyi düzgün bir şekilde ayarladıysanız, tarayıcınızdan <a href="[[+checkUrl]]" target="_blank">Changelog</a> girdiğinizde size 403(izin reddedildi) veya daha iyisi 404 (bulunamadı) karşınıza çıkacaktır. Eğer tarayıcınızda değişim günlüğü sayfasını görürseniz, birşeyler hala yanlış demektir ve bunu çözmek için tekrar ayarlamaya veya bir uzmana ihtiyacınız var.';
$_lang['configcheck_images'] = 'Resimler dizini yazılabilir değil';
$_lang['configcheck_images_msg'] = 'Resimler dizini yazılabilir değil, veya mevcut değil. Bu düzenleyicideki yönetici işlevleri çalışmayacak anlamına gelir!';
$_lang['configcheck_installer'] = 'Yükleyici hala mevcut';
$_lang['configcheck_installer_msg'] = 'Kurulum/ dizininde MODX için yükleyici bulunur. Eğer kötü bir kişi bu klasörü bulup yükleyiciyi çalıştırırsa ne olacağını hayal edin! Muhtemelen çok ileri gidemeyecekler, çünkü veritabanı için bazı kullanıcı bilgilerini girmeleri gerekir, ancak bu klasörü sunucudan kaldırmak hala en iyisidir.
Bulunduğu yer: [[+path]]';
$_lang['configcheck_lang_difference'] = 'Dil dosyasındaki girdilerin sayısı yanlış';
$_lang['configcheck_lang_difference_msg'] = 'Seçili dil varsayılan dilden farklı sayıda girdi içeriyor. Her ne kadar bir sorun değilse de, bu dil dosyasının güncelleneceği anlamına gelebilir.';
$_lang['configcheck_notok'] = 'Bir veya birkaç yapılandırma detayları kontrol edilmedi:';
$_lang['configcheck_ok'] = 'Kontrol tamamlandı - rapor etmek için uyarı yok.';
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
