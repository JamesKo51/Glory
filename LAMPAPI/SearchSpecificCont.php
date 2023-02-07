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
		$stmt = $conn->prepare("select * from Contacts where Phone=? and UserID=?;");
		$stmt->bind_param("ss", $phone, $userId)
		$stmt->execute();

		$result = $stmt->get_result();

		while($row = $result->fetch_assoc())
		{
			if( $searchCount > 0 )
			{
				$searchResults .= ",";
			}
			$searchCount++;
			$searchResults .= '{
				"FirstName" : "' . $row["FirstName"]. '",
				"LastName": "' . $row["LastName"].'",
				"Phone": "' . $row["Phone"].'",
				"Email": "' . $row["Email"].'",
				"ID": "' . $row["ID"].'"
			}';
		}

		if( $searchCount == 0 )
		{
			returnWithError( "No Records Found" );
			http_response_code(403);
		}
		else
		{
			returnWithInfo( $searchResults );
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
		$retValue = '{"id":0,"firstName":"","lastName":"","error":"' . $err . '"}';
		sendResultInfoAsJson( $retValue );
	}

	function returnWithInfo( $searchResults )
	{
		$retValue = '{"results":[' . $searchResults . '],"error":""}';
		sendResultInfoAsJson( $retValue );
	}

?>