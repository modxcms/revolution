<?php
header('HTTP/1.1 500 Internal Server Error');
?>
<html>
<head>
<title>Error 500: Internal Server Error</title>
<style type="text/css">
* {
    margin: 0;
    padding: 0;
}
body {
    margin: 50px;
    background: #eee;
}
.message {
    padding: 10px;
    border: 2px solid #f22;
    background: #f99;
    margin: 0 auto;
    font: 120%/1em sans-serif;
    text-align: center;
}
p {
    margin: 20px 0;
}
a {
    font-size: 180%;
    color: #f22;
    text-decoration: underline;
    margin-top: 30px;
    padding: 5px;
}
</style>
</head>
<body>
<div class="message">
    <h1>500 Error</h1>
    <p><?php echo $errorMessage ? $errorMessage : 'A fatal application error has been encountered.'; ?></p>
</div>
</body>
<?php
@session_write_close();
exit();
?>
