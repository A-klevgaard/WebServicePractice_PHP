<?php 
//Austin Klevgaard
//Submission Code : 1201_2500_A06

//PREFIX PHP
require_once "./inc/db.php";
require_once 'functions.php';

// Set to always get fresh page processing, no caches supplied
header("Cache-Control: no-cache, must re-validate");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");

$pageStatus = "";

//if logout was initiated then end the current session and redirect to the index page
if (isset($_POST['submit']) && $_POST['submit'] == "Logout")
{
    session_destroy();
    session_unset();
    header('location:index.php');
    die();
}
//if login was selected then grab a the sanitized user input and place it into the user array
if (isset($_POST['submit']) && $_POST['submit'] == "Login")
{
    //sanitize the inputs
    $userName = strip_tags($_POST['username']);
    $password = strip_tags($_POST['password']);
    //save the data into a user array
    $userData = array();
    $userData['username'] = $userName;
    $userData['password'] = $password;
    $userData['userID'] = "";
    $userData['response'] = "";
    $userData['status'] = false;
    $userData['test'] = "";

    $userData = Validate($userData);

    if ($userData['status'] == true)
    {
        //if success occurs then authenticate the username in the session
        $_SESSION['username'] = $userData['username'];
        $_SESSION['userID'] = $userData['userID'];
        header('location:index.php');
        die();
    }
    else
    {
        $pageStatus = $userData['response'];   
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Austin Klevgaard - ICA 06 - Login</title>
    <link rel="stylesheet" type="text/css" href=".\ICA06.css"> 
    <script src="//ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
    <div id="site">
        <div id="header-bar" class="middleGrid">
            <header>
                <div class="center">
                    <h1>Austin Klevgaard - ICA 06 - Login</h1>
                </div>

            </header>
        </div>

        <div id="content">
            <form action="" method="post" name="credentialForm">
                <div class="twoColumnGrid background">
                    
                    <div class="right">
                        <label>Username: </label>
                    </div>
                    <div >
                        <input class="credInput" type="text" placeholder="Supply a username" name="username">admin
                    </div>
                    <div class="right">
                        <label>Password: </label>
                    </div>
                    <div >
                        <input class="credInput" type="password" placeholder="Supply a password" name="password">god
                    </div>
                    <button type="submit" name="submit" value="Login" class="colspan2">Login</button>
                </div>
            </form>
        </div>
        <div id="statusOutput">
            <div class="center">    
                <?php echo "Page Status: {$pageStatus}"?>          
            </div>       
        </div>
        <a href="../icas.html" class="center middleGrid">Return to ICAs Home</a>

        <div id="footer-bar" class="middleGrid ">
            <div class="center">
                <footer >
                    &copy;2021 by Austin Klevgaard <br>
                    <?php echo "Last modified: " . date ("d-M-Y H:i:s.", getlastmod()); ?>
                        <!-- <script>
                            document.write('Last Modified: ' + document.lastModified); 
                        </script> -->
                </footer>
            </div>
        </div>
    </div>
</body>
</html>