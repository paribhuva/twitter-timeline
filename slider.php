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
<body style="text-align:center;background-image:url(dist/img/2.jpg));	">
<?php
	if(isset($_GET['st']))
  	{
		$showtweet=$_GET['st'];
		if($showtweet != NULL)
		  {
				
				// getting recent tweeets by user 
				$tweets = $connection->get('statuses/user_timeline', ['count' => 10,'include_rts' => false, 'exclude_replies' => true ,'screen_name' => $showtweet]);
				$ttweets[] = $tweets;
				$p = 0;
			
				for ($i = 10; $i < 10; $i += 10)
				{ 
					$max = count($ttweets[$page]) - 1;
					$tweets = $connection->get('statuses/user_timeline', ['count' => 10,'include_rts' => false, 'exclude_replies' => true, 'max_id' => $ttweets[$p][$maximum]->id_str ,'screen_name' => $showtweet ]);
					$ttweets[] = $tweets;
					$p += 1;
				}
			}
	}
	else if(isset($_GET['d']))
	{
		$tweets = $connection->get('statuses/user_timeline', ['count' => 3200,'include_rts' => false, 'exclude_replies' => true]);
		$ttweets[] = $tweets;
		$p = 0;
			for($i = 3200; $i < 3200; $i += 3200)
				{
					$maximum = count($ttweets[$p]) - 1;
					$tweets = $connection->get('statuses/user_timeline', ['count' => 3200,'include_rts' => false, 'exclude_replies' => true, 'max_id' => $ttweets[$p][$maximum]->id_str ]);
					$ttweets[] = $tweets;
					$p += 1;
				}
	}
	else
	{
		// getting recent tweeets by user itself
		$tweets = $connection->get('statuses/user_timeline', ['count' => 10,'include_rts' => false, 'exclude_replies' => true]);
		$ttweets[] = $tweets;
		$p = 0;
	
		for ($i = 10; $i < 10; $it += 10)
		{ 
			$maximum = count($totalTweets[$p]) - 1;
			$tweets = $connection->get('statuses/user_timeline', ['count' => 10,'include_rts' => false, 'exclude_replies' => true, 'max_id' => $ttweets[$p][$maximum]->id_str ]);
			$ttweets[] = $tweets;
			$p += 1;
		}
	
	}
?>
	<div class="my-slider">
	<ul>
<?php
	// printing recent tweets on screen
	$no = 1;
	foreach ($ttweets as $p)
	{
		foreach ($p as $id)
		{
		
			echo "<li class=a id='fslider'>" . $no . ':' . $id->text . "</li>";
			$no++;
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
