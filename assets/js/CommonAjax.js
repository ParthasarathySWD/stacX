

$(function () {
    $(document).off('change', '#Customer, #bulk_Customers').on('change', '#Customer, #bulk_Customers', function(e) {
        var CustomerUID = $(this).val();
        var id = $(this).attr('id');

        console.log(AjaxGetCustomerProjects);
        AjaxGetCustomerProjects(CustomerUID).then((response)=>{
            console.table(response);

            var ProjectCustomer = response.ProjectCustomer;

            Project_select = ProjectCustomer.reduce((accumulator, value) => {
                return accumulator + '<Option value="' + value.ProjectUID + '">' + value.ProjectName + '</Option>';
            }, '')

            if (id == 'Customer') {
              
                $('#ProjectUID').html(Project_select);
                $('#ProjectUID').val($('#ProjectUID').find('option:first').val()).trigger('change');
            }
            else if (id == 'bulk_Customers'){
                $('#bulk_ProjectUID').html(Project_select);
                $('#bulk_ProjectUID').val($('#bulk_ProjectUID').find('option:first').val()).trigger('change');
                
            }
            
            removecardspinner('#Orderentrycard');
            callselect2();

        })
        .catch( (jqXHR) => {
            console.log(jqXHR);
        })
    });

    $(document).off('change', '#ProjectUID').on('change', '#ProjectUID', function(e) {

        var ProjectUID = $(this).val();
        $.ajax({
            type: "POST",
            url: base_url + "CommonController/GetPriority",
            data: { "ProjectUID": ProjectUID },
            dataType:'json',
            beforeSend: function () {
                addcardspinner('#Orderentrycard');
            },

            success: function (response) {
                console.table(response);


                var ProjectCustomer = response.ProjectCustomer;

                console.log(typeof ProjectCustomer);
                Priority_Select = ProjectCustomer.reduce((accumulator, value)=>{
                    return accumulator + '<Option value="'+value.Priority+'">'+value.Priority+'</Option>';
                }, '');

                $('#PriorityUID').html(Priority_Select);
                $('#PriorityUID').val($('#PriorityUID').find('option:first').val()).trigger('change');
                callselect2();
                removecardspinner('#Orderentrycard');

            }
        });
    });

    /*ZipCode Change function*/

    $(document).off('blur', '#PropertyZipcode').on('blur', '#PropertyZipcode', function (event){
        zip_val = $(this).val();
        if (zip_val != '') {
            addcardspinner('#Orderentrycard');
            $.ajax({
                type: "POST",
                url: base_url + 'CommonController/GetZipCodeDetails',
                data: { 'Zipcode': zip_val },
                dataType: 'json',
                cache: false,
                beforeSend: function () {
                    addcardspinner('#Orderentrycard');
                },
                success: function (data) {
                    $('.PropertyCityName').empty();
                    $('.PropertyStateCode').empty();
                    $('.PropertyCountyName').empty();
                    $('.MultiOrderedcity').html(' ');
                    $('.MultiOrderedcounty').html(' ');
                    $('.MultiOrderedstate').html(' ');


                    if (data != '') {

                        if (data['success'] == 1) {
                            $("#zipcodeadd").hide();

                            if (data['City'].length > 1) {
                                $('.MultiOrderedcity').html(' ');
                                $('.MultiOrderedcity').append('<span class="badge badge-danger cus-badge" style="background: #eb6357;color: #fff; z-index: 9999; top: -16px; right: -20px;">' + data['City'].length + '</span>');
                            }

                            if (data['County'].length > 1) {
                                $('.MultiOrderedcounty').html(' ');
                                $('.MultiOrderedcounty').append('<span class="badge badge-danger cus-badge" style="background: #eb6357;color: #fff; z-index: 9999; top: -16px; right: -20px;">' + data['County'].length + '</span>');
                            }

                            if (data['State'].length > 1) {
                                $('.MultiOrderedstate').html(' ');
                                $('.MultiOrderedstate').append('<span class="badge badge-danger cus-badge" style="background: #eb6357;color: #fff; z-index: 9999; top: -16px; right: -20px;">' + data['State'].length + '</span>');
                            }


                            $.each(data['City'], function (k, v) {
                                $('#PropertyCityName').val(v['CityName']);
                                $('.PropertyCityName').append('<li><a href="javascript:(void);" data-value="' + v['CityName'] + '">' + v['CityName'] + '</a></li>');
                                $('#PropertyCityName').closest('.form-group').addClass('is-filled');
                                zipcode_select();
                            });

                            $.each(data['County'], function (k, v) {
                                $('#PropertyCountyName').val(v['CountyName']);
                                $('.PropertyCountyName').append('<li><a href="javascript:(void);" data-value="' + v['CountyName'] + '">' + v['CountyName'] + '</a></li>');
                                $('#PropertyCountyName').closest('.form-group').addClass('is-filled');
                                zipcode_select();
                            });

                            $.each(data['State'], function (k, v) {
                                $('#PropertyStateCode').val(v['StateCode']);
                                $('.PropertyStateCode').append('<li><a href="javascript:(void);" data-value="' + v['StateCode'] + '">' + v['StateCode'] + '</a></li>');
                                $('#PropertyStateCode').closest('.form-group').addClass('is-filled');
                                zipcode_select();
                            });

                            $('#PropertyStateCode,#PropertyCountyName,#PropertyCityName').removeClass("is-invalid").closest('.form-group').removeClass('has-danger');
                            $('#PropertyStateCode.select2picker,#PropertyCountyName.select2picker,#PropertyCityName.select2picker').next().find('span.select2-selection').removeClass('errordisplay')
                        }
                        else {
                            $('#PropertyCityName').val('');
                            $('#PropertyCityName').closest('.form-group').removeClass('is-filled');

                            $('#PropertyCountyName').val('');
                            $('#PropertyCountyName').closest('.form-group').removeClass('is-filled');

                            $('#PropertyStateCode').val('');
                            $('#PropertyStateCode').closest('.form-group').removeClass('is-filled');

                            $("#zipcodeadd").show();
                        }
                    }
                    removecardspinner('#Orderentrycard');

                },
                error: function (jqXHR, textStatus, errorThrown) {

                    console.log(errorThrown);

                },
                failure: function (jqXHR, textStatus, errorThrown) {

                    console.log(errorThrown);

                },
            });
        }
        else {
            $('#PropertyCityName').val('');
            $('#PropertyCityName').closest('.form-group').removeClass('is-filled');

            $('#PropertyCountyName').val('');
            $('#PropertyCountyName').closest('.form-group').removeClass('is-filled');

            $('#PropertyStateCode').val('');
            $('#PropertyStateCode').closest('.form-group').removeClass('is-filled');
        }
    });

    $(document).off('click','#stackingcomplete').on('click','#stackingcomplete', (e)=>{
        /*SWEET ALERT CONFIRMATION*/
        swal({
            title: '<div class="text-primary" id="iconchg"><i style="font-size: 40px;" class="fa fa-info-circle fa-5x"></i></div>',
            html: '<span id="modal_msg" class= "modal_spanheading" > Do you want to complete the Stacking ?</span>',
            showCancelButton: true,
            confirmButtonClass: 'btn btn-success',
            cancelButtonClass: 'btn btn-danger',
            buttonsStyling: false,
            closeOnClickOutside: false,
            allowOutsideClick: false,
            showLoaderOnConfirm: true,
            position: 'top-end'
        }).then(function (confirm) {

            var OrderUID = $('#OrderUID').val();

            $.ajax({
                type: "POST",
                url: base_url + 'OrderComplete/StackingComplete',
                data: { 'OrderUID': OrderUID },
                dataType: 'json',
                cache: false,
                beforeSend: function () {
                    addcardspinner('#Orderentrycard');
                },
                success: function (data) {
                    if (data.validation_error==0) {
                        /*Sweet Alert MSG*/
                        swal({
                            title: "<i class='icon-checkmark2 iconsuccess'></i>",
                            html: "<p>"+data.message+"</p>",
                            confirmButtonClass: "btn btn-success",
                            allowOutsideClick: false,
                            width: '300px',
                            buttonsStyling: false
                        }).catch(swal.noop)                        
                    }
                    else{
                        swal({
                            title: "<i class='icon-close2 icondanger'></i>",
                            html: "<p>" + data.message + "</p>",
                            confirmButtonClass: "btn btn-success",
                            allowOutsideClick: false,
                            width: '300px',
                            buttonsStyling: false
                        }).catch(swal.noop)                        

                    }

                },
                error: function(jqXHR){
                    swal({
                        title: "<i class='icon-close2 icondanger'></i>",
                        html: "<p>Failed to Complete</p>",
                        confirmButtonClass: "btn btn-success",
                        allowOutsideClick: false,
                        width: '300px',
                        buttonsStyling: false
                    }).catch(swal.noop)


                }
            });

            },
            function (dismiss) {

            });


    });
    
    $(document).off('click','#reviewcomplete').on('click','#reviewcomplete', (e)=>{
        /*SWEET ALERT CONFIRMATION*/
        swal({
            title: '<div class="text-primary" id="iconchg"><i style="font-size: 40px;" class="fa fa-info-circle fa-5x"></i></div>',
            html: '<span id="modal_msg" class= "modal_spanheading" > Do you want to complete Review ?</span>',
            showCancelButton: true,
            confirmButtonClass: 'btn btn-success',
            cancelButtonClass: 'btn btn-danger',
            buttonsStyling: false,
            closeOnClickOutside: false,
            allowOutsideClick: false,
            showLoaderOnConfirm: true,
            position: 'bottom-middle'
        }).then(function (confirm) {

            var OrderUID = $('#OrderUID').val();

            $.ajax({
                type: "POST",
                url: base_url + 'OrderComplete/ReviewComplete',
                data: { 'OrderUID': OrderUID },
                dataType: 'json',
                cache: false,
                beforeSend: function () {
                    addcardspinner('#Orderentrycard');
                },
                success: function (data) {
                    if (data.validation_error==0) {
                        /*Sweet Alert MSG*/
                        swal({
                            title: "<i class='icon-checkmark2 iconsuccess'></i>",
                            html: "<p>"+data.message+"</p>",
                            confirmButtonClass: "btn btn-success",
                            allowOutsideClick: false,
                            width: '300px',
                            buttonsStyling: false
                        }).catch(swal.noop)                        
                    }
                    else{
                        swal({
                            title: "<i class='icon-close2 icondanger'></i>",
                            html: "<p>" + data.message + "</p>",
                            confirmButtonClass: "btn btn-success",
                            allowOutsideClick: false,
                            width: '300px',
                            buttonsStyling: false
                        }).catch(swal.noop)                        

                    }

                },
                error: function(jqXHR){
                    swal({
                        title: "<i class='icon-close2 icondanger'></i>",
                        html: "<p>Failed to Complete</p>",
                        confirmButtonClass: "btn btn-success",
                        allowOutsideClick: false,
                        width: '300px',
                        buttonsStyling: false
                    }).catch(swal.noop)


                }
            });

            },
            function (dismiss) {

            });


    })
    
    $(document).off('submit','#raiseexcetion').on('submit','#raiseexcetion', function(e){

            e.preventDefault();
            e.stopPropagation();
            var OrderUID = $('#OrderUID').val();

            var button = $('.btnraiseexcetion');
            var button_text = $('.btnraiseexcetion').html();

            var formdata = new FormData($(this)[0]);
            formdata.append('OrderUID', OrderUID);

            $.ajax({
                type: "POST",
                url: base_url + 'OrderComplete/RaiseException',
                data: formdata,
                dataType: 'json',
                cache: false,
                processData: false,
                contentType: false,
                beforeSend: function () {
                    button.attr("disabled", true);
                    button.html('<i class=""fa fa-spin fa-spinner"></i> Loading ...'); 

                },
                success: function (data) {
                    if (data.validation_error==0) {
                        /*Sweet Alert MSG*/
                        swal({
                            title: "<i class='icon-checkmark2 iconsuccess'></i>",
                            html: "<p>"+data.message+"</p>",
                            confirmButtonClass: "btn btn-success",
                            allowOutsideClick: false,
                            width: '300px',
                            buttonsStyling: false
                        }).catch(swal.noop)                        
                    }
                    else{
                        $.notify({ icon: "icon-bell-check", message: data['message'] }, { type: "danger", delay: 1000 });

                    }
                    button.html(button_text);
                    button.attr("disabled", false);

                },
                error: function(jqXHR){
                    swal({
                        title: "<i class='icon-close2 icondanger'></i>",
                        html: "<p>Failed to Complete</p>",
                        confirmButtonClass: "btn btn-success",
                        allowOutsideClick: false,
                        width: '300px',
                        buttonsStyling: false
                    }).catch(swal.noop)


                }
            });



    })
    
    $(document).off('submit','#frmclearexception').on('submit','#frmclearexception', function(e){

            e.preventDefault();
            e.stopPropagation();
            var OrderUID = $('#OrderUID').val();

            var button = $('.btnclearexception');
            var button_text = $('.btnclearexception').html();

            var formdata = new FormData($(this)[0]);
            formdata.append('OrderUID', OrderUID);

            $.ajax({
                type: "POST",
                url: base_url + 'OrderComplete/ClearException',
                data: formdata,
                dataType: 'json',
                cache: false,
                processData: false,
                contentType: false,
                beforeSend: function () {
                    button.attr("disabled", true);
                    button.html('<i class=""fa fa-spin fa-spinner"></i> Loading ...'); 

                },
                success: function (data) {
                    if (data.validation_error==0) {
                        /*Sweet Alert MSG*/
                        swal({
                            title: "<i class='icon-checkmark2 iconsuccess'></i>",
                            html: "<p>"+data.message+"</p>",
                            confirmButtonClass: "btn btn-success",
                            allowOutsideClick: false,
                            width: '300px',
                            buttonsStyling: false
                        }).catch(swal.noop)                        
                    }
                    else{
                        $.notify({ icon: "icon-bell-check", message: data['message'] }, { type: "danger", delay: 1000 });

                    }
                    button.html(button_text);
                    button.attr("disabled", false);

                },
                error: function(jqXHR){
                    swal({
                        title: "<i class='icon-close2 icondanger'></i>",
                        html: "<p>Failed to Complete</p>",
                        confirmButtonClass: "btn btn-success",
                        allowOutsideClick: false,
                        width: '300px',
                        buttonsStyling: false
                    }).catch(swal.noop)


                }
            });



    })
    
    
});//Document Ends


    function zipcode_select() {
        $('.dropdown-menu a').click(function () {
            $(this).closest('.dropdown').find('input.select')
                .val($(this).attr('data-value'));
        });
    } 

    var AjaxGetCustomerProjects = async (CustomerUID) => {
        return new Promise(function (resolve, reject) {
            resolve(
                    $.ajax({
                        type: "POST",
                        url: base_url + "CommonController/GetCustomerDetails",
                        data: { "CustomerUID": CustomerUID },
                        dataType: 'json',
                        beforeSend: function () {
                            addcardspinner('#Orderentrycard');
                        },
                    })
                );
            
        }) 
      }