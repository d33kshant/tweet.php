<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Homepage</title>
	<link rel="stylesheet" href="./styles/common.css">
	<link rel="stylesheet" href="./styles/index.css">
	<script src="./js/index.js" defer></script>
</head>
<body>
	<div class="tweets-list">
		<nav class="nav-container">
			<a href="./" class="nav-title">Tweets</a>
		</nav>
		<?php 
			require_once "config.php";

			$sql = "SELECT tweets.tweet_id, tweets.ref_tweet, tweets.body, tweets.created_on, users.username FROM tweets INNER JOIN users ON users.user_id = tweets.author WHERE tweets.ref_tweet IS NULL ORDER BY tweets.created_on DESC LIMIT 10";
			if ($result = mysqli_query($link, $sql)) {
				if (mysqli_num_rows($result) > 0) {
					while ($row = mysqli_fetch_array($result)) {
						echo '<div id="tweet-'.$row["tweet_id"].'" class="tweet-container">';
						echo '<p class="tweet-body">'.$row["body"].'</p>';
						echo '<div class="tweet-info">';
						echo '<a href="./search.php?user='.$row["username"].'" class="tweet-user">'.$row["username"].'</a><span style="color:indigo;">tweeted</span> at<span class="tweet-time">'.$row["created_on"].'</span>';
						echo '</div></div>';
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