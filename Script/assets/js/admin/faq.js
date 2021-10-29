(function ($) {
    'use strict';
    var sidePanel=$("#sidePanel");
    var baseUrl=$('base').attr("href");
    var appLanguage=[];
    var tileOverlay=$('.tile-overlay');
    var filterFaqForm=$("#filterFaqForm");
    var sidePanelLoader=$("#sidePanelLoader");
    var sidePanelContent=$("#sidePanelContent");
    var faqList=$("#faqList");
    var faqOrderingList=$("#faqOrderingList");
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
    FAQS
    ==============================================*/
    //FAQ TAB
    var faqTab=$('#faqTab');
    if(faqTab.length>0){
        faqTab.responsiveTabs({
            animation: 'slide',
            startCollapsed: 'accordion',
            startCollapsed: false // Start with the panels collapsed
        });
    }

    //CALL LIST FAQ FUNCTION
    if(faqList.length>0){
        listFaqs(0);
    }

    //FILTER FAQ
    filterFaqForm.on('submit',function(e){
        e.preventDefault();
        var loader = Ladda.create(document.querySelector('#filterButton'));
        loader.start();
        listFaqs(0);
        loader.stop();
    });

    //WHEN CLICK VIEW BUTTON
    faqList.on('click',"#viewButton",function () {
        var faq_id=$(this).attr("data-id");
        viewFaq(faq_id);
    });

    //WHEN CLICK ADD BUTTON
    $('#addFAQButton').on('click',function () {
        addFaq();
    });

    //WHEN CLICK EDIT BUTTON
    faqList.on('click',"#editButton",function () {
        var faq_id=$(this).attr("data-id");
        editFaq(faq_id);
    });

    //WHEN CLICK PUBLISH BUTTON
    faqList.on('click',"#publishButton",function () {
        var faq_id=$(this).attr("data-id");
        publishFaq(faq_id);
    });

    //WHEN CLICK CONFIRM PUBLISH BUTTON
    sidePanel.on('click',"#confirmPublishButton",function () {
        var faq_id=$(this).attr("data-id");
        confirmPublishFaq(faq_id);
    });

    //WHEN CLICK UNPUBLISH BUTTON
    faqList.on('click',"#unpublishButton",function () {
        var faq_id=$(this).attr("data-id");
        unpublishFaq(faq_id);
    });

    //WHEN CLICK CONFIRM UNPUBLISH BUTTON
    sidePanel.on('click',"#confirmUnublishButton",function () {
        var faq_id=$(this).attr("data-id");
        confirmUnpublishFaq(faq_id);
    });

    //WHEN CHANGE SORTABLE CATEGORY
    var inputSortableCategory=$("#inputSortableCategory");
    inputSortableCategory.on('change',function(){
        var selectedCategory = inputSortableCategory.val();
        if(selectedCategory===null || selectedCategory===''){
            showAlert('error',appLanguage[0]['text_error'],appLanguage[0]['alert_select_category']);
            faqOrderingList.html('');
        }else{
            faqOrdering(selectedCategory);
        }
    });

    //WHEN CLICK DELETE BUTTON
    faqList.on('click',"#deleteButton",function () {
        var faq_id=$(this).attr("data-id");
        deleteFaq(faq_id);
    });

    //WHEN CLICK CONFIRM DELETE BUTTON
    sidePanel.on('click',"#confirmDeleteButton",function () {
        var faq_id=$(this).attr("data-id");
        confirmDeleteFaq(faq_id);
    });

    //LIST FAQS
    function listFaqs(page_num) {
        var form=filterFaqForm;
        $.ajax({
            type: 'POST',
            url: baseUrl+'admin/faq/list_faqs_ajax/'+page_num,
            data:form.serialize(),
            dataType: 'json',
            async: false,
            beforeSend: function () {
                    tileOverlay.show();
            },
            success: function (data) {
                if (data.success) {
                    //append faqs data
                    faqList.html(data.content);
                    //hide loader
                    tileOverlay.fadeOut("slow");
                    // Detect pagination click
                    $('#pagination a').on('click',function(e){
                        e.preventDefault(); 
                        var pageno = $(this).attr('data-ci-pagination-page');
                        listFaqs(pageno);
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

    // VIEW FAQ
    function viewFaq(faq_id){
        var faq_id=faq_id;
        //show panel
        sidePanel.slideReveal("show");
        //show loader
        sidePanelLoader.show();
        //ajax submit
        $.ajax({
            type: "POST",
            url: baseUrl+"admin/faq/view",
            data: {
                faq_id: faq_id
            },
            dataType: 'json',
            async: false,
            success: function (data) {
                if (data.success) {
                    //load view faq view
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

    // ADD FAQ
    function addFaq(){
        //show loader
        sidePanelLoader.show();
        //ajax submit
        $.ajax({
            type: "POST",
            url: baseUrl+"admin/faq/add",
            data: {
                faq_id: 0
            },
            dataType: 'json',
            async: false,
            success: function (data) {
                if (data.success) {
                    //load add faq view
                    sidePanelContent.html(data.content);
                    //hide loader
                    sidePanelLoader.fadeOut();
                    var addFaqForm=$("#addFaqForm");  
                    var loader = Ladda.create( document.querySelector('#createFaqButton'));
                    addFaqForm.validate({
                        rules: {
                            title: {
                                required: true
                            },
                            category: {
                                required: true
                            },
                            content: {
                                required: true
                            },
                        },
                        messages: {
                            title: {
                                required: appLanguage[0]['alert_enter_title']
                            },
                            category: {
                                required: appLanguage[0]['alert_select_category']
                            },
                            content: {
                                required: appLanguage[0]['alert_enter_content']
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
                            // CREATE FAQ AJAX
                            $.ajax({
                                type: "POST",
                                url: baseUrl + "admin/faq/create",
                                data: addFaqForm.serialize(),
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
                                        listFaqs(0);
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

    // EDIT FAQ
    function editFaq(faq_id){
        var faq_id=faq_id;
        //show panel
        sidePanel.slideReveal("show");
        //show loader
        sidePanelLoader.show();
        //ajax submit
        $.ajax({
            type: "POST",
            url: baseUrl+"admin/faq/edit",
            data: {
                faq_id: faq_id
            },
            dataType: 'json',
            async: false,
            success: function (data) {
                if (data.success) {
                    //load edit faq view
                    sidePanelContent.html(data.content);
                    //hide loader
                    sidePanelLoader.fadeOut();
                    var editFaqForm=$("#editFaqForm");  
                    var loader = Ladda.create( document.querySelector('#updateFaqButton'));
                    editFaqForm.validate({
                        rules: {
                            title: {
                                required: true
                            },
                            category: {
                                required: true
                            },
                            content: {
                                required: true
                            },
                        },
                        messages: {
                            title: {
                                required: appLanguage[0]['alert_enter_title']
                            },
                            category: {
                                required: appLanguage[0]['alert_select_category']
                            },
                            content: {
                                required: appLanguage[0]['alert_enter_content']
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
                            // UPDATE FAQ AJAX
                            $.ajax({
                                type: "POST",
                                url: baseUrl + "admin/faq/update",
                                data: editFaqForm.serialize(),
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
                                        listFaqs(0);
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

    // PUBLISH FAQ VIEW LOAD
    function publishFaq(faq_id){
        var faq_id=faq_id;
        //show panel
        sidePanel.slideReveal("show");
        //show loader
        sidePanelLoader.show();
        //ajax submit
        $.ajax({
            type: "POST",
            url: baseUrl+"admin/faq/publish",
            data: {
                faq_id: faq_id
            },
            dataType: 'json',
            async: false,
            success: function (data) {
                if (data.success) {
                    //load publish faq view
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
    

    // CONFIRM PUBLISH FAQ
    function  confirmPublishFaq(faq_id){
        var faq_id=faq_id;
        $.ajax({
            type: "POST",
            url: baseUrl+"admin/faq/publish_faq_action",
            data: {
                faq_id: faq_id
            },
            dataType: 'json',
            async: false,
            success: function (data) {
                if (data.success) {
                    showAlert('success',appLanguage[0]['text_success'],data.message);
                    //close panel
                    closePanel();
                    //refresh list
                    listFaqs(0);

                } else {
                    showAlert('error',appLanguage[0]['text_error'],data.message);
                }
            },
            error: function () {
                showAlert('error',appLanguage[0]['text_error'],appLanguage[0]['alert_went_wrong']);
            }
        });
    }

    // UNPUBLISH FAQ VIEW LOAD
    function unpublishFaq(faq_id){
        var faq_id=faq_id;
        //show panel
        sidePanel.slideReveal("show");
        //show loader
        sidePanelLoader.show();
        //ajax submit
        $.ajax({
            type: "POST",
            url: baseUrl+"admin/faq/unpublish",
            data: {
                faq_id: faq_id
            },
            dataType: 'json',
            async: false,
            success: function (data) {
                if (data.success) {
                    //load unpublish faq view
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

    // CONFIRM UNPUBLISH FAQ
    function  confirmUnpublishFaq(faq_id){
        var faq_id=faq_id;
        $.ajax({
            type: "POST",
            url: baseUrl+"admin/faq/unpublish_faq_action",
            data: {
                faq_id: faq_id
            },
            dataType: 'json',
            async: false,
            success: function (data) {
                if (data.success) {
                    showAlert('success',appLanguage[0]['text_success'],data.message);
                    //close panel
                    closePanel();
                    //refresh list
                    listFaqs(0);

                } else {
                    showAlert('error',appLanguage[0]['text_error'],data.message);
                }
            },
            error: function () {
                showAlert('error',appLanguage[0]['text_error'],appLanguage[0]['alert_went_wrong']);
            }
        });
    }

    //FAQ ORDERING
    function faqOrdering(category_id){
        var category_id=category_id;
        $.ajax({
            type: 'POST',
            url: baseUrl+'admin/faq/ordering',
            data: {
                category_id:category_id
            },
            dataType: 'json',
            async: false,
            beforeSend: function () {
                    tileOverlay.show();
            },
            success: function (data) {
                if (data.success) {
                    //append faqs data
                    faqOrderingList.html(data.content);
                    //hide loader
                    tileOverlay.fadeOut("slow");
                    if(data.listed){
                        // make list as sortable
                        var sortableFaq = $( "#sortableFaq" );
                        sortableFaq.sortable();
                        sortableFaq.disableSelection();
                        var faqOrderingButton=$("#faqOrderingButton");  
                        var loader = Ladda.create( document.querySelector('#faqOrderingButton'));
                        faqOrderingButton.on('click',function(){
                            var list = new Array();
                            sortableFaq.find('.ui-state-default').each(function(){
                                var id=$(this).attr('data-id'); 
                                list.push(id);
                            });
                            var sorted_data=JSON.stringify(list);
                            // UPDATE FAQ ORDER AJAX
                            $.ajax({
                                type: "POST",
                                url: baseUrl + "admin/faq/update_ordering",
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
                                        listFaqs(0);
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
                    }
                        
                } else {
                    showAlert('error',appLanguage[0]['text_error'],appLanguage[0]['alert_load_view_error']);
                }
            },
            error: function () {
                showAlert('error',appLanguage[0]['text_error'],appLanguage[0]['alert_went_wrong']);
            }
        });
    }

    // DELETE FAQ VIEW LOAD
    function deleteFaq(faq_id){
        var faq_id=faq_id;
        //show panel
        sidePanel.slideReveal("show");
        //show loader
        sidePanelLoader.show();
        //ajax submit
        $.ajax({
            type: "POST",
            url: baseUrl+"admin/faq/delete",
            data: {
                faq_id: faq_id
            },
            dataType: 'json',
            async: false,
            success: function (data) {
                if (data.success) {
                    //load delete faq view
                    sidePanelContent.html(data.content);
                    //hide loader
                    sidePanelLoader.fadeOut();
                } else {
                    showAlert('error',appLanguage[0]['text_error'],appLanguage[0]['alert_load_view_error']);
                }
            },
            error: function () {
                showAlert('error',appLanguage[0]['text_error'],appLanguage[0]['alert_went_wrong']);
            }
        });

    }

    //CONFIRM DELETE FAQ
    function confirmDeleteFaq(faq_id){
        var faq_id=faq_id;
        $.ajax({
            type: "POST",
            url: baseUrl+"admin/faq/delete_faq_action",
            data: {
                faq_id: faq_id
            },
            dataType: 'json',
            async: false,
            success: function (data) {
                if (data.success) {
                    showAlert('success',appLanguage[0]['text_success'],data.message);
                    //close panel
                    closePanel();
                    //refresh list
                    listFaqs(0);

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
    ARTICLE CATEGORIES
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
    function listCategories(page_num) {
        $.ajax({
            type: 'POST',
            url: baseUrl+'admin/faq/list_categories_ajax/'+page_num,
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
            url: baseUrl+"admin/faq/category/view",
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
            url: baseUrl+"admin/faq/category/add",
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
                            // CREATE FAQ CATEGORY AJAX
                            $.ajax({
                                type: "POST",
                                url: baseUrl + "admin/faq/category/create",
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

    // EDIT CATEGORY
    function editCategory(category_id){
        var category_id=category_id;
        //show panel
        sidePanel.slideReveal("show");
        //show loader
        sidePanelLoader.show();
        //ajax submit
        $.ajax({
            type: "POST",
            url: baseUrl+"admin/faq/category/edit",
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
                            // UPDATE FAQ CATEGORY AJAX
                            $.ajax({
                                type: "POST",
                                url: baseUrl + "admin/faq/category/update",
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
            url: baseUrl+'admin/faq/category/ordering',
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
                        // UPDATE FAQ CATEGORY AJAX
                        $.ajax({
                            type: "POST",
                            url: baseUrl + "admin/faq/category/update_ordering",
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
            url: baseUrl+"admin/faq/category/delete",
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
                                // DELETE FAQ CATEGORY AJAX
                                $.ajax({
                                    type: "POST",
                                    url: baseUrl + "admin/faq/delete_category_action",
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
            url: baseUrl+"admin/faq/delete_category_action",
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