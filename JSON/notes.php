<?php
		if($_GET["id"]){
			header('Content-Type: application/json');
			
			$mysqli = new mysqli("oniddb.cws.oregonstate.edu", "braune-db", "GxGW1nC0BStHXQcB", "braune-db");

			//Compile combos from db
			$dbCombos = array();
			if($result = $mysqli -> query("SELECT id, class, professor FROM combinations")){
				while($obj = $result -> fetch_object()){
					array_push($dbCombos, array($obj->id, $obj->class, $obj->professor));
				}
			}

			//Acquire Class and Prof from $dbCombos
			foreach($dbCombos as $curCombo){
				if($curCombo[0] == $_GET["id"]){
					$myClass = $curCombo[1];
					$myProf = $curCombo[2];
					break;
				}
			}


			$classes = array();


			$myQuery = "SELECT nid, title, class, professor, note, timeVal FROM notes WHERE class='".$myClass."' AND professor='".$myProf."'";

			if($result = $mysqli -> query($myQuery)){
				while($obj = $result -> fetch_object()){
					array_push($classes, $obj);
				}
			}
			
			$output = json_encode(array("Notes" => $classes));
			exit($output);	


			$mysqli -> close();
		}
		else{
			echo "Make GET request for id based on /combos.php";
		}

		?>