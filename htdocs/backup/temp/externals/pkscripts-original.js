function P7_StyleLoader(tS) { //v1.0 by PVII
 var tH='',tDoc='',tA='<LIN'+'K REL="stylesheet" HREF=',tB=' TYPE="text/css">';
 if(document.getElementsByTagName){var bb=document.getElementsByTagName("LINK");
 if(bb) {for(var k=0;k<bb.length;k++){if(bb[k].rel.toLowerCase()=="stylesheet"){
 var h=bb[k].href,x=h.lastIndexOf("/");if(x>0){tH=h.substring(0,x+1);}
 bb[k].disabled=true;tDoc=tA+'"'+ tH + tS + '"' +tB;
 document.write(tDoc);break;}}}}
}
P7_StyleLoader('w3csheet.css');

function P7_OpResizeFix(a) { //v1.1 by PVII
if(!window.opera){return;}if(!document.p7oprX){
 document.p7oprY=window.innerWidth;document.p7oprX=window.innerHeight;
 document.onmousemove=P7_OpResizeFix;
 }else{if(document.p7oprX){
  var k=document.p7oprX-window.innerHeight;
  var j=document.p7oprY - window.innerWidth;
  if(k>1 || j>1 || k<-1 || j<-1){
  document.p7oprY=window.innerWidth;document.p7oprX=window.innerHeight;
  location.reload();}}}
}
P7_OpResizeFix();

function P7_getWD(tDim){ //v2.1 by PVII
 var sh=0,sw=0,rt=0;if(window.innerWidth){sh=window.innerHeight;sw=window.innerWidth;
 }else if(document.body){sh=document.body.clientHeight;sw=document.body.clientWidth;
 if(document.body.offsetHeight==sh&&document.documentElement&&document.documentElement.clientHeight){
  sh=document.documentElement.clientHeight;}
 if(document.body.offsetWidth==sw&&document.documentElement&&document.documentElement.clientWidth) {
 sw=document.documentElement.clientWidth;}}rt=(tDim=="width")?sw:sh;return rt;
}

function P7_getLDims(el) { //v2.1 by PVII
 var x,ll=0,tl=0,hh=0,ww=0,wx,g,gg;
 if((g=MM_findObj(el))!=null){gg=(document.layers)?g:g.style;ll=parseInt(gg.left);tt=parseInt(gg.top);
 if(isNaN(ll)){if(g.currentStyle){ll=parseInt(g.currentStyle.left);}else if(document.defaultView){
 ll=parseInt(document.defaultView.getComputedStyle(g,"").getPropertyValue("left"));}if(isNaN(ll)){ll=0;}}
 if(isNaN(tt)){if(g.currentStyle){tt=parseInt(g.currentStyle.top);}else if(document.defaultView){
 tt=parseInt(document.defaultView.getComputedStyle(g,"").getPropertyValue("top"));}if(isNaN(ll)){tt=0;}}
 if(document.all||document.getElementById){ww=parseInt(g.offsetWidth);hh=parseInt(g.offsetHeight);
 if(!ww){ww=parseInt(g.style.pixelWidth);if(!ww){ww=parseInt(g.style.width);}}if(!hh){
 hh=parseInt(g.style.pixelHeight);}if(g.hasChildNodes){for(x=0;x<g.childNodes.length;x++){
 wx=parseInt(g.childNodes[x].offsetWidth);if(wx>ww){ww=wx;}}}}else if(document.layers){
 ww=parseInt(g.clip.width);hh=parseInt(g.clip.height);}}var aR=[ll,tt,hh,ww];return aR;
}

function P7_alignRR() { //v2.1 by PVII
 var i;if(!document.p7alignRR) {return;}if(document.layers){
 if(innerWidth!=document.p7RRiw || innerHeight!=document.p7RRih){location.reload();}
 }else{document.p7RR=1;for(i=0;i<p7aRR.length;i++){eval(p7aRR[i]);}document.p7RR=0;}
}

function P7_alignWD2() { //v2.1 by PVII
 var g,gg,lp=0,tp=0,aL,lh,lw,ww,wh,pa='px',args=P7_alignWD2.arguments;if(document.layers||window.opera){pa='';}
 for(var i=0;i<args.length;i+=4){if((g=MM_findObj(args[i]))!=null){gg=(document.layers)?g:g.style;
 mm=parseInt(args[i+1]);mr=parseInt(args[i+2]);ml=parseInt(args[i+3]);ww=parseInt(P7_getWD('width'));
 wh=parseInt(P7_getWD('height'));aL=P7_getLDims(args[i]);lh=aL[2];lw=aL[3];tp=aL[1];lp=aL[0];
 if(mm==1){lp=mr;}if(mm==2){lp=parseInt((ww-lw-mr)/2);if(lp<0){lp=0;}}if(mm==3){lp=ww-lw-mr;if(lp<ml){lp=ml;}}
 if(mm==4){tp=parseInt((wh-lh-mr)/2);if(tp<0){tp=0;}}if(mm==5){tp=parseInt((wh-lh)/2);lp=parseInt((ww-lw)/2);
 if((lp-mr)<=0){lp=mr;}if((tp-ml)<=0){tp=ml;}}if(mm==6){tp=wh-lh-mr;}gg.top=tp+pa;gg.left=lp+pa;
 if(!document.p7alignRR){p7aRR=new Array();document.p7alignRR=true;document.p7RR=0;
 if(document.layers){document.p7RRiw=innerWidth;document.p7RRih=innerHeight;}onresize=P7_alignRR;}
 if(document.p7RR==0){p7aRR[p7aRR.length]="P7_alignWD2('"+args[i]+"',"+mm+","+mr+","+ml+")";}}}
}

function P7_alignLL2(){ //v2.1 by PVII
 var g,gg,lp,tp,tW,tH,oV,oH,aL,aT,pa='px',args=P7_alignLL2.arguments;if(document.layers||window.opera){pa='';}
 for(var i=0;i<args.length;i+=5){if((g=MM_findObj(args[i]))!=null){gg=(document.layers)?g:g.style;
 oV=parseInt(args[i+3]);oH=parseInt(args[i+4]);aL = P7_getLDims(args[i]);aT = P7_getLDims(args[i+1]);
 lp=aL[0];tp=aL[1];switch (args[i+2]){case 'A':lp=aT[0]-aL[3]+oH;tp=aT[1]-aL[2]+oV;break;
 case 'B':lp=aT[0]+oH;tp=aT[1]-aL[2]+oV;break;case 'C':lp=aT[0]+aT[3]+oH;tp=aT[1]-aL[2]+oV;break;
 case 'D':lp=aT[0]-aL[3]+oH;tp=aT[1]+oV;break;case 'E':lp=aT[0]+aT[3]+oH;tp=aT[1]+oV;break;
 case 'F':lp=aT[0]-aL[3]+oH;tp=aT[1]+aT[2]+oV;break;case 'G':lp=aT[0]+oH;tp=aT[1]+aT[2]+oV;break;
 case 'H':lp=aT[0]+aT[3]+oH;tp=aT[1]+aT[2]+oV;break;}gg.left=lp+pa;gg.top=tp+pa;
 if(!document.p7alignRR){p7aRR=new Array();document.p7alignRR=true;document.p7RR=0;onresize=P7_alignRR;
 if(document.layers){document.p7RR=0;document.p7RRiw=innerWidth;document.p7RRih=innerHeight;}}if(document.p7RR==0){
 p7aRR[p7aRR.length]="P7_alignLL2('"+args[i]+"','"+args[i+1]+"','"+args[i+2]+"',"+oV+","+oH+")";}}}
}

function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}

function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}

function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_swapImage() { //v3.0
  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}

function P7_HScroller(el,dr,tx,ox,spd) { //v1.4 by PVII
 var g,gg,fr,sp,pa='',slw=true,m=false,w,ww,lx,rx;tx=parseInt(tx);
 if((g=MM_findObj(el))!=null){gg=(document.layers)?g:g.style;}else{return;}
 if(dr=="Stop"){if(g.toMove){clearTimeout(g.p7Magic);}g.toMove=false;}
 if((parseInt(navigator.appVersion)>4 || navigator.userAgent.indexOf("MSIE")>-1)&& !window.opera){pa="px";}
 if(navigator.userAgent.indexOf("NT")>-1 || navigator.userAgent.indexOf("Windows 2000")>-1){slw=false;}
 if(parseInt(navigator.appVersion)>4&&navigator.userAgent.indexOf("Mozilla")>-1){slw=true;}
 if(spd=="Slow"){sp=(slw)?2:1;fr=(slw)?40:30;}else if(spd=="Medium"){sp=(slw)?4:1;fr=(slw)?40:10;
 }else{sp=(slw)?8:4;fr=(slw)?40:10;}if(spd=="Warp"){sp=5000;}var xx = parseInt(gg.left);if(isNaN(xx)){
 if(g.currentStyle){xx=parseInt(g.currentStyle.left);}else if(document.defaultView){
 xx=parseInt(document.defaultView.getComputedStyle(g,"").getPropertyValue("left"));}else{xx=0;}}
 if(document.all || document.getElementById){w=parseInt(g.offsetWidth);if(!w){w=parseInt(g.style.pixelWidth);}
 if(g.hasChildNodes){for(wx=0;wx<g.childNodes.length;wx++){ww=parseInt(g.childNodes[wx].offsetWidth);
 if(ww>w){w=ww;}}}}else if(document.layers){w=parseInt(g.clip.width);}lx=tx-w+parseInt(ox);rx=tx;
 if(dr=="Right"){if(xx>lx){m=true;xx-=sp;if(xx<lx){xx=lx;}}}
 if(dr=="Left"){if(xx<rx){m=true;xx+=sp;if(xx>rx){xx=rx;}}}
 if(dr=="Reset"){gg.left=tx+pa;if(g.toMove){clearTimeout(g.p7Magic);}g.toMove=false;}
 if(m){gg.left=xx+pa;if(g.toMove){clearTimeout(g.p7Magic);}g.toMove=true;
  eval("g.p7Magic=setTimeout(\"P7_HScroller('"+el+"','"+dr+"',"+tx+","+ox+",'"+spd+"')\","+fr+")");
 }else{g.toMove=false;}
}
function P7_VScroller(el,dr,ty,oy,spd) { //v1.5 by PVII
 var g,gg,fr,sp,pa='',slw=true,m=false,h,ly;ty=parseInt(ty);
 if((g=MM_findObj(el))!=null){gg=(document.layers)?g:g.style;}else{return;}
 if(dr=="Stop"){if(g.toMove){clearTimeout(g.p7Magic);}g.toMove=false;}
 if((parseInt(navigator.appVersion)>4 || navigator.userAgent.indexOf("MSIE")>-1)&& !window.opera){pa="px";}
 if(navigator.userAgent.indexOf("NT")>-1 || navigator.userAgent.indexOf("Windows 2000")>-1){slw=false;}
 if(parseInt(navigator.appVersion)>4&&navigator.userAgent.indexOf("Mozilla")>-1){slw=true;}
 if(spd=="Slow"){sp=(slw)?2:1;fr=(slw)?40:30;}else if(spd=="Medium"){sp=(slw)?4:1;fr=(slw)?40:10;
 }else{sp=(slw)?8:4;fr=(slw)?40:10;}if(spd=="Warp"){sp=5000;}var yy=parseInt(gg.top);if(isNaN(yy)){
 if(g.currentStyle){yy=parseInt(g.currentStyle.top);}else if(document.defaultView){
 yy=parseInt(document.defaultView.getComputedStyle(g,"").getPropertyValue("top"));}else{yy=0;}}
 if(document.all || document.getElementById){h=parseInt(g.offsetHeight);
 if(!h){h=parseInt(g.style.pixelHeight);}
 }else if(document.layers){h=parseInt(g.clip.height);}ly=ty+parseInt(oy)-h;
 if(dr=="Down"){if(yy>ly){m=true;yy-=sp;if(yy<ly){yy=ly;}}}
 if(dr=="Up"){if(yy<ty){m=true;yy+=sp;if(yy>ty){yy=ty;}}}
 if(dr=="Reset"){gg.top=ty+pa;if(g.toMove){clearTimeout(g.p7Magic);}g.toMove=false;}
 if(m){gg.top=yy+pa;if(g.toMove){clearTimeout(g.p7Magic);}g.toMove=true;
  eval("g.p7Magic=setTimeout(\"P7_VScroller('"+el+"','"+dr+"',"+ty+","+oy+",'"+spd+"')\","+fr+")");
 }else{g.toMove=false;}
}
function P7_alignFooter(ly,ft,x,y) { //v1.0 by PVII
	var g,gg,d,xx,yy,h=0,pa='';x=parseInt(x);y=parseInt(y);
	if( (g=MM_findObj(ly))==null || (d=MM_findObj(ft))==null){return;}
	if((parseInt(navigator.appVersion)>4 || navigator.userAgent.indexOf("MSIE")>-1)&& navigator.userAgent.indexOf("Opera")==-1){pa="px";}
	if(document.all || document.getElementById){h=parseInt(g.offsetHeight);if(!h){h=parseInt(g.style.pixelHeight);}
 	}else if(document.layers){h=parseInt(g.clip.height);}gg=(document.layers)?g:g.style;
	xx=parseInt(gg.left)+x+pa;yy=parseInt(gg.top)+h+y+pa;dd=(document.layers)?d:d.style;dd.left=xx;dd.top=yy;
}

function P7_autoLayers() { //v1.2 by PVII
 var g,b,k,f,args=P7_autoLayers.arguments;
 var a = parseInt(args[0]);if(isNaN(a))a=0;
 if(!document.p7setc) {p7c=new Array();document.p7setc=true;
  for (var u=0;u<10;u++) {p7c[u] = new Array();}}
 for(k=0; k<p7c[a].length; k++) {
  if((g=MM_findObj(p7c[a][k]))!=null) {
   b=(document.layers)?g:g.style;b.visibility="hidden";}}
 for(k=1; k<args.length; k++) {
  if((g=MM_findObj(args[k])) != null) {
   b=(document.layers)?g:g.style;b.visibility="visible";f=false;
   for(j=0;j<p7c[a].length;j++) {
    if(args[k]==p7c[a][j]) {f=true;}}
  if(!f) {p7c[a][p7c[a].length++]=args[k];}}}
}
function P7_ShowPic(a,b) { //v1.8 by PVII
 var g,gg,d,dd,ic,im;if((g=MM_findObj(b))!=null){
 if(!document.P7ShowPic){document.P7ShowPic=true;}else{
 if((d=MM_findObj(document.P7SPlay))!=null){
  dd=(document.layers)?d:d.style;dd.visibility="hidden";}}
 document.P7SPlay=b;gg=(document.layers)?g:g.style;im=b+"im";
 if((ic=MM_findObj(im))!=null){ic.src=a;gg.visibility="visible";}}
}
function P7_Scrub(obj) { //v1.0 by PVII
 if(obj.blur){obj.blur();}
}
function P7AniMagic(el, x, y, a, b, c, s) { //v2.8 PVII
 var g,elo=el,f="",m=false,d="";x=parseInt(x);y=parseInt(y);
 var t = 'g.p7Magic = setTimeout("P7AniMagic(\''+elo+'\','; 
 if ((g=MM_findObj(el))!=null) {d=(document.layers)?g:g.style;}else{return;}
 if (parseInt(s)>0) {eval(t+x+','+y+','+a+','+b+','+c+',0)",' + s+')');return;}
 var xx=(parseInt(d.left))?parseInt(d.left):0;
 var yy=(parseInt(d.top))?parseInt(d.top):0;
 if(parseInt(c)==1) {x+=xx;y+=yy;m=true;c=0;}
 else if (c==2) {m=false;clearTimeout(g.p7Magic);}
 else {var i=parseInt(a);
  if (eval(g.moved)){clearTimeout(g.p7Magic);}
  if (xx<x){xx+=i;m=true;if(xx>x){xx=x;}}
  if (xx>x){xx-=i;m=true;if(xx<x){xx=x;}}
  if (yy<y){yy+=i;m=true;if(yy>y){yy=y;}}
  if (yy>y){yy-=i;m=true;if(yy<y){yy=y;}}}
 if (m){if((parseInt(navigator.appVersion)>4 || navigator.userAgent.indexOf("MSIE")>-1)&& navigator.userAgent.indexOf("Opera")==-1){
    xx+="px";yy+="px";}
  d.left=xx;d.top=yy;g.moved=true;eval(t+x+','+y+','+a+','+b+','+c+',0)",'+b+')');
  }else {g.moved=false;}
}
