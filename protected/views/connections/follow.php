<?php
include_once 'jsincludes.php';

$boxcount=0;
$boxidcount=6;
foreach ($followArray as $follow) {
	if ($boxcount == 0) {
		echo '<div class="prevbox"></div>';
	}
	$boxid = "box" . $boxidcount;
	printf("<div class='roundbox' id='%s'>", $boxid);
	$userAvatarUrl = getProfileImageUrl($follow['avatar'], "sqr", $follow['username']);		
	printf("<div class='profileimgbox'><img class='profileimg' src='%s'></div>", $userAvatarUrl);
	printf("<div class='userFullName'>%s</div>",$follow['name']);
	if ($this->page == "Followers") {
		$blockUserUrl = $this->createUrl("connections/blockuser");
		printf("<div class='blockuser' onclick='blockUser(\"%s\",\"%s\",\"%s\",\"%s\");'>Block User</div>", $follow['userId'], Yii::app()->user->id, $blockUserUrl, $boxid);
	} elseif ($this->page == "Following") {
		$unfollowUserUrl = $this->createUrl("connections/unfollowuser");
		printf("<div class='blockuser' onclick='unfollowUser(\"%s\",\"%s\",\"%s\",\"%s\");'>Unfollow User</div>", Yii::app()->user->id, $follow['userId'], $unfollowUserUrl, $boxid);
	} 
	echo '</div>';
	$boxcount++;
	$boxidcount++;
	if ($boxcount >= 5) {
		echo '<div class="nextbox"></div>';
		echo '<br clear="all">';
		$boxcount=0;
	} 		
}

// if content does not occupy the whole row, then fill in remaining blank boxes for category
while ($boxcount > 0 && $boxcount < 5) {
	echo '<div class="roundbox">&nbsp;</div>';
	$boxcount++;
}
?>
