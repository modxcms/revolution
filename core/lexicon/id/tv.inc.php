<?php
/**
 * TV English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */
$_lang['example_tag_tvname'] = 'NameOfTV';
$_lang['has_access'] = 'Memiliki akses?';
$_lang['filter_by_category'] = 'Filter berdasarkan kategori...';
$_lang['rank'] = 'Peringkat';
$_lang['rendering_options'] = 'Render pilihan';
$_lang['tv'] = 'TV';
$_lang['tvs'] = 'Variabel template';
$_lang['tv_binding_msg'] = 'Bidang ini mendukung sumber data binding menggunakan @ perintah';
$_lang['tv_caption'] = 'Keterangan';
$_lang['tv_caption_desc'] = 'The label shown for this TV in Resource editing pages (can be overridden per template or other criteria using <a href="?a=security/forms" target="_blank">Form Customization</a>).';
$_lang['tv_category_desc'] = 'Use to group TVs in Resource editing pages and within the Elements tree.';
$_lang['tv_change_template_msg'] = 'Changing this template will cause the page to reload the TVs, losing any unsaved changes.<br /><br /> Are you sure you want to change this template?';
$_lang['tv_delete_confirm'] = 'Yakin ingin menghapus TV ini?';
$_lang['tv_description'] = 'Deskripsi';
$_lang['tv_description_desc'] = 'Usage information for this TV shown next its caption in Resource editing pages and as a tooltip in the Elements tree.';
$_lang['tv_err_delete'] = 'An error occurred while trying to delete the TV.';
$_lang['tv_err_duplicate'] = 'An error occurred while trying to duplicate the TV.';
$_lang['tv_err_duplicate_templates'] = 'Terjadi kesalahan saat duplikasi template TV.';
$_lang['tv_err_duplicate_documents'] = 'Terjadi kesalahan saat duplikasi TV dokumen.';
$_lang['tv_err_duplicate_documentgroups'] = 'Terjadi kesalahan saat duplikasi TV dokumen kelompok.';
$_lang['tv_err_ae'] = 'A TV already exists with the name "[[+name]]".';
$_lang['tv_err_invalid_name'] = 'TV name is invalid.';
$_lang['tv_err_invalid_id_attr'] = 'HTML ids must not begin with a number or contain any white space.';
$_lang['tv_err_locked'] = 'TV locked!';
$_lang['tv_err_nf'] = 'TV not found.';
$_lang['tv_err_nfs'] = 'TV not found with key: [[+id]]';
$_lang['tv_err_ns'] = 'TV not specified.';
$_lang['tv_err_reserved_name'] = 'A TV cannot have the same name as a Resource field.';
$_lang['tv_err_save_access_permissions'] = 'An error occured while attempting to save TV access permissions.';
$_lang['tv_err_save'] = 'An error occurred while saving the TV.';
$_lang['tv_inuse'] = 'The following document(s) are currently using this TV. To continue with the delete operation click the Delete button otherwise click the Cancel button.';
$_lang['tv_inuse_template'] = 'Template(s) berikut saat ini menggunakan TV ini: [[+templates]]. <br /><br />Lepaskan TV dari template(s) sebelum menghapusnya.';
$_lang['tv_lock'] = 'Restrict Editing';
$_lang['tv_lock_desc'] = 'Only users with “edit_locked” permissions can edit this TV.';
$_lang['tv_management_msg'] = 'Manage additional custom TVs for your documents.';
$_lang['tv_name'] = 'TV Name';
$_lang['tv_name_desc'] = 'Place the content generated by this TV in a Resource, Template, or Chunk using the following MODX tag: <span class="copy-this">[[*<span class="example-replace-name">'.$_lang['example_tag_tvname'].'</span>]]</span>';
$_lang['tv_new'] = 'Create TV';
$_lang['tv_novars'] = 'No TVs found';
$_lang['tv_properties'] = 'Properti bawaan';
$_lang['tv_rank'] = 'Urutan';
$_lang['tv_rank_desc'] = 'Use to control the positioning of this TV in Resource editing pages (can be overridden per template or other criteria using <a href="?a=security/forms" target="_blank">Form Customization</a>).';
$_lang['tv_reset_params'] = 'Reset parameter';
$_lang['tv_tab_access_desc'] = 'Select the Resource Groups that this TV belongs to. Only users with access to the Groups selected will be able to modify this TV. If no Groups are selected, all users with access to the Manager will be able to modify the TV.';
$_lang['tv_tab_general_desc'] = 'Here you can create/edit a <dfn>Template Variable</dfn> (TV). TVs must be assigned to templates in order to access them from snippets and documents.';
$_lang['tv_tab_input_options'] = 'Pilihan input';
$_lang['tv_tab_input_options_desc'] = '<p>Here you can edit the input options for the TV, specific to the type of input render that you select.</p>';
$_lang['tv_tab_output_options'] = 'Pilihan output';
$_lang['tv_tab_output_options_desc'] = '<p>Here you can edit the output options for the TV, specific to the type of output render that you select.</p>';
$_lang['tv_tab_sources_desc'] = 'Di sini Anda dapat menetapkan sumber-sumber Media yang digunakan untuk TV ini dalam konteks tertentu masing-masing. Klik dua kali pada nama sumber dalam grid untuk mengubahnya.';
$_lang['tv_tab_tmpl_access'] = 'Akses template';
$_lang['tv_tab_tmpl_access_desc'] = 'Select the templates that are allowed to access this TV.';
$_lang['tv_tag_copied'] = 'TV tag copied!';
$_lang['tv_widget'] = 'Widget';
$_lang['tv_widget_prop'] = 'Properti Widget';
$_lang['tvd_err_remove'] = 'An error occurred while trying to delete the TV from the document.';
$_lang['tvdg_err_remove'] = 'An error occurred while trying to delete the TV from the document group.';
$_lang['tvdg_err_save'] = 'An error occurred while trying to attach the TV to the document group.';
$_lang['tvt_err_nf'] = 'TV does not have access to the specified template.';
$_lang['tvt_err_remove'] = 'An error occurred while trying to delete the TV from the template.';
$_lang['tvt_err_save'] = 'An error occurred while trying to attach the TV to the template.';

// Temporarily match old keys to new ones to ensure compatibility
// -- fields
$_lang['tv_desc_caption'] = $_lang['tv_caption_desc'];
$_lang['tv_desc_category'] = $_lang['tv_category_desc'];
$_lang['tv_desc_description'] = $_lang['tv_description_desc'];
$_lang['tv_desc_name'] = $_lang['tv_name_desc'];
$_lang['tv_lock_msg'] = $_lang['tv_lock_desc'];
$_lang['tv_rank_msg'] = $_lang['tv_rank_desc'];

// -- tabs
$_lang['tv_access_msg'] = $_lang['tv_tab_access_desc'];
$_lang['tv_input_options'] = $_lang['tv_tab_input_options'];
$_lang['tv_input_options_msg'] = $_lang['tv_tab_input_options_desc'];
$_lang['tv_msg'] = $_lang['tv_tab_general_desc'];
$_lang['tv_output_options'] = $_lang['tv_tab_output_options'];
$_lang['tv_output_options_msg'] = $_lang['tv_tab_output_options_desc'];
$_lang['tv_sources.intro_msg'] = $_lang['tv_tab_sources_desc'];
$_lang['tv_tmpl_access'] = $_lang['tv_tab_tmpl_access'];
$_lang['tv_tmpl_access_msg'] = $_lang['tv_tab_tmpl_access_desc'];
