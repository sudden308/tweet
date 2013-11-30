<?php
ini_set('display_errors', 'on');
require_once('./twitteroauth-master/twitteroauth/twitteroauth.php');
session_start();
$consumeKey = 'ahUH2KQoiygJV6Xzsy9dZg';
$consumeSecret = 'btxZ0wakaAgk1iboKaqlr8dLlqeJU1MloOBU4rSBoHY';
$ip = '192.168.217.182';

function getReplyList($tweets,$id)
{
        $replies = array();

        foreach($tweets as $tweet)
        {
                $inReplyToStatusId = $tweet->in_reply_to_status_id;
                if(!empty($inReplyToStatusId) && $inReplyToStatusId == $id) {
                        $replies[] = $tweet->text;
                }else {
                        continue;
                }
        }

        return $replies;
}

function connectTwitter() 
{
	global $consumeKey, $consumeSecret, $ip; 

	$twitterOauth  = new TwitterOAuth($consumeKey, $consumeSecret);
        $requestToken = $twitterOauth->getRequestToken('http://'.$ip.'/tweet/twitter_login.php');
        $_SESSION['oauth_token'] = $requestToken['oauth_token'];
        $_SESSION['oauth_token_secret'] = $requestToken['oauth_token_secret'];
        if($twitterOauth->http_code=='200'){
                $url = $twitterOauth->getAuthorizeURL($requestToken['oauth_token']);
                header('Location: '. $url);
        }else {
                exit('Sorry, there is a problem when authorised by twitter, please contact sudden308@gmail.com.');
        }
}

?>
<DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<style>
body 
{
font-size: 14px; 
font-family: arial, serif;
font-weight: bold; 
}

td
  {
	padding: 10px; 
	background-color: #40E0D0;
  }
.reply{
}

.reply td {
	padding-left: 30px; 
	font-size: 12px; 
	font-style:italic; 
	font-weight: bold; 
	border: 0; 
}
</style>
</head>
<body>

