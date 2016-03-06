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

$onidID = checkAuth(false);
if($onidID){
	$infoString = "Logged in as, ".$onidID;
}	
else{
	$infoString = "Not logged in";
}
?>

<html>
	<style>
		#userInfo {
			font-family: Century Gothic, sans-serif;
			font-weight: lighter;
			position: absolute;
			color: #FFFFFF;
			right: 40px;
			top: 10px;
		}
	</style>
    <link type="text/css" rel="stylesheet" href="http://web.engr.oregonstate.edu/~simsw/fileViewStyle.css"/>
    <header>
        <a href="../View/fileView.php">
            <img src="../Header.png"/>
        </a>
        <?php echo "<span id='userInfo'>".$infoString."</span>"; ?>
        <nav>
            <form action="../Upload/newEntry.php">
                <input type="submit" class="button uploadButton" value="Upload Note">
            </form>
        </nav>
    </header>
</html>