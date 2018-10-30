


$(document).ready(function(){ 

  $(".select2picker").select2({
    tags: false,
    theme: "bootstrap",
  });

  $(".nav-item").hover(function(){
    if($(this).children("a").hasClass("disabled")){  
      $(this).css("cursor" , "not-allowed")   
    }
  });

  $("body").on("click" , "input[type='email']" , function(){
    var emailidvalid  = $(this).val();
    var regex=/^[a-zA-Z0-9\.\_]+\@@{1}[a-zA-Z0-9]+\.\w{2,4}$/;
    if(!regex.test(emailidvalid))
    {         
      $(this).closest(".form-group").addClass("has-danger");
    }else{        
     $(this).closest(".form-group").removeClass("has-danger");
   }
   if(emailidvalid == ""){
    $(this).closest(".form-group").removeClass("has-danger");
  }
});

  $("body").on("change" , "input[type='email']" , function(){
   var emailidvalid  = $(this).val();
   var regex=/^[a-zA-Z0-9\.\_]+\@@{1}[a-zA-Z0-9]+\.\w{2,4}$/;
   if(!regex.test(emailidvalid))
   {         
    $(this).closest(".form-group").addClass("has-danger");
  }else{        
   $(this).closest(".form-group").removeClass("has-danger");
 }  
 if(emailidvalid == ""){
  $(this).closest(".form-group").removeClass("has-danger");
}
});




  $('.perfectscrollbar').perfectScrollbar();
  $('.dataTables_scrollBody').perfectScrollbar();
  $('.modal-dialog .modal-content').perfectScrollbar();
  $(".multiselect-container").perfectScrollbar();

  

  $('.jq-dte-day').attr("maxLength","2");
  $('.jq-dte-month').attr("maxLength","2");
  $('.jq-dte-year').attr("maxLength","4");
  $(".jq-dte-day").keydown(function(){
    if ((event.keyCode >= 65) &&  (event.keyCode <= 90) ||  (event.keyCode >=106 && event.keyCode <=109) || (event.keyCode >= 111) && (event.keyCode < 190) || (event.keyCode > 190) ) {
     event.preventDefault(); 
   } 
 });
  $(".jq-dte-month").keydown(function(){
    if ((event.keyCode >= 65) &&  (event.keyCode <= 90) ||  (event.keyCode >=106 && event.keyCode <=109) || (event.keyCode >= 111) && (event.keyCode < 190) || (event.keyCode > 190) ) {
     event.preventDefault(); 
   } 
 });
  $(".jq-dte-year").keydown(function(){
    if ((event.keyCode >= 65) &&  (event.keyCode <= 90) ||  (event.keyCode >=106 && event.keyCode <=109) || (event.keyCode >= 111) && (event.keyCode < 190) || (event.keyCode > 190) ) {
     event.preventDefault(); 
   } 
 });
  currencyformat();
  calldatetmask();   

  $('.contactnum').mask('(999) 999-9999');
  $("body").on("keyup" , ".contactnum" , function(e){     
   if(46==e.keyCode || 8==e.keyCode || 9==e.keyCode){
    var $this = $(this);
    if($this.val() == "(___)___-____")
      $this.val("");            
  }
});

  $("body").on("blur" , ".currency" , function(e){             
    var ele=$(this);
    var val = $(this).val();               
    if(val==='0.00' || val==='0' || val==='$0.00'){               
      $(this).val('0.00');
      $(this).parent().removeClass('is-dirty');
    }
    if($(this).val()!= 0){
      $(this).val($(this).val().split(" ").join(""));
      ele.formatCurrency();
    }
  });
  $( ".currency" ).trigger("blur");
  var keyDown = false, ctrl = 17, vKey = 86, Vkey = 118, Vdown=46;

  $("body").on("keypress" , ".currency", function (e) {
    if (!e) var e = window.event;
    if (e.keyCode > 0 && e.which == 0) return true;
    if (e.keyCode)    code = e.keyCode;
    else if (e.which) code = e.which;
    var character = String.fromCharCode(code);          
    if (character == '\b' || character == ' ' || character == '\t') return true;

    if ((code == vKey || code == Vkey || code == Vdown))  {                
     if (e.keyCode === 46 && this.value.split('.').length === 2) {
      return false   
    }else{
      return (character) 
    }
  }
  else return (/[0-9]$/.test(character));
});


  $('.currency').keydown(function (e) {
    if (e.keyCode == ctrl) keyDown = true;
  }).keyup(function (e) {
    if (e.keyCode == ctrl) keyDown = false;
  });



    // Sidebar
    $sidebar = $('.sidebar');
    $sidebar_img_container = $sidebar.find('.sidebar-background');
    $full_page = $('.full-page');
    $sidebar_responsive = $('body > .navbar-collapse');
    window_width = $(window).width();
    fixed_plugin_open = $('.sidebar .sidebar-wrapper .nav li.active a p').html();
    if (window_width > 767 && fixed_plugin_open == 'Dashboard') {
      if ($('.fixed-plugin .dropdown').hasClass('show-dropdown')) {
        $('.fixed-plugin .dropdown').addClass('open');
      }
    }
    $('.fixed-plugin a').click(function(event) {
          // Alex if we click on switch, stop propagation of the event, so the dropdown will not be hide, otherwise we set the  section active
          if ($(this).hasClass('switch-trigger')) {
            if (event.stopPropagation) {
              event.stopPropagation();
            } else if (window.event) {
              window.event.cancelBubble = true;
            }
          }
        });

    $("body").on("change" , "select.select2picker" , function(){
      var sval  =  $(this).val();
      if(sval  == ""){
        $(this).closest(".form-group").removeClass("is-filled");
      }else{
        $(this).closest(".form-group").addClass("is-filled");
      }
    })


    $('.fixed-plugin .active-color span').click(function() {
      $full_page_background = $('.full-page-background');
      $(this).siblings().removeClass('active');
      $(this).addClass('active');
      var new_color = $(this).data('color');
      if ($sidebar.length != 0) {
        $sidebar.attr('data-color', new_color);
        $("#headers").attr("class" , "");
        $("#headers").addClass(new_color);
        
      }
      if ($full_page.length != 0) {
        $full_page.attr('filter-color', new_color);
      }
      if ($sidebar_responsive.length != 0) {
        $sidebar_responsive.attr('data-color', new_color);
      }
    });

    $('.fixed-plugin .background-color .badge').click(function() {
      $(this).siblings().removeClass('active');
      $(this).addClass('active');
      var new_color = $(this).data('background-color');
      if ($sidebar.length != 0) {
        $sidebar.attr('data-background-color', new_color);
      }
    });

    $('.fixed-plugin .img-holder').click(function() {
      $full_page_background = $('.full-page-background');
      $(this).parent('li').siblings().removeClass('active');
      $(this).parent('li').addClass('active');
      var new_image = $(this).find("img").attr('src');
      if ($sidebar_img_container.length != 0 && $('.switch-sidebar-image input:checked').length != 0) {
        $sidebar_img_container.fadeOut('fast', function() {
          $sidebar_img_container.css('background-image', 'url("' + new_image + '")');
          $sidebar_img_container.fadeIn('fast');
        });
      }

      if ($full_page_background.length != 0 && $('.switch-sidebar-image input:checked').length != 0) {
        var new_image_full_page = $('.fixed-plugin li.active .img-holder').find('img').data('src');
        $full_page_background.fadeOut('fast', function() {
          $full_page_background.css('background-image', 'url("' + new_image_full_page + '")');
          $full_page_background.fadeIn('fast');
        });
      }

      if ($('.switch-sidebar-image input:checked').length == 0) {
        var new_image = $('.fixed-plugin li.active .img-holder').find("img").attr('src');
        var new_image_full_page = $('.fixed-plugin li.active .img-holder').find('img').data('src');
        $sidebar_img_container.css('background-image', 'url("' + new_image + '")');
        $full_page_background.css('background-image', 'url("' + new_image_full_page + '")');
      }
      if ($sidebar_responsive.length != 0) {
        $sidebar_responsive.css('background-image', 'url("' + new_image + '")');
      }
    });

    $('.switch-sidebar-image input').change(function() {
      $full_page_background = $('.full-page-background');
      $input = $(this);
      if ($input.is(':checked')) {
        if ($sidebar_img_container.length != 0) {
          $sidebar_img_container.fadeIn('fast');
          $sidebar.attr('data-image', '#');
        }
        if ($full_page_background.length != 0) {
          $full_page_background.fadeIn('fast');
          $full_page.attr('data-image', '#');
        }
        background_image = true;
      } else {
        if ($sidebar_img_container.length != 0) {
          $sidebar.removeAttr('data-image');
          $sidebar_img_container.fadeOut('fast');
        }
        if ($full_page_background.length != 0) {
          $full_page.removeAttr('data-image', '#');
          $full_page_background.fadeOut('fast');
        }
        background_image = false;
      }
    });

    $('.switch-sidebar-mini input').change(function() {
      $body = $('body');
      $input = $(this);
      if (md.misc.sidebar_mini_active == true) {
        $('body').removeClass('sidebar-mini');
        md.misc.sidebar_mini_active = false;
        $('.sidebar .sidebar-wrapper, .main-panel').perfectScrollbar();
      } else {
        $('.sidebar .sidebar-wrapper, .main-panel').perfectScrollbar('destroy');
        setTimeout(function() {
          $('body').addClass('sidebar-mini');
          md.misc.sidebar_mini_active = true;
        }, 300);
      }

          // we simulate the window Resize so the charts will get updated in realtime.
          var simulateWindowResize = setInterval(function() {
            window.dispatchEvent(new Event('resize'));
          }, 180);

          // we stop the simulation of Window Resize after the animations are completed
          setTimeout(function() {
            clearInterval(simulateWindowResize);
          }, 1000);
        });

  });



$(".abstractordetails").click(function(e){
  e.preventDefault();

  var abstractoruid=$(this).closest('tr').attr('data-id');

  if (abstractoruid=='' || typeof abstractoruid =='undefined') {
    swal({
      title: "<i class='icon-warning iconwarning'></i>", 
      html: "<p>Invalid Request</p>",
      confirmButtonClass: "btn btn-success",
      allowOutsideClick: true,
      width: '300px',
      buttonsStyling: false
    }).catch(swal.noop);
    return;
  }
  $.ajax({
    type: "POST",
    url: '<?php echo base_url();?>Customer/GetAbstractorDetailsView',
    data:{"AbstractorUID":abstractoruid},
    success: function(data)
    {
      $('#abstractormodal').remove();
      $('body').append(data);
      $('#abstractormodal').modal('show');
    },
    error: function(jqXHR, textStatus, errorThrown){

    }
  });

  $('#abstractordts').modal('toggle');
})

$(document).ready(function(){ 
  md.initFormExtendedDatetimepickers();
});


$(document).ready(function() {
  ScrolltoTop_init();
  select_mdl();


  $(".popoveroption").click(function(){
  var ttop = $(".popover").css("top");
  getscrolltop();
  })
});

 function getscrolltop(){
    var ttop = $(".popover").css("top");
    // alert(ttop);
 }

function ScrolltoTop_init(){
  $('.scrollup').remove();
  var button = $('<a href="javascript:void(0);" class="scrollup" style="display: none;"><i class="icon-arrow-up8"></i></a>');
  button.appendTo("body");

  const container = document.querySelector('.main-panel');
  const ps = $('.main-panel').perfectScrollbar();
  var button = document.querySelector('.scrollup');
  button.addEventListener("click", ScrolltoTop);

  container.addEventListener('ps-scroll-down', function () {
    $('.scrollup').show();
    // const ps =  document.querySelector('.main-panel');
    // var psY = ps.querySelector('.ps-scrollbar-y');      
    // var topposition = psY.offsetTop;   
    // var dd  = $(".popover").css("top");
    // var avoid = "px";
    // var abc = dd.replace(avoid, '');
    //alert(abc);
    // var newtop =  parseInt(abc) - parseInt(topposition);
    // //alert(newtop);
    // $(".popover").css("top" , newtop);  
  });
  container.addEventListener('ps-y-reach-start', function () {
    $('.scrollup').hide(); 
  });
  container.addEventListener('ps-scroll-up', function () {   
  popovertop();  
  });
}


function currencyformat(){
  $(".currency").trigger("blur");
}


function popovertop(){
    const ps =  document.querySelector('.main-panel');
    var psY = ps.querySelector('.ps-scrollbar-y');      
    var topposition = psY.offsetTop;   
  //  alert(topposition);
    var dd  = $(".popover").css("top");
    var avoid = "px";
    // var abc=dd.replace(avoid, '');
    // var newtop = parseInt(abc) + parseInt(topposition);
    // $(".popover").css("top" , newtop);
    // console.log(newtop);
 
}
// function getposition(){
//  var topval =$("body .ps-scrollbar-y-rail").attr("style");
//  alert(topval);
// }


function ScrolltoTop() {
  $('.main-panel').animate({ scrollTop: 0 }, 600);
  $('.main-panel').scrollTop(0).perfectScrollbar('update');
}


function calldatetmask(){
  $(".date-entry1").datetextentry();
}
/*FOR SELECT2 COMPATABILITY*/

function select_mdl() {
  var $eventSelect = $(".select2picker");
  $eventSelect.on("select2:opening", function () { 
    $(this).closest('.form-group').addClass("is-focused is-filled");
  });

  $eventSelect.on("select2:close", function () {
    $(this).closest('.form-group').removeClass("is-focused");
    var selected_value = $(this).val();
    if (selected_value==0 || selected_value=='' || selected_value==undefined) {
      $(this).closest('.form-group').removeClass("is-filled");
    } else {
      $(this).closest('.form-group').addClass("is-filled");
      if($(this).next().find('span.select2-selection').hasClass('errordisplay')){
        $(this).removeClass('is-invalid').closest('.form-group').removeClass("has-danger");
        console.log($(this))
        $(this).next().find('span.select2-selection').removeClass('errordisplay');
      }
    }
  });

  $eventSelect.each(function(){
    var selected_value = $(this).val();
    if (selected_value==0 || selected_value=='' || selected_value==undefined) {
      $(this).closest('.form-group').removeClass("is-filled");
    } else {
      $(this).closest('.form-group').addClass("is-filled");
    }
  });

  var $eventSelectTag = $(".mdl-select2-tags");
  $eventSelectTag.on("select2:opening", function () { 
    $(this).closest('.form-group').addClass("is-focused is-filled");
  });

  $eventSelectTag.on("select2:close", function () {
    $(".form-group").removeClass("is-focused");
    var selected_tag = $(this).closest('.form-group').find('.select2-selection__choice').hasClass('select2-selection__choice');
    if (selected_tag) {
      $(this).closest('.form-group').addClass("is-filled");
    } else {
      $(this).closest('.form-group').removeClass("is-filled");
    }
  });

  $eventSelectTag.on("change", function(){
    if ($('.select2-selection__rendered li').hasClass('select2-selection__choice')) {
      $(this).closest('.form-group').addClass("is-filled");
    } else {
      $(this).closest('.form-group').removeClass("is-filled");
    }
  });

  $eventSelectTag.each(function(){
    var selected_tag = $(this).closest('.form-group').find('.select2-selection__choice').hasClass('select2-selection__choice');
    if (selected_tag) {
      $(this).closest('.form-group').addClass("is-filled");
    } else {
      $(this).closest('.form-group').removeClass("is-filled");
    }
  });

};




$( document ).ajaxComplete(function() {
  select_mdl();
});

/*FOR SELECT2 COMPATABILITY*/

$(document).on('select2:close', '.select2picker', function(event) {
  event.preventDefault();
  /* Act on the event */
  $(this).closest('.form-group').removeClass("is-focused");
  var selected_value = $(this).val();
  if (selected_value==0 || selected_value=='' || selected_value==undefined) {
    $(this).closest('.form-group').removeClass("is-filled");
  } else {
    $(this).closest('.form-group').addClass("is-filled");
    $(this).removeClass('is-invalid').closest('.form-group').removeClass("has-danger");
    if($(this).next().find('span.select2-selection').hasClass('errordisplay')){
      $(this).next().find('span.select2-selection').removeClass('errordisplay');
    }
  }
});

$(document).ready(function() {
 $(document).on('blur', 'input[type=text]', function(event) {
  event.preventDefault();
  /* Act on the event */
  var ele = $(event.currentTarget);
  if(ele.val().length != 0){
    ele.removeClass('is-invalid').closest('.form-group').removeClass("has-danger");
  }
});

 $(document).on('blur', '.check-invalid', function(event) {
  event.preventDefault();
  /* Act on the event */
  var ele = $(event.currentTarget);
  if(ele.val().length != 0){
    ele.removeClass('is-invalid').closest('.form-group').removeClass("has-danger");
  }
});
});



function bytesToSize(bytes) {
 var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
 if (bytes == 0) return '0 Byte';
 var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
 return (bytes / Math.pow(1024, i)).toFixed(2) + ' ' + sizes[i];
};
function findParentForm(elem){ 
  var parent = elem.parentNode; 
  if(parent && parent.tagName != 'FORM'){
    parent = findParentForm(parent);
  }
  return parent;
}

function getParentForm( elem )
{
  var parentForm = findParentForm(elem);
  if(parentForm){
    return parentForm;
  }else{
    alert("unable to locate parent Form");
  }

}

function findParentElement(elem, parentClass){ 
  var parent = elem.parentNode; 
  var classlist = parent.classList;
  var ispresent=$.inArray(parentClass, classlist);
  if(parent && ispresent ==-1){
    parent = findParentElement(parent, parentClass);
  }
  return parent;
}

function getParentByClass(elem, parentClass) {
  var parentElement=findParentElement(elem, parentClass);
  if (parentElement) {
    return parentElement;
  }
}

function calcTime(city, offset) {
    // create Date object for current location
    var d = new Date();

    // convert to msec
    // subtract local time zone offset
    // get UTC time in msec
    var utc = d.getTime() + (d.getTimezoneOffset() * 60000);

    // create new Date object for different city
    // using supplied offset
    var nd = new Date(utc + (3600000*offset));

    return nd.toISOString().slice(0,10);
    // return time as a string
    return "The local time for city "+ city +" is "+ nd.toLocaleString();
  }

// alert(calcTime('Caribbean', '-5'));


function ResetProgress(progress) {
  $(progress).width(0 + '%');
  $(progress).text('');
  $(progress).parent('.progress').hide(); 
  $(progress).find('span').text(''); 
}


$(document).off('click','.Reports').on('click','.Reports',function(){
  $("#Reports").slideToggle();
});
