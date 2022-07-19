<?php
/**
 * English language strings for Dashboards
 *
 * @package modx
 * @subpackage lexicon
 * @language en
 */
$_lang['dashboard'] = 'Kontrol Paneli';
$_lang['dashboard_desc_name'] = 'Panonun adı.';
$_lang['dashboard_desc_description'] = 'Panonun kısa açıklaması.';
$_lang['dashboard_desc_hide_trees'] = 'Bunu kontrol ederseniz, Pano karşılama sayfasında görüntülendiğinde sol taraftaki ağaçları gizleyecektir.';
$_lang['dashboard_hide_trees'] = 'Sol-El Ağaölarını Gizle';
$_lang['dashboard_desc_customizable'] = 'Allow users to customize this dashboard for their accounts: create, delete and change position or size of widgets.';
$_lang['dashboard_customizable'] = 'Customizable';
$_lang['dashboard_remove_confirm'] = 'Are you sure you want to delete this Dashboard?';
$_lang['dashboard_remove_multiple_confirm'] = 'Are you sure you want to delete the selected Dashboards?';
$_lang['dashboard_err_ae_name'] = '"[[+name]]" isimli bir gösterge tablosu zaten var! Başka bir isim deneyin.';
$_lang['dashboard_err_duplicate'] = 'Pano yineleme denemesi sırasında bir hata meydana geldi.';
$_lang['dashboard_err_nf'] = 'Pano bulunamadı.';
$_lang['dashboard_err_ns'] = 'Pano belirtilmemiş.';
$_lang['dashboard_err_ns_name'] = 'Lütfen widget için bir isim belirleyin.';
$_lang['dashboard_err_remove'] = 'An error occurred while trying to delete the Dashboard.';
$_lang['dashboard_err_remove_default'] = 'You cannot delete the default Dashboard!';
$_lang['dashboard_err_save'] = 'Panoyu kaydetmeye çalışılırken bir hata meydana geldi.';
$_lang['dashboard_usergroup_add'] = 'Kullanıcı grubuna pano ata';
$_lang['dashboard_usergroup_remove'] = 'Delete Dashboard from User Group';
$_lang['dashboard_usergroup_remove_confirm'] = 'Bu Kullanıcı Grubunu varsayılan Gösterge Tablosunu kullanmaya geri döndürmek istediğinizden emin misiniz?';
$_lang['dashboard_usergroups.intro_msg'] = 'Bu panoyu kullanan tüm kullanıcı gruplarının listesi.';
$_lang['dashboard_widget_err_placed'] = 'Bu widget zaten bu panoda yer alıyor!';
$_lang['dashboard_widgets.intro_msg'] = 'Manage widgets in this dashboard. You can also drag and drop rows in the grid to rearrange them.<br><br>Please note: if a dashboard is "customizable", this settings will be applied only for the first load for every user. From here they will be able to create, delete and change the position or size of their widgets. User access to widgets can be limited by applying permissions.';
$_lang['dashboards'] = 'Panolar';
$_lang['dashboards.intro_msg'] = 'Burada MODX yöneticisi için mevcut olan bütün panoları yönetebilirsin.';
$_lang['rank'] = 'Rütbe';
$_lang['user_group_filter'] = 'Kullanıcı Grubu';
$_lang['widget'] = 'Araç';
$_lang['widget_content'] = 'Widget içeriği';
$_lang['widget_err_ae_name'] = '"[[+name]]" adlı bir araç zaten var! Lütfen başka bir ad deneyin.';
$_lang['widget_err_nf'] = 'Widget bulunamadı!';
$_lang['widget_err_ns'] = 'Widget belirtilmedi!';
$_lang['widget_err_ns_name'] = 'Lütfen widget için bir isim belirleyin.';
$_lang['widget_err_remove'] = 'An error occurred while trying to delete the Widget.';
$_lang['widget_err_save'] = 'Aracı kaydetmeye çalışırken bir hata meydana geldi.';
$_lang['widget_file'] = 'Dosya';
$_lang['widget_dashboards.intro_msg'] = 'Aşağıda bu Araçların yerleştirildiği tüm Gösterge tablolarının bir listesi bulunmaktadır.';
$_lang['widget_dashboard_remove'] = 'Delete Widget From Dashboard';
$_lang['widget_description_desc'] = 'Araç\'in bir açıklaması veya Sözlük Girişi anahtarı ne işe yarıyor ve bu ne yapıyor.';
$_lang['widget_html'] = 'HTML';
$_lang['widget_lexicon_desc'] = 'Sözcük Konusu bu Widget ile yüklemek için. Widget\'daki herhangi bir metnin yanı sıra, isim ve açıklama için çeviriler sağlamak için yararlıdır.';
$_lang['widget_permission_desc'] = 'This permission will be required to add this widget to a user dashboard.';
$_lang['widget_permission'] = 'İzin';
$_lang['widget_name_desc'] = 'Widget\'ın adı veya Sözlük Giriş anahtarı.';
$_lang['widget_add'] = 'Add Widget';
$_lang['widget_add_desc'] = 'Please select a Widget to add to your Dashboard.';
$_lang['widget_add_success'] = 'The widget was successfully added to your Dashboard. It will be loaded after closing this window.';
$_lang['widget_remove_confirm'] = 'Are you sure you want to delete this Dashboard Widget? This is permanent, and will delete the Widget from all Dashboards.';
$_lang['widget_remove_multiple_confirm'] = 'Are you sure you want to delete these Dashboard Widgets? This is permanent, and will delete the Widgets from all their assigned Dashboards.';
$_lang['widget_namespace'] = 'İsim Alanı';
$_lang['widget_namespace_desc'] = 'Bu aracın yükleneceği isim alanı. Özel yollar için kullanışlıdır.';
$_lang['widget_php'] = 'Satır içi PHP Araçları';
$_lang['widget_place'] = 'Aracı yerleştir';
$_lang['widget_size'] = 'Boyut';
$_lang['widget_size_desc'] = 'The size of the widget. Can either be a from "quarter" to "double".';
$_lang['widget_size_double'] = 'Double Size';
$_lang['widget_size_full'] = 'Full Size';
$_lang['widget_size_three_quarters'] = 'Three Quarters';
$_lang['widget_size_two_third'] = 'Two Third';
$_lang['widget_size_half'] = 'Yarım';
$_lang['widget_size_one_third'] = 'One Third';
$_lang['widget_size_quarter'] = 'Quarter';
$_lang['widget_snippet'] = 'Parça';
$_lang['widget_type'] = 'Araç Türü';
$_lang['widget_type_desc'] = 'Bu araç türü. "Snippet" araçları, çalıştırılan ve çıktılarını döndüren MODX Araçlarıdır. "HTML" araçları sadece düz HTML\'dir. "Dosya" araçları doğrudan dosyalardan yüklenir, bu da çıktılarını veya modDashboardWidgetClass\'ın genişletilmiş sınıfının adını döndürebilir. "Inline PHP" araçları, bir Snippet\'e benzer araç içeriğinde düz PHP olan araçlardır.';
$_lang['widget_unplace'] = 'Delete Widget from Dashboard';
$_lang['widgets'] = 'Araçlar';
$_lang['widgets.intro_msg'] = 'Sahip olduğunuz tüm Gösterge Tablosu Parçacık\'larının bir listesi aşağıda verilmiştir.';

$_lang['action_new_resource'] = 'New page';
$_lang['action_new_resource_desc'] = 'Create a new page for your website.';
$_lang['action_view_website'] = 'View website';
$_lang['action_view_website_desc'] = 'Open your website in a new window.';
$_lang['action_advanced_search'] = 'Advanced search';
$_lang['action_advanced_search_desc'] = 'Advanced search through your website.';
$_lang['action_manage_users'] = 'Manage users';
$_lang['action_manage_users_desc'] = 'Manage all your website and manager users.';

$_lang['w_buttons'] = 'Buttons';
$_lang['w_buttons_desc'] = 'Displays a set of buttons from array specified in properties.';
$_lang['w_updates'] = 'Updates';
$_lang['w_updates_desc'] = 'Checks for available updates for core and extras.';
$_lang['w_configcheck'] = 'Yapılandırma Kontrolu';
$_lang['w_configcheck_desc'] = 'MODX kurulumunuzun güvenliğini garanti eden bir yapılandırma kontrolü görüntüler.';
$_lang['w_newsfeed'] = 'MODX haber akışı';
$_lang['w_newsfeed_desc'] = 'MODX haber akışını görüntüler';
$_lang['w_recentlyeditedresources'] = 'Son Düzenlenen Kaynaklar';
$_lang['w_recentlyeditedresources_desc'] = 'Kullanıcı tarafından en son düzenlenen kaynakların bir listesini gösterir.';
$_lang['w_securityfeed'] = 'MODX Güvenlik Beslemesi';
$_lang['w_securityfeed_desc'] = 'MODX Güvenlik Beslemesini görüntüler';
$_lang['w_whosonline'] = 'Kimler Çevrimiçi';
$_lang['w_whosonline_desc'] = 'Çevrimiçi kullanıcıların bir listesini gösterir.';
$_lang['w_view_all'] = 'View all';
$_lang['w_no_data'] = 'Görüntülenecek veri yok';