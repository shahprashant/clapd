<div class="roundbox" id="box4">
<form>
<?php //printf("<div class='loginbutton green'><a href='%s'>Login</a></div>", $this->createUrl('site/login')); 
printf("<input type=button value='Login' class='loginbutton green' onClick=\"parent.location='%s'\">", $this->createUrl('site/login'));
//printf("<input type=button value='Request Invite' class='requestinvitebutton orange' onClick=\"parent.location='%s'\">", $this->createUrl('site/invite'));
?>
</form>
</div>
