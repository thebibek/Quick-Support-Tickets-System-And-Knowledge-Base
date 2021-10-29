<?php if($article) { ?>
<div class="tile mb30">
    <div class="tile-title-w-btn">
        <div class="title">
        <h3><?= $title ?></h3>
        </div>
        <p><button class="btn btn-theme-grey btn-fab close-panel-button"><i class="feather icon-x"></i></button></p>
    </div>
    <div class="tile-content">
        <div class="article-list mb20">
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
        </div>
        <div class="excerpt">
            <em><?= $article['article_excerpt']; ?></em>
        </div>
        <p><?= html_entity_decode($article['article_description']); ?></p>
        <ul class="list-group mt20">
            <li class="list-group-item d-flex justify-content-between align-items-center"> <?= $this->lang->line("text_likes"); ?>
                <span>
                    <?= $article['usefull']; ?>
                </span>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center"> <?= $this->lang->line("text_dislikes"); ?>
                <span>
                    <?= $article['unusefull']; ?>
                </span>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center"> <?= $this->lang->line("text_created_on"); ?>
                <span>
                    <?php $created_on = strtotime($article['created_on']); echo date("m/d/Y g:i A", $created_on); ?>
                </span>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center"> <?= $this->lang->line("text_updated_on"); ?>
                <span>
                    <?php $updated_on = strtotime($article['updated_on']); echo date("m/d/Y g:i A", $updated_on); ?>
                </span>
            </li>
        </ul>

    </div>
</div>
<?php } ?>
