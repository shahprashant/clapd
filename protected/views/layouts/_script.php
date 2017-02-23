<script type="text/javascript">
function flipLogoBox(boxid)
{
	var flipContent = "<div class=\"actionbox1\"><a href='<?php echo $this->createUrl('site/about');?>'>About</a></div><div class=\"actionbox2\"><a href='<?php echo $this->createUrl('site/info');?>'>Salient Features</a></div><div class=\"actionbox1\"><a href='<?php echo $this->createUrl('site/faq');?>'>FAQ</a></div><div class='logoboxbottom'><div class='bottomsubbox1'><img src='/images/flip.png' onmouseover='$(\"#box5\").revertFlip();'></img></div></div></div>";
	$("#" + boxid).flip({
		direction:'tb',
		color:'#F5F5F5',
		content:flipContent,
	});
	return false;
}
</script>
