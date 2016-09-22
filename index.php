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
	} else {
		$access_token = $_SESSION['access_token'];
		$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);
		$user = $connection->get("account/verify_credentials");
		$_SESSION['connection']=$connection;
		$_SESSION['user']=$user;
		$_SESSION['usernm']=$user->name;
		
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
<script>
		function showtweet(msg)
			{
				//alert("hhahahah");
				xmlhttp=new XMLHttpRequest();
				xmlhttp.open("GET","slider.php?st="+msg,true);
				xmlhttp.send();
				xmlhttp.onreadystatechange=function()
				{
					if (xmlhttp.status=200 && xmlhttp.readyState==4)
					{
						document.getElementById("fslider").innerHTML=xmlhttp.responseText;
					}
				}
			}
			
			
			
		</script>
</head>
<body style="text-align:center;background-image:url(dist/img/2.jpg));	">
	 <!-- Navigation -->
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#">Twi Timeline</a>
				 
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
			<a class="navbar-brand" href="#" style="float:right">
				<?php 
					$user=$_SESSION['usernm'];
					echo "Welcome: " . $user;	
				?> 
			</a>
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                   
                    <li>
                        <a href="logout.php" class="btn btn-default">Logout</a>
                    </li>
                   
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>
	<br>
	<br>
	<!-- Full Page Image Background Carousel Header -->
		<?php require_once 'slider.php'; ?>
		
		<!-- Page Content -->
    <div class="container" style="margin-top:-8%">
	
	<!-- Follower Content -->
	    <div class="row">
			<div class="col-sm-3"></div>
    		<div class="col-sm-6 a follower" >FOLLOWER</div>
    		
  		</div>
		<div class="row">
			<div class="col-lg-3"></div>
            <div class="col-lg-6 well">
               
				<?php
					$follower = $connection->get('followers/list', ['count' => 10]);
					$totalfollower[] = $follower;
					$no = 1;
					echo "<ul class='list-inline'>";
					foreach ($totalfollower as $p) {
						foreach ($p->users as $id) {
							echo "<li>" . $no . ':' . $id->name . "</li><br>";
							$no++;
						}
					}
					echo "</ul>";
				?>
            </div>
        </div>
	<!-- Search Content -->
		
		
		
		<script>
		function Searchmsg(msg)
		{
			//alert(msg);
			xmlhttp=new XMLHttpRequest();
			xmlhttp.open("GET","follo.php?S="+msg,true);
			xmlhttp.send();
			xmlhttp.onreadystatechange=function()
			{
				if (xmlhttp.status=200 && xmlhttp.readyState==4)
				{
					document.getElementById("follower").innerHTML=xmlhttp.responseText;
				}
			}
		}
		</script>
		<div class="row">
			<div class="col-sm-3"></div>
    		<div class="col-sm-6 a follower" >SEARCH FOLLOWER</div>
  		</div>
		<div class="row">
			<div class="col-lg-3"></div>
           <div class="col-lg-6 well search_box">
			<center>
				<form>
                 <input type="text" class="form-control" onKeyUp="Searchmsg(this.value)">
				</form>
			</center>
           
			<div class="col-lg-3"></div>
           <div class="col-lg-6 ">
			<center>
               <div id="follower"></div>
			</center>
           </div>
		 </div>
		 </div>
		
		 
	<!-- Download -->
		<script>
		function download()
			{
				
				xmlhttp=new XMLHttpRequest();
				xmlhttp.open("GET","pdf.php",true);
				xmlhttp.send();
				xmlhttp.onreadystatechange=function()
				{
					if (xmlhttp.status=200 && xmlhttp.readyState==4)
					{
						  var pdf = new jsPDF();
       					 pdf.text(30, 30,xmlhttp.responseText);
       					 pdf.text(30, 30,' ');
							pdf.save('tweet.pdf');
					
						 
					}
				}
			</script>


	    <div class="row">
			<div class="col-sm-3"></div>
    		<div class="col-sm-6 a follower">
				<form action="" method="get">
					<input type="button" class="btn btn-default" value="Download Tweets" name="d" onClick="download()"/> 
				</form>
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
	<script src="dist/js/jquery.js"></script>
    <script src="dist/js/bootstrap.min.js"></script>

<?php }?>
</body>
</html>
