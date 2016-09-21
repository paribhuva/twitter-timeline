<?php 
	session_start();
	require 'autoload.php';
	
	use Abraham\TwitterOAuth\TwitterOAuth;
	
	define('CONSUMER_KEY', '69eMzTcTMycQNop3gFL5Gqcl9'); 
	define('CONSUMER_SECRET', 'kNrw00ssqCnB7cHG8IDymdT8tdepDyMI6YCYs6ZZE6iOKCicZG');
	define('OAUTH_CALLBACK', 'http://jobcall.in/callback.php'); 
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
?>
<html>
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Twi-TiMeline</title>
	<link href="dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="dist/css/full-slider.css" rel="stylesheet">
	<link href='https://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>
	<link href="http://cdnjs.cloudflare.com/ajax/libs/normalize/3.0.3/normalize.css" rel="stylesheet" type="text/css">
	<link href="dist/css/cardslider.css" rel="stylesheet">
	<link href="dist/css/demo.css" rel="stylesheet">
</head>
<body style="text-align:center;	">
<?php
	if(isset($_GET['st']))
  	{
		$showtweet=$_GET['st'];
		//echo $showtweet;
		if($showtweet != NULL)
		  {
				
				// getting recent tweeets by user 
				$tweets = $connection->get('statuses/user_timeline', ['count' => 10,'include_rts' => false, 'exclude_replies' => true ,'screen_name' => $showtweet]);
				$totalTweets[] = $tweets;
				$page = 0;
			
				for ($count = 10; $count < 10; $count += 10)
				{ 
					$max = count($totalTweets[$page]) - 1;
					$tweets = $connection->get('statuses/user_timeline', ['count' => 10,'include_rts' => false, 'exclude_replies' => true, 'max_id' => $totalTweets[$page][$max]->id_str ,'screen_name' => $showtweet ]);
					$totalTweets[] = $tweets;
					$page += 1;
				}
			}
	}
	else if(isset($_GET['d']))
	{
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
	}
	else
	{
		// getting recent tweeets by user itself
		$tweets = $connection->get('statuses/user_timeline', ['count' => 10,'include_rts' => false, 'exclude_replies' => true]);
		$totalTweets[] = $tweets;
		$page = 0;
	
		for ($count = 10; $count < 10; $count += 10)
		{ 
			$max = count($totalTweets[$page]) - 1;
			$tweets = $connection->get('statuses/user_timeline', ['count' => 10,'include_rts' => false, 'exclude_replies' => true, 'max_id' => $totalTweets[$page][$max]->id_str ]);
			$totalTweets[] = $tweets;
			$page += 1;
		}
	
	}
?>
	<div class="my-slider">
	<ul>
<?php
	// printing recent tweets on screen
	echo "";
	$start = 1;
	foreach ($totalTweets as $page)
	{
		foreach ($page as $key)
		{
		
			echo "<li class=a id='fslider'>" . $start . ':' . $key->text . "</li>";
			$start++;
		}
	}
?>
 	</ul>
	</div>
	<script src="http://code.jquery.com/jquery-2.2.3.min.js"></script> 
	<script src="jquery.event.move.js"></script> 
	<script src="jquery.event.swipe.js"></script> 
	<script src="dist/js/jquery.cardslider.min.js"></script> 
	<script>
				$('.my-slider').cardslider({
					swipe: true,
					dots: false
				});
			</script><script type="text/javascript">
	
	  var _gaq = _gaq || [];
	  _gaq.push(['_setAccount', 'UA-36251023-1']);
	  _gaq.push(['_setDomainName', 'jqueryscript.net']);
	  _gaq.push(['_trackPageview']);
	
	  (function() {
		var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
		ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	  })();
	
	</script>

<?php }?>
</body>
</html>