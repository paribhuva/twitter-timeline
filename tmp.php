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
	} else
		{
			$access_token = $_SESSION['access_token'];
			$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);
			$user = $connection->get("account/verify_credentials");
?>
		<script>
			function showtweet(msg)
			{
				alert(msg);
				xmlhttp=new XMLHttpRequest();
				//xmlhttp.open("GET","index.php?st="+msg,true);
				xmlhttp.send();
			}
		</script>
<?php
		$d=$_GET['S'];
		
		$follower = $connection->get('followers/list', ['count' => 10]);
		$totalfollower[] = $follower;
		$start = 1;
			echo "<ul class='list-inline'>";
			foreach ($totalfollower as $page)
			{
				foreach ($page->users as $key) 
				{
				//$a[]=$key->name;
					if(stripos($key->name,$d) !== false)
					{
?>
						<li><input type="button" class="btn btn-default" value="<?php echo $key->name ?>" onclick="showtweet('<?php echo $key->screen_name ?>')"/></li><br />
<?php
					}
					$start++;
				}
			}
			echo "</ul>";
	}	
?>

					