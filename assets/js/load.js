/*----------------------------*/
/*----------------------------*/
/*USED USING HTML5 STATE API*/
/*LOAD WITHOUT PAGE REFRESH*/

var load_success = true;

if(window.history.state === null){
	var stateObj = { 'url': window.location.pathname, 'scrolly': 0}
	window.history.pushState(stateObj, "initial", window.location.pathname)
}


window.onpopstate = function(event) {
	if(event.state != null){
		ajaxtrigger(event.state.url, event.state.scrolly)
	}
};


$('html').on("click",".ajaxload",function(e){ 
	e.preventDefault(); /*cancel click*/

	if(window.history.state != null){
		addbodyspinner();
		var url = $(this).attr('href');

  	NProgress.start();
		var load_success = triggerpage(url);
		if(load_success){
			if ($(this).parent().hasClass("nav-item")) {
				$('.nav-item').removeClass('active');
				$(this).parent().addClass('active');
			}
		}
	}
});



function triggerpage(url){

	if ( typeof async == 'undefined' ) async=true;
	if ( async ) {
		/*update this state first*/
		var stateObj = { 'url': history.state.url, 'scrolly': $(window).scrollTop()}
		window.history.replaceState(stateObj, "page", window.location.pathname)
		/*next state*/
		var stateObj = { 'url': url };
		window.history.pushState(stateObj, "page", url);
		load_success = ajaxtrigger(url);
		return load_success;
	}
}

function ajaxtrigger(url, interval){

	var interval = setInterval(function() { NProgress.inc(); }, 1000);  

	/*loading page*/
	$.ajax({
		type: "GET",
		url: url,
		dataType:'html',
		beforeSend: function(){
			NProgress.set(0.6);
		},
		success: function(data)
		{
			NProgress.set(0.9);
			$('#loadcontent').html(data);
			load_success = true;
			$(document).ready(function() {
				clearInterval(interval);
				NProgress.done();
				$(".select2picker").select2({theme: "bootstrap"});
				ScrolltoTop_init();
				select_mdl();
				removebodyspinner();
			});	
		},
		error: function(jqXHR){
			console.log(jqXHR);
			clearInterval(interval);
			NProgress.done();
			removebodyspinner();
			load_success = false;
		},
	});


	return load_success;
	/*loading page*/
}


/*LOAD WITHOUT PAGE REFRESH*/

/*----------------------------*/
/*----------------------------*/
/*USED USING HTML5 STATE API*/


var interval = 0;

$(document).on('unload', function() {
	var interval = setInterval(function() { NProgress.inc(); }, 1000);  
})
$(document).off('ajaxStart').on('ajaxStart', function(event) {
	NProgress.start();
});



$(document).off('ajaxStop').on('ajaxStop', function() {
	clearInterval(interval);
	NProgress.done();
});

$(document).ready(function(){
    clearInterval(interval);
    NProgress.done();
    
});


/*FUNCTION FOR SPINNER*/
var spinner = '<svg class="d2tspinner-circular spinner_svg" viewBox="25 25 50 50" style="width:50px;z-index: 10000;"><circle class="d2tspinner-path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10"/></svg>';
var overlaydiv  = '<div class="overlay d2tspinner-overlay"></div>';

function addcardspinner(ele){
  $(ele).closest(".card").append(spinner); 
  $(ele).closest(".card").append(overlaydiv);
  $(ele).closest(".card .btn").addClass("reduceindex");
}

function removecardspinner(ele){
	$('.spinner_svg').remove();
	$('.d2tspinner-overlay').remove();
  $(ele).closest(".card .btn").removeClass("reduceindex");
}


var spinnerbody = '<svg class="d2tspinner-circular bodyspinner_svg" viewBox="25 25 50 50" style="width:50px;z-index: 999999;"><circle class="d2tspinner-path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10"/></svg>'; 
var bodyoverlaydiv = "<div class='bodyoverlaydiv'></div>"; 

function addbodyspinner(){
	$("body").append(spinnerbody);
	$("body").append(bodyoverlaydiv);
}

function removebodyspinner(){
	$('.bodyspinner_svg').remove();
	$('.bodyoverlaydiv').remove();
}

/*FUNCTION FOR SPINNER*/