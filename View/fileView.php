<?php include("../_header.php"); ?>
<html>
	<head>
		<title>Browse Notes</title>

		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.4.3/jquery.min.js"></script>

		<link type="text/css" rel="stylesheet" href="http://web.engr.oregonstate.edu/~omalleya/fileViewStyle.css"/>

		<style>
			#score {
				text-align: center;
				font-size: 18px;
				width: auto;
				height: 30px;
				margin: 0px;
				position: relative;
				color: #777777;
				line-height: 30px;
			}
			#title{
				margin-top: 33px;
				margin-left: 80px;
			}

			#noteID {
				display: none;
			}

			.upvote {
				display: inline;
				overflow: hidden;
				border: none;
				width: 40px;
				height: 25px;
				background: #F7F7F7;
				outline: none;
				margin-bottom: 10px;
				background-image: url(updoot.png);
			} 

			.upvote-on{
				background: #F7F7F7;
				background-image: url(updoot-on.png);
			}

			.downvote {
				display: inline;
				overflow: hidden;
				border: none;
				width: 40px;
				height: 25px;
				background: #F7F7F7;
				outline: none;
				margin-top: 10px;
				background-image: url(downdoot.png);
			}

			.downvote-on{
				background: #F7F7F7;
				background-image: url(downdoot-on.png);
			}

			#voting {
				display: inline;
				position: absolute;
				width: 80px;
				height: 100px;
				text-align: center;
			}

		</style>

	</head>

	<body>
		<!--<h1>Normie Notes</h1>
		<h2 id="subtitle">Note Search</h2>-->

		<?php
	

		if(!($mysqli = new mysqli("oniddb.cws.oregonstate.edu", "braune-db", "GxGW1nC0BStHXQcB", "braune-db"))){
			echo "<h1>Could not connect to database<br></h1>";
		}

		function notIn($myArray, $myValue){
			foreach($myArray as $index){
				if($index[0] == $myValue[0] && $index[1] == $myValue[1]){
					return 0;
				}
			}
			return 1;
		}
		
		//Populates Class/Professor associative array
		$myClassesTest = array();
		if($result = $mysqli -> query("SELECT class FROM classes")){
			while($obj = $result -> fetch_object()){
				$thisClass = $obj -> class;
				$newQuery = "SELECT class, professor FROM entries WHERE class='".$thisClass."'";
				if($newResult = $mysqli -> query($newQuery)){
					while($newObj = $newResult -> fetch_object()){
						$thisProf = $newObj -> professor;
						$newArray = array($thisClass, $thisProf);
						if(notIn($myClassesTest, $newArray)){
							array_push($myClassesTest, $newArray);
						}
					}		
				}
			}
		}
	
		
		$myClasses = array();
	
		//Populates class list
		if($result = $mysqli -> query("SELECT class FROM classes")){
			while($obj = $result -> fetch_object()){
				array_push($myClasses, $obj -> class);
			}
		}

		$mysqli -> close();

		?>


		<script>	
			//JS for creating/updating drop-down selects
			function createOption(mySelect, prof){
				var myOption = document.createElement("option");
				myOption.value = prof;
				myOption.text = prof;
				mySelect.options.add(myOption);
			}

			function updateOptions(selectC, selectP){
				var classes = <?php echo json_encode($myClassesTest); ?>;
				var curClass = selectC.value;
				var myProfs = [];

				console.log(classes);

				for(selection in classes){
					if(classes[selection][0] == curClass){
						myProfs.push(classes[selection][1]);
					}

				}
				selectP.options.length = 0;
				createOption(selectP, "Choose a professor");
				for(var i = 0; i < myProfs.length; i++){
					createOption(selectP, myProfs[i]);
				}
				
			}

		</script>
		
		<div id="formWrapper">	
			<form id="myForm" action="http://web.engr.oregonstate.edu/~braune/NormieNotes/View/fileView.php" method="GET">
				<select name = "selectC" id="selectC" onchange="updateOptions(this, document.getElementById('selectP'))">
					<option selected="selected">Choose a class</option>
					<?php
					foreach($myClasses as $curClass){ ?>
						<option value="<?php echo $curClass ?>"><?php echo $curClass ?></option>
					<?php
					}
					?>
				</select>

				<select name = "selectP" id="selectP">
					<option selected="selected">Choose a professor</option>
				</select>
			
				<input type="submit"> 
				<!--onclick="setTimeout(unhideParams(), 1000)"-->
			</form>
			<h3 id="searchParams"></h3>
		</div>	

		<div id="contentWrapper">
			<!--
			<div id="content">
				
				<h2 id="index">1</h2>
							
				<a href="http://web.engr.oregonstate.edu/~braune/testSite/noteView.php?nid=1" style="text-decoration:none">
					<h2 id="title">Title Goes Here</h2>
				</a>
							
				<h3 id="class">Class</h3>
								
				<h3 id="prof">Professor</h3>
				
			</div>
			-->
			<?php
				//Get user's upvoted and downvoted notes
				if($onidID){
					$mysqli = new mysqli("oniddb.cws.oregonstate.edu", "braune-db", "GxGW1nC0BStHXQcB", "braune-db");

					$result = $mysqli -> query("SELECT ups, downs FROM users WHERE username='".$onidID."'");
					$obj = $result -> fetch_object();

					$curUps = unserialize($obj -> ups);
					$curDowns = unserialize($obj -> downs);

					$mysqli -> close();
				}	


				$class = htmlspecialchars($_REQUEST["selectC"]);
				$prof = htmlspecialchars($_REQUEST["selectP"]);

				if($class == NULL && $prof == NULL){
					$class = "Choose a class";
					$prof = "Choose a professor";
				}

				if($class && $class != "Choose a class" && $prof != "Choose a professor"){
					$myQuery = "SELECT id, title, class, professor, user, timeVal, ups, downs FROM entries WHERE class='".$class."' AND professor='".$prof."' ORDER by downs-ups";
				}
				else if($class != "Choose a class" && $prof == "Choose a professor"){
					$myQuery = "SELECT id, title, class, professor, user, timeVal, ups, downs FROM entries WHERE class='".$class."' ORDER by downs-ups";
				}
				else if($class == "Choose a class" && $prof == "Choose a professor"){
					$myQuery = "SELECT id, title, class, professor, user, timeVal, ups, downs FROM entries ORDER by downs-ups";
				}


				if($class && $prof){
					$mysqli = new mysqli("oniddb.cws.oregonstate.edu", "braune-db", "GxGW1nC0BStHXQcB", "braune-db");
					
					$result = $mysqli -> query($myQuery);
					$count = 1;
					
					//Echo all entries with correct class and professor
					while($obj = $result -> fetch_object()){
						$thisNid = htmlspecialchars($obj -> id);
						$thisTitle = htmlspecialchars($obj -> title);
						$thisClass = htmlspecialchars($obj -> class);
						$thisProf = htmlspecialchars($obj -> professor);
						$thisUser = htmlspecialchars($obj -> user);
						$timeVal = htmlspecialchars($obj -> timeVal);
						$thisUps = htmlspecialchars($obj -> ups);
						$thisDowns = htmlspecialchars($obj -> downs);
						$score = $thisUps - $thisDowns;

						$sec = time() - $timeVal;
						$min = floor($sec / 60);
						$hr = floor($min / 60);
						$dy = floor($hr / 24);
						$wks = floor($dy / 7);
						$mnth = floor($wks / 4);
						$yrs = floor($mnth / 12);

						$times = array($sec, $min, $hr, $dy, $wks, $mnth, $yrs);
						$units = array(" second", " minute", " hour", " day", " week", " month", " year");

						$goTime;
						$goUnits;

						$thisUser = " by ".$thisUser;


						for($i = 0; $i < 7; $i++){
							if($times[$i] == 0){
								$goTime = $times[$i-1];
								$goUnits = $units[$i-1];
								break;
							}
							else if($i == 6){
								$goTime = $times[6];
								$goUnits = $units[6];
							}
						}

						if($goTime != 1){
							$goUnits .= "s";
						}

						$submitTime = "Submitted ".$goTime.$goUnits." ago";


						//Create content div for listing in entries table

						echo "<div id='content'>";	
						//echo "<h2 id='index'>".$count."</h2>";
						echo "<div id='voting'>";
						if($onidID && in_array($thisNid, $curUps)){
							echo "<button class='upvote upvote-on' onClick='toggleVote(this)'></button>";
						}
						else{
							echo "<button class='upvote' onClick='toggleVote(this)'></button>";
						}
						echo "<span id='noteID'>".$thisNid."</span>";
						echo "<h2 id='score'>".$score."</h2>";
						if($onidID && in_array($thisNid, $curDowns)){
							echo "<button class='downvote downvote-on' onClick='toggleVote(this)'></button>";
						}
						else{
							echo "<button class='downvote' onClick='toggleVote(this)'></button>";
						}
						echo "</div>";
						echo "<a href='http://web.engr.oregonstate.edu/~braune/NormieNotes/View/noteView.php?id=".$thisNid."' style='text-decoration:none'>";
						echo "<h2 id='title'>".$thisTitle."</h2>";
						echo "</a>";
						echo "<h2 id='time'>".$submitTime.$thisUser."</h2>";
						echo "<h3 id='class'>".$thisClass."</h3>";
						echo "<h3 id='prof'>".$thisProf."</h3>";
						echo "</div>";
						$count += 1;

					}
					$mysqli -> close();
				}
			?>

		</div>

		
		<script>

		//Passing PHP vars to JS		
		var selectC = <?php echo "'"; if($class != "Choose a class"){echo "".$class;}else{echo "all";} echo "'"; ?>;
		var selectP = <?php echo "'"; if($prof != "Choose a professor"){echo "".$prof;}else{echo "all";} echo "'"; ?>;
		

		//console.log(selectC);
		//console.log(selectP);

		/*
		var classSelect = document.getElementById("selectC");
		var profSelect = document.getElementById("selectP");

		for(choice in classSelect.options){
			if(classSelect.options[choice].text == selectC){
				classSelect.options[choice].selected = "selected";
			}
		}
		
		for(choice in profSelect.options){
			if(profSelect.options[choice].text == selectP){
				profSelect.options[choice].selected = "selected";
			}
		}
		*/

		var params = document.getElementById("searchParams");

		var myClass = "Class: " + selectC;
		var myProf = "Professor: " + selectP;

		var myParams = myClass + ", " + myProf;


		//console.log(myParams);
		params.innerHTML = myParams;

		/*
		$(function(){
			$('#content #voting').each(function(){
				if($(this).find('.upvote').hasClass('upvote-on') && $(this).find('.downvote').hasClass('downvote-on')){
					$(this).find('.upvote').removeClass('upvote-on');
					$(this).find('.downvote').removeClass('downvote-on');
				}
			});
		});
		*/
		
		//AJAX calls to PHP service for the four voting operations
		function addUpvote(username, note, $thisScore){
			var options = {
				user: username,
				id: note,
				action: 1,
			}
			$.ajax({
				type: 'POST',
				url: '/~braune/NormieNotes/Services/voting.php',
				data: options,
				success: function(response){
					$thisScore.text(response);
				},
			});
		}		

		function removeUpvote(username, note, $thisScore){
			var options = {
				user: username,
				id: note,
				action: 2,
			}
			$.ajax({
				type: 'POST',
				url: '/~braune/NormieNotes/Services/voting.php',
				data: options,
				success: function(response){
					$thisScore.text(response);
				},
			});
		}

		function addDownvote(username, note, $thisScore){
			var options = {
				user: username,
				id: note,
				action: 3,
			}
			$.ajax({
				type: 'POST',
				url: '/~braune/NormieNotes/Services/voting.php',
				data: options,
				success: function(response){
					$thisScore.text(response);
				},
			});
		}

		function removeDownvote(username, note, $thisScore){
			var options = {
				user: username,
				id: note,
				action: 4,
			}
			$.ajax({
				type: 'POST',
				url: '/~braune/NormieNotes/Services/voting.php',
				data: options,
				success: function(response){
					$thisScore.text(response);
				},
			});
		}

		// toggleVote is called when any voting button is clicked
		// 'item' parameter is the 'this' pointer for the voting button
		function toggleVote(item){
			if(loggedIn){
				var btn = $(item);
				var user = <?php echo "'".$onidID."'" ?>;
				var thisID = btn.parent().find("#noteID").text();
				var $score = btn.parent().find("#score");

				if(btn.hasClass("upvote")){
					btn.toggleClass("upvote-on");
					if(btn.hasClass("upvote-on")){
						addUpvote(user, thisID, $score);
					}
					else{
						removeUpvote(user, thisID, $score);
					}

					if(btn.parent().find(".downvote").hasClass("downvote-on")){
						btn.parent().find(".downvote").toggleClass("downvote-on");
						removeDownvote(user, thisID, $score);
					}
				}
				else{
					btn.toggleClass("downvote-on");
					if(btn.hasClass("downvote-on")){
						addDownvote(user, thisID, $score);
					}
					else{
						removeDownvote(user, thisID, $score);
					}

					if(btn.parent().find(".upvote").hasClass("upvote-on")){
						btn.parent().find(".upvote").toggleClass("upvote-on");
						removeUpvote(user, thisID, $score);
					}
				}
				//Reloads page
				//window.location.reload();
			}
			else{
				//Alerts user's that aren't logged in
				alert("You must be logged in to vote");
			}
		}
		</script>
		
	</body>
