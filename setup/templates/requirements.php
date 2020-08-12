<!doctype html>
<html lang="en">

<head>
    <title>MODX Setup cannot continue.</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <base href="<?= str_replace('index.php', '', MODX_SETUP_URL) ?>">

    <link rel="shortcut icon" href="favicon.ico" />
    <link href="assets/css/installer.css" type="text/css" rel="stylesheet" />

</head>

<body>
<!-- start header -->
<header>
    <div class="wrapper">
        <div class="wrapper_logo">
            <a href="https://modx.com/" title="MODX" class="logo" target="_blank">MODX</a>
        </div>
    </div>
</header>
<!-- end header -->

<div id="content">
    <div class="content-inside">
        <div class="wrapper">
            <div class="content_header">
                <div class="content_header_title notok">
                    MODX Setup cannot continue.
                </div>
            </div>
            <div id="install">
                <ul class="checklist">
                <?php foreach ($unsatisfiedRequirementsErrors as $unsatisfiedRequirementError): ?>
                    <li class="failed">
                        <p class="notok"><?= $unsatisfiedRequirementError['title'] ?? ''; ?></p>
                        <p><?= $unsatisfiedRequirementError['description'] ?? '';  ?></p>
                    </li>
                <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</div>

</body>

</html>
