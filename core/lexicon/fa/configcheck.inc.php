<?php
/**
 * Config Check English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */
$_lang['configcheck_admin'] = 'لطفا با سرپرست سیستم تماس گرفته و او را از این پیام آگاه کنید!';
$_lang['configcheck_allowtagsinpost_context_enabled'] = 'گزینه‌ی allow_tags_in_post در خارج از کانتکستِ `mgr` فعال است';
$_lang['configcheck_allowtagsinpost_context_enabled_msg'] = 'The allow_tags_in_post Context Setting is enabled in your installation outside the mgr Context. MODX recommends this setting be disabled unless you need to explicitly allow users to submit MODX tags, numeric entities, or HTML script tags via the POST method to a form in your site. This should generally be disabled except in the mgr Context.';
$_lang['configcheck_allowtagsinpost_system_enabled'] = 'گزینه‌ی allow_tags_in_post در تنظیمات سیستم فعال است';
$_lang['configcheck_allowtagsinpost_system_enabled_msg'] = 'The allow_tags_in_post System Setting is enabled in your installation. MODX recommends this setting be disabled unless you need to explicitly allow users to submit MODX tags, numeric entities, or HTML script tags via the POST method to a form in your site. It is better to enable this via Context Settings for specific Contexts.';
$_lang['configcheck_cache'] = 'دایرکتوری کَش دارای مجور نوشتن نیست';
$_lang['configcheck_cache_msg'] = 'مادایکس قادر به استفاده از دایرکتوری کَش نیست. سیستم همچنان به فعالیت خود ادامه خواهد داد ولی بدون هیچگونه عملیات کَش. برای رفع این مشکل، لطفا مجوزِ نوشتن را برای دایرکتوری /cache_/ فعال نمایید.';
$_lang['configcheck_configinc'] = 'فایل پیکربندی (Config) همچنان دارای مجوز نوشتن است!';
$_lang['configcheck_configinc_msg'] = 'سایت شما نسبت به حملات هکرها بسیار آسیب‌پذیر است. آنها می‌توانند مشکلات فراوانی برای سایت ایجاد کنند. لطفا مجوزهای فایل پیکربندی (config) را به «فقط خواندنی» (read only) تغییر دهید. اگر شما سرپرست سیستم نیستید لطفا مدیران و سرپرستان سیستم را از این هشدار مطلع نمایید. مورد تهدیدآمیز در اینجا واقع شده: [[+path]]';
$_lang['configcheck_default_msg'] = 'خطای نامشخصی رخ داده که بسیار مشکوک است.';
$_lang['configcheck_errorpage_unavailable'] = 'هنوز پرونده‌ای به عنوانِ صفحه‌ی خطا، برای سایت شما تعیین نشده است.';
$_lang['configcheck_errorpage_unavailable_msg'] = 'This means that your Error page is not accessible to normal web surfers or does not exist. This can lead to a recursive looping condition and many errors in your site logs. Make sure there are no webuser groups assigned to the page.';
$_lang['configcheck_errorpage_unpublished'] = 'پرونده‌ای به عنوانِ صفحه‌ی خطا، برای سایت شما تعیین و یا منتشر نشده است.';
$_lang['configcheck_errorpage_unpublished_msg'] = 'This means that your Error page is inaccessible to the general public. Publish the page or make sure it is assigned to an existing document in your site tree in the System &gt; System Settings menu.';
$_lang['configcheck_htaccess'] = 'Core folder is accessible by web';
$_lang['configcheck_htaccess_msg'] = 'MODX detected that your core folder is (partially) accessible to the public.
<strong>This is not recommended and a security risk.</strong>
If your MODX installation is running on a Apache webserver
you should at least set up the .htaccess file inside the core folder <em>[[+fileLocation]]</em>.
This can be easily done by renaming the existing ht.access example file there to .htaccess.
<p>There are other methods and webservers you may use, please read the <a href="https://docs.modx.com/current/en/getting-started/maintenance/securing-modx">Hardening MODX Guide</a>
for further information about securing your site.</p>
If you setup everything correctly, browsing e.g. to the <a href="[[+checkUrl]]" target="_blank">Changelog</a>
should give you a 403 (permission denied) or better a 404 (not found). If you can see the changelog
there in the browser, something is still wrong and you need to reconfigure or call an expert to solve this.';
$_lang['configcheck_images'] = 'دایرکتوری تصاویر (images) دارای مجوز نوشتن نیست';
$_lang['configcheck_images_msg'] = 'دایرکتوری تصاویر (images) یا موجود نیست و یا دارای مجوز نوشتن نیست. این بدین معناست که امکانات ابزار «مدیریت تصاویر» در ویرایشگر، قابل استفاده نخواهد بود!';
$_lang['configcheck_installer'] = 'نصب کننده‌ی مادایکس همچنان قابل دسترسی است';
$_lang['configcheck_installer_msg'] = 'The setup/ directory contains the installer for MODX. Just imagine what might happen if an evil person finds this folder and runs the installer! They probably won\'t get too far, because they\'ll need to enter some user information for the database, but it\'s still best to remove this folder from your server. It is located at: [[+path]]';
$_lang['configcheck_lang_difference'] = 'فایل زبان دارای تعداد نادرستی از کلیدهاست';
$_lang['configcheck_lang_difference_msg'] = 'The currently selected language has a different number of entries than the default language. While not necessarily a problem, this may mean the language file needs to be updated.';
$_lang['configcheck_notok'] = 'برخی از موارد پیکربندی به درستی بررسی نشدند: ';
$_lang['configcheck_ok'] = 'نتیجه‌ی بررسی مطلوب است و مشکلی وجود ندارد.';
$_lang['configcheck_phpversion'] = 'PHP version is outdated';
$_lang['configcheck_phpversion_msg'] = 'Your PHP version [[+phpversion]] is no longer maintained by the PHP developers, which means no security updates are available. It is also likely that MODX or an extra package now or in the near future will no longer support this version. Please update your environment at least to PHP [[+phprequired]] as soon as possible to secure your site.';
$_lang['configcheck_register_globals'] = 'گزینه‌ی register_globals در فایل پیکربندیِ php.ini فعال است';
$_lang['configcheck_register_globals_msg'] = 'This configuration makes your site much more susceptible to Cross Site Scripting (XSS) attacks. You should speak to your host about what you can do to disable this setting.';
$_lang['configcheck_title'] = 'بررسی پیکربندی';
$_lang['configcheck_unauthorizedpage_unavailable'] = 'هنوز پرونده‌ای به عنوانِ صفحه‌ی «غیرقابل دسترسی»، برای سایت شما تعیین و یا منتشر نشده است.';
$_lang['configcheck_unauthorizedpage_unavailable_msg'] = 'This means that your Unauthorized page is not accessible to normal web surfers or does not exist. This can lead to a recursive looping condition and many errors in your site logs. Make sure there are no webuser groups assigned to the page.';
$_lang['configcheck_unauthorizedpage_unpublished'] = 'پرونده‌ای به عنوانِ صفحه‌ی «غیرقابل دسترسی»، برای سایت شما تعیین شده ولی هنوز از طریق تنظیماتش، منتشر نشده است.';
$_lang['configcheck_unauthorizedpage_unpublished_msg'] = 'This means that your Unauthorized page is inaccessible to the general public. Publish the page or make sure it is assigned to an existing document in your site tree in the System &gt; System Settings menu.';
$_lang['configcheck_warning'] = 'هشدار پیکربندی:';
$_lang['configcheck_what'] = 'توضیح بیشتر';
