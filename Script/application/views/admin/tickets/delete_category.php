<div class="tile mb30">
    <div class="tile-title-w-btn">
        <div class="title">
        <h3><?= $title; ?></h3>
        </div>
        <p><button class="btn btn-theme-grey btn-fab close-panel-button"><i class="feather icon-x"></i></button></p>
    </div>
    <div class="tile-content">
        <?php if($is_exist) { ?>
        <div class="text-center mt50 mb50">
            <div class="mb15"><img src="<?= base_url();?>assets/images/delete-category.svg" alt="<?= $title; ?>"></div>
            <p><?= $this->lang->line("text_ticket_category_transfer_and_delete_info"); ?></p>
            <form id="transferCategoryForm" action="#" method="POST">
                <div class="form-group">
                    <select id="inputFaqCategory" class="form-control" name="transfer_category">
                        <option value="">--<?= $this->lang->line("text_select_category"); ?>--</option>
                        <?php if(isset($categories) && $categories!=NULL) { foreach($categories as $category) { ?>
                        <option value="<?= $category['id'] ?>"><?= $category['category_name'] ?></option>
                        <?php } } ?>
                    </select>
                    <input type="hidden" name="category_id" value="<?= $category_id ?>">
                    <input type="hidden" name="complete_delete" value="0">
                </div>

                <div class="form-group">
                    <button id="transferCategoryButton" class="btn btn-warning btn-lg btn-oval ladda-button" data-style="expand-right" data-size="xs"
                                        type="submit"><span class="ladda-label"><i class="feather icon-trash"></i> <?= $this->lang->line("text_transfer_and_delete"); ?></span></button>
                    <button type="button" class="btn btn-danger btn-lg btn-oval" id="confirmDeleteCategoryButton" data-id="<?= $category_id; ?>" data-complete-delete="1"><i class="feather icon-trash-2"></i> <?= $this->lang->line("text_complete_delete"); ?></button>
                    <button type="button" class="btn btn-outline-theme-grey btn-lg btn-oval close-panel-button"><i class="feather icon-x-circle"></i> <?= $this->lang->line("text_cancel"); ?></button>
                </div>
            </form>
        </div>
        <?php } else { ?>
        <div class="text-center mt50 mb50">
            <div class="mb15"><img src="<?= base_url();?>assets/images/delete-category.svg" alt="<?= $title; ?>"></div>
            <p><?= $this->lang->line("text_delete_ticket_category_info"); ?></p>
            <p>
                <button class="btn btn-danger btn-lg btn-oval" id="confirmDeleteCategoryButton" data-id="<?= $category_id; ?>" data-complete-delete="0"><i class="feather icon-trash-2"></i> <?= $this->lang->line("text_delete"); ?></button>
                <button class="btn btn-outline-theme-grey btn-lg btn-oval close-panel-button"><i class="feather icon-x-circle"></i> <?= $this->lang->line("text_cancel"); ?></button>
            </p>
        </div>
        <?php } ?>
    </div>
</div>

