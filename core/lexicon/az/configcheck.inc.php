<?php
/**
* Config Check Azerbaijani lexicon topic
*
* @language az
* @package modx
* @subpackage lexicon
*/
$_lang['configcheck_admin'] = 'Zəhmət olmasa sistem administratoru ilə əlaqə saxlayın və onlara bu mesajı xəbərdar edin!';
$_lang['configcheck_allowtagsinpost_context_enabled'] = 'allow_tags_in_post Kontekst Parametri `mgr` xaricində aktivdir';
$_lang['configcheck_allowtagsinpost_context_enabled_msg'] = 'allow_tags_in_post Kontekst Parametri quraşdırmanızda `mgr` kontekstindən kənarda aktivdir. MODX bu parametri deaktiv etməyi tövsiyə edir, əgər istifadəçilərin formaya MODX tag-ləri, rəqəmli entitilər və ya HTML script tag-ləri göndərməsinə icazə vermək lazım deyilsə. Bu parametri ümumiyyətlə yalnız `mgr` kontekstində aktiv etmək lazımdır.';
$_lang['configcheck_allowtagsinpost_system_enabled'] = 'allow_tags_in_post Sistem Parametri aktivdir';
$_lang['configcheck_allowtagsinpost_system_enabled_msg'] = 'allow_tags_in_post Sistem Parametri quraşdırmanızda aktivdir. MODX bu parametri deaktiv etməyi tövsiyə edir, əgər istifadəçilərin formaya MODX tag-ləri, rəqəmli entitilər və ya HTML script tag-ləri göndərməsinə icazə vermək lazım deyilsə. Bu parametri, müəyyən kontekstlər üçün Kontekst Parametrləri vasitəsilə aktiv etmək daha yaxşıdır.';
$_lang['configcheck_cache'] = 'Keş qovluğu yazıla bilmir';
$_lang['configcheck_cache_msg'] = 'MODX keş qovluğuna yazmaqda çətinlik çəkir. MODX hələ də gözlənildiyi kimi işləyəcək, amma heç bir keşləmə olmayacaq. Bunun həlli üçün /cache/ qovluğunu yazıla bilən etmək lazımdır.';
$_lang['configcheck_configinc'] = 'Konfiqurasiya faylı hələ də yazıla bilər!';
$_lang['configcheck_configinc_msg'] = 'Saytınız hakerlərə qarşı həssasdır, çünki onlar saytınıza ziyan vurmaq üçün bu faylı istifadə edə bilərlər. Konfiqurasiya faylını yalnız oxunaqlı edin! Əgər saytın administratoru deyilsinizsə, zəhmət olmasa sistem administratoru ilə əlaqə saxlayın və onları bu mesaj haqqında xəbərdar edin! Bu fayl [[+path]] ünvanında yerləşir.';
$_lang['configcheck_default_msg'] = 'Təyin edilməmiş xəbərdarlıq tapıldı. Bu qəribədir.';
$_lang['configcheck_errorpage_unavailable'] = 'Saytınızın səhv səhifəsi mövcud deyil.';
$_lang['configcheck_errorpage_unavailable_msg'] = 'Bu, səhv səhifənizin adi istifadəçilərə açıq olmaması və ya mövcud olmaması deməkdir. Bu, saytınızda rekurziv dövrə səbəb ola bilər və səhv qeydləri ilə nəticələnə bilər. Əmin olun ki, səhifəyə heç bir webuser qrupu təyin edilməyib.';
$_lang['configcheck_errorpage_unpublished'] = 'Saytınızın səhv səhifəsi nəşr edilməyib və ya mövcud deyil.';
$_lang['configcheck_errorpage_unpublished_msg'] = 'Bu, səhv səhifənizin ümumi istifadəçilərə əlçatan olmaması deməkdir. Səhifəni nəşr edin və ya onun Sistemi > Sistem Parametrləri menyusunda sayt ağacında mövcud bir sənədə təyin olunduğundan əmin olun.';
$_lang['configcheck_htaccess'] = 'Core qovluğu vebə açıqdır';
$_lang['configcheck_htaccess_msg'] = 'MODX, core qovluğunuzun (qismən) ictimaiyyətə açıq olduğunu aşkarladı.
<strong>Bu tövsiyə edilmir və təhlükəsizlik riski yaradır.</strong>
Əgər MODX quraşdırmanız Apache veb serverində işləyirsə,
core qovluğunda .htaccess faylını qurmaq lazımdır <em>[[+fileLocation]]</em>.
Bu, mövcud ht.access nümunə faylını adını dəyişdirməklə asanlıqla edilə bilər.
<p>Başqa metodlar və veb serverlər mövcuddur, saytınızı qorumağa dair əlavə məlumat üçün <a href="https://docs.modx.com/3.x/en/getting-started/maintenance/securing-modx">MODX Təhlükəsizliyi Bələdçisi</a>ni oxuyun.</p>
Hər şey düzgün qurulursa, məsələn, <a href="[[+checkUrl]]" target="_blank">Dəyişikliklər</a> səhifəsinə daxil olmağa çalışdığınızda 403 (icazə verilmir) və ya 404 (tapılmadı) almanız lazımdır. Əgər dəyişikliklər səhifəsini brauzerdə görə bilirsinizsə, bir şeylər hələ də səhvdir və bunu düzəltmək üçün yenidən konfiqurasiya etməli və ya mütəxəssislə əlaqə saxlamalısınız.';
$_lang['configcheck_images'] = 'Şəkillər qovluğu yazıla bilmir';
$_lang['configcheck_images_msg'] = 'Şəkillər qovluğu yazıla bilmir və ya mövcud deyil. Bu, redaktorda Şəkil Meneceri funksiyalarının işləməyəcəyi deməkdir!';
$_lang['configcheck_installer'] = 'Quraşdırıcı hələ də mövcuddur';
$_lang['configcheck_installer_msg'] = 'setup/ qovluğu MODX quraşdırıcısını ehtiva edir. Təsəvvür edin ki, pis niyyətli bir şəxs bu qovluğu tapıb quraşdırıcıyı işə salsa! Onlar çox irəliləyə bilməzlər, çünki verilənlər bazası üçün istifadəçi məlumatlarını daxil etməli olacaqlar, amma bu qovluğu serverinizdən silmək daha yaxşıdır. Bu qovluq [[+path]] ünvanında yerləşir.';
$_lang['configcheck_lang_difference'] = 'Dil faylında qeydlərin sayının uyğunsuzluğu';
$_lang['configcheck_lang_difference_msg'] = 'Hal-hazırda seçilmiş dilin qeydlərinin sayı, əsas dilin qeydləri ilə uyğun gəlmir. Bu mütləq problem deyil, amma dil faylının yenilənməsi lazım ola bilər.';
$_lang['configcheck_notok'] = 'Bir və ya daha çox konfiqurasiya detalları düzgün yoxlanmadı: ';
$_lang['configcheck_phpversion'] = 'PHP versiyası köhnəlib';
$_lang['configcheck_phpversion_msg'] = 'Sizin PHP versiyanız [[+phpversion]] artıq PHP tərtibatçıları tərəfindən saxlanılmır, yəni təhlükəsizlik yeniləmələri yoxdur. Ehtimal ki, MODX və ya əlavə paketlər yaxın gələcəkdə bu versiyanı dəstəkləməyəcək. Zəhmət olmasa, mühitinizi ən azı PHP [[+phprequired]] versiyasına yeniləyin ki, saytınızı təhlükəsiz edəsiniz.';
$_lang['configcheck_register_globals'] = 'register_globals php.ini konfiqurasiya faylında ON olaraq təyin edilib';
$_lang['configcheck_register_globals_msg'] = 'Bu konfiqurasiya saytınızı Cross Site Scripting (XSS) hücumlarına qarşı çox daha həssas edir. Bu ayarı deaktiv etmək üçün hostunuzla əlaqə saxlayın.';
$_lang['configcheck_title'] = 'Konfiqurasiya yoxlaması';
$_lang['configcheck_unauthorizedpage_unavailable'] = 'Saytınızın icazəsiz səhifəsi nəşr edilməyib və ya mövcud deyil.';
$_lang['configcheck_unauthorizedpage_unavailable_msg'] = 'Bu, icazəsiz səhifənizin adi istifadəçilərə açıq olmaması və ya mövcud olmaması deməkdir. Bu, saytınızda rekurziv dövrə və səhv qeydləri ilə nəticələnə bilər. Əmin olun ki, səhifəyə heç bir webuser qrupu təyin edilməyib.';
$_lang['configcheck_unauthorizedpage_unpublished'] = 'Saytınızın icazəsiz səhifəsi nəşr edilməyib.';
$_lang['configcheck_unauthorizedpage_unpublished_msg'] = 'Bu, icazəsiz səhifənizin ümumi istifadəçilərə əlçatan olmaması deməkdir. Səhifəni nəşr edin və ya onun Sistemi > Sistem Parametrləri menyusunda sayt ağacında mövcud bir sənədə təyin olunduğundan əmin olun.';
$_lang['configcheck_warning'] = 'Konfiqurasiya xəbərdarlığı:';
$_lang['configcheck_what'] = 'Bu nə deməkdir?';
