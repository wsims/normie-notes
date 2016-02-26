<?php 
session_start(); 

function checkAuth($doRedirect) {
	if (isset($_SESSION["onidid"]) && $_SESSION["onidid"] != "") return $_SESSION["onidid"];

	 $pageURL = 'http';
	 if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
	 $pageURL .= "://";
	 if ($_SERVER["SERVER_PORT"] != "80") {
	  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["SCRIPT_NAME"];
	 } else {
	  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["SCRIPT_NAME"];
	 }

	$ticket = isset($_REQUEST["ticket"]) ? $_REQUEST["ticket"] : "";

	if ($ticket != "") {
		$url = "https://login.oregonstate.edu/cas/serviceValidate?ticket=".$ticket."&service=".$pageURL;
		$html = file_get_contents($url);
		$pattern = '/\\<cas\\:user\\>([a-zA-Z0-9]+)\\<\\/cas\\:user\\>/';
		preg_match($pattern, $html, $matches);
		if ($matches && count($matches) > 1) {
			$onidid = $matches[1];
			$_SESSION["onidid"] = $onidid;
			return $onidid;
		} 
	} else if ($doRedirect) {
		$url = "https://login.oregonstate.edu/cas/login?service=".$pageURL;
		echo "<script>location.replace('" . $url . "');</script>";
	} 
	return "";
}
?>

<html>
    <link type="text/css" rel="stylesheet" href="http://web.engr.oregonstate.edu/~simsw/fileViewStyle.css"/>
    <header>
        <a href="fileView.php">
            <img src="Header.png"/>
        </a>
        <nav>
            <ul>
                <li><a href="fileUpload.php">Upload</a></li>
            </ul>
        </nav>
    </header>
</html>