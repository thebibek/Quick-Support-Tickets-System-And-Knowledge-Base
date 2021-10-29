(function ($) {
    'use strict';
    var baseUrl=$('base').attr("href");
    var appLanguage=[];
    var filterTicketForm=$("#filterTicketForm");
    var tileOverlay=$('.tile-overlay');
    var ticketsList=$('#ticketsList');
    var filterTicketModal=$("#filterTicketModal");
    var addTicketForm=$("#addTicketForm");
    var replyTicketForm=$("#replyTicketForm");
    var inputAttachment=$("#inputAttachment");
    var updateProfileForm=$("#updateProfileForm");
    var updatePasswordForm=$("#updatePasswordForm");

    /*============================================
    LOAD LANGUAGE DATA
    ==============================================*/
    $.ajax({
        type: 'POST',
        url: baseUrl+'pages/get_all_language_keys/',
        data:null,
        dataType: 'json',
        async: false,
        success: function (data) {
            appLanguage.push(data.languages);
        }
    });

    /*============================================
    TICKETS
    ==============================================*/
    //CALL LIST TICKETS FUNCTION
    if(ticketsList.length>0){
        listTickets(0);
    }

    //FILTER TICKETS
    filterTicketForm.on('submit',function(e){
        e.preventDefault();
        var loader = Ladda.create( document.querySelector('#filterButton'));
        loader.start();
        listTickets(0);
        filterTicketModal.modal('hide');
        loader.stop();
    });

    //WHEN CLICK DELETE BUTTON
    ticketsList.on('click',"#deleteButton",function () {
        var ticket_id=$(this).attr("data-id");
        deleteTicket(ticket_id);
    });

    //LIST TICKETS
    function listTickets(page_num){
        var form=filterTicketForm;
        $.ajax({
            type: 'POST',
            url: baseUrl+'user/list_tickets_ajax/'+page_num,
            data:form.serialize(),
            dataType: 'json',
            async: false,
            beforeSend: function () {
                    tileOverlay.show();
            },
            success: function (data) {
                if (data.success) {
                    //append tickets data
                    ticketsList.html(data.content);
                    //hide loader
                    tileOverlay.fadeOut("slow");
                    // Detect pagination click
                    $('#pagination a').on('click',function(e){
                        e.preventDefault(); 
                        var pageno = $(this).attr('data-ci-pagination-page');
                        listTickets(pageno);
                    });
                } else {
                    showAlert('error',appLanguage[0]['text_error'],appLanguage[0]['alert_loading_list_error']);
                }
            },
            error: function () {
                showAlert('error',appLanguage[0]['text_error'],appLanguage[0]['alert_went_wrong']);
            }
        });
    }

    //ADD TICKET
    if(addTicketForm.length>0){
        //summernote
        $(".summertext").summernote({
            toolbar: [
              ['style', ['bold', 'italic', 'underline', 'clear']],
              ['para', ['ul', 'ol']]
            ]
        });
        var loader = Ladda.create( document.querySelector('#addTicketButton'));
        addTicketForm.validate({
            rules: {
                ticket_title: {
                    required: true
                },
                ticket_description: {
                    required: true
                },
                category: {
                    required: true
                },
                priority: {
                    required: true
                }
            },
            messages: {
                ticket_title: {
                    required: appLanguage[0]['alert_enter_ticket_title']
                },
                ticket_description: {
                    required: appLanguage[0]['alert_enter_ticket_description']
                },
                category: {
                    required: appLanguage[0]['alert_select_ticket_category']
                },
                priority: {
                    required: appLanguage[0]['alert_select_ticket_priority']
                }
            },
            errorElement: "em",
            errorPlacement: function (error, element) {
                // Add the `invalid-feedback` class to the error element
                error.addClass("invalid-feedback");
                error.insertAfter(element);
            },
            highlight: function (element, errorClass, validClass) {
                $(element).addClass("is-invalid").removeClass("is-valid");
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).addClass("is-valid").removeClass("is-invalid");
            },
            submitHandler: function(form) {
                var attachment = $('#inputAttachment').prop('files')[0];
                var form_data = new FormData();
                form_data.append('ticket_title', $('#addTicketForm input[name="ticket_title"]').val());
                form_data.append('ticket_description', $('#addTicketForm textarea[name="ticket_description"]').val());
                form_data.append('category', $('#addTicketForm select[name="category"]').val());
                form_data.append('priority', $('#addTicketForm select[name="priority"]').val());
                form_data.append('attachment', attachment);
                //SUBMIT AJAX
                $.ajax({
                    type: "POST",
                    url: baseUrl + "user/submit_ticket",
                    data: form_data,
                    dataType: 'json',
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        //show button loader
                        loader.start();
                    },
                    success: function (data) {
                        if (data.success) {
                            loader.stop();
                            showAlert('success',appLanguage[0]['text_success'],data.message);
                            window.setTimeout(function(){
                                window.location.replace(baseUrl + "user/tickets");
                            },2000);
                        } else {
                            
                            loader.stop();
                            showAlert('error',appLanguage[0]['text_error'],data.message);

                        }
                    },
                    error: function () {
                        loader.stop();
                        showAlert('error',appLanguage[0]['text_error'],appLanguage[0]['alert_went_wrong']);
                    }
                });
        
            }
        });
    }

    //REPLY TO TICKET
    if(replyTicketForm.length>0){
        var loader = Ladda.create( document.querySelector('#replyTicketButton'));
        //summernote
        $(".summertext").summernote({
            toolbar: [
              ['style', ['bold', 'italic', 'underline', 'clear']],
              ['para', ['ul', 'ol']]
            ]
        });
        replyTicketForm.validate({
            rules: {
                reply_content: {
                    required: true
                }
            },
            messages: {
                reply_content: {
                    required: appLanguage[0]['alert_enter_reply']
                }
            },
            errorElement: "em",
            errorPlacement: function (error, element) {
                // Add the `invalid-feedback` class to the error element
                error.addClass("invalid-feedback");
                error.insertAfter(element);
            },
            highlight: function (element, errorClass, validClass) {
                $(element).addClass("is-invalid").removeClass("is-valid");
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).addClass("is-valid").removeClass("is-invalid");
            },
            submitHandler: function(form) {
                var reply_file = $('#inputAttachment').prop('files')[0];
                var form_data = new FormData();
                form_data.append('ticket_id', $('#replyTicketForm input[name="ticket_id"]').val());
                form_data.append('reply_content', $('#replyTicketForm textarea[name="reply_content"]').val());
                form_data.append('reply_file', reply_file);
                // TICKET REPLY AJAX
                $.ajax({
                    type: "POST",
                    url: baseUrl + "user/reply_to_ticket",
                    data: form_data,
                    dataType: 'json',
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        //show button loader
                        loader.start();
                    },
                    success: function (data) {
                        if (data.success) {
                            loader.stop();
                            showAlert('success',appLanguage[0]['text_success'],data.message);
                            window.setTimeout(function(){
                                location.reload(); 
                            },2000);
                        } else {
                            loader.stop();
                            showAlert('error',appLanguage[0]['text_error'],data.message);

                        }
                    },
                    error: function () {
                        loader.stop();
                        showAlert('error',appLanguage[0]['text_error'],appLanguage[0]['alert_went_wrong']);
                    }
                });
        
            }
        });
    }

    //ON REPLY ATTACHMENT CHANGE
    inputAttachment.on("change", function() {
        var fileName = $(this).val().split("\\").pop();
        $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
    });

    //DELETE TICKET
    function deleteTicket(ticket_id) {
        var ticket_id = ticket_id;
        Swal.fire({
            title: appLanguage[0]['alert_are_you_sure'],
            text: appLanguage[0]['alert_delete_confirm'],
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#40b1dd',
            cancelButtonColor: '#1d2c63',
            cancelButtonText: appLanguage[0]['alert_cancel'],
            confirmButtonText: appLanguage[0]['alert_yes_delete']
            }).then((result) => {
            if (result.value) {
                //submit form value
                $.ajax({
                    type: "POST",
                    url: baseUrl+"user/delete_ticket",
                    data: {
                        ticket_id: ticket_id
                    },
                    dataType: 'json',
                    async: false,
                    success: function (data) {
                        if (data.success) {
                            Swal.fire(appLanguage[0]['text_success'],data.message,'success');
                              //list tickets
                            listTickets(0);
                        } else {
                            Swal.fire(appLanguage[0]['alert_error'],data.message,'error');
                        }
                    },
                    error: function () {
                        Swal.fire(appLanguage[0]['alert_error'],appLanguage[0]['alert_went_wrong'],'error')
                    }
                });
            }
        });
		
    }

    /*============================================
    PROFILE
    ==============================================*/
    //UPDATE PROFILE
    if(updateProfileForm.length>0){
        var loader = Ladda.create( document.querySelector('button[type=submit]'));
        updateProfileForm.validate({
            rules: {
                full_name: {
                    required: true
                },
                email: {
                    required: true,
                    email: true,
                    minlength: 4
                },
                mobile: {
                    required: true
                }
            },
            messages: {
                full_name: {
                    required: appLanguage[0]['alert_enter_fullname']
                },
                email: {
                    required: appLanguage[0]['alert_enter_email'],
                    email:appLanguage[0]['alert_enter_valid_email'],
                    minlength: appLanguage[0]['alert_enter_valid_email_lenght']
                },
                mobile: {
                    required: appLanguage[0]['alert_enter_mobile']
                }
            },
            errorElement: "em",
            errorPlacement: function (error, element) {
                // Add the `invalid-feedback` class to the error element
                error.addClass("invalid-feedback");
                error.insertAfter(element);
            },
            highlight: function (element, errorClass, validClass) {
                $(element).addClass("is-invalid").removeClass("is-valid");
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).addClass("is-valid").removeClass("is-invalid");
            },
            submitHandler: function(form) {
                // UPDATE PROFILE AJAX
                $.ajax({
                    type: "POST",
                    url: baseUrl + "user/update_profile",
                    data: updateProfileForm.serialize(),
                    dataType: 'json',
                    async: false,
                    beforeSend: function() {
                        //show button loader
                        loader.start();
                    },
                    success: function (data) {
                        if (data.success) {
                            loader.stop();
                            showAlert('success',appLanguage[0]['text_success'],data.message);
                        } else {
                            
                            loader.stop();
                            showAlert('error',appLanguage[0]['text_error'],data.message);

                        }
                    },
                    error: function () {
                        loader.stop();
                        showAlert('error',appLanguage[0]['text_error'],appLanguage[0]['alert_went_wrong']);
                    }
                });
        
            }
        });
    }

    //CHANGE PASSWORD
    if(updatePasswordForm.length>0){
        var loader = Ladda.create( document.querySelector('button[type=submit]'));
        updatePasswordForm.validate({
            rules: {
                old_password: {
                    required: true,
                    
                },
                new_password: {
                    required: true,
                    minlength: 8,
                    maxlength: 15
                },
                confirm_new_password: {
                    required: true,
                    minlength: 8,
                    maxlength: 15,
                    equalTo: "#inputNewPassword",
                }
            },
            messages: {
                old_password: {
                    required: appLanguage[0]['alert_enter_old_password']
                },
                new_password: {
                    required: appLanguage[0]['alert_enter_new_password'],
                    minlength:appLanguage[0]['alert_enter_new_password_minlength'],
                    minlength: appLanguage[0]['alert_enter_new_password_maxlength']
                },
                confirm_new_password: {
                    required: appLanguage[0]['alert_enter_confirm_password'],
                    minlength:appLanguage[0]['alert_enter_confirm_password_minlength'],
                    minlength: appLanguage[0]['alert_enter_confirm_password_maxlength'],
                    equalTo: appLanguage[0]['alert_enter_password_repeat']
                }
            },
            errorElement: "em",
            errorPlacement: function (error, element) {
                // Add the `invalid-feedback` class to the error element
                error.addClass("invalid-feedback");
                error.insertAfter(element);
            },
            highlight: function (element, errorClass, validClass) {
                $(element).addClass("is-invalid").removeClass("is-valid");
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).addClass("is-valid").removeClass("is-invalid");
            },
            submitHandler: function(form) {
                // CHANGE PASSWORD AJAX
                $.ajax({
                    type: "POST",
                    url: baseUrl + "user/change-password",
                    data: updatePasswordForm.serialize(),
                    dataType: 'json',
                    async: false,
                    beforeSend: function() {
                        //show button loader
                        loader.start();
                    },
                    success: function (data) {
                        if (data.success) {
                            loader.stop();
                            showAlert('success',appLanguage[0]['text_success'],data.message);
                            window.setTimeout(function(){
                                location.reload(); 
                            },2000);
                        } else {
                            loader.stop();
                            showAlert('error',appLanguage[0]['text_error'],data.message);
                        }
                    },
                    error: function () {
                        loader.stop();
                        showAlert('error',appLanguage[0]['text_error'],appLanguage[0]['alert_went_wrong']);
                    }
                });
        
            }
        });
    }

    //PROFILE IMAGE UPDATE
    var profileImageHolder=$("#profileImageHolder");
    if(profileImageHolder.length>0){
        //CROP IMAGE
        var avatar = document.getElementById('profileAvatar');
        var image = document.getElementById('profileImage');
        var input = document.getElementById('inputProfileImage');
        var $progress = $('.profile-image-progress');
        var $progressBar = $('.profile-image-progress-bar');
        var $modal = $('#modalProfileImage');
        var cropper;
        input.addEventListener('change', function (e) {
            var files = e.target.files;
            var done = function (url) {
                input.value = '';
                image.src = url;
                $modal.modal({backdrop: 'static', keyboard: false});
            };
            var reader;
            var file;
            var URL;

            if (files && files.length > 0) {
                file = files[0];

                if (URL) {
                    done(URL.createObjectURL(file));
                } else if (FileReader) {
                    reader = new FileReader();
                    reader.onload = function (e) {
                        done(reader.result);
                    };
                    reader.readAsDataURL(file);
                }
            }
        });

        $modal.on('shown.bs.modal', function () {
            cropper = new Cropper(image, {
                aspectRatio: 1,
                viewMode: 3,
            });
        }).on('hidden.bs.modal', function () {
            cropper.destroy();
            cropper = null;
        });

        document.getElementById('crop').addEventListener('click', function () {
            var initialAvatarURL;
            var canvas;

            $modal.modal('hide');

            if (cropper) {
                canvas = cropper.getCroppedCanvas({
                    width: 300,
                    height: 300,
                });
                initialAvatarURL = avatar.src;
                avatar.src = canvas.toDataURL();
                $progress.show();
                canvas.toBlob(function (blob) {
                    $.ajax(baseUrl+'user/update_profile_image', {
                        method: 'POST',
                        data: {
                            profile_image: canvas.toDataURL()
                        },
                        dataType: 'json',
                        async: false,

                        xhr: function () {
                            var xhr = new XMLHttpRequest();

                            xhr.upload.onprogress = function (e) {
                                var percent = '0';
                                var percentage = '0%';

                                if (e.lengthComputable) {
                                    percent = Math.round((e.loaded / e.total) *
                                        100);
                                    percentage = percent + '%';
                                    $progressBar.width(percentage).attr(
                                        'aria-valuenow', percent).text(
                                        percentage);
                                }
                            };

                            return xhr;
                        },

                        success: function (data) {
                            if (data.success) {
                                showAlert('success',appLanguage[0]['text_success'],data.message);
                            } else {
                                showAlert('error',appLanguage[0]['text_error'],data.message);
                            }
                        },

                        error: function () {
                            avatar.src = initialAvatarURL;
                            showAlert('error',appLanguage[0]['text_error'],appLanguage[0]['alert_went_wrong']);
                        },

                        complete: function () {
                            $progress.hide();
                        },
                    });
                });
            }
        });
    }

    //SHOW ALERT
    function showAlert(type,head,message){
        $.toast({heading: head ,text: message,loader: false,position : 'bottom-right',showHideTransition: 'fade', icon: type });
    }

})(jQuery);