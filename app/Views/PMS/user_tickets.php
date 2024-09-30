<?php esc($pageTitle = 'My Tickets') ?>

<header>
    <?php include 'header.php'; ?>
</header>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">

<div class="container mt-5">
    <h2 class="mb-4">My Support Tickets</h2>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th scope="col">#</th>
                <th scope="col"><i class="bi bi-person-fill"></i> Name</th>
                <th scope="col"><i class="bi bi-calendar3"></i> Date Submitted</th>
                <th scope="col"><i class="bi bi-ticket-fill"></i> Title</th>
                <th scope="col"><i class="bi bi-exclamation-triangle-fill"></i> Status</th>
                <th scope="col"><i class="bi bi-eye-fill"></i> View</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($tickets) && is_array($tickets)): ?>
                <?php foreach ($tickets as $index => $ticket): ?>
                    <tr>
                        <th scope="row"><?= $index + 1 ?></th>
                        <td><?= esc($ticket['firstName'] . ' ' . $ticket['lastName']) ?></td>
                        <td><?= date('M d, Y', strtotime($ticket['created_at'])) ?></td>
                        <td><?= esc($ticket['issue_title']) ?></td>
                        <td>
                            <?php if ($ticket['status'] == 'open'): ?>
                                <span class="badge bg-warning text-dark"><i class="bi bi-hourglass-split"></i> Open</span>
                            <?php elseif ($ticket['status'] == 'resolved'): ?>
                                <span class="badge bg-success"><i class="bi bi-check-circle-fill"></i> Resolved</span>
                            <?php else: ?>
                                <span class="badge bg-secondary"><i class="bi bi-x-circle-fill"></i> Closed</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="/support_ticket/<?= esc($ticket['ticketID']) ?>" class="btn btn-sm btn-primary">
                                <i class="bi bi-eye-fill"></i> View
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" class="text-center">No support tickets found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>