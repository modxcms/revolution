<?php
/**
 * TV Widget Azerbaijani lexicon topic
 *
 * @language az
 * @package modx
 * @subpackage lexicon
 */
$_lang['attributes'] = 'Atributlar';
$_lang['attr_attr_desc'] = 'Bu elementin etiketinə əlavə ediləcək bir və ya bir neçə boşluqla ayrılmış atribut (məsələn, <span class="example-input">rel="external" type="application/pdf"</span>).';
$_lang['attr_class_desc'] = 'Bir və ya bir neçə boşluqla ayrılmış CSS sinfi adları.';
$_lang['attr_style_desc'] = 'CSS tərifləri (məsələn, <span class="example-input">color:#f36f99; text-decoration:none;</span>).';
$_lang['attr_target_blank'] = 'Boş';
$_lang['attr_target_parent'] = 'Ana Səhifə';
$_lang['attr_target_self'] = 'Özünə';
$_lang['attr_target_top'] = 'Yuxarı';
$_lang['attr_target_desc'] = 'Bağlantıdakı URL-nin hansı pəncərə/tab və ya çərçivədə açılacağını göstərir. Xüsusi bir çərçivə hədəf almaq üçün mövcud seçimlərdən birinin əvəzinə çərçivənin adını daxil edin.';
$_lang['capitalize'] = 'Böyük hərflə başla';
$_lang['checkbox'] = 'Yoxlama Qutusu';
$_lang['checkbox_columns'] = 'Sütunlar';
$_lang['checkbox_columns_desc'] = 'Yoxlama qutularının göstərildiyi sütunların sayı.';
$_lang['checkbox_display_switch'] = 'Dəyişdirici olaraq göstər';
$_lang['checkbox_display_switch_desc'] = '“Bəli” olaraq təyin edildikdə, bu TV-nin girişləri resurs redaktə formasında yoxlama qutuları əvəzinə dəyişdiricilər (toggles) kimi göstəriləcək. (Defolt: “Xeyr”)';
$_lang['class'] = 'Sinif';
$_lang['classes'] = 'Sinif(ler)';
$_lang['combo_allowaddnewdata'] = 'Yeni Əşyaların Əlavə edilməsinə icazə ver';
$_lang['combo_allowaddnewdata_desc'] = '“Bəli” olduqda, artıq siyahıda olmayan əşyaların əlavə edilməsinə icazə verir. Defolt olaraq Xeyr-dir.';
$_lang['combo_forceselection'] = 'Uyğunluq tələb et';
$_lang['combo_forceselection_desc'] = 'Yalnızca siyahıda artıq təyin edilmiş bir seçimlə uyğun gəldikdə saxlanacaq yazılmış seçim.';
$_lang['combo_forceselection_multi_desc'] = 'Əgər bu “Bəli” olaraq təyin olunubsa, yalnızca siyahıda olan əşyalar icazə veriləcək. Əgər Xeyr, yeni dəyərlər də daxil edilə bilər.';
$_lang['combo_listempty_text'] = 'Seçim Tapılmadı Mesajı';
$_lang['combo_listempty_text_desc'] = 'Yazılmış mətn mövcud seçimlərlə uyğun gəlmədikdə göstərilən mesaj.';
$_lang['combo_listheight'] = 'Siyahı Hündürlüyü';
$_lang['combo_listheight_desc'] = 'Siyahının hündürlüyü, % və ya px ilə. Defolt olaraq combobox-un hündürlüyü ilə eynidir.';
$_lang['combo_listwidth'] = 'Siyahı Eni';
$_lang['combo_listwidth_desc'] = 'Siyahının eni, % və ya px ilə. Defolt olaraq combobox-un eni ilə eynidir.';
$_lang['combo_maxheight'] = 'Maksimum Hündürlük';
$_lang['combo_maxheight_desc'] = 'Siyahı üçün maksimum hündürlük, aşağı sürüşdürmə çubuqları göstərilməzdən əvvəl. (Defolt: 300)';
$_lang['combo_preserve_selectionorder'] = 'Seçim sırasını qoruyun';
$_lang['combo_preserve_selectionorder_desc'] = '“Bəli” olduqda, saxlanmış əşyalar, orijinal olaraq seçildikləri sırada göstəriləcək. Əks halda, əşyalar siyahı seçimlərinə görə sıralanacaq. (Defolt: Xeyr)';
$_lang['combo_stackitems'] = 'Seçilmiş əşyaları yığın';
$_lang['combo_stackitems_desc'] = '“Bəli” olduqda, əşyalar bir xəttə sıralanacaq. Defolt olaraq “Xeyr”, əşyalar üfüqi olaraq göstəriləcək.';
$_lang['combo_title'] = 'Siyahı Başlığı';
$_lang['combo_title_desc'] = 'Əgər təqdim edilsə, bu mətnlə başlıq elementi yaradılacaq və siyahının başına əlavə olunacaq.';
$_lang['combo_typeahead'] = 'Type-Ahead Aktivləşdir';
$_lang['combo_typeahead_desc'] = 'Yazmağa başladıqca uyğun gələn seçimləri doldurur və avtomatik seçir. (Defolt: Xeyr)';
$_lang['combo_typeahead_delay'] = 'Gecikmə';
$_lang['combo_typeahead_delay_desc'] = 'Uyğunlaşan bir seçim göstərilmədən əvvəl gecikmə, millisekundla. (Defolt: 250)';
$_lang['date'] = 'Tarix';
$_lang['date_format'] = 'Tarix Formatı';
$_lang['date_format_desc'] = 'Formatı daxil edin <a href="https://www.php.net/strftime" target="_blank">php-nin strftime sintaksisi</a> ilə.
    <div class="example-list">Ən çox istifadə olunan nümunələr:
        <ul>
            <li><span class="example-input">[[+example_1a]]</span> ([[+example_1b]]) (defolt format)</li>
            <li><span class="example-input">[[+example_2a]]</span> ([[+example_2b]])</li>
            <li><span class="example-input">[[+example_3a]]</span> ([[+example_3b]])</li>
            <li><span class="example-input">[[+example_4a]]</span> ([[+example_4b]])</li>
            <li><span class="example-input">[[+example_5a]]</span> ([[+example_5b]])</li>
            <li><span class="example-input">[[+example_6a]]</span> ([[+example_6b]])</li>
            <li><span class="example-input">[[+example_7a]]</span> ([[+example_7b]])</li>
        </ul>
    </div>
';
$_lang['date_use_current'] = 'Cari Tarixi Yedək kimi istifadə et';
$_lang['date_use_current_desc'] = 'Bu TV üçün bir dəyər tələb olunmadıqda (Boş olmasına icazə = “Bəli”) və Defolt Tarix təyin edilmədikdə, bu seçimi “Bəli” olaraq təyin edərək cari tarixi göstərəcəkdir.';
$_lang['default'] = 'Defolt';
$_lang['default_date_now'] = 'Bugün və Cari Vaxt';
$_lang['default_date_today'] = 'Bugün (gecə yarısı)';
$_lang['default_date_yesterday'] = 'Dünən (gecə yarısı)';
$_lang['default_date_tomorrow'] = 'Sabah (gecə yarısı)';
$_lang['default_date_custom'] = 'Özelleşdirilmiş (aşağıda izah edilmişdir)';
$_lang['delim'] = 'Ayırıcı';
$_lang['delimiter'] = 'Ayırıcı';
$_lang['delimiter_desc'] = 'Bir və ya bir neçə xarakter, dəyərləri ayırmaq üçün istifadə olunur (bir neçə seçilə bilən variantları dəstəkləyən TV-lər üçün).';
$_lang['disabled_dates'] = 'Aktiv olmayan Tarixlər';
$_lang['disabled_dates_desc'] = 'Menecerin tarix formatında vergül ilə ayrılmış, javascript <abbr title="regular expression">regex</abbr>-uyğun siyahı (ayırıcılar olmadan).
    <p>Defolt format ilə nümunələr (“[[+format_default]]”):</p>
    <div class="example-list">
        <ul>
            <li><span class="example-input">[[+example_1]]</span> (yeganə tarixləri seçir)</li>
            <li><span class="example-input">[[+example_2a]]</span> (hər ilin [[+example_2b]] və [[+example_2c]] tarixlərini seçir)</li>
            <li><span class="example-input">[[+example_3a]]</span> (“^” ilə başlanğıc uyğunluğu seçir; bu, bütün [[+example_3b]]-ni seçir)</li>
            <li><span class="example-input">[[+example_4a]]</span> (hər günün [[+example_4b]] tarixlərini seçir)</li>
            <li><span class="example-input">[[+example_5]]</span> (“$” ilə sonu uyğunluğu seçir; bu, hər ilin mart ayının hər gününü seçir)</li>
        </ul>
        Qeyd: Əgər tarix formatınızda nöqtə ayırıcıları varsa, bunlar qaçırılmalıdır (məsələn, “[[+example_6a]]” yuxarıda “[[+example_6b]]” olaraq daxil edilməlidir).
    </div>
';
$_lang['disabled_days'] = 'Aktiv Olmayan Günlər';
$_lang['disabled_days_desc'] = '';
$_lang['dropdown'] = 'Açılan Siyahı Menyu';
$_lang['earliest_date'] = 'Ən Erkən Tarix';
$_lang['earliest_date_desc'] = 'Seçilə bilən ən erkən tarix.';
$_lang['earliest_time'] = 'Ən Erkən Vaxt';
$_lang['earliest_time_desc'] = 'Seçilə bilən ən erkən vaxt.';
$_lang['email'] = 'E-poçt';
$_lang['file'] = 'Fayl';
$_lang['height'] = 'Hündürlük';
$_lang['hidden'] = 'Gizli';
$_lang['hide_time'] = 'Vaxt Seçimini Gizlət';
$_lang['hide_time_desc'] = 'Bu TV-nin tarix seçicisindən vaxt seçmə imkanı ləğv edilir.';
$_lang['htmlarea'] = 'HTML Sahəsi';
$_lang['htmltag'] = 'HTML Etiketi';
$_lang['image'] = 'Şəkil';
$_lang['image_alt'] = 'Alternativ Mətn';
$_lang['input_height'] = 'Giriş Hündürlüyü';
$_lang['input_height_desc'] = 'Girişin hündürlüyünü piksel ilə göstərən bir rəqəm. (Defolt: 140)';
$_lang['latest_date'] = 'Ən Son Tarix';
$_lang['latest_date_desc'] = 'Seçilə biləcək ən son tarix.';
$_lang['latest_time'] = 'Ən Son Vaxt';
$_lang['latest_time_desc'] = 'Seçilə biləcək ən son vaxt.';
$_lang['listbox'] = 'Seçim Qutusu (Tək Seçim)';
$_lang['listbox-multiple'] = 'Seçim Qutusu (Çoxlu Seçim)';
$_lang['lower_case'] = 'Kiçik Hərf';
$_lang['max_length'] = 'Maksimum Uzunluq';
$_lang['min_length'] = 'Minimal Uzunluq';
$_lang['regex_text'] = 'Regular Expression Xətası';
$_lang['regex_text_desc'] = 'Əgər istifadəçi <abbr title="regular expression">regex</abbr> validatoruna uyğun olmayan mətn daxil edərsə, göstərilən mesaj.';
$_lang['regex'] = 'Regular Expression Validator';
$_lang['regex_desc'] = 'Bu TV-nin məzmununu məhdudlaşdıran javascript <abbr title="regular expression">regex</abbr>-uyğunluğuna malik bir sətir (seperatorlardan istisna olmaqla). Bəzi nümunələr:
    <div class="example-list">
        <ul>
            <li><span class="example-input">[[+example_1]]</span> (ABŞ poçt kodları üçün nümunə)</li>
            <li><span class="example-input">[[+example_2]]</span> (yalnız hərflərə icazə ver)</li>
            <li><span class="example-input">[[+example_3]]</span> (nömrələr xaricində bütün simvollara icazə ver)</li>
            <li><span class="example-input">[[+example_4]]</span> (mütləq “-XP” ilə bitməlidir)</li>
        </ul>
    </div>
';
$_lang['name'] = 'Ad';
$_lang['number'] = 'Rəqəm';
$_lang['number_allowdecimals'] = 'Desimalara İcazə Ver';
$_lang['number_allownegative'] = 'Mənfi Rəqəmlərə İcazə Ver';
$_lang['number_decimalprecision'] = 'Dəqiqlik';
$_lang['number_decimalprecision_desc'] = 'Desimal separatorundan sonra icazə verilən maksimum rəqəm sayı. (Defolt: 2)';
$_lang['number_decimalprecision_strict'] = 'Sıx Desimal Dəqiqlik';
$_lang['number_decimalprecision_strict_desc'] = '“Bəli” olaraq təyin edildikdə, desimal ədədlərdə son sıfırlar qorunur (defolt “Xeyr”).';
$_lang['number_decimalseparator'] = 'Separator';
$_lang['number_decimalseparator_desc'] = 'Desimal separatoru kimi istifadə edilən simvol. (Defolt: “.”)';
$_lang['number_maxvalue'] = 'Maksimum Dəyər';
$_lang['number_minvalue'] = 'Minimal Dəyər';
$_lang['option'] = 'Radio Seçimlər';
$_lang['parent_resources'] = 'Valideyn Resurslar';
$_lang['radio_columns'] = 'Sütunlar';
$_lang['radio_columns_desc'] = 'Radio düymələrinin göstərildiyi sütun sayı.';
$_lang['rawtext'] = 'Sadə Mətn (Deprecated)';
$_lang['rawtextarea'] = 'Sadə Textarea (Deprecated)';
$_lang['required'] = 'Boş Buraxmağa İcazə Ver';
$_lang['required_desc'] = 'Bu TV-ni resurslarda tələb olunan sahə etmək üçün “Xeyr” seçin. (Defolt: “Bəli”)';
$_lang['resourcelist'] = 'Resurs Siyahısı';
$_lang['resourcelist_depth'] = 'Dərinlik';
$_lang['resourcelist_depth_desc'] = 'Bu siyahı üçün alt qovluqlara qədər axtarılacaq resurs sayı. (Defolt: 10)';
$_lang['resourcelist_forceselection_desc'] = 'Deaktiv; yalnız uyğun siyahılar keçərlidir.';
$_lang['resourcelist_includeparent'] = 'Valideynləri Daxil Et';
$_lang['resourcelist_includeparent_desc'] = 'Siyahıya, Valideynlər sahəsində qeyd olunan resursları daxil etmək üçün “Bəli” seçin.';
$_lang['resourcelist_limitrelatedcontext'] = 'Əlaqəli Kontekstə Məhdudlaşdır';
$_lang['resourcelist_limitrelatedcontext_desc'] = 'Bu resursun kontekstinə aid olan yalnız resursları daxil etmək üçün “Bəli” seçin.';
$_lang['resourcelist_limit'] = 'Məhdudiyyət';
$_lang['resourcelist_limit_desc'] = 'Bu TV-nin siyahısında göstərilən maksimum resurs sayı. (Defolt: 0, məhdudiyyətsiz)';
$_lang['resourcelist_listempty_text_desc'] = 'Deaktiv; seçimlər həmişə siyahı ilə uyğun olacaq.';
$_lang['resourcelist_parents'] = 'Valideynlər';
$_lang['resourcelist_parents_desc'] = 'Bu TV-nin siyahısı yalnız göstərilən resurs ID-lərindən (konteynerlərdən) olan alt resursları daxil edəcək.';
$_lang['resourcelist_where'] = 'Harada Şərtləri';
$_lang['resourcelist_where_desc'] = '
    <p>Bu TV-nin resurs siyahısını süzgəcdən keçirmək üçün bir və ya daha çox resurs sahəsini göstərən JSON obyekti.</p>
    <div class="example-list">Bəzi nümunələr:
        <ul>
            <li><span class="example-input">[[+example_1]]</span> (yalnız 4 nömrəli şablon tətbiq edilmiş resursları daxil et)</li>
            <li><span class="example-input">[[+example_2]]</span> (bütün resursları daxil et, ancaq “Ana Səhifə” adlıları daxil etmə)</li>
            <li><span class="example-input">[[+example_3]]</span> (yalnız Weblink və ya Symlink olan resursları daxil et)</li>
            <li><span class="example-input">[[+example_4]]</span> (yalnız nəşr olunan və konteyner olmayan resursları daxil et)</li>
        </ul>
    </div>
    <p>Qeyd: TV dəyərləri ilə süzgəc tətbiq etmək dəstəklənmir.</p>
';
$_lang['richtext'] = 'RichText';
$_lang['sentence_case'] = 'Cümlə Başlığı';
$_lang['start_day'] = 'Başlanğıc Günü';
$_lang['start_day_desc'] = 'Bu TV-nin tarix seçicisində həftənin başlanğıcı kimi göstərilən gün. (Defolt: “Bazar”)';
$_lang['string'] = 'Sətir';
$_lang['string_format'] = 'Sətir Formatı';
$_lang['style'] = 'Stil';
$_lang['tag_name'] = 'Etiket Adı';
$_lang['target'] = 'Hədəf';
$_lang['text'] = 'Mətn';
$_lang['textarea'] = 'Textarea';
$_lang['textarea_grow'] = 'Avtomatik Artırmaq?';
$_lang['textarea_grow_desc'] = 'Girişin hündürlüyünün məzmununa əsasən avtomatik dəyişməsinə icazə vermək üçün “Bəli” seçin. (Defolt: “Xeyr”)';
$_lang['textarea_resizable'] = 'Ölçülən?';
$_lang['textarea_resizable_desc'] = 'Girişin hündürlüyünün alt sərhədini sürükləyərək dəyişməsinə icazə vermək üçün “Bəli” seçin. (Defolt: “Xeyr”)';
$_lang['textareamini'] = 'Textarea (Mini)';
$_lang['textbox'] = 'Textbox';
$_lang['time_increment'] = 'Vaxt Artımı';
$_lang['time_increment_desc'] = 'Siyahıdakı hər bir vaxt dəyəri arasındakı dəqiqə sayı. (Defolt: 15)';
$_lang['title'] = 'Başlıq';
$_lang['tv_default_checkbox_desc'] = 'Əgər istifadəçi bir və ya bir neçə seçim etməzsə, bu TV üçün seçilən seçimlər cüt boru ilə ayrılır. Əgər seçimlər etiketlərdən ibarət olarsa (məsələn, Seçim Bir==1||Seçim İki==2||Seçim Üç==3), qiyməti daxil etdiyinizə əmin olun (yəni “1” Seçim Bir üçün, və ya “1||3” Seçim Bir və Seçim Üç üçün)';
$_lang['tv_default_date'] = 'Defolt Tarix və Vaxt';
$_lang['tv_default_date_desc'] = 'Əgər istifadəçi bir tarix təqdim etməzsə, göstəriləcək tarix. Yuxarıdakı siyahıdan bir nisbət tarixini seçin və ya aşağıdakı nümunələrdən birini istifadə edərək fərqli bir tarix daxil edin:
    <div class="example-list">
        <ul>
            <li><span class="example-input">[[+example_1]]</span> (rəqəm keçmiş saatları təmsil edir)</li>
            <li><span class="example-input">[[+example_2]]</span> (rəqəm gələcək saatları təmsil edir)</li>
            <li><span class="example-input">[[+example_3]]</span> (şəxsi bir tarix [və istəsəniz saat] göstərilən formatda)</li>
        </ul>
        Qeyd: Yuxarıda göstərilən “+” və “-” istifadəsi qeyri-intuitivdir, amma düzgündür (“+” keçmişə doğru göstərir).
    </div>';
$_lang['tv_default_email'] = 'Defolt E-poçt Ünvanı';
$_lang['tv_default_email_desc'] = 'İstifadəçi bir e-poçt ünvanı təqdim etməzsə, bu TV-də göstərilən e-poçt ünvanı.';
$_lang['tv_default_file'] = 'Defolt Fayl';
$_lang['tv_default_file_desc'] = 'İstifadəçi bir fayl təqdim etməzsə, bu TV-də göstərilən fayl yolu.';
$_lang['tv_default_image'] = 'Defolt Şəkil';
$_lang['tv_default_image_desc'] = 'İstifadəçi bir şəkil təqdim etməzsə, bu TV-də göstərilən şəkil yolu.';
$_lang['tv_default_option'] = 'Defolt Seçim';
$_lang['tv_default_option_desc'] = 'İstifadəçi heç bir seçim etməzsə, bu TV-də seçilən seçim. Seçimləriniz etiketlər (məsələn, Seçim Bir==1||Seçim İki==2||Seçim Üç==3) ehtiva edirsə, dəyəri daxil etdiyinizdən əmin olun (məsələn, “1” Seçim Bir üçün)';
$_lang['tv_default_options'] = 'Defolt Seçim(ler)';
$_lang['tv_default_options_desc'] = 'İstifadəçi heç bir və ya bir neçə seçim etməzsə, bu TV-də seçilən iki sətirli seçim(ler). Seçimləriniz etiketlər (məsələn, Seçim Bir==1||Seçim İki==2||Seçim Üç==3) ehtiva edirsə, dəyəri daxil etdiyinizdən əmin olun (məsələn, “1” Seçim Bir üçün, ya da “1||3” Seçim Bir və Seçim Üç üçün)';
$_lang['tv_default_radio_desc'] = $_lang['tv_default_option_desc'];
$_lang['tv_default_listbox_desc'] = $_lang['tv_default_option_desc'];
$_lang['tv_default_listbox-multiple_desc'] = $_lang['tv_default_options_desc'];
$_lang['tv_default_number'] = 'Defolt Rəqəm';
$_lang['tv_default_number_desc'] = 'İstifadəçi bir rəqəm təqdim etməzsə, bu TV-də göstərilən rəqəm.';
$_lang['tv_default_resource'] = 'Defolt Resurs (ID)';
$_lang['tv_default_resourcelist_desc'] = 'İstifadəçi heç bir seçim etməzsə, bu TV-də göstərilən resurs.';
$_lang['tv_default_tag'] = 'Defolt Etiket(ler)';
$_lang['tv_default_tag_desc'] = 'İstifadəçi heç bir və ya bir neçə seçim etməzsə, bu TV-də seçilən vergüllə ayrılmış seçim(ler). Seçimləriniz etiketlər (məsələn, Etiket Bir==1||Etiket İki==2||Etiket Üç==3) ehtiva edirsə, dəyəri daxil etdiyinizdən əmin olun (məsələn, “1” Etiket Bir üçün, ya da “1,3” Etiket Bir və Etiket Üç üçün)';
$_lang['tv_default_text'] = 'Defolt Mətn';
$_lang['tv_default_text_desc'] = 'İstifadəçi mətn təqdim etməzsə, bu TV-də göstərilən mətn məzmunu.';
$_lang['tv_default_url'] = 'Defolt URL';
$_lang['tv_default_url_desc'] = 'İstifadəçi bir URL təqdim etməzsə, bu TV-də göstərilən URL.';
$_lang['tv_elements_checkbox'] = 'Çekboks Seçimləri';
$_lang['tv_elements_listbox'] = 'Yıxılma Siyahısı Seçimləri';
$_lang['tv_elements_radio'] = 'Radiyo Düyməsi Seçimləri';
$_lang['tv_elements_tag'] = 'Etiket Seçimləri';
$_lang['tv_elements_desc'] = 'Bu TV üçün seçilə bilən seçimləri müəyyən edir, bunlar əl ilə daxil edilə bilər və ya bir sətirlik <a href="https://docs.modx.com/current/en/building-sites/elements/template-variables/bindings/select-binding" target="_blank">Verilənlər Bazası sorğusu</a> ilə qurula bilər. Bəzi nümunələr:
    <div class="example-list">
        <ul>
            <li><span class="example-input">Quş||Pişik||İt</span> (Quş==Quş||Pişik==Pişik||İt==İt) qısa yazılış forması)</li>
            <li><span class="example-input">Ağ==#ffffff||Qara==#000000</span> (etiket==dəyər ilə)</li>
            <li><span class="example-input">[[+example_1]]</span> (təqdim edilmiş Şablon ID-si 1 olan yayımlanan Resursların siyahısını yaradır)</li>
            <li><span class="example-input">[[+example_2]]</span> (yuxarıdakı nümunə ilə eyni siyahını qurur, boş seçimlə daxil olur)</li>
        </ul>
    </div>
    ';
$_lang['tv_elements_checkbox_desc'] = $_lang['tv_elements_desc'];
$_lang['tv_elements_listbox_desc'] = $_lang['tv_elements_desc'];
$_lang['tv_elements_listbox-multiple_desc'] = $_lang['tv_elements_listbox_desc'];
$_lang['tv_elements_radio_desc'] = $_lang['tv_elements_option_desc'] = $_lang['tv_elements_desc'];
$_lang['tv_elements_tag_desc'] = $_lang['tv_elements_desc'];
$_lang['upper_case'] = 'Yuxarı Hərf';
$_lang['url'] = 'URL';
$_lang['url_display_text'] = 'Göstəriləcək Mətn';
$_lang['width'] = 'En';

// Köhnə açarları yeni olanlarla müvəqqəti uyğunlaşdırmaq üçün
$_lang['tv_default_datetime'] = $_lang['tv_default_date'];

/*
    Refer to default.inc.php for the keys below.
    (Placement in this default file necessary to allow
    quick create/edit panels access to them when opened
    outside the context of their respective element types)

    tv_type
    tv_default
    tv_default_desc
    tv_elements

*/
