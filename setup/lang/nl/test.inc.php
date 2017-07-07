<?php
/**
 * Test-related English Lexicon Topic for Revolution setup.
 *
 * @package setup
 * @subpackage lexicon
 */
$_lang['test_config_file'] = 'Controleer of <span class="mono">[[+file]]</span> bestaat en schrijfbaar is: ';
$_lang['test_config_file_nw'] = 'Voor nieuwe Linux/Unix installaties, maak een nieuw leeg bestaand aan met de naam <span class="mono">[[+key]].inc.php</span> in de MODX core <span class="mono">config/</span> map, waarbij PHP schrijfrechten heeft.';
$_lang['test_db_check'] = 'Maak verbinding met de database: ';
$_lang['test_db_check_conn'] = 'Controleer de verbinding details en probeer het nog eens.';
$_lang['test_db_failed'] = 'Database verbinding mislukt!';
$_lang['test_db_setup_create'] = 'Installatie zal proberen de database aan te maken.';
$_lang['test_dependencies'] = 'Controleer PHP voor de zlib afhankelijkheid: ';
$_lang['test_dependencies_fail_zlib'] = 'Jouw PHP installatie heeft geen "zlib" extensie ge�nstalleerd. Deze extentie is nodig voor de werking van MODX. Activeer deze en ga dan verder.';
$_lang['test_directory_exists'] = 'Controleer of <span class="mono">[[+dir]]</span> map bestaat: ';
$_lang['test_directory_writable'] = 'Controleer of <span class="mono">[[+dir]]</span> map schrijfbaar is: ';
$_lang['test_memory_limit'] = 'Controleer of de memory_limit is ingesteld op 24M: ';
$_lang['test_memory_limit_fail'] = 'De memory_limit instelling is ingesteld op [[+memory]], wat lager is dan de aanbevolen instelling van 24M. MODX heeft geprobeerd dit limiet te verhogen maar dat is niet gelukt. Pas je php.ini aan om de memory_limit instelling tenminste 24M te geven voordat je doorgaat met de installatie. Mocht je daarna nog steeds problemen hebben (zoals een leeg wit scherm), probeer dan 32M, 64M of hoger.';
$_lang['test_memory_limit_success'] = 'OKE! Ingesteld op [[+memory]]';
$_lang['test_mysql_version_5051'] = 'MODX zal problemen hebben met de geïnstalleerde MySQL versie [[+version]], omdat hier verschillende bugs in zitten gerelateerd aan de PDO drivers. Upgrade MySQL om deze problemen te verhelpen. Ook als je besluit om niet MODX te gebruiken is het aan te raden voor de veiligheid en stabiliteit van je website deze versie te upgraden.';
$_lang['test_mysql_version_client_nf'] = 'Kon de MySQL Client versie niet detecteren.';
$_lang['test_mysql_version_client_nf_msg'] = 'MODX kon jouw MySQL client versie niet detecteren via mysql_get_client_info(). Controleer zelf of jouw MySQL client versie minimaal 4.1.20 is voordat je doorgaat.';
$_lang['test_mysql_version_client_old'] = 'MODX kan mogelijk problemen ondervinden doordat er een erg oude MySQL Client versie is geïnstalleerd ([[+version]]).';
$_lang['test_mysql_version_client_old_msg'] = 'MODX staat installaties met deze MySQL Client versie toe, maar kan niet garanderen dat alle functionaliteiten beschikbaar zijn of correct werken wanner oudere versies in gebruik zijn.';
$_lang['test_mysql_version_client_start'] = 'Controleert jouw MySQL versie:';
$_lang['test_mysql_version_fail'] = 'Je draait MySQL versie [[+version]], en MODX Revolution eist MySQL 4.1.20 of hoger. Upgrade MySQL naar minimaal versie 4.1.20.';
$_lang['test_mysql_version_server_nf'] = 'Kon de MySQL Server versie niet detecteren!';
$_lang['test_mysql_version_server_nf_msg'] = 'MODX kan jouw MySQL versie niet detecteren via mysql_get_server_info(). Controleer zelf of jouw MySQL client versie minimaal 4.1.20 is voordat je doorgaat.';
$_lang['test_mysql_version_server_start'] = 'Controleert MySQL server versie:';
$_lang['test_mysql_version_success'] = 'OKE! Draait: [[+version]]';
$_lang['test_nocompress'] = 'Controleren of CSS/JS compressie moet worden uitgeschakeld: ';
$_lang['test_nocompress_disabled'] = 'OK! Uitgeschakeld.';
$_lang['test_nocompress_skip'] = 'Niet geselecteerd, test overgeslagen.';
$_lang['test_php_version_fail'] = 'De server draait op PHP [[+version]], en MODX Revolution heeft tenminste PHP 5.1.1 of later nodig. Upgrade PHP naar tenminste 5.1.1, maar bij voorkeur naar 5.3.2 of hoger.';
$_lang['test_php_version_start'] = 'Controle PHP versie:';
$_lang['test_php_version_success'] = 'OKE! Draait: [[+version]]';
$_lang['test_safe_mode_start'] = 'Controleren of safe_mode uitgeschakeld is:';
$_lang['test_safe_mode_fail'] = 'MODX heeft gedetecteerd dat safe_mode ingeschakeld is. Om door te gaan met de installatie dien je safe_mode uit te zetten in de PHP configuratie.';
$_lang['test_sessions_start'] = 'Controleren of sessies goed geconfigureerd zijn:';
$_lang['test_simplexml'] = 'Controleren of SimpleXML is geïnstalleerd:';
$_lang['test_simplexml_nf'] = 'Kon SimpleXML niet vinden!';
$_lang['test_simplexml_nf_msg'] = 'MODX kon SimpleXML niet vinden in de PHP configuratie. Package Management en andere functionaliteit in MODX zal niet werken zonder deze PHP extensie. Je kan doorgaan met de installatie, maar om gebruik te maken van deze functies dien je SimpleXML aan te zetten.';
$_lang['test_suhosin'] = 'Suhosin configuratie controleren:';
$_lang['test_suhosin_max_length'] = 'Suhosin GET max value te laag!';
$_lang['test_suhosin_max_length_err'] = 'De suhosin PHP extensie wordt op dit moment gebruikt, en de suhosin.get.max_value_length is te laag ingesteld om in de manager javascript bestanden te comprimeren. MODX raadt aan om deze waarde in te stellen op 4096; tot die tijd zal MODX automatisch javascript compressie (compress_js instelling) uitschakelen om fouten te voorkomen.';
$_lang['test_table_prefix'] = 'Controleer de tabel prefix `[[+prefix]]`: ';
$_lang['test_table_prefix_inuse'] = 'De tabel prefix is reeds ingebruik in deze database!';
$_lang['test_table_prefix_inuse_desc'] = 'De installatie kan de database niet installeren, vanwege al reeds bestaande tabellen met de gespecificeerde prefix. Kies een andere tabel prefix en start de installatie opnieuw.';
$_lang['test_table_prefix_nf'] = 'Tabel prefix bestaat nog niet in deze database!';
$_lang['test_table_prefix_nf_desc'] = 'De installatie kan de database niet installeren, aangezien het geen tabellen met deze prefix bevat om te upgraden. Kies een bestaande tabel prefix en start de installatie opnieuw.';
$_lang['test_zip_memory_limit'] = 'Controleer of de memory_limit is ingesteld op minimaal 24M voor zip extenties: ';
$_lang['test_zip_memory_limit_fail'] = 'MODX heeft jouw memory_limit instelling gevonden maar is lager dan de aanbevolen 24M. MODX probeert deze in te stellen naar 24M, maar dat was onsuccesvol. Stel de memory_limit op 24M of hoger in, in jouw php.ini bestand voordat je doorgaat, zodat de zip extenties naar behoren zullen werken.';