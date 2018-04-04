<?php
/**
 * Test-related English Lexicon Topic for Revolution setup.
 *
 * @package setup
 * @subpackage lexicon
 */
$_lang['test_config_file'] = '<span class="mono">[[+file]]</span> mevcut ve yazılabilir olduğu denetleniyor: ';
$_lang['test_config_file_nw'] = 'Yeni Linux/Unix kurulumları için, MODX çekirdek <span class="mono">config/</span> dizininizde, izinler PHP tarafından yazılabilir şekilde ayarlanmış olarak <span class="mono">[[+key]].inc.php</span> isimli boş bir dosya oluşturun.';
$_lang['test_db_check'] = 'Veri tabanı giriş bağlantısı oluşturuluyor: ';
$_lang['test_db_check_conn'] = 'Bağlantı detaylarını kontrol edin ve yeniden deneyin.';
$_lang['test_db_failed'] = 'Veri tabanı bağlantısı başarısız!';
$_lang['test_db_setup_create'] = 'Kurulum veri tabanı oluşturmayı deneyecek.';
$_lang['test_dependencies'] = 'Zlip bağımlılığı için PHP\'yi konrol etme: ';
$_lang['test_dependencies_fail_zlib'] = 'PHP kurulumuzda bir "zlib" eklentisi kurulumu yok. Bu eklenti MODX\'i çalıştırmak için gerekli. Lütfen devam etmek için etkinleştirin.';
$_lang['test_directory_exists'] = '<span class="mono">[[+dir]]</span> mevcutluğu denetleniyor: ';
$_lang['test_directory_writable'] = '<span class="mono">[[+dir]]</span> dizininin yazılabilirliği denetleniyor: ';
$_lang['test_memory_limit'] = 'Bellek sınırının en az 24M ayarlanıp ayarlanmadığı kontrol ediliyor: ';
$_lang['test_memory_limit_fail'] = 'MODX bellek_limit ayarlarını [[+memory]]\'nda buldu, önerilen 24M değerinin altında. MODX bellek_limit\'ini otomatik olarak 24M\'e ayarlamaya çalıştı, ancak başarısız oldu. Lütfen bellek_limitini işleme başlamadan önce php.ini dosyasında en azından 24M veya yukarısı olarak ayarlayın. Eğer hala problem yaşıyorsanız (Kurulum esnasında boş beyaz ekran gibi), 32M, 64M veya daha yüksek değere ayarlayın.';
$_lang['test_memory_limit_success'] = 'Tamam! [[+memory]]\'ye ayarla';
$_lang['test_mysql_version_5051'] = 'MySQL ([[+version]]) sürümünüzdeki PDO sürücüleriyle ilgili birçok hata nedeniyle, MODX bu sürümle sorunlar yaşayacaktır. Bu problemleri düzeltmek için lütfen MySQL\'i güncelleyin. MODX\'i kullanmayı tercih etmeseniz bile, web sitenizin güvenliği ve dengesi için bu sürümü yükseltmeniz önerilir.';
$_lang['test_mysql_version_client_nf'] = 'MySQL İstemci sürümünü tespit edilemedi!';
$_lang['test_mysql_version_client_nf_msg'] = 'MODX mysql_get_client_info() aracılığıyla sizin MySQL istemci sürümünüzü tespit edemedi. Lütfen devam etmeden önce manuel olarak MySQL istemci sürümünüzün en az 4.1.20 olduğundan emin olun.';
$_lang['test_mysql_version_client_old'] = 'MySQL istemci sürümünüz çok eski olduğu için MODX çok meşgul olabilir ([[+version]])';
$_lang['test_mysql_version_client_old_msg'] = 'MODX, bu MySQL istemci sürümünü kullanarak kurulum yapmanıza izin verecektir ama MySQL istemci kütüphanelerinin eski versiyonlarını kullanırken tüm işlevselliğin kullanılabilir olacağını veya düzgün çalışacağını garanti edemiyoruz.';
$_lang['test_mysql_version_client_start'] = 'MySQL istemci sürümü kontrol ediliyor:';
$_lang['test_mysql_version_fail'] = 'MySQL[[+version]] üzerinde çalışıyorsunuz, ve MODX Revolutıon en az MySQL 4.1.20 veya daha yüksek bir sürüm gerektiriyor. Lütfen MySQL\'i en az 4.1.20 sürümüne güncelleyin.';
$_lang['test_mysql_version_server_nf'] = 'MySQL sunucu sürümünü algılayamadı!';
$_lang['test_mysql_version_server_nf_msg'] = 'MODX mysql_sucunu_bilgisi_al_bilgi() üzerinden MySQL sunucu sürümünü algılayamadı. Lütfen devam etmeden önce el ile MySQL sunucu sürümünün en az 4.1.20 olduğundan emin olun.';
$_lang['test_mysql_version_server_start'] = 'MySQL sunucu sürümü kontrol ediliyor:';
$_lang['test_mysql_version_success'] = 'Tamam! Çalışıyor: [[+version]]';
$_lang['test_nocompress'] = 'CSS/JS sıkıştırmasını devre dışı bırakıp bırakmamızı kontrol ediyoruz: ';
$_lang['test_nocompress_disabled'] = 'Tamam! Engellendi.';
$_lang['test_nocompress_skip'] = 'Seçili değil, testi geçiyor.';
$_lang['test_php_version_fail'] = 'PHP [[+version]]\'da çalışıyorsunuz ve MODX Revolution PHP [[+required]] veya üstü bir sürümü gerektiriyor. Lütfen PHP\'yi en azından [[+required]] sürümüne yükseltin. MODX, güvenlik nedenleri ve ileride destek sağlanabilmesi için mevcut kararlı dal olan [[+recommended]] sürüme yükseltilmesini önerir.';
$_lang['test_php_version_start'] = 'PHP sürümünü konrtol etme:';
$_lang['test_php_version_success'] = 'Tamam! Çalışıyor: [[+version]]';
$_lang['test_safe_mode_start'] = 'Safe_mode\'un kapalı olduğundan emin olmak için kontrol etme:';
$_lang['test_safe_mode_fail'] = 'MODX güvenlik_modu\'nu etkin gördü. Devam edebilmek için PHP ayarlarından güvenlik_modu\'nu devre dışı bırakmanız gerekir.';
$_lang['test_sessions_start'] = 'Oturumların düzgün şekilde yapılandırılıp yapılandırılmadığını kontrol etme:';
$_lang['test_simplexml'] = 'SimpleXML\'i kontrol et:';
$_lang['test_simplexml_nf'] = 'SimpleXML bulunamadı!';
$_lang['test_simplexml_nf_msg'] = 'MODX, PHP ortamınızda SimpleXML\'yi bulamadı. Paket Yönetimi ve diğer fonksiyonlar bu yüklü olmadan çalışmayacaklar. Yüklemeden devam edebilirsiniz, ancak MODX gelişmiş özellikler ve kullanışlılık için SimpleXML\'yi etkin hale getirmenizi önerir.';
$_lang['test_suhosin'] = 'Suhosin sorunları için kontrol ediliyor:';
$_lang['test_suhosin_max_length'] = 'Suhosin GET maksimum değeri çok düşük!';
$_lang['test_suhosin_max_length_err'] = 'Şu anda, PHP suhosin eklentisini kullanıyorsun, ve seninsuhosin.get.max_value_length değerin MODX\'in JS dosyalarını yönetici\'de sıkıştırmak için çok düşük. MODX bu değeri 4096\'ya çekmeni önerir; o zamana kadar, MODX otomatik olarak JS sıkıştırmalarını (compress_js setting) hataları önlemek için 0\'a ayarlayacaktır.';
$_lang['test_table_prefix'] = 'Tablo öncüleri kontrol ediliyor `[[+prefix]]`: ';
$_lang['test_table_prefix_inuse'] = 'Tablo öneki zaten bu veritabanında kullanılıyor!';
$_lang['test_table_prefix_inuse_desc'] = 'Önceden belirttiğiniz önek ile tablolar içerdiğinden kur seçili veritabanına yükleyemedi. Lütfen yeni bir tablo_öneki seçin ve Kur\'u yeniden çalıştırın.';
$_lang['test_table_prefix_nf'] = 'Tablo öneki bu veritabanında yok!';
$_lang['test_table_prefix_nf_desc'] = 'Yükseltme için belirtilen önek ile varolan tablolar içermediği gibi kur, seçili veritabanına yükleyemedi. Lütfen varolan bir tablo_öneki seçin ve Kur\'u yeniden çalıştırın.';
$_lang['test_zip_memory_limit'] = 'Zip uzantıları için bellek sınırının en az 24M olup olmadığını kontrol etme: ';
$_lang['test_zip_memory_limit_fail'] = 'MODX bellek_limit ayarlarının tavsiye edilen 24M değerinin altında olduğu tespit eti. MODX bellek_limit değerini 24M yapmaya çalıştı, fakat başarılı olamadı. Lütfen devam etmeden önce php.ini dosyanızın altındaki bellek_limit ayarını 24M veya daha yüksek bir değere çıkarın, böylece zip eklentisi düzgün bir şekilde çalışabilir.';