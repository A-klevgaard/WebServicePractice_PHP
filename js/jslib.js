//Austin Klevgaard
//Submission Code : 1201_2500_A06
$(function () {
    
});

//Helper function which bundles up an ajax request into an object then calls the request
function AjaxRequest (url, type, data, dataType, successFunction, errorFunction) 
{
    let ajaxOptions = {}; 
        ajaxOptions['url'] = url;
        ajaxOptions['type'] = type;
        ajaxOptions['data'] = data;
        ajaxOptions['dataType'] = dataType;
        ajaxOptions['success'] = successFunction;
        ajaxOptions['error'] = errorFunction;

        //logs the ajax request object for error checking and verification
        console.log(ajaxOptions);
        //sends the ajax request
        $.ajax(ajaxOptions);
}

//Event handler that provides information on an Ajax request failure
function ErrorHandler(req, status, error) {

    let errorMessage = `Ajax Request ${status}` + ' Unable to complete Ajax Request';
    console.log(errorMessage);
    console.log(req);
    console.log(status);

}

function GenericSuccess(responseData, textStatus)
{
    console.log(responseData);
    console.log(textStatus);
} 