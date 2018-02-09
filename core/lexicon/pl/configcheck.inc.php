<?php
/**
 * Config Check English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */
$_lang['configcheck_admin'] = 'Proszę skontaktować się z administratorem systemu i poinformować go o tej wiadomości!';
$_lang['configcheck_allowtagsinpost_context_enabled'] = 'allow_tags_in_post Context Setting Enabled outside `mgr`';
$_lang['configcheck_allowtagsinpost_context_enabled_msg'] = 'The allow_tags_in_post Context Setting is enabled in your installation outside the mgr Context. MODX recommends this setting be disabled unless you need to explicitly allow users to submit MODX tags, numeric entities, or HTML script tags via the POST method to a form in your site. This should generally be disabled except in the mgr Context.';
$_lang['configcheck_allowtagsinpost_system_enabled'] = 'allow_tags_in_post System Setting Enabled';
$_lang['configcheck_allowtagsinpost_system_enabled_msg'] = 'The allow_tags_in_post System Setting is enabled in your installation. MODX recommends this setting be disabled unless you need to explicitly allow users to submit MODX tags, numeric entities, or HTML script tags via the POST method to a form in your site. It is better to enable this via Context Settings for specific Contexts.';
$_lang['configcheck_cache'] = 'Cache directory not writable';
$_lang['configcheck_cache_msg'] = 'MODX cannot write to the cache directory. MODX will still function as expected, but no caching will take place. To solve this, make the /_cache/ directory writable.';
$_lang['configcheck_configinc'] = 'Plik konfiguracyjny jest edytowalny!';
$_lang['configcheck_configinc_msg'] = 'Witryna jest narażona na atak hakerów, którzy mogą wyrządzić wiele szkód na stronie. Proszę o ustawienie pliku config tylko do odczytu! Jeśli nie jesteś administratorem, prosimy o kontakt z administratorem systemów i przekazanie mu tej wiadomość! Plik config znajdziesz w [[+path]]';
$_lang['configcheck_default_msg'] = 'Nieokreślone ostrzeżenie zostało znalezione. Co jest dziwne.';
$_lang['configcheck_errorpage_unavailable'] = 'Your site\'s Error page is not available.';
$_lang['configcheck_errorpage_unavailable_msg'] = 'This means that your Error page is not accessible to normal web surfers or does not exist. This can lead to a recursive looping condition and many errors in your site logs. Make sure there are no webuser groups assigned to the page.';
$_lang['configcheck_errorpage_unpublished'] = 'Your site\'s Error page is not published or does not exist.';
$_lang['configcheck_errorpage_unpublished_msg'] = 'This means that your Error page is inaccessible to the general public. Publish the page or make sure it is assigned to an existing document in your site tree in the System &gt; System Settings menu.';
$_lang['configcheck_htaccess'] = 'Core folder is accessible by web';
$_lang['configcheck_htaccess_msg'] = 'MODX detected that your core folder is (partially) accessible to the public.
<strong>This is not recommended and a security risk.</strong>
If your MODX installation is running on a Apache webserver
you should at least set up the .htaccess file inside the core folder <em>[[+fileLocation]]</em>.
This can be easily done by renaming the existing ht.access example file there to .htaccess.
<p>There are other methods and webservers you may use, please read the <a href="https://rtfm.modx.com/revolution/2.x/administering-your-site/security/hardening-modx-revolution">Hardening MODX Guide</a>
for further information about securing your site.</p>
If you setup everything correctly, browsing e.g. to the <a href="[[+checkUrl]]" target="_blank">Changelog</a>
should give you a 403 (permission denied) or better a 404 (not found). If you can see the changelog
there in the browser, something is still wrong and you need to reconfigure or call an expert to solve this.';
$_lang['configcheck_images'] = 'Brak uprawnień do zapisu w katalogu Images';
$_lang['configcheck_images_msg'] = 'The images directory isn\'t writable, or doesn\'t exist. This means the Image Manager functions in the editor will not work!';
$_lang['configcheck_installer'] = 'Installer still present';
$_lang['configcheck_installer_msg'] = 'The setup/ directory contains the installer for MODX. Just imagine what might happen if an evil person finds this folder and runs the installer! They probably won\'t get too far, because they\'ll need to enter some user information for the database, but it\'s still best to remove this folder from your server. It is located at: [[+path]]';
$_lang['configcheck_lang_difference'] = 'Nieprawidłowa liczba wpisów w pliku języka';
$_lang['configcheck_lang_difference_msg'] = 'The currently selected language has a different number of entries than the default language. While not necessarily a problem, this may mean the language file needs to be updated.';
$_lang['configcheck_notok'] = 'One or more configuration details didn\'t check out OK: ';
$_lang['configcheck_ok'] = 'Check passed OK - no warnings to report.';
$_lang['configcheck_phpversion'] = 'PHP version is outdated';
$_lang['configcheck_phpversion_msg'] = 'Your PHP version [[+phpversion]] is no longer maintained by the PHP developers, which means no security updates are available. It is also likely that MODX or an extra package now or in the near future will no longer support this version. Please update your environment at least to PHP [[+phprequired]] as soon as possible to secure your site.';
$_lang['configcheck_register_globals'] = 'register_globals is set to ON in your php.ini configuration file';
$_lang['configcheck_register_globals_msg'] = 'This configuration makes your site much more susceptible to Cross Site Scripting (XSS) attacks. You should speak to your host about what you can do to disable this setting.';
$_lang['configcheck_title'] = 'Sprawdzanie konfiguracji';
$_lang['configcheck_unauthorizedpage_unavailable'] = 'Your site\'s Unauthorized page is not published or does not exist.';
$_lang['configcheck_unauthorizedpage_unavailable_msg'] = 'This means that your Unauthorized page is not accessible to normal web surfers or does not exist. This can lead to a recursive looping condition and many errors in your site logs. Make sure there are no webuser groups assigned to the page.';
$_lang['configcheck_unauthorizedpage_unpublished'] = 'The Unauthorized page defined in the site configuration settings is not published.';
$_lang['configcheck_unauthorizedpage_unpublished_msg'] = 'This means that your Unauthorized page is inaccessible to the general public. Publish the page or make sure it is assigned to an existing document in your site tree in the System &gt; System Settings menu.';
$_lang['configcheck_warning'] = 'Ostrzeżenie konfiguracji:';
$_lang['configcheck_what'] = 'Co to oznacza?';
