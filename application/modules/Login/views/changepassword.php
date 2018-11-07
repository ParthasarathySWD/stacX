<style type="text/css">
  /* Style all input fields */
input {
    width: 100%;
    padding: 12px;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
    margin-top: 6px;
    margin-bottom: 16px;
}

/* Style the submit button */
input[type=submit] {
    background-color: #4CAF50;
    color: white;
}

/* Style the container for inputs */


/* The message box is shown when the user clicks on the password field */
#message {
    display:none;
    background: #f1f1f1;
    color: #000;
    position: relative;
    padding: 20px;
    margin-top: 10px;
}

#message p {
    padding-top: 5px;
    font-size: 12px;
    margin-bottom: 0px;
}

/* Add a green text color and a checkmark when the requirements are right */
.valid {
    color: green;
}

.valid:before {
    position: relative;
    left: -5px;
    content: "\2714";
}

/* Add a red text color and an "x" when the requirements are wrong */
.invalid {
    color: red;
    font-size: 12px;
}

.invalid:before {
    position: relative;
    left: -5px;
    content: "\2718";
}

window.scrollBy({ 
  top: 100, // could be negative value
  left: 0, 
  behavior: 'smooth' 
});

.center{
	 margin: auto;
    width: 50%;
   
    padding: 10px;
}
</style>
<div class="col-md-6 col-sm-6 center">
	<form class="form" id="UpdatePassword">
	   <div class="card card-login card-hidden">
	      <div class="card-header card-header-default text-center" style=" background: #f44336 !important;box-shadow: 0 4px 20px 0px rgba(0, 0, 0, 0.14), 0 7px 10px -5px rgba(30, 74, 233, 0.4) !important;">
	         <h4 class="card-title">Change Password</h4>
	         <input type="hidden"  class="form-control" value="<?php echo $this->session->userdata('UserUID'); ?>" id="UserUID" name="UserUID" />
            <input type="hidden"  class="form-control" value="0" id="Firstlogin" name="Firstlogin" />           
	      </div>
	      <div class="card-body" style="padding:10px 30px;">
	         <div class="form-group">
	            <label for="oldpassword" class="bmd-label-floating">Old Password</label>
	            <input type="password" class="form-control" id="oldpassword" name="oldpassword" />
	         </div>
	         <div class="form-group mt-30">
	            <label for="password" class="bmd-label-floating">New Password</label>
	            <input type="password" class="form-control" id="password" name="password" />
	         </div>
	         <div id="message">
	            <h7>Your password needs to be:</h7>
	            <p id="letter" class="invalid">include <b>lowercase</b> letter.</p>
	            <p id="capital" class="invalid">include <b>capital (uppercase)</b> letter.</p>
	            <p id="number" class="invalid">include atleast one <b>number.</b></p>
	            <p id="length" class="invalid">at least <b>8 characters long.</b></p>
	         </div>
	         <div class="form-group mt-30">
	            <label for="cpassword" class="bmd-label-floating">Confirm Password</label>
	            <input type="password" class="form-control" id="cpassword" name="cpassword" />
	         </div>
	      </div>
	      <div class="card-footer justify-content-center">
	         <div class="row">
	            <div class="col-md-6">
	               <a href="#" style=" background: #f44336 !important;" class="btn  btn-round mt-10 UpdatePassword" id="UpdatePassword">Apply</a>
	            </div>
	            
	         </div>
	      </div>
	   </div>
	</form>
 </div>

<script type="text/javascript">
  var myInput = document.getElementById("password");
var letter = document.getElementById("letter");
var capital = document.getElementById("capital");
var number = document.getElementById("number");
var length = document.getElementById("length");

// When the user clicks on the password field, show the message box
myInput.onfocus = function() {
    // document.getElementById("message").style.display = "block";
    $('#message').slideToggle();
}

// When the user clicks outside of the password field, hide the message box
myInput.onblur = function() {
    // document.getElementById("message").style.display = "none";
    $('#message').slideToggle();
}

// When the user starts to type something inside the password field
myInput.onkeyup = function() {
  // Validate lowercase letters
  var lowerCaseLetters = /[a-z]/g;
  if(myInput.value.match(lowerCaseLetters)) {  
    letter.classList.remove("invalid");
    letter.classList.add("valid");
  } else {
    letter.classList.remove("valid");
    letter.classList.add("invalid");
  }
  
  // Validate capital letters
  var upperCaseLetters = /[A-Z]/g;
  if(myInput.value.match(upperCaseLetters)) {  
    capital.classList.remove("invalid");
    capital.classList.add("valid");
  } else {
    capital.classList.remove("valid");
    capital.classList.add("invalid");
  }

  // Validate numbers
  var numbers = /[0-9]/g;
  if(myInput.value.match(numbers)) {  
    number.classList.remove("invalid");
    number.classList.add("valid");
  } else {
    number.classList.remove("valid");
    number.classList.add("invalid");
  }
  
  // Validate length
  if(myInput.value.length >= 8) {
    length.classList.remove("invalid");
    length.classList.add("valid");
  } else {
    length.classList.remove("valid");
    length.classList.add("invalid");
  }
}


 $('.UpdatePassword').on('click',function(){

        var data = $('#UpdatePassword').serialize();

        $.ajax({
          url:'<?php echo base_url();?>Login/ChangeCurrentPassword',
          cache:false,
          type:'POST',
          data:data,
          dataType:'json',
          success:function(data)
          {
              console.log(data);
              if(data.validation_error == 1)
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
                $('#oldpassword').val('');
                $('#password').val('');
                $('#cpassword').val('');                  
                setTimeout(function(){window.location.replace("<?php echo base_url();?>Login");}, 3000); 
                
              }
              else if(data.validation_error == 2)
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
              }
              else if(data.validation_error == 3)
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
</script>






