<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Homepage</title>
</head>
<body>
	<div class="tweets-list">
		<?php 
			require_once "config.php";

			$sql = "SELECT * FROM tweets";
			if ($result = mysqli_query($link, $sql)) {
				if (mysqli_num_rows($result) > 0) {
					while ($row = mysqli_fetch_array($result)) {
						echo $row["body"]."<br />";
					}
				} else {
					echo "No tweets found.";
				}
				mysqli_free_result($result);
			}
			mysqli_close($link);
		?>
	</div>
</body>
</html>