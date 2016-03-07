<html>
	<head>
		<title>Note View</title>
	
		<link type="text/css" rel="stylesheet" href="http://web.engr.oregonstate.edu/~braune/NormieNotes/View/noteViewStyle.css"/>

	</head>

	<body>

		<h1>Normie Notes</h1>
		<h3 id="subtitle">Note View</h3>

		<?php


		$id = $_GET["id"];
		$validIds = array();

		$mysqli = new mysqli("oniddb.cws.oregonstate.edu", "braune-db", "GxGW1nC0BStHXQcB", "braune-db");
		$myResult = $mysqli -> query("SELECT id FROM noteID");
		while($myObj = $myResult -> fetch_object()){
			array_push($validIds, ($myObj -> id));
		}

		function isValid($valid, $cur){
			$realId = FALSE;

			foreach($valid as $thisId){
				if($thisId == $cur){
					$realId = TRUE;
					break;
				}
			}
			return $realId;
		}



		if(isValid($validIds, $id)){
			$mysqli = new mysqli("oniddb.cws.oregonstate.edu", "braune-db", "GxGW1nC0BStHXQcB", "braune-db");

			$myQuery = "SELECT type FROM entries WHERE id='".$id."'";

			$result = $mysqli -> query($myQuery);
			$obj = $result -> fetch_object();
			$myType = $obj -> type;

			if($myType == "text"){
				$thisQuery = "SELECT title, class, professor, note FROM notes WHERE nid='".$id."'";
			
				$result = $mysqli -> query($thisQuery);
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
			else if($myType == "image"){
				$thisQuery = "SELECT title, class, professor FROM entries WHERE id='".$id."'";

				$result = $mysqli -> query($thisQuery);
				$obj = $result -> fetch_object();

				$title = ($obj -> title);
				$class = ($obj -> class);
				$prof = ($obj -> professor);

				echo "<div id='mainWrapper'>";
				echo "<h2 id='title'>".$title."</h2>";
				echo "<div id='innerWrapper'>";
				echo "<h2 id='class'>".$class."</h2>";
				echo "<h2 id='prof'>".$prof."</h2>";
				echo "</div>";
				echo "<div id='imageWrapper'>";
				echo "<img id='upload' src='http://web.engr.oregonstate.edu/~braune/NormieNotes/getImage.php?id=".$id."'/>";
				echo "</div>";
				echo "</div>";

			}
			else if($myType == "text/image"){
				$thisQuery = "SELECT title, class, professor, note FROM notes WHERE nid='".$id."'";
			
				$result = $mysqli -> query($thisQuery);
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
				echo "<div id='imageWrapper'>";
				echo "<img id='upload' src='http://web.engr.oregonstate.edu/~braune/NormieNotes/getImage.php?id=".$id."'/>";
				echo "</div>";
				echo "</div>";
			}
		}	
		else{
			echo "<h2>Error: invalid file id</h2>";
		}

		$mysqli -> close();


			
		?>

	</body>