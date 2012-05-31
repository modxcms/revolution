<?php
/**
 * Japanese Drivers Lexicon Topic for Revolution setup
 *
 * @package setup
 * @subpackage lexicon
 * @author honda http://kogus.org
 */
$_lang['mysql_err_ext'] = 'PHPのMySQL拡張が見つかりません。';
$_lang['mysql_err_pdo'] = 'ネイティブのPDOとpdo_mysqlドライバが見つかりません。';
$_lang['mysql_version_5051'] = 'お使いのMySQLのバージョン([[+version]])には、PDOドライバに関する多くの問題があります。問題を解消するため、MySQLをアップグレードしてください。MODXを使用しない場合でも、セキュリティと安定性のためアップグレードをおすすめします。';
$_lang['mysql_version_client_nf'] = 'mysql_get_client_info()関数を使ったMySQL clientのバージョン取得に失敗しました。セットアップを続行する前に、お使いのMySQL clientのバージョンが4.1.20以上である事を手動で確認してください。';
$_lang['mysql_version_client_start'] = 'MySQL clientのバージョンをチェック中:';
$_lang['mysql_version_client_old'] = 'MySQL clientが非常に古いバージョン([[+version]])のため、MODXに問題が発生する可能性があります。MODXのインストールは可能ですが、古いMySQL Clientライブラリでの使用は正常な動作を保証できません。';
$_lang['mysql_version_fail'] = 'MODXは、お使いのMySQLのバージョン([[+version]])をサポートしていません。MySQLをバージョン4.1.20以上にアップグレードして下さい。';
$_lang['mysql_version_server_nf'] = 'mysql_get_server_info()関数を使ったMySQL serverのバージョン取得に失敗しました。セットアップを続行する前に、お使いのMySQL clientのバージョンが4.1.20以上である事を手動で確認してください。';
$_lang['mysql_version_server_start'] = 'MySQL Serverのバージョンをチェック中:';
$_lang['mysql_version_success'] = 'OK! MySQLの動作を確認: バージョン[[+version]]';
$_lang['sqlsrv_version_success'] = 'OK!';
$_lang['sqlsrv_version_client_success'] = 'OK!';