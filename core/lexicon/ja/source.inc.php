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
$_lang['source_access_remove'] = 'アクセス権限の削除';
$_lang['source_access_remove_confirm'] = 'このアクセス権限エントリーを本当に削除しますか？';
$_lang['source_access_update'] = 'アクセス権限の編集';
$_lang['source_create'] = '新規メディアソース';
$_lang['source_description_desc'] = 'メディアソースの説明文を設定します。';
$_lang['source_duplicate'] = 'メディアソースの複製';
$_lang['source_err_ae_name'] = '指定された名前のメディアソースは既に存在しています。別の名前を指定してください。';
$_lang['source_err_nf'] = 'メディアソースが見つかりませんでした。';
$_lang['source_err_nfs'] = '指定されたIDのメディアソースは存在しません: [[+id]]';
$_lang['source_err_ns'] = 'メディアソースを指定してください。';
$_lang['source_err_ns_name'] = 'メディアソースの名前を指定してください。';
$_lang['source_name_desc'] = 'メディアソースの名前を設定します。';
$_lang['source_properties.intro_msg'] = 'このメディアソースのプロパティは以下の画面で設定します。';
$_lang['source_remove'] = 'メディアソースの削除';
$_lang['source_remove_confirm'] = 'このメディアソースを本当に削除しますか？　続行すると、このメディアソースに関連付けられているテンプレート変数が正常に機能しなくなります。';
$_lang['source_remove_multiple'] = 'メディアソースの一括削除';
$_lang['source_remove_multiple_confirm'] = 'これらのメディアソースを本当に削除しますか？　続行すると、指定されたメディアソースに関連付けられているテンプレート変数が正常に機能しなくなります。';
$_lang['source_update'] = 'メディアソースの編集';
$_lang['source_type'] = 'ソースタイプ';
$_lang['source_type_desc'] = 'メディアソースとして使用するストレージの種類を選択します。MODXは対応したメディアソースドライバを選択することで異なるサービスに保存したデータも提供できます。';
$_lang['source_type.file'] = 'ファイルシステム';
$_lang['source_type.file_desc'] = 'メディアの取得元としてサーバーのファイルシステムを使用します。';
$_lang['source_type.s3'] = 'Amazon S3';
$_lang['source_type.s3_desc'] = 'メディアの取得元としてAmazon S3のバケットを使用します。';
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
$_lang['prop_file.basePath_desc'] = 'ソースの場所を示すファイルパス。';
$_lang['basePathRelative'] = 'basePathRelative';
$_lang['prop_file.basePathRelative_desc'] = 'ベースパスがMODXのインストールされたディレクトリの外にある場合、"いいえ"にしてください。';
$_lang['baseUrl'] = 'baseUrl';
$_lang['prop_file.baseUrl_desc'] = 'このソースにアクセスするためのURL';
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
$_lang['s3_no_move_folder'] = 'S3ドライバは、現時点ではフォルダの移動をサポートしていません。';
$_lang['prop_s3.region_desc'] = 'Region of the bucket. Example: us-west-1';

/* file type */
$_lang['PNG'] = 'PNG';
$_lang['JPG'] = 'JPG';
$_lang['GIF'] = 'GIF';
