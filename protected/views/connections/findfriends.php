<?php
include_once 'jsincludes.php';
?>

<?php if ($fbFriends) { ?>
<div class='form'>
<?php

printf("<form method=post action='%s'>", $this->createUrl("connections/findfriends"));
$boxcount=0;
$boxidcount=6;
foreach ($fbFriends as $fbFriend) {
	if ($boxcount == 0) {
		echo '<div class="prevbox"></div>';
	    $boxid = "box" . $boxidcount;
        printf("<div class='roundbox' id='%s'>", $boxid);
        printf("<input type=submit name='follow' value='Follow' id='clapsubmit'>");
        printf("</div>");
        $boxidcount++;
        $boxcount++;
	}
	$boxid = "box" . $boxidcount;
	printf("<div class='roundbox' id='%s'>", $boxid);
    
	$userAvatarUrl = getProfileImageUrl($fbFriend['avatar'], "sqr", $fbFriend['username']);		
	printf("<div class='profileimgbox'><img class='profileimg' src='%s'></div>", $userAvatarUrl);
	printf("<div class='userFullName'>%s</div>",$fbFriend['name']);
    printf("<div class='followuser'><input type=checkbox name=followUser[] value='%s' checked></div>", $fbFriend['id']);
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

echo "</form>";
?>

</div>
<?php } else { ?>
<br><br>
<center>
<h2>No Facebook friends found on Clapd. Check back later. </h2>
</center>
<?php } ?>
