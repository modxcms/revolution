<?php
/**
 * Common upgrade script for 3.0 to clean up files removed since 2.x
 *
 * @var $modx modX
 *
 * @package setup
 */
$paths = [
    'assets' => $modx->getOption('assets_path', null, MODX_ASSETS_PATH),
    'base' => $modx->getOption('base_path', null, MODX_BASE_PATH),
    'connectors' => $modx->getOption('connectors_path', null, MODX_CONNECTORS_PATH),
    'core' => $modx->getOption('core_path', null, MODX_CORE_PATH),
    'manager' => $modx->getOption('manager_path', null, MODX_MANAGER_PATH),
    'processors' => $modx->getOption('processors_path', null, MODX_PROCESSORS_PATH),
];

$cleanup = [
    'assets' => [],
    'base' => [],
    'connectors' => [],
    'core' => [
        'model/aws/',
        'model/lib/',
        'model/modx/error/',
        'model/modx/filters/',
        'model/modx/hashing/',
        'model/modx/import/',
        'model/modx/jsonrpc/',
        'model/modx/mail/',
        'model/modx/mysql/',
        'model/modx/processors/',
        'model/modx/registry/',
        'model/modx/rest/',
        'model/modx/smarty/',
        'model/modx/sources/',
        'model/modx/sqlsrv/',
        'model/modx/transport/',
        'model/modx/validation/',
        'model/modx/xmlrpc/',
        'model/modx/xmlrss/',
        'model/modx/modaccessibleobject.class.php',
        'model/modx/modusersetting.class.php',
        'model/modx/modusergrouprole.class.php',
        'model/modx/modusergroupmember.class.php',
        'model/modx/metadata.mysql.php',
        'model/modx/moddashboardwidget.class.php',
        'model/modx/moddashboard.class.php',
        'model/modx/modconnectorresponse.class.php',
        'model/modx/modmanagerresponse.class.php',
        'model/modx/modnamespace.class.php',
        'model/modx/modjsonrpcresource.class.php',
        'model/modx/modsession.class.php',
        'model/modx/modmanagercontrollerdeprecated.class.php',
        'model/modx/modrequest.class.php',
        'model/modx/modusergroupsetting.class.php',
        'model/modx/modscript.class.php',
        'model/modx/modresourcegroupresource.class.php',
        'model/modx/modaccesspolicytemplategroup.class.php',
        'model/modx/modactiondom.class.php',
        'model/modx/modcategory.class.php',
        'model/modx/modplugin.class.php',
        'model/modx/modresourcegroup.class.php',
        'model/modx/modcachemanager.class.php',
        'model/modx/modsystemsetting.class.php',
        'model/modx/modextensionpackage.class.php',
        'model/modx/modaccesspolicy.class.php',
        'model/modx/modweblink.class.php',
        'model/modx/modaccesscategory.class.php',
        'model/modx/modcontext.class.php',
        'model/modx/modaccesspolicytemplate.class.php',
        'model/modx/modsnippet.class.php',
        'model/modx/modelement.class.php',
        'model/modx/modformcustomizationprofileusergroup.class.php',
        'model/modx/modaccessiblesimpleobject.class.php',
        'model/modx/modcontextsetting.class.php',
        'model/modx/modevent.class.php',
        'model/modx/modlexiconentry.class.php',
        'model/modx/modprocessor.class.php',
        'model/modx/modtemplate.class.php',
        'model/modx/modaccessresource.class.php',
        'model/modx/modcategoryclosure.class.php',
        'model/modx/modformcustomizationset.class.php',
        'model/modx/modmanagerlog.class.php',
        'model/modx/modaccessnamespace.class.php',
        'model/modx/modcontenttype.class.php',
        'model/modx/modactiveuser.class.php',
        'model/modx/moduserprofile.class.php',
        'model/modx/modaccesstemplatevar.class.php',
        'model/modx/modprincipal.class.php',
        'model/modx/modaccessresourcegroup.class.php',
        'model/modx/modactionfield.class.php',
        'model/modx/modaccesspermission.class.php',
        'model/modx/modaccess.class.php',
        'model/modx/modformcustomizationprofile.class.php',
        'model/modx/modaccesselement.class.php',
        'model/modx/modtemplatevar.class.php',
        'model/modx/modelementpropertyset.class.php',
        'model/modx/modsessionhandler.class.php',
        'model/modx/modmanagercontroller.class.php',
        'model/modx/modsymlink.class.php',
        'model/modx/modlexicon.class.php',
        'model/modx/modaccessactiondom.class.php',
        'model/modx/modtranslate095.class.php',
        'model/modx/modtemplatevarresourcegroup.class.php',
        'model/modx/modtemplatevarresource.class.php',
        'model/modx/modclassmap.class.php',
        'model/modx/modstaticresource.class.php',
        'model/modx/modaction.class.php',
        'model/modx/modaccessmenu.class.php',
        'model/modx/modparser.class.php',
        'model/modx/modresource.class.php',
        'model/modx/modconnectorrequest.class.php',
        'model/modx/modusergroup.class.php',
        'model/modx/modparser095.class.php',
        'model/modx/modtemplatevartemplate.class.php',
        'model/modx/modaccessaction.class.php',
        'model/modx/moddashboardwidgetplacement.class.php',
        'model/modx/modpropertyset.class.php',
        'model/modx/modworkspace.class.php',
        'model/modx/modmanagerrequest.class.php',
        'model/modx/modmenu.class.php',
        'model/modx/moduser.class.php',
        'model/modx/moddocument.class.php',
        'model/modx/modpluginevent.class.php',
        'model/modx/modchunk.class.php',
        'model/modx/modusermessage.class.php',
        'model/modx/modxmlrpcresource.class.php',
        'model/modx/modresponse.class.php',
        'model/modx/modaccesscontext.class.php',
        'model/modx/modfilehandler.class.php',
        'model/modx/metadata.sqlsrv.php',
        'model/modx/modcontextresource.class.php',
        'model/phpthumb/',
        'model/smarty/',

        // Remove sqlsrv
        'model/schema/modx.registry.db.sqlsrv.schema.xml',
        'model/schema/modx.sources.sqlsrv.schema.xml',
        'model/schema/modx.sqlsrv.schema.xml',
        'model/schema/modx.transport.sqlsrv.schema.xml',
        'src/Revolution/Processors/System/DatabaseTable/sqlsrv/',
        'src/Revolution/Registry/Db/sqlsrv/',
        'src/Revolution/Sources/sqlsrv/',
        'src/Revolution/Sources/metadata.sqlsrv.php',
        'src/Revolution/Transport/sqlsrv/',
        'src/Revolution/sqlsrv/',
        'src/Revolution/metadata.sqlsrv.php',

        // were present in alpha1, but removed in alpha2
        'src/Revolution/modAction.php',
        'src/Revolution/modAccessAction.php',
        'src/Revolution/mysql/modAction.php',
        'src/Revolution/mysql/modAccessAction.php',
        'src/Revolution/sqlsrv/modAction.php',
        'src/Revolution/sqlsrv/modAccessAction.php',
        'src/Revolution/modManagerControllerDeprecated.php',
        'src/Revolution/Processors/Element/TemplateVar/Renders/mgr/input/list-multiple-legacy.class.php',
        'src/Revolution/Processors/Element/TemplateVar/Renders/mgr/inputproperties/list-multiple-legacy.php',

        // remove modClassMap and dependencies
        'src/Revolution/Processors/System/ClassMap/GetList.php',
        'src/Revolution/modClassMap.php',
        'src/Revolution/mysql/modClassMap.php',
        'src/Revolution/sqlsrv/modClassMap.php',

        // remove deprecated rest client present until alpha4/beta1
        'src/Revolution/Rest/modRestClient.php',
        'src/Revolution/Rest/modRestCurlClient.php',
        'src/Revolution/Rest/modRestResponse.php',
        'src/Revolution/Rest/modRestSockClient.php',

        // remove import utilities until alpha4/beta1
        'src/Revolution/Processors/System/Import/Html.php',
        'src/Revolution/Processors/System/Import/Index.php',
        'src/Revolution/Import/modImport.php',
        'src/Revolution/Import/modStaticImport.php',
        'lexicon/ar/import.inc.php',
        'lexicon/be/import.inc.php',
        'lexicon/bg/import.inc.php',
        'lexicon/cs/import.inc.php',
        'lexicon/da/import.inc.php',
        'lexicon/de/import.inc.php',
        'lexicon/el/import.inc.php',
        'lexicon/en/import.inc.php',
        'lexicon/es/import.inc.php',
        'lexicon/et/import.inc.php',
        'lexicon/fa/import.inc.php',
        'lexicon/fi/import.inc.php',
        'lexicon/fr/import.inc.php',
        'lexicon/he/import.inc.php',
        'lexicon/hi/import.inc.php',
        'lexicon/hu/import.inc.php',
        'lexicon/id/import.inc.php',
        'lexicon/id/import.inc.php',
        'lexicon/ja/import.inc.php',
        'lexicon/nl/import.inc.php',
        'lexicon/pl/import.inc.php',
        'lexicon/pt/import.inc.php',
        'lexicon/ro/import.inc.php',
        'lexicon/ru/import.inc.php',
        'lexicon/sv/import.inc.php',
        'lexicon/th/import.inc.php',
        'lexicon/tr/import.inc.php',
        'lexicon/uk/import.inc.php',
        'lexicon/yo/import.inc.php',
        'lexicon/zh/import.inc.php',

        // remove unused processor present until beta2
        'src/Revolution/Processors/Security/User/Group/',
    ],
    'manager' => [
        'min/',
        'assets/modext/widgets/resource/modx.grid.resource.security.js',
        'assets/modext/widgets/security/modx.grid.role.user.js',
        'assets/modext/workspace/lexicon/language.grid.js',
        'assets/modext/workspace/lexicon/lexicon.topic.grid.js',
        'templates/default/element/tv/renders/input/list-multiple-legacy.tpl',
        'templates/default/element/tv/renders/inputproperties/list-multiple-legacy.tpl',
        'assets/modext/sections/system/import/html.js',
        'assets/modext/sections/system/import/resource.js',
        'assets/modext/widgets/system/modx.panel.import.html.js',
        'assets/modext/widgets/system/modx.panel.import.resources.js',
        'controllers/default/system/import/html.class.php',
        'controllers/default/system/import/index.class.php',
    ],
];

$removedFiles = 0;
$removedDirs = 0;

if (!function_exists('recursiveRemoveDir')) {
    function recursiveRemoveDir($dir)
    {
        $files = array_diff(scandir($dir), ['.', '..']);
        foreach ($files as $file) {
            (is_dir("$dir/$file")) ? recursiveRemoveDir("$dir/$file") : unlink("$dir/$file");
        }

        return rmdir($dir);
    }
}

// Loop through legacy files/folders to clean up
foreach ($cleanup as $folder => $files) {
    foreach ($files as $file) {
        $legacyFile = $paths[$folder].$file;
        if (file_exists($legacyFile) === true) {
            if (is_dir($legacyFile) === true) {
                // Remove legacy directory
                recursiveRemoveDir($legacyFile);
                ++$removedDirs;
            } else {
                // Remove legacy file
                unlink($legacyFile);
                ++$removedFiles;
            }
        }
    }
}

$this->runner->addResult(
    modInstallRunner::RESULT_SUCCESS,
    '<p class="ok">' . $this->install->lexicon('legacy_cleanup_complete') .
    '<br /><small>' . $this->install->lexicon(
        'legacy_cleanup_count',
        ['files' => $removedFiles, 'folders' => $removedDirs]
    ) . '</small></p>'
);
