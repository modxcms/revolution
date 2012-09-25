<?php
/**
 * @package modx
 * @subpackage setup
 */
$posted = !empty($_POST) && isset($_POST['findcore']) && isset($_POST['core_path']);
if ($posted) {
    $core_path = $_POST['core_path'];
    if (is_writable(MODX_SETUP_PATH . 'includes/config.core.php')) {
        $content = file_get_contents(MODX_SETUP_PATH . 'includes/config.core.php');
        $pattern = "/define\s*\(\s*'MODX_CORE_PATH'\s*,.*\);/";
        $replacement = "define ('MODX_CORE_PATH', '{$core_path}');";
        $content = preg_replace($pattern, $replacement, $content);
        file_put_contents(MODX_SETUP_PATH . 'includes/config.core.php', $content);
        header('Location: ' . MODX_SETUP_URL . '?s=set');
        die();
    }
} else {
    $core_path = MODX_CORE_PATH;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <title>MODX Revolution Core Finder</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link rel="shortcut icon" href="/favicon.ico" />
    <link rel="stylesheet" type="text/css" media="all" href="assets/css/reset.css" />
    <link rel="stylesheet" type="text/css" media="all" href="assets/css/text.css" />
    <link rel="stylesheet" type="text/css" media="all" href="assets/css/960.css" />

    <link rel="stylesheet" href="assets/modx.css" type="text/css" media="screen" />

    <link rel="stylesheet" href="assets/css/print.css" type="text/css" media="print" />

    <link href="assets/css/style.css" type="text/css" rel="stylesheet" />
    <script type="text/javascript" src="assets/js/ext-core.js"></script>
    <script type="text/javascript" src="assets/js/modx.setup.js"></script>
    <!--[if lt IE 7]>
        <script type="text/javascript" src="assets/js/inc/say.no.to.ie.6.js"></script>
        <style type="text/css">
        body {
            behavior:url("assets/js/inc/csshover2.htc");
        }
        .pngfix {
            behavior:url("assets/js/inc/iepngfix.htc");
        }
        </style>
    <![endif]-->

</head>

<body>
<!-- start header -->
<div id="header">
    <div class="container_12">
        <div id="metaheader">
            <div class="grid_6">
                <div id="mainheader">
                    <h1 id="logo" class="pngfix"><span>MODX</span></h1>
                </div>
            </div>
            <div id="metanav" class="grid_6">
                <a href="#"><strong>MODX Revolution</strong>&nbsp;<em>Core Finder</em></a>
            </div>
        </div>
        <div class="clear">&nbsp;</div>
    </div>
</div>
<!-- end header -->

<div id="contentarea">
    <div class="container_16">
       <!-- start content -->
        <div id="content" class="grid_12">
            <h2>Your MODX_CORE_PATH is invalid</h2>
            <p>In order to install MODX Revolution, you must first locate your core directory that contains the files required to run MODX,
            including this setup application. The current value specified is not valid and setup cannot continue until this is resolved.</p>
            <div>
                <form id="corefinder" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
                    <div>
                        <label>MODX_CORE_PATH</label>
                        <input type="text" name="core_path" id="core_path" value="<?php echo $core_path ?>" size="80" maxlength="255" />
                        <br />
<?php
if (!is_writable(MODX_SETUP_PATH . 'includes/config.core.php')) {
?>
                        <span class="field_error">ERROR: Your setup/includes/config.core.php is not writable; please make it writable
                        OR edit the file manually with the correct MODX_CORE_PATH and click Submit.</span>
<?php
} else {
?>
                        <span class="field_error">ERROR: Your MODX_CORE_PATH is invalid; please specify the correct path in the
                        field above and click Submit.</span>
<?php
}
?>
                        <br />
                    </div>
                    <br />
                    <div class="setup_navbar">
                        <input type="submit" id="modx-findcore" name="findcore" value="Submit" />
                    </div>
                </form>
                <br />
            </div>
        </div>
        <!-- end content -->
        <div class="clear">&nbsp;</div>
    </div>
</div>

<!-- start footer -->
<div id="footer">
    <div id="footer-inner">
    <div class="container_12">
        <p>&copy; 2005-2012 the <a href="http://www.modx.com/" onclick="window.open(this.href); return false;" onkeypress="window.open(this.href); return false;"  style="color: green; text-decoration:underline">MODX</a> Content Management Framework (CMF) project. All rights reserved. MODX is licensed under the GNU GPL.</p>
        <p>MODX is free software.  We encourage you to be creative and make use of MODX in any way you see fit. Just make sure that if you do make changes and decide to redistribute your modified MODX, that you keep the source code free!</p>
    </div>
    </div>
</div>
<!-- end footer -->
</body>
</html>