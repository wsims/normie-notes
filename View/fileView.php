<?php include("../_header.php"); ?>
<html>
	<head>
		<title>Browse Notes</title>

		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.4.3/jquery.min.js"></script>

		<link type="text/css" rel="stylesheet" href="http://web.engr.oregonstate.edu/~simsw/fileViewStyle.css"/>

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
	

		if($result = $mysqli -> query("SELECT class FROM classes")){
			while($obj = $result -> fetch_object()){
				array_push($myClasses, $obj -> class);
			}
		}

		$mysqli -> close();

		?>

		<script>	
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
				$class = $_REQUEST["selectC"];
				$prof = $_REQUEST["selectP"];

				if($class == NULL && $prof == NULL){
					$class = "Choose a class";
					$prof = "Choose a professor";
				}

				if($class && $class != "Choose a class" && $prof != "Choose a professor"){
					$myQuery = "SELECT id, title, class, professor, user, timeVal FROM entries WHERE class='".$class."' AND professor='".$prof."'";
				}
				else if($class != "Choose a class" && $prof == "Choose a professor"){
					$myQuery = "SELECT id, title, class, professor, user, timeVal FROM entries WHERE class='".$class."'";
				}
				else if($class == "Choose a class" && $prof == "Choose a professor"){
					$myQuery = "SELECT id, title, class, professor, user, timeVal FROM entries";
				}


				if($class && $prof){
					$mysqli = new mysqli("oniddb.cws.oregonstate.edu", "braune-db", "GxGW1nC0BStHXQcB", "braune-db");
					
					$result = $mysqli -> query($myQuery);
					$count = 1;
					while($obj = $result -> fetch_object()){
						$thisNid = $obj -> id;
						$thisTitle = ($obj -> title);
						$thisClass = ($obj -> class);
						$thisProf = ($obj -> professor);
						$thisUser = ($obj -> user);
						$timeVal = ($obj -> timeVal);

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



						echo "<div id='content'>";	
						echo "<h2 id='index'>".$count."</h2>";
						echo "<a href='http://web.engr.oregonstate.edu/~braune/NormieNotes/View/noteView.php?id=".$thisNid."' style='text-decoration:none'>";
						echo "<h2 id='title'>".$thisTitle."</h2>";
						echo "</a>";
						echo "<h2 id='time'>".$submitTime.$thisUser."</h2>";
						echo "<h3 id='class'>".$thisClass."</h3>";
						echo "<h3 id='prof'>".$thisProf."</h3>";
						echo "</div>";
						$count += 1;

					}
				}
			?>

			

		</div>

		
		<script>

		
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
		$(document).ready(function(){
			
			var isRequest = <?php if($_REQUEST["selectC"]){ echo 1;} else{ echo 0;} ?>;
			var params = document.getElementById("searchParams");
			console.log(isRequest);

			if(isRequest){
				params.style.visibility = "visible";
			}
		});
		*/
		</script>
		
	</body>
