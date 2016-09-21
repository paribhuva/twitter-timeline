<?php
	session_start();
	require 'autoload.php';
	
	use Abraham\TwitterOAuth\TwitterOAuth;
	
	define('CONSUMER_KEY', ''); //Enter your consume key
	define('CONSUMER_SECRET', ''); //Enter your consume secret
	define('OAUTH_CALLBACK', ''); // Enter call  backurl
	if (!isset($_SESSION['access_token'])) {
	
		$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);
		$request_token = $connection->oauth('oauth/request_token', array('oauth_callback' => OAUTH_CALLBACK));
		$_SESSION['oauth_token'] = $request_token['oauth_token'];
		$_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];
		$url = $connection->url('oauth/authorize', array('oauth_token' => $request_token['oauth_token']));
		//echo $url;
		header('Location:'. $url ); 
	} else
		{
			$access_token = $_SESSION['access_token'];
			$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);
			$user = $connection->get("account/verify_credentials");

		$d=$_GET['S'];
		
		$follower = $connection->get('followers/list', ['count' => 100]);
		$totalfollower[] = $follower;
		$no = 1;
			echo "<ul class='list-inline'>";
			foreach ($totalfollower as $p)
			{
				foreach ($p->users as $id) 
				{
				if(stripos($id->name,$d) !== false)
					{
?>
						<li><input type="button" class="btn btn-default" value="<?php echo $id->name ?>" onclick="showtweet('<?php echo $id->screen_name ?>')"/></li><br />
<?php
					}
					$no++;
				}
			}
			echo "</ul>";
	}	
?>

					
