<?php 
//Austin Klevgaard
//Submission Code : 1201_2500_A06
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
    <title>Austin Klevgaard - ICA 06 - Settings</title>
    <link rel="stylesheet" type="text/css" href="ICA06.css"> 
    <script src="//ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script type="text/javascript" src="./js/jslib.js"></script>
    <script type="text/javascript" src="./js/settings.js"></script>
</head>
<body>
    <div id="site">
        <div id="header-bar" class="middleGrid">
            <header>
                <div class="center">
                <h1>
                    <?php
                        echo "ICA 06 - Settings : {$_SESSION['username']}";
                    ?>
                </h1>
                </div>

            </header>
        </div>

        <div id="content">
            <form action="" method="post" name="userAdd">
                <div class="twoColumnGrid background">
                    
                    <div class="right">
                        <label>Username: </label>
                    </div>
                    <div >
                        <input class="credInput" id="userInput" name="usernameInput" type="text" placeholder="Supply a username">
                    </div>
                    <div class="right">
                        <label>Password: </label>
                    </div>
                    <div >
                        <input class="credInput" id="passInput" name="passwordInput" type="text" placeholder="Supply a password">
                    </div>
                    <button type="button" id="addUserButton" name="submit" value="addUser" class="colspan2">Add User</button>
                </div>
            </form>
        </div>
        <div id="tableContainer" class="middleColumn center ">
            <table id="outputTable" class="fillWidth">
                <thead>
                    <tr>
                        <th id="Op">Op</th>
                        <th id="table-userID">userID</th>
                        <th id="table-userName">UserName</th>
                        <th id="table-password">Encrypted Password</th>
                    </tr>
                </thead>
                <tbody id="userTableBody"></tbody>
            </table>
        </div>
        <!-- Dont need to have a logout button with the settings -->
        <!-- <div class="colspan2 middleColumn" >
            <form action="login.php" method="post" >
                <button  type="submit" name="submit" value="Logout" class="fillWidth">Logout</button>
            </form>
        </div> -->

        <div id="statusOutput" class="middleColumn">
            <p id="statusMessage" class="statusMessage"></p>
            <p id="actionMessage" class="statusMessage"></p>
            <!-- Status output is now controlled with js instead of php -->
            <!-- <div class="center">    
                <?php //echo "Page Status: {$pageStatus}"?>          
            </div>            -->
        </div>
        <div id="actionStatus" class="middleColumn">
            
        <div>

        <div id="homeLink" class="middleGrid center ">
            <h4><a class="homeAnchor" href="index.php">&Omega;</a></h4>
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