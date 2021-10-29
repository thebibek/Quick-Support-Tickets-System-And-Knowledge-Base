<?php if (!empty($articles)): foreach ($articles as $article): ?>
<div class="article-list" id="actionCard<?= $article['id']; ?>">
    <div class="media">
        <div class="image-holder"><img src="<?= base_url('assets/images/article.jpg'); ?>" alt="article"></div>
        <div class="media-body">
            <h6><?= $article['article_title']; ?></h6>
            <p><i class="feather icon-briefcase text-theme-secondary"></i> <?= $article['category_name']; ?></p>
            <?php if($article['status']==0) { ?>
                <span class="badge badge-pill badge-danger"><?= $this->lang->line("text_unpublished"); ?></span>
            <?php }elseif($article['status']==1) { ?>
                <span class="badge badge-pill badge-success"><?= $this->lang->line("text_published"); ?></span>
            <?php } ?>
        </div>
    </div>
    <div class="article-votes">
        <ul>
            <li><span class="badge badge-pill badge-success"><i class="lni-thumbs-up"></i> <?= $article['usefull']; ?></span><span class="label"> <?= $this->lang->line("text_likes"); ?></span></li>
            <li><span class="badge badge-pill badge-danger"><i class="lni-thumbs-down"></i> <?= $article['unusefull']; ?></span><span class="label"> <?= $this->lang->line("text_dislikes"); ?></span></li>
        </ul>
    </div>
    <div class="action-dropdown">
        <div class="btn-group">
            <button type="button" class="btn btn-light btn-fab dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
            
            <div class="dropdown-menu dropdown-menu-right">
                <?php if($permissions['view_article']==TRUE) { ?>
                    <button class="dropdown-item" type="button" id="viewButton" data-id="<?= $article['id']; ?>"><i class="feather icon-eye"></i> <?= $this->lang->line("text_view"); ?></button>
                <?php } ?>
                <?php if($permissions['edit_article']==TRUE) { ?>
                    <button class="dropdown-item" type="button" id="editButton" data-id="<?= $article['id']; ?>"><i class="feather icon-edit"></i> <?= $this->lang->line("text_edit"); ?></button>
                <?php } ?>
                <?php if($permissions['publishing_article']==TRUE) { ?>
                    <?php if($article['status']==0) { ?>
                        <button class="dropdown-item" type="button" id="publishButton" data-id="<?= $article['id']; ?>"><i class="feather icon-unlock"></i> <?= $this->lang->line("text_publish"); ?></button>
                    <?php } ?>
                    <?php if($article['status']==1) { ?>
                        <button class="dropdown-item" type="button" id="unpublishButton" data-id="<?= $article['id']; ?>"><i class="feather icon-lock"></i> <?= $this->lang->line("text_unpublish"); ?></button>
                    <?php } ?>
                <?php } ?>
                <?php if($permissions['delete_article']==TRUE) { ?>
                    <button class="dropdown-item" type="button" id="deleteButton" data-id="<?= $article['id']; ?>"><i class="feather icon-trash"></i> <?= $this->lang->line("text_delete"); ?></button>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
<?php endforeach;
    else:
?>
<div class="text-center mt30 mb30">
    <div class="mb5"><img src="<?= base_url();?>assets/images/articles.svg" alt="Articles"></div>
    <p><?= $this->lang->line("text_not_found_articles"); ?></p>
</div>
<?php endif; ?>
<div id="pagination" class="mt10"><?= $pagination; ?></div>