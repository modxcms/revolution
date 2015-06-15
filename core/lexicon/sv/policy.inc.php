<?php
/**
 * Access Policy English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */
$_lang['active_of'] = '[[+active]] av [[+total]]';
$_lang['active_permissions'] = 'Aktiva behörigheter';
$_lang['no_policy_option'] = ' (ingen policy) ';
$_lang['permission'] = 'Behörighet';
$_lang['permission_add'] = 'Lägg till behörighet';
$_lang['permission_add_template'] = 'Lägg till behörighet till mall';
$_lang['permission_err_ae'] = 'Behörighet finns redan för den här policyn.';
$_lang['permission_err_nf'] = 'Behörighet kunde inte hittas.';
$_lang['permission_err_ns'] = 'Behörighet inte angiven.';
$_lang['permission_err_remove'] = 'Ett fel inträffade när den här behörigheten skulle tas bort.';
$_lang['permission_err_save'] = 'Ett fel inträffade när den här behörigheten skulle sparas.';
$_lang['permission_new'] = 'Ny behörighet';
$_lang['permission_remove'] = 'Ta bort behörighet';
$_lang['permission_remove_confirm'] = 'Är du säker på att du vill ta bort denna behörighet?';
$_lang['permission_update'] = 'Uppdatera behörighet';
$_lang['permissions'] = 'Behörigheter';
$_lang['permissions_desc'] = 'Här kan du definiera de specifika behörigheter som denna policy ska innehålla. Alla användargrupper med denna policy kommer att ärva dessa behörigheter.';
$_lang['policies'] = 'Åtkomstpolicyer';
$_lang['policy'] = 'Åtkomstpolicy';
$_lang['policy_create'] = 'Skapa åtkomstpolicy';
$_lang['policy_data'] = 'Policydata';
$_lang['policy_desc'] = 'Åtkomstpolicyer är allmänna policyer som hindrar eller tillåter vissa aktiviteter i MODX.';
$_lang['policy_desc_name'] = 'Åtkomstpolicyns namn';
$_lang['policy_desc_description'] = 'Valfri. En kort beskrivning av åtkomstpolicyn.';
$_lang['policy_desc_template'] = 'Den policymall som används av denna policy. Policyer får sina rättighetslistor från sina mallar.';
$_lang['policy_desc_lexicon'] = 'Valfri. Det lexikonämne som denna policy använder för att översätta de rättigheter den har.';
$_lang['policy_duplicate'] = 'Duplicera policy';
$_lang['policy_duplicate_confirm'] = 'Är du säker på att du vill duplicera denna policy och all dess data?';
$_lang['policy_err_ae'] = 'Det finns redan en policy med namnet `[[+name]]`. Välj ett annat namn.';
$_lang['policy_err_nf'] = 'Policyn kunde inte hittas.';
$_lang['policy_err_ns'] = 'Ingen policy angiven.';
$_lang['policy_err_remove'] = 'Ett fel inträffade när policyn skulle tas bort.';
$_lang['policy_err_save'] = 'Ett fel inträffade när policyn skulle sparas.';
$_lang['policy_export'] = 'Exportera policy';
$_lang['policy_import'] = 'Importera policy';
$_lang['policy_import_msg'] = 'Ange den XML-fil som policyn ska importeras från. Den måste vara i det korrekta XML-formatet för policyer.';
$_lang['policy_management'] = 'Åtkomstpolicyer';
$_lang['policy_management_msg'] = 'Åtkomstpolicyer anger hur MODX ska hantera rättigheter för angivna åtgärder.';
$_lang['policy_name'] = 'Policynamn';
$_lang['policy_property_create'] = 'Skapa åtkomstpolicyegenskap';
$_lang['policy_property_new'] = 'Ny åtkomstpolicyegenskap';
$_lang['policy_property_remove'] = 'Ta bort åtkomstpolicyegenskap';
$_lang['policy_property_specify_name'] = 'Ange ett namn på policyegenskapen:';
$_lang['policy_remove'] = 'Ta bort policy';
$_lang['policy_remove_confirm'] = 'Är du säker på att du vill ta bort den här åtkomstpolicyn?';
$_lang['policy_remove_multiple'] = 'Ta bort policyer';
$_lang['policy_remove_multiple_confirm'] = 'Är du säker på att du vill ta bort dessa åtkomstpolicyer? Denna åtgärd går inte att ångra.';
$_lang['policy_update'] = 'Uppdatera policy';
$_lang['policy_template'] = 'Policymall';
$_lang['policy_template.desc'] = 'En policymall definierar vilka behörigheter som visas när man redigerar en specifik policy. Du kan lägga till eller ta bort behörigheter från denna mall nedan. Notera att om en behörighet tas bort från mallen, så tas den också bort från alla policyer som använder denna mall.';
$_lang['policy_template_create'] = 'Skapa ny policymall';
$_lang['policy_template_desc_name'] = 'Åtkomstpolicymallens namn';
$_lang['policy_template_desc_description'] = 'Valfri. En kort beskrivning av åtkomstpolicymallen.';
$_lang['policy_template_desc_lexicon'] = 'Valfri. Det lexikonämne som denna policymall använder för att översätta de rättigheter den har.';
$_lang['policy_template_desc_template_group'] = 'Den policymallgrupp som ska användas. Denna används när policyer väljs i en rullgardinsmeny (vanligen är de filtrerade efter mallgrupp). Välj en lämplig grupp för din policymall.';
$_lang['policy_template_duplicate'] = 'Duplicera policymall';
$_lang['policy_template_duplicate_confirm'] = 'Är du säker på att du vill duplicera denna policymall?';
$_lang['policy_template_err_ae'] = 'Det finns redan en policymall med namnet `[[+name]]`. Välj ett annat namn.';
$_lang['policy_template_err_nf'] = 'Policymallen kunde inte hittas.';
$_lang['policy_template_err_ns'] = 'Ingen policymall angiven.';
$_lang['policy_template_err_remove'] = 'Ett fel inträffade när policymallen skulle tas bort.';
$_lang['policy_template_err_save'] = 'Ett fel inträffade när policymallen skulle sparas.';
$_lang['policy_template_export'] = 'Exportera policymall';
$_lang['policy_template_import'] = 'Importera policymall';
$_lang['policy_template_import_msg'] = 'Ange en XML-fil för att importera en policymall. Den måste vara i ett giltigt XML-format för policymallar.';
$_lang['policy_template_remove'] = 'Ta bort policymall';
$_lang['policy_template_remove_confirm'] = 'Är du säker på att du vill ta bort denna policymall? Det kommer även att ta bort alla policyer som hör till denna mall. Det här kan ha sönder din MODX-installation om det finns några aktiva policyer kopplade till den här mallen.';
$_lang['policy_template_remove_multiple'] = 'Ta bort markerade policymallar';
$_lang['policy_template_remove_multiple_confirm'] = 'Är du säker på att du vill ta bort dessa policymallar? Det kommer även att ta bort alla policyer som hör till dessa mallar. Det här kan ha sönder din MODX-installation om det finns några aktiva policyer kopplade till de här mallarna.';
$_lang['policy_template_update'] = 'Uppdatera policymall';
$_lang['policy_templates'] = 'Policymallar';
$_lang['policy_templates.intro_msg'] = 'Det här är en lista med policymallar som definierar listor med behörigheter som är aktiva eller inaktiva i specifika policyer.';
$_lang['template_group'] = 'Mallgrupp';