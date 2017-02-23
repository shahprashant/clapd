<?php 
    include_once 'jsincludes.php';
    if ($this->page == "DetailsAjax") {
		printf("<div class='detailsclosebox' id='detailsclosebox%s'></div>", $boxrow);
    }
    if (isset($image) && ($image != "")) {
    	$regImgUrl = getImageUrl($image, "reg", $username, $id);   	
    }    
    
	// clean clap of unwanted highlights 
	if ((isset($highlights)) && (count($highlights) > 0)) {
		if (isset($highlights['refs']) and (count($highlights['refs'] > 0))) {		
			foreach ($highlights['refs'] as $refUrl) {
				$clap = str_replace($refUrl, "", $clap);
			}
		}
		if (isset($highlights['locs']) and (count($highlights['locs'] > 0))) {
			foreach ($highlights['locs'] as $location) {
				$clap = str_replace($location, "", $clap);
			}
		}
	}
    
    
?>
<br clear="all">
<div class="detailsrightbox">
	<?php 
		if (!isset($likes)) {
			$likes = 0;
		}
		if (!isset($usefuls)) {
			$usefuls = 0;
		}
		if (!isset($saves)) {
			$saves = 0;
		}
		$clapScore = $postScore + $feedbackScore;
		$useractionUrl = $this->createUrl("useractions/save");
		//echo "<div class='likebox'>Rating</div><div class='likenumbox'>: <big>$rating</big></div><br clear='all'><br>";
        if (isset($question) && ($question == "1")) {
        } else {
            echo "<div class='ratingbox'><div class='ratingboxtext'>Rating</div><div class='ratingboxnumber'>$rating</div></div>";
        }
        echo "<div class='ratingbox'><div class='ratingboxtext'>Score</div><div class='ratingboxnumber'>$clapScore</div></div>";
        echo "<br clear='all'><br>";
		
		// Like
		echo "<div class='likebox'>";
		if (isset($selfLike)) {
			echo "Like";
		} else {
			echo "<a href='javascript:void(0)' onclick='saveLike(\"$id\",\"$useractionUrl\");'>Like</a>";
		};
		echo "</div>";		 		
		echo "<div class='likenumbox'>: ";
        if ($likes > 0) {
            echo "(<a href='javascript:void(0)' class='modallike'>". $likes . "</a>)";
        }  else {
            echo "($likes)";
        }
        echo "</div><br clear='all'>";
		
		// Useful 
		echo "<div class='likebox'>";
		if (isset($selfUseful)) {
			echo "Useful";
		} else {
			echo "<a href='javascript:void(0)' onclick='saveUseful(\"$id\",\"$useractionUrl\");'>Useful</a>";
		}
		echo "</div>";
		echo "<div class='likenumbox'>: ";
        if ($usefuls > 0) {
            echo "(<a href='javascript:void(0)' class='modaluseful'>". $usefuls . "</a>)";
        }  else {
            echo "($usefuls)";
        }
        echo "</div><br clear='all'>";
		
		// Save
		echo "<div class='likebox'>";
		if (isset($selfSave)) {
			echo "Save";
		} else {
			echo "<a href='javascript:void(0)' onclick='saveFavorite(\"$id\",\"$useractionUrl\");'>Save</a>";
		}
		echo "</div>";
		echo "<div class='likenumbox'>: ";
        if ($saves > 0) {
            echo "(<a href='javascript:void(0)' class='modalsave'>". $saves . "</a>)";
        }  else {
            echo "($saves)";
        }
        echo "</div><br clear='all'>";
		
		echo "<div class='likebox'><br><a href='" . formPermaLink($id,$title) . "'>PermaLink</a></div><br clear='all'>";
		//echo "<br><p>Score: $clapScore</p>";
        if ($this->user['id'] == $userId) {
            echo "<br><br>";
            $form=$this->beginWidget('CActiveForm', array(
            'id'=>'delete-form',
            'enableAjaxValidation'=>false,
            'action' => '/clap/delete',
            'method' => 'post',
            ));
            printf("<input type=hidden name=clapId value='%s'>", $id);
            echo CHtml::submitButton('Delete', array('class' => 'clapdelete'));
            $this->endWidget();
        }
	?>
</div>
<div class="detailsmaincontent">
    
	<?php
	echo "<div class='detailnametitleavatar'>";
	if (isset($clapOwnerAvatarUrl)) {
		echo "<div class='detailavatarimgbox'>";
		printf("<img src='%s' class='detailavatarimg'>", $clapOwnerAvatarUrl);
		echo "</div>";
	}
	echo "<div class='detailnametitle'>";
	echo "<font color=blue><a href='". Yii::app()->request->getBaseUrl() . "/" . $username . "'>" . $name . '</a></font>';
    if (isset($question) && ($question == "1")) {
        echo " requested for Claps on the following:";
    }

    echo '<br>';
	echo "<b>$title</b><br>";
	printf ("<span style='font-size:12px;'>posted in %s on %s </span>", $category, date("j M Y @ G:i", strtotime($createtime)));
	echo "</div>";
	echo "</div>";
	echo "<br clear='all'><br>";
	if (isset($regImgUrl)) {

		echo '<div class="detailimg">';
		echo "<img src='$regImgUrl'>";
		echo '</div><br clear="all">';
	}
	echo "<div class='detailsclap'>";
	
    // make links clickable 
    $clap = makeClickableLinks($clap);
	echo nl2br($clap)  ;
	echo "</div>";

	if ((isset($highlights)) && (count($highlights) > 0)) {
		echo "<br clear='all'>";
		if (isset($highlights['tags'])) {
		
			$tagsArray = $highlights['tags'];
			echo "<div class='tagcontainerbox'>";
			echo "<h3 style='float:right;'>Tags&nbsp;&nbsp;</h3>";
			foreach ($tagsArray as $tag) {
				$tagUrl = Yii::app()->request->getBaseUrl() . "/$username?tag=" . trim($tag,'#');
				echo "<div class='tagconnectorbox'><br><hr size=2></div>";
				echo "<div class='tagbox'><a href='$tagUrl'>" . $tag . "</a></div><br clear='all'>";
			}
			echo "</div>";
		}		
		if (isset($highlights['cheers'])) {
			
			$cheersArray = $highlights['cheers'];
			echo "<div class='cheercontainerbox'>";
			echo "<h3>&nbsp;&nbsp;Cheers</h3>";
			foreach ($cheersArray as $cheer) {
				echo "<div class='cheerconnectorbox'><br><hr size=2></div>";
				echo "<div class='cheerbox'>" . $cheer . "</div><br clear='all'>";
			}
			echo "</div>";
		}

		if (isset($highlights['locs'])) {
			echo "<br clear='all'>";
			echo "<div class='refsclap'>";
			echo "<h3>Location:</h3>";
			$locsArray = $highlights['locs'];
			$location = trim($locsArray[0],'%');
			echo $location;
			echo '</div>';
		}

		if (isset($highlights['refs'])) {
			echo "<br clear='all'>";
			echo "<div class='refsclap'>";
			echo "<h3>Reference URLs:</h3>";
			$refsArray = $highlights['refs'];
		
			foreach ($refsArray as $ref) {
				$ref = trim($ref,'^');
				echo "<a href='$ref'>$ref</a><br>";
			}
			echo '</div>';
		}

	}

    // If its a question 
    if (isset($question) && ($question == "1")) {
        echo "<hr size=1>";
        $replyUrl = Yii::app()->createUrl('clap/post', array('catid' => $categoryId,  'rClap' => $id));
        $responsesUrl = Yii::app()->request->getBaseUrl() . "?saved=1";
        echo "<div class='detailsclap'>";
        printf("<a href='%s'>Respond to Request by posting a Clap</a><br><br>", $replyUrl);
        printf("<a href='%s'>View Responses</a>", $responsesUrl);
        echo "</div>";
    }
	?>
</div>
