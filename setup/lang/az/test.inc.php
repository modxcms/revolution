<?php
/**
 * Test-related Azerbaijani Lexicon Topic for Revolution setup.
 *
 * @package setup
 * @subpackage lexicon
 */
$_lang['test_config_file'] = '<span class="mono">[[+file]]</span> faylının mövcud olub-olmadığını və yazıla biləcəyini yoxlayırıq: ';
$_lang['test_config_file_nw'] = 'Linux/Unix sistemlərində yeni quraşdırma üçün, MODX nüvəsində <span class="mono">config/</span> qovluğunda <span class="mono">[[+key]].inc.php</span> adlı boş bir fayl yaradın və PHP tərəfindən yazıla bilən olmasını təmin edin.';
$_lang['test_db_check'] = 'Verilənlər bazasına qoşulma yoxlanılır: ';
$_lang['test_db_check_conn'] = 'Qoşulma detalları yoxlayın və yenidən cəhd edin.';
$_lang['test_db_failed'] = 'Verilənlər bazasına qoşulma uğursuz oldu!';
$_lang['test_db_setup_create'] = 'Quraşdırma, verilənlər bazasını yaratmağa cəhd edəcək.';
$_lang['test_dependencies'] = 'PHP üçün zlib asılılığını yoxlayırıq: ';
$_lang['test_dependencies_fail_zlib'] = 'PHP sisteminizdə "zlib" uzantısı quraşdırılmayıb. Bu uzantı MODX-in işləməsi üçün vacibdir. Davam etmək üçün onu aktiv edin.';
$_lang['test_directory_exists'] = '<span class="mono">[[+dir]]</span> qovluğunun mövcud olub-olmadığını yoxlayırıq: ';
$_lang['test_directory_writable'] = '<span class="mono">[[+dir]]</span> qovluğunun yazıla bilən olub-olmadığını yoxlayırıq: ';
$_lang['test_memory_limit'] = 'Yaddaş limitinin ən azı 24M olub-olmadığını yoxlayırıq: ';
$_lang['test_memory_limit_fail'] = 'MODX sizin memory_limit dəyərinizin [[+memory]] olduğunu aşkarladı, bu isə tövsiyə edilən 24M dəyərindən aşağıdır. MODX memory_limit-i 24M-ə qaldırmağa cəhd etdi, lakin uğursuz oldu. Davam etməzdən əvvəl php.ini faylınızda memory_limit dəyərini ən azı 24M və ya daha yüksək təyin edin. Əgər hələ də problemlər yaşayırsaınız (məsələn, quraşdırma zamanı ağ ekran görürsünüzsə), bu dəyəri 32M, 64M və ya daha yüksək bir rəqəmə təyin edin.';
$_lang['test_memory_limit_success'] = 'OK! Dəyər: [[+memory]]';
$_lang['test_mysql_version_5051'] = 'Sizin MySQL versiyanız ([[+version]]) MODX üçün problemlər yarada bilər. Bu versiyada PDO sürücüləri ilə bağlı çoxsaylı səhvlər var. Xahiş olunur, bu problemləri həll etmək üçün MySQL-i yeniləyin.';
$_lang['test_mysql_version_client_nf'] = 'MySQL müştəri versiyası aşkar edilə bilmədi!';
$_lang['test_mysql_version_client_nf_msg'] = 'MODX mysql_get_client_info() vasitəsilə MySQL müştəri versiyanızı aşkar edə bilmədi. Davam etməzdən əvvəl MySQL müştəri versiyanızın ən azı 4.1.20 olduğuna əmin olun.';
$_lang['test_mysql_version_client_old'] = 'Çox köhnə bir MySQL müştəri versiyasından ([[+version]]) istifadə edirsiniz, bu MODX üçün problemlər yarada bilər.';
$_lang['test_mysql_version_client_old_msg'] = 'MODX bu MySQL müştəri versiyasını istifadə etməyə icazə verəcək, lakin bütün funksionallığın düzgün işləyəcəyinə zəmanət verilmir.';
$_lang['test_mysql_version_client_start'] = 'MySQL müştəri versiyasını yoxlayırıq:';
$_lang['test_mysql_version_fail'] = 'Siz MySQL [[+version]] versiyasını istifadə edirsiniz, lakin MODX Revolution üçün minimum MySQL 4.1.20 lazımdır. Xahiş olunur, MySQL-i ən azı 4.1.20 versiyasına yeniləyin.';
$_lang['test_mysql_version_server_nf'] = 'MySQL server versiyası aşkar edilə bilmədi!';
$_lang['test_mysql_version_server_nf_msg'] = 'MODX mysql_get_server_info() vasitəsilə MySQL server versiyanızı aşkar edə bilmədi. Davam etməzdən əvvəl MySQL server versiyanızın ən azı 4.1.20 olduğuna əmin olun.';
$_lang['test_mysql_version_server_start'] = 'MySQL server versiyasını yoxlayırıq:';
$_lang['test_mysql_version_success'] = 'OK! Versiya: [[+version]]';
$_lang['test_nocompress'] = 'CSS/JS sıxılmasının deaktiv edilməli olub-olmadığını yoxlayırıq: ';
$_lang['test_nocompress_disabled'] = 'OK! Deaktiv edildi.';
$_lang['test_nocompress_skip'] = 'Seçilmədi, test atlanır.';
$_lang['test_php_version_fail'] = 'Siz PHP [[+version]] versiyasını istifadə edirsiniz, lakin MODX Revolution üçün minimum PHP [[+required]] lazımdır. Xahiş olunur, PHP-ni ən azı [[+required]] versiyasına yeniləyin. Təhlükəsizlik və gələcək dəstək üçün MODX [[+recommended]] stabil versiyaya keçməyi tövsiyə edir.';
$_lang['test_php_version_start'] = 'PHP versiyasını yoxlayırıq:';
$_lang['test_php_version_success'] = 'OK! İşləyir: [[+version]]';
$_lang['test_session_gc'] = 'Sessiya tullantılarının təmizlənməsinin düzgün konfiqurasiya edilib-edilmədiyini yoxlayırıq: ';
$_lang['test_session_gc_fail'] = 'Sessiya tullantıları təmizlənmir! Cari konfiqurasiya "session.gc_probability" [[+gc_probability]] və "session.gc_divisor" [[+gc_divisor]] olaraq təyin edilib. <br>MODX standart olaraq sessiyaları verilənlər bazasında saxlayır, buna görə də bu parametrlərin düzgün tənzimlənməməsi sessiya cədvəlinin həddən artıq böyüməsinə səbəb ola bilər.';
$_lang['test_session_gc_success'] = 'OK! Cari konfiqurasiya "session.gc_probability" [[+gc_probability]] və "session.gc_divisor" [[+gc_divisor]] olaraq təyin edilib.';
$_lang['test_simplexml'] = 'SimpleXML-in mövcudluğunu yoxlayırıq:';
$_lang['test_simplexml_nf'] = 'SimpleXML aşkar edilmədi!';
$_lang['test_simplexml_nf_msg'] = 'MODX PHP mühitinizdə SimpleXML-i tapa bilmədi. Paket İdarəetməsi və digər funksiyalar SimpleXML olmadan işləməyəcək. Davam edə bilərsiniz, lakin MODX inkişaf etmiş funksiyalar üçün SimpleXML-in aktiv edilməsini tövsiyə edir.';
$_lang['test_suhosin'] = 'Suhosin problemlərini yoxlayırıq:';
$_lang['test_suhosin_max_length'] = 'Suhosin GET maksimum dəyəri çox aşağıdır!';
$_lang['test_suhosin_max_length_err'] = 'PHP suhosin uzantısından (extension-undan) istifadə edirsiniz və suhosin.get.max_value_length parametri MODX-in JS fayllarını düzgün sıxışdırması üçün çox aşağıdır. Dəyəri 4096-ya qədər artırmağı tövsiyə edirik.';
$_lang['test_table_prefix'] = 'Cədvəl prefiksini `[[+prefix]]` yoxlayırıq: ';
$_lang['test_table_prefix_inuse'] = 'Bu prefiks artıq verilənlər bazasında istifadə olunur!';
$_lang['test_table_prefix_inuse_desc'] = 'Quraşdırma seçdiyiniz verilənlər bazasına quraşdırıla bilmədi, çünki o artıq müəyyən etdiyiniz prefikslə olan cədvəlləri ehtiva edir. Zəhmət olmasa yeni bir table_prefix seçin və quraşdırmanı yenidən işlədin.';
$_lang['test_table_prefix_nf'] = 'Bu prefiks verilənlər bazasında mövcud deyil!';
$_lang['test_table_prefix_nf_desc'] = 'Quraşdırma seçilmiş verilənlər bazasına quraşdırıla bilmədi, çünki burada siz təkmilləşdirilməsi üçün göstərdiyiniz ön əlavəsi ilə mövcud cədvəllər yoxdur. Zəhmət olmasa mövcud bir table_prefix seçin və Quraşdırmanı yenidən işə salın.';
$_lang['test_zip_memory_limit'] = 'Zip genişləndirmələri (extension-ları) üçün memory limitin 24M olub-olmadığını yoxlayırıq: ';
$_lang['test_zip_memory_limit_fail'] = 'MODX memory_limit dəyərinin tövsiyə edilən 24M-dən aşağı olduğunu aşkar etdi. Zip funksiyalarının düzgün işləməsi üçün bunu artırın.';