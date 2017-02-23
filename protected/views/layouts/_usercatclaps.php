<div class="roundbox headertext greenbox" id="box3">
<?php 
    $cat = Category::getCategoryText($this->layoutParams['userCatClaps'][0]['parent']);
    $catUrl = getCategoryUrl($this->layoutParams['userCatClaps'][0]['parent'], $cat, $this->layoutParams['userCatClaps'][0]['username']);
    $author = $this->layoutParams['userCatClaps'][0]['name'];
    $authorUrl = Yii::app()->request->getBaseUrl() . "/" . $this->layoutParams['userCatClaps'][0]['username']; 
    printf("<p>Recent Claps<br>in <a href='%s'>%s</a><br>by <a href='%s'>%s</a></p>",$catUrl,$cat,$authorUrl,$author);
    foreach ($this->layoutParams['userCatClaps'] as $userCatClap) {
?>
<div class="actionbox1">
<?php 
  $permaLink = formPermaLink($userCatClap['id'], $userCatClap['title']);
  printf("<a href='%s'>%s</a>",$permaLink, $userCatClap['title']);
?> 
</div>
<?php } ?>
</div>
