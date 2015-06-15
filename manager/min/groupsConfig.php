<?php
/**
 * @var modX $modx
 */
$managerUrl = '../';
$externals = array();
$externals[] = $managerUrl.'assets/modext/core/modx.localization.js';
$externals[] = $managerUrl.'assets/modext/util/utilities.js';
$externals[] = $managerUrl.'assets/modext/util/datetime.js';
$externals[] = $managerUrl.'assets/modext/util/uploaddialog.js';
$externals[] = $managerUrl.'assets/modext/util/fileupload.js';
$externals[] = $managerUrl.'assets/modext/util/superboxselect.js';
$externals[] = $managerUrl.'assets/modext/widgets/core/modx.button.js';
$externals[] = $managerUrl.'assets/modext/widgets/core/modx.searchbar.js';
$externals[] = $managerUrl.'assets/modext/core/modx.component.js';
$externals[] = $managerUrl.'assets/modext/core/modx.view.js';
$externals[] = $managerUrl.'assets/modext/widgets/core/modx.panel.js';
$externals[] = $managerUrl.'assets/modext/widgets/core/modx.tabs.js';
$externals[] = $managerUrl.'assets/modext/widgets/core/modx.window.js';
$externals[] = $managerUrl.'assets/modext/widgets/core/modx.combo.js';

$externals2 = array();
$externals2[] = $managerUrl.'assets/modext/widgets/core/modx.grid.js';
$externals2[] = $managerUrl.'assets/modext/widgets/core/modx.console.js';
$externals2[] = $managerUrl.'assets/modext/widgets/core/modx.portal.js';
$externals2[] = $managerUrl.'assets/modext/widgets/windows.js';
$externals2[] = $managerUrl.'assets/fileapi/FileAPI.js';
$externals2[] = $managerUrl.'assets/modext/util/multiuploaddialog.js';
$externals2[] = $managerUrl.'assets/modext/widgets/core/tree/modx.tree.js';
$externals2[] = $managerUrl.'assets/modext/widgets/core/tree/modx.tree.treeloader.js';
$externals2[] = $managerUrl.'assets/modext/widgets/modx.treedrop.js';

$externals3 = array();
$externals3[] = $managerUrl.'assets/modext/widgets/core/modx.tree.asynctreenode.js';
$externals3[] = $managerUrl.'assets/modext/widgets/resource/modx.tree.resource.js';
$externals3[] = $managerUrl.'assets/modext/widgets/element/modx.tree.element.js';
$externals3[] = $managerUrl.'assets/modext/widgets/system/modx.tree.directory.js';
$externals3[] = $managerUrl.'assets/modext/widgets/system/modx.panel.filetree.js';
$externals3[] = $managerUrl.'assets/modext/widgets/media/modx.browser.js';
$externals3[] = $managerUrl.'assets/modext/core/modx.layout.js';
$externals3[] = $managerUrl.'templates/default/js/layout.js';

return array(
    'coreJs1' => $externals,
    'coreJs2' => $externals2,
    'coreJs3' => $externals3,
    // 'js' => array('//js/file1.js', '//js/file2.js'),
    // 'css' => array('//css/file1.css', '//css/file2.css'),

    // custom source example
    /*'js2' => array(
        dirname(__FILE__) . '/../min_unit_tests/_test_files/js/before.js',
        // do NOT process this file
        new Minify_Source(array(
            'filepath' => dirname(__FILE__) . '/../min_unit_tests/_test_files/js/before.js',
            'minifier' => create_function('$a', 'return $a;')
        ))
    ),//*/

    /*'js3' => array(
        dirname(__FILE__) . '/../min_unit_tests/_test_files/js/before.js',
        // do NOT process this file
        new Minify_Source(array(
            'filepath' => dirname(__FILE__) . '/../min_unit_tests/_test_files/js/before.js',
            'minifier' => array('Minify_Packer', 'minify')
        ))
    ),//*/
);