<?php if($faq) { ?>
<div class="tile mb30">
    <div class="tile-title-w-btn">
        <div class="title">
        <h3><?= $title ?></h3>
        </div>
        <p><button class="btn btn-theme-grey btn-fab close-panel-button"><i class="feather icon-x"></i></button></p>
    </div>
    <div class="tile-content">
        <div class="faq-list mb20" id="actionCard<?= $faq['id']; ?>">
            <div class="media">
                <div class="image-holder"><img src="<?= base_url('assets/images/faq.jpg'); ?>" alt="image"></div>
                <div class="media-body">
                    <h6><?= $faq['faq_title']; ?></h6>
                    <p><i class="feather icon-briefcase text-theme-secondary"></i> <?= $faq['category_name']; ?></p>
                    <?php if($faq['status']==0) { ?>
                        <span class="badge badge-pill badge-danger"><?= $this->lang->line("text_unpublished"); ?></span>
                    <?php }elseif($faq['status']==1) { ?>
                        <span class="badge badge-pill badge-success"><?= $this->lang->line("text_published"); ?></span>
                    <?php } ?>

                </div>
            </div>
        </div>
        <p><?= $faq['faq_description']; ?></p>
        <ul class="list-group mt20">
            <li class="list-group-item d-flex justify-content-between align-items-center"> <?= $this->lang->line("text_created_on"); ?>
                <span>
                    <?php $created_on = strtotime($faq['created_on']); echo date("m/d/Y g:i A", $created_on); ?>
                </span>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center"> <?= $this->lang->line("text_updated_on"); ?>
                <span>
                    <?php $updated_on = strtotime($faq['updated_on']); echo date("m/d/Y g:i A", $updated_on); ?>
                </span>
            </li>
        </ul>
    </div>
</div>
<?php } ?>
