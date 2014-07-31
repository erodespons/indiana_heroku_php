<?php 

 

//create your task body from your post variables

$source = $_POST['source'];



if ($source == "design-integration-services") { 

    // form located at http://iuk.edu/admin-services/it/classroom-technology-services/forms/design-integration-services.shtml

    

    //This will be the task title

    $task_title = "Project request from " . $_POST['fullname']; // $_POST['first_form_value'];

    

    //Fields that show up in the email

    $task_body = 	"Email: " . $_POST['email'] . "<br/>" . 

		"Name: " . $_POST['fullname'] . "<br/>" . 

		"Phone: " . $_POST['phone'] . "<br/>" . 

		"Contact name: " . $_POST['contactname'] . "<br/>" . 

		"Contact email: " . $_POST['contactemail'] . "<br/>" . 

		"Contact phone: " . $_POST['contactphone'] . "<br/>" . 

		"Account: " . $_POST['account'] . "<br/>" . 

		"Campus: " . $_POST['campus'] . "<br/>" . 

		"Building: " . $_POST['building'] . "<br/>" . 

		"Room no: " . $_POST['roomno'] . "<br/>" . 

		"Room type: " . $_POST['roomtype'] . "<br/>" . 

		"Description: " . $_POST['description'] . "<br/>"

		;

    

	if ($_POST['campus'] == "IUK") {

		//You can get the project id by doing a API call. 

		$project_id =  866576; // ex: https://next.redbooth.com/a/#!/projects/757353/tasks

		//You can get the task list id by doing a API call. 

		$task_list_id =  1543672; // ex: https://next.redbooth.com/a/#!/projects/757353/task-lists/1855951

	} elseif ($_POST['campus'] == "IUB") {

		$project_id =  1234511;

		$task_list_id =  2375775;

	} elseif ($_POST['campus'] == "IUPUI") {

		$project_id =  1234506;

		$task_list_id =  2375763;

	}

	    

	

	 

	

		

} elseif ($source == "web") {

	$task_title = $_POST['dept'] . ": " . $_POST['shortDesc'];

    $task_body = 	$_POST['email'] . "<br/><br/>" . 

		"Description: " . $_POST['issueDesc'] . "<br/><br/>" . 

        "URL: " . $_POST['url'] . "<br/>" . 

        "Name: " . $_POST['fullname'] . "<br/>" . 

        "Phone: " . $_POST['phone'] . "<br/>" . 

		"Due date: " . $_POST['dueDate'] . "<br/>"

		;

	$project_id = 1011902; // uits web team project

	$task_list_id = 1842370; // incoming tickets task list

}





// Clean the task body so we ensure no line breaks

function parse($task_body) {
    // Damn pesky carriage returns...
    $task_body = str_replace("\r\n", "\n", $task_body);
    $task_body = str_replace("\r", "\n", $task_body);

    // JSON requires new line characters be escaped
    $task_body = str_replace("\n", "\\n", $task_body);
    return $task_body;
}



//Get access_token from the database which you have already stored.

//Write your SQL here.

//Or for now just hard code it. 

//$access_token = '6Y70J1yFdvmKdZZxTPbFLrZilV0Vqw9PQNz4vUGJ'; //token for venturae@iu.edu

$access_token = 'KJ4Y0Ssjt5xatkXPlIpKfPGsF1HbySoFLzAwzz2F'; //token for redtask@iuk.edu

 

//The date the task is due, add it to the data_string (it's currently not being used)

$due_date = '2014-04-26'; // 2014-04-26

 

//This is the payload

$data_string = '{"name":"'.$task_title.'","project_id":'.$project_id.',"task_list_id":'.$task_list_id.',"comments_attributes":[{"body":"'.$task_body.'"}],"type":"Task","is_private":false,"urgent":false}';   



// Comment this out if you don't want to see the JSON that's being returned

// echo $data_string;

 

//Endpoint to create task

$endpoint = 'https://redbooth.com/api/2/tasks';                                                                             

 

//Start Curl session to create task 

$ch = curl_init($endpoint);

curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     

curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                                                                  

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 

curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);                                                                     

curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: OAuth ' . $access_token,'Content-Type: application/json', 'Content-Length: ' . strlen($data_string)));                                                                                                                   

$result = curl_exec($ch);

curl_close ($ch);

 

// check for empty data

if ($result === NULL) {

	//redirect to URL with failure status

	//header("Location: https://something.iu.edu/fail.html");	

	echo "Your request has failed. Please go back and resubmit.";	

} else {

	//redirect to URL with success status

	//header("Location: https://something.iu.edu/success.html");	

	echo "Your request has been created successfully.";



	//send email confirmation to user now

}

	 

 

