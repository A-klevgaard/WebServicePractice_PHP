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
    <title>Austin Klevgaard - ICA 06 - Messages</title>
    <link rel="stylesheet" type="text/css" href="ICA06.css"> 
    <script src="//ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script type="text/javascript" src="./js/jslib.js"></script>
    <script type="text/javascript" src="./js/messages.js"></script>
</head>
<body>
    <div id="site">
        <div id="header-bar" class="middleGrid">
            <header>
                <div class="center">
                <h1>
                    <?php
                        echo "ICA 06 - Messages : {$_SESSION['username']}";
                    ?>
                </h1>
                </div>

            </header>
        </div>

        <div id="content">
            <form action="" method="GET" name="userAdd">
                <div class="twoColumnGrid background">
                    
                    <div class="right">
                        <label>Filter: </label>
                    </div>
                    <div >
                        <input class="credInput" id="filterInput" name="filterInput" type="text" placeholder="Supply a filter">
                    </div>
                    <button type="button" id="filterMessageButton" name="filterMessage" value="filterMessage" class="fillWidth colspan2">Search</button>
                    <div class="right">
                        <label>Post Message: </label>
                    </div>
                    <div >
                        <input class="credInput" id="messageInput" name="messageInput" type="text" placeholder="Enter a message to share">
                    </div>
                    <button type="button" id="sendMessageButton" name="sendMessage" value="sendMessage" class="fillWidth colspan2">Send Message</button>
                </div>
            </form>
        </div>
        <div id="tableContainer" class="middleColumn center ">
            <table id="messagesTable" class="fillWidth">
                <thead>
                    <tr>
                        <th id="Op">Op</th>
                        <th id="table-MessageID">MessageID</th>
                        <th id="table-User">User</th>
                        <th id="table-Message">Message</th>
                        <th id="table-TimeStamp">TimeStamp</th>
                    </tr>
                </thead>
                <tbody id="messagesTableBody"></tbody>
            </table>
        </div>

        <div id="statusOutput" class="middleColumn">
            <p id="statusMessage" class="statusMessage"></p>
            <p id="actionMessage" class="statusMessage"></p>
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