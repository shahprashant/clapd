<?php if ((isset($this->page)) && (($this->page == 'CategoryClaps') || ($this->page == 'CategoryClapsByUser'))) { ?>
<div class="roundbox headerbox greenbox" id="box2">
<div class="headercontent">
<?php printf("<p>%s<br>Category</p>", $this->layoutParams['catText']); ?>
</div>
<?php
		printf("<div class='headercontentbottombox'>");
		if (isset($_GET['sort']) && ($_GET['sort'] == 'user')) {
			printf("<div class='headercontentbottomsubbox2'>");
			printf("<a href='%s'>By<br>Time</a>", getCategoryUrl($_GET['catid'], $this->layoutParams['catText']));
			printf("</div>");
			printf("<div class='headercontentbottomsubbox2'>");
			printf("By<br>User");
			printf("</div>");
		} else {
			printf("<div class='headercontentbottomsubbox2'>");
			printf("By<br>Time");
			printf("</div>");
			printf("<div class='headercontentbottomsubbox2'>");
			printf("<a href='%s'>By<br>User</a>", getCategoryUrl($_GET['catid'], $this->layoutParams['catText']) . "?sort=user");
			printf("</div>");
		}
		printf("</div>");
?>		
</div>
<?php } elseif ((isset($this->page)) && (($this->page == 'UserClaps') || ($this->page == 'UserClapsByCategory') || ($this->page == 'UserClapsByScore'))) { ?>
	<div class="roundbox headerbox greenbox" id="box2">
	<div class="headercontent">
<?php 
	printf("<p><a href='%s'>%s's</a><br>Claps</p>", $this->layoutParams['profileUrl'], $this->layoutParams['author']); 
?>
	</div>
<?php 
		printf("<div class='headercontentbottombox'>");
		if (isset($_GET['sort']) && ($_GET['sort'] == 'cat')) {
			printf("<div class='headercontentbottomsubbox3'>");
			printf("<a href='%s'>By<br>Time</a>", Yii::app()->request->getBaseUrl() . "/" . $_GET['user']);
			printf("</div>");
			printf("<div class='headercontentbottomsubbox3'>");
			printf("By<br>Category");
			printf("</div>");
			printf("<div class='headercontentbottomsubbox3'>");
			printf("<a href='%s'>By<br>Score</a>", Yii::app()->request->getBaseUrl() . "/" . $_GET['user'] . "?sort=score");
			printf("</div>");
		} elseif (isset($_GET['sort']) && ($_GET['sort'] == 'score')) {
			printf("<div class='headercontentbottomsubbox3'>");
			printf("<a href='%s'>By<br>Time</a>", Yii::app()->request->getBaseUrl() . "/" . $_GET['user']);
			printf("</div>");
			printf("<div class='headercontentbottomsubbox3'>");
			printf("<a href='%s'>By<br>Category</a>", Yii::app()->request->getBaseUrl() . "/" . $_GET['user'] . "?sort=cat");
			printf("</div>");
			printf("<div class='headercontentbottomsubbox3'>");
			printf("By<br>Score");
			printf("</div>");
		} else {
			printf("<div class='headercontentbottomsubbox3'>");
			printf("By<br>Time");
			printf("</div>");
			printf("<div class='headercontentbottomsubbox3'>");
			printf("<a href='%s'>By<br>Category</a>", Yii::app()->request->getBaseUrl() . "/" . $_GET['user'] . "?sort=cat");
			printf("</div>");
			printf("<div class='headercontentbottomsubbox3'>");
			printf("<a href='%s'>By<br>Score</a>", Yii::app()->request->getBaseUrl() . "/" . $_GET['user'] . "?sort=score");
			printf("</div>");
		}
		printf("</div>");
?>
</div>
<?php } elseif ((isset($this->page)) && ($this->page == 'UserClapsForCategory')) { ?>
	<div class="roundbox headerbox greenbox" id="box2">
	<div class="headercontent">
<?php 
	printf("<p>%s's<br>Claps<br>in %s</p>", $this->layoutParams['author'], $this->layoutParams['catText']); 
?>
	</div>
<?php
    if (Yii::app()->user->isGuest) {
		printf("<div class='headercontentbottombox'>");
        printf("<a href='%s'>View All Claps</a>", $this->layoutParams['userClapsUrl']); 
        printf("</div>");
    }
?>
    </div>
<?php } elseif  ((isset($this->page)) && ($this->page == 'PostClap')) { ?>
	<div class="roundbox headerbox greenbox" id="box2">
	<div class="headercontent">
	<?php 
		if (isset($_GET['question']) && ($_GET['question'] == '1')) { 
			if (isset($this->layoutParams['catText'])) {
				// printf("<p>Ask for Claps in %s</p>",$this->layoutParams['catText']);
			} else {
				// printf("<p>Ask for Claps in one of the following categories.</p>");
			}	
		} else { 
			if (isset($this->layoutParams['catText'])) { 
				if (isset($_GET['rClap']) && ($_GET['rClap'] != '')) {
					printf("<p>You are replying to a request for Claps in %s Category</p>",$this->layoutParams['catText']);
				} else {
					printf("<p>Post a Clap in<br>%s<br>Category</p>",$this->layoutParams['catText']); 
				}
			} else { 
				printf("<p>Post a Clap in one of the following categories.</p>");
			}
		}
	?>
	</div>
	</div>
<?php } elseif  ((isset($this->page)) && ($this->page == 'BrowseCategories')) { ?>
	<div class="roundbox headerbox greenbox" id="box2">
	<div class="headercontent">
	<?php printf("<p>Claps<br>by<br>Category</p>"); ?>
	</div>
	</div>
<?php } elseif  ((isset($this->page)) && ($this->page == 'UserHashtagClaps')) { ?>
	<div class="roundbox headerbox greenbox" id="box2">
	<div class="headercontent">
	<?php printf("<p>%s's<br>Claps<br>with<br>#%s</p>", $this->layoutParams['author'], $this->layoutParams['hashtag']); ?>
	</div>
	</div>
<?php } elseif  ((isset($this->page)) && ($this->page == 'ClapAnswers')) { ?>
	<div class="roundbox headerbox greenbox" id="box2">
	<div class="headercontent">
	<?php printf("<p>Responses to Request for Claps</p>"); ?>
	</div>
	</div>
<?php } elseif  ((isset($this->page)) && ($this->page == 'Following')) { ?>
	<div class="roundbox headerbox greenbox" id="box2">
	<div class="headercontent">
	<?php printf("<p>You are Following the people below</p>"); ?>
	</div>
	</div>
<?php } elseif  ((isset($this->page)) && ($this->page == 'Followers')) { ?>
	<div class="roundbox headerbox greenbox" id="box2">
	<div class="headercontent">
	<?php printf("<p>The people below Follow you</p>"); ?>
	</div>
	</div>
<?php } elseif  ((isset($this->page)) && ($this->page == 'FindFriends')) { ?>
	<div class="roundbox headerbox greenbox" id="box2">
	<div class="headercontent">
	<?php printf("<p>Follow your below facebook friends on Claps</p>"); ?>
	</div>
	</div>
<?php } elseif ((isset($this->page)) && ($this->page == 'UserProfile')) { ?>
	<div class="roundbox headerbox greenbox" id="box2">
	<div class="headercontent">
<?php 
	printf("<p>%s's<br>Profile</p>", $this->layoutParams['author']); 
?>
	</div>
    </div>
<?php } elseif ((isset($this->page)) && ($this->page == 'SavedClaps')) { ?>
	<div class="roundbox headerbox greenbox" id="box2">
	<div class="headercontent">
<?php 
	printf("<p>My Saved Claps</p>"); 
?>
	</div>
    </div>
<?php } elseif ((isset($this->page)) && ($this->page == 'UserTags')) { ?>
	<div class="roundbox headerbox greenbox" id="box2">
	<div class="headercontent">
<?php 
	printf("<p>%s's<br>Tags</p>", $this->layoutParams['author']);
?>
	</div>
    </div>
<?php } else { ?>
<div class="roundbox headerbox greenbox" id="box2">
<div class="headercontent" style="font-size:16px;">
<?php printf("<p>Clap For Something Today!! </p>"); ?>
</div>
</div>
<?php } ?>
