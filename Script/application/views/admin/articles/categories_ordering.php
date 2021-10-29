<?php if(isset($categories) && $categories!=NULL) { ?>
<div class="row" id="sortableCategoryHolder">
    <div class="col-12">
        <ul id="sortableCategories" class="sortable-list">
            <?php  foreach($categories as $category) { ?>
            <li data-id="<?= $category['id'];  ?>" class="ui-state-default"><i class="lni-arrows-vertical"></i> <?= $category['category_name']; ?></li>
            <?php }  ?>
        </ul>
        
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="form-group">
            <button id="categoryOrderingButton" class="btn btn-theme-secondary btn-lg btn-oval ladda-button" data-style="expand-right" data-size="xs"
                                    type="button"><span class="ladda-label"><i class="feather icon-save"></i> <?= $this->lang->line("text_save_order"); ?></span></button>
        </div>
    </div>
</div>
<?php } else {  ?>
    <div class="text-center mt30 mb30">
        <div class="mb5"><img src="<?= base_url();?>assets/images/categories.svg" alt="Categories"></div>
        <p><?= $this->lang->line("text_categories_not_found"); ?></p>
    </div>
<?php }  ?>