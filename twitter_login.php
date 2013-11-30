<?php
require_once('./config.php');

if(!empty($_REQUEST['oauth_verifier']) && !empty($_SESSION['oauth_token']) && !empty($_SESSION['oauth_token_secret'])){
        $twitterOauth = new TwitterOAuth($consumeKey, $consumeSecret, $_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);
        $accessToken = $twitterOauth->getAccessToken($_REQUEST['oauth_verifier']);
	foreach($accessToken as $access) {
		if(stripos($access, 'invalid')) {
			connectTwitter(); 
			break;
		}
	}
        $_SESSION['access_token'] = $accessToken;

	//add a new tweet 
	echo '<form method="post" action="twitter_login.php">New Tweet: <input type="text" name="new">&nbsp;&nbsp;<input type="submit" value="Add"></form>';
	
	if(!empty($_SESSION['new'])) {
		$twitterOauth->post('statuses/update', array('status' => $_SESSION['new']));			
	}
	unset($_SESSION['new']);
	unset($_POST['new']);

	//show the tweets 
	$tweets = $twitterOauth->get('statuses/user_timeline');
	$tweetReplies = array();  
	$newTweets = array(); 
	
	echo '<table>';
	foreach($tweets as $tweet) {
		$tweetString = $tweet->text; 
		$tweetId = $tweet->id; 
		$replies = getReplyList($tweets,$tweetId); 
		echo '<tr><td>'.$tweetString;
		if(!empty($replies)) {
			echo '<br/><table class="reply">';
			foreach($replies as $reply) {
				echo '<tr><td>'.$reply.'</td></tr>';
			}
			echo '</table>';
		}
		echo '</td></tr>';
	}	
	echo '</table><br/><br/>';

}else {
	if(!empty($_POST['new'])) {
        	$_SESSION['new'] = $_POST['new'];
	}
	
	connectTwitter(); 
}

require_once('./footer.inc');
?>

