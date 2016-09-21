<?php

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

					
