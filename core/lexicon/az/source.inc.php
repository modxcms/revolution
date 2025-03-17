<?php
/**
 * Sources Azerbaijani lexicon topic
 *
 * @language az
 * @package modx
 * @subpackage lexicon
 */
$_lang['access'] = 'Giriş icazələri';
$_lang['base_path'] = 'Əsas yol';
$_lang['base_path_relative'] = 'Əsas yol nisbətlidir?';
$_lang['base_url'] = 'Əsas URL';
$_lang['base_url_relative'] = 'Əsas URL nisbətlidir?';
$_lang['minimum_role'] = 'Minimum rol';
$_lang['path_options'] = 'Yol seçimləri';
$_lang['policy'] = 'Siyasət';
$_lang['source'] = 'Media mənbəyi';
$_lang['source_access_add'] = 'İstifadəçi qrupunu əlavə et';
$_lang['source_access_remove'] = 'Girişi sil';
$_lang['source_access_remove_confirm'] = 'Bu istifadəçi qrupu üçün bu mənbəyə giriş icazəsini silmək istədiyinizə əminsiniz?';
$_lang['source_access_update'] = 'Girişi redaktə et';
$_lang['source_description_desc'] = 'Media mənbəyi üçün qısa təsvir.';
$_lang['source_err_ae_name'] = 'Bu adda media mənbəyi artıq mövcuddur! Zəhmət olmasa yeni bir ad təyin edin.';
$_lang['source_err_nf'] = 'Media mənbəyi tapılmadı!';
$_lang['source_err_init'] = '"[[+source]]" media mənbəyi başladılmadı!';
$_lang['source_err_nfs'] = 'Bu ID ilə media mənbəyi tapılmadı: [[+id]].';
$_lang['source_err_ns'] = 'Zəhmət olmasa media mənbəyini seçin.';
$_lang['source_err_ns_name'] = 'Zəhmət olmasa media mənbəyi üçün ad təyin edin.';
$_lang['source_name_desc'] = 'Media mənbəyinin adı.';
$_lang['source_properties.intro_msg'] = 'Aşağıda bu mənbə üçün xüsusiyyətləri idarə edin.';
$_lang['source_remove_confirm'] = 'Bu media mənbəyini silmək istədiyinizə əminsiniz? Bu, bu mənbəyə təyin edilmiş hər hansı TV-ləri poza bilər.';
$_lang['source_remove_multiple_confirm'] = 'Bu media mənbələrini silmək istədiyinizə əminsiniz? Bu, bu mənbələrə təyin edilmiş hər hansı TV-ləri poza bilər.';
$_lang['source_type'] = 'Mənbə növü';
$_lang['source_type_desc'] = 'Media mənbəyinin növü və ya sürücüsü. Mənbə bu sürücüdən istifadə edərək məlumatları əldə edəcək. Məsələn: Fayl sistemi server fayllarını götürəcək. S3 isə S3 bulud anbarından məlumat çəkəcək.';
$_lang['source_type.file'] = 'Fayl sistemi';
$_lang['source_type.file_desc'] = 'Serverinizdəki faylları idarə edən fayl sistemi əsaslı mənbə.';
$_lang['source_type.s3'] = 'Amazon S3';
$_lang['source_type.s3_desc'] = 'Amazon S3 bulud anbarında naviqasiya edir.';
$_lang['source_type.ftp'] = 'Fayl Transfer Protokolu (FTP)';
$_lang['source_type.ftp_desc'] = 'FTP uzaq serverində naviqasiya edir.';
$_lang['source_types'] = 'Mənbə növləri';
$_lang['source_types.intro_msg'] = 'Bu, MODX sisteminizdə quraşdırılmış bütün media mənbə növlərinin siyahısıdır.';
$_lang['source.access.intro_msg'] = 'Burada media mənbəyinə müəyyən istifadəçi qruplarının girişini məhdudlaşdıra və həmin qruplar üçün siyasətlər təyin edə bilərsiniz. Hər hansı istifadəçi qrupuna qoşulmamış media mənbəyi bütün idarəçilər üçün əlçatandır.';
$_lang['sources'] = 'Media mənbələri';
$_lang['sources.intro_msg'] = 'Bütün media mənbələrinizi burada idarə edin.';
$_lang['user_group'] = 'İstifadəçi qrupu';

/* fayl mənbə növü */
$_lang['allowedFileTypes'] = 'icazə verilənFaylNövləri';
$_lang['prop_file.allowedFileTypes_desc'] = 'Əgər təyin olunubsa, yalnız göstərilən genişlənmələrə malik faylların görünməsinə icazə veriləcək. Genişlənmələri nöqtəsiz və vergüllə ayrılmış siyahı şəklində daxil edin.';
$_lang['basePath'] = 'əsasYol';
$_lang['prop_file.basePath_desc'] = 'Mənbənin göstəriləcəyi fayl yolu, məsələn: assets/images/<br>Yol "basePathRelative" parametrindən asılı ola bilər.';
$_lang['basePathRelative'] = 'əsasYolNisbidir';
$_lang['prop_file.basePathRelative_desc'] = 'Əgər yuxarıdakı Əsas Yol MODX quraşdırma yoluna nisbətən deyilsə, bunu "Xeyr" olaraq təyin edin.';
$_lang['baseUrl'] = 'əsasURL';
$_lang['prop_file.baseUrl_desc'] = 'Bu mənbəyə daxil olmaq üçün URL, məsələn: assets/images/<br>Yol "baseUrlRelative" parametrindən asılı ola bilər.';
$_lang['baseUrlPrependCheckSlash'] = 'əsasURLÖndənYoxla';
$_lang['prop_file.baseUrlPrependCheckSlash_desc'] = 'Əgər bu parametr aktiv edilibsə, MODX yalnız əsasURL-ni əlavə edəcək, əgər URL-in əvvəlində irəliyə meylli (/) slash yoxdursa. TV dəyərini əsasURL xaricində təyin etmək üçün faydalıdır.';
$_lang['baseUrlRelative'] = 'əsasURLNisbidir';
$_lang['prop_file.baseUrlRelative_desc'] = 'Əgər yuxarıdakı Əsas URL MODX quraşdırma URL-ə nisbətən deyilsə, bunu "Xeyr" olaraq təyin edin.';
$_lang['imageExtensions'] = 'şəkilGenişlənmələri';
$_lang['prop_file.imageExtensions_desc'] = 'Şəkillər üçün istifadə ediləcək fayl genişlənmələrinin vergüllə ayrılmış siyahısı. MODX bu genişlənmələrə malik faylların kiçik görüntülərini yaratmağa çalışacaq.';
$_lang['skipFiles'] = 'gizlədiləcəkFayllar';
$_lang['prop_file.skipFiles_desc'] = 'Vergüllə ayrılmış siyahı. MODX bu siyahıya uyğun gələn fayl və qovluqları gizlədəcək.';
$_lang['skipExtensions'] = 'gizlədiləcəkGenişlənmələr';
$_lang['prop_file.skipExtensions'] = 'Vergüllə ayrılmış genişlənmələr siyahısı. MODX bu genişlənmələrə malik faylları göstərməyəcək.';
$_lang['thumbnailQuality'] = 'kiçikŞəkilKeyfiyyəti';
$_lang['prop_file.thumbnailQuality_desc'] = 'Yaradılan kiçik şəkillərin keyfiyyəti, 0-100 arasında bir dəyər.';
$_lang['thumbnailType'] = 'kiçikŞəkilNövü';
$_lang['prop_file.thumbnailType_desc'] = 'Kiçik şəkillərin hansı növdə yaradılacağını təyin edir.';
$_lang['prop_file.visibility_desc'] = 'Yeni fayl və qovluqlar üçün standart görünürlük parametri.';
$_lang['no_move_folder'] = 'Media Mənbə sürücüsü hazırda qovluqların köçürülməsini dəstəkləmir.';

/* S3 mənbə növü */
$_lang['bucket'] = 'Bulud anbarı (Bucket)';
$_lang['prop_s3.bucket_desc'] = 'Verilənlərin yüklənəcəyi Amazon S3 bulud anbarı.';
$_lang['prop_s3.key_desc'] = 'Bulud anbarına giriş üçün Amazon açarı.';
$_lang['prop_s3.imageExtensions_desc'] = 'Şəkil kimi istifadə ediləcək fayl uzantılarının vergüllə ayrılmış siyahısı. MODX bu uzantılara malik faylların kiçik önizləmələrini (thumbnail) yaratmağa çalışacaq.';
$_lang['prop_s3.secret_key_desc'] = 'Bulud anbarına giriş üçün Amazon gizli açarı.';
$_lang['prop_s3.skipFiles_desc'] = 'Vergüllə ayrılmış siyahı. MODX bu faylları və qovluqları görməzlikdən gələcək və gizlədəcək.';
$_lang['prop_s3.thumbnailQuality_desc'] = 'Kiçik önizləmələrin keyfiyyəti (0-100 arası miqyasda).';
$_lang['prop_s3.thumbnailType_desc'] = 'Kiçik önizləmələrin yaradılacağı şəkil formatı.';
$_lang['prop_s3.url_desc'] = 'Amazon S3 ünvanı.';
$_lang['prop_s3.endpoint_desc'] = 'Alternativ S3 uyğun ünvanı, məsələn, "https://s3.<region>.example.com".';
$_lang['prop_s3.region_desc'] = 'Vedrənin yerləşdiyi bölgə. Məsələn: us-west-1';
$_lang['prop_s3.prefix_desc'] = 'İsteğe bağlı yol/qovluq prefiksi.';
$_lang['prop_s3.no_check_bucket_desc'] = 'Əgər aktiv edilsə, vedrənin mövcud olub-olmadığını yoxlamayacaq. Bu, əgər istifadə etdiyiniz giriş açarının vedrə yaratmaq/siyahılamaq icazəsi yoxdursa, lazım ola bilər.';
$_lang['s3_no_move_folder'] = 'Hazırda S3 sürücüsü qovluqların köçürülməsini dəstəkləmir.';

/* FTP mənbə növü */
$_lang['prop_ftp.host_desc'] = 'Serverin host adı və ya IP ünvanı.';
$_lang['prop_ftp.username_desc'] = 'Giriş üçün istifadəçi adı. "anonymous" da ola bilər.';
$_lang['prop_ftp.password_desc'] = 'İstifadəçi şifrəsi. Anonim istifadəçi üçün boş buraxın.';
$_lang['prop_ftp.url_desc'] = 'Əgər FTP serverinin açıq URL-i varsa, onu burada daxil edə bilərsiniz.';
$_lang['prop_ftp.port_desc'] = 'Serverin port nömrəsi, standart 21-dir.';
$_lang['prop_ftp.root_desc'] = 'Bağlandıqdan sonra açılacaq əsas qovluq.';
$_lang['prop_ftp.passive_desc'] = 'Passiv FTP rejimini aktivləşdir və ya deaktiv et.';
$_lang['prop_ftp.ssl_desc'] = 'SSL bağlantısını aktivləşdir və ya deaktiv et.';
$_lang['prop_ftp.timeout_desc'] = 'Bağlantı üçün vaxt limiti (saniyə).';

/* file type */
$_lang['PNG'] = 'PNG';
$_lang['JPG'] = 'JPG';
$_lang['GIF'] = 'GIF';
$_lang['WebP'] = 'WebP';
