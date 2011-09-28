// Preload some stuff
var preloaded = new Array();
function preload_images() {
    for (var i = 0; i < arguments.length; i++){
        preloaded[i] = document.createElement('img');
        preloaded[i].setAttribute('src',arguments[i]);
    };
};
preload_images(
	'http://assets0.maskitalia.com/images/assets/pk_pgbg_long.jpg',
	'http://assets0.maskitalia.com/images/assets/frame.png',
	'http://assets0.maskitalia.com/images/assets/maskitalia-mainmenu-sprite-v2.png',
	'http://assets1.maskitalia.com/images/assets/maskitalia-nav-sprite.png'
);

