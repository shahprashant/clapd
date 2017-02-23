<div class="commentcontent">
<br>
<b>Comments: </b><br>
<?php 
//$commentModel = $commentsData['commentModel'];
//if ((isset($commentsData['comments'])) && (count($commentsData['comments']) > 0)) { 
if ((isset($comments)) && (count($comments) > 0)) { 
	foreach ($comments as $commentInfo) {
		$commentAuthor = $commentInfo['name'];
		$comment = $commentInfo['comment'];
		echo "<div class='comment'>";
		
		echo "$commentAuthor: $comment <br>";
		echo "</div>";
		
	}
	
}

$lastcommentdivid = "lastcom-" . $_GET['clap'];
echo "<div id='$lastcommentdivid'></div>";
?>

<?php 
if (Yii::app()->user->isGuest) {
    echo "You need to be logged in to be able to comment."; 
} elseif ($showCommentForm === false) {
        echo "Restricted Commenting for this Clap. You need to follow this user to be able to comment.";
} else { ?>

<div class="form">
<?php 
$form=$this->beginWidget('CActiveForm', array(
	'id'=>'comment-form',
	'enableAjaxValidation'=>false,
	'action' => 'site/comments',
)); 
 ?>

	<?php echo $form->errorSummary($commentModel); ?>

	<br>
	<div id="comment-row">
	    <?php  
	     $commentUrl = $this->createUrl("site/comments");
	     $ajaxArray = array(
	     		 "type" => "POST",
	     		 "dataType" => "html",
	     		 "success" => "function(html) {
	     				$('#$lastcommentdivid').before(html);
	     			}",
	     );
	     $htmlArray = array(
	     		"id" => "commentsubmit",
	     		);
	    
		 //echo $form->textArea($commentModel,'comment',array('rows'=>1, 'cols'=>50));
		 echo $form->textArea($commentModel,'comment');
		 //echo $form->error($commentModel,'comment'); 
		 //echo CHtml::submitButton($commentModel->isNewRecord ? 'Comment' : 'Save'); 
		 echo $form->hiddenField($commentModel,'clapId', array('value' => $_GET['clap']));
		 echo CHtml::ajaxSubmitButton('Comment', $commentUrl, $ajaxArray, $htmlArray);
		 ?>
	</div>


<?php $this->endWidget(); ?>

</div><!-- form -->

<?php } ?>

</div><!-- commentcontent -->
