<?php if (!empty($faqs)): foreach ($faqs as $faq): ?>
<div class="faq-list" id="actionCard<?= $faq['id']; ?>">
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
    <div class="action-dropdown">
        <div class="btn-group">
            <button type="button" class="btn btn-light btn-fab dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
            <div class="dropdown-menu dropdown-menu-right">
                <?php if($permissions['view_faq']==TRUE) { ?>
                    <button class="dropdown-item" type="button" id="viewButton" data-id="<?= $faq['id']; ?>"><i class="feather icon-eye"></i> <?= $this->lang->line("text_view"); ?></button>
                <?php } ?>
                <?php if($permissions['edit_faq']==TRUE) { ?>
                    <button class="dropdown-item" type="button" id="editButton" data-id="<?= $faq['id']; ?>"><i class="feather icon-edit"></i> <?= $this->lang->line("text_edit"); ?></button>
                <?php } ?>
                <?php if($permissions['faq_publishing']==TRUE) { ?>
                    <?php if($faq['status']==0) { ?>
                        <button class="dropdown-item" type="button" id="publishButton" data-id="<?= $faq['id']; ?>"><i class="feather icon-unlock"></i> <?= $this->lang->line("text_publish"); ?></button>
                    <?php } ?>
                    <?php if($faq['status']==1) { ?>
                        <button class="dropdown-item" type="button" id="unpublishButton" data-id="<?= $faq['id']; ?>"><i class="feather icon-lock"></i> <?= $this->lang->line("text_unpublish"); ?></button>
                    <?php } ?>
                <?php } ?>
                <?php if($permissions['delete_faq']==TRUE) { ?>
                    <button class="dropdown-item" type="button" id="deleteButton" data-id="<?= $faq['id']; ?>"><i class="feather icon-trash"></i> <?= $this->lang->line("text_delete"); ?></button>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
<?php endforeach;
    else:
?>
<div class="text-center mt30 mb30">
    <div class="mb5"><img src="<?= base_url();?>assets/images/faqs.svg" alt="FAQs"></div>
    <p><?= $this->lang->line("text_no_faq_found"); ?></p>
</div>
<?php endif; ?>
<div id="pagination" class="mt10"><?= $pagination; ?></div>