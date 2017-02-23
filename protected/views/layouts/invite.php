<?php include_once "_header.php"; ?>

<body>

<div class="container" id="page">

<!-- box0 -->
<div class="prevbox"></div>
<!--  end box0 -->

<!-- box1 -->
<?php include_once "_logobox.php";?>
<!-- end box1 -->

<!-- box2 -->
<div class="roundbox displaytable" id="box2">
	<p class='tablecellcenter' style="font-size:24px; color: darkgreen;">Positive</p>
</div>
<!-- end box2 -->

<!-- box3 -->
<div class="roundbox displaytable" id="box2">
	<p class='tablecellcenter' style="font-size:24px; color: darkgreen;">Useful</p>
</div>
<!-- end box3 -->



<!-- box4 -->
<div class="roundbox displaytable" id="box2">
	<p class='tablecellcenter' style="font-size:24px; color: darkgreen;">Fun</p>
</div>
<!-- end box4 -->

<!-- box5 -->
<? 
if (isset($this->page) && ($this->page == 'Home')) {
    include_once "_loginbox.php"; 
} else {
    echo '<div class="roundbox displaytable" id="box5">';
    if (isset($this->pageDesp)) {
        echo "<p class='tablecellcenter'><b>" . $this->pageDesp . "</b></p>";
    }
    echo '</div>';
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
