<!DOCTYPE html>
<html>
<head>
  <title>Access Denied</title>

  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons">
  <link href="http://cinbox.avanzegroup.com/assets/css/style.css" rel="stylesheet">
  <link href="http://cinbox.avanzegroup.com/assets/css/material-dashboard.min40a0.css?v=2.0.2" rel="stylesheet">
  <style>
  /* ================= GENERAL=================*/
  .errorpage {
    max-width: 650px;
    margin: 5% auto auto auto;
  }
  svg{
    width: 40%;
    fill: #444243;
    margin: 3%;
  } 
  /* ================== LAYOUT SPECIFIC =================*/
  .errorpage #wrapper {
    background: #fff;
    -webkit-border-radius: 0.4em;
    border-radius: 0.4em;
    padding: 0;
    -webkit-box-shadow: 0 0 25px 0 #d5d9e0;
    box-shadow: 0 0 25px 0 #d5d9e0;
    border: 1px solid #d0d7df;
    overflow: hidden;
  }
  .errorpage #wrapper article h1, #wrapper article h4 {
    text-align: center;
  }
  .errorpage header {
    display: block;
    background: #fff;
    padding: 1.5em 1.5em 1.5em 1.5em;
    margin: 0em 0em 2em;
    border-bottom: 1px solid rgba(0,0,0,0.1);
    -webkit-border-radius: 0.4em 0.4em 0 0;
    border-radius: 0.4em 0.4em 0 0;
    -webkit-box-shadow: inset 0 0 0 1px rgba(255,255,255,1);
    box-shadow: inset 0 0 0 1px rgba(255,255,255,1);
  }
  .errorpage header h3 {
    color: #9ea7b3;
    font-size: 1.5em;
    margin: 0;
  }
  .errorpage header h3 a {
    line-height: 1.3em;
  } 
  .errorpage .tb-content {
    margin: 0;
    padding: 0;
    overflow: hidden;
  }
  .errorpage .tb-content .box {
    padding: 1em 1.3em 0 1.3em;
  }
  .errorpage article {
    text-align: center;
    padding: 0;
  }
  @media screen and (max-width : 320px) {
    .errorpage .tabs li a {
      font-size: 1em;
    }
    .errorpage .tabs li a i {
      font-size: 1em;
      display: block;
    }
    .errorpage svg {
      width: 70%!important;
    }
  }
  @media only screen and (max-width : 768px) {
    .errorpage header h3.brand {
      text-align: center;
      margin-bottom: 1em;
    }
    .errorpage header ul.list-inline {
      text-align: center;
    }
    vsvg {
      width: 55%;
    }
  }
  @media screen and (max-width : 400px) {
    .errorpage  .tabs li a i {
      display: block;
    }
  }
</style>
</head>
<div class="container errorpage mt-30">
  <div id="wrapper">
    <header class="clearfix">
      <div class="row">
        <div class="col-md-12 col-sm-12 text-center">
          <h3><b>Access denied !!!</b></h3>
        </div>    
      </div>     
    </header>
    <article> 

      <!-- Tab panes -->
      <div class="tab-content-wrapper">
        <div class="tb-content active" id="home">
          <div class="box"><span class="section-icon"><i class="icon-unlink"></i></span>
            <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
            viewBox="0 0 53 53" style="enable-background:new 0 0 53 53;" xml:space="preserve">
            <g>
              <g>
                <path d="M45.707,10.074l-9.794-9.782C35.726,0.105,35.471,0,35.206,0H8C7.447,0,7,0.447,7,1v51c0,0.553,0.447,1,1,1h37
                c0.553,0,1-0.447,1-1V10.782C46,10.517,45.895,10.263,45.707,10.074z M42.586,10H36V3.414L42.586,10z M9,51V2h25v9
                c0,0.553,0.447,1,1,1h9v39H9z"/>
                <path d="M26.5,16C19.056,16,13,22.056,13,29.5S19.056,43,26.5,43S40,36.944,40,29.5S33.944,16,26.5,16z M26.5,18
                c2.892,0,5.532,1.082,7.555,2.851L17.851,37.055C16.082,35.032,15,32.393,15,29.5C15,23.159,20.159,18,26.5,18z M26.5,41
                c-2.729,0-5.237-0.96-7.211-2.555l16.156-16.156C37.04,24.263,38,26.771,38,29.5C38,35.841,32.841,41,26.5,41z"/>
              </g> 
            </svg>
            <h4><b>Sorry! - You may not have the permissions!</b></h4>
            <p>The page you are looking for without permission. <br>Contact your organization administrator to request access the page. </p>
            <a href="javascript:javascript:history.go(-1);" class="btn btn-rose" style="margin: -3px auto 20px;">Go Back</a>
          </div>  
          <!--end-of-tab-content-->  
        </div>
        <!--end-of-tab-content-->       
      </article>
    </div>
  </div>