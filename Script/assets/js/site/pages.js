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
    HOME SEARCH
    ==============================================*/
    var mainSearch=$("#mainSearch");
    if(mainSearch.length>0){
        var $search_input = $("#InputSearch");
        var $search_results = $("#searchResults");
        var $search_suggestions = $("#searchSuggestions");
        $search_input.on('keyup',function () {
            let keyword=$(this).val();
            if(keyword.length>=3){
                $.ajax({
                    type: "POST",
                    url: baseUrl + "pages/get_search_suggestions",
                    data: {keyword:keyword},
                    dataType: 'json',
                    async: false,
                    success: function (data) {
                        if (data.success) {
                            $search_results.html('');
                            var results = [];
                            $.each(data.results, function(i, result) {
                                results.push('<li><a href="' + baseUrl + 'article/' + result.slug + '">' + result.article_title + '</a></li>');
                            });
                            $search_results.append(results.join(''));
                            $search_suggestions.show();
                        }else{
                            $search_suggestions.hide();
                        }
                    },
                    error: function () {
                        showAlert('error',appLanguage[0]['text_error'],appLanguage[0]['alert_went_wrong']);
                    }
                });
                
            }else{
                $search_suggestions.hide();
            }
        });
        $(document).on('mouseup',function(e) 
        {
            if (!$search_suggestions.is(e.target) && $search_suggestions.has(e.target).length === 0) 
            {
                $search_suggestions.hide();
            }
        });
    }

    /*============================================
    SEARCH ARTICLE
    ==============================================*/
    //CALL SEARCH ARTICLE FUNCTION
    var searchList=$('#searchList');
    if(searchList.length>0){
        var listOverlay=$('.list-overlay');
        var searchKeyword=searchList.attr("data-keyword");
        searchArticles(0,searchKeyword);
    }

    //SEARCH ARTICLES FUNCTION
    function searchArticles(page_num,keyword) {
        $.ajax({
            type: 'POST',
            url: baseUrl+'pages/search_result_ajax/'+page_num,
            data:{keyword: keyword},
            dataType: 'json',
            async: false,
            beforeSend: function () {
                listOverlay.show();
            },
            success: function (data) {
                if (data.success) {
                    //append articles data
                    searchList.html(data.content);
                    //hide loader
                    listOverlay.fadeOut("slow");
                    // Detect pagination click
                    $('#pagination a').on('click',function(e){
                        e.preventDefault(); 
                        var pageno = $(this).attr('data-ci-pagination-page');
                        searchArticles(pageno,keyword);
                    });
                } else {
                    //hide loader
                    listOverlay.fadeOut("slow");
                    searchList.html('No Data Loaded!');
                }
            },
            error: function () {
                //hide loader
                listOverlay.fadeOut("slow");
                searchList.html(appLanguage[0]['alert_went_wrong']);
            }
        });
    }

    /*============================================
    LIST ARTICLE
    ==============================================*/
    //LIST CALL LIST ARTICLE FUNCTION
    var articlesList=$('#articlesList');
    if(articlesList.length>0){
        var listOverlay=$('.list-overlay');
        var articleCategory=articlesList.attr("data-category-id");
        listArticles(0,articleCategory);
    }

    //LIST ARTICLES FUNCTION
    function listArticles(page_num,category) {
        $.ajax({
            type: 'POST',
            url: baseUrl+'pages/list_articles_ajax/'+page_num,
            data:{category: category},
            dataType: 'json',
            async: false,
            beforeSend: function () {
                listOverlay.show();
            },
            success: function (data) {
                if (data.success) {
                    //append articles data
                    articlesList.html(data.content);
                    //hide loader
                    listOverlay.fadeOut("slow");
                    // Detect pagination click
                    $('#pagination a').on('click',function(e){
                        e.preventDefault(); 
                        var pageno = $(this).attr('data-ci-pagination-page');
                        listArticles(pageno,category);
                    });
                } else {
                    //hide loader
                    listOverlay.fadeOut("slow");
                    articlesList.html(appLanguage[0]['alert_loading_list_error']);
                }
            },
            error: function () {
                //hide loader
                listOverlay.fadeOut("slow");
                articlesList.html(appLanguage[0]['alert_went_wrong']);
            }
        });
    }
    

    /*============================================
    VOTE ARTICLE
    ==============================================*/
    var voteButton=$(".vote-button");
    //WHEN CLICK VOTE BUTTON
    voteButton.on('click',function () {
        var article_id=$(this).attr("data-article-id");
        var vote_status=$(this).attr("data-vote");
        voteArticle(article_id,vote_status);
    });
    
    //VOTE ARTICLE FUNCTION
    function voteArticle(article_id,vote_status){
        var article_id=article_id;
        var vote_status=vote_status;
        var userMenu=$('#userMenu');
        if(userMenu.length>0){
            $.ajax({
                type: "POST",
                url: baseUrl+"user/article_voting",
                data: {
                    article_id: article_id,
                    vote_status: vote_status,
                },
                dataType: 'json',
                async: false,
                success: function (data) {
                    if (data.success) {
                        showAlert('success',appLanguage[0]['text_success'],data.message);
                        $('#totalCount').html(data.total_votes);
                        $('#helpCount').html(data.up_votes);
                    } else {
                        showAlert('error',appLanguage[0]['text_error'],data.message);
                    }
                },
                error: function () {
                    showAlert('error',appLanguage[0]['text_error'],appLanguage[0]['alert_went_wrong']);
                }
            });

        }else{
            showAlert('error',appLanguage[0]['text_error'],appLanguage[0]['alert_must_login']);
        }
    }

    /*============================================
    SUBMIT TICKET
    ==============================================*/
    var submitTicketForm=$("#submitTicketForm");
    if(submitTicketForm.length>0){
        var loader = Ladda.create( document.querySelector('#submitTicketButton'));
        //summernote
        $(".summertext").summernote({
            toolbar: [
              ['style', ['bold', 'italic', 'underline', 'clear']],
              ['para', ['ul', 'ol']]
            ]
        });
        submitTicketForm.validate({
            rules: {
                full_name: {
                    required: true
                },
                email: {
                    required: true,
                    email: true,
                    minlength: 4
                },
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
                full_name: {
                    required: appLanguage[0]['alert_enter_fullname']
                },
                email: {
                    required: appLanguage[0]['alert_enter_email'],
                    email:appLanguage[0]['alert_enter_valid_email'],
                    minlength: appLanguage[0]['alert_enter_valid_email_lenght']
                },
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
                form_data.append('full_name', $('#submitTicketForm input[name="full_name"]').val());
                form_data.append('email', $('#submitTicketForm input[name="email"]').val());
                form_data.append('ticket_title', $('#submitTicketForm input[name="ticket_title"]').val());
                form_data.append('ticket_description', $('#submitTicketForm textarea[name="ticket_description"]').val());
                form_data.append('category', $('#submitTicketForm select[name="category"]').val());
                form_data.append('priority', $('#submitTicketForm select[name="priority"]').val());
                form_data.append('attachment', attachment);
                //SUBMIT AJAX
                $.ajax({
                    type: "POST",
                    url: baseUrl + "submit-ticket",
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
                                window.location.replace(baseUrl + "submit-ticket");
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

        //ON REPLY ATTACHMENT CHANGE
        $("#inputAttachment").on("change", function() {
            var fileName = $(this).val().split("\\").pop();
            $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
        });
    }


    /*============================================
    CONTACT
    ==============================================*/
    var contactForm=$("#contactForm");
    if(contactForm.length>0){
        var loader = Ladda.create( document.querySelector('#contactButton'));
        contactForm.validate({
            rules: {
                full_name: {
                    required: true
                },
                email: {
                    required: true,
                    email: true,
                    minlength: 4
                },
                subject: {
                    required: true
                },
                message: {
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
                subject: {
                    required: appLanguage[0]['alert_enter_subject']
                },
                message: {
                    required: appLanguage[0]['alert_enter_message']
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
                //SUBMIT AJAX
                $.ajax({
                    type: "POST",
                    url: baseUrl + "contact",
                    data: contactForm.serialize(),
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
                                window.location.replace(baseUrl + "contact");
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
        $.toast({heading: head ,text: message,loader: false,position : 'bottom-right',showHideTransition: 'fade', icon: type });
    }

})(jQuery);