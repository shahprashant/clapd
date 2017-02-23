<?php

if (isset($clapsData)) {

	foreach ($clapsData as $refId => $clapsInfo) {

		if (($this->page == 'Claps') || ($this->page == 'CategoryClaps') || ($this->page == "UserClapsByCategory") ) {
			$catText = $clapsInfo['catText'];
		} elseif (($this->page == 'CategoryClapsByUser') || ($this->page == 'UserHashtagClaps')) {
			$author = $clapsInfo['author'];
		} elseif ($this->page == 'UserClapsForCategory') {
			$catText = $clapsInfo['catText'];
			$author = $clapsInfo['author'];
        }


		$clapsArray = $clapsInfo['claps'];

      if (count($clapsArray) > 0) {

		// We are retrieving one extra clap then required so that we can
		// decide whether to show the Next Arrow or not.
		// If next arrow needs to be shown then set appropriate flag and
		// pass only the desired number of claps to the for loop

		if (($this->page == "Claps") && (count($clapsArray) > Yii::app()->params['numContentCols'])) {
			$clapsArray = array_slice($clapsArray,0,Yii::app()->params['numContentCols'],true);
			$nextClaps = true;
		} else {
			$nextClaps = false;
		}

		if (isset($_GET['from']) && ($_GET['from'] > 1)) {
			$prevClaps = true;
		} else {
			$prevClaps = false;
		}

        if ($this->page == "UserClapsByScore") {
		    $score1 = "";
    		$score2 = "";
    		$tmpcount = 1;
    		$scoreindex = 2;
    		foreach ($clapsArray as $clapRow) {
			
    			if (!$score1) {
    				$score1 = $clapRow['yscore']; 
    			}	
    			$score2 = $clapRow['yscore']; 
    			$scores[$scoreindex]  = "$score1 - $score2";
    		    if ($tmpcount % 4 == 0) {
    					$scoreindex++;
    					$score1 = "";
    		    }
    			$tmpcount++;			
            }
        } else {
    		// get dates for the first and last content box shown.
    		$created1 = "";
    		$created2 = "";
    		$tmpcount = 1;
    		$dateindex = 2;
    		foreach ($clapsArray as $clapRow) {
			
    			if (!$created1) {
    				$created1 = date("M j Y", strtotime($clapRow['createtime']));
    			}	
    			if (($this->page == "CategoryClaps") || 
    				($this->page == 'UserHashtagClaps') || 
    				($this->page == 'ClapAnswers') || 
    				($this->page == 'SavedClaps') || 
    				($this->page == 'UserClapsForCategory') || 
    				($this->page == 'UserClaps'))	{
    				$created2 = date("M j Y", strtotime($clapRow['createtime']));
    				$dates[$dateindex]  = "$created1<br> to <br>$created2";
    				if ($tmpcount % 4 == 0) {
    					$dateindex++;
    					$created1 = "";
    				}
    			} else {	
    				$created2 = date("M j Y", strtotime($clapRow['createtime']));
    			}
    			$tmpcount++;			
    		}
        }
		
		foreach ($clapsArray as $clapRow) {

			$boxrow = (int)(($boxidcount-1) / 5) + 1;

			$nextClapsUrl = $this->createUrl("site/getmoreclapcols", array('catid' => "$refId"));
			$prevClapsUrl = $this->createUrl("site/getmoreclapcols", array('catid' => "$refId"));

			// Print the First Column Boxes
			if ($boxcount == 0) {

				$boxid = "box" . $boxidcount;
				//$boxHideStr = getBoxesToHide($boxidcount+1);

				// Prev hidden box
				printf ("<div class='prevbox' id='prev%s'>", $boxrow);
				printf ("</div>");

				echo "<div class='roundbox headerbox greenbox' id='" . $boxid . "'>";
				echo "<div class='headercontent'>";
				if ($this->page == 'Claps') {
					echo "<p><a href='" . getCategoryUrl($refId, $catText) . "'>$catText</a>";
                    $parentCatText = Category::model()->getCategoryText($clapRow['parent']); 
					printf("<br><span>(<a href='%s'>%s</a>)</span></p>", getCategoryUrl($clapRow['parent'], $parentCatText), $parentCatText);
				} elseif ($this->page == 'UserClapsByCategory') {
					echo "<p><a href='" . getCategoryUrl($refId, $catText, $_GET['user']) . "'>$catText</a>";
                    $parentCatText = Category::model()->getCategoryText($clapRow['parent']); 
					printf("<br><span>(<a href='%s'>%s</a>)</span></p>", getCategoryUrl($clapRow['parent'], $parentCatText, $_GET['user']), $parentCatText);
				} elseif ($this->page == 'CategoryClapsByUser') { 
					echo "<p><a href='" . Yii::app()->request->getBaseUrl() . "/" . $refId . "'>$author</a>";
                } elseif ($this->page == 'UserClapsByScore') {
					if (isset($scores[$boxrow])) {
						echo "<p>$scores[$boxrow]</p>";
					}

				} else {
					if (isset($dates[$boxrow])) {
						echo "<p>$dates[$boxrow]</p>";
					}
				}
				echo "</div>\n";
				if (($this->page == "Claps") || ($this->page == "UserClapsByCategory")) {
					echo "<div class='roundbox0bottom'>";
					echo "<div class='roundbox0datedisplay' id='daterange$boxrow'>$created1 - $created2</div>";
					//echo "<div class='roundbox0date1'>$created1 - $created2</div>";
					echo "</div>";
				}

				echo "</div>\n";
				$boxcount++;
				$boxidcount++;

				// combo box for maximized view
				echo "<div class='combobox' id='combo" . $boxrow . "'></div>";
				// browse box for horizontal browsing
				echo "<div class='browsebox' id='hbrowse" . $boxrow . "'>";
			}


			$boxid = "box" . $boxidcount;
			//$boxHideStr = getBoxesToHide($boxidcount);

			// get a Clap content box
			$params = array(
				'boxid' => $boxid,
				'boxrow' => $boxrow,
				'page' => $this->page
			);
			$clapHtml = getClapContentHtml($clapRow, $params);
			echo $clapHtml;

			$boxcount++;

			if ($boxcount >= 5) {
				echo "</div>";  // end of hbrowse box div
				printf ("<div class='nextbox' id='next%s'>", $boxrow);
				if ($nextClaps) {
					$from = 5;
					printf ("<img src='/images/next-arrow.gif' onclick='getMoreClapCols(\"%s\",\"%s\", %s);' width=15>", $boxrow, $nextClapsUrl, $from);
				};
				printf ("</div>");
				echo "\n<br clear='all'>\n";
				$boxcount=0;

			}
			$boxidcount++;
		} // end of foreach

		// if content does not occupy the whole row, then fill in remaining blank boxes for category
		while ($boxcount > 0 && $boxcount < 5) {
			$boxid = "box" . $boxidcount;
			echo '<div class="roundbox" id="' . $boxid . '">&nbsp;</div>' . "\n";
			$boxidcount++;
			$boxcount++;
		}
		
		// print Next hidden box
		if ($boxcount >= 5) {
			echo "</div>"; // end of hbrowse box div
			printf ("<div class='nextbox' id='next%s'>", $boxrow);
			printf ("</div>");

			echo "\n<br clear='all'>\n";
		 	$boxcount=0;
		}

      }
	} // end of foreach clapsData
}// endf of if 

		// fill remaining empty boxes
		/*
		while ($boxidcount <= 25) {
		if ($boxcount == 0) {
		printf ("<div class='prevbox'></div>");
		}
		$boxid = "box" . $boxidcount;
		echo '<div class="roundbox" id="' . $boxid . '">&nbsp;</div>' . "\n";
		$boxidcount++;
		$boxcount++;
		if ($boxcount >= 5) {
		printf ("<div class='nextbox'></div>");
		echo "\n<br clear='all'>\n";
$boxcount=0;
}

} */

if ($this->page == "Claps")  {
echo "<div class='dummybox' nextClapRow='$nextClapRow' nextBoxIdCount='$boxidcount'></div>";
}


?>

