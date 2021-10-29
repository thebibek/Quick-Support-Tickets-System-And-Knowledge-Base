<div class="tile mb30">
    <div class="tile-title-w-btn">
        <div class="title">
        <h3><?= $title; ?></h3>
        </div>
        <p><button class="btn btn-theme-grey btn-fab close-panel-button"><i class="feather icon-x"></i></button></p>
    </div>
    <div class="tile-content">
        <form id="editTemplateForm" action="#" method="POST">
            <div class="form-group">
                <label for="inputTemplateTitle"><?= $this->lang->line("text_template_name"); ?></label>
                <input type="text" id="inputTemplateTitle" class="form-control" name="template_name" placeholder="<?= $this->lang->line("text_enter_template_name"); ?>" value="<?= $template['template_name']; ?>">
                <small><?= $this->lang->line("text_template_name_info"); ?></small>
                <input type="hidden" name="template_id" value="<?= $template['id']; ?>">
            </div>
            <div class="form-group">
                <label for="inputTemplateDescription"><?= $this->lang->line("text_template_content"); ?></label>
                <p><?= $this->lang->line("text_template_variables"); ?> 
                <?php 
                    $template_variables=explode(",",$template['template_variables']);
                    foreach($template_variables as $key => $val){
                ?>
                <span class="badge badge-success"><?= $val;  ?></span>
                <?php } ?>
                </p>
                <textarea id="inputTemplateDescription" class="summertext" name="template_content" placeholder="Template Content"><?= $template['template_content']; ?></textarea>
            </div>
            <div class="form-group">
                <button id="updateTemplateButton" class="btn btn-theme-secondary btn-lg btn-oval ladda-button" data-style="expand-right" data-size="xs"
                                    type="submit"><span class="ladda-label"><i class="feather icon-save"></i> <?= $this->lang->line("text_update_template"); ?></span></button>
            </div>
        </form>
    </div>
</div>