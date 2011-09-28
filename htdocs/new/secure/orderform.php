<?php 

//set the url for the order controller which processes orders
define("ORDER_CONTROLLER_URL", "https://www.maskitalia.com/secure/orderController.php");
define("ORDER_FORM_URL", "https://www.maskitalia.com/secure/orderform.php");

//start a session
if(!isset($_SESSION)){
	session_name('maskitalia');
	ini_set("session.gc_maxlifetime", "18000"); 
	session_start();
}

// define session
$session = $_SESSION;

//declare vars for form data that would be passed back from order controller if form data does not validate
$name = '';
$phone = '';
$fax = '';
$email = '';
$card = '';
$address = '';
$billing = '';
$order = '';
$shipping = '';


// determine if request is redirected back from order controller
$redirect_back = false;
if(isset($_SERVER['HTTP_REFERER'])){
	$referer = parse_url($_SERVER['HTTP_REFERER']);
	$referer = $referer['scheme'] . "://" . $referer['host'] . $referer['path'];
	if($referer == ORDER_FORM_URL){
		$redirect_back = true;
	}
}

// fill form data vars with data from order controller
if($redirect_back){
	if(isset($_GET['fName'])){
		$name = $_GET['fName'];
	}
	if(isset($_GET['Phone'])){
		$phone = $_GET['Phone'];
	}
	if(isset($_GET['Fax'])){
		$fax = $_GET['Fax'];
	}
	if(isset($_GET['Email'])){
		$email = $_GET['Email'];
	}
	if(isset($_GET['Card'])){
		$card = $_GET['Card'];
	}
	if(isset($_GET['Address'])){
		$address = $_GET['Address'];
	}
	if(isset($_GET['Billing'])){
		$billing = $_GET['Billing'];
	}
	if(isset($_GET['Order'])){
		$order = $_GET['Order'];
	}
	if(isset($_GET['Shipping'])){
		$shipping = $_GET['Shipping'];
	}	
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
"http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title>Mask Italia - Order Form</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<script type="text/javascript" src="https://www.maskitalia.com/assets/javascripts/validator.js"></script>
<style type="text/css" media="screen">
/* YAHOO RESET FONTS N GRIDS v2.5.2 */
html{color:#000;background:#FFF;}body,div,dl,dt,dd,ul,ol,li,h1,h2,h3,h4,h5,h6,pre,code,form,fieldset,legend,input,textarea,p,blockquote,th,td{margin:0;padding:0;}table{border-collapse:collapse;border-spacing:0;}fieldset,img{border:0;}address,caption,cite,code,dfn,em,strong,th,var{font-style:normal;font-weight:normal;}li{list-style:none;}caption,th{text-align:left;}h1,h2,h3,h4,h5,h6{font-size:100%;font-weight:normal;}q:before,q:after{content:'';}abbr,acronym {border:0;font-variant:normal;}sup {vertical-align:text-top;}sub {vertical-align:text-bottom;}input,textarea,select{font-family:inherit;font-size:inherit;font-weight:inherit;}input,textarea,select{*font-size:100%;}legend{color:#000;}body {font:13px/1.231 arial,helvetica,clean,sans-serif;*font-size:small;*font:x-small;}table {font-size:inherit;font:100%;}pre,code,kbd,samp,tt{font-family:monospace;*font-size:108%;line-height:100%;}
body{text-align:center;}#ft{clear:both;}#doc,#doc2,#doc3,#doc4,.yui-t1,.yui-t2,.yui-t3,.yui-t4,.yui-t5,.yui-t6,.yui-t7{margin:auto;text-align:left;width:57.69em;*width:56.25em;min-width:750px;}#doc2{width:73.076em;*width:71.25em;}#doc3{margin:auto 10px;width:auto;}#doc4{width:74.923em;*width:73.05em;}.yui-b{position:relative;}.yui-b{_position:static;}#yui-main .yui-b{position:static;}#yui-main{width:100%;}.yui-t1 #yui-main,.yui-t2 #yui-main,.yui-t3 #yui-main{float:right;margin-left:-25em;}.yui-t4 #yui-main,.yui-t5 #yui-main,.yui-t6 #yui-main{float:left;margin-right:-25em;}.yui-t1 .yui-b{float:left;width:12.30769em;*width:12.00em;}.yui-t1 #yui-main .yui-b{margin-left:13.30769em;*margin-left:13.05em;}.yui-t2 .yui-b{float:left;width:13.8461em;*width:13.50em;}.yui-t2 #yui-main .yui-b{margin-left:14.8461em;*margin-left:14.55em;}.yui-t3 .yui-b{float:left;width:23.0769em;*width:22.50em;}.yui-t3 #yui-main .yui-b{margin-left:24.0769em;*margin-left:23.62em;}.yui-t4 .yui-b{float:right;width:13.8456em;*width:13.50em;}.yui-t4 #yui-main .yui-b{margin-right:14.8456em;*margin-right:14.55em;}.yui-t5 .yui-b{float:right;width:18.4615em;*width:18.00em;}.yui-t5 #yui-main .yui-b{margin-right:19.4615em;*margin-right:19.125em;}.yui-t6 .yui-b{float:right;width:23.0769em;*width:22.50em;}.yui-t6 #yui-main .yui-b{margin-right:24.0769em;*margin-right:23.62em;}.yui-t7 #yui-main .yui-b{display:block;margin:0 0 1em 0;}#yui-main .yui-b{float:none;width:auto;}.yui-gb .yui-u,.yui-g .yui-gb .yui-u,.yui-gb .yui-g,.yui-gb .yui-gb,.yui-gb .yui-gc,.yui-gb .yui-gd,.yui-gb .yui-ge,.yui-gb .yui-gf,.yui-gc .yui-u,.yui-gc .yui-g,.yui-gd .yui-u{float:left;}.yui-g .yui-u,.yui-g .yui-g,.yui-g .yui-gb,.yui-g .yui-gc,.yui-g .yui-gd,.yui-g .yui-ge,.yui-g .yui-gf,.yui-gc .yui-u,.yui-gd .yui-g,.yui-g .yui-gc .yui-u,.yui-ge .yui-u,.yui-ge .yui-g,.yui-gf .yui-g,.yui-gf .yui-u{float:right;}.yui-g div.first,.yui-gb div.first,.yui-gc div.first,.yui-gd div.first,.yui-ge div.first,.yui-gf div.first,.yui-g .yui-gc div.first,.yui-g .yui-ge div.first,.yui-gc div.first div.first{float:left;}.yui-g .yui-u,.yui-g .yui-g,.yui-g .yui-gb,.yui-g .yui-gc,.yui-g .yui-gd,.yui-g .yui-ge,.yui-g .yui-gf{width:49.1%;}.yui-gb .yui-u,.yui-g .yui-gb .yui-u,.yui-gb .yui-g,.yui-gb .yui-gb,.yui-gb .yui-gc,.yui-gb .yui-gd,.yui-gb .yui-ge,.yui-gb .yui-gf,.yui-gc .yui-u,.yui-gc .yui-g,.yui-gd .yui-u{width:32%;margin-left:1.99%;}.yui-gb .yui-u{*margin-left:1.9%;*width:31.9%;}.yui-gc div.first,.yui-gd .yui-u{width:66%;}.yui-gd div.first{width:32%;}.yui-ge div.first,.yui-gf .yui-u{width:74.2%;}.yui-ge .yui-u,.yui-gf div.first{width:24%;}.yui-g .yui-gb div.first,.yui-gb div.first,.yui-gc div.first,.yui-gd div.first{margin-left:0;}.yui-g .yui-g .yui-u,.yui-gb .yui-g .yui-u,.yui-gc .yui-g .yui-u,.yui-gd .yui-g .yui-u,.yui-ge .yui-g .yui-u,.yui-gf .yui-g .yui-u{width:49%;*width:48.1%;*margin-left:0;}.yui-g .yui-gb div.first,.yui-gb .yui-gb div.first{*margin-right:0;*width:32%;_width:31.7%;}.yui-g .yui-gc div.first,.yui-gd .yui-g{width:66%;}.yui-gb .yui-g div.first{*margin-right:4%;_margin-right:1.3%;}.yui-gb .yui-gc div.first,.yui-gb .yui-gd div.first{*margin-right:0;}.yui-gb .yui-gb .yui-u,.yui-gb .yui-gc .yui-u{*margin-left:1.8%;_margin-left:4%;}.yui-g .yui-gb .yui-u{_margin-left:1.0%;}.yui-gb .yui-gd .yui-u{*width:66%;_width:61.2%;}.yui-gb .yui-gd div.first{*width:31%;_width:29.5%;}.yui-g .yui-gc .yui-u,.yui-gb .yui-gc .yui-u{width:32%;_float:right;margin-right:0;_margin-left:0;}.yui-gb .yui-gc div.first{width:66%;*float:left;*margin-left:0;}.yui-gb .yui-ge .yui-u,.yui-gb .yui-gf .yui-u{margin:0;}.yui-gb .yui-gb .yui-u{_margin-left:.7%;}.yui-gb .yui-g div.first,.yui-gb .yui-gb div.first{*margin-left:0;}.yui-gc .yui-g .yui-u,.yui-gd .yui-g .yui-u{*width:48.1%;*margin-left:0;}s .yui-gb .yui-gd div.first{width:32%;}.yui-g .yui-gd div.first{_width:29.9%;}.yui-ge .yui-g{width:24%;}.yui-gf .yui-g{width:74.2%;}.yui-gb .yui-ge div.yui-u,.yui-gb .yui-gf div.yui-u{float:right;}.yui-gb .yui-ge div.first,.yui-gb .yui-gf div.first{float:left;}.yui-gb .yui-ge .yui-u,.yui-gb .yui-gf div.first{*width:24%;_width:20%;}.yui-gb .yui-ge div.first,.yui-gb .yui-gf .yui-u{*width:73.5%;_width:65.5%;}.yui-ge div.first .yui-gd .yui-u{width:65%;}.yui-ge div.first .yui-gd div.first{width:32%;}#bd:after,.yui-g:after,.yui-gb:after,.yui-gc:after,.yui-gd:after,.yui-ge:after,.yui-gf:after{content:".";display:block;height:0;clear:both;visibility:hidden;}#bd,.yui-g,.yui-gb,.yui-gc,.yui-gd,.yui-ge,.yui-gf{zoom:1;}
/* END*/
/* Foundations */
body,html{background-color:#666666;color:#999999;font-family:"Trebuchet MS",Arial,sans-serif;height:100%;}
div{background-repeat:no-repeat;}
#doc4{min-height:100%;background-color:#660000;border-left:1px solid #333;border-right:1px solid #333;}
#hd{background:#fff url('https://www.maskitalia.com/assets/images/assets/pk_pgbg_long.jpg') repeat-x;background-position:0px -20px;width:974px;height:117px;}
#bd{background-color:#fff;min-height:80%;width:974px;}
#phone_number{background:transparent url('https://www.maskitalia.com/assets/images/assets/maskitalia-mainmenu-sprite-v2.png') no-repeat 0px -160px;height:16px;width:124px;float:left;margin-left:10px;display:block;}
#ft{background-color:#660000;text-align:right;padding:2px 5px 2px 0;font-size:10px;color:#440000;width:969px;}
a {
	outline: none;
}
/* essentials */
span.hide{display:none;}
.cl{clear:both;height:0;margin:0;padding:0;line-height:0;font-size:10%;}
/* Main Menu */
#main_menu{display:block;float:right;margin:97px 2px 0 0;}
#main_menu li{float:left;line-height:7px;border-right:1px solid #909090;border-left:1px solid #909090;padding:0 6px;}
#main_menu li.first{border-left:0px solid white;}
#main_menu li.last{border-right:0px solid white;}
#main_menu_item1 a,#main_menu_item2 a,#main_menu_item3 a,#main_menu_item4 a,#main_menu_item5 a,#main_menu_item a{display:block;background-image:url('https://www.maskitalia.com/assets/images/assets/maskitalia-mainmenu-sprite-v2.png');color:transparent;}
#main_menu_item1 a{background-position:0px 0px;width:112px;height:10px;}
#main_menu_item2 a{background-position:0px -25px;width:157px;height:10px;}
#main_menu_item3 a{background-position:0px -80px;width:68px;height:10px;}
#main_menu_item4 a{background-position:0px -105px;width:49px;height:10px;}
#main_menu_item5 a{background-position:0px -130px;width:92px;height:10px;}
/* Side Menu */
#side_menu_header{background-image:url('https://www.maskitalia.com/assets/images/assets/maskitalia-sidemenu-sprite-v2.png');height:49px;width:288px;}
body.page #side_menu_header{clear:both;margin:20px 0 0 40px;}
body.michela #side_menu_header{background-position:0px 0px;}
body.lucia #side_menu_header{background-position:0px -51px;}
body.carlo #side_menu_header{background-position:0px -102px;}
body.massimo #side_menu_header{background-position:0px -153px;}
body.bijan #side_menu_header{background-position:0px -204px;}
body.antonia #side_menu_header{background-position:0px -272px;}
body.orderform #side_menu_header{background-position:-136px -324px;background-repeat: repeat-x;}
body.events #side_menu_header{background-position:-79px -377px;}
body.wholesale #side_menu_header{background-position:-167px -438px;}
body.history #side_menu_header{background-position:-192px -489px;}
body.headquarters #side_menu_header{background-position:0px -548px;}
#side_menu ul li,#side_menu a{display:block;background-image:url('https://www.maskitalia.com/assets/images/assets/maskitalia-sidemenu-sprite-v2.png');float:right;clear:both;height:17px;width:167px;}
#side_menu_item1 a{background-position:145px -623px;}
#side_menu_item1 a:hover{background-position:145px -606px;}
body.michela #side_menu_item1 a{background-position:145px -606px;}
#side_menu_item2 a{background-position:145px -657px;}
#side_menu_item2 a:hover{background-position:145px -640px;}
body.lucia #side_menu_item2 a{background-position:145px -640px;}
#side_menu_item3 a{background-position:145px -691px;}
#side_menu_item3 a:hover{background-position:145px -674px;}
body.carlo #side_menu_item3 a{background-position:145px -674px;}
#side_menu_item4 a{background-position:145px -725px;}
#side_menu_item4 a:hover{background-position:145px -708px;}
body.massimo #side_menu_item4 a{background-position:145px -708px;}
#side_menu_item5 a{background-position:145px -759px;}
#side_menu_item5 a:hover{background-position:145px -742px;}
body.bijan #side_menu_item5 a{background-position:145px -742px;}
#side_menu_item6 a{background-position:145px -793px;}
#side_menu_item6 a:hover{background-position:145px -776px;}
body.antonia #side_menu_item6 a{background-position:145px -776px;}
#side_menu_item7 a{background-position:145px -810px;}
/* navigator */
#navigator{clear:both;}
#navigator li{display:block;float:left;margin:6px;}
#navigator a{display:block;float:left;background-image:url('https://www.maskitalia.com/assets/images/assets/maskitalia-nav-sprite.png');background-repeat:repeat-x;height:11px;width:8px;}
#first_button a{background-position:1px -28px;}
#first_button a:hover{background-position:1px -75px;height:11px;width:8px;}
#previous_button a{background-position:0px -28px;}
#previous_button a:hover{background-position:0px -75px;height:11px;width:8px;}
#next_button a{background-position:0px -4px;}
#next_button a:hover{background-position:0px -51px;height:11px;width:8px;}
#last_button a{background-position:-1px -4px;}
#last_button a:hover{background-position:-1px -51px;height:11px;width:8px;}
/* thumbs */
.thumb img{background:transparent url('https://www.maskitalia.com/assets/images/assets/frame.png') repeat-x 0px 0px;padding:11px 10px;}
#thumbs ul li{display:block;float:left;margin-top:0px;margin-right:10px;}
#thumb-nav{background-color:#660000;padding-bottom:22px;clear:right;}
#thumb-nav li{font-weight:bold;float:left;margin:1px 1% 0pt;font-family:Verdana,arial,sans-serif;font-size:10px;line-height:19px;}
#thumb-nav li a{color:#eee;text-decoration:none;}
.active {text-decoration:underline !important;}
/* mask viewer */
#image_viewer{height:350px;min-height:350px;margin-left:200px;}
.maskimage{height:260px;min-height:260px;}
p{color:#999999;font-family:"Trebuchet MS",arial,sans-serif;font-size:12px;margin:3px 0px 6px 3px;}
.title{color:#660000;font-family:Verdana,arial,sans-serif;font-size:16px;font-weight:bold;margin-bottom:6px;}
.thumb-container{background-color:#660000;padding:10px;}
body.page h3{font-family:Verdana,Arial,Helvetica,sans-serif;color:#990000;font-size:100%;font-weight:bold;}
body.page p{color:#333333;font-size:93%;font-family:Verdana,Arial,Helvetica,sans-serif;text-align:justify;padding:5px 0px;}
body.page p.large{font-size:108%;}
body.page a:link{color:#660000;font-weight:bold;text-decoration:none;}
body.page a:active{color:#660000;font-weight:bold;text-decoration:none;}
body.page a:visited{color:#660000;font-weight:bold;text-decoration:none;}
body.page a:hover{color:#660000;font-weight:bold;text-decoration:underline;}
body.page img.img-float-left{padding:5px 15px;float:left;}
body.page img.img-float-right{padding:5px 15px;float:right;}
body.page p.list{color:#666666;font-size:85%;font-family:Verdana,Arial,Helvetica,sans-serif;font-weight:bold;margin:24px 0px 12px 24px;text-align:left;}
div.half-page-text-container{padding:0pt 50px 10px;margin-bottom:60px;}
div.full-page-text-container{width:92%;margin:0 auto 60px;}
div.page-image-container{text-align:center;margin-top:20px;}

	form p {
		margin:1%;
	}
	form p span.lbl {
		float:left;
		font-weight:bold;
		line-height:200%;
		margin:0px 5px;
		text-align:right;
		width:200px;
	}
	input, select {
		color:#333;
		font-family:Arial,sans-serif;
		font-size:108%;
		padding:3px;
	}
	input[type=submit], input[type=reset] {
		font-size:140%;
		height:2em;
	}
	textarea {
		color:#333;
		font-family:Arial,sans-serif;
		font-size:108%;		
		padding:10px;
	}
	.note {
		font-weight:normal;
		font-style:italic;
		color:#660000;
	}
	.errors {
		border:1px solid red;
		color:#333333;
		padding:15px 20px;
		font: 100% verdana, sans-serif;
	}
	.errors li {
		margin-bottom:1em;
	}
	.errors li.error {
		margin:0;
		list-style: disc;
		padding-left:7px;
		list-style-position: inside;
	}
</style>
</head>
<body class="orderform page">
	<div id="doc4" class="yui-t7">
		<div id="hd"><h1><span class="hide">Mask Italia: Importer of Handmade Italian Masks</span></h1>
			<div id="main_menu">
				<ul id="main_menu_items">
					<li id="main_menu_item1" class="first"><a href="http://www.maskitalia.com/lucia.htm" title="Italian Masks &amp; Artworks"><span class="hide">Masks &amp; Artwork</span></a></li>
					<li id="main_menu_item2"><a href="http://www.maskitalia.com/eventsparties.htm" title="Sponsored Events and Parties with Venetian Masks"><span class="hide">Events &amp; Parties</span></a></li>
					<li id="main_menu_item3"><a href="http://www.maskitalia.com/wholesale.htm" title="Wholesale Information on venetian masks and italian masks"><span class="hide">Wholesale</span></a></li>
					<li id="main_menu_item4"><a href="http://www.maskitalia.com/maskhistory.htm" title="History of Venetian Masks"><span class="hide">History</span></a></li>
					<li id="main_menu_item5" class="last"><a href="http://www.maskitalia.com/maskitaliaheadquarters.htm" title="Mask Italia Headquarters Locations"><span class="hide">Headquarters</span></a></li>
				</ul>
			</div>
		</div>
		<div id="bd">
			<div class="yui-g">
				<div id="phone_number"></div>
				<br class="cl">
				<div id="side_menu_header" style="float:left;"><span class="hide">Order Form</span></div>
			</div>
<div class="yui-g">
	<div style="padding:3%;border:1px solid #F1FFF1;background-color:#FBFFFB;width:85%;margin:0 auto 20px;">
		<?php if($redirect_back): ?>
			<?php if(isset($session)): ?>
				<?php if(isset($session['errors'])): ?>
					<div class="errors">
						<ul>
							<li>The following errors occured:</li>
							<?php foreach($session['errors'] as $error): ?>
							<li class="error"><?php echo $error ?></li>
							<?php endforeach ?>
						</ul>
					</div>
				<?php endif ?>
			<?php endif ?>
		<?php endif ?>
	<form method="POST" action="<?php echo ORDER_CONTROLLER_URL ?>" name="myForm">
		<p style="padding:0 231px 0 212px;"><span class="note">All orders are processed and shipped within 24 hours!</span><br/><span style="font-size:10px;">In the rare instance that the mask you chose isn't in stock you will be notified immediately so that you may make another choice</span></p>
		<p><span class="lbl">Name:</span><input type="text" name="fName" value="<?php echo $name ?>" size="40"><span class="note"> * REQUIRED</span></p>

		<p><span class="lbl">Shipping Address:<br><span class="note"> * REQUIRED</span></span><textarea name="Address" cols="40" rows="4"><?php echo $address ?></textarea></p>

		<p><span class="lbl">Billing Address:<br><span class="note">(List only if different from shipping address)</span></span><textarea name="Billing" cols="40" rows="4"><?php echo $billing ?></textarea></p>

		<p><span class="lbl">Phone:</span><input type="text" value="<?php echo $phone ?>" name="Phone" size="25"></p>

		<p><span class="lbl">Fax:</span><input type="text" value="<?php echo $fax ?>" name="Fax" size="25"></p>

		<p><span class="lbl">Email:</span><input type="text" value="<?php echo $email ?>" name="Email" size="25"><span class="note"> * REQUIRED</span></p>

		<p><span class="lbl">Mask Orders:<br><span style="font-weight:normal;font-style:italic;">(Provide name or item #, quantity, and price</span></span>
			<textarea name="Order" cols="40" rows="8" onFocus="clearDefault(this)"><?php if($order ==''): ?>example:
Farfallia Black/White
CV012a
$54.00
quantity 2
			<?php else: ?><?php echo $order ?><?php endif ?></textarea></p>

		<p><span class="lbl">Shipping:</span><select name="Shipping" size="1">
			<option value='' <?php echo ($shipping=='') ? 'selected="true"' : '' ?>>(select one)</option>
			<option value='UPS Ground' <?php echo ($shipping=='UPS Ground') ? 'selected="true"' : '' ?>>UPS Ground</option>
			<option value='UPS 3-day' <?php echo ($shipping=='UPS 3-day') ? 'selected="true"' : '' ?>>UPS 3-day</option>
			<option value='UPS 2-day' <?php echo ($shipping=='UPS 2-day') ? 'selected="true"' : '' ?>>UPS 2-day</option>
			<option value='UPS Overnight' <?php echo ($shipping=='UPS Overnight') ? 'selected="true"' : '' ?>>UPS Overnight</option>
			<option value='Postal Service' <?php echo ($shipping=='Postal Service') ? 'selected="true"' : '' ?>>Postal Service (for international sales)</option>
			<option value='Other' <?php echo ($shipping=='Other') ? 'selected="true"' : '' ?>>Other (indicate below)</option>
		</select><span class="note"> * REQUIRED</span></p>

		<p><span class="lbl"><br><br></span>* Prices vary depending on selected shipping option,<br>destination, size, and weight. Mask Italia will obtain<br>a quote for shipping and charge customers accordingly.</p>
			
		<p><span class="lbl">Comments/Questions:</span>
			<textarea name="Comments" cols="40" rows="6"></textarea></p>
			
		<p><span class="lbl">Payment Type:</span><select name="Card" id="Card" onChange="updateValidator();" size="1">
						<option value='' <?php echo ($card=='') ? 'selected="true"' : '' ?>>(select payment)</option>
						<option value='Visa' <?php echo ($card=='Visa') ? 'selected="true"' : '' ?>>Visa</option>
						<option value='MC' <?php echo ($card=='MC') ? 'selected="true"' : '' ?>>MC</option>
						<option value='Amex' <?php echo ($card=='Amex') ? 'selected="true"' : '' ?>>Amex</option>
						<option value='Discover' <?php echo ($card=='Discover') ? 'selected="true"' : '' ?>>Discover</option>
						<option value='Check' <?php echo ($card=='Check') ? 'selected="true"' : '' ?>>Check</option>
					</select><span class="note"> * REQUIRED</span></p>
			
		<p><span class="lbl">Credit Card Number:</span><input onFocus="javascript:document.getElementById('charge_note').style.display = 'block';" type="text" name="Number" size="25" onBlur="stripChars(this);"><span class="note">&nbsp;No Spaces or Dashes</span></p>
		<p id="charge_note" style="display:none;"><span class="lbl">&nbsp;</span><span class="note">Charge will be made by Ma Sherie Amour, Inc.<br>This name will appear on your credit card statement.</span></p>
		<p><span class="lbl">Expiration Date:</span><input type="hidden" name="Expiration Date" value="&nbsp;"><input type="text" name="Month" size="3" value="MM" onFocus="clearDefault(this)"><input type="text" name="Year" size="5" value="YYYY" onFocus="clearDefault(this)"></p>
		<p><span class="lbl">CVN Number:</span><input type="text" name="CVN" size="4" maxlength="4"><a href="javascript:showBranch('branch1')" style="color:aqua;text-decoration:underline;font-size:10px;font-weight:normal;margin-left:10px;">What's this?</a>

<span id="branch1" class="branch" style="display:none;">
<span class="lbl"><br><br><br><br><br><br><br><br></span>What is a Card Validation Number (CVN)?<br>
This is an authentication feature established by credit and debit<br> card companies to further efforts towards reducing fraud on <br>online transactions.<br><br> 

The CVN is printed on your Visa, Master Card and Discover<br>cards in the signature area of the back of the card. It is the last<br>3 digits AFTER the credit card number in the signature area of <br>the card.<br><br>American Express cards show the CVN printed above and to<br>the right of the imprinted card number on the front of the card.</span>
<p><span class="lbl"><br><br><br></span><input type="submit" name="Submit" value="Submit">
&nbsp;&nbsp;<input type="reset" name="reset" value="Reset"><br>Pressing submit will transmit order directly to Mask Italia and <br>confirm that you have fully read and understand our policy below.</p>
<div style="background-color:#fff;width:73%;margin:30px auto;border:2px groove #fff;padding:20px;">
<h3>Mask Italia Return Policy</h3>
<p>Mask Italia does not issue refunds of any kind, at any time. If you are dissatisfied with your item, we do offer credit for use on future purchases  once the original mask is sent back to us insured and paid for by the customer. A claim for store credit must be made within one day of receipt. For items which have been damaged in shipment, we require that a claim be made on the day of receipt due to UPS requirements. Please save all packaging that your item arrived in for purposes of the claim.  Please know that all our masks are handmade and therefore may differ slightly.</p>
</div>
		</form>
				</div>
			</div>
			</div>
		<div id="ft">&copy; 2011 Mask Italia</div>
	</div>
<script type="text/javascript" charset="utf-8">

function stripChars(elm){
	elm.value = elm.value.replace(/-/gi, '');
	elm.value = elm.value.replace(/\./gi, '');
	elm.value = elm.value.replace(/\s/gi, '');
}
 var frmvalidator = new Validator("myForm");
 frmvalidator.addValidation("fName","req", "Please enter your name");
 frmvalidator.addValidation("fName","alphabetic_space", "Your name contains an unrecognized character");
 frmvalidator.addValidation("Address","req", "Please enter a shipping address");
 frmvalidator.addValidation("Email","req", "Please enter your email address");
 frmvalidator.addValidation("Email","email", "Your email address is not formatted correctly");
 frmvalidator.addValidation("Shipping","dontselect=0", "Please select a shipping option");
 frmvalidator.addValidation("Card","dontselect=0", "Please select a payment method");
function updateValidator() {
	var payment = document.getElementById('Card').selectedIndex;
	if (payment == 5)
	{
		frmvalidator.clearAllValidations();
		frmvalidator.addValidation("fName","req", "Please enter your name");
		frmvalidator.addValidation("fName","alphabetic_space", "Your name contains an unrecognized character");
		frmvalidator.addValidation("Address","req", "Please enter a shipping address");
		frmvalidator.addValidation("Email","req", "Please enter your email address");
		frmvalidator.addValidation("Email","email", "Your email address is not formatted correctly");
		frmvalidator.addValidation("Shipping","dontselect=0", "Please select a shipping option");
		frmvalidator.addValidation("Card","dontselect=0", "Please select a payment method");
	}
	else if (payment == 4)
	{
		frmvalidator.clearAllValidations();
		frmvalidator.addValidation("fName","req", "Please enter your name");
		frmvalidator.addValidation("fName","alphabetic_space", "Your name contains an unrecognized character");
		frmvalidator.addValidation("Address","req", "Please enter a shipping address");
		frmvalidator.addValidation("Email","req", "Please enter your email address");
		frmvalidator.addValidation("Email","email", "Your email address is not formatted correctly");
		frmvalidator.addValidation("Shipping","dontselect=0", "Please select a shipping option");
		frmvalidator.addValidation("Card","dontselect=0", "Please select a payment method");
		frmvalidator.addValidation("Number","req", "Please enter your credit card number");
		frmvalidator.addValidation("Number","maxlen=16","The credit card number you entered is invalid");
		frmvalidator.addValidation("Number","minlen=12","The credit card number you entered is invalid");
		frmvalidator.addValidation("Number","numeric","Please enter only numbers in the credit card field");
		frmvalidator.addValidation("Month","req", "Please enter a complete expiration date");
		frmvalidator.addValidation("CVN","num", "The expiration month contains an unrecognized character");
		frmvalidator.addValidation("Month","maxlen=2", "Please enter a two digit expiration month");
		frmvalidator.addValidation("Month","minlen=2", "Please enter a two digit expiration month");
		frmvalidator.addValidation("Year","req", "Please enter a complete expiration date");
		frmvalidator.addValidation("CVN","num", "The expiration year contains an unrecognized character");
		frmvalidator.addValidation("Year","maxlen=4", "Please enter a four digit expiration year");
		frmvalidator.addValidation("Year","minlen=4", "Please enter a four digit expiration year");
		frmvalidator.addValidation("CVN","req", "Please enter the card's CVN number");
		frmvalidator.addValidation("CVN","num", "The CVN number contains an unrecognized character");
	}
	else if (payment == 3)
	{
		frmvalidator.clearAllValidations();
		frmvalidator.addValidation("fName","req", "Please enter your name");
		frmvalidator.addValidation("fName","alphabetic_space", "Your name contains an unrecognized character");
		frmvalidator.addValidation("Address","req", "Please enter a shipping address");
		frmvalidator.addValidation("Email","req", "Please enter your email address");
		frmvalidator.addValidation("Email","email", "Your email address is not formatted correctly");
		frmvalidator.addValidation("Shipping","dontselect=0", "Please select a shipping option");
		frmvalidator.addValidation("Card","dontselect=0", "Please select a payment method");
		frmvalidator.addValidation("Number","req", "Please enter your credit card number");
		frmvalidator.addValidation("Number","maxlen=16","The credit card number you entered is invalid");
		frmvalidator.addValidation("Number","minlen=12","The credit card number you entered is invalid");
		frmvalidator.addValidation("Number","numeric","Please enter only numbers in the credit card field");
		frmvalidator.addValidation("Month","req", "Please enter a complete expiration date");
		frmvalidator.addValidation("CVN","num", "The expiration month contains an unrecognized character");
		frmvalidator.addValidation("Month","maxlen=2", "Please enter a two digit expiration month");
		frmvalidator.addValidation("Month","minlen=2", "Please enter a two digit expiration month");
		frmvalidator.addValidation("Year","req", "Please enter a complete expiration date");
		frmvalidator.addValidation("CVN","num", "The expiration year contains an unrecognized character");
		frmvalidator.addValidation("Year","maxlen=4", "Please enter a four digit expiration year");
		frmvalidator.addValidation("Year","minlen=4", "Please enter a four digit expiration year");
		frmvalidator.addValidation("CVN","req", "Please enter the card's CVN number");
		frmvalidator.addValidation("CVN","num", "The CVN number contains an unrecognized character");
	}
	else if (payment == 2)
	{
		frmvalidator.clearAllValidations();
		frmvalidator.addValidation("fName","req", "Please enter your name");
		frmvalidator.addValidation("fName","alphabetic_space", "Your name contains an unrecognized character");
		frmvalidator.addValidation("Address","req", "Please enter a shipping address");
		frmvalidator.addValidation("Email","req", "Please enter your email address");
		frmvalidator.addValidation("Email","email", "Your email address is not formatted correctly");
		frmvalidator.addValidation("Shipping","dontselect=0", "Please select a shipping option");
		frmvalidator.addValidation("Card","dontselect=0", "Please select a payment method");
		frmvalidator.addValidation("Number","req", "Please enter your credit card number");
		frmvalidator.addValidation("Number","maxlen=16","The credit card number you entered is invalid");
		frmvalidator.addValidation("Number","minlen=12","The credit card number you entered is invalid");
		frmvalidator.addValidation("Number","numeric","Please enter only numbers in the credit card field");
		frmvalidator.addValidation("Month","req", "Please enter a complete expiration date");
		frmvalidator.addValidation("CVN","num", "The expiration month contains an unrecognized character");
		frmvalidator.addValidation("Month","maxlen=2", "Please enter a two digit expiration month");
		frmvalidator.addValidation("Month","minlen=2", "Please enter a two digit expiration month");
		frmvalidator.addValidation("Year","req", "Please enter a complete expiration date");
		frmvalidator.addValidation("CVN","num", "The expiration year contains an unrecognized character");
		frmvalidator.addValidation("Year","maxlen=4", "Please enter a four digit expiration year");
		frmvalidator.addValidation("Year","minlen=4", "Please enter a four digit expiration year");
		frmvalidator.addValidation("CVN","req", "Please enter the card's CVN number");
		frmvalidator.addValidation("CVN","num", "The CVN number contains an unrecognized character");
	}
	else if (payment == 1)
	{
		frmvalidator.clearAllValidations();
		frmvalidator.addValidation("fName","req", "Please enter your name");
		frmvalidator.addValidation("fName","alphabetic_space", "Your name contains an unrecognized character");
		frmvalidator.addValidation("Address","req", "Please enter a shipping address");
		frmvalidator.addValidation("Email","req", "Please enter your email address");
		frmvalidator.addValidation("Email","email", "Your email address is not formatted correctly");
		frmvalidator.addValidation("Shipping","dontselect=0", "Please select a shipping option");
		frmvalidator.addValidation("Card","dontselect=0", "Please select a payment method");
		frmvalidator.addValidation("Number","req", "Please enter your credit card number");
		frmvalidator.addValidation("Number","maxlen=16","The credit card number you entered is invalid");
		frmvalidator.addValidation("Number","minlen=12","The credit card number you entered is invalid");
		frmvalidator.addValidation("Number","numeric","Please enter only numbers in the credit card field");
		frmvalidator.addValidation("Month","req", "Please enter a complete expiration date");
		frmvalidator.addValidation("CVN","num", "The expiration month contains an unrecognized character");
		frmvalidator.addValidation("Month","maxlen=2", "Please enter a two digit expiration month");
		frmvalidator.addValidation("Month","minlen=2", "Please enter a two digit expiration month");
		frmvalidator.addValidation("Year","req", "Please enter a complete expiration date");
		frmvalidator.addValidation("CVN","num", "The expiration year contains an unrecognized character");
		frmvalidator.addValidation("Year","maxlen=4", "Please enter a four digit expiration year");
		frmvalidator.addValidation("Year","minlen=4", "Please enter a four digit expiration year");
		frmvalidator.addValidation("CVN","req", "Please enter the card's CVN number");
		frmvalidator.addValidation("CVN","num", "The CVN number contains an unrecognized character");
	}
}

function showBranch(branch){
	var objBranch = document.getElementById(branch).style;
	if(objBranch.display=="block")
	{
		objBranch.display="none";
	}
	else
	{
		objBranch.display="block";
	}
}

function clearDefault(el) {
  if (el.defaultValue==el.value) el.value = ""
}

</script>
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-1354660-5']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
</body>
</html>

