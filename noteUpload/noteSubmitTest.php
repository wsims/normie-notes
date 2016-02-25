<html>

<head>
	<title>Submitting Note</title>

</head>

<body>
	<?php

	function insertClass($myClass){
		if($stmt = $mysqli -> prepare("insert into classes(class) values(?)")){
			$stmt -> bind_param("s", $myClass);
			$stmt -> execute();
			$stmt -> close();
			echo "Inserted class into classes";
		}
	}

	function insertProf($myProf){
		if($stmt = $mysqli -> prepare("insert into professors(professor) values(?)")){
			$stmt -> bind_param("s", $myProf);
			$stmt -> execute();
			$stmt -> close();
			echo "Inserted prof into professors";
		}
	}




	if($_REQUEST["title"]){
		$title = htmlspecialchars($_REQUEST["title"]);
	}


	if($_REQUEST["class"]){
		$class = htmlspecialchars($_REQUEST["class"]);
	}


	if($_REQUEST["prof"]){
		$prof = htmlspecialchars($_REQUEST["prof"]);
	}


	if($_REQUEST["note"]){
		$note = htmlspecialchars($_REQUEST["note"]);
	}

	//Convert \n to <br>
	$note = nl2br($note);

	//Get unix time
	$timeVal = time();

	if(!($mysqli = new mysqli("oniddb.cws.oregonstate.edu", "braune-db", "GxGW1nC0BStHXQcB", "braune-db"))){
	echo "<h1>Could not connect to database<br></h1>";

	}	

	
	if($result = $mysqli -> query("SELECT class FROM classes")){
		$count = 0;
		while($obj = $result -> fetch_object()){
			if($obj -> class == $class){
				$count += 1;
			}
		}
		if($count == 0){
			//insertClass($class);
			if($stmt = $mysqli -> prepare("insert into classes(class) values(?)")){
				$stmt -> bind_param("s", $class);
				$stmt -> execute();
				$stmt -> close();
				//echo "Inserted class into classes";
			}
		}
	}

	if($result = $mysqli -> query("SELECT prof FROM professors")){
		$count = 0;
		while($obj = $result -> fetch_object()){
			if($obj -> prof == $prof){
				$count += 1;
			}
		}
		//echo "Count of prof loop: ".$count;
		if($count == 0){
			//insertProf($prof);
			if($stmt = $mysqli -> prepare("insert into professors(prof) values(?)")){
				$stmt -> bind_param("s", $prof);
				$stmt -> execute();
				$stmt -> close();
				//echo "Inserted prof into professors";
			}
		}
	}
	




	if($stmt = $mysqli -> prepare("insert into notes(title, class, professor, note, timeVal) values(?,?,?,?,?)")){
		$stmt -> bind_param("ssssi", $title, $class, $prof, $note, $timeVal);
		$stmt -> execute();
		$stmt -> close();
		echo "<h1>Note Submitted</h1>";
	}
	else{
		echo "<h1>Failed to Submit Note</h1>";
	}
	
	$mysqli -> close();

	?>


	<?php
		$mysqli = new mysqli("oniddb.cws.oregonstate.edu", "braune-db", "GxGW1nC0BStHXQcB", "braune-db");

		//echo time();
		//echo "<br>";

		$combos = array();
		$myClasses = array();

		if($newResult = $mysqli -> query("SELECT class FROM notes")){
			while($newObj = $newResult -> fetch_object()){
				$combos[$newObj -> class] = array();
				if(in_array($newObj->class, $myClasses) == FALSE){
					array_push($myClasses, $newObj->class);
				}
			}
		}

		//echo "<br>Testing in array: ".in_array("CS 290", $myClasses)."<br>";
	

		$i = 0;

		//print_r($myClasses);
		//echo "<br>";
		
		foreach($combos as $curClass){
			if($myResult = $mysqli -> query("SELECT professor FROM notes WHERE class='".$myClasses[$i]."'")){
				while($curObj = $myResult -> fetch_object()){
					//echo "<br>";
					//echo $myClasses[$i]."<br>";
					//print_r($curObj);

					//echo $curObj->professor;
					//echo "<br>";
					if(in_array($curObj->professor, $combos[$myClasses[$i]]) == FALSE){
						array_push($combos[$myClasses[$i]], $curObj->professor); 
					}
				}
			}
			$i += 1;
		}
		

	

		//Read combos from db
		$dbCombos = array();
		if($result = $mysqli -> query("SELECT class, professor FROM combinations")){
			while($obj = $result -> fetch_object()){
				array_push($dbCombos, array($obj->class, $obj->professor));


			}
		}
		
		//Insert new class/professor combinations into combos
		
		$i = 0;
		foreach($combos as $thisClass){
			foreach($thisClass as $curProf){
				if(in_array(array($myClasses[$i], $curProf), $dbCombos) == FALSE){
					if($stmt = $mysqli -> prepare("insert into combinations(class, professor) values(?,?)")){
						$stmt -> bind_param("ss", $myClasses[$i], $curProf);
						$stmt -> execute();
						$stmt -> close();
					}
				}
			}
			$i += 1;
		}
		






		$mysqli -> close();

	?>







	<a href="http://web.engr.oregonstate.edu/~braune/NormieNotes/fileView.php">
		<h2>View Notes</h2>
	</a>
</body>