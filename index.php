<?php 
//Austin Klevgaard
//Submission Code : 1201_2500_A06

//PREFIX PHP
require_once "./inc/db.php";
require_once 'functions.php';
// Set to always get fresh page processing, no caches supplied
header("Cache-Control: no-cache, must re-validate");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");

if (!isset($_SESSION['username']))
{
    header('location:login.php');
    die();
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Austin Klevgaard - ICA 06 - I had a CRUD-DY REST last night</title>
    <link rel="stylesheet" type="text/css" href=".\ICA06.css"> 
    <script src="//ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
    <div id="site">
        <div id="header-bar" class="middleGrid">
            <header>
                <div class="center">
                    <h1>
                        <?php
                            echo "ICA 06 - Index : {$_SESSION['username']}";
                        ?>
                    </h1>
                </div>

            </header>
        </div>

        <div id="content">
            <div class="twoColumnGrid">
                <div class="center"><a href="settings.php">Settings</a></div>
                <div class="center"><a href="messages.php">Messages</a></div>
                <div class="center"><a href="admin.php">Tag Admin</a></div>
                <div class="center"><a href="realtime.php">RealTime Moniter</a></div>
                <div class="colspan2">
                    <form action="login.php" method="post" >
                        <button  type="submit" name="submit" value="Logout" class="fillWidth">Logout</button>
                    </form>
                </div>
            </div>
        </div>

        <div id="statusOutput">
            <div class="center">Page Status: </div>
            
        </div>

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