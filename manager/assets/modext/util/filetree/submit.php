<?
error_log(print_r($_FILES,1));
$o = array(
	 "success"=>true
	,"post"=>preg_replace("/\\n+/", "<br>", print_r($_POST, 1))
	,"files"=>preg_replace("/\\n+/", "<br>", print_r($_FILES, 1))
);
echo json_encode($o);
?>
