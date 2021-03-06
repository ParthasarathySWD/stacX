<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <link rel="apple-touch-icon" sizes="76x76" href="assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <title>Forgot Password | StacX</title>
  <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
  <link href="<?php echo base_url(); ?>assets/css/material-dashboard.min.css" rel="stylesheet" />
  <link href="<?php echo base_url(); ?>assets/demo/demo.css" rel="stylesheet" />
  <link rel="stylesheet" type="text/css"  href="<?php echo base_url(); ?>assets/icon/css/ionicons.css" />
  <link href="<?php echo base_url(); ?>assets/css/icomoon.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <style>
  .off-canvas-sidebar .navbar .navbar-collapse .navbar-nav .nav-item .nav-link{
        font-weight: 400 !important;
  }
  .card .card-footer{
    display: block !important;
        margin: 0 15px 0px !important;     
  }
    .navbar.navbar-transparent{
     background-color: transparent !important; 
     box-shadow: none;
     border-bottom: none !important; 
   }
   .navbar{
    background-color: transparent !important;
  }
  .card-header-default{
    background: #f44336 !important;
    box-shadow: 0 4px 20px 0px rgba(0, 0, 0, 0.14), 0 7px 10px -5px rgba(30, 74, 233, 0.4) !important;
  }
  .pr-10{
    padding-right:6px;
  }
  .borderleft{
border-left: 1px solid #ddd !important;
  }
  .borderright{
    border-right: 1px solid #ddd !important;
  }
  .bordertop{
    border-top:1px solid #ddd !important;
  }
  .pd-20{
    padding: 5px
  }
  .mt-20{
margin-top:20px;
  }
  .font14{
    font-size: 18px;
  }

  .alert.alert-with-icon i[data-notify=icon]{
top :20px !important;
}
.ForgetPassword{
  background: #f44336 !important;
}

</style>

</head>

<body class="off-canvas-sidebar">
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-transparent navbar-absolute fixed-top text-white" id="navigation-example">
    <div class="container">
      <div class="navbar-wrapper">
        <a class="navbar-brand" href="#"><img src=""  class="img-responsive" style="filter: brightness(0) invert(1);height:40px"/></a>
      </div>
      <button class="navbar-toggler" type="button" data-toggle="collapse" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation" data-target="#navigation-example">
        <span class="sr-only">Toggle navigation</span>
        <span class="navbar-toggler-icon icon-bar"></span>
        <span class="navbar-toggler-icon icon-bar"></span>
        <span class="navbar-toggler-icon icon-bar"></span>
      </button>
      <div class="collapse navbar-collapse justify-content-end">
      </div>
    </div>
  </nav>      
  <!-- End Navbar -->
<div class="wrapper wrapper-full-page">
   <div class="page-header login-page header-filter" filter-color="black" style="background-image: url('<?php echo base_url(); ?>assets/img/bookstore-bg.jpg'); background-size: cover; background-position: top center;">
      <div class="container">
         <div class="col-lg-4 col-md-6 col-sm-6 ml-auto mr-auto">
            <form class="form" id="ForgetPassword">
               <div class="card card-login card-hidden">
                  <div class="card-header card-header-default text-center">
                     <h4 class="card-title">Forgot Password</h4>
                     <div class="social-line">
                     </div>
                  </div>
                  <div class="card-body ">
                    <div class="form-group">
                      <label for="loginid" class="bmd-label-floating">Login ID/Email</label>
                      <input type="text" class="form-control" id="loginid" name="loginid" />
                    </div>
                  </div>
                  <div class="card-footer justify-content-center">
                     <div class="text-center" style="display: block;margin-bottom:20px">
                        <button type="submit" class="btn btn-tumblr btn-round mt-10 ForgetPassword" >Next</button>
                     </div>
                  </div>
               </div>
            </form>
         </div>
      </div>
   </div>
</div>
<!--   Core JS Files   -->

<script src="<?php echo base_url();?>assets/js/core/jquery.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/js/core/popper.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/js/core/bootstrap-material-design.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/js/plugins/perfect-scrollbar.jquery.min.js"></script>
<!-- Plugin for the momentJs  -->
<script src="<?php echo base_url();?>assets/js/plugins/moment.min.js"></script>
<!--  Plugin for Sweet Alert -->
<script src="<?php echo base_url();?>assets/js/plugins/sweetalert2.js"></script>
<!--  Plugin for Tags, full documentation here: https://github.com/bootstrap-tagsinput/bootstrap-tagsinputs  -->
<script src="<?php echo base_url();?>assets/js/plugins/bootstrap-tagsinput.js"></script>
<!-- Plugin for Fileupload, full documentation here: http://www.jasny.net/bootstrap/javascript/#fileinput -->
<script src="<?php echo base_url();?>assets/js/plugins/jasny-bootstrap.min.js"></script>
<script src="<?php echo base_url();?>assets/js/plugins/arrive.min.js"></script>
<!--  Notifications Plugin    -->
<script src="<?php echo base_url();?>assets/js/plugins/bootstrap-notify.js"></script>
<!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
<script src="<?php echo base_url();?>assets/js/material-dashboard.min.js" type="text/javascript"></script>
<!-- Material Dashboard DEMO methods, don't include it in your project! -->
<script src="<?php echo base_url();?>assets/demo/demo.js"></script>
<script>
  $(document).ready(function() {
    $().ready(function() {
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


      // $("#Username").change(function(event){        
      //    event.stopPropagation();
      //   alert();
      // })

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

      $('.fixed-plugin .active-color span').click(function() {
        $full_page_background = $('.full-page-background');

        $(this).siblings().removeClass('active');
        $(this).addClass('active');

        var new_color = $(this).data('color');

        if ($sidebar.length != 0) {
          $sidebar.attr('data-color', new_color);
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
});
</script>
<script>
  $(document).ready(function() {
        
    demo.checkFullPageBackgroundImage();
    setTimeout(function() {
        
        $('.card').removeClass('card-hidden');
      }, 700);

        $('.ForgetPassword').on('click',function(e){


         e.preventDefault();

        var data = $('#ForgetPassword').serialize();

        $.ajax({
          url:'<?php echo base_url();?>Login/CheckLoginExist',
          cache:false,
          type:'POST',
          data:data,
          dataType:'json',
           beforeSend:function()
           {
            $('.ForgetPassword').attr('disabled',true); 
            $('button.ForgetPassword').html("PLEASE WAIT");
           },
          success:function(data)
          {
            console.log(data);
            if(data.validation_error == 2 || data.validation_error == 0)
            {
              $.notify(
              {
                icon:"icon-bell-check",
                message:data.message
              },
              {
                type:"danger",
                delay:1000 
              });
                  $.each(data, function(k, v) 
                  {
                    $('#'+k).closest('.form-group').removeClass('has-success').addClass('has-danger');
                    $('#'+k).addClass("is-invalid");;
                  });
                  $('.ForgetPassword').attr('disabled',false); 
                  $('.ForgetPassword').html('Next');
            }
            else
            {
                $.notify(
                {
                  icon:"icon-bell-check",
                  message:data.message
                },
                {
                  type:"success",
                  delay:1000 
                });
                setTimeout ("window.location.href='<?php echo base_url();?>Login/updatepasswordpage'", 3500);
                $('.ForgetPassword').attr('disabled',false); 
                $('.ForgetPassword').html('Next');

            }
          },
          error:function(jqXHR, textStatus, errorThrown)
         {
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
         } 

        });

    });

  });
</script>
</body>

</html>