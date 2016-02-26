<html>
    <head>
        <title>Log In</title>
        <link rel="stylesheet" type="text/css" href="login.css">
    </head>
    <body id = "bodyColor">
            <form class = "form-4" method="POST" action="test.php"> 
                <h1>Welcome to NormieNotes</h1>
                <h2>Login or Register</h2>
                <form action="test.php" method="POST">
            <p>
                <label for="login">Username or email</label>   
                <input id = username type="text" name="user" placeholder="Username or email" required>
            </p>
            <p>
                <input id = password type="password" name="pass" placeholder="Password" required>
            </p>
            <p>
                <input id="button" type="submit" name="submit" value="Submit">
            </p>
            </form>
    </body>
        

</html>