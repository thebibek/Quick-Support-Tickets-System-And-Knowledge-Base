<?php if(isset($articles) && $articles!=NULL) { ?>
<div class="row" id="sortableArticlesHolder">
    <div class="col-12">
        <ul id="sortableArticles" class="sortable-list">
            <?php  foreach($articles as $article) { ?>
                <li data-id="<?= $article['id'];  ?>" class="ui-state-default"><i class="lni-arrows-vertical"></i> <?= $article['article_title']; ?></li>
            <?php }  ?>
        </ul>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="form-group">
            <button id="articleOrderingButton" class="btn btn-theme-secondary btn-lg btn-oval ladda-button" data-style="expand-right" data-size="xs"
                                    type="button"><span class="ladda-label"><i class="feather icon-save"></i> <?= $this->lang->line("text_save_order"); ?></span></button>
        </div>
    </div>
</div>
<?php }else { ?>
    <div class="text-center mt30 mb30">
        <div class="mb5"><img src="<?= base_url();?>assets/images/articles.svg" alt="Articles"></div>
        <p><?= $this->lang->line("text_not_found_articles"); ?></p>
    </div>
<?php } ?>