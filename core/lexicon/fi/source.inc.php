<?php
/**
 * Sources English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */
$_lang['access'] = 'Käyttöoikeudet';
$_lang['base_path'] = 'Base Path';
$_lang['base_path_relative'] = 'Base Path Relative?';
$_lang['base_url'] = 'Base URL';
$_lang['base_url_relative'] = 'Base URL Relative?';
$_lang['minimum_role'] = 'Minimum Role';
$_lang['path_options'] = 'Path Options';
$_lang['policy'] = 'Policy';
$_lang['source'] = 'Media Source';
$_lang['source_access_add'] = 'Lisää käyttäjäryhmä';
$_lang['source_access_remove'] = 'Remove Access';
$_lang['source_access_remove_confirm'] = 'Are you sure you want to remove Access to this Source for this User Group?';
$_lang['source_access_update'] = 'Update Access';
$_lang['source_create'] = 'Create New Media Source';
$_lang['source_description_desc'] = 'A short description of the Media Source.';
$_lang['source_duplicate'] = 'Duplicate Media Source';
$_lang['source_err_ae_name'] = 'A Media Source with that name already exists! Please specify a new name.';
$_lang['source_err_nf'] = 'Media Source not found!';
$_lang['source_err_nfs'] = 'No Media Source can be found with the id: [[+id]].';
$_lang['source_err_ns'] = 'Please specify the Media Source.';
$_lang['source_err_ns_name'] = 'Please specify a name for the Media Source.';
$_lang['source_name_desc'] = 'The name of the Media Source.';
$_lang['source_properties.intro_msg'] = 'Manage the properties for this Source below.';
$_lang['source_remove'] = 'Delete Media Source';
$_lang['source_remove_confirm'] = 'Are you sure you want to remove this Media Source? This might break any TVs you have assigned to this source.';
$_lang['source_remove_multiple'] = 'Delete Multiple Media Sources';
$_lang['source_remove_multiple_confirm'] = 'Are you sure you want to delete these Media Sources? This might break any TVs you have assigned to these sources.';
$_lang['source_update'] = 'Update Media Source';
$_lang['source_type'] = 'Source Type';
$_lang['source_type_desc'] = 'The type, or driver, of the Media Source. The Source will use this driver to connect to when gathering its data. For example: File System will grab files from the file system. S3 will get files from an S3 bucket.';
$_lang['source_type.file'] = 'File System';
$_lang['source_type.file_desc'] = 'A filesystem-based source that navigates your server\'s files.';
$_lang['source_type.s3'] = 'Amazon S3';
$_lang['source_type.s3_desc'] = 'Navigates an Amazon S3 bucket.';
$_lang['source_types'] = 'Source Types';
$_lang['source_types.intro_msg'] = 'This is a list of all the installed Media Source Types you have on this MODX instance.';
$_lang['source.access.intro_msg'] = 'Here you can restrict a Media Source to specific User Groups and apply policies for those User Groups. A Media Source with no User Groups attached to it is available to all manager users.';
$_lang['sources'] = 'Media Sources';
$_lang['sources.intro_msg'] = 'Manage all your Media Sources here.';
$_lang['user_group'] = 'Käyttäjäryhmä';

/* file source type */
$_lang['allowedFileTypes'] = 'allowedFileTypes';
$_lang['prop_file.allowedFileTypes_desc'] = 'If set, will restrict the files shown to only the specified extensions. Please specify in a comma-separated list, without the dots preceding the extensions.';
$_lang['basePath'] = 'basePath';
$_lang['prop_file.basePath_desc'] = 'The file path to point the Source to.';
$_lang['basePathRelative'] = 'basePathRelative';
$_lang['prop_file.basePathRelative_desc'] = 'If the Base Path setting above is not relative to the MODX install path, set this to No.';
$_lang['baseUrl'] = 'baseUrl';
$_lang['prop_file.baseUrl_desc'] = 'The URL that this source can be accessed from.';
$_lang['baseUrlPrependCheckSlash'] = 'baseUrlPrependCheckSlash';
$_lang['prop_file.baseUrlPrependCheckSlash_desc'] = 'If true, MODX only will prepend the baseUrl if no forward slash (/) is found at the beginning of the URL when rendering the TV. Useful for setting a TV value outside the baseUrl.';
$_lang['baseUrlRelative'] = 'baseUrlRelative';
$_lang['prop_file.baseUrlRelative_desc'] = 'If the Base URL setting above is not relative to the MODX install URL, set this to No.';
$_lang['imageExtensions'] = 'imageExtensions';
$_lang['prop_file.imageExtensions_desc'] = 'A comma-separated list of file extensions to use as images. MODX will attempt to make thumbnails of files with these extensions.';
$_lang['skipFiles'] = 'skipFiles';
$_lang['prop_file.skipFiles_desc'] = 'A comma-separated list. MODX will skip over and hide files and folders that match any of these.';
$_lang['thumbnailQuality'] = 'thumbnailQuality';
$_lang['prop_file.thumbnailQuality_desc'] = 'The quality of the rendered thumbnails, in a scale from 0-100.';
$_lang['thumbnailType'] = 'thumbnailType';
$_lang['prop_file.thumbnailType_desc'] = 'The image type to render thumbnails as.';

/* s3 source type */
$_lang['bucket'] = 'Bucket';
$_lang['prop_s3.bucket_desc'] = 'The S3 Bucket to load your data from.';
$_lang['prop_s3.key_desc'] = 'The Amazon key for authentication to the bucket.';
$_lang['prop_s3.imageExtensions_desc'] = 'A comma-separated list of file extensions to use as images. MODX will attempt to make thumbnails of files with these extensions.';
$_lang['prop_s3.secret_key_desc'] = 'The Amazon secret key for authentication to the bucket.';
$_lang['prop_s3.skipFiles_desc'] = 'A comma-separated list. MODX will skip over and hide files and folders that match any of these.';
$_lang['prop_s3.thumbnailQuality_desc'] = 'The quality of the rendered thumbnails, in a scale from 0-100.';
$_lang['prop_s3.thumbnailType_desc'] = 'The image type to render thumbnails as.';
$_lang['prop_s3.url_desc'] = 'The URL of the Amazon S3 instance.';
$_lang['s3_no_move_folder'] = 'The S3 driver does not support moving of folders at this time.';
$_lang['prop_s3.region_desc'] = 'Region of the bucket. Example: us-west-1';

/* file type */
$_lang['PNG'] = 'PNG';
$_lang['JPG'] = 'JPG';
$_lang['GIF'] = 'GIF';
