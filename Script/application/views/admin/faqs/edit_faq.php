<?php if($faq) { ?>
<div class="tile mb30">
    <div class="tile-title-w-btn">
        <div class="title">
        <h3><?= $title; ?></h3>
        </div>
        <p><button class="btn btn-theme-grey btn-fab close-panel-button"><i class="feather icon-x"></i></button></p>
    </div>
    <div class="tile-content">
        <form id="editFaqForm" action="#" method="POST">
            <div class="form-group">
                <label for="inputFaqCategory"><?= $this->lang->line("text_category"); ?></label>
                <select id="inputFaqCategory" class="form-control" name="category">
                    <option value="">--<?= $this->lang->line("text_select_category"); ?>--</option>
                    <?php if(isset($categories) && $categories!=NULL) { foreach($categories as $category) { ?>
                    <option value="<?= $category['id'] ?>" <?php if($category['id']==$faq['category_id']) { echo "selected"; } ?>><?= $category['category_name'] ?></option>
                    <?php } } ?>
                </select>
                <input type="hidden" name="faq_id" value="<?= $faq['id']; ?>">
            </div>
            <div class="form-group">
                <label for="inputFaqTitle"><?= $this->lang->line("text_title"); ?></label>
                <input type="text" id="inputFaqTitle" class="form-control" name="title" placeholder="<?= $this->lang->line("text_enter_title"); ?>" value="<?= $faq['faq_title'] ?>">
            </div>
            <div class="form-group">
                <label for="inputFaqContent"><?= $this->lang->line("text_content"); ?></label>
                <textarea id="inputFaqContent" class="form-control" name="content" placeholder="<?= $this->lang->line("text_enter_content"); ?>"><?= $faq['faq_description'] ?></textarea>
            </div>
            <div class="form-group">
                <button id="updateFaqButton" class="btn btn-theme-secondary btn-lg btn-oval ladda-button" data-style="expand-right" data-size="xs"
                                    type="submit"><span class="ladda-label"><i class="feather icon-save"></i> <?= $this->lang->line("text_update_faq"); ?></span></button>
            </div>
        </form>
    </div>
</div>
<?php } ?>