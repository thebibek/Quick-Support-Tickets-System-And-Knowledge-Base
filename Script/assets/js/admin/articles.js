(function ($) {
    'use strict';
    var sidePanel=$("#sidePanel");
    var baseUrl=$('base').attr("href");
    var appLanguage=[];
    var tileOverlay=$('.tile-overlay');
    var filterArticleForm=$("#filterArticleForm");
    var sidePanelLoader=$("#sidePanelLoader");
    var sidePanelContent=$("#sidePanelContent");
    var articlesList=$("#articlesList");
    var articleOrderingList=$("#articleOrderingList");
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
    ARTICLES
    ==============================================*/
    //ARTICLE TAB
    var articlesTab=$('#articlesTab');
    if(articlesTab.length>0){
        articlesTab.responsiveTabs({
            animation: 'slide',
            startCollapsed: 'accordion',
            startCollapsed: false // Start with the panels collapsed
        });
    }

    //CALL LIST ARTICLE FUNCTION
    if(articlesList.length>0){
        listArticles(0);
    }
    

    //FILTER ARTICLES
    filterArticleForm.on('submit',function(e){
        e.preventDefault();
        var loader = Ladda.create(document.querySelector('#filterButton'));
        loader.start();
        listArticles(0);
        loader.stop();
    });

    //WHEN CLICK VIEW BUTTON
    articlesList.on('click',"#viewButton",function () {
        var article_id=$(this).attr("data-id");
        viewArticle(article_id);
    });

    //WHEN CLICK ADD BUTTON
    $('#addArticleButton').on('click',function () {
        addArticle();
    });

    //WHEN CLICK EDIT BUTTON
    articlesList.on('click',"#editButton",function () {
        var article_id=$(this).attr("data-id");
        editArticle(article_id);
    });

    //WHEN CLICK PUBLISH BUTTON
    articlesList.on('click',"#publishButton",function () {
        var article_id=$(this).attr("data-id");
        publishArticle(article_id);
    });

    //WHEN CLICK CONFIRM PUBLISH BUTTON
    sidePanel.on('click',"#confirmPublishButton",function () {
        var article_id=$(this).attr("data-id");
        confirmPublishArticle(article_id);
    });

    //WHEN CLICK UNPUBLISH BUTTON
    articlesList.on('click',"#unpublishButton",function () {
        var article_id=$(this).attr("data-id");
        unpublishArticle(article_id);
    });

    //WHEN CLICK CONFIRM UNPUBLISH BUTTON
    sidePanel.on('click',"#confirmUnublishButton",function () {
        var article_id=$(this).attr("data-id");
        confirmUnpublishArticle(article_id);
    });

    //WHEN CHANGE SORTABLE CATEGORY
    var inputSortableCategory=$("#inputSortableCategory");
    inputSortableCategory.on('change',function(){
        var selectedCategory = inputSortableCategory.val();
        if(selectedCategory===null || selectedCategory===''){
            showAlert('error',appLanguage[0]['text_error'],appLanguage[0]['alert_select_category']);
            articleOrderingList.html('');
        }else{
            articleOrdering(selectedCategory)
        }
    });

    //WHEN CLICK DELETE BUTTON
    articlesList.on('click',"#deleteButton",function () {
        var article_id=$(this).attr("data-id");
        deleteArticle(article_id);
    });

    //WHEN CLICK CONFIRM DELETE BUTTON
    sidePanel.on('click',"#confirmDeleteButton",function () {
        var article_id=$(this).attr("data-id");
        confirmDeleteArticle(article_id);
    });

    //LIST ARTICLES
    function listArticles(page_num){
        var form=filterArticleForm;
        $.ajax({
            type: 'POST',
            url: baseUrl+'admin/articles/list_articles_ajax/'+page_num,
            data:form.serialize(),
            dataType: 'json',
            async: false,
            beforeSend: function () {
                tileOverlay.show();
            },
            success: function (data) {
                if (data.success) {
                    //append articles data
                    $('#articlesList').html(data.content);
                    //hide loader
                    tileOverlay.fadeOut("slow");
                    // Detect pagination click
                    $('#pagination a').on('click',function(e){
                        e.preventDefault(); 
                        var pageno = $(this).attr('data-ci-pagination-page');
                        listArticles(pageno);
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

    // VIEW ARTICLE  
    function viewArticle(article_id){
        var article_id=article_id;
        //show panel
        sidePanel.slideReveal("show");
        //show loader
        sidePanelLoader.show();
        //ajax submit
        $.ajax({
            type: "POST",
            url: baseUrl+"admin/article/view",
            data: {
                article_id: article_id
            },
            dataType: 'json',
            async: false,
            success: function (data) {
                if (data.success) {
                    //load view article view
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

    // ADD ARTICLE
    function addArticle(){
        //show loader
        sidePanelLoader.show();
        //ajax submit
        $.ajax({
            type: "POST",
            url: baseUrl+"admin/article/add",
            data: {
                article_id: 0
            },
            dataType: 'json',
            async: false,
            success: function (data) {
                if (data.success) {
                    //load add article view
                    sidePanelContent.html(data.content);
                    $(".summertext").summernote();
                    //hide loader
                    sidePanelLoader.fadeOut();
                    var addArticleForm=$("#addArticleForm");  
                    var loader = Ladda.create( document.querySelector('#createArticleButton'));
                    addArticleForm.validate({
                        rules: {
                            title: {
                                required: true
                            },
                            category: {
                                required: true
                            },
                            excerpt: {
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
                            excerpt: {
                                required: appLanguage[0]['alert_enter_excerpt']
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
                            // CREATE ARTICLE AJAX
                            $.ajax({
                                type: "POST",
                                url: baseUrl + "admin/article/create",
                                data: addArticleForm.serialize(),
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
                                        listArticles(0);
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

    // EDIT ARTICLE
    function editArticle(article_id){
        var article_id=article_id;
        //show panel
        sidePanel.slideReveal("show");
        //show loader
        sidePanelLoader.show();
        //ajax submit
        $.ajax({
            type: "POST",
            url: baseUrl+"admin/article/edit",
            data: {
                article_id: article_id
            },
            dataType: 'json',
            async: false,
            success: function (data) {
                if (data.success) {
                    //load edit article view
                    sidePanelContent.html(data.content);
                    $(".summertext").summernote();
                    //hide loader
                    sidePanelLoader.fadeOut();
                    var editArticleForm=$("#editArticleForm");  
                    var loader = Ladda.create( document.querySelector('#updateArticleButton'));
                    editArticleForm.validate({
                        rules: {
                            title: {
                                required: true
                            },
                            category: {
                                required: true
                            },
                            excerpt: {
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
                            excerpt: {
                                required: appLanguage[0]['alert_enter_excerpt']
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
                            // CREATE ARTICLE AJAX
                            $.ajax({
                                type: "POST",
                                url: baseUrl + "admin/article/update",
                                data: editArticleForm.serialize(),
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
                                        listArticles(0);
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

    // PUBLISH ARTICLE VIEW LOAD
    function publishArticle(article_id){
        var article_id=article_id;
        //show panel
        sidePanel.slideReveal("show");
        //show loader
        sidePanelLoader.show();
        //ajax submit
        $.ajax({
            type: "POST",
            url: baseUrl+"admin/article/publish",
            data: {
                article_id: article_id
            },
            dataType: 'json',
            async: false,
            success: function (data) {
                if (data.success) {
                    //load publish article view
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

    //CONFIRM PUBLISH ARTICLE
    function  confirmPublishArticle(article_id){
        var article_id=article_id;
        $.ajax({
            type: "POST",
            url: baseUrl+"admin/articles/publish_article_action",
            data: {
                article_id: article_id
            },
            dataType: 'json',
            async: false,
            success: function (data) {
                if (data.success) {
                    showAlert('success',appLanguage[0]['text_success'],data.message);
                    //close panel
                    closePanel();
                    //refresh list
                    listArticles(0);

                } else {
                    showAlert('error',appLanguage[0]['text_error'],data.message);
                }
            },
            error: function () {
                showAlert('error',appLanguage[0]['text_error'],appLanguage[0]['alert_went_wrong']);
            }
        });
    }

    // UNPUBLISH ARTICLE VIEW LOAD
    function unpublishArticle(article_id){
        var article_id=article_id;
        //show panel
        sidePanel.slideReveal("show");
        //show loader
        sidePanelLoader.show();
        //ajax submit
        $.ajax({
            type: "POST",
            url: baseUrl+"admin/article/unpublish",
            data: {
                article_id: article_id
            },
            dataType: 'json',
            async: false,
            success: function (data) {
                if (data.success) {
                    //load unpublish article view
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

    //CONFIRM UNPUBLISH ARTICLE
    function  confirmUnpublishArticle(article_id){
        var article_id=article_id;
        $.ajax({
            type: "POST",
            url: baseUrl+"admin/articles/unpublish_article_action",
            data: {
                article_id: article_id
            },
            dataType: 'json',
            async: false,
            success: function (data) {
                if (data.success) {
                    showAlert('success',appLanguage[0]['text_success'],data.message);
                    //close panel
                    closePanel();
                    //refresh list
                    listArticles(0);

                } else {
                    showAlert('error',appLanguage[0]['text_error'],data.message);
                }
            },
            error: function () {
                showAlert('error',appLanguage[0]['text_error'],appLanguage[0]['alert_went_wrong']);
            }
        });
    }

    //ARTICLE ORDERING
    function articleOrdering(category_id){
        var category_id=category_id;
        $.ajax({
            type: 'POST',
            url: baseUrl+'admin/article/ordering',
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
                    //append articles data
                    articleOrderingList.html(data.content);
                    //hide loader
                    tileOverlay.fadeOut("slow");
                    if(data.listed){
                        // make list as sortable
                        var sortableArticles = $( "#sortableArticles" );
                        sortableArticles.sortable();
                        sortableArticles.disableSelection();
                        var articleOrderingButton=$("#articleOrderingButton");  
                        var loader = Ladda.create( document.querySelector('#articleOrderingButton'));
                        articleOrderingButton.on('click',function(){
                            var list = new Array();
                            sortableArticles.find('.ui-state-default').each(function(){
                                var id=$(this).attr('data-id'); 
                                list.push(id);
                            });
                            var sorted_data=JSON.stringify(list);
                            // UPDATE ARTICLE ORDER AJAX
                            $.ajax({
                                type: "POST",
                                url: baseUrl + "admin/article/update_ordering",
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
                                        listArticles(0);
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

    // DELETE ARTICLE VIEW LOAD
    function deleteArticle(article_id){
        var article_id=article_id;
        //show panel
        sidePanel.slideReveal("show");
        //show loader
        sidePanelLoader.show();
        //ajax submit
        $.ajax({
            type: "POST",
            url: baseUrl+"admin/article/delete",
            data: {
                article_id: article_id
            },
            dataType: 'json',
            async: false,
            success: function (data) {
                if (data.success) {
                    //load delete article view
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

    //CONFIRM DELETE ARTICLE
    function confirmDeleteArticle(article_id){
        var article_id=article_id;
        $.ajax({
            type: "POST",
            url: baseUrl+"admin/articles/delete_article_action",
            data: {
                article_id: article_id
            },
            dataType: 'json',
            async: false,
            success: function (data) {
                if (data.success) {
                    showAlert('success',appLanguage[0]['text_success'],data.message);
                    //close panel
                    closePanel();
                    //refresh list
                    listArticles(0);

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
    function listCategories(page_num){
        $.ajax({
            type: 'POST',
            url: baseUrl+'admin/articles/list_categories_ajax/'+page_num,
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
            url: baseUrl+"admin/article/category/view",
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
            url: baseUrl+"admin/article/category/add",
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
                    //on category icon change
                    $("#inputCategoryIcon").on("change", function() {
                        var fileName = $(this).val().split("\\").pop();
                        $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
                    });
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
                            var category_icon = $('#inputCategoryIcon').prop('files')[0];
                            var form_data = new FormData();
                            form_data.append('title', $('#addCategoryForm input[name="title"]').val());
                            form_data.append('description', $('#addCategoryForm textarea[name="description"]').val());
                            form_data.append('category_icon', category_icon);
                            // CREATE ARTICLE CATEGORY AJAX
                            $.ajax({
                                type: "POST",
                                url: baseUrl + "admin/article/category/create",
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
            url: baseUrl+"admin/article/category/edit",
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
                    //on category icon change
                    $("#inputCategoryIcon").on("change", function() {
                        var fileName = $(this).val().split("\\").pop();
                        $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
                    });
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
                            var category_icon = $('#inputCategoryIcon').prop('files')[0];
                            var form_data = new FormData();
                            form_data.append('category_id', $('#editCategoryForm input[name="category_id"]').val());
                            form_data.append('title', $('#editCategoryForm input[name="title"]').val());
                            form_data.append('description', $('#editCategoryForm textarea[name="description"]').val());
                            form_data.append('category_icon', category_icon);
                            // UPDATE ARTICLE CATEGORY AJAX
                            $.ajax({
                                type: "POST",
                                url: baseUrl + "admin/article/category/update",
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
            url: baseUrl+'admin/article/category/ordering',
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
                        // UPDATE ARTICLE CATEGORY AJAX
                        $.ajax({
                            type: "POST",
                            url: baseUrl + "admin/article/category/update_ordering",
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

    function deleteCategory(category_id){
        var category_id=category_id;
        //show panel
        sidePanel.slideReveal("show");
        //show loader
        sidePanelLoader.show();
        //ajax submit
        $.ajax({
            type: "POST",
            url: baseUrl+"admin/article/category/delete",
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
                                // DELETE ARTICLE CATEGORY AJAX
                                $.ajax({
                                    type: "POST",
                                    url: baseUrl + "admin/articles/delete_category_action",
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
            url: baseUrl+"admin/articles/delete_category_action",
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