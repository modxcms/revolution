<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" {if $_config.manager_direction EQ 'rtl'}dir="rtl"{/if} lang="{$_config.manager_lang_attribute}" xml:lang="{$_config.manager_lang_attribute}">
<head>
<title>{$_lang.modx_resource_browser}</title>
<meta http-equiv="Content-Type" content="text/html; charset={$_config.modx_charset}" />

<link rel="stylesheet" type="text/css" href="{$_config.manager_url}assets/ext3/resources/css/ext-all.css" />
<link rel="stylesheet" type="text/css" href="{$_config.manager_url}assets/ext3/resources/css/xtheme-gray-extend.css" />
<link rel="stylesheet" type="text/css" href="{$_config.manager_url}templates/{$_config.manager_theme}/css/index.css" />

<script src="{$_config.manager_url}assets/ext3/adapter/ext/ext-base.js" type="text/javascript"></script>
<script src="{$_config.manager_url}assets/ext3/ext-all.js" type="text/javascript"></script>
<script src="{$_config.manager_url}assets/modext/core/modx.js" type="text/javascript"></script>
<script src="{$_config.connectors_url}lang.js.php?topic=category,file,resource" type="text/javascript"></script>
<script src="{$_config.connectors_url}layout/modx.config.js.php" type="text/javascript"></script>

<script src="{$_config.manager_url}assets/modext/core/modx.localization.js" type="text/javascript"></script>
<script src="{$_config.manager_url}assets/modext/core/modx.form.handler.js" type="text/javascript"></script>
<script src="{$_config.manager_url}assets/modext/core/modx.component.js" type="text/javascript"></script>
<script src="{$_config.manager_url}assets/modext/core/modx.actionbuttons.js" type="text/javascript"></script>
<script src="{$_config.manager_url}assets/modext/core/modx.view.js" type="text/javascript"></script>
<script src="{$_config.manager_url}assets/modext/util/utilities.js" type="text/javascript"></script>
<script src="{$_config.manager_url}assets/modext/util/switchbutton.js" type="text/javascript"></script>
<script src="{$_config.manager_url}assets/modext/widgets/core/modx.combo.js" type="text/javascript"></script>
<script src="{$_config.manager_url}assets/modext/widgets/core/modx.msg.js" type="text/javascript"></script>
<script src="{$_config.manager_url}assets/modext/widgets/core/modx.tree.js" type="text/javascript"></script>
<script src="{$_config.manager_url}assets/modext/widgets/core/modx.window.js" type="text/javascript"></script>
<script src="{$_config.manager_url}assets/modext/widgets/windows.js" type="text/javascript"></script>

<script src="{$_config.manager_url}assets/modext/widgets/core/modx.browser.js" type="text/javascript"></script>
<script src="{$_config.manager_url}assets/modext/widgets/system/modx.tree.directory.js" type="text/javascript"></script>

<script src="{$_config.manager_url}assets/modext/util/filetree/js/Ext.ux.form.BrowseButton.js" type="text/javascript"></script>
<script src="{$_config.manager_url}assets/modext/util/filetree/js/Ext.ux.FileUploader.js" type="text/javascript"></script>
<script src="{$_config.manager_url}assets/modext/util/filetree/js/Ext.ux.UploadPanel.js" type="text/javascript"></script>
<link href="{$_config.manager_url}assets/modext/util/filetree/css/icons.css" rel="stylesheet" type="text/css" />
<link href="{$_config.manager_url}assets/modext/util/filetree/css/filetype.css" rel="stylesheet" type="text/css" />
<link href="{$_config.manager_url}assets/modext/util/filetree/css/filetree.css" rel="stylesheet" type="text/css" />

{$rteincludes}
</head>
<body>

{literal}
<script type="text/javascript">
Ext.onReady(function() {
    Ext.QuickTips.init();
    Ext.BLANK_IMAGE_URL = MODx.config.manager_url+'assets/ext2/resources/images/default/s.gif';
    
	var b = MODx.load({
	   xtype: 'modx-browser'
	   ,el: 'browser'
	   ,hideFiles: true
	   ,onSelect: function(data) {
		{/literal}{$rtecallback}{literal}
		}
	});
	b.show();
});
</script>
{/literal}
<br /><br />
<div id="browser"></div>
</body>
</html>