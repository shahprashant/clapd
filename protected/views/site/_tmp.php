<div class="commentcontent">
<br>
<b>Comments: </b><br>
<?php if (isset($comments) && count($comments > 0)) { 
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

<div class="commentcontent">
<br>
<b>Comments: </b><br>
<?php if (isset($comments) && count($comments > 0)) { 
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

<div class="form">
<?php 
$form=$this->beginWidget('CActiveForm', array(
	'id'=>'comment-form',
	'enableAjaxValidation'=>false,
	'action' => 'site/comments',
)); 
 ?>


	<?php echo $form->errorSummary($model); ?>

	<br>
	<div class="comment-row">
	    <?php  
	     $commentUrl = Yii::app()->request->getBaseUrl() . "/?r=site/comments";
	     $ajaxArray = array(
	     		 "type" => "POST",
	     		 "dataType" => "html",
	     		 "success" => "function(html) {
	     				$('#$lastcommentdivid').before(html);
	     			}",
	     );
	     $htmlArray = array(
	     		"id" => "com-" . $_GET['clap'],
	     		"style" => "margin-bottom:5px;"
	     		);
	     
		 echo $form->textArea($model,'comment',array('rows'=>1, 'cols'=>50)); 		
		 echo $form->error($model,'comment'); 
		 //echo CHtml::submitButton($model->isNewRecord ? 'Comment' : 'Save'); 
		 echo $form->hiddenField($model,'clapId', array('value' => $_GET['clap']));
		 echo CHtml::ajaxSubmitButton('Comment', $commentUrl, $ajaxArray, $htmlArray);
		 ?>
	</div>


<?php $this->endWidget(); ?>

</div><!-- form -->

</div><!-- commentcontent -->

</div><!-- commentcontent -->
