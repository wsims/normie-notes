<html>

<head>
	<title>Submitting Note</title>

</head>

<body>
	<?php

	if(!($mysqli = new mysqli("oniddb.cws.oregonstate.edu", "braune-db", "GxGW1nC0BStHXQcB", "braune-db"))){
		echo "<h1>Could not connect to database<br></h1>";
	}	
	
	$isNote = FALSE;
	$isFile = FALSE;
	$curUser = $_SESSION["onidid"];

	//Set random ID from 1-1000000
	
	$idArray = array();
	if($result = $mysqli -> query("SELECT id FROM noteID")){
		while($obj = $result -> fetch_object()){
			array_push($idArray, $obj -> id);
		}
	}
	$curID = rand(1, 1000000);
	while(in_array($curID, $idArray)){
		$curID = rand(1, 1000000);
	}
	
	$mysqli -> close();

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
		if($note != NULL){
			$isNote = TRUE;
		}
	}

	if($_REQUEST["user"]){
		$curUser = htmlspecialchars($_REQUEST["user"]);
	}
	
	if($_FILES["upload"]){
		$errorInfo = $_FILES['upload']["error"];
		$fileName = htmlspecialchars($_FILES['upload']["name"]);
		$tmpFile = $_FILES['upload']["tmp_name"];
		$fileSize = $_FILES['upload']["size"];
		$fileType = $_FILES['upload']["type"];
		if($fileName != NULL){
			$isFile = TRUE;
		}
	}
	/*
	$null = NULL;
	if($isNote && !$isFile){
		$dataArray = array($title, $class, $prof, $note);
	}
	else if($isFile && !$isNote){
		$dataArray = array($title, $class, $prof, $fileName);
	}
	else if($isFile %% $isNote){
		$dataArray = array($title, $class, $prof, $note, $fileName);
	}
	else {
		$dataArray = array($null);
	}
	foreach($dataArray as $curData){
		if($curData == NULL){
			exit("Cannot submit note with NULL information");
		}
	}
	*/
	//echo "isNote: ".$isNote."<br>";
	//echo "isFile: ".$isFile."<br>";
	//echo "curID: ".$curID."<br>";
	//If Note and not File
	if($isNote && !$isFile){

		
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
				if($stmt = $mysqli -> prepare("insert into classes(class) values(?)")){
					$stmt -> bind_param("s", $class);
					$stmt -> execute();
					$stmt -> close();
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
			if($count == 0){
				if($stmt = $mysqli -> prepare("insert into professors(prof) values(?)")){
					$stmt -> bind_param("s", $prof);
					$stmt -> execute();
					$stmt -> close();
				}
			}
		}

		if($stmt = $mysqli -> prepare("insert into notes(nid, title, class, professor, note, timeVal) values(?,?,?,?,?,?)")){
			$stmt -> bind_param("issssi", $curID, $title, $class, $prof, $note, $timeVal);
			$stmt -> execute();
			$stmt -> close();
			echo "<h1>Note Submitted</h1>";
		}
		else{
			echo "<h1>Failed to Submit Note</h1>";
		}
		if($stmt = $mysqli -> prepare("insert into noteID(id) values(?)")){
			$stmt -> bind_param("i", $curID);
			$stmt -> execute();
			$stmt -> close();
		}

		$typeOf = "text";
		//Insert into entries table
		if($stmt = $mysqli -> prepare("insert into entries(id, title, class, professor, type, user, timeVal) values(?,?,?,?,?,?,?)")){
			$stmt -> bind_param("isssssi", $curID, $title, $class, $prof, $typeOf, $curUser, $timeVal);
			$stmt -> execute();
			$stmt -> close();
		}
	} 

	else if($isFile && !$isNote){
		if(!($mysqli = new mysqli("oniddb.cws.oregonstate.edu", "braune-db", "GxGW1nC0BStHXQcB", "braune-db"))){
			echo "<h1>Could not connect to database<br></h1>";
		}	

		//Get unix time
		$timeVal = time();

		if($fileType == "image/jpeg" && $fileSize < 1048576){
			$fileData = file_get_contents($tmpFile);

			$query = $mysqli -> prepare("insert into uploads(fid, title, class, professor, filename, filedata) values(?, ?, ?, ?, ?, ?)");
			$empty = NULL;
			$query -> bind_param("issssb", $curID, $title, $class, $prof, $fileName, $empty);
			$query -> send_long_data(5, $fileData);
			$query -> execute();
			echo "<h2>File uploaded successfully</h2>";
		}
		else{
			exit("File must be jpg and under 1MB");
		}
		if($stmt = $mysqli -> prepare("insert into noteID(id) values(?)")){
			$stmt -> bind_param("i", $curID);
			$stmt -> execute();
			$stmt -> close();
		}

		if($result = $mysqli -> query("SELECT class FROM classes")){
			$count = 0;
			while($obj = $result -> fetch_object()){
				if($obj -> class == $class){
					$count += 1;
				}
			}
			if($count == 0){
				if($stmt = $mysqli -> prepare("insert into classes(class) values(?)")){
					$stmt -> bind_param("s", $class);
					$stmt -> execute();
					$stmt -> close();
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
			if($count == 0){
				if($stmt = $mysqli -> prepare("insert into professors(prof) values(?)")){
					$stmt -> bind_param("s", $prof);
					$stmt -> execute();
					$stmt -> close();
				}
			}
		}
		$typeOf = "image";
		//Insert into entries table
		if($stmt = $mysqli -> prepare("insert into entries(id, title, class, professor, type, user, timeVal) values(?,?,?,?,?,?,?)")){
			$stmt -> bind_param("isssssi", $curID, $title, $class, $prof, $typeOf, $curUser, $timeVal);
			$stmt -> execute();
			$stmt -> close();
		}
	}

	
	//If File and Note
	else if($isFile && $isNote){
		//Convert \n to <br>
		$note = nl2br($note);

		//Get unix time
		$timeVal = time();

		if(!($mysqli = new mysqli("oniddb.cws.oregonstate.edu", "braune-db", "GxGW1nC0BStHXQcB", "braune-db"))){
			echo "<h1>Could not connect to database<br></h1>";
		}	

		if($fileType == "image/jpeg" && $fileSize < 1048576){
			$fileData = file_get_contents($tmpFile);

			$query = $mysqli -> prepare("insert into uploads(fid, title, class, professor, filename, filedata) values(?, ?, ?, ?, ?, ?)");
			$empty = NULL;
			$query -> bind_param("issssb", $curID, $title, $class, $prof, $fileName, $empty);
			$query -> send_long_data(5, $fileData);
			$query -> execute();
			echo "<h2>File uploaded successfully</h2>";
		}
		else{
			exit("File must be jpg and under 1MB");
		}

		
		if($stmt = $mysqli -> prepare("insert into notes(nid, title, class, professor, note, timeVal) values(?,?,?,?,?,?)")){
			$stmt -> bind_param("issssi", $curID, $title, $class, $prof, $note, $timeVal);
			$stmt -> execute();
			$stmt -> close();
			echo "<h1>Note Submitted</h1>";
		}
		else{
			echo "<h1>Failed to Submit Note</h1>";
		}
	
		if($stmt = $mysqli -> prepare("insert into noteID(id) values(?)")){
			$stmt -> bind_param("i", $curID);
			$stmt -> execute();
			$stmt -> close();
		}

			
		//Update classes table
		if($result = $mysqli -> query("SELECT class FROM classes")){
			$count = 0;
			while($obj = $result -> fetch_object()){
				if($obj -> class == $class){
					$count += 1;
				}
			}
			if($count == 0){
				if($stmt = $mysqli -> prepare("insert into classes(class) values(?)")){
					$stmt -> bind_param("s", $class);
					$stmt -> execute();
					$stmt -> close();
				}
			}
		}

		//Update professors table
		if($result = $mysqli -> query("SELECT prof FROM professors")){
			$count = 0;
			while($obj = $result -> fetch_object()){
				if($obj -> prof == $prof){
					$count += 1;
				}
			}
			if($count == 0){
				if($stmt = $mysqli -> prepare("insert into professors(prof) values(?)")){
					$stmt -> bind_param("s", $prof);
					$stmt -> execute();
					$stmt -> close();
				}
			}
		}
		$typeOf = "text/image";
		//Insert into entries table
		if($stmt = $mysqli -> prepare("insert into entries(id, title, class, professor, type, user, timeVal) values(?,?,?,?,?,?,?)")){
			$stmt -> bind_param("isssssi", $curID, $title, $class, $prof, $typeOf, $curUser, $timeVal);
			$stmt -> execute();
			$stmt -> close();
		}
	}

	$mysqli -> close();
	?>
	
	

	<?php
		
		$mysqli = new mysqli("oniddb.cws.oregonstate.edu", "braune-db", "GxGW1nC0BStHXQcB", "braune-db");

		$combos = array();
		$myClasses = array();

		//Populate myClasses array
		if($newResult = $mysqli -> query("SELECT class FROM classes")){
			while($newObj = $newResult -> fetch_object()){
				if(in_array($newObj->class, $myClasses) == FALSE){
					array_push($myClasses, $newObj->class);
					$combos[$newObj -> class] = array();
				}
			}
		}

		//echo "classlength: ".sizeof($myClasses)."<br>";
		//Populate combos array from notes
		$i = 0;
		foreach($combos as $curClass){
			if($myResult = $mysqli -> query("SELECT professor FROM notes WHERE class='".$myClasses[$i]."'")){
				while($curObj = $myResult -> fetch_object()){
					if(in_array($curObj->professor, $combos[$myClasses[$i]]) == FALSE){
						//echo "Pushed ".$curObj->professor." into ".$myClasses[$i]." array<br>";
						array_push($combos[$myClasses[$i]], $curObj->professor); 
					}
				}
			}
			$i += 1;
		}

		//Populate combos array from uploads
		$i = 0;
		foreach($combos as $curClass){
			if($myResult = $mysqli -> query("SELECT professor FROM uploads WHERE class='".$myClasses[$i]."'")){
				while($curObj = $myResult -> fetch_object()){
					if(in_array($curObj->professor, $combos[$myClasses[$i]]) == FALSE){
						//echo "Pushed ".$curObj->professor." into ".$myClasses[$i]." array<br>";
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
					//echo "<br>Inserting ".$myClasses[$i].", ".$curProf." into combos table<br>";
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

	<a href="http://web.engr.oregonstate.edu/~braune/NormieNotes/View/fileView.php">
		<h2>View Notes</h2>
	</a>
</body>

