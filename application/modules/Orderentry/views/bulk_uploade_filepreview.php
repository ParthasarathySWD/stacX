<!-- <div class="col-md-12 pd-0">
    <h4 class="sectionhead"><i class="icon-checkmark4 headericon"></i>LoanNumber/Document Preview</h4>	
</div> -->

<div class="tablescroll defaultfontsize">
    <table class="table table-striped table-responsive table-bordered">
        <thead>
            <tr>
                <th>SNO</th>
                <th>LOAN NUMBER</th>
                <th>DOCUMENT NAME</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($FileUploadPreview as $key => $file) { ?>
            <tr>
                <td><?php echo $key + 1; ?></td>
                <td><?php echo $file->LoanNumber; ?></td>
                <td><?php echo $file->DocumentName; ?></td>
            </tr>
            
        <?php } ?>    
        </tbody>
    </table>
</div>