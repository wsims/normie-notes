<html>

	<head>
		<title>Upload a File</title>
		<link type="text/css" rel="stylesheet" href="fileUploadStyle.css"/>
	</head>
	<body>
		<h1>Normie Notes</h1>
		<h2>Upload a File</h2>
		<form method="POST" action="fileReceive.php" enctype="multipart/form-data">
			<div>
				<h3>Note Title: </h2>
				<input type="text" name="title" class="type1" placeholder="Enter note title..." required>
			</div>

			<div>
				<h3>Class: </h2>
				<input type="text" name="class"  class="type1" placeholder="Enter class..." required>
			</div>

			<div>
				<h3>Professor: </h2>
				<input type="text" name="prof" class="type1" placeholder="Enter Professor's name..." required>
			</div>

			<div>
				<h3>File: </h3>
			</div>

			<div id="wrapper1">
				<input type="file" class="type2" name="myfile">
			</div>
		
			<div id="wrapper2">
				<input id="submit" type="submit" value="Submit File">
			</div>
		</form>

	</body>