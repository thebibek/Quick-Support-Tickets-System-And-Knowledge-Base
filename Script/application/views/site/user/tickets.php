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
                            <h3><i class="feather icon-life-buoy"></i> <?= $this->lang->line("text_tickets"); ?><h3>
                        </div>
                        <p>
                            <a href="javascript:void(0)"  data-toggle="modal" data-target="#filterTicketModal" class="btn btn-theme-grey btn-fab"><i class="lni-funnel"></i></a>
                            <a href="<?= base_url(); ?>user/submit-ticket" class="btn btn-theme-primary btn-oval"><i class="lni-plus"></i> <?= $this->lang->line("text_submit_ticket"); ?></a>
                            
                        </p>
                    </div>
                    <div class="tile-content">
                        
                        <div id="ticketsList"></div>
                    </div>
                    <div class="tile-overlay" style="display: none;">
                        <div class="m-loader mr-2">
                        <svg class="m-circular" viewBox="25 25 50 50">
                        <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="4" stroke-miterlimit="10"/>
                        </svg>
                        </div>
                        <h3 class="l-text"><?= $this->lang->line("text_loading"); ?></h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- The Modal -->
<div class="modal" id="filterTicketModal">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
    <form id="filterTicketForm" action="#" method="POST">  
      <!-- Modal Header -->
      <div class="modal-header">
        <h6 class="modal-title"><i class="feather icon-filter"></i> <?= $this->lang->line("text_filters"); ?></h6>
        <button type="button" class="close" data-dismiss="modal"><i class="lni-close"></i></button>
      </div>

        <!-- Modal body -->
        <div class="modal-body">
            <div class="form-group">
                <label for="inputKeyword"><?= $this->lang->line("text_keyword"); ?></label>
                <input type="text" id="inputKeyword" class="form-control" name="keyword" placeholder="<?= $this->lang->line("text_enter_keyword"); ?>">
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
                <label for="inputStatus"><?= $this->lang->line("text_status"); ?></label>
                <select id="inputStatus" class="form-control" name="status">
                    <option value="">--<?= $this->lang->line("text_select_status"); ?>--</option>
                    <option value="NEW"><?= $this->lang->line("text_new"); ?></option>
                    <option value="INPROGRESS"><?= $this->lang->line("text_in_progress"); ?></option>
                    <option value="CLOSED"><?= $this->lang->line("text_closed"); ?></option>
                </select>
            </div>
        </div>
        <!-- Modal footer -->
        <div class="modal-footer">
        <button id="filterButton" type="submit" class="btn btn-theme-secondary btn-lg btn-block btn-oval ladda-button" data-style="expand-right" data-size="xs"><span class="ladda-label"><i class="feather icon-filter"></i> <?= $this->lang->line("text_filter_tickets"); ?></span></button>
        </div>
      </form> 

    </div>
  </div>
</div>