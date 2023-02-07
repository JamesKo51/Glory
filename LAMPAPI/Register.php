
<?php
$inData = getRequestInfo();

$id = 0;
$login = "";
$password = "";
$firstName = "";
$lastName = "";
$conn = new mysqli("localhost", "TheBeast", "WeLoveCOP4331", "COP4331");
if( $conn->connect_error )
{
    returnWithError( $conn->connect_error );
}
 else
 {
    $stmt = $conn->prepare("SELECT * FROM Users WHERE Login=?");
	$stmt->bind_param("s", $inData["login"]);
	$stmt->execute();
	$result = $stmt->get_result();

	if( $row = $result->fetch_assoc()  )
	{
		echo "This User Already Exists\nPlease try again at another time";
		http_response_code(403);
	}
	else
	{
		$stmt = $conn->prepare("INSERT INTO Users (Login,Password,FirstName,LastName) VALUES (?,?,?,?)");
        $stmt->bind_param("ssss", $inData["login"], $inData["password"],$inData["firstName"],$inData["lastName"]);
		$stmt->execute();

        echo "Thank you for Registering!\n";

	}
	$stmt->close();
	$conn->close();
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
    $retValue = '{"Id":,"firstName":"","lastName":"","error":"' . $err . '"}';
    sendResultInfoAsJson( $retValue );
}

function returnWithInfo( $firstName, $lastName, $id )
{
    $retValue = '{"Id":' . $id . ',"firstName":"' . $firstName . '","lastName":"' . $lastName . '","error":""}';
    sendResultInfoAsJson( $retValue );
}
?>
