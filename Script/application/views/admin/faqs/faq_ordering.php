<?php if(isset($faqs) && $faqs!=NULL) { ?>
<div class="row" id="sortableFaqHolder">
    <div class="col-12">
        <ul id="sortableFaq" class="sortable-list">
            <?php  foreach($faqs as $faq) { ?>
                <li data-id="<?= $faq['id'];  ?>" class="ui-state-default"><i class="lni-arrows-vertical"></i> <?= $faq['faq_title']; ?></li>
            <?php }  ?>
        </ul>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="form-group">
            <button id="faqOrderingButton" class="btn btn-theme-secondary btn-lg btn-oval ladda-button" data-style="expand-right" data-size="xs"
                                    type="button"><span class="ladda-label"><i class="feather icon-save"></i> <?= $this->lang->line("text_save_order"); ?></span></button>
        </div>
    </div>
</div>
<?php }else { ?>
    <div class="text-center mt30 mb30">
        <div class="mb5"><img src="<?= base_url();?>assets/images/faqs.svg" alt="FAQs"></div>
        <p><?= $this->lang->line("text_no_faq_found"); ?></p>
    </div>
<?php } ?>