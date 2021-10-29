<?php 
$session_data=$this->session->userdata('login');
?>
<?php if (!empty($tickets)): foreach ($tickets as $ticket): ?>
<div class="tickets-list" id="actionCard<?= $ticket['id']; ?>">
    
    <div class="ticket-info">
        <div class="media">
            <div class="image-holder">

                <img src="<?= base_url(); ?>assets/images/ticket.jpg" alt="<?= $ticket['category_name']; ?>">

            </div>
            <div class="media-body">
                <h6><?= $ticket['ticket_title']; ?></h6>
                <p><i class="feather icon-briefcase text-theme-secondary"></i> <?= $ticket['category_name']; ?></p>
                <?php if($ticket['status']==0) { ?>
                    <span class="badge badge-pill badge-success"><?= $this->lang->line("text_new"); ?></span>
                <?php }elseif($ticket['status']==1) { ?>
                    <span class="badge badge-pill badge-warning"><?= $this->lang->line("text_in_progress"); ?></span>
                <?php }elseif($ticket['status']==2) { ?>
                    <span class="badge badge-pill badge-danger"><?= $this->lang->line("text_closed"); ?></span>
                <?php } ?>
                <?php if($ticket['priority']=="L") { ?>
                    <span class="badge badge-pill badge-secondary"><?= $this->lang->line("text_low"); ?></span>
                <?php }elseif($ticket['priority']=="M") { ?>
                    <span class="badge badge-pill badge-secondary"><?= $this->lang->line("text_medium"); ?></span>
                <?php }elseif($ticket['priority']=="H") { ?>
                    <span class="badge badge-pill badge-secondary"><?= $this->lang->line("text_high"); ?></span>
                <?php }elseif($ticket['priority']=="U") { ?>
                    <span class="badge badge-pill badge-secondary"><?= $this->lang->line("text_urgent"); ?></span>
                <?php } ?>

            </div>
        </div>
    </div>   
    <div class="action-dropdown">
        <div class="btn-group">
            <button type="button" class="btn btn-light btn-fab dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
            <div class="dropdown-menu dropdown-menu-right">
                <a class="dropdown-item" href="<?= base_url(); ?>user/view_ticket/<?= $ticket['id']; ?>"><i class="feather icon-eye"></i> <?= $this->lang->line("text_view_and_reply"); ?></a>
                <button class="dropdown-item" type="button" id="deleteButton" data-id="<?= $ticket['id']; ?>"><i class="feather icon-trash"></i> <?= $this->lang->line("text_delete"); ?></button>
            </div>
        </div>
    </div>
</div>
<?php endforeach;
    else:
?>
<div class="text-center mt30 mb30">
    <div class="mb5"><img src="<?= base_url();?>assets/images/tickets.svg" alt="Tickets"></div>
    <p><?= $this->lang->line("text_no_tickets_found"); ?></p>
</div>
<?php endif; ?>
<div id="pagination" class="mt10"><?= $pagination; ?></div>