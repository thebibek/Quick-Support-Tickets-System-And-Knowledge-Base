(function ($) {
    'use strict';
    var baseUrl=$('base').attr("href");
    var appLanguage=[];

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
    LOGIN
    ==============================================*/
    var loginForm=$("#loginForm");
    if(loginForm.length>0){
        var loader = Ladda.create( document.querySelector('button[type=submit]'));
        loginForm.validate({
            rules: {
                email: {
                    required: true,
                    email: true,
                    minlength: 4
                },
                password: {
                    required: true
                }
            },
            messages: {
                email: {
                    required: appLanguage[0]['alert_enter_email'],
                    email:appLanguage[0]['alert_enter_valid_email'],
                    minlength: appLanguage[0]['alert_enter_valid_email_lenght']
                },
                password: {
                    required: appLanguage[0]['alert_enter_password']
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
                $.ajax({
                    type: "POST",
                    url: baseUrl + "admin/login",
                    data: loginForm.serialize(),
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
                                window.location.replace(baseUrl + "admin");
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
    /*============================================
    FORGOT PASSWORD
    ==============================================*/
    var forgotPasswordForm=$("#forgotPasswordForm");
    if(forgotPasswordForm.length>0){
        var loader = Ladda.create( document.querySelector('button[type=submit]'));
        forgotPasswordForm.validate({
            rules: {
                email: {
                    required: true,
                    email: true,
                    minlength: 4
                }
            },
            messages: {
                email: {
                    required: appLanguage[0]['alert_enter_email'],
                    email:appLanguage[0]['alert_enter_valid_email'],
                    minlength: appLanguage[0]['alert_enter_valid_email_lenght']
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
                /*============================================
                FORGOT PASSWORD AJAX
                ==============================================*/
                $.ajax({
                    type: "POST",
                    url: baseUrl + "admin/forgot-password",
                    data: forgotPasswordForm.serialize(),
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
                                window.location.replace(baseUrl + "admin");
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

    /*============================================
    FORGOT PASSWORD
    ==============================================*/
    var resetPasswordForm=$("#resetPasswordForm");
    if(resetPasswordForm.length>0){
        var loader = Ladda.create( document.querySelector('button[type=submit]'));
        resetPasswordForm.validate({
            rules: {
                password: {
                    required: true,
                    minlength: 8,
                    maxlength: 15
                },
                confirm_password: {
                    required: true,
                    minlength: 8,
                    maxlength: 15,
                    equalTo: "#inputPassword",
                }
            },
            messages: {
                password: {
                    required: appLanguage[0]['alert_enter_new_password'],
                    minlength:appLanguage[0]['alert_enter_new_password_minlength'],
                    minlength: appLanguage[0]['alert_enter_new_password_maxlength']
                },
                confirm_password: {
                    required: appLanguage[0]['alert_enter_confirm_password'],
                    minlength:appLanguage[0]['alert_enter_confirm_password_minlength'],
                    minlength:appLanguage[0]['alert_enter_confirm_password_maxlength'],
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
                $.ajax({
                    type: "POST",
                    url: baseUrl + "admin/auth/reset_password_action",
                    data: resetPasswordForm.serialize(),
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
                                window.location.replace(baseUrl + "admin/login");
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


    //SHOW ALERT
    function showAlert(type,head,message){
        $.toast({heading: head ,text: message,loader: false,position : 'top-center',showHideTransition: 'fade', icon: type });
    }
})(jQuery);