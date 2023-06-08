<?php
// Initialize the session
session_start();

// If not loggedin redirect to login page
if(!isset($_SESSION["loggedin"]) || !isset($_SESSION["user_id"]) || $_SESSION["loggedin"] === false){
    header("location: login.php");
    exit;
}

// Include config file
require_once "config.php";

$user_id = $_SESSION["user_id"];
$tweet_id = $_GET["tweet_id"];

if(!isset($tweet_id)) {
	header("location: error.php");
	exit;
}

// checked if already liked
$check_id_liked_sql = "SELECT * FROM likes WHERE tweet_id=".$tweet_id." AND user_id=".$user_id;
// echo $check_id_liked_sql;
if ($result_likes = mysqli_query($link, $check_id_liked_sql)) {
	// Drop the row if exist
	$row_count = mysqli_num_rows($result_likes);
	if ($row_count > 0) {
		// mysqli_free_result($result_likes);
		$drop_like_sql = "DELETE FROM `likes` WHERE user_id=".$user_id." AND tweet_id=".$tweet_id;
		if($drop_like_result = mysqli_query($link, $drop_like_sql)) {
			header("location: tweet.php?id=".$tweet_id);
			exit;
		} else {
			header("location: error.php");
			exit;
		}
	} 
	// Insert row in likes
	else {
		// mysqli_free_result($result_likes);
		$create_like_sql = "INSERT INTO `likes` (`tweet_id`, `user_id`) VALUES ('".$tweet_id."', '".$user_id."')";
		// echo $create_like_sql;
		if($create_like_result = mysqli_query($link, $create_like_sql)) {
			header("location: tweet.php?id=".$tweet_id);
			exit;
		} else {
			header("location: error.php");
			exit;
		}
	}
} else {
	header("location: error.php");
	exit;
}
?>