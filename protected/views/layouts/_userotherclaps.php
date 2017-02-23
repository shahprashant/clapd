<div class="roundbox headertext greenbox" id="box3">
<?php 
    $author = $this->layoutParams['userOtherClaps'][0]['name'];
    $authorUrl = Yii::app()->request->getBaseUrl() . "/" . $this->layoutParams['userOtherClaps'][0]['username']; 
    printf("<p>Recent Other Claps<br>by <a href='%s'>%s</a></p>",$authorUrl,$author);
    foreach ($this->layoutParams['userOtherClaps'] as $userOtherClap) {
?>
<div class="actionbox1">
<?php 
  $permaLink = formPermaLink($userOtherClap['id'], $userOtherClap['title']);
  printf("<a href='%s'>%s</a>",$permaLink, $userOtherClap['title']);
?> 
</div>
<br>
<?php } ?>
    <?php printf("<p> &nbsp;<b><a href='%s'>View All</a></b></p>",$authorUrl);?>
</div>
