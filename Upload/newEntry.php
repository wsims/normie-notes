<html>

<head>
	<title>Submit a Note</title>
	<link type="text/css" rel="stylesheet" href="newEntryStyle.css" />
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>	
</head>

<body>
	<h1>Normie Notes</h1>
	<h3>Submit a Note</h3>
	<form action="noteSubmit.php" id="myForm" enctype="multipart/form-data" method="POST">
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
			<textarea name="note" id="note" placeholder="Enter your note here..."></textarea>
		</div>

		<div>
			<h2>File: </h2>
			<input type="file" id="file" class="type2" name="upload"></input>
		</div>
		
		<input type="submit" id="submit" onClick="validateForm(); return false">
	</form>

	<script>	
		//toggle required input if note or file is filled
		function validateForm(){
			var form = document.getElementById('myForm');
			var note = document.getElementById('note');
			var file = document.getElementById('file');

			if(note.value == '' && file.value ==''){
				alert("You must enter a note or upload an image");
				return false;
			}
			else{
				form.submit();
			}


		}
	</script>
</body>