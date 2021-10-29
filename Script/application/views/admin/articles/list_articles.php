<div class="app-title">
    <div class="title-holder">
        <h1><i class="feather icon-file-text"></i> <?= $this->lang->line("text_articles"); ?></h1>
        <p><?= $this->lang->line("text_articles_subtitle"); ?></p>
    </div>
    <ul class="app-breadcrumb breadcrumb">
        <li class="breadcrumb-item"><a href="<?= base_url('admin/'); ?>"><i class="feather icon-home"></i></a></li>
        <li class="breadcrumb-item"><?= $this->lang->line("text_articles"); ?></li>
    </ul>
</div>
<div class="row">
    <div class="col-xl-3 col-lg-12 col-md-12">
        <div class="tile mb30">
            <div class="tile-title">
                <h3><i class="feather icon-filter"></i> <?= $this->lang->line("text_filters"); ?></h3>
            </div>
            <div class="tile-content">
                <form id="filterArticleForm" action="#" method="POST">
                    <div class="form-group">
                        <label for="inputKeyword"><?= $this->lang->line("text_keyword"); ?></label>
                        <input type="text" id="inputKeyword" class="form-control" name="keyword" placeholder="<?= $this->lang->line("text_enter_keyword"); ?>">
                    </div>
                    <div class="form-group">
                        <label for="inputCategory"><?= $this->lang->line("text_category"); ?></label>
                        <select id="inputCategory" class="form-control" name="category">
                            <option value="">--<?= $this->lang->line("text_select_category"); ?>--</option>
                            <?php if(isset($categories) && $categories!=NULL) { foreach($categories as $category) { ?>
                            <option value="<?= $category['id'] ?>"><?= $category['category_name'] ?></option>
                            <?php } } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="inputStatus"><?= $this->lang->line("text_status"); ?></label>
                        <select id="inputStatus" class="form-control" name="status">
                            <option value="">--<?= $this->lang->line("text_select_status"); ?>--</option>
                            <option value="PUBLISHED"><?= $this->lang->line("text_published"); ?></option>
                            <option value="UNPUBLISHED"><?= $this->lang->line("text_unpublished"); ?></option>
                        </select>
                    </div>
                    <div class="form-group">
                        <button id="filterButton" type="submit" class="btn btn-theme-secondary btn-lg btn-block btn-oval ladda-button" data-style="expand-right" data-size="xs"><span class="ladda-label"><i class="feather icon-filter"></i> <?= $this->lang->line("text_filter_articles"); ?></span></button>
                    </div>
                </form>  
            </div>
        </div>
    </div>
    <div class="col-xl-9 col-lg-12 col-md-12">
        <div class="tile mb30">
            <div class="tile-title-w-btn">
                <div class="title">
                    <h3><i class="feather icon-file-text"></i> <?= $this->lang->line("text_articles"); ?></h3>
                </div>
                <?php if($permissions['add_article']==TRUE) { ?>
                    <p><button type="button" id="addArticleButton" class="btn btn-theme-primary btn-oval trigger-button"><i class="lni-plus"></i> <?= $this->lang->line("text_add_article"); ?></button></p>
                <?php } ?>
            </div>
            <div id="articlesTab" class="tile-tabs">
                <ul>
                    <?php if($permissions['list_articles']==TRUE) { ?>
                        <li><a href="#articlesList"><?= $this->lang->line("text_articles_list"); ?></a></li>
                    <?php } ?>
                    <?php if($permissions['article_ordering']==TRUE) { ?>
                        <li><a href="#articlesOrdering"><?= $this->lang->line("text_articles_ordering"); ?></a></li>
                    <?php } ?>
                </ul>
                <?php if($permissions['list_articles']==TRUE) { ?>
                    <div id="articlesList"></div>
                <?php } ?>    
                    
                <?php if($permissions['article_ordering']==TRUE) { ?>
                    <div id="articlesOrdering">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="inputSortableCategory"><?= $this->lang->line("text_article_category"); ?></label>
                                    <select id="inputSortableCategory" class="form-control" name="sortable_category">
                                        <option value="">--<?= $this->lang->line("text_select_category"); ?>--</option>
                                        <?php if(isset($categories) && $categories!=NULL) { foreach($categories as $category) { ?>
                                        <option value="<?= $category['id'] ?>"><?= $category['category_name'] ?></option>
                                        <?php } } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div id="articleOrderingList"></div>
                    </div>
                <?php } ?>
            </div>
            <div class="tile-overlay" style="display: none;">
                <div class="m-loader mr-2">
                <svg class="m-circular" viewBox="25 25 50 50">
                <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="4" stroke-miterlimit="10"/>
                </svg>
                </div>
                <h3 class="l-text"><?= $this->lang->line("text_loading"); ?></h3>
            </div>
        </div>
    </div>
</div>

<div id="sidePanel" class="side-panel">
    <div class="side-panel-content-holder">
        <div class="side-panel-loader"  id="sidePanelLoader"><div class="loader-ripple"><div></div><div></div></div></div>
        <div class="side-panel-content"  id="sidePanelContent"></div>
    </div>
</div>