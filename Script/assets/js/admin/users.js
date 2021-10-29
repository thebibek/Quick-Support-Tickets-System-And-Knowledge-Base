(function ($) {
    'use strict';
    var sidePanel=$("#sidePanel");
    var baseUrl=$('base').attr("href");
    var appLanguage=[];
    var tileOverlay=$('.tile-overlay');
    var filterUsersForm=$("#filterUsersForm");
    var sidePanelLoader=$("#sidePanelLoader");
    var sidePanelContent=$("#sidePanelContent");
    var usersList=$("#usersList");
    var permissionsList=$("#permissionsList");
    var filterPermissionForm=$("#filterPermissionForm");
   
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
    USERS
    ==============================================*/

    //CALL LIST USERS FUNCTION
    if(usersList.length>0){
        listUsers(0);
    }

    //FILTER USERS
    filterUsersForm.on('submit',function(e){
        e.preventDefault();
        var loader = Ladda.create(document.querySelector('#filterButton'));
        loader.start();
        listUsers(0);
        loader.stop();
    });

    //WHEN CLICK VIEW BUTTON
    usersList.on('click',"#viewButton",function () {
        var user_id=$(this).attr("data-id");
        viewUser(user_id);
    });

    //WHEN CLICK ADD BUTTON
    $('#addUserButton').on('click',function () {
        addUser();
    });

    //WHEN CLICK EDIT BUTTON
    usersList.on('click',"#editButton",function () {
        var user_id=$(this).attr("data-id");
        editUser(user_id);
    });

    //WHEN CLICK BLOCK BUTTON
    usersList.on('click',"#blockButton",function () {
        var user_id=$(this).attr("data-id");
        blockUser(user_id);
    });

    //WHEN CLICK CONFIRM BLOCK BUTTON
    sidePanel.on('click',"#confirmBlockButton",function () {
        var user_id=$(this).attr("data-id");
        confirmBlockUser(user_id);
    });

    //WHEN CLICK UNBLOCK BUTTON
    usersList.on('click',"#unblockButton",function () {
        var user_id=$(this).attr("data-id");
        unblockUser(user_id);
    });

    //WHEN CLICK CONFIRM UNBLOCK BUTTON
    sidePanel.on('click',"#confirmUnblockButton",function () {
        var user_id=$(this).attr("data-id");
        confirmUnblockUser(user_id);
    });

    //WHEN CLICK DELETE BUTTON
    usersList.on('click',"#deleteButton",function () {
        var user_id=$(this).attr("data-id");
        deleteUser(user_id);
    });

    //WHEN CLICK CONFIRM DELETE BUTTON
    sidePanel.on('click',"#confirmDeleteButton",function () {
        var user_id=$(this).attr("data-id");
        confirmDeleteUser(user_id);
    });

    //LIST USERS
    function listUsers(page_num) {
        var form=$("#filterUsersForm");
        $.ajax({
            type: 'POST',
            url: baseUrl+'admin/users/list_users_ajax/'+page_num,
            data:form.serialize(),
            dataType: 'json',
            async: false,
            beforeSend: function () {
                    tileOverlay.show();
            },
            success: function (data) {
                if (data.success) {
                    //append users data
                    usersList.html(data.content);
                    //hide loader
                    tileOverlay.fadeOut("slow");
                    // Detect pagination click
                    $('#pagination a').on('click',function(e){
                        e.preventDefault(); 
                        var pageno = $(this).attr('data-ci-pagination-page');
                        listUsers(pageno);
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

    // USER VIEW LOAD
    function viewUser(user_id){
        var user_id=user_id;
        //show panel
        sidePanel.slideReveal("show");
        //show loader
        sidePanelLoader.show();
        //ajax submit
        $.ajax({
            type: "POST",
            url: baseUrl+"admin/user/view",
            data: {
                user_id: user_id
            },
            dataType: 'json',
            async: false,
            success: function (data) {
                if (data.success) {
                    //load view user view
                    sidePanelContent.html(data.content);
                     //hide loader
                    sidePanelLoader.fadeOut();
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

    // ADD USER
    function addUser(){
        //show loader
        sidePanelLoader.show();
        //ajax submit
        $.ajax({
            type: "POST",
            url: baseUrl+"admin/user/add",
            data: {
                user_id: 0
            },
            dataType: 'json',
            async: false,
            success: function (data) {
                if (data.success) {
                    //load add user view
                    sidePanelContent.html(data.content);
                    //hide loader
                    sidePanelLoader.fadeOut();
                    var addUserForm=$("#addUserForm");  
                    var loader = Ladda.create( document.querySelector('#createUserButton'));
                    addUserForm.validate({
                        rules: {
                            user_type: {
                                required: true
                            },
                            full_name: {
                                required: true
                            },
                            email: {
                                required: true,
                                email: true,
                                minlength: 4
                            },
                            password: {
                                required: true,
                                minlength: 8
                            }
                        },
                        messages: {
                            user_type: {
                                required: appLanguage[0]['alert_select_user_type']
                            },
                            full_name: {
                                required: appLanguage[0]['alert_enter_fullname']
                            },
                            email: {
                                required: appLanguage[0]['alert_enter_email'],
                                email:appLanguage[0]['alert_enter_valid_email'],
                                minlength: appLanguage[0]['alert_enter_valid_email_lenght']
                            },
                            password: {
                                required: appLanguage[0]['alert_enter_password'],
                                minlength: appLanguage[0]['alert_valid_password_lenght']
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
                            // CREATE USER AJAX
                            $.ajax({
                                type: "POST",
                                url: baseUrl + "admin/user/create",
                                data: addUserForm.serialize(),
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
                                        listUsers(0);
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

    // EDIT USER VIEW LOAD
    function editUser(user_id){
        var user_id=user_id;
        //show panel
        sidePanel.slideReveal("show");
        //show loader
        sidePanelLoader.show();
        //ajax submit
        $.ajax({
            type: "POST",
            url: baseUrl+"admin/user/edit",
            data: {
                user_id: user_id
            },
            dataType: 'json',
            async: false,
            success: function (data) {
                if (data.success) {
                    //load edit user view
                    sidePanelContent.html(data.content);
                    //hide loader
                    sidePanelLoader.fadeOut();
                    var editUserForm=$("#editUserForm");  
                    var loader = Ladda.create( document.querySelector('#updateUserButton'));
                    editUserForm.validate({
                        rules: {
                            user_type: {
                                required: true
                            },
                            full_name: {
                                required: true
                            }
                        },
                        messages: {
                            user_type: {
                                required: appLanguage[0]['alert_select_user_type']
                            },
                            full_name: {
                                required: appLanguage[0]['alert_enter_fullname']
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
                            // UPDATE USER AJAX
                            $.ajax({
                                type: "POST",
                                url: baseUrl + "admin/user/update",
                                data: editUserForm.serialize(),
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
                                        listUsers(0);

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

    //BLOCK USER VIEW LOAD
    function blockUser(user_id){
        var user_id=user_id;
        //show panel
        sidePanel.slideReveal("show");
        //show loader
        sidePanelLoader.show();
        //ajax submit
        $.ajax({
            type: "POST",
            url: baseUrl+"admin/user/block",
            data: {
                user_id: user_id
            },
            dataType: 'json',
            async: false,
            success: function (data) {
                if (data.success) {
                    //load block user view
                    sidePanelContent.html(data.content);
                    //hide loader
                    sidePanelLoader.fadeOut();
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

    //CONFIRM BLOCK USER
    function  confirmBlockUser(user_id){
        var user_id=user_id;
        $.ajax({
            type: "POST",
            url: baseUrl+"admin/users/block_user_action",
            data: {
                user_id: user_id
            },
            dataType: 'json',
            async: false,
            success: function (data) {
                if (data.success) {
                    showAlert('success',appLanguage[0]['text_success'],data.message);
                    //close panel
                    closePanel();
                    //refresh users list
                    listUsers(0);

                } else {
                    showAlert('error',appLanguage[0]['text_error'],data.message);
                }
            },
            error: function () {
                showAlert('error',appLanguage[0]['text_error'],appLanguage[0]['alert_went_wrong']);
            }
        });
        
    }

    //UNBLOCK USER VIEW LOAD
    function unblockUser(user_id){
        var user_id=user_id;
        //show panel
        sidePanel.slideReveal("show");
        //show loader
        sidePanelLoader.show();
        //ajax submit
        $.ajax({
            type: "POST",
            url: baseUrl+"admin/user/unblock",
            data: {
                user_id: user_id
            },
            dataType: 'json',
            async: false,
            success: function (data) {
                if (data.success) {
                    //load unblock user view
                    sidePanelContent.html(data.content);
                    //hide loader
                    sidePanelLoader.fadeOut();
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
    
    //CONFIRM UNBLOCK USER
    function  confirmUnblockUser(user_id){
        var user_id=user_id;
        $.ajax({
            type: "POST",
            url: baseUrl+"admin/users/unblock_user_action",
            data: {
                user_id: user_id
            },
            dataType: 'json',
            async: false,
            success: function (data) {
                if (data.success) {
                    showAlert('success',appLanguage[0]['text_success'],data.message);
                    //close panel
                    closePanel();
                    //refresh users list
                    listUsers(0);

                } else {
                    showAlert('error',appLanguage[0]['text_error'],data.message);
                }
            },
            error: function () {
                showAlert('error',appLanguage[0]['text_error'],appLanguage[0]['alert_went_wrong']);
            }
        });
    }

    // DELETE USER VIEW LOAD
    function deleteUser(user_id){
        var user_id=user_id;
        //show panel
        sidePanel.slideReveal("show");
        //show loader
        sidePanelLoader.show();
        //ajax submit
        $.ajax({
            type: "POST",
            url: baseUrl+"admin/user/delete",
            data: {
                user_id: user_id
            },
            dataType: 'json',
            async: false,
            success: function (data) {
                if (data.success) {
                    //load delete user view
                    sidePanelContent.html(data.content);
                    //hide loader
                    sidePanelLoader.fadeOut();
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
    
    //CONFIRM DELETE USER
    function confirmDeleteUser(user_id){
        var user_id=user_id;
        $.ajax({
            type: "POST",
            url: baseUrl+"admin/users/delete_user_action",
            data: {
                user_id: user_id
            },
            dataType: 'json',
            async: false,
            success: function (data) {
                if (data.success) {
                    showAlert('success',appLanguage[0]['text_success'],data.message);
                    //close panel
                    closePanel();
                    //refresh users list
                    listUsers(0);

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
    PERMISSIONS
    ==============================================*/
    //FILTER PERMISSIONS
    filterPermissionForm.on('submit',function(e){
        e.preventDefault();
        var user=$("#inputUser").val();
        if(user===null||user===''){
            showAlert('error',appLanguage[0]['text_error'],appLanguage[0]['alert_select_user']);
        }else{
            var loader = Ladda.create( document.querySelector('#filterButton'));
            loader.start();
            listPermissions(0);
            loader.stop();
        }
    });

    //WHEN CLICK TOGGLE BUTTON
    permissionsList.on('click',".toggle-button",function () {
        var permission_id=$(this).attr("data-permission-id");
        var user_id=$(this).attr("data-user-id");
        var role_id=$(this).attr("data-role-id");
        changePermissions(permission_id,user_id,role_id);
    });

    //LIST PERMISSIONS
    function listPermissions(page_num){
        var form=filterPermissionForm;
        $.ajax({
            type: 'POST',
            url: baseUrl+'admin/users/list_permissions_ajax/'+page_num,
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
    function changePermissions(permission_id,user_id,role_id){
        var permission_id=permission_id;
        var user_id=user_id;
        var role_id=role_id;
        $.ajax({
            type: "POST",
            url: baseUrl + "admin/users/permissions",
            data: {
                permission_id : permission_id,
                user_id : user_id,
                role_id : role_id
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

    //SHOW ALERT
    function showAlert(type,head,message){
        $.toast({heading: head ,text: message,loader: false,position : 'bottom-right',showHideTransition: 'fade', icon: type });
    }

    //CLOSE PANEL
    function closePanel(){
        sidePanel.slideReveal("hide");
    }
})(jQuery);