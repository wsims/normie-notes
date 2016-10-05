<?php
// Normie Notes voting service


//Check that the request was sent from Normie Notes
$referer = $_SERVER['HTTP_REFERER'];
$refSub = substr($referer, 0, 52);
if($refSub != "http://web.engr.oregonstate.edu/~braune/NormieNotes/"){
	exit("Invalid referer");
}

$mysqli = new mysqli("oniddb.cws.oregonstate.edu", "braune-db", "GxGW1nC0BStHXQcB", "braune-db");

$user = htmlspecialchars($_POST['user']);
$id = htmlspecialchars($_POST['id']);

// Adding Upvote
if($_POST['action'] == 1){
	$result = $mysqli -> query("SELECT ups FROM users WHERE username='".$user."'");
	$obj = $result -> fetch_object();
	if(($obj -> ups) != NULL){
		$thisUps = unserialize($obj -> ups);
		array_push($thisUps, $id);
		$thisUps = serialize($thisUps);
	}
	else{
		$thisUps = serialize(array($id));
	}
	if($mysqli -> query("UPDATE users SET ups='".$thisUps."' WHERE username='".$user."'")){
		$result = $mysqli -> query("SELECT ups FROM entries WHERE id='".$id."'");
		$obj = $result -> fetch_object();
		$ups = ($obj -> ups);
		$ups = $ups + 1;
		$mysqli -> query("UPDATE entries SET ups='".$ups."' WHERE id='".$id."'");
	}
}

// Removing Upvote
else if($_POST['action'] == 2){
	$result = $mysqli -> query("SELECT ups FROM users WHERE username='".$user."'");
	$obj = $result -> fetch_object();
	
	$thisUps = unserialize($obj -> ups);
	$valid = FALSE;
	foreach($thisUps as $curID){
		if($curID == $id){
			$curKey = array_search($curID, $thisUps);
			unset($thisUps[$curKey]);
			$valid = TRUE;
		}
	}

	if($valid){
		$thisUps = serialize($thisUps);
		$mysqli -> query("UPDATE users SET ups='".$thisUps."' WHERE username='".$user."'");

		$result = $mysqli -> query("SELECT ups FROM entries WHERE id='".$id."'");
		$obj = $result -> fetch_object();
		$ups = ($obj -> ups);
		$ups = $ups - 1;
		$mysqli -> query("UPDATE entries SET ups='".$ups."' WHERE id='".$id."'");
	}
}

// Adding Downvote
else if($_POST['action'] == 3){
	$result = $mysqli -> query("SELECT downs FROM users WHERE username='".$user."'");
	$obj = $result -> fetch_object();
	if(($obj -> downs) != NULL){
		$thisDowns = unserialize($obj -> downs);
		array_push($thisDowns, $id);
		$thisDowns = serialize($thisDowns);
	}
	else{
		$thisDowns = serialize(array($id));
	}
	if($mysqli -> query("UPDATE users SET downs='".$thisDowns."' WHERE username='".$user."'")){
		$result = $mysqli -> query("SELECT downs FROM entries WHERE id='".$id."'");
		$obj = $result -> fetch_object();
		$downs = ($obj -> downs);
		$downs = $downs + 1;
		$mysqli -> query("UPDATE entries SET downs='".$downs."' WHERE id='".$id."'");	
	}
}

// Removing Downvote
else if($_POST['action'] == 4){
	$result = $mysqli -> query("SELECT downs FROM users WHERE username='".$user."'");
	$obj = $result -> fetch_object();
	
	$thisDowns = unserialize($obj -> downs);
	$valid = FALSE;
	foreach($thisDowns as $curID){
		if($curID == $id){
			$curKey = array_search($curID, $thisDowns);
			unset($thisDowns[$curKey]);
			$valid = TRUE;
		}
	}	

	if($valid){
		$thisDowns = serialize($thisDowns);		
		$mysqli -> query("UPDATE users SET downs='".$thisDowns."' WHERE username='".$user."'");

		$result = $mysqli -> query("SELECT downs FROM entries WHERE id='".$id."'");
		$obj = $result -> fetch_object();
		$downs = ($obj -> downs);
		$downs = $downs - 1;
		$mysqli -> query("UPDATE entries SET downs='".$downs."' WHERE id='".$id."'");
	}
}

$result = $mysqli -> query("SELECT ups, downs FROM entries WHERE id='".$id."'");
$obj = $result -> fetch_object();
$myScore = ($obj -> ups) - ($obj -> downs);
echo $myScore;

$mysqli -> close();

?>