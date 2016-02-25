<?php

header('Content-Type: application/json');

$mysqli = new mysqli("oniddb.cws.oregonstate.edu", "braune-db", "GxGW1nC0BStHXQcB", "braune-db");

$combos = array();

if($result = $mysqli -> query("SELECT id, class, professor FROM combinations")){
	while($obj = $result -> fetch_object()){
		array_push($combos, $obj);
	}
}
	
$output = json_encode(array("Combos" => $combos));		
exit($output);

$mysqli -> close();

?>