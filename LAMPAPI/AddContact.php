<?php
	$inData = getRequestInfo();
	
	$firstName = $inData["firstName"];
  $lastName = $inData["lastName"];
  $phone = $inData["phone"];
  $email = $inData["email"];
	$userId = $inData["userId"];

	$conn = new mysqli("localhost", "TheBeast", "WeLoveCOP4331", "COP4331");
	if ($conn->connect_error) 
	{
		returnWithError( $conn->connect_error );
	} 
	else
	{
        $stmt = $conn->prepare("SELECT * FROM Contacts WHERE (Phone=? AND UserId=?)");
        $stmt->bind_param("ss", $inData["phone"],$inData["userId"]);
        $stmt->execute();

        $result = $stmt->get_result();
	if( $row = $result->fetch_assoc() )
	{
		echo "This Contact Already Exists\nPlease try again at another time";
	}
	else
	{
        $stmt = $conn->prepare("INSERT into Contacts (UserId,FirstName,LastName,Phone,Email) VALUES(?,?,?,?,?)");
		$stmt->bind_param("sssss", $userId, $firstName, $lastName, $phone, $email);
		$stmt->execute();

        echo "Thank you for Registering!\n";

	}
		$stmt->close();
		$conn->close();
		returnWithError("");
	}

	function getRequestInfo()
	{
		return json_decode(file_get_contents('php://input'), true);
	}

	function sendResultInfoAsJson( $obj )
	{
		header('Content-type: application/json');
		echo $obj;
	}
	
	function returnWithError( $err )
	{
		$retValue = '{"error":"' . $err . '"}';
		sendResultInfoAsJson( $retValue );
	}
	
?>