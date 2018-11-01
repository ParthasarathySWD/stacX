

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