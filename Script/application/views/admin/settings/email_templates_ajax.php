<?php if (!empty($templates)): foreach ($templates as $template): ?>
    <div class="mini-list">
    <div class="media">
        <div class="icon-holder">
            <div class="icon">
                <i class="feather icon-mail"></i>
            </div>
        </div>
        <div class="media-body">
            <h6><?= $template['template_name']; ?></h6>
            <p><i class="feather icon-user text-theme-secondary"></i> <?php $updated_on = strtotime($template['updated_on']); echo date("d/m/Y g:i A", $updated_on); ?></p>
        </div>
    </div>
    <div class="action-dropdown">
        <div class="btn-group">
            <button type="button" class="btn btn-light btn-fab dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
            <div class="dropdown-menu dropdown-menu-right">
                <button class="dropdown-item" type="button" id="editButton" data-id="<?= $template['id']; ?>"><i class="feather icon-edit"></i> <?= $this->lang->line("text_edit"); ?></button>
            </div>
        </div>
    </div>
</div>
<?php endforeach;
    else:
?>
<div class="text-center mt30 mb30">
    <div class="mb5"><img src="<?= base_url();?>assets/images/categories.svg" alt="Categories"></div>
    <p><?= $this->lang->line("text_templates_not_found"); ?></p>
</div>
<?php endif; ?>
<div id="pagination" class="mt10"><?= $pagination; ?></div>