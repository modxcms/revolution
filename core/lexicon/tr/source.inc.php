<?php
/**
 * Sources English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */
$_lang['access'] = 'Erişim İzinleri';
$_lang['base_path'] = 'Ana Yol';
$_lang['base_path_relative'] = 'Göreceli Temel Yol?';
$_lang['base_url'] = 'Temel URL';
$_lang['base_url_relative'] = 'Göreceli Temel URL?';
$_lang['minimum_role'] = 'Minimum Rol';
$_lang['path_options'] = 'Yol Seçenekleri';
$_lang['policy'] = 'Politika';
$_lang['source'] = 'Medya kaynağı';
$_lang['source_access_add'] = 'Kullanıcı Grubu Ekle';
$_lang['source_access_remove'] = 'Erişimi Kaldır';
$_lang['source_access_remove_confirm'] = 'Bu Kullanıcı Grubunun bu kaynağa erişimini engellemek istediğinize emin misiniz?';
$_lang['source_access_update'] = 'Erişimi Güncelle';
$_lang['source_create'] = 'Yeni Medya Kaynağı Yarat';
$_lang['source_description_desc'] = 'Medya Kaynağının kısa bir açıklaması.';
$_lang['source_duplicate'] = 'Medya Kaynağını kopyala';
$_lang['source_err_ae_name'] = 'Bu isimde bir Medya Kaynağı zaten var. Lütfen yeni bir isim belirtin.';
$_lang['source_err_nf'] = 'Medya Kaynağı bulunamadı!';
$_lang['source_err_nfs'] = 'Bu kimlikte bir Medya Kaynağı bulunamadı: [[+id]].';
$_lang['source_err_ns'] = 'Lütfen bir Medya Kaynağı belirtin.';
$_lang['source_err_ns_name'] = 'Medya Kaynağı için lütfen bir isim belirtin.';
$_lang['source_name_desc'] = 'Medya Kaynağının ismi.';
$_lang['source_properties.intro_msg'] = 'Aşağıdaki kaynak için özellikleri yönetin.';
$_lang['source_remove'] = 'Medya Kaynağını Sil';
$_lang['source_remove_confirm'] = 'Bu medya kaynağını kaldırmak istediğinize emin misiniz? Bunu yapmak, bu kaynak için atadığınız şablon değişkenlerinizi bozabilir.';
$_lang['source_remove_multiple'] = 'Çoklu Medya Kaynaklarını Sil';
$_lang['source_remove_multiple_confirm'] = 'Bu medya kaynaklarını silmek istediğinize emin misiniz? Bunu yapmak, bu kaynaklar için atadığınız şablon değişkenlerinizi bozabilir.';
$_lang['source_update'] = 'Meya kaynağını gücelle';
$_lang['source_type'] = 'Kaynak türü';
$_lang['source_type_desc'] = 'Medya kaynağının türü, ya da sürücüsü. Kaynak, bu sürücüyü, verilerini toplarken bağlanmak için kullanacaktır. Örneğin: Dosya Sistemi dosyaları dosya sisteminden alacaktır. S3, S3 kovasından dosyalarını çekecektir.';
$_lang['source_type.file'] = 'Dosya sistemi';
$_lang['source_type.file_desc'] = 'Sunucunuzun dosyalarında gezinen bir dosya sistemi tabanlı kaynak.';
$_lang['source_type.s3'] = 'Amazon S3';
$_lang['source_type.s3_desc'] = 'Bir Amazon S3 kovasını gezer.';
$_lang['source_types'] = 'Kaynak Türleri';
$_lang['source_types.intro_msg'] = 'Bu, bu MODX örneğinde sahip olduğunuz tüm yüklü Medya Kaynağı Türlerinin bir listesidir.';
$_lang['source.access.intro_msg'] = 'Burada Medya Kaynağını belirli Kullanıcı Grupları ile sınırlandırabilir ve bu Kullanıcı Grupları için ilkeler uygulayabilirsiniz. Hiçbir kullanıcı grubu eklenmemiş bir medya kaynağı tüm yönetici kullanıcılar tarafından kullanılabilir.';
$_lang['sources'] = 'Medya Kaynakları';
$_lang['sources.intro_msg'] = 'Tüm Medya Kaynaklarını burada yönetin.';
$_lang['user_group'] = 'Kullanıcı Grubu';

/* file source type */
$_lang['allowedFileTypes'] = 'allowedFileTypes';
$_lang['prop_file.allowedFileTypes_desc'] = 'Eğer ayarlanırsa, gösterilen dosyaları yalnızca belirtilen uzantılarla sınırlar. Lütfen uzantılardan önce koyulmuş noktalar olmadan, virgülle ayrılmış bir liste içerisinde belirtin.';
$_lang['basePath'] = 'basePath';
$_lang['prop_file.basePath_desc'] = 'Kaynağı işaret edecek olan dosya yolu.';
$_lang['basePathRelative'] = 'basePathRelative';
$_lang['prop_file.basePathRelative_desc'] = 'Yukarıdaki Temel Yol ayarı MODX yükleme yolu ile alakalı değil ise, bu seçeneği Hayır olarak ayarlayın.';
$_lang['baseUrl'] = 'baseUrl';
$_lang['prop_file.baseUrl_desc'] = 'Bu kaynağın tarafından ulaşılabildiği URL.';
$_lang['baseUrlPrependCheckSlash'] = 'baseUrlPrependCheckSlash';
$_lang['prop_file.baseUrlPrependCheckSlash_desc'] = 'Eğer doğruysa; MODX, şablon değişkenini oluştururken URL başında hiçbir eğik çizgi (/) bulunmazsa, yalnızca temel URL\'yi onun önüne koyacaktır. Temel URL dışında bir şablon değişkeni değeri oluşturmakta yararlı.';
$_lang['baseUrlRelative'] = 'baseUrlRelative';
$_lang['prop_file.baseUrlRelative_desc'] = 'Yukarıdaki Temel URL ayarı MODX yükleme yolu ile alakalı değil ise, bu seçeneği Hayır olarak ayarlayın.';
$_lang['imageExtensions'] = 'imageExtensions';
$_lang['prop_file.imageExtensions_desc'] = 'Resimler olarak kullanılacak olan virgülle ayrılmış dosya uzantıları listesi. MODX, bu uzantılara sahip dosyaların küçük resimlerini oluşturmayı deneyecektir.';
$_lang['skipFiles'] = 'skipFiles';
$_lang['prop_file.skipFiles_desc'] = 'Virgüller ile ayrılmış bir liste. MODX, bunların herhangi biriyle eşleşen dosyaları ve klasörleri atlayacak ve gizleyecektir.';
$_lang['thumbnailQuality'] = 'thumbnailQuality';
$_lang['prop_file.thumbnailQuality_desc'] = '0-100 arasında derecelendirilmiş halde, işlenmiş küçük resimlerin kalitesi.';
$_lang['thumbnailType'] = 'thumbnailType';
$_lang['prop_file.thumbnailType_desc'] = 'İşlenecek küçük resimlerin, resim türü.';

/* s3 source type */
$_lang['bucket'] = 'Kova';
$_lang['prop_s3.bucket_desc'] = 'Verilerini oradan yüklemen için S3 Kovası.';
$_lang['prop_s3.key_desc'] = 'Kova kimliğini doğrulamak için Amazon anahtarı.';
$_lang['prop_s3.imageExtensions_desc'] = 'Resimler olarak kullanılacak olan virgülle ayrılmış dosya uzantıları listesi. MODX, bu uzantılara sahip dosyaların küçük resimlerini oluşturmayı deneyecektir.';
$_lang['prop_s3.secret_key_desc'] = 'Kova kimliğini doğrulamak için gizli Amazon anahtarı.';
$_lang['prop_s3.skipFiles_desc'] = 'Virgüller ile ayrılmış bir liste. MODX, bunların herhangi biriyle eşleşen dosyaları ve klasörleri atlayacak ve gizleyecektir.';
$_lang['prop_s3.thumbnailQuality_desc'] = '0-100 arasında derecelendirilmiş halde, işlenmiş küçük resimlerin kalitesi.';
$_lang['prop_s3.thumbnailType_desc'] = 'İşlenecek küçük resimlerin, resim türü.';
$_lang['prop_s3.url_desc'] = 'Amazon S3 örneğinin URL\'si.';
$_lang['s3_no_move_folder'] = 'S3 sürücüsü şu an için taşınan klasörleri desteklemiyor.';
$_lang['prop_s3.region_desc'] = 'Kovanın bölgesi. Örnek: Abd-batı-1';

/* file type */
$_lang['PNG'] = 'PNG';
$_lang['JPG'] = 'JPG';
$_lang['GIF'] = 'GIF';
