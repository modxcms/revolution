<?php
/**
 * Sources English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */
$_lang['access'] = 'アクセス許可';
$_lang['base_path'] = 'ベースパス';
$_lang['base_path_relative'] = 'ベースパスは相対パスですか？';
$_lang['base_url'] = 'ベースURL';
$_lang['base_url_relative'] = 'ベースURLは相対URLですか？';
$_lang['minimum_role'] = 'ミニマムロール';
$_lang['path_options'] = 'パスオプション';
$_lang['policy'] = 'ポリシー';
$_lang['source'] = 'メディアソース';
$_lang['source_access_add'] = 'ユーザーグループを追加';
$_lang['source_access_remove'] = 'Delete Access';
$_lang['source_access_remove_confirm'] = 'Are you sure you want to delete Access to this Source for this User Group?';
$_lang['source_access_update'] = 'Edit Access';
$_lang['source_description_desc'] = 'メディアソースの説明文を設定します。';
$_lang['source_err_ae_name'] = '指定された名前のメディアソースは既に存在しています。別の名前を指定してください。';
$_lang['source_err_nf'] = 'メディアソースが見つかりませんでした。';
$_lang['source_err_init'] = 'Could not initialize "[[+source]]" Media Source!';
$_lang['source_err_nfs'] = '指定されたIDのメディアソースは存在しません: [[+id]]';
$_lang['source_err_ns'] = 'メディアソースを指定してください。';
$_lang['source_err_ns_name'] = 'メディアソースの名前を指定してください。';
$_lang['source_name_desc'] = 'メディアソースの名前を設定します。';
$_lang['source_properties.intro_msg'] = 'このメディアソースのプロパティは以下の画面で設定します。';
$_lang['source_remove_confirm'] = 'Are you sure you want to delete this Media Source? This might break any TVs you have assigned to this source.';
$_lang['source_remove_multiple_confirm'] = 'これらのメディアソースを本当に削除しますか？　続行すると、指定されたメディアソースに関連付けられているテンプレート変数が正常に機能しなくなります。';
$_lang['source_type'] = 'ソースタイプ';
$_lang['source_type_desc'] = 'メディアソースとして使用するストレージの種類を選択します。MODXは対応したメディアソースドライバを選択することで異なるサービスに保存したデータも提供できます。';
$_lang['source_type.file'] = 'ファイルシステム';
$_lang['source_type.file_desc'] = 'メディアの取得元としてサーバーのファイルシステムを使用します。';
$_lang['source_type.s3'] = 'Amazon S3';
$_lang['source_type.s3_desc'] = 'メディアの取得元としてAmazon S3のバケットを使用します。';
$_lang['source_type.ftp'] = 'File Transfer Protocol';
$_lang['source_type.ftp_desc'] = 'Navigates an FTP remote server.';
$_lang['source_types'] = 'ソースタイプの管理';
$_lang['source_types.intro_msg'] = 'お使いのMODX環境で使用できるメディアソースの種類は以下の通りです。';
$_lang['source.access.intro_msg'] = 'メディアソースにはユーザーグループごとにアクセスポリシーを強制することができます。ユーザーグループの指定を省略すると、管理画面を使用できる全てのユーザーに同じ設定が適用されます。';
$_lang['sources'] = 'メディアソース';
$_lang['sources.intro_msg'] = 'メディアソースを管理します。';
$_lang['user_group'] = 'ユーザーグループ';

/* file source type */
$_lang['allowedFileTypes'] = 'allowedFileTypes';
$_lang['prop_file.allowedFileTypes_desc'] = 'ここに拡張子（ピリオドは不要）を指定することで、表示されるファイルの種類を制限できます。複数の拡張子を指定するときはカンマ区切りで列挙してください。';
$_lang['basePath'] = 'basePath';
$_lang['prop_file.basePath_desc'] = 'The file path to point the Source to, for example: assets/images/<br>The path may depend on the "basePathRelative" parameter';
$_lang['basePathRelative'] = 'basePathRelative';
$_lang['prop_file.basePathRelative_desc'] = 'ベースパスがMODXのインストールされたディレクトリの外にある場合、"いいえ"にしてください。';
$_lang['baseUrl'] = 'baseUrl';
$_lang['prop_file.baseUrl_desc'] = 'The URL that this source can be accessed from, for example: assets/images/<br>The path may depend on the "baseUrlRelative" parameter';
$_lang['baseUrlPrependCheckSlash'] = 'baseUrlPrependCheckSlash';
$_lang['prop_file.baseUrlPrependCheckSlash_desc'] = '有効にすると、テンプレート変数のレンダリング時、スラッシュ（/）がURLの先頭に場合にbaseURLを付加します。ベースURLをテンプレート変数外で設定しておく場合に便利です。';
$_lang['baseUrlRelative'] = 'baseUrlRelative';
$_lang['prop_file.baseUrlRelative_desc'] = 'ベースURLがMODXのインストールされているURLの外にある場合、"いいえ"にしてください。';
$_lang['imageExtensions'] = 'imageExtensions';
$_lang['prop_file.imageExtensions_desc'] = '画像ファイルの拡張子のリストをカンマ区切りで設定します。MODXはこのリストで設定された拡張子のファイルに対応するサムネイル画像を作成します。';
$_lang['skipFiles'] = 'skipFiles';
$_lang['prop_file.skipFiles_desc'] = '表示しないファイル名のリストをカンマ区切りで設定します。MODXはいずれかのパターンに当てはまるファイル、およびディレクトリを表示しません。';
$_lang['thumbnailQuality'] = 'thumbnailQuality';
$_lang['prop_file.thumbnailQuality_desc'] = 'サムネイル画像の品質を0-100の間で設定します。';
$_lang['thumbnailType'] = 'thumbnailType';
$_lang['prop_file.thumbnailType_desc'] = 'サムネイル画像の種類を設定します。';
$_lang['prop_file.visibility_desc'] = 'Default visibility for new files and folders.';
$_lang['no_move_folder'] = 'The Media Source driver does not support moving of folders at this time.';

/* s3 source type */
$_lang['bucket'] = 'バケット';
$_lang['prop_s3.bucket_desc'] = 'Amazon S3のバケットからデータをロードします。';
$_lang['prop_s3.key_desc'] = 'バケットの認証に用いるAmazon Key';
$_lang['prop_s3.imageExtensions_desc'] = '画像ファイルの拡張子のリストをカンマ区切りで設定します。MODXはこのリストで設定された拡張子のファイルに対応するサムネイル画像を作成します。';
$_lang['prop_s3.secret_key_desc'] = 'バケットの認証に使用するAmazon secret key。';
$_lang['prop_s3.skipFiles_desc'] = '表示しないファイル名のリストをカンマ区切りで設定します。MODXはいずれかのパターンに当てはまるファイル、およびディレクトリを表示しません。';
$_lang['prop_s3.thumbnailQuality_desc'] = 'サムネイル画像の品質を0-100の間で設定します。';
$_lang['prop_s3.thumbnailType_desc'] = 'サムネイル画像の種類を設定します。';
$_lang['prop_s3.url_desc'] = 'Amazon S3インスタンスのURL。';
$_lang['prop_s3.endpoint_desc'] = 'Alternative S3-compatible endpoint URL, e.g., "https://s3.<region>.example.com". Review your S3-compatible provider’s documentation for the endpoint location. Leave empty for Amazon S3';
$_lang['prop_s3.region_desc'] = 'Region of the bucket. Example: us-west-1';
$_lang['prop_s3.prefix_desc'] = 'Optional path/folder prefix';
$_lang['s3_no_move_folder'] = 'S3ドライバは、現時点ではフォルダの移動をサポートしていません。';

/* ftp source type */
$_lang['prop_ftp.host_desc'] = 'Server hostname or IP address';
$_lang['prop_ftp.username_desc'] = 'Username for authentication. Can be "anonymous".';
$_lang['prop_ftp.password_desc'] = 'Password of user. Leave empty for anonymous user.';
$_lang['prop_ftp.url_desc'] = 'If this FTP is has a public URL, you can enter its public http-address here. This will also enable image previews in the media browser.';
$_lang['prop_ftp.port_desc'] = 'Port of the server, default is 21.';
$_lang['prop_ftp.root_desc'] = 'The root folder, it will be opened after connection';
$_lang['prop_ftp.passive_desc'] = 'Enable or disable passive ftp mode';
$_lang['prop_ftp.ssl_desc'] = 'Enable or disable ssl connection';
$_lang['prop_ftp.timeout_desc'] = 'Timeout for connection in seconds.';

/* file type */
$_lang['PNG'] = 'PNG';
$_lang['JPG'] = 'JPG';
$_lang['GIF'] = 'GIF';
