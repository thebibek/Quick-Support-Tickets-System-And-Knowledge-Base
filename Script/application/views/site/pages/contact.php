<!-- Sub Banner-->
<section class="pt10 pb10 bb1 br0 bl0 bt1 b-solid bc-grey">
    <div class="container">
        <div class="row">
            <div class="col-xl-8 col-lg-8 col-md-12 col-sm-12">
                <ul class="breadcrumb-default mt15 mb15">
                    <li><a href="<?= base_url(); ?>"><?= $this->lang->line("text_home"); ?></a></li>
                    <li class="active"><?= $this->lang->line("text_contact"); ?></li>
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
            <h3 class="mb5"><?= $this->lang->line("text_contact"); ?></h3>
            <h6 class="heading-thin text-theme-grey"><?= $this->lang->line("text_contact_subtitle"); ?></h6>
        </div>
        <form id="contactForm" name="contact-from" method="POST">
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
                <div class="col-12">
                    <div class="form-group">
                        <label for="inputSubject"><?= $this->lang->line("text_subject"); ?></label>
                        <input type="text" id="inputSubject" class="form-control" name="subject" placeholder="<?= $this->lang->line("text_enter_subject"); ?>">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <label for="inputMessage"><?= $this->lang->line("text_message"); ?></label>
                        <textarea id="inputMessage" class="form-control" name="message" placeholder="<?= $this->lang->line("text_enter_message"); ?>"></textarea>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <button id="contactButton" type="submit" class="btn btn-theme-secondary btn-lg btn-oval ladda-button" data-style="expand-right" data-size="xs"><span class="ladda-label"><i class="feather icon-send"></i> <?= $this->lang->line("text_send_message"); ?></span></button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>