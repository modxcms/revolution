<?php
/**
 * TV English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */
$_lang['example_tag_tv_name'] = 'NameOfTV';
$_lang['has_access'] = 'Má přístup?';
$_lang['filter_by_category'] = 'Filtrovat podle kategorie...';
$_lang['rank'] = 'Pořadí';
$_lang['rendering_options'] = 'Zobrazovací možnosti';
$_lang['tv'] = 'TV';
$_lang['tvs'] = 'Template variables';
$_lang['tv_binding_msg'] = 'Toto políčko podporuje zadávat data pomocí @ příkazu (tzv. bindování)';
$_lang['tv_caption'] = 'Titulek';
$_lang['tv_caption_desc'] = 'The label shown for this TV in Resource editing pages (can be overridden per template or other criteria using <a href="?a=security/forms" target="_blank">Form Customization</a>).';
$_lang['tv_category_desc'] = 'Use to group TVs in Resource editing pages and within the Elements tree.';
$_lang['tv_change_template_msg'] = 'Changing this template will cause the page to reload the TVs, losing any unsaved changes.<br /><br /> Are you sure you want to change this template?';
$_lang['tv_delete_confirm'] = 'Jste si jisti, že chcete odstranit tuto TV?';
$_lang['tv_description'] = 'Popis';
$_lang['tv_description_desc'] = 'Usage information for this TV shown next to its caption in Resource editing pages, as a tooltip in the Elements tree, and within search results.';
$_lang['tv_err_delete'] = 'An error occurred while trying to delete the TV.';
$_lang['tv_err_duplicate'] = 'An error occurred while trying to duplicate the TV.';
$_lang['tv_err_duplicate_templates'] = 'Nastala chyba při kopírování template variable šablon.';
$_lang['tv_err_duplicate_documents'] = 'Nastala chyba při kopírování template variable dokumentů.';
$_lang['tv_err_duplicate_documentgroups'] = 'Nastala chyba při kopírování template variable skupin dokumentů.';
$_lang['tv_err_ae'] = 'A TV already exists with the name "[[+name]]".';
$_lang['tv_err_invalid_name'] = 'TV name is invalid.';
$_lang['tv_err_invalid_id_attr'] = 'HTML ids must not begin with a number or contain any white space.';
$_lang['tv_err_locked'] = 'TV locked!';
$_lang['tv_err_nf'] = 'TV not found.';
$_lang['tv_err_nfs'] = 'TV not found with key: [[+id]]';
$_lang['tv_err_ns'] = 'TV not specified.';
$_lang['tv_err_reserved_name'] = 'A TV cannot have the same name as a Resource field.';
$_lang['tv_err_save_access_permissions'] = 'An error occurred while attempting to save TV access permissions.';
$_lang['tv_err_save'] = 'An error occurred while saving the TV.';
$_lang['tv_inuse'] = 'The following document(s) are currently using this TV. To continue with the delete operation click the Delete button otherwise click the Cancel button.';
$_lang['tv_inuse_template'] = 'Následující šablony aktuálně používají tuto TV: [[+templates]].<br/><br/>Prosím, odpojte TV ze šablon před jejich odstraněním.';
$_lang['is_static_tv_desc'] = 'Use an external file to store the default value for this TV. This may be useful if the default value’s content is particularly lengthy.';
$_lang['tv_lock'] = 'Restrict Editing';
$_lang['tv_lock_desc'] = 'Only users with “edit_locked” permissions can edit this TV.';
$_lang['tv_management_msg'] = 'Manage additional custom TVs for your documents.';
$_lang['tv_name_desc'] = 'Umístěte obsah generovaný touto TV do dokumentu, šablony nebo chunku pomocí následující MODX značky: [[+tag]]';
$_lang['tv_new'] = 'Create TV';
$_lang['tv_novars'] = 'No TVs found';
$_lang['tv_properties'] = 'Výchozí vlastnosti';
$_lang['tv_rank'] = 'Řazení';
$_lang['tv_rank_desc'] = 'Use to control the positioning of this TV in Resource editing pages (can be overridden per template or other criteria using <a href="?a=security/forms" target="_blank">Form Customization</a>).';
$_lang['tv_reset_params'] = 'Resetovat parametry';
$_lang['tv_tab_access_desc'] = 'Select the Resource Groups that this TV belongs to. Only users with access to the Groups selected will be able to modify this TV. If no Groups are selected, all users with access to the Manager will be able to modify the TV.';
$_lang['tv_tab_general_desc'] = 'Here you can enter the basic attributes for this <em>Template Variable</em> (TV). Note that TVs must be assigned to templates in order to access them from snippets and documents.';
$_lang['tv_tab_input_options'] = 'Možnosti vstupu';
$_lang['tv_tab_input_options_desc'] = '<p>Here you can edit the input options for the TV, specific to the type of input render that you select.</p>';
$_lang['tv_tab_output_options'] = 'Možnosti výstupu';
$_lang['tv_tab_output_options_desc'] = '<p>Here you can edit the output options for the TV, specific to the type of output render that you select.</p>';
$_lang['tv_tab_sources_desc'] = 'Zde můžete přiřadit zdroje médií, které budou použity v každém specifikovaném kontextu. Pro změnu poklikejte na název zdroje v tabulce.';
$_lang['tv_tab_tmpl_access'] = 'Přístup k šabloně';
$_lang['tv_tab_tmpl_access_desc'] = 'Select the templates that are allowed to access this TV.';
$_lang['tv_tag_copied'] = 'TV tag copied!';
$_lang['tv_widget'] = 'Widget';
$_lang['tv_widget_prop'] = 'Vlastnosti widgetu';
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

/*
    Refer to default.inc.php for the keys below.
    (Placement in this default file necessary to allow
    quick create/edit panels access to them when opened
    outside the context of their respective element types)

    tv_caption_desc
    tv_category_desc
    tv_description_desc

*/
