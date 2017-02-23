<?php include_once "_header.php"; ?>
<?php $publicPages = array('UserClapsForCategory', 'UserClaps','UserClapsByCategory','UserClapsByScore','UserHashtagClaps'); ?>
<body>

<div class="container" id="page">

<!-- box0 -->
<div class="prevbox"></div>
<!--  end box0 -->
<!-- box1 -->
<?php if ((!Yii::app()->user->isGuest) || (in_array($this->page, $publicPages)) ) {
	if (isset($this->layoutParams['avatarUrl']) ) {		
		printf("<div class='roundbox greenbox' id='box1'>");
		printf("<div class='profileimgbox'><img class='profileimg' src='%s'></div>", $this->layoutParams['avatarUrl']);
		printf("<div class='profileUserFullName'><a href='%s'>%s</a></div>", $this->layoutParams['profileUrl'], $this->layoutParams['author']);
		printf("</div>");
    } elseif (isset($this->user)) {
		printf("<div class='roundbox' id='box1'>");
		//printf("<div class='profileimgbox'><img class='profileimg' src='%s'></div>", "https://graph.facebook.com/" . $this->user['fbUsername'] . "/picture?type=normal") ;
		printf("<div class='profileimgbox'><img class='profileimg' src='%s'></div>", $this->layoutParams['avatarUrl']);
		printf("<div class='profileUserFullName'>%s</div>", $this->layoutParams['author']);
		printf("</div>");

	} else {
		printf("<div class='roundbox' id='box1'></div>");
	}
	?>

<?php } else { ?>
<?php include_once "_logobox.php";?>
<?php } ?>
<!-- end box1 -->

<!-- box2 -->
<?php if ((!Yii::app()->user->isGuest) || (in_array($this->page, $publicPages)) ) { ?>
<?php include_once "_pageinfo.php";?>
<?php } else { ?>
<div class="roundbox" id="box2">
	<div class="promosubbox" style="font-size:24px;"><br><br>Positive
	</div>
</div>
<?php } ?>
<!-- end box2 -->

<!-- box3 -->

<?php if (!Yii::app()->user->isGuest) { ?>
<?php include_once "_actionsbox.php"; ?>
<?php } elseif (in_array($this->page, $publicPages)) { ?>
<?php include_once "_promobox.php"; ?>
<?php } else { ?>
<div class="roundbox" id="box3">
<div class="promosubbox" style="font-size:24px;"><br><br>Useful</div>
</div>
<?php } ?>
<!-- end box3 -->



<!-- box4 -->

<?php
if (!Yii::app()->user->isGuest) {
include_once "_settingsbox.php";
} elseif (in_array($this->page, $publicPages)) { 
include_once "_loginbox.php"; 
} else { ?>
<div class='roundbox' id='box4'>
	<div class="promosubbox" style="font-size:24px;"><br><br>Fun

	</div>
</div>
<?php } ?>
<!-- end box4 -->




<!-- box5 -->
<?php
if (!Yii::app()->user->isGuest) {
include_once "_logobox.php";
} elseif (in_array($this->page, $publicPages)) { 
include_once "_logobox.php"; 
} else {
include_once "_loginbox.php";
}
?>
<!-- end box5 -->

<!-- box6 -->
<div class="nextbox"></div>
<!--  end box6 -->

<br clear="all">


<?php echo $content; ?>
<br clear="all">
<?php include_once "_footer.php"; ?>

</div>

<!-- page -->

<?php include_once "_script.php";?>
</body>
</html>
