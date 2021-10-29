<div class="app-title">
    <div class="title-holder">
        <h1><i class="feather icon-life-buoy"></i> <?= $this->lang->line("text_tickets"); ?></h1>
        <p><?= $this->lang->line("text_tickets_subtitle"); ?></p>
    </div>
    <ul class="app-breadcrumb breadcrumb">
        <li class="breadcrumb-item"><a href="<?= base_url('admin/'); ?>"><i class="feather icon-home"></i></a></li>
        <li class="breadcrumb-item"><?= $this->lang->line("text_tickets"); ?></li>
    </ul>
</div>
<div class="row">
    <div class="col-xl-3 col-lg-12 col-md-12">
        <div class="tile mb30">
            <div class="tile-title">
                <h3><i class="feather icon-filter"></i> <?= $this->lang->line("text_filters"); ?></h3>
            </div>
            <div class="tile-content">
                <form id="filterTicketForm" action="#" method="POST">
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
                    <div class="form-group">
                        <label for="inputType"><?= $this->lang->line("text_ticket_type"); ?></label>
                        <select id="inputType" class="form-control" name="ticket_type">
                            <option value="">--<?= $this->lang->line("text_select_ticket_type"); ?>--</option>
                            <option value="CUSTOMER"><?= $this->lang->line("text_customer_tickets"); ?></option>
                            <option value="GUEST"><?= $this->lang->line("text_guest_tickets"); ?></option>
                        </select>
                    </div>
                    <div class="form-group">
                        <button id="filterButton" type="submit" class="btn btn-theme-secondary btn-lg btn-block btn-oval ladda-button" data-style="expand-right" data-size="xs"><span class="ladda-label"><i class="feather icon-filter"></i> <?= $this->lang->line("text_filter_tickets"); ?></span></button>
                    </div>
                </form>  
            </div>
        </div>
    </div>
    <div class="col-xl-9 col-lg-12 col-md-12">
        <div class="tile mb30">
            <div class="tile-title">
              <div class="title">
                <h3><i class="feather icon-life-buoy"></i> <?= $this->lang->line("text_tickets"); ?></h3>
              </div>
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

<div id="sidePanel" class="side-panel">
    <div class="side-panel-content-holder">
        <div class="side-panel-loader"  id="sidePanelLoader"><div class="loader-ripple"><div></div><div></div></div></div>
        <div class="side-panel-content"  id="sidePanelContent"></div>
    </div>
</div>