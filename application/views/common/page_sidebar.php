
<body>
<svg class="d2tspinner-circular bodyspinner_svg" viewBox="25 25 50 50" style="width:50px;z-index: 999999;"><circle class="d2tspinner-path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10"/></svg>
<div class='bodyoverlaydiv'></div>

            
  <div class="wrapper">
    <div class="sidebar" data-color="rose" data-background-color="black" data-image=""> 
      <div class="logo">
        <a href="#" class="simple-text logo-mini" style="display:none;">
         D2T
       </a>
       <a href="#" class="simple-text logo-normal text-center">
         <img src="" class="img-responsive" style="height:30px;filter: brightness(0) invert(1);"/>
       </a></div>
       <div class="sidebar-wrapper">
        <div class="user">
          <div class="photo">
            <img src="<?php echo base_url(); ?>assets/img/faces/avatar.jpg" />
          </div>
          <div class="user-info">
            <a data-toggle="collapse" href="#collapseExample" class="username">
              <span>
               TECH TEAM1
               <b class="caret"></b>
             </span>
           </a>
           <div class="collapse" id="collapseExample">
            <ul class="nav" id="leftsidebarmenu">
              <li class="nav-item">
                <a class="nav-link ajaxload" href="#">
                  <span class="sidebar-mini"> P </span>
                  <span class="sidebar-normal"> Profile </span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link ajaxload" href="#">
                  <span class="sidebar-mini"> H </span>
                  <span class="sidebar-normal"> Help </span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link ajaxload" href="#">
                  <span class="sidebar-mini"> CP </span>
                  <span class="sidebar-normal"> Change Password </span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="<?php echo base_url(); ?>Login/Logout">
                  <span class="sidebar-mini"> L </span>
                  <span class="sidebar-normal"> Logout </span>
                </a>
              </li>
            </ul>
          </div>
        </div>
      </div>
      <ul class="nav"> 
          <li class="nav-item active">
          <a class="nav-link ajaxload" href="<?php echo base_url(); ?>">
          <i class="icon-city"></i>
          <p> Dashboard </p>
          </a>
          </li>
          <li class="nav-item">
          <a class="nav-link ajaxload" data-session="<?php echo $this->session->userdata('UserUID'); ?>" href="<?php echo base_url(); ?>Orderentry"> 
          <i class="icon-file-text"></i> 
          <p> Order Entry </p>
          </a>
          </li>
          <li class="nav-item">
          <a class="nav-link ajaxload" href="<?php echo base_url(); ?>Orderassignment">
          <i class="icon-pencil5"></i>
          <p>Order Assignment</p>
          </a>
          </li>
          <li class="nav-item">
          <a class="nav-link ajaxload" href="<?php echo base_url(); ?>MyOrders">
          <i class="icon-pencil5"></i>
          <p>My Orders</p>
          </a>
          </li>
          <li class="nav-item">
          <a class="nav-link ajaxload" href="<?php echo base_url(); ?>Exceptionorders">
          <i class="icon-reload-alt"></i>
          <p>Exception Orders</p>
          </a>
          </li>
       </ul>                
  </div>
  </div>


<!-- Sidebar -->
<script type="text/javascript">
  $(document).on('click','.Reports',function(){
    $("#Reports").slideToggle();
  });
</script>