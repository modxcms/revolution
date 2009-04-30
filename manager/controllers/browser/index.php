<?php
/**
 * Loads the MODx.Browser page
 *
 * @package modx
 * @subpackage manager.browser
 */
require_once dirname(__FILE__).'/init.php';
if (!$modx->hasPermission('file_manager')) return $modx->error->failure($modx->lexicon('permission_denied'));

/* eventually replace this line with rte-specific handler injected by transport */
$rtecallback = "
	 var FileBrowserDialogue = {
        init : function () {
            // Here goes your code for setting your custom things onLoad.
        },
        selectURL : function (url) {
            var win = tinyMCEPopup.getWindowArg('window');

            // insert information now
            win.document.getElementById(tinyMCEPopup.getWindowArg('input')).value = url;

            if (typeof(win.ImageDialog) != 'undefined') {
                // for image browsers: update image dimensions
                if (win.ImageDialog.getImageData) {
                    win.ImageDialog.getImageData();
                }
                win.ImageDialog.showPreviewImage(url);
            }

            // close popup window
            tinyMCEPopup.close();
            win.focus(); win.document.focus();
        }
    }

    tinyMCEPopup.onInit.add(FileBrowserDialogue.init, FileBrowserDialogue);

    function OpenFile(fileUrl){
        FileBrowserDialogue.selectURL(fileUrl);
    }
    var fileUrl = unescape(data.url);
	OpenFile(fileUrl);
";

$modx->smarty->assign('rtecallback',$rtecallback);

$modx->smarty->assign('rteincludes','<script language="javascript" type="text/javascript" src="../../../assets/components/tinymce/jscripts/tiny_mce/tiny_mce_popup.js"></script><script language="javascript" type="text/javascript" src="../../../assets/components/tinymce/jscripts/tiny_mce/langs/en.js"></script>');
$modx->smarty->display('browser/index.tpl');