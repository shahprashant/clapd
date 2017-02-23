<?php

/*
function getBoxesToHide($boxidcount, $numOfBoxes=4) {
	$boxesToHide = array();

	if (($boxidcount-1) % 5 == 0) {
			if ($numOfBoxes == 5) 
				$boxesToHide[] = $boxidcount;
			$boxesToHide[] = $boxidcount+1;
			$boxesToHide[] = $boxidcount+2;
			$boxesToHide[] = $boxidcount+3;
			$boxesToHide[] = $boxidcount+4;		
	}
	if (($boxidcount-2) % 5 == 0) {
		if ($numOfBoxes == 5)
			$boxesToHide[] = $boxidcount-1;
		$boxesToHide[] = $boxidcount; 
		$boxesToHide[] = $boxidcount+1;
		$boxesToHide[] = $boxidcount+2;
		$boxesToHide[] = $boxidcount+3;		
	} 
	elseif (($boxidcount-3) % 5 == 0) {
		if ($numOfBoxes == 5)
			$boxesToHide[] = $boxidcount-2;
		$boxesToHide[] = $boxidcount-1;
		$boxesToHide[] = $boxidcount;
		$boxesToHide[] = $boxidcount+1;
		$boxesToHide[] = $boxidcount+2;
	}
	elseif (($boxidcount-4) % 5 == 0) {
		if ($numOfBoxes == 5)
			$boxesToHide[] = $boxidcount-3;
		$boxesToHide[] = $boxidcount-2;
		$boxesToHide[] = $boxidcount-1;
		$boxesToHide[] = $boxidcount;
		$boxesToHide[] = $boxidcount+1;
	}	
	elseif (($boxidcount-5) % 5 == 0) {
		if ($numOfBoxes == 5)
			$boxesToHide[] = $boxidcount-4;
		$boxesToHide[] = $boxidcount-3;
		$boxesToHide[] = $boxidcount-2;
		$boxesToHide[] = $boxidcount-1;
		$boxesToHide[] = $boxidcount;
	}
	
	$boxHideStr = '';
	$sep = '';
	foreach ($boxesToHide as $boxHideId) {
		$boxHideStr .= $sep . '"' . $boxHideId . '"';
		$sep = ",";
	}
	return $boxHideStr;
} */



function getClapHighlights($clap) {
	$ret = array();
	$cheers = array();
	$tags = array();
	$refs=array();
	$locs=array();
	if ($clap) {
		$token = strtok($clap, " \n\t");
		while ($token !== false) {
			if (strpos($token, "+") === 0) {
				$cheers[] = $token;
			}
			if (strpos($token, "#") === 0) {
				$tags[] = $token;
			}
			if (strpos($token, "^") === 0) {
				$refs[] = $token;
			}
			$token = strtok(" \n\t");

		}
		$token = strtok($clap, "\n");
		while ($token !== false) {
			$tokenLength = strlen($token);
			if ((strpos($token, "%") === 0) && (strrpos($token, "%") === $tokenLength-1) ) {
				$locs[] = $token;
			}
			$token = strtok("\n");
		}
		
		// allow only the first 3 cheers, tags, refs and 1 location 
		if (count($cheers) > 0) {
			$ret['cheers'] = array_slice($cheers, 0, 3, true);
		}
		if (count($tags) > 0) {
			$ret['tags'] = array_slice($tags, 0, 3, true);
		}
		if (count($refs) > 0) {
			$ret['refs'] = array_slice($refs, 0, 3, true);
		}
		if (count($locs) > 0) {
			$ret['locs'] = array_slice($locs, 0, 1, true);
		}
	}
	return $ret;
}

function getClapHashTags($clap) 
{
	$ret = array();
	$tags = array();
	if ($clap) {
		$token = strtok($clap, " \n\t");
		while ($token !== false) {
			if (strpos($token, "#") === 0) {
				$tags[] = $token;
			}
			$token = strtok(" \n\t");
		}
		if (count($tags) > 0) {
			$ret = array_slice($tags, 0, 3, true);
		}
	}
	return $ret;
}

function getPostScore($clap) {
	$score = 0;
	if ($clap) {
		$score += 5; // 5 points for posting a clap
	}
	$highlights = getClapHighlights($clap);
	if (count($highlights) > 0) {
		// 2 points for each cheer, tag, ref, location 
		foreach ($highlights as $key => $dataArray) {
			switch ($key) {
				case "cheers":
					$score += count($dataArray) * 2; 	
					break; 
				case"tags": 
					$score += count($dataArray) * 2;
					break;
				case"refs":
					$score += count($dataArray) * 2;
					break;
				case "locs" :
					$score += count($dataArray) * 2;
					break;
			}
		}
	}
	
	return ($score);
}

function getImageUrl($image, $size, $username, $clapId) 
{
	$imgUrl="";
	if (($image) && ($size) && ($username) && ($clapId)) {
		list($imgName, $imgExtension) = explode(".", $image);
		$imgSizeName = $imgName . "_$size." . $imgExtension;
		$imgUrl = Yii::app()->request->getBaseUrl() . "/userdata/" . $username . "/" . $clapId . "/" . $imgSizeName;
	}
	return $imgUrl;
}

function getProfileImageUrl($image, $size, $username)
{
	$imgUrl="";
	if (($image) && ($size) && ($username)) {
		list($imgName, $imgExtension) = explode(".", $image);
		$imgSizeName = $imgName . "_$size." . $imgExtension;
		$imgUrl = Yii::app()->request->getBaseUrl() . "/userdata/" . $username . "/profile/" . $imgSizeName;
	}
	return $imgUrl;	
}

function getClapsPromos()
{
    $clapsInfoUrl = Yii::app()->createUrl('site/info');
	$clapsPromos = array (
			"Clapd (pronounced as 'clapped') is a social network to capture the things you <b>would recommend</b> to your friends.",
			"Claps are <b>categorized</b> and presented in a <b>unique format</b> which makes it easier to find information.",
			"<b>Discover</b> interesting Claps from your friends and return the favor by posting interesting Claps.",
            "Claps have a <b>Score</b>, can be grouped by user defined <b>Tags</b>, and have <b>Cheer Words</b>.",
			"Claps can be <b>Liked</b>, found <b>Useful</b> & <b>Saved</b>.<br>Learn more <a href='$clapsInfoUrl'>here</a><br><br>Try out a Demo<br>(user/pass:demo)",
			);
	return ($clapsPromos);
}

function encryptText($data)
{
	$ret = base64_encode($data);
	return $ret;
}

function decryptText($data)
{
	$ret = base64_decode($data);
	return $ret;
}

function formPermaLink($clapId, $clapTitle) 
{
    $normalizedTitle = preg_replace("/[^a-zA-Z0-9\s]/", "", $clapTitle);
    $normalizedTitle = preg_replace("/\s/", "_", $normalizedTitle);

    $permaLink = Yii::app()->request->getBaseUrl() . "/clap/$clapId/$normalizedTitle" ; 
    return $permaLink;

}

function makeClickableLinks($s) {
  return preg_replace('@(https?://([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-]*(\?\S+)?[^\.\s])?)?)@', '<a href="$1" target="_blank">$1</a>', $s);
}

function processYouTubeVideo() {

}

function getRegularImage($img) {
    $ret = "";
    if (!$img) {
        return $ret;
    };
    $imgElements = explode(".", $img); 
    if (count($imgElements) == 2) {
       $namePart = $imgElements[0]; 
       $extensionPart = $imgElements[1]; 
       $ret = $namePart . "_reg." . $extensionPart;
    } else {
       $namePart = $img;
       $ret = $namePart . "_reg";
    }
    return ($ret);
}

function getReviewedTitles($claps, $charLimit=500) {
    $ret = ""; $sep = "";
    foreach ($claps as $clap) {
        $ret .= $sep . $clap['title'];
        $sep = ", ";
        if (strlen($ret) > $charLimit) {
            break;
        }
    }
    return ($ret);
}

function getSingularNameForCategory($cat) { 
    $singular = array(
        'Books' => 'Book',
        'Movies' => 'Movie',
        'TV Shows' => 'TV Show',
        'Finance Tips' => 'Finance Tip',
        'Stocks' => 'Stock', 
        'Food Stores' => 'Food Store', 
        'Recipes' => 'Recipe', 
        'Restaurants' => 'Restaurant', 
        'Quotes' => 'Quote', 
        'Riddles' => 'Riddle', 
        'Articles' => 'Article',
        'Mobile Apps' => 'Mobile App', 
        'Videos' => 'Video',
        'Websites' => 'Website',
        'Kids Activities' => 'Kids Activity', 
        'Kids Classes' => 'Kids Class', 
        'Local Businesses' =>'Local Business',
        'Professional Services' => 'Professional Service',
        'Accomodations' => 'Accomodation',
        'Area Attractions' => 'Area Attraction', 
        'Events' => 'Event',
        'Parks' => 'Park', 
        'Places'=> 'Place',
        'Deals' => 'Deal',
    ); 
    if (($cat) && (array_key_exists($cat, $singular))) {
        $ret = $singular[$cat];
    } else {
        $ret = $cat;
    }
    return $ret;
    
}
?>
