        <div class="main-panel">
          <!-- Navbar -->
          <nav class="navbar navbar-expand-lg navbar-transparent navbar-absolute fixed-top " id="navigation-example">
            <div class="container-fluid">
              <div class="navbar-wrapper">
                <div class="navbar-minimize">
                  <button id="minimizeSidebar" style="border: none;background: #fff;">
                    <i class="icon-menu7 visible-on-sidebar-regular"></i>
                    <i class="icon-cross2 visible-on-sidebar-mini"></i>
                  </button>
                </div>
                <a class="navbar-brand" href="#" id="pagetitle"></a>
              </div>
              <button class="navbar-toggler" type="button" data-toggle="collapse" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation" data-target="#navigation-example">
                <span class="sr-only">Toggle navigation</span>
                <span class="navbar-toggler-icon icon-bar"></span>
                <span class="navbar-toggler-icon icon-bar"></span>
                <span class="navbar-toggler-icon icon-bar"></span>
              </button>
              <div class="collapse navbar-collapse justify-content-end">
                <form id="form_search" name="navbar-form"  action="" method="POST" class="form-inline">
                <div class="input-group no-border">
                 <input type="text" value="" class="form-control" placeholder="Search..."  id="searchinput" />
                 <button type="button" class="btn btn-white btn-round btn-just-icon" id="searchbtn" type="submit">
                  <i class="icon-search4"></i>
                  <div class="ripple-container"></div>
                </button>
              </div>
            </form>
            <ul class="navbar-nav"> 
              <li class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation" style="display:none;">
                <span class="navbar-toggler-icon"></span>
              </li>
              <div class="collapse navbar-collapse" id="navbarNavDropdown" style="display:none;">
                <ul class="navbar-nav">
                  <li class="nav-item dropdown has-mega-menu" style="position:static;">
                    <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false"><i class="icon-grid5"></i></a>
                    <div class="dropdown-menu" style="width:100%;">
                      <div class="px-0 container">
                        <div class="col-md-12">
                          <div class="form-group">
                            <input type="text" class="form-control" placeholder="">
                          </div>
                        </div>
                        <div class="col-md-12">
                          <div class="row">

                          </div>
                        </div>
                      </div>
                    </div>
                  </li>
                </ul>
              </div>

              <li class="nav-item">
                <a class="nav-link" href="#" style="margin: 0 auto;" >
                  <i class="icon-bell2" style="font-size:20px"></i>                    
                </a>
              </li>
              <li class="nav-item upload-notify" style="display: none;">
                <a class="nav-link"  data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" href="#" style="margin: 0 auto;" >
                  <i class="icon-bell2" style="font-size:20px"></i>                    
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                  <div class="col-md-12">
                    <p>File Uploading...<span class="text-right">54%</span></p>
                    <div class="progress progress-line-info" id="progressupload" style=" height: 22px;">
                      <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100" style="width:0%; height: 21px;">
                        <span class="sr-only">0% Complete</span>
                      </div>
                    </div>
                  </div>
                </div>
              </li>
              <li class="nav-item" style="padding-left:20px">
               <img src="" class="img-responsive" style="height:40px;" />
             </li>
           </ul>
         </div>
       </div>
     </nav>


     <!-- End Navbar -->




     <div class="content">
       <div class="content">
         <div class="container-fluid">
           <div class="row" id="loadcontent">









