<html>
	<head>
		<title>Note View</title>
	
		<link type="text/css" rel="stylesheet" href="http://web.engr.oregonstate.edu/~braune/NormieNotes/noteViewStyle.css"/>

	</head>

	<body>

		<h1>Normie Notes</h1>
		<h3 id="subtitle">Note View</h3>

		<?php


		$nid = $_GET["nid"];
		$validIds = array();

		$mysqli = new mysqli("oniddb.cws.oregonstate.edu", "braune-db", "GxGW1nC0BStHXQcB", "braune-db");
		$myResult = $mysqli -> query("SELECT nid FROM notes");
		while($myObj = $myResult -> fetch_object()){
			array_push($validIds, ($myObj -> nid));
		}

		function isValid($valid, $id){
			$realId = FALSE;

			foreach($valid as $thisId){
				if($thisId == $id){
					$realId = TRUE;
					break;
				}
			}
			return $realId;
		}



		if(isValid($validIds, $nid)){
			$mysqli = new mysqli("oniddb.cws.oregonstate.edu", "braune-db", "GxGW1nC0BStHXQcB", "braune-db");

			$myQuery = "SELECT title, class, professor, note FROM notes WHERE nid='".$nid."'";
			
			$result = $mysqli -> query($myQuery);
			$obj = $result -> fetch_object();

			
			$title = ($obj -> title);
			$class = ($obj -> class);
			$prof = ($obj -> professor);
			$note = ($obj -> note);

			echo "<div id='mainWrapper'>";
			echo "<h2 id='title'>".$title."</h2>";
			echo "<div id='innerWrapper'>";
			echo "<h2 id='class'>".$class."</h2>";
			echo "<h2 id='prof'>".$prof."</h2>";
			echo "</div>";
			echo "<div id='noteWrapper'>";
			echo "<p id='note'>".$note."</p>";
			echo "</div>";
			echo "</div>";

		}
		else{
			echo "<h2>Error: invalid file id</h2>";
		}
		?>

	</body>