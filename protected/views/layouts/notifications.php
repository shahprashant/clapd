<?php include_once "_header.php"; ?>

<body>

<div class="container" id="page">

<!-- box0 -->
<div class="prevbox"></div>
<!--  end box0 -->

<!-- box1 -->
<?php if (!Yii::app()->user->isGuest) { ?>
<div class="roundbox" id="box1" style="background-image:url('/images/pps150.jpg')"></div>
<?php } else { ?>
<?php include_once "_logobox.php";?>
<?php } ?>
<!-- end box1 -->

<!-- box2 -->
<?php if (!Yii::app()->user->isGuest) { ?>
<?php include_once "_pageinfo.php";?>
<?php } else { ?>
<div class="roundbox" id="box2"></div>
<?php } ?>
<!-- end box2 -->

<!-- box3 -->
<div class="roundbox" id="box3">
<div class="actionbox1">
<a href='<?php echo Yii::app()->request->getBaseUrl(); ?>'>Home</a>
</div>
<div class="actionbox2">
<a href='<?php echo Yii::app()->request->getBaseUrl() . "/?r=clap/post"; ?>'>Post a Clap</a>
</div>
<div class="actionbox1">
<a href='<?php echo Yii::app()->request->getBaseUrl() . "/?r=clap/browse"; ?>'>Browse Claps by Category</a>
</div>
</div>
<!-- end box3 -->



<!-- box4 -->

<?php
if (!Yii::app()->user->isGuest) {
include_once "_settingsbox.php";
} else {
include_once "_loginbox.php";
}
?>
<!-- end box4 -->




<!-- box5 -->
<?php
if (!Yii::app()->user->isGuest) {
include_once "_logobox.php";
} else {
echo '<div class="roundbox" id="box5">&nbsp;</div>';
}
?>
<!-- end box5 -->

<!-- box6 -->
<div class="nextbox"></div>
<!--  end box6 -->

<br clear="all">

<div class='fullboxspan'>
<?php echo $content; ?>
</div>
<br clear="all">
<?php include_once "_footer.php"; ?>

</div>

<!-- page -->

<?php include_once "_script.php";?>
</body>
</html>
