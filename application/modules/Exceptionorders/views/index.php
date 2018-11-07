<style type="text/css">

</style>
<div class="card" id="Exceptionorders">
	<div class="card-header card-header-danger card-header-icon">
		<div class="card-icon">EXCEPTIONS
		</div>
	</div>
	<div class="card-body">
        <table class="table table-hover table-striped" id="exception">
            <thead>
               <tr>
                    <th>Prop No</th>
                    <th>Customer </th>
                    <th>Current Status</th>
                    <th>Property Address</th>   
                    <th>Property City</th>  
                    <th>Property County</th>  
                    <th>Property State</th> 
                    <th>Zip Code</th>     
                    <th>Project</th>
                    <th>Actions</th>
               </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
	</div>
</div>

<script type="text/javascript">
          var exception = false;
        $(function() {
          $(".select2picker").select2({
            tags: false,
            theme: "bootstrap",
          });
          $('#exception').DataTable().destroy();
        });
	$(document).ready(function(){


          exception = $('#exception').DataTable( {
            scrollX:        true,
            scrollCollapse: true,
            paging:  true,
            "autoWidth": true,
          "processing": true, //Feature control the processing indicator.
          "serverSide": true, //Feature control DataTables' server-side processing mode.
          "order": [], //Initial no order.
          "pageLength": 50, // Set Page Length
          "lengthMenu":[[10, 25, 50, 100], [10, 25, 50, 100]],
                    fixedColumns: {
          leftColumns: 1,
          rightColumns: 2
        },

          language: {
            sLengthMenu: "Show _MENU_ Orders",
            emptyTable:     "No Orders Found",
            info:           "Showing _START_ to _END_ of _TOTAL_ Orders",
            infoEmpty:      "Showing 0 to 0 of 0 Orders",
            infoFiltered:   "(filtered from _MAX_ total Orders)",
            zeroRecords:    "No matching Orders found",
            processing: '<svg class="d2tspinner-circular" viewBox="25 25 50 50"><circle class="d2tspinner-path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10"/></svg>',

          },

          // Load data for the table's content from an Ajax source
          "ajax": {
            "url": "<?php echo base_url('Exceptionorders/exceptionorders_ajax_list')?>",
            "type": "POST" 
          }

        });



	});
	</script>







