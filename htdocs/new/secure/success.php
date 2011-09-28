<?php 
$referer = parse_url($_SERVER['HTTP_REFERER']);
$referer = $referer['scheme'] . "://" . $referer['host'] . $referer['path'];

if($referer !='https://www.maskitalia.com/secure/orderform.php'){
	header('HTTP', true, '500');
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
"http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title>
Mask Italia - Order Confirmation
</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="Keywords" content="venetian masks,italian masks,mask italia,venetian-style masks,venetian mask artwork,venetian carnival masks,costume masks,masquerade ball masks,mask collections,mardi gras masks,mardi gras feather masks,mardi gras ball costumes,italian mask,italian classic masks,handmade venetian masks,handmade italian masks,halloween mask,feather mask,decorative mask,costume ball masks,carnival masks,authentic venetian masks">
<meta name="Description" content="Imported Venetian masks, Italian masks, feather masks, costume ball masks, and mask collections.  Masks handmade in the traditional spirit of Venice, Italy. Come in to view the many styles, sizes, and colors.">
<meta name="robots" content="all">
<meta name="classification" content="shopping, apparel, specialty, costumes, masks">
<meta http-equiv="imagetoolbar" content="no" > 
<meta name="verify-v1" content="H6r+Xr2HhbaMFchwfcMCa/9sRtnh2t5Qr0ApdLc6JLc=">
<link rel="stylesheet" href="http://www.maskitalia.com/assets/stylesheets/screen-v2.css" type="text/css">
<script type="text/javascript" src="http://www.maskitalia.com/assets/javascripts/script.js"></script>
</head>
<body class="page">
	<div id="doc4" class="yui-t7">
		<div id="hd">
			<h1><span class="hide">Mask Italia: Importer of Handmade Italian Masks</span></h1>
			<div id="main_menu">
				<ul id="main_menu_items">
					<li id="main_menu_item1" class="first"><a href="../lucia.htm" title="Italian Masks &amp; Artworks"><span class="hide">Masks &amp; Artwork</span></a></li>
					<li id="main_menu_item2"><a href="../eventsparties.htm" title="Sponsored Events and Parties with Venetian Masks"><span class="hide">Events &amp; Parties</span></a></li>
					<li id="main_menu_item3"><a href="../wholesale.htm" title="Wholesale Information on venetian masks and italian masks"><span class="hide">Wholesale</span></a></li>
					<li id="main_menu_item4"><a href="../maskhistory.htm" title="History of Venetian Masks"><span class="hide">History</span></a></li><li id="main_menu_item5" class="last"><a href="../maskitaliaheadquarters.htm" title="Mask Italia Headquarters Locations"><span class="hide">Headquarters</span></a></li>
				</ul>
			</div>
		</div>
		<div id="bd">
			<div class="yui-g">
				<div id="phone_number"></div>
				<br class="clear"/>
			</div>
			<div class="yui-g">
				<div class="full-page-text-container" style="height:400px;">
					<h3 style="margin-top:30px">Order Confirmation</h3>
					<p class="large">Thank you for ordering from Mask Italia.  Your order has been submitted and you will receive a confirmation email shortly.  Orders are normally processed within 24 hours.  Should your needs be most urgent, please <a href="mailto:maskitalia@gmail.com" style="color:#0000FF;text-decoration:underline;font-weight:normal">email us</a> with your request.</p>
				</div>
			</div>
		</div>
		<div id="ft">&copy; 2011 Mask Italia</div>
	</div>
	<script type="text/javascript">
	  var ga_date = new Date();
	  var ga_trans_id = ga_date.getTime();
	  var _gaq = _gaq || [];

	  _gaq.push(['_setAccount', 'UA-1354660-5']);
	  _gaq.push(['_trackPageview']);

	  _gaq.push(['_addTrans',ga_trans_id,'Mask','150','0','0','New Orleans','Louisiana','United States']);
	  _gaq.push(['_addItem',ga_trans_id,'MASK','Mask','Mask','150','1']);
	  _gaq.push(['_trackTrans']);

	  (function() {
	    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
	    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
	    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	  })();

	</script>
</body>
</html>