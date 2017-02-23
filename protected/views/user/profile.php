<?php
include_once 'jsincludes.php';
?>

<div class="prevbox"></div>

<div class='roundbox'> 
<div class='userprofileheader'>
#Claps
</div>
<div class='userprofilecontent'>
<?php 
if (isset($numOfClaps)) {
    echo $numOfClaps;
}
?>
</div>
</div>

<div class='roundbox'> 
<div class='userprofileheader'>
#Comments
</div>
<div class='userprofilecontent'>
<?php 
if (isset($numOfComments)) {
    echo $numOfComments;
}
?>
</div>
</div>

<div class='roundbox'> 
<div class='userprofileheader'>
#Likes
</div>
<div class='userprofilecontent'>
<?php 
if (isset($numOfLikes)) {
    echo $numOfLikes;
}
?>
</div>
</div>

<div class='roundbox'> 
<div class='userprofileheader'>
#Usefuls
</div>
<div class='userprofilecontent'>
<?php 
if (isset($numOfUsefuls)) {
    echo $numOfUsefuls;
}
?>
</div>
</div>

<div class='roundbox'> 
<div class='userprofileheader'>
Average Clap Score
</div>
<div class='userprofilecontent'>
<?php 
if (isset($avgClapScore)) {
    echo $avgClapScore;
}
?>
</div>
</div>

<br clear='all'>

<div class="prevbox"></div>
<div class='roundbox'> 
<div class='userprofileheader'>
Popular Post
</div>
<div class='userprofilecontent singlefontsize12 singletextalignleft'>
<?php 
if (isset($popularClap)) {
    echo $popularClap['title'];
}
?>
</div>
</div>

<div class='roundbox'> 
<div class='userprofileheader'>
Latest Post
</div>
<div class='userprofilecontent singlefontsize12 singletextalignleft'>
<?php 
if (isset($latestClap)) {
    echo $latestClap['title'];
}
?>
</div>
</div>

<div class='roundbox'> 
<div class='userprofileheader'>
Followers
</div>
<div class='userprofilecontent'>
<?php 
if (isset($numOfFollowers)) {
    echo $numOfFollowers;
}
?>
</div>
</div>

<div class='roundbox'> 
<div class='userprofileheader'>
Following
</div>
<div class='userprofilecontent'>
<?php 
if (isset($numOfFollowings)) {
    echo $numOfFollowings;
}
?>
</div>
</div>

<div class='roundbox'> 
<div class='userprofileheader'>
Favorite Category
</div>
<div class='userprofilecontent singlefontsize16'>
<?php 
if (isset($favCategory)) {
    echo $favCategory;
}
?>
</div>
</div>


