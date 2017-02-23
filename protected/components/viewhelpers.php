<?php
function getClapContentHtml($clapRow, $params) {

	$flipContent = "";
	$likes = 0; $usefuls = 0; $saves = 0;
	
	if (isset($clapRow['username'])) {
		$username = $clapRow['username'];
	}
	if (isset($clapRow['name'])) {
		$author = $clapRow['name'];
	}
	$title = $clapRow['title'];
	$rating = $clapRow['rating'];
	$clap = $clapRow['clap'];
	$title = $clapRow['title'];
	$clap = $clapRow['clap'];
	$clapId = $clapRow['id'];
	$clapScore = $clapRow['postScore'] + $clapRow['feedbackScore'];
	$question = $clapRow['question'];
	
	
	if (isset($clapRow['category'])) {
		$category = $clapRow['category'];
	}
	if (isset($clapRow['catid'])) {
		$catid = $clapRow['catid'];
	}
	if (isset($clapRow['likes'])) {
		$likes = $clapRow['likes'];
	}
	if (isset($clapRow['usefuls'])) {
		$usefuls = $clapRow['usefuls'];
	}
	if (isset($clapRow['saves'])) {
		$saves = $clapRow['saves'];
	}

	if (isset($clapRow['image']) && ($clapRow['image'] != "")) {
		$thumbImgUrl = getImageUrl($clapRow['image'], "thb", $clapRow['username'], $clapRow['id']);
	}

	// clean clap of unwanted highlights 
	$highlights = getClapHighlights($clap);
	if (isset($highlights['refs']) and (count($highlights['refs'] > 0))) {		
		foreach ($highlights['refs'] as $refUrl) {
			$clap = str_replace($refUrl, "", $clap);
		}
	}
	if (isset($highlights['locs']) and (count($highlights['locs'] > 0))) {
		foreach ($highlights['locs'] as $location) {
			$newLocation = str_replace('%',"", $location);
			$clap = str_replace($location, "Location: $newLocation", $clap);
		}
	}

	
	//$refId = $params['refId'];
	$boxid = $params['boxid'];
	$boxrow = $params['boxrow'];
	if (isset($params['page'])) {
		$page = $params['page'];
	}

	
	$created = date("M j Y @ g:i a", strtotime($clapRow['createtime']));
	
	// form ajax Urls
    $detailsUrl = Yii::app()->createUrl('site/details', array('clap' => $clapId, boxrow => $boxrow, 'ajax' => 1));
    $commentsUrl = Yii::app()->createUrl('site/comments', array('clap' => $clapId));

    $useractionUrl = Yii::app()->createUrl('useractions/save');

	switch ($rating) {
		case '3':
			$ratingimg = 'triangle15.png';
			break;
		case '4':
			$ratingimg = 'square15.png';
			break;
		case '5':
			$ratingimg = 'pentagon15.png';
			break;
	}
	
	// Content for flip side
	$flipContent .= "$created<br>";
    $flipContent .= "Category: " . $clapRow['category'] . "<br><br>";
    if ($question != "1") {
	    $flipContent .= "<div class='likebox'>Rating</div><div class='likenumbox'>: $rating</div><br clear='all'>";
    }
	
	// Like
	$flipContent .= "<div class='likebox'>";
	if (isset($clapRow['selfLike'])) {
		$flipContent .= "Like";
	} else {
		$flipContent .= "<a href='javascript:void(0)' onclick='saveLike(\"$clapId\",\"$useractionUrl\");'>Like</a>";
		
	}
	$flipContent .= "</div>";
	//$flipContent .= "<div class='likenumbox'>: (<a href='javascript:void(0)' class='modal'>". $likes . "</a>)</div><br clear='all'>";
	$flipContent .= "<div class='likenumbox'>: (". $likes . ")</div><br clear='all'>";
	
	// Useful 
	$flipContent .= "<div class='likebox'>";
	if (isset($clapRow['selfUseful'])) {
		$flipContent .= "Useful";
	} else {
		$flipContent .= "<a href='javascript:void(0)' onclick='saveUseful(\"$clapId\",\"$useractionUrl\");'>Useful</a>";
	};
	$flipContent .= "</div>";
	$flipContent .= "<div class='likenumbox'>: (". $usefuls . ")</div><br clear='all'>";
	
	// Save
	$flipContent .= "<div class='likebox'>";
	if (isset($clapRow['selfSave'])) {
		$flipContent .= "Save";
	} else {
		$flipContent .= "<a href='javascript:void(0)' onclick='saveFavorite(\"$clapId\",\"$useractionUrl\");'>Save</a>";
	};
	$flipContent .= "</div>";
	$flipContent .= "<div class='likenumbox'>: (" . $saves . ")</div><br clear='all'><br>";
	
	$flipContent .= "Comments: 0<br>";
	
	$flipContent .= "<div class=\"roundboxbottom\">";
	$flipContent .= "<div class=\"bottomsubbox1\"><img src=\"/images/flip.png\" onmouseover='revertFlip(" . '"'. $boxid . '"' . ");'></img></div>";
	$flipContent .= "</div>";
	$flipContent = str_replace("'", "&apos;", $flipContent);
	
	
	$html = "";
	// $html .= "<div class='roundbox' id='" . $boxid . "'>";
	$html .= sprintf("<div class='roundbox cursorhand' id='%s' onclick='fadeBoxes(\"%s\",\"%s\");'>", $boxid, $boxrow, $detailsUrl);
	$html .= "<div class='hackimgbox'></div>";
	$roundboxbottomstyle = '';
	$bottomsubbox2style = '';
	if (isset($thumbImgUrl)) {
		$html .=  "<div class='imgbox'>";
		$html .= "<img class='clapimg' src='$thumbImgUrl'>";
		$html .= "</div>";
		$roundboxbottomstyle = " style='width:106px; border-bottom-right-radius:0px;' ";
		$bottomsubbox2style = " style='margin-left:18px; ' ";
	}


	if (isset($page) && (($page == 'UserClaps') || ($page == 'UserHashtagClaps')) || ($page == 'UserClapsByScore') || ($page == 'SavedClaps')) {
		//$html .= sprintf ("<font color=blue><span id='author_%s'><a href='%s'>%s</a></span></font><br>", $boxid, Yii::app()->request->getBaseUrl() . "?catid=" . $catid, $category);
		$html .= sprintf ("<font color=blue><span id='author_%s'><a href='%s'>%s</a></span></font><br>", $boxid, Yii::app()->request->getBaseUrl() . "/" . $username . "/$catid/$category", $category);

	} elseif (isset($page) && ($page == 'UserClapsByCategory') || ($page == 'UserClapsForCategory')) {
		// do nothing.	
	} else {
		if ($question == '1') {

			$html .= "<div class='requesttext'>Request for Claps</div>";
		}
		$html .= sprintf ("<font color=blue><div id='author_%s' class='authorsubbox'><a href='%s'>%s</a></div></font>", $boxid, Yii::app()->request->getBaseUrl() . "/" . $username, $author);
	}
	$html .= sprintf ("<b><div id='title_%s' class='titlesubbox'>%s</div></b>", $boxid, $title) ;

	// $html .= sprintf ("<div id='clap_%s' class='clapsubbox' onclick='fadeBoxes(\"%s\",%s,\"%s\",\"%s\");'>%s</div>", $boxid, $boxrow,$boxHideStr, $detailsUrl, $commentsUrl, $clap);
	$html .= sprintf ("<div id='clap_%s' class='clapsubbox'>%s</div>", $boxid, $clap);
	if ($question == '1') {
		// $replyUrl = Yii::app()->request->getBaseUrl() . "/?r=clap/post&catid=$catid&rClap=$clapId";
        $replyUrl = Yii::app()->createUrl('clap/post', array('catid' => $catid,  'rClap' => $clapId));
        $responsesUrl = Yii::app()->request->getBaseUrl() . "?clap=$clapId"; 
		$html .= sprintf("<div class='replytext'><a href='%s'>Respond to Request</a>", $replyUrl);
		$html .= sprintf("<br><a href='%s'>View Responses</a></div>", $responsesUrl);
	}

	
	$html .= "<div class='roundboxbottom' $roundboxbottomstyle>";
	$html .= "<div class='bottomsubbox1' title='Clap Score'>$clapScore</div>";
    if ($ratingimg) {
	    $html .= "<div title='Rating:$rating'  class='bottomsubbox2' $bottomsubbox2style><img src='/images/" . $ratingimg . "' alt='Rating:" . $clapScore . "'></div>";
    }
	$html .= sprintf ("<div class='bottomsubbox3'><img src='/images/flip.png' onmouseover='flipBox(\"%s\",\"%s\");'></img></div>", $boxid, addslashes($flipContent));
	//$html .= sprintf ("<div class='bottomsubbox4'><img src='/images/maximize18.png' onclick='fadeBoxes(\"%s\",%s,\"%s\",\"%s\");'></img></div>",$boxrow,$boxHideStr, $detailsUrl, $commentsUrl);

	
	$html .=  "</div>";

	$html .=  "</div>";
	
	return $html;
}

function displayError($errorMsg) 
{
    printf("<span class='error'>%s</span>", $errorMsg);
}

function getCategoryUrl($catId, $catText, $username="") {
    $catUrl = "";
    if ($catId && $catText && !$username) {
        $catText = strtolower($catText);
        $catText = urlencode($catText);
        $catUrl = Yii::app()->request->getBaseUrl() . "/category/$catId/$catText";
    } elseif ($catId && $catText && $username) {
        $catText = strtolower($catText);
        $catText = urlencode($catText);
        $username = strtolower($username);
        $catUrl = Yii::app()->request->getBaseUrl() . "/$username/$catId/$catText";
    }
    return $catUrl;
}
?>
