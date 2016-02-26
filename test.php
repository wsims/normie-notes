<html>
<link rel="stylesheet" href="style.css">
<head><title>NoteShare</title></head>
<body>
    <main>
        <div id = "menu">
            <ul>
                <li>Home</li>
                <li>Classes</li>
                <li>Submit Notes</li>
                <li>Account</li>
            </ul>
        </div>
        <h1>Welcome to NormieNotes</h1>
        
        <div id="body">
        <?php
            
        $dbhost = 'oniddb.cws.oregonstate.edu';
        $dbname = 'omalleya-db';
        $dbuser = 'omalleya-db';
        $dbpass = 'c7Olrwr4i4r9xGCb';
            
        $mysql_handle = mysql_connect($dbhost, $dbuser, $dbpass)
    or die("Error connecting to database server");

        mysql_select_db($dbname, $mysql_handle)
            or die("Error selecting database: $dbname");

        echo 'Successfully connected to database! <br>';
            
        $mysqli = new mysqli("oniddb.cws.oregonstate.edu",'omalleya-db', 'c7Olrwr4i4r9xGCb', 'omalleya-db');
        
        $mysqli->query("drop table classes");
        $mysqli->query("drop table users");
        
        if(!$mysqli->query("create table classes(course varchar(32), cid integer, instructor varchar(32), primary key(cid))") || !$mysqli->query("create table users(usernameID int(9) NOT NULL auto_increment, username varchar(40) NOT NULL, pass varchar(40) NOT NULL, primary key(usernameID))"))
        {
            echo 'Cannot create table';
        }
            
        $mysqli->query("insert into users(username, pass) values('Will', '123')");
        $mysqli->query("insert into users(username, pass) values('Bob', 'skngjslkjdg')");
        $mysqli->query("insert into users(username, pass) values('Aidan', 'gskjdg')");

        function signIn() {
            session_start();
            if(!empty($_POST['user'])) {
                $query = mysql_query("SELECT * FROM users where username = '$_POST[user]' AND pass = '$_POST[pass]'") or die(mysql_error()); $row = mysql_fetch_array($query);
                if(!empty($row['username']) AND !empty($row['pass'])) { 
                    $_SESSION['username'] = $row['pass']; echo 'Successful log in <br>'; 
                } else {
                    echo 'Invalid username or password <br>';
                }
            }
        }
    
        if (isset($_POST['submit'])) {
            signIn();
        }
        echo "<div id=\"table\">";
        echo "<table>";?>
        <tr>
        <th>ID</th>
        <th>Username</th>
        <th>Password</th>
        </tr>
        
        <?php
        if($result = $mysqli->query("select usernameID, username, pass from users")){
            while($obj = $result->fetch_object()){
            echo "<tr>";
            echo "<td>".htmlspecialchars($obj->usernameID)."</td>";
            echo "<td>".htmlspecialchars($obj->username)."</td>";
            echo "<td>".htmlspecialchars($obj->pass)."</td>";
            echo "</tr>";
            }
            $result->close();
        }
        echo "</table>";    
            
              
        $mysqli->query("insert into classes values('MTH', 111, 'Dillon')");
        $mysqli->query("insert into classes values('MTH', 254, 'Woon Kim')");
        $mysqli->query("insert into classes values('MTH', 341, 'Cozzi')");
        
        
        echo "<table>";
        ?>
        
        <tr>
        <th>Course</th>
        <th>CID</th>
        <th>Instructor</th>
        </tr>
            
        <?php
        
        
        if($result = $mysqli->query("select course, cid, instructor from classes")){
            while($obj = $result->fetch_object()){
            echo "<tr>";
            echo "<td>";
            echo "<a href=\"testclasspage.php\">".htmlspecialchars($obj->course)."</a>";
            echo "</td>";
            echo "<td>".htmlspecialchars($obj->cid)."</td>";
            echo "<td>".htmlspecialchars($obj->instructor)."</td>";
            echo "</tr>";
            }
            $result->close();
        }
        echo "</table>";
        echo "</div>";

        mysql_close($mysql_handle);

        ?>
            </div>
    </main>    
</body>
    
</html>