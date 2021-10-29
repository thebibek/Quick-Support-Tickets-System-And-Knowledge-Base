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
    PROFILE SIDEBAR
    ==============================================*/
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
                    $.ajax(baseUrl+'admin/profile/update_profile_image', {
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
    

    /*============================================
    UPDATE PROFILE
    ==============================================*/
    var updateProfileForm=$("#updateProfileForm");
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
                    url: baseUrl + "admin/profile/update_profile",
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

    /*============================================
    CHANGE PASSWORD
    ==============================================*/
    var updatePasswordForm=$("#updatePasswordForm");
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
                // CHANGE PASSWORD AJAX
                $.ajax({
                    type: "POST",
                    url: baseUrl + "admin/change-password",
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
        $.toast({heading: head ,text: message,loader: false,position : 'bottom-right',showHideTransition: 'fade', icon: type });
    }
})(jQuery);