<html>

<head>
	<title>Submit a Note</title>
	<link type="text/css" rel="stylesheet" href="dataEntryStyle.css" />

	
</head>

<body>
	<h1>Normie Notes</h1>
	<h3>Submit a Note</h3>
	<form action="noteSubmitTest.php" method="POST">
		<div>
			<h2>Note Title: </h2>
			<input type="text" name="title" class="type1" placeholder="Enter note title..." required>
		</div>

		<div>
			<h2>Class: </h2>
			<input type="text" name="class"  class="type1" placeholder="Enter class..." required>
		</div>

		<div>
			<h2>Professor: </h2>
			<input type="text" name="prof" class="type1" placeholder="Enter Professor's name..." required>
		</div>

		<div>
			<h2>Note: </h2>
			<textarea name="note" placeholder="Enter your note here..." required></textarea>
		</div>
		
		<input type="submit" id="submit" >
	</form>

</body>