<?php
$inData = getRequestInfo();

$phone = $inData["phone"];
$userId = $inData["userId"];

$conn = new mysqli("localhost", "TheBeast", "WeLoveCOP4331", "COP4331");
if ($conn->connect_error) 
{
	returnWithError( $conn->connect_error );
} 
else
{
	$stmt = $conn->prepare("DELETE from Contacts where Phone=? and UserID=?;");
	$stmt->bind_param("ss", $phone, $userId);
	$stmt->execute();
	echo "Contact Deleted\n";
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
