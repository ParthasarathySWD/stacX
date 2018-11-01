

$(function () {
    $(document).on('click', '#CustomerUID', (e)=>{

        var CustomerUID = $(this).val();
        $.ajax({
            type: "POST",
            url: "MY_Controller/GetCustomerDetails",
            data: {"CustomerUID": CustomerUID},
            success: function (response) {
                console.table(response);
            }
        });
    });
    
});