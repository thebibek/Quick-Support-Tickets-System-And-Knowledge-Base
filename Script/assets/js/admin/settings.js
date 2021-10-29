(function ($) {
    'use strict';
    var baseUrl=$('base').attr("href");
    var appLanguage=[];
    var tileOverlay=$('.tile-overlay');
    var sidePanel=$("#sidePanel");
    var sidePanelLoader=$("#sidePanelLoader");
    var sidePanelContent=$("#sidePanelContent");
    var loader = Ladda.create(document.querySelector('button[type=submit]'));
    var filterPermissionForm=$('#filterPermissionForm');
    var permissionsList=$('#permissionsList');
    var templatesList=$('#templatesList');

    /*============================================
    SIDEPANEL
    ==============================================*/
    var viewportWidth = $(window).width();
    if (viewportWidth < 960) {var sidePanelWidth='100%';}else {var sidePanelWidth=700;}
    if(sidePanel.length >0){
        sidePanel.slideReveal({
            trigger: $(".trigger-button"),
            position:'right',
            push: false,
            overlay: true,
            width: sidePanelWidth,
        });
    }

    //close side panel
    sidePanel.on('click',".close-panel-button",function () {
        closePanel();
    });

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
    SITE SETTINGS
    ==============================================*/

    //ON SITE LOGO CHANGE
    var inputSiteLogo=$("#inputSiteLogo");
    var siteLogoImg=$('#siteLogoImg');
    inputSiteLogo.on("change", function() {
        var fileName = $(this).val().split("\\").pop();
        $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
        var input = this;
        var url = $(this).val();
        var ext = url.substring(url.lastIndexOf('.') + 1).toLowerCase();
        if (input.files && input.files[0]&& (ext == "gif" || ext == "png" || ext == "jpeg" || ext == "jpg")) 
        {
            var reader = new FileReader();
            reader.onload = function (e) {
                siteLogoImg.attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }else{
            siteLogoImg.attr('src', baseUrl+'/assets/images/admin-logo.png');
        }
    });

    //ON SITE FAVICON CHANGE
    var inputSiteFavicon=$("#inputSiteFavicon");
    var siteFaviconImg=$("#siteFaviconImg");
    inputSiteFavicon.on("change", function() {
        var fileName = $(this).val().split("\\").pop();
        $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
        var input = this;
        var url = $(this).val();
        var ext = url.substring(url.lastIndexOf('.') + 1).toLowerCase();
        if (input.files && input.files[0]&& (ext == "gif" || ext == "png" || ext == "jpeg" || ext == "jpg" || ext == "ico")) 
        {
            var reader = new FileReader();
            reader.onload = function (e) {
                siteFaviconImg.attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }else{
            siteFaviconImg.attr('src', baseUrl+'/assets/images/favicon.png');
        }
    });

    //SUBMIT SITE SETTING FORM
    var siteSettingsForm=$("#siteSettingsForm");
    siteSettingsForm.validate({
        rules: {
            site_title: {
                required: true
            },
            site_email: {
                required: true,
                email: true,
                minlength: 4
            },
            site_phone: {
                required: true
            }
        },
        messages: {
            site_title: {
                required: appLanguage[0]['alert_enter_site_title']
            },
            site_email: {
                required: appLanguage[0]['alert_enter_email'],
                email:appLanguage[0]['alert_enter_valid_email'],
                minlength: appLanguage[0]['alert_enter_valid_email_lenght']
            },
            site_phone: {
                required: appLanguage[0]['alert_enter_site_phone']
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
            var site_logo = inputSiteLogo.prop('files')[0];
            var site_favicon = inputSiteFavicon.prop('files')[0];
            var form_data = new FormData();
            form_data.append('site_title', $('#siteSettingsForm input[name="site_title"]').val());
            form_data.append('site_email', $('#siteSettingsForm input[name="site_email"]').val());
            form_data.append('site_phone', $('#siteSettingsForm input[name="site_phone"]').val());
            form_data.append('site_logo', site_logo);
            form_data.append('site_favicon', site_favicon);
            // SITE SETTING AJAX
            $.ajax({
                type: "POST",
                url: baseUrl + "admin/settings/site",
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

    /*============================================
    SOCIAL MEDIA SETTINGS
    ==============================================*/
    var socialMediaSettingsForm=$("#socialMediaSettingsForm");
    socialMediaSettingsForm.on('submit',function(e) {
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: baseUrl + "admin/settings/social-media",
            data: socialMediaSettingsForm.serialize(),
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
    });

    /*============================================
    SEO SETTINGS
    ==============================================*/
    var seoSettingsForm=$("#seoSettingsForm");
    seoSettingsForm.validate({
        rules: {
            meta_title: {
                required: true
            },
            meta_description: {
                required: true
            },
            meta_keywords: {
                required: true
            }
        },
        messages: {
            meta_title: {
                required: appLanguage[0]['alert_enter_meta_title']
            },
            meta_description: {
                required: appLanguage[0]['alert_enter_meta_description']
            },
            meta_keywords: {
                required: appLanguage[0]['alert_enter_meta_keywords']
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
            SEO SETTING AJAX
            ==============================================*/
            $.ajax({
                type: "POST",
                url: baseUrl + "admin/settings/seo",
                data: seoSettingsForm.serialize(),
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

    /*============================================
    PERMISSIONS
    ==============================================*/

    //CALL LIST PERMISSIONS FUNCTION
    if(permissionsList.length>0){
        listPermissions(0);
    }

    //FILTER PERMISSIONS
    filterPermissionForm.on('submit',function(e){
        e.preventDefault();
        loader.start();
        listPermissions(0);
        loader.stop();
    });

    //WHEN CLICK TOGGLE BUTTON
    permissionsList.on('click',".toggle-button",function () {
        var role_permission_id=$(this).attr("data-role-permission-id");
        changePermissions(role_permission_id);
    });

    //LIST PERMISSIONS
    function listPermissions(page_num){
        var form=filterPermissionForm;
        $.ajax({
            type: 'POST',
            url: baseUrl+'admin/settings/list_permissions_ajax/'+page_num,
            data:form.serialize(),
            dataType: 'json',
            async: false,
            beforeSend: function () {
                    tileOverlay.show();
            },
            success: function (data) {
                if (data.success) {
                    //append permission data
                    permissionsList.html(data.content);
                    //hide loader
                    tileOverlay.fadeOut("slow");
                    // Detect pagination click
                    $('#pagination a').on('click',function(e){
                        e.preventDefault(); 
                        var pageno = $(this).attr('data-ci-pagination-page');
                        listPermissions(pageno);
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

    //CHANGE PERMISSION AJAX
    function changePermissions(role_permission_id){
        var role_permission_id=role_permission_id;
        $.ajax({
            type: "POST",
            url: baseUrl + "admin/settings/permissions",
            data: {
                role_permission_id : role_permission_id
            },
            dataType: 'json',
            async: false,
            success: function (data) {
                if (data.success) {
                    showAlert('success',appLanguage[0]['text_success'],data.message);
                } else {
                    showAlert('error',appLanguage[0]['text_error'],data.message);
                }
            },
            error: function () {
                showAlert('error',appLanguage[0]['text_error'],appLanguage[0]['alert_went_wrong']);
            }
        });
        
    }

    /*============================================
    APP SETTING AJAX
    ==============================================*/

    var appSettingsForm=$("#appSettingsForm");
    appSettingsForm.on('submit',function(e) {
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: baseUrl + "admin/settings/app",
            data: appSettingsForm.serialize(),
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
    });

    /*============================================
    MAIL SETTINGS
    ==============================================*/
    var emailSettingsForm=$("#emailSettingsForm");
    emailSettingsForm.validate({
        rules: {
            mail_from_title: {
                required: true
            },
            mail_from_email: {
                required: true,
                email: true,
                minlength: 4
            },
            mail_driver: {
                required: true
            }
        },
        messages: {
            mail_from_title: {
                required: appLanguage[0]['alert_mail_from_title']
            },
            mail_from_email: {
                required: appLanguage[0]['alert_enter_from_email'],
                email:appLanguage[0]['alert_enter_valid_email'],
                minlength: appLanguage[0]['alert_enter_valid_email_lenght']
            },
            mail_driver: {
                required: appLanguage[0]['alert_select_mail_driver']
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
            SEO SETTING AJAX
            ==============================================*/
            $.ajax({
                type: "POST",
                url: baseUrl + "admin/settings/email",
                data: emailSettingsForm.serialize(),
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

    /*============================================
    MAIL TEMPLATES
    ==============================================*/
    //CALL LIST TEMPLATES FUNCTION
    if(templatesList.length>0){
        listTemplates(0);
    }

    //WHEN CLICK EDIT BUTTON
    templatesList.on('click',"#editButton",function () {
        var template_id=$(this).attr("data-id");
        editTemplate(template_id);
    });

    //LIST TEMPLATES
    function listTemplates(page_num){
        $.ajax({
            type: 'POST',
            url: baseUrl+'admin/settings/list_email_templates/'+page_num,
            data:{template_id:0},
            dataType: 'json',
            async: false,
            beforeSend: function () {
                tileOverlay.show();
            },
            success: function (data) {
                if (data.success) {
                    //append data
                    templatesList.html(data.content);
                    //hide loader
                    tileOverlay.fadeOut("slow");
                    // Detect pagination click
                    $('#pagination a').on('click',function(e){
                        e.preventDefault(); 
                        var pageno = $(this).attr('data-ci-pagination-page');
                        listTemplates(pageno);
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

    // EDIT TEMPLATE
    function editTemplate(template_id){
        var template_id=template_id;
        //show panel
        sidePanel.slideReveal("show");
        //show loader
        sidePanelLoader.show();
        //ajax submit
        $.ajax({
            type: "POST",
            url: baseUrl+"admin/settings/edit_template",
            data: {
                template_id: template_id
            },
            dataType: 'json',
            async: false,
            success: function (data) {
                if (data.success) {
                    //load edit view
                    sidePanelContent.html(data.content);
                    //hide loader
                    sidePanelLoader.fadeOut();
                    $(".summertext").summernote();
                    var editTemplateForm=$("#editTemplateForm");  
                    var loader = Ladda.create( document.querySelector('#updateTemplateButton'));
                    editTemplateForm.validate({
                        rules: {
                            template_title: {
                                required: true
                            },
                            template_content: {
                                required: true
                            }
                        },
                        messages: {
                            template_name: {
                                required: appLanguage[0]['alert_enter_template_name']
                            },
                            template_content: {
                                required: appLanguage[0]['alert_enter_template_content']
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
                            // update ajax
                            $.ajax({
                                type: "POST",
                                url: baseUrl + "admin/settings/update_template",
                                data: editTemplateForm.serialize(),
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
                                        closePanel();
                                        //refresh list
                                        listTemplates(0);
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
                    
                } else {
                    showAlert('error',appLanguage[0]['text_error'],appLanguage[0]['alert_load_view_error']);
                    closePanel();
                }
            },
            error: function () {
                showAlert('error',appLanguage[0]['text_error'],appLanguage[0]['alert_went_wrong']);
            }
        });

    }

    //SHOW ALERT
    function showAlert(type,head,message){
        $.toast({heading: head ,text: message,loader: false,position : 'bottom-right',showHideTransition: 'fade', icon: type });
    }

    //CLOSE PANEL
    function closePanel(){
        sidePanel.slideReveal("hide");
    }
})(jQuery);