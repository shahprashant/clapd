<?php
	// echo json_encode($clapsData);

	if (isset($clapsData) && (count($clapsData['claps']) > 0 )) {
		
		// get dates for the first and last content box shown.
		$created1 = "";
		$created2 = "";
		$tmpcount = 1;
		$dateindex = 2;
		foreach ($clapsData['claps'] as $clapRow) {
		
			if (!$created1) {
				$created1 = date("M j", strtotime($clapRow['createtime']));
			}
			$created2 = date("M j", strtotime($clapRow['createtime']));
		}
		
		
		$params = array('boxrow' => $boxrow);
		$boxidcount = (($boxrow-1)*5)+2;
		$boxcount=1;
		$retHtml = "";
		foreach ($clapsData['claps'] as $clapRow) {
			$boxid = "box" . $boxidcount;
			$params['boxid'] = $boxid;
			
			$clapHtml = getClapContentHtml($clapRow, $params); 
			//echo $clapHtml;
			$retHtml .= $clapHtml;
			$boxcount++;
			$boxidcount++;
		}
		
		// if content does not occupy the whole row, then fill in remaining blank boxes for category
		while ($boxcount > 0 && $boxcount < 5) {
			$boxid = "box" . $boxidcount;
			// echo '<div class="roundbox" id="' . $boxid . '">&nbsp;</div>' . "\n";
			$retHtml .= '<div class="roundbox" id="' . $boxid . '">&nbsp;</div>' . "\n";
			$boxidcount++;
			$boxcount++;
		}
		
		$retArray['clapHtml'] = $retHtml; 
		$retArray['prevClaps'] = $clapsData['prevClaps'];
		$retArray['nextClaps'] = $clapsData['nextClaps'];
		$retArray['dateRange'] = $created1 . " - " . $created2;

		echo json_encode($retArray);
	}
?>
