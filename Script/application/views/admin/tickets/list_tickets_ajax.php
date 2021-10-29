<?php 
$session_data=$this->session->userdata('login');
?>
<?php if (!empty($tickets)): foreach ($tickets as $ticket): ?>
<div class="tickets-list" id="actionCard<?= $ticket['id']; ?>">
    
    <div class="ticket-info">
        <div class="media">
            <div class="image-holder">
                <?php if($ticket['profile_image']==NULL) { ?>
                    <img src="<?= base_url('assets/images/ticket.jpg'); ?>" alt="<?= $ticket['full_name']; ?>">
                <?php }else { ?>
                    <img src="<?= base_url().$ticket['profile_image']; ?>" alt="<?= $ticket['full_name']; ?>">
                <?php } ?>
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
    <?php if($session_data['user_role_slug']!='agent'){ ?>
    <?php if($ticket['assigned_to']!=0) { ?>
    <div class="assigned-to">
        <div class="media">
            <div class="image-holder">
                <?php if($ticket['assigned_user_image']==NULL) { ?>
                    <img src="<?= base_url('assets/images/user.jpg'); ?>" alt="<?= $ticket['assigned_user']; ?>">
                <?php }else { ?>
                    <img src="<?= base_url().$ticket['assigned_user_image']; ?>" alt="<?= $ticket['assigned_user']; ?>">
                <?php } ?>
            </div>
            <div class="media-body">
                <p><?= $this->lang->line("text_assigned_to"); ?></p>
                <?php if($ticket['assigned_user']!=NULL) { ?>
                    <h6><?= $ticket['assigned_user']; ?></h6>
                <?php }else{?>
                    <h6><?= $this->lang->line("text_undefined"); ?></h6>
                <?php } ?>
            </div>  
        </div>
    </div>
    <?php } ?>
    <?php } ?>
    
    <div class="action-dropdown">
        <div class="btn-group">
            <button type="button" class="btn btn-light btn-fab dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
            <div class="dropdown-menu dropdown-menu-right">
                <?php if($permissions['view_reply_ticket']==TRUE) { ?>
                    <button class="dropdown-item" type="button" id="viewButton" data-id="<?= $ticket['id']; ?>"><i class="feather icon-eye"></i> <?= $this->lang->line("text_view_and_reply"); ?></button>
                <?php } ?>
                <?php if($permissions['assign_ticket']==TRUE) { ?>
                    <?php if($ticket['status']!=2) { ?>
                        <button class="dropdown-item" type="button" id="assignButton" data-id="<?= $ticket['id']; ?>"><i class="feather icon-user-check"></i> <?= $this->lang->line("text_assign"); ?></button>
                    <?php } ?>
                <?php } ?>
                <?php if($permissions['ticket_completion']==TRUE) { ?>
                    <?php if($ticket['status']==1) { ?>
                        <button class="dropdown-item" type="button" id="completeButton" data-id="<?= $ticket['id']; ?>"><i class="feather icon-check-circle"></i> <?= $this->lang->line("text_mark_completed"); ?></button>
                    <?php }elseif($ticket['status']==2) { ?>
                        <button class="dropdown-item" type="button" id="incompleteButton" data-id="<?= $ticket['id']; ?>"><i class="feather icon-check-circle"></i> <?= $this->lang->line("text_mark_incompleted"); ?></button>
                    <?php } ?>
                <?php } ?>
                <?php if($permissions['delete_ticket']==TRUE) { ?>
                    <button class="dropdown-item" type="button" id="deleteButton" data-id="<?= $ticket['id']; ?>"><i class="feather icon-trash"></i> <?= $this->lang->line("text_delete"); ?></button>
                <?php } ?>
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