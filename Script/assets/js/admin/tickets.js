(function ($) {
    'use strict';
    var sidePanel=$("#sidePanel");
    var baseUrl=$('base').attr("href");
    var appLanguage=[];
    var tileOverlay=$('.tile-overlay');
    var filterTicketForm=$("#filterTicketForm");
    var sidePanelLoader=$("#sidePanelLoader");
    var sidePanelContent=$("#sidePanelContent");
    var ticketsList=$("#ticketsList");
    var categoriesList=$('#categoriesList');
    var categoriesOrdering=$('#categoriesOrdering');

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
    TICKETS
    ==============================================*/

    //CALL LIST ARTICLE FUNCTION
    if(ticketsList.length>0){
        listTickets(0);
    }
    

    //FILTER ARTICLES
    filterTicketForm.on('submit',function(e){
        e.preventDefault();
        var loader = Ladda.create(document.querySelector('#filterButton'));
        loader.start();
        listTickets(0);
        loader.stop();
    });

    //WHEN CLICK VIEW BUTTON
    ticketsList.on('click',"#viewButton",function () {
        var ticket_id=$(this).attr("data-id");
        viewTicket(ticket_id);
    });

    //WHEN CLICK VIEW BUTTON
    ticketsList.on('click',"#assignButton",function () {
        var ticket_id=$(this).attr("data-id");
        assignTicket(ticket_id);
    });

    //WHEN CLICK COMPLETE BUTTON
    ticketsList.on('click',"#completeButton",function () {
        var ticket_id=$(this).attr("data-id");
        completeTicket(ticket_id);
    });

    //WHEN CLICK CONFIRM COMPLETE BUTTON
    sidePanel.on('click',"#confirmCompleteButton",function () {
        var ticket_id=$(this).attr("data-id");
        confirmCompleteTicket(ticket_id);
    });

    //WHEN CLICK INCOMPLETE BUTTON
    ticketsList.on('click',"#incompleteButton",function () {
        var ticket_id=$(this).attr("data-id");
        incompleteTicket(ticket_id);
    });

    //WHEN CLICK CONFIRM INCOMPLETE BUTTON
    sidePanel.on('click',"#confirmIncompleteButton",function () {
        var ticket_id=$(this).attr("data-id");
        confirmIncompleteTicket(ticket_id);
    });

    //WHEN CLICK DELETE BUTTON
    ticketsList.on('click',"#deleteButton",function () {
        var ticket_id=$(this).attr("data-id");
        deleteTicket(ticket_id);
    });

    //WHEN CLICK CONFIRM DELETE BUTTON
    sidePanel.on('click',"#confirmDeleteButton",function () {
        var ticket_id=$(this).attr("data-id");
        confirmDeleteTicket(ticket_id);
    });

    //LIST TICKETS
    function listTickets(page_num){
        var form=filterTicketForm;
        $.ajax({
            type: 'POST',
            url: baseUrl+'admin/tickets/list_tickets_ajax/'+page_num,
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
                    $('.tile-overlay').fadeOut("slow");
                    // Detect pagination click
                    tileOverlay.on('click',function(e){
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

    // TICKET VIEW LOAD
    function viewTicket(ticket_id){
        var ticket_id=ticket_id;
        //show panel
        sidePanel.slideReveal("show");
        //show loader
        sidePanelLoader.show();
        //ajax submit
        $.ajax({
            type: "POST",
            url: baseUrl+"admin/ticket/view",
            data: {
                ticket_id: ticket_id
            },
            dataType: 'json',
            async: false,
            success: function (data) {
                if (data.success) {
                    //load view ticket view
                    sidePanelContent.html(data.content);
                     //hide loader
                    sidePanelLoader.fadeOut();
                    //enable ticket tab
                    $('#ticketsTab').responsiveTabs({
                        animation: 'slide',
                        startCollapsed: 'accordion',
                        startCollapsed: false // Start with the panels collapsed
                    });
                    //summernote
                    $(".summertext").summernote({
                        toolbar: [
                          ['style', ['bold', 'italic', 'underline', 'clear']],
                          ['para', ['ul', 'ol']]
                        ]
                    });
                    //ON REPLY ATTACHMENT CHANGE
                    $("#inputAttachment").on("change", function() {
                        var fileName = $(this).val().split("\\").pop();
                        $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
                    });
                    //SUBMIT REPLY FORM
                    var replyTicketForm=$("#replyTicketForm");
                    var loader = Ladda.create( document.querySelector('#replyTicketButton'));
                    replyTicketForm.validate({
                        rules: {
                            reply_content: {
                                required: true
                            }
                        },
                        messages: {
                            reply_content: {
                                required: appLanguage[0]['alert_enter_reply'],
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
                                url: baseUrl + "admin/ticket/reply",
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
                                        //close panel
                                        closePanel();
                                        //refresh list
                                        listTickets(0);
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

    //ASSIGN TICKET VIEW LOAD
    function assignTicket(ticket_id){
        var ticket_id=ticket_id;
        //show panel
        sidePanel.slideReveal("show");
        //show loader
        sidePanelLoader.show();
        //ajax submit
        $.ajax({
            type: "POST",
            url: baseUrl+"admin/ticket/assign",
            data: {
                ticket_id: ticket_id
            },
            dataType: 'json',
            async: false,
            success: function (data) {
                if (data.success) {
                    //load assign ticket view
                    sidePanelContent.html(data.content);
                    //hide loader
                    sidePanelLoader.fadeOut();
                    var assignTicketForm=$("#assignTicketForm");  
                    var loader = Ladda.create( document.querySelector('#assignTicketButton'));
                    assignTicketForm.validate({
                        rules: {
                            assign_to: {
                                required: true
                            },
                            priority: {
                                required: true
                            }
                        },
                        messages: {
                            assign_to: {
                                required: appLanguage[0]['alert_select_user']
                            },
                            priority: {
                                required: appLanguage[0]['alert_select_priority']
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
                            // ASSIGN TICKET AJAX
                            $.ajax({
                                type: "POST",
                                url: baseUrl + "admin/tickets/assign_ticket_action",
                                data: assignTicketForm.serialize(),
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
                                        listTickets(0);
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

    // COMPLETE TICKET VIEW LOAD
    function completeTicket(ticket_id){
        var ticket_id=ticket_id;
        //show panel
        sidePanel.slideReveal("show");
        //show loader
        sidePanelLoader.show();
        //ajax submit
        $.ajax({
            type: "POST",
            url: baseUrl+"admin/ticket/completed",
            data: {
                ticket_id: ticket_id
            },
            dataType: 'json',
            async: false,
            success: function (data) {
                if (data.success) {
                    //load complete ticket view
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

    //CONFIRM COMPLETE TICKET
    function confirmCompleteTicket(ticket_id){
        var ticket_id=ticket_id;
        $.ajax({
            type: "POST",
            url: baseUrl+"admin/tickets/mark_ticket_completed_action",
            data: {
                ticket_id: ticket_id
            },
            dataType: 'json',
            async: false,
            success: function (data) {
                if (data.success) {
                    showAlert('success',appLanguage[0]['text_success'],data.message);
                    //close panel
                    closePanel();
                    //refresh list
                    listTickets(0);

                } else {
                    showAlert('error',appLanguage[0]['text_error'],data.message);
                }
            },
            error: function () {
                showAlert('error',appLanguage[0]['text_error'],appLanguage[0]['alert_went_wrong']);
            }
        });
    }
    
    //INCOMPLETE TICKET
    function incompleteTicket(ticket_id){
        var ticket_id=ticket_id;
        //show panel
        sidePanel.slideReveal("show");
        //show loader
        sidePanelLoader.show();
        //ajax submit
        $.ajax({
            type: "POST",
            url: baseUrl+"admin/ticket/incompleted",
            data: {
                ticket_id: ticket_id
            },
            dataType: 'json',
            async: false,
            success: function (data) {
                if (data.success) {
                    //load complete ticket view
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

    //CONFIRM INCOMPLETE TICKET
    function confirmIncompleteTicket(ticket_id){
        var ticket_id=ticket_id;
        $.ajax({
            type: "POST",
            url: baseUrl+"admin/tickets/mark_ticket_incompleted_action",
            data: {
                ticket_id: ticket_id
            },
            dataType: 'json',
            async: false,
            success: function (data) {
                if (data.success) {
                    showAlert('success',appLanguage[0]['text_success'],data.message);
                    //close panel
                    closePanel();
                    //refresh list
                    listTickets(0);

                } else {
                    showAlert('error',appLanguage[0]['text_error'],data.message);
                }
            },
            error: function () {
                showAlert('error',appLanguage[0]['text_error'],appLanguage[0]['alert_went_wrong']);
            }
        });
    }

    // DELETE TICKET VIEW LOAD
    function deleteTicket(ticket_id){
        var ticket_id=ticket_id;
        //show panel
        sidePanel.slideReveal("show");
        //show loader
        sidePanelLoader.show();
        //ajax submit
        $.ajax({
            type: "POST",
            url: baseUrl+"admin/ticket/delete",
            data: {
                ticket_id: ticket_id
            },
            dataType: 'json',
            async: false,
            success: function (data) {
                if (data.success) {
                    //load delete ticket view
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

    //CONFIRM DELETE FAQ
    function confirmDeleteTicket(ticket_id){
        var ticket_id=ticket_id;
        $.ajax({
            type: "POST",
            url: baseUrl+"admin/tickets/delete_ticket_action",
            data: {
                ticket_id: ticket_id
            },
            dataType: 'json',
            async: false,
            success: function (data) {
                if (data.success) {
                    showAlert('success',appLanguage[0]['text_success'],data.message);
                    //close panel
                    closePanel();
                    //refresh list
                    listTickets(0);

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
    TICKETS CATEGORIES
    ==============================================*/

    //CATEGORIES TAB
    var categoryTab=$('#categoryTab');
    if(categoryTab.length>0){
        categoryTab.responsiveTabs({
            animation: 'slide',
            startCollapsed: 'accordion',
            startCollapsed: false // Start with the panels collapsed
        });
    }

    //CALL LIST CATEGORIES FUNCTION
    if(categoriesList.length>0){
        listCategories(0);
    }

    //WHEN CLICK VIEW BUTTON
    categoriesList.on('click',"#viewButton",function () {
        var category_id=$(this).attr("data-id");
        viewCategory(category_id);
    });

    //WHEN CLICK ADD BUTTON
    $('#addCategoryButton').on('click',function () {
        addCategory();
    });

    //WHEN CLICK EDIT BUTTON
    categoriesList.on('click',"#editButton",function () {
        var category_id=$(this).attr("data-id");
        editCategory(category_id);
    });

    //WHEN CLICK CATEGORY ORDERING
    $('#categoriesOrderingButton').on('click',function (e) {
        e.preventDefault();
        categoryOrdering();
    });

    //WHEN CLICK DELETE BUTTON
    categoriesList.on('click',"#deleteButton",function () {
        var category_id=$(this).attr("data-id");
        deleteCategory(category_id);
    });

    //WHEN CLICK CONFIRM DELETE BUTTON
    sidePanel.on('click',"#confirmDeleteCategoryButton",function () {
        var category_id=$(this).attr("data-id");
        var complete_delete=$(this).attr("data-complete-delete");
        confirmDeleteCategory(category_id,complete_delete);
    });

    //LIST CATEGORIES
    function listCategories(page_num){
        $.ajax({
            type: 'POST',
            url: baseUrl+'admin/tickets/list_categories_ajax/'+page_num,
            data:{
                page_num:page_num
            },
            dataType: 'json',
            async: false,
            beforeSend: function () {
                    tileOverlay.show();
            },
            success: function (data) {
                if (data.success) {
                    //append categories data
                    categoriesList.html(data.content);
                    //hide loader
                    tileOverlay.fadeOut("slow");
                    // Detect pagination click
                    $('#pagination a').on('click',function(e){
                        e.preventDefault(); 
                        var pageno = $(this).attr('data-ci-pagination-page');
                        listCategories(pageno);
                    });
                } else {
                    showAlert('error',appLanguage[0]['text_error'],appLanguage[0]['alert_load_view_error']);
                }
            },
            error: function () {
                showAlert('error',appLanguage[0]['text_error'],appLanguage[0]['alert_went_wrong']);
            }
        });
    }

    // CATEGORY VIEW LOAD
    function viewCategory(category_id){
        var category_id=category_id;
        //show panel
        sidePanel.slideReveal("show");
        //show loader
        sidePanelLoader.show();
        //ajax submit
        $.ajax({
            type: "POST",
            url: baseUrl+"admin/ticket/category/view",
            data: {
                category_id: category_id
            },
            dataType: 'json',
            async: false,
            success: function (data) {
                if (data.success) {
                    //load view category view
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

    // ADD CATEGORY
    function addCategory(){
        //show loader
        sidePanelLoader.show();
        //ajax submit
        $.ajax({
            type: "POST",
            url: baseUrl+"admin/ticket/category/add",
            data: {
                category_id: 0
            },
            dataType: 'json',
            async: false,
            success: function (data) {
                if (data.success) {
                    //load add category view
                    sidePanelContent.html(data.content);
                    //hide loader
                    sidePanelLoader.fadeOut();
                    var addCategoryForm=$("#addCategoryForm");  
                    var loader = Ladda.create( document.querySelector('#createCategoryButton'));
                    addCategoryForm.validate({
                        rules: {
                            title: {
                                required: true
                            },
                            description: {
                                required: true
                            },
                        },
                        messages: {
                            title: {
                                required: appLanguage[0]['alert_enter_title']
                            },
                            description: {
                                required: appLanguage[0]['alert_enter_description']
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
                            // CREATE TICKET CATEGORY AJAX
                            $.ajax({
                                type: "POST",
                                url: baseUrl + "admin/ticket/category/create",
                                data: addCategoryForm.serialize(),
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
                                        listCategories(0);
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

    // EDIT CATEGORY VIEW LOAD
    function editCategory(category_id){
        var category_id=category_id;
        //show panel
        sidePanel.slideReveal("show");
        //show loader
        sidePanelLoader.show();
        //ajax submit
        $.ajax({
            type: "POST",
            url: baseUrl+"admin/ticket/category/edit",
            data: {
                category_id: category_id
            },
            dataType: 'json',
            async: false,
            success: function (data) {
                if (data.success) {
                    //load edit category view
                    sidePanelContent.html(data.content);
                    //hide loader
                    sidePanelLoader.fadeOut();
                    var editCategoryForm=$("#editCategoryForm");  
                    var loader = Ladda.create( document.querySelector('#updateCategoryButton'));
                    editCategoryForm.validate({
                        rules: {
                            title: {
                                required: true
                            },
                            description: {
                                required: true
                            },
                        },
                        messages: {
                            title: {
                                required: appLanguage[0]['alert_enter_title']
                            },
                            description: {
                                required: appLanguage[0]['alert_enter_description']
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
                            // UPDATE TICKET CATEGORY AJAX
                            $.ajax({
                                type: "POST",
                                url: baseUrl + "admin/ticket/category/update",
                                data: editCategoryForm.serialize(),
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
                                        listCategories(0);
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

    //CATEGORY ORDERING
    function categoryOrdering(){
        $.ajax({
            type: 'POST',
            url: baseUrl+'admin/ticket/category/ordering',
            data: {
                category:0
            },
            dataType: 'json',
            async: false,
            beforeSend: function () {
                    tileOverlay.show();
            },
            success: function (data) {
                if (data.success) {
                    //append categories data
                    categoriesOrdering.html(data.content);
                    //hide loader
                    tileOverlay.fadeOut("slow");
                    // make list as sortable
                    var sortableCategories= $("#sortableCategories");
                    sortableCategories.sortable();
                    sortableCategories.disableSelection();
                    var categoryOrderingButton=$("#categoryOrderingButton");  
                    var loader = Ladda.create( document.querySelector('#categoryOrderingButton'));
                    categoryOrderingButton.on('click',function(){
                        var list = new Array();
                        sortableCategories.find('.ui-state-default').each(function(){
                            var id=$(this).attr('data-id'); 
                            list.push(id);
                        });
                        var sorted_data=JSON.stringify(list);
                        // UPDATE TICKET CATEGORY AJAX
                        $.ajax({
                            type: "POST",
                            url: baseUrl + "admin/ticket/category/update_ordering",
                            data: {
                                sorted_data:sorted_data
                            },
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
                                    //refresh list
                                    listCategories(0);
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
                } else {
                    showAlert('error',appLanguage[0]['text_error'],appLanguage[0]['alert_load_view_error']);
                }
            },
            error: function () {
                showAlert('error',appLanguage[0]['text_error'],appLanguage[0]['alert_went_wrong']);
            }
        });
    }

    // DELETE CATEGORY VIEW LOAD
    function deleteCategory(category_id){
        var category_id=category_id;
        //show panel
        sidePanel.slideReveal("show");
        //show loader
        sidePanelLoader.show();
        //ajax submit
        $.ajax({
            type: "POST",
            url: baseUrl+"admin/ticket/category/delete",
            data: {
                category_id: category_id
            },
            dataType: 'json',
            async: false,
            success: function (data) {
                if (data.success) {
                    //load delete category view
                    sidePanelContent.html(data.content);
                    //hide loader
                    sidePanelLoader.fadeOut();
                    if(data.exist){
                        var transferCategoryForm=$("#transferCategoryForm");  
                        var loader = Ladda.create( document.querySelector('#transferCategoryButton'));
                        transferCategoryForm.validate({
                            rules: {
                                transfer_category: {
                                    required: true
                                },
                            },
                            messages: {
                                transfer_category: {
                                    required: appLanguage[0]['alert_select_category']
                                },
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
                                // DELETE TICKET CATEGORY AJAX
                                $.ajax({
                                    type: "POST",
                                    url: baseUrl + "admin/tickets/delete_category_action",
                                    data: transferCategoryForm.serialize(),
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
                                            listCategories(0);
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

    //CONFIRM DELETE CATEGORY
    function confirmDeleteCategory(category_id,complete_delete){
        var category_id=category_id;
        var complete_delete=complete_delete;
        $.ajax({
            type: "POST",
            url: baseUrl+"admin/tickets/delete_category_action",
            data: {
                category_id: category_id,
                transfer_category: 0,
                complete_delete: complete_delete
            },
            dataType: 'json',
            async: false,
            success: function (data) {
                if (data.success) {
                    showAlert('success',appLanguage[0]['text_success'],data.message);
                    //close panel
                    closePanel();
                    //refresh list
                    listCategories(0);

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