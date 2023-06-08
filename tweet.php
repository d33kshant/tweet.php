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
					}
					
					// Render All Retweets
					$ref_tweet_sql = "SELECT tweets.tweet_id, tweets.ref_tweet, tweets.body, tweets.created_on, users.username, (SELECT COUNT(*) FROM likes WHERE likes.tweet_id = tweets.tweet_id) as like_count  FROM tweets INNER JOIN users ON users.user_id = tweets.author WHERE tweets.ref_tweet = ".$tweet_id." ORDER BY tweets.created_on DESC LIMIT 10";
					if ($result_retweet = mysqli_query($link, $ref_tweet_sql)) {
						if (mysqli_num_rows($result_retweet) > 0) {
							while ($row = mysqli_fetch_array($result_retweet)) {
								echo '<div id="tweet-'.$row["tweet_id"].'" class="tweet-container retweet-container">';
								echo '<div class="tweet-info">';
								echo '<a href="./search.php?user='.$row["username"].'" class="tweet-user">'.$row["username"].'</a><span style="color:mediumseagreen;">replied</span> at<span class="tweet-time">'.$row["created_on"].'</span></div>';
								echo '<p class="tweet-body">'.$row["body"].'</p>';
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