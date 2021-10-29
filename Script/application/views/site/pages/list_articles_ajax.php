<?php if (!empty($articles)): ?>
<div class="archives-holder mt20 mb20">
    <div class="archives-items">
        <ul>
            <?php foreach ($articles as $article): ?>
            <li>
                <a href="<?= base_url().'article/'.$article['slug'] ?>"><?= $article['article_title'] ?></a>
            </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>
<?php 
    else:
?>
<div class="text-center mt30 mb30">
    <div class="mb5"><img src="<?= base_url();?>assets/images/articles.svg" alt="Articles"></div>
    <p><?= $this->lang->line("text_not_found_articles"); ?></p>
</div>
<?php endif; ?>
<div id="pagination" class="mt10"><?= $pagination; ?></div>