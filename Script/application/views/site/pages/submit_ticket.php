<!-- Sub Banner-->
<section class="pt10 pb10 bb1 br0 bl0 bt1 b-solid bc-grey">
    <div class="container">
        <div class="row">
            <div class="col-xl-8 col-lg-8 col-md-12 col-sm-12">
                <ul class="breadcrumb-default mt15 mb15">
                    <li><a href="<?= base_url(); ?>"><?= $this->lang->line("text_home"); ?></a></li>
                    <li class="active"><?= $this->lang->line("text_submit_ticket"); ?></li>
                </ul>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12">
                <div class="search-holder mt10 mb10">
                    <form class="sub-search-form" method="GET" action="<?= base_url() ?>search">
                        <div class="input-group">
                            <input class="form-control" name="s" type="text" placeholder="<?= $this->lang->line("text_search_here"); ?>">
                            <div class="input-group-append">
                                <button class="btn btn-light btn-flat" type="submit"><i class="lni-search"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Submit Ticket-->
<section class="pt40 pb60">
    <div class="container">
        <div class="mt20 mb20">
            <h3 class="mb5"><?= $this->lang->line("text_submit_ticket"); ?></h3>
            <h6 class="heading-thin text-theme-grey"><?= $this->lang->line("text_submit_ticket_subtitle"); ?></h6>
        </div>
        <form id="submitTicketForm" name="submit-ticket-from" method="POST">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-12">
                    <div class="form-group">
                        <label for="inputName"><?= $this->lang->line("text_full_name"); ?></label>
                        <input type="text" id="inputName" class="form-control" name="full_name" placeholder="<?= $this->lang->line("text_enter_full_name"); ?>" autofocus>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-12">
                    <div class="form-group">
                        <label for="inputEmail"><?= $this->lang->line("text_email"); ?></label>
                        <input type="email" id="inputEmail" class="form-control" name="email" placeholder="<?= $this->lang->line("text_enter_email"); ?>">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 col-md-6 col-12">
                    <div class="form-group">
                        <label for="inputTicketCategory"><?= $this->lang->line("text_category"); ?></label>
                        <select id="inputTicketCategory" class="form-control" name="category">
                            <option value="">--<?= $this->lang->line("text_select_category"); ?>--</option>
                            <?php if(isset($categories) && $categories!=NULL) { foreach($categories as $category) { ?>
                            <option value="<?= $category['id'] ?>"><?= $category['category_name'] ?></option>
                            <?php } } ?>
                        </select>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-12">
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
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <label for="inputTicketTitle"><?= $this->lang->line("text_ticket_title"); ?></label>
                        <input type="text" id="inputTicketTitle" class="form-control" name="ticket_title" placeholder="<?= $this->lang->line("text_enter_ticket_title"); ?>">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <label for="inputTicketDescription"><?= $this->lang->line("text_ticket_description"); ?></label>
                        <textarea id="inputTicketDescription" class="summertext" name="ticket_description" placeholder="<?= $this->lang->line("text_enter_ticket_description"); ?>"></textarea>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <label for="inputAttachment"><?= $this->lang->line("text_attachment"); ?></label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" name="attachment" id="inputAttachment">
                            <label class="custom-file-label" for="inputAttachment"><?= $this->lang->line("text_choose_file"); ?></label>
                        </div>
                        <small><?= $this->lang->line("text_ticket_attachment_info"); ?></small>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <button id="submitTicketButton" type="submit" class="btn btn-theme-secondary btn-lg btn-oval ladda-button" data-style="expand-right" data-size="xs"><span class="ladda-label"><i class="feather icon-save"></i> <?= $this->lang->line("text_create_ticket"); ?></span></button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>