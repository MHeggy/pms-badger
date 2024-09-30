<?php esc($pageTitle = 'View Tickets') ?>

<header>
    <?php include 'header.php'; ?>
</header>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">

<div class="container mt-5">
    <h2>Support Ticket Details</h2>
    
    <div class="card mb-4">
        <div class="card-header">
            <h5><?= esc($ticket['issue_title']) ?></h5>
        </div>
        <div class="card-body">
            <p><strong>Submitted by:</strong> <?= esc($ticket['firstName'] . ' ' . $ticket['lastName']) ?></p>
            <p><strong>Date Submitted:</strong> <?= date('M d, Y', strtotime($ticket['created_at'])) ?></p>
            <p><strong>Priority:</strong> <?= esc($ticket['priority']) ?></p>
            <p><strong>Description:</strong> <?= esc($ticket['issue_description']) ?></p>

            <?php if ($ticket['attachment']): ?>
                <p><strong>Attachment:</strong> <a href="<?= base_url('uploads/' . $ticket['attachment']) ?>" download>Download</a></p>
            <?php endif; ?>

            <p><strong>Status:</strong> 
                <?php if ($ticket['status'] == 'open'): ?>
                    <span class="badge bg-warning text-dark"><i class="bi bi-hourglass-split"></i> Open</span>
                <?php elseif ($ticket['status'] == 'resolved'): ?>
                    <span class="badge bg-success"><i class="bi bi-check-circle-fill"></i> Resolved</span>
                <?php else: ?>
                    <span class="badge bg-secondary"><i class="bi bi-x-circle-fill"></i> Closed</span>
                <?php endif; ?>
            </p>
        </div>
    </div>

    <!-- Replies Section (Visible to both Admin and Regular Users) -->
    <div class="card mb-4">
        <div class="card-header">
            <h5>Replies</h5>
        </div>
        <div class="card-body">
            <?php if (!empty($replies)): ?>
                <ul class="list-group mb-4">
                    <?php foreach ($replies as $reply): ?>
                        <li class="list-group-item">
                            <strong><?= esc($reply['userID'] == auth()->id() ? 'You' : 'Support Staff') ?>:</strong> <?= esc($reply['reply_text']) ?>
                            <br>
                            <small class="text-muted"><?= date('M d, Y h:i A', strtotime($reply['created_at'])) ?></small>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>No replies yet.</p>
            <?php endif; ?>

            <!-- Reply form (Visible to all users) -->
            <form action="<?= base_url('/support_ticket/' . $ticket['ticketID'] . '/reply') ?>" method="post">
                <?= csrf_field() ?>
                <div class="mb-3">
                    <textarea class="form-control" name="reply_text" rows="3" placeholder="Enter your reply here..."></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Submit Reply</button>
            </form>
        </div>
    </div>

    <!-- Update Ticket Status Section (Visible only to Admins) -->
    <?php if (auth()->user()->inGroup('superadmin')): ?>
    <div class="card">
        <div class="card-header">
            <h5>Update Ticket Status</h5>
        </div>
        <div class="card-body">
            <form action="<?= site_url('support_ticket/' . $ticket['ticketID'] . '/reply') ?>" method="post">
                <?= csrf_field() ?>
                
                <div class="mb-3">
                    <label for="status" class="form-label">Select Status</label>
                    <select class="form-select" name="status" id="status">
                        <option value="open" <?= $ticket['status'] == 'open' ? 'selected' : '' ?>>Open</option>
                        <option value="resolved" <?= $ticket['status'] == 'resolved' ? 'selected' : '' ?>>Resolved</option>
                        <option value="closed" <?= $ticket['status'] == 'closed' ? 'selected' : '' ?>>Closed</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Update Status</button>
            </form>
        </div>
    </div>
    <?php endif; ?>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>