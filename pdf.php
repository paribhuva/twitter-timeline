<?php
		session_start();
	require 'autoload.php';
	
	use Abraham\TwitterOAuth\TwitterOAuth;
	
	define('CONSUMER_KEY', ''); 
	define('CONSUMER_SECRET', '');
	define('OAUTH_CALLBACK', ''); 
	if (!isset($_SESSION['access_token'])) {
	
		$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);
		$request_token = $connection->oauth('oauth/request_token', array('oauth_callback' => OAUTH_CALLBACK));
		$_SESSION['oauth_token'] = $request_token['oauth_token'];
		$_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];
		$url = $connection->url('oauth/authorize', array('oauth_token' => $request_token['oauth_token']));
		//echo $url;
		header('Location:'. $url ); 
	} else {
		$access_token = $_SESSION['access_token'];
		$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);
		$user = $connection->get("account/verify_credentials");
		$tweets = $connection->get('statuses/user_timeline', ['count' => 3200,'include_rts' => false, 'exclude_replies' => true]);
		$totalTweets[] = $tweets;
		$page = 0;
			for($count = 3200; $count < 3200; $count += 3200)
				{
					$max = count($totalTweets[$page]) - 1;
					$tweets = $connection->get('statuses/user_timeline', ['count' => 3200,'include_rts' => false, 'exclude_replies' => true, 'max_id' => $totalTweets[$page][$max]->id_str ]);
					$totalTweets[] = $tweets;
					$page += 1;
				}
				$start = 1;
	foreach ($totalTweets as $page)
	{
		foreach ($page as $key)
		{
		
			echo $start . ':' . $key->text ;
			$start++;
		}
	}
	
	}
	?>
