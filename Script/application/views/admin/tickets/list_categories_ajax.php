<?php if (!empty($categories)): foreach ($categories as $category): ?>
<div class="category-list">
    <div class="media">
        <div class="image-holder"><img src="<?= base_url('assets/images/'); ?>category.jpg" alt="image"></div>
        <div class="media-body">
            <h6><?= $category['category_name'];?></h6>
            <p><i class="feather icon-life-buoy text-theme-secondary"></i> <?= $category['num_tickets'];?> <?= $this->lang->line("text_tickets"); ?></p>
        </div>
    </div>
    <div class="action-dropdown">
        <div class="btn-group">
            <button type="button" class="btn btn-light btn-fab dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
            
            <div class="dropdown-menu dropdown-menu-right">
                <?php if($permissions['view_ticket_category']==TRUE) { ?>
                    <button class="dropdown-item" type="button" id="viewButton" data-id="<?= $category['id']; ?>"><i class="feather icon-eye"></i> <?= $this->lang->line("text_view"); ?></button>
                <?php } ?>
                <?php if($permissions['edit_ticket_category']==TRUE) { ?>
                    <button class="dropdown-item" type="button" id="editButton" data-id="<?= $category['id']; ?>"><i class="feather icon-edit"></i> <?= $this->lang->line("text_edit"); ?></button>
                <?php } ?>
                <?php if($permissions['delete_ticket_category']==TRUE) { ?>
                    <button class="dropdown-item" type="button" id="deleteButton" data-id="<?= $category['id']; ?>"><i class="feather icon-trash"></i> <?= $this->lang->line("text_delete"); ?></button>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
<?php endforeach;
    else:
?>
<div class="text-center mt30 mb30">
    <div class="mb5"><img src="<?= base_url();?>assets/images/categories.svg" alt="Categories"></div>
    <p><?= $this->lang->line("text_categories_not_found"); ?></p>
</div>
<?php endif; ?>
<div id="pagination" class="mt10"><?= $pagination; ?></div>