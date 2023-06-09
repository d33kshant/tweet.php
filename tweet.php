<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	
	// Initialize the session
	session_start();
	
	// Check if the user is already logged in, if yes then redirect him to welcome page
	if(!isset($_SESSION["loggedin"]) || !isset($_SESSION["user_id"]) || $_SESSION["loggedin"] === false){
		header("location: login.php");
		exit;
	}

	// Include config file
	require_once "config.php";

	$ref_tweet;
	$author = $_SESSION["user_id"];
	$body = $_POST["body"];

	if(empty(trim($_POST["body"]))){
        exit;
    }

	if (isset($_POST["ref"])) {
		$ref_tweet = $_POST["ref"];
	}

	$create_tweet_sql = "INSERT INTO `tweets` (`ref_tweet`, `author`, `body`, `created_on`) VALUES (?, ?, ?, current_timestamp())";
	if($stmt = mysqli_prepare($link, $create_tweet_sql)){
		mysqli_stmt_bind_param($stmt, "iis", $ref_tweet, $author, $body);

		// Attempt to execute the prepared statement
		if(mysqli_stmt_execute($stmt)){
			$new_tweet = mysqli_insert_id($link);
			header("location: tweet.php?id=".$new_tweet);
			exit;
		} else{
			echo "Oops! Something went wrong. Please try again later.";
		}
	}
}

?>

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
			$tweet_id = $_GET["id"];
			
			// Render Original Tweet
			$tweet_sql = "SELECT tweets.tweet_id, tweets.ref_tweet, tweets.body, tweets.created_on, users.username, (SELECT COUNT(*) FROM likes WHERE likes.tweet_id = tweets.tweet_id) as like_count FROM tweets INNER JOIN users ON users.user_id = tweets.author WHERE tweets.tweet_id=".$tweet_id;
			if ($result = mysqli_query($link, $tweet_sql)) {
				if (mysqli_num_rows($result) > 0) {
					while ($row = mysqli_fetch_array($result)) {
						echo '<div id="tweet-'.$row["tweet_id"].'" class="tweet-container-og">';
						echo '<div class="tweet-info">';
						echo '<a href="./search.php?user='.$row["username"].'" class="tweet-user">'.$row["username"].'</a>tweeted at<span class="tweet-time">'.$row["created_on"].'</span></div>';
						echo '<p class="tweet-body">'.$row["body"].'</p>';
						echo '<div>';
						echo '<a href="./like.php?tweet_id='.$row["tweet_id"].'">'.$row['like_count'].' Likes </a>';
						echo '</div>';
						echo '</div>';
						
						echo '<div class="tweet-box">';
						echo 	'<form class="tweet-form" action="tweet.php" method="post">';
						echo		'<input style="display:none;" name="ref" value="'.$row["tweet_id"].'" />';
						echo 		'<textarea name="body" id="textarea" cols="30" rows="5" required></textarea>';
						echo 		'<button type="submit">Retweet</button>';
						echo 	'</form>';
						echo '</div>';
					}
					
					// Render All Retweets
					$ref_tweet_sql = "SELECT tweets.tweet_id, tweets.ref_tweet, tweets.body, tweets.created_on, users.username, (SELECT COUNT(*) FROM likes WHERE likes.tweet_id = tweets.tweet_id) as like_count  FROM tweets INNER JOIN users ON users.user_id = tweets.author WHERE tweets.ref_tweet = ".$tweet_id." ORDER BY tweets.created_on DESC LIMIT 10";
					if ($result_retweet = mysqli_query($link, $ref_tweet_sql)) {
						if (mysqli_num_rows($result_retweet) > 0) {
							while ($row = mysqli_fetch_array($result_retweet)) {
								echo '<div class="tweet-container retweet-container">';
								echo '<div class="tweet-info">';
								echo '<a href="./search.php?user='.$row["username"].'" class="tweet-user">'.$row["username"].'</a><span style="color:mediumseagreen;">replied</span> at<span class="tweet-time">'.$row["created_on"].'</span></div>';
								echo '<p id="tweet-'.$row["tweet_id"].'" class="tweet-body">'.$row["body"].'</p>';
								echo '<div>';
								echo '<a href="./like.php?tweet_id='.$row["tweet_id"].'">'.$row['like_count'].' Likes </a>';
								echo '</div>';
								echo '</div>';
							}

							
						} else {
							echo '<div class="empty-tweet-banner">No retweets to show.</div>';
						}
						mysqli_free_result($result_retweet);
					} else {
						echo '<div class="empty-tweet-banner">Failed to read retweets</div>';
					}
				} else {
					echo '<div class="empty-tweet-banner">Tweet with id '.$tweet_id.' not found.</div>';
				}
				mysqli_free_result($result);
				
			}

			mysqli_close($link);
		?>
	</div>
</body>
</html>