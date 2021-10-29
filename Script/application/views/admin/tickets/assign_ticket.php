<div class="tile mb30">
    <div class="tile-title-w-btn">
        <div class="title">
        <h3><?= $title; ?></h3>
        </div>
        <p><button class="btn btn-theme-grey btn-fab close-panel-button"><i class="feather icon-x"></i></button></p>
    </div>
    <div class="tile-content">
        <form id="assignTicketForm" action="#" method="POST">
            <div class="form-group">
                <label for="inputAssignTo"><?= $this->lang->line("text_assigned_to"); ?></label>
                <select id="inputAssignTo" class="form-control" name="assign_to">
                    <option value="">--<?= $this->lang->line("text_select_user"); ?>--</option>
                    <?php if(isset($users) && $users!=NULL) { foreach($users as $user) { ?>
                        <option value="<?= $user['id'] ?>"><?= $user['full_name'] ?> [<?= $user['role_name'] ?>]</option>
                    <?php } } ?>
                </select>
                <input type="hidden" name="ticket_id" value="<?= $ticket_id; ?>"
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
                <button id="assignTicketButton" class="btn btn-theme-secondary btn-lg btn-oval ladda-button" data-style="expand-right" data-size="xs"
                                    type="submit"><span class="ladda-label"><i class="feather icon-user-check"></i> <?= $this->lang->line("text_assign_ticket"); ?></span></button>
            </div>
        </form>
    </div>
</div>

