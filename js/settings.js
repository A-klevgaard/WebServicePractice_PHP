//Name: Austin Klevgaard - ICA02 - Submissions Code : 1201_2500_A04 


var getUsersURL = 'webservice.php'; //relative path url to our php webservice

$(function () {

    GetUsers(); //grabs the users from the server database
    $('#addUserButton').on('click', AddUser);
});

//Adds a user into the database
function AddUser()
{
    let sendData = {};
    sendData['action'] = 'AddUser';
    sendData['user'] = $('#userInput').val();
    sendData['pass'] = $('#passInput').val();
    sendData['callingFunction'] = "AddUser";
    console.log(sendData);
    AjaxRequest(getUsersURL,'POST', sendData, 'json', NewUserRefresh, ErrorHandler);
}
//deletes a user from the database
function DeleteUser()
{
    console.log("delete fired");
    console.log(this.value);
    let sendData = {};
    sendData['action'] = 'DeleteUser';
    sendData['userID'] = this.value;
    AjaxRequest(getUsersURL,'POST', sendData, 'json', NewUserRefresh, ErrorHandler);
    
}
//Refreshes the user table on the webpage
function NewUserRefresh(responseData, status)
{
    //current spot Need to finish off the add user ajax call and then work on web service
    console.log("NewUserRefresh");
    console.log(responseData);
    console.log(status);

    $('#actionMessage').html(responseData['status']);
    //Updates the user table with the new added user data
    GetUsers();
}

//Sends an ajax request to our webservice to grab user info from our database
function GetUsers()
{
    let sendData = {};
    sendData['action'] = 'GET';
    AjaxRequest(getUsersURL,'GET', sendData, 'json', ShowUsers, ErrorHandler);
    //AjaxRequest (url, type, data, dataType, successFunction, errorFunction) 
}

//Takes user data returned from an ajax request and 
function ShowUsers(responseData, status)
{
    //logs status and response to the console for verification
    //console.log(status);
    //console.log(responseData);
    //updates the webpage status output message to show the user what occured from the webservice call
    $('#statusMessage').html(responseData['status']);
    //creates a table of users from returned json data
    MakeTable(responseData['data']['jsonData']);
}
//Builds a table from provided user data
function MakeTable( userData)
{
    console.log(userData);
    let outputTable = $('#outputTable');    //output container

    //clears out the table body of any old data, but keeps the headers since they are prebuilt
    $('#userTableBody').html("");

    //creates a new row, with an empty first column to fill with operations later
    for (key in userData)
    {
        //creates a new row and gives it an empty column to put a button in later
        let newRow = document.createElement('tr');
        let opColumn = document.createElement('td');
        newRow.append(opColumn);
        //creates a delete user Button for every user
        let deleteButton = document.createElement('button');
        deleteButton.classList.add("DeleteBtn");

        //creates a new data column populated with user info
        let userCreds = userData[key];
        for (item in userCreds)
        {   
            if (item == 'userID')
            {
                //assigns the delete button a value based on each unique userID, then adds it to the table
                deleteButton.value = userCreds[item];
                deleteButton.innerHTML = "Delete User";
                opColumn.append(deleteButton);
            }
            let newData = document.createElement('td');
            newData.textContent = userCreds[item];
            newRow.append(newData);
        }
        //appends to table
        outputTable.append(newRow);
        
        //adds events handlers for all the delete buttons
        $('.DeleteBtn').on('click', DeleteUser);
    } 
}