<html>
	<head>
		<title>View Images</title>
		<link type="text/css" rel="stylesheet" href="http://web.engr.oregonstate.edu/~braune/NormieNotes/imageViewStyle.css"/>
	</head>
	<body>
		<h1>Normie Notes</h1>
		<h3 id="subtitle">Image View</h3>

		<?php

		if(!($mysqli = new mysqli("oniddb.cws.oregonstate.edu", "braune-db", "GxGW1nC0BStHXQcB", "braune-db"))){
			echo "<h1>Could not connect to database<br></h1>";
		}

		$id = $_GET['id'];
		// do some validation here to ensure id is safe

		$myQuery = "SELECT title, class, professor FROM uploads WHERE fid='".$id."'"; 
		if($result = $mysqli -> query($myQuery)){
			$obj = $result -> fetch_object();
			$title = $obj -> title;
			$class = $obj -> class;
			$professor = $obj -> professor;
		}

		$mysqli -> close();

		?><script> 
		var myTitle = <?php echo "'".$title."'" ?> 
		var myClass = <?php echo "'".$class."'" ?> 
		var myProf = <?php echo "'".$professor."'" ?> 
		console.log("Test\n")
		console.log("Title: " + myTitle);
		console.log("Class: " + myClass);
		console.log("Professor: " + myProf);
		</script> <?php

		echo "<div id='mainWrapper'>";
		echo "<h2 id='title'>".$title."</h2>";
		echo "<div id='innerWrapper'>";
		echo "<h2 id='class'>".$class."</h2>";
		echo "<h2 id='prof'>".$professor."</h2>";
		echo "</div>";
		echo "<div id='imageWrapper'>";
		echo "<img id='upload' src='http://web.engr.oregonstate.edu/~braune/NormieNotes/getImage.php?id=".$id."'/>";
		echo "</div>";
		echo "</div>";


		?>
	</body>