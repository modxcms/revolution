<?php
/**
 * Access Policy English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */
$_lang['active_of'] = '[[+active]] / [[+total]]';
$_lang['active_permissions'] = 'Aktiivsed õigused';
$_lang['no_policy_option'] = ' (poliis puudub) ';
$_lang['permission'] = 'Permission';
$_lang['permission_add'] = 'Lisa Õigus';
$_lang['permission_add_template'] = 'Lisa Õigus Templatele';
$_lang['permission_err_ae'] = 'Õigus juba ekisteerib selle poliisi jaoks.';
$_lang['permission_err_nf'] = 'Õigust ei leitud.';
$_lang['permission_err_ns'] = 'Õigust ei olnud määratud.';
$_lang['permission_err_remove'] = 'An error occurred while trying to delete this permission.';
$_lang['permission_err_save'] = 'Tekkis viga selle õiguse salvestamisel.';
$_lang['permission_new'] = 'Create Permission';
$_lang['permission_remove'] = 'Eemalda Õigus';
$_lang['permission_remove_confirm'] = 'Are you sure you want to delete this permission?';
$_lang['permission_update'] = 'Edit Permission';
$_lang['permissions'] = 'Õigused';
$_lang['permissions_desc'] = 'Siit saate määrata spetsiifilised õigused, mida see poliis sisaldab. Kõik kasutajate grupid selle poliisiga pärivad need õigused.';
$_lang['policies'] = 'Juudepääsu Poliisid';
$_lang['policy'] = 'Juudepääsu Poliis';
$_lang['policy_create'] = 'Loo Juudepääsu Poliis';
$_lang['policy_data'] = 'Poliisi Andmed';
$_lang['policy_desc'] = 'Juudepääsu poliisid on üldised poliisid, mis piiravad või lubavad kindlaid tegevusi teha MODX-is.';
$_lang['policy_desc_name'] = 'The name of the Access Policy';
$_lang['policy_desc_description'] = 'Optional. A short description of the Access Policy';
$_lang['policy_desc_template'] = 'The Policy Template used for this Policy. Policies get their Permission lists from their Template.';
$_lang['policy_desc_lexicon'] = 'Optional. The Lexicon Topic that this Policy uses to translate the Permissions it owns.';
$_lang['policy_duplicate'] = 'Dubleeri Poliisi';
$_lang['policy_duplicate_confirm'] = 'Oled kindel, et soovid dubleerida seda poliisi ja kõiki selle andmeid?';
$_lang['policy_err_ae'] = 'Poliis nimega `[[+name]]` juba eksisteerib. Palun valige teine nimi.';
$_lang['policy_err_nf'] = 'Poliisi ei leitud.';
$_lang['policy_err_ns'] = 'Poliisi ei olnud määratud.';
$_lang['policy_err_remove'] = 'An error occurred while trying to delete the Policy.';
$_lang['policy_err_save'] = 'Tekkis viga Poliisi salvestamisel.';
$_lang['policy_export'] = 'Export Policy';
$_lang['policy_import'] = 'Import Policy';
$_lang['policy_import_msg'] = 'Select an XML file to import a Policy from. It must be in the correct XML Policy format.';
$_lang['policy_management'] = 'Juudepääsu Poliisid';
$_lang['policy_management_msg'] = 'Juudepääsu Poliisid haldavad kuidas MODX  käsitleb õigusi spetsiifilistele tegevustele.';
$_lang['policy_name'] = 'Poliisi Nimi';
$_lang['policy_property_create'] = 'Loo Juurdepääsu Poliisi Omadus';
$_lang['policy_property_new'] = 'Create Policy Property';
$_lang['policy_property_remove'] = 'Kustuta Juurdepääsu Poliisi Omadus';
$_lang['policy_property_specify_name'] = 'Palun määra poliisi omaduse nimi:';
$_lang['policy_remove'] = 'Eemalda Pollis';
$_lang['policy_remove_confirm'] = 'Are you sure you want to delete this Access Policy?';
$_lang['policy_remove_multiple'] = 'Eemalda Poliisid';
$_lang['policy_remove_multiple_confirm'] = 'Are you sure you want to delete these Access Policies? This is irreversible.';
$_lang['policy_update'] = 'Edit Policy';
$_lang['policy_template'] = 'Poliisi Template';
$_lang['policy_template.desc'] = 'A Policy Template defines which Permissions will show up in the Permissions grid when editing a specific Policy. You can add or delete specific Permissions from this template below. Note that deleting a Permission from a Template will delete it from any Policies that use this Template.';
$_lang['policy_template_create'] = 'Loo Poliisi Template';
$_lang['policy_template_desc_name'] = 'The name of the Access Policy Template';
$_lang['policy_template_desc_description'] = 'Optional. A short description of the Access Policy Template';
$_lang['policy_template_desc_lexicon'] = 'Optional. The Lexicon Topic that this Policy Template uses to translate the Permissions it owns.';
$_lang['policy_template_desc_template_group'] = 'The Policy Template Group to use. This is used when selecting Policies from a dropdown menu; usually they are filtered by template group. Select an appropriate group for your Policy Template.';
$_lang['policy_template_duplicate'] = 'Dubleeri Poliisi Templatet';
$_lang['policy_template_duplicate_confirm'] = 'Olete kindel, et soovite dubleerida Poliisi Templatet?';
$_lang['policy_template_err_ae'] = 'Poliisi template nimega `[[+name]]` juba eksisteerib. Palun valige teine nimi.';
$_lang['policy_template_err_nf'] = 'Poliisi Templatet ei leitud.';
$_lang['policy_template_err_ns'] = 'Poliisi Templatet ei olnud määratud.';
$_lang['policy_template_err_remove'] = 'An error occurred while trying to delete the Policy Template.';
$_lang['policy_template_err_save'] = 'Tekkis viga Poliisi Template salvestamisel.';
$_lang['policy_template_export'] = 'Export Policy Template';
$_lang['policy_template_import'] = 'Import Policy Template';
$_lang['policy_template_import_msg'] = 'Select an XML file to import a Policy Template from. It must be in the correct XML Policy Template format.';
$_lang['policy_template_remove'] = 'Eemalda Poliisi Template';
$_lang['policy_template_remove_confirm'] = 'Are you sure you want to delete this Policy Template? It will delete all Policies attached to this Template as well - this could break your MODX installation if any active Policies are attached to this Template.';
$_lang['policy_template_remove_multiple'] = 'Eemalda Valitud Poliisi Templated';
$_lang['policy_template_remove_multiple_confirm'] = 'Are you sure you want to delete these Policy Templates? It will delete all Policies attached to these Templates as well - this could break your MODX installation if any active Policies are attached to these Templates.';
$_lang['policy_template_update'] = 'Edit Policy Template';
$_lang['policy_templates'] = 'Poliisi Templated';
$_lang['policy_templates.intro_msg'] = 'Siin on nimekiri Poliisi Templatedest, mis defineerib nimekirja Õigustest, mida saab spetsiifilisel Poliisil chekckida ja mitte-checkida.';
$_lang['template_group'] = 'Template Grupp';
