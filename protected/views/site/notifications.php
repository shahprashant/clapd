<?php
include_once 'jsincludes.php';
?>
<div class="prevboxflex"></div>
<div class="halfboxspan">
<h2>Notifications on content you created</h2>
<?php 
	if (count($notificationsArray) > 0) {
		foreach ($notificationsArray as $notification) {
			$notificationText = '';
			$actionDate = date("j M @ G:i", strtotime($notification['createtime']));
			$clapUrl = $this->createUrl("site/details", array('clap' =>  $notification['clapId']));
			switch ($notification['type']) {
				case '0': $notificationText = sprintf("%s commented on <a href='%s'>%s</a> (%s)", $notification['name'], $clapUrl, $notification['title'], $actionDate);
					break;
				case '1': $notificationText = sprintf("%s Liked <a href='%s'>%s</a> (%s)", $notification['name'], $clapUrl, $notification['title'], $actionDate);
					break;
				case '2': $notificationText = sprintf("%s found <a href='%s'>%s</a> to be Useful (%s)", $notification['name'], $clapUrl, $notification['title'], $actionDate);
					break;
				case '3': $notificationText = sprintf("%s Saved <a href='%s'>%s</a> (%s)", $notification['name'], $clapUrl, $notification['title'], $actionDate);
					break;
			}
			if ($notificationText)
				echo "$notificationText<br>";
		}
	} else {
		echo "No new notifications in the past few days.";
	}
?>
</div>
<div class="halfboxspan">
<h2>Notifications on content you liked</h2>
</div>
<div class="nextboxflex"></div>

