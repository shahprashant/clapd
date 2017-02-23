<?php 
echo '<div class="roundbox headertext greenbox" id="box4">';
echo "<center><b>Welcome " . Yii::app()->user->getName() . "</b></center>";
echo "<br>";
printf("<div class='actionbox1'><a href='%s'>Logout</a></div>", $this->createUrl('site/logout'));
printf("<div class='actionbox1'><a href='%s'>My Claps</a></div>", Yii::app()->request->getBaseUrl(true) . "/" . Yii::app()->user->getName()); 
printf("<div class='actionbox2'><a href='%s'>My Saved Claps</a></div>", Yii::app()->request->getBaseUrl(true) . "?saved=1"); 
printf("<div class='actionbox1'><a href='%s'>My Tags</a></div>", Yii::app()->request->getBaseUrl(true) . "/" . Yii::app()->user->getName() . "?tags=1"); 
printf("<div class='actionbox2'><a href='%s'>Followers</a> , <a href='%s'>Following</a></div>", $this->createUrl('connections/followers'), $this->createUrl('connections/following'));
printf("<div class='actionbox1'><a href='%s'>Find Friends</a></div>", $this->createUrl('connections/findfriends'));
echo "<span id='notificationbox'></span>";
echo '</div>';
?>
