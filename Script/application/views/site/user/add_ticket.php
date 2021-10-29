<section class="inner-banner">
    <div class="container">
        <h4><?= $this->lang->line("text_hello"); ?>, <?= $user['full_name'] ?></h4>
    </div>
</section>
<section class="inner-section">
    <div class="container">
        <div class="row">
            <div class="col-xl-3 col-lg-4 col-md-12">
                <div class="tile mb30">
                    <div class="tile-content">
                        <?php $this->load->view('site/user/sidebar'); ?>
                    </div>
                </div>
            </div>
            <div class="col-xl-9 col-lg-8 col-md-12">
                <div class="tile mb30">
                    <div class="tile-title-w-btn">
                        <div class="title">
                            <h3><i class="feather icon-life-buoy"></i> <?= $this->lang->line("text_submit_ticket"); ?><h3>
                        </div>
                        <p><a href="<?= base_url(); ?>user/tickets" class="btn btn-theme-primary btn-oval"><i class="lni-list"></i> <?= $this->lang->line("text_back_to_list"); ?></a></p>
                    </div>
                    <div class="tile-content">
                        <form id="addTicketForm" name="add-ticket-from" method="POST">
                            <div class="form-group">
                                <label for="inputTicketTitle"><?= $this->lang->line("text_ticket_title"); ?></label>
                                <input type="text" id="inputTicketTitle" class="form-control" name="ticket_title" placeholder="<?= $this->lang->line("text_enter_ticket_title"); ?>">
                            </div>
                            <div class="form-group">
                                <label for="inputTicketDescription"><?= $this->lang->line("text_ticket_description"); ?></label>
                                <textarea id="inputTicketDescription" class="summertext" name="ticket_description" placeholder="<?= $this->lang->line("text_enter_ticket_description"); ?>"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="inputTicketCategory"><?= $this->lang->line("text_category"); ?></label>
                                <select id="inputTicketCategory" class="form-control" name="category">
                                    <option value="">--<?= $this->lang->line("text_select_category"); ?>--</option>
                                    <?php if(isset($categories) && $categories!=NULL) { foreach($categories as $category) { ?>
                                    <option value="<?= $category['id'] ?>"><?= $category['category_name'] ?></option>
                                    <?php } } ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="inputTicketPriority"><?= $this->lang->line("text_ticket_priority"); ?></label>
                                <select id="inputTicketPriority" class="form-control" name="priority">
                                    <option value="">--<?= $this->lang->line("text_select_ticket_priority"); ?>--</option>
                                    <option value="L"><?= $this->lang->line("text_low"); ?></option>
                                    <option value="M"><?= $this->lang->line("text_medium"); ?></option>
                                    <option value="H"><?= $this->lang->line("text_high"); ?></option>
                                    <option value="U"><?= $this->lang->line("text_urgent"); ?></option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="inputAttachment"><?= $this->lang->line("text_attachment"); ?></label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" name="attachment" id="inputAttachment">
                                    <label class="custom-file-label" for="inputAttachment"><?= $this->lang->line("text_choose_file"); ?></label>
                                </div>
                                <small><?= $this->lang->line("text_ticket_attachment_info"); ?></small>
                            </div>
                            <div class="form-group">
                                <button id="addTicketButton" type="submit" class="btn btn-theme-secondary btn-lg btn-oval ladda-button" data-style="expand-right" data-size="xs"><span class="ladda-label"><i class="feather icon-save"></i> <?= $this->lang->line("text_create_ticket"); ?></span></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>