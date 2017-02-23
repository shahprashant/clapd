<?php include_once "_header.php"; ?>

<body>

<div class="container" id="page">

<!-- left column box -->
<div class="longcolumn">
	<!--  logo box  -->
	<?php 
         include_once "_logobox.php";
         if ((isset($this->layoutParams['userCatClaps'])) && (is_array($this->layoutParams['userCatClaps']))  && (count($this->layoutParams['userCatClaps']) > 0)) { 
	         include_once "_usercatclaps.php"; 
         }
         if ((isset($this->layoutParams['userOtherClaps'])) && (is_array($this->layoutParams['userOtherClaps']))  && (count($this->layoutParams['userOtherClaps']) > 0)) { 
	         include_once "_userotherclaps.php"; 
         }
         if ((!Yii::app()->user->isGuest)) {
	         include_once "_actionsbox.php"; 
         } else {
	         include_once "_promobox.php"; 
	         include_once "_loginbox.php"; 
         }
    ?>
	<!--  end logo box -->
</div>
<!--  left column box -->

<!-- main content box -->
<div class="combobox" style="display:block;">
<?php echo $content;?>
</div>
<!-- end main content box -->


<br clear="all">
<?php include_once "_footer.php"; ?>
</div>
<?php include_once "_script.php";?>
</body>
</html>
