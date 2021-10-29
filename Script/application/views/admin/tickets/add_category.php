<div class="tile mb30">
    <div class="tile-title-w-btn">
        <div class="title">
        <h3><?= $title; ?></h3>
        </div>
        <p><button class="btn btn-theme-grey btn-fab close-panel-button"><i class="feather icon-x"></i></button></p>
    </div>
    <div class="tile-content">
        <form id="addCategoryForm" action="#" method="POST">
            <div class="form-group">
                <label for="inputCategoryTitle"><?= $this->lang->line("text_title"); ?></label>
                <input type="text" id="inputCategoryTitle" class="form-control" name="title" placeholder="<?= $this->lang->line("text_enter_category_title"); ?>">
            </div>
            <div class="form-group">
                <label for="inputCategoryDescription"><?= $this->lang->line("text_description"); ?></label>
                <textarea id="inputCategoryDescription" class="form-control" name="description" placeholder="<?= $this->lang->line("text_enter_description"); ?>"></textarea>
            </div>
            <div class="form-group">
                <button id="createCategoryButton" class="btn btn-theme-secondary btn-lg btn-oval ladda-button" data-style="expand-right" data-size="xs"
                                    type="submit"><span class="ladda-label"><i class="feather icon-save"></i> <?= $this->lang->line("text_save_category"); ?></span></button>
            </div>
        </form>
    </div>
</div>
