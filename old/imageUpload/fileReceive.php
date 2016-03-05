<html>
	<head>
		<title>Receiving file</title>
	</head>

	<body>
		<h1>Test</h1>

		<?php

		$mysqli = new mysqli("oniddb.cws.oregonstate.edu", "braune-db", "GxGW1nC0BStHXQcB", "braune-db");


		if($_SERVER['REQUEST_METHOD'] == "POST"){
			if($_FILES["myfile"]){
				$errorInfo = $_FILES["myfile"]["error"];
				$fileName = $_FILES["myfile"]["name"];
				$tmpFile = $_FILES["myfile"]["tmp_name"];
				$fileSize = $_FILES["myfile"]["size"];
				$fileType = $_FILES["myfile"]["type"];

				$title = htmlspecialchars($_REQUEST["title"]);
				$class = htmlspecialchars($_REQUEST["class"]);
				$prof = htmlspecialchars($_REQUEST["prof"]);

				if($fileType == "image/jpeg" && $fileSize < 1048576){
					$fileData = file_get_contents($tmpFile);

					$query = $mysqli -> prepare("insert into uploads(title, class, professor, filename, filedata) values(?, ?, ?, ?, ?)");
					$empty = NULL;
					$query -> bind_param("ssssb", $title, $class, $prof, $fileName, $empty);
					$query -> send_long_data(4, $fileData);
					$query -> execute();
					echo "<h2>File uploaded successfully</h2>";
				}
				else{
					echo "File must be jpg and under 1MB";
				}
			}
			else{
				echo "<h1>ERROR</h1>";
			}
		}
		$mysqli -> close();

		?>
	</body>