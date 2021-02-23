//Austin Klevgaard
//Submission Code : 1201_2500_A06

var baseRESTurl = "./svc/messages/";

$(function () {
    $('#filterMessageButton').on('click',GetMessages);
    $('#sendMessageButton').on('click', PostMessage);
    GetMessages();
});
//Function to retrieve user's posted messages from the database
function GetMessages()
{
    let sendData = {};
    sendData['filter'] = $('#filterInput').val(); //record of the filter used to get the messages
    let URL = baseRESTurl + $('#filterInput').val();    //base url + our filter argument for REST api$
    AjaxRequest(URL, 'GET', sendData, 'json', ShowMessages, ErrorHandler);
}
//shows user message data in table format
function ShowMessages(responseData, status)
{
    $('#statusMessage').html(responseData['status']);
    
    console.log(responseData);
    
    //creates a table of users from returned json data
    MakeTable(responseData['data']);
}

//sends an ajax request to our api to post a message to the databse
function PostMessage()
{
    let sendData = {};
    sendData['message'] = $('#messageInput').val();
    AjaxRequest(baseRESTurl, 'Post', sendData, 'json', MessagePosted, ErrorHandler);
}

//if ajax request message post is successful then clear out input text boxes and update the message board
function MessagePosted(responseData, status)
{
    console.log(responseData);
    //update action status 
    $('#actionMessage').html(responseData['data']);
    //clear out the message input box and the filter box
    $('#messageInput').val("");
    $('#filterInput').val("");
    GetMessages();
}
//If ajax request message delete is successful then clear out the filter box and update the message boards
function DeleteMessage(responseData, status)
{
    console.log(responseData);
    //update action status 
    $('#actionMessage').html(responseData['data']);
    //clear out the filter box
    $('#filterInput').val("");
    GetMessages();
}

//Builds a table from provided user message data
function MakeTable(userData)
{
    console.log(userData);
    let outputTable = $('#messagesTable');    //output container

    //clears out the table body of any old data, but keeps the headers since they are prebuilt
    $('#messagesTableBody').html("");

    //creates a new row, with an empty first column to fill with operations later
    for (key in userData)
    {
        //creates a new row and gives it an empty column to put a button in later (OP columns)
        let newRow = document.createElement('tr');
        let opColumn = document.createElement('td');
        newRow.append(opColumn);

        //creates a new data column populated with user info
        let userMessage = userData[key];
        for (item in userMessage)
        {   
            if (item == 'messageID')
            {
                //creates a delete user Button for the message
                let deleteButton = document.createElement('button');
                deleteButton.classList.add("DeleteBtn");

                //assigns the delete button a value based on each unique messageID, then adds it to the table
                deleteButton.value = userMessage[item];
                deleteButton.innerHTML = "Delete";
                opColumn.append(deleteButton);
            }
            let newData = document.createElement('td');
            newData.textContent = userMessage[item];
            newRow.append(newData);


        }
        //appends to table
        outputTable.append(newRow);
    } 
    //adds events handlers for all the delete buttons
    //event sends an ajax request to our messages api to delete a posted message
    $('.DeleteBtn').on('click', function(){

        let sendData = {};
        sendData['messageID'] = this.value; //Note: sendData is not actually needed or used by our api in the delete request
        //as the required info is parsed from the url however, I will send it for potential debugging purposes.

        let url = baseRESTurl + this.value; //this.value is the value of the button associated with each message (messageID)
        AjaxRequest(url, 'DELETE', sendData, 'json', DeleteMessage, ErrorHandler );
    });
}

