



<script type="text/javascript">
	$(document).on('click', ".abstractordetails", function(e){
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
				console.log(data);
				$('#abstractormodal').remove();
				$('body').append(data);
				$('#abstractormodal').modal('show');
			},
			error: function(jqXHR, textStatus, errorThrown){

			}
		});

	})


/*--- GENERAL FUNCTIONS ---*/
function callselect2(){
  $(".select2picker").select2({
    tags: false,
    theme: "bootstrap",
  });
}

function callselect2byclass(byclass){
  $('.'+byclass).select2({
    tags: false,
    theme: "bootstrap",
  });
}

function callselect2byid(byid){
  $('#'+byid).select2({
    tags: false,
    theme: "bootstrap",
  });
}
/*--- GENERAL FUNCTIONS ---*/

$(window).on('load', function () {
	removebodyspinner();
});
</script>
</body>

</html>
