<div class="roundbox headertext greenbox" id="box3">
<div class="actionbox1">
<a href='<?php echo Yii::app()->request->getBaseUrl(true); ?>'>Home</a>
</div>
<div class="actionbox2">
<a href='<?php echo $this->createUrl('clap/post'); ?>'>Post a Clap</a>
</div>
<!-- <div class="actionbox1"> <a href='<?php echo $this->createUrl('clap/post', array('question' => '1')); ?>'>Ask for Claps on a Topic</a> </div> -->
<div class="actionbox2">
<a href='<?php echo $this->createUrl('clap/browse'); ?>'>Browse Claps by Category</a>
</div>
<?php 
if ((isset($this->page)) && (($this->page == 'UserClaps') || ($this->page == 'UserClapsByCategory') || ($this->page == 'UserClapsByScore'))) { 
    echo '<div class="actionbox2">';
if ($_GET['user'] != $this->user['username']) {
                    $connectionsModel = new Connections;
                    if ($connectionsModel->isFollowing($this->user['id'], $this->layoutParams['authorId'])) {  
echo "Unfollow";
                    
                    } else {
                        echo "Follow";
                    }

                }
    echo '</div>';
}
?>
</div>
