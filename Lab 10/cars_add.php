<!DOCTYPE html>
<html lang="">
<head>
	<meta charset="utf-8">
	<meta name="description" content="Creating web application lab 10">
	<meta name="keywords" content="PHP, MySQL">
	<title>Adding cars to MySQL</title>
</head>
<body>
	<?php
		function input_sanitization($data){
			$data = trim($data);				
			$data = stripslashes($data);		
			$data = htmlspecialchars($data);	
			return $data;
		}
		if (isset($_POST["carmake"])){		
			$make = input_sanitization($_POST["carmake"]);
			$model = input_sanitization($_POST["carmodel"]);
			$price = input_sanitization($_POST["price"]);
			$yom = input_sanitization($_POST["yom"]);

			require_once("settings.php");
			$conn = @mysqli_connect($host,$user,$pwd,$sql_db);	
			$sql_table = "cars";	
			$query = "insert into $sql_table (make, model, price, yom) values ($make, '$model', $price, $yom);";	
			$result = mysqli_query($conn, $query);	
			if (!$result){	
				echo "<p>Something is wrong with ", $query, "</p>";
			}
			else{		
				echo "<p>Successfully added New Car record</p>";
			}
			mysqli_close($conn);
		}
		else{
			header("location: addcar.html");
		}
	?>
</body>
</html>