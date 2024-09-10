<?php $pageTitle = $post['title']; ?>
<link rel="stylesheet" href="<?php echo base_url('/assets/css/messages.css') ?>">
<!-- Link to Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
    .reply-author-info {
        font-size: 0.9rem;
    }

    /* Ensure buttons have consistent styling */
    .action-buttons .btn {
        font-size: 0.875rem; /* Adjust font size if needed */
        padding: 0.375rem 0.75rem; /* Adjust padding if needed */
        margin: 0.1rem; /* Add margin to prevent overlap */
    }

    /* Additional styling for button consistency */
    .btn-edit {
        font-size: 0.875rem;
        padding: 0.375rem 0.75rem;
    }
</style>

<header>
    <?php include 'header.php' ?>
</header>

<br><br><br><br>
<!-- Main content -->
<div class="container mt-4">
    <div class="row justify-content-center align-items-center">
        <div class="col-md-6">
            <div class="post-card border rounded mb-3 p-3">
                <!-- Back Button -->
                <div class="mb-2">
                    <a href="/forums" class="btn btn-secondary btn-sm">&larr; Back to Forums</a>
                </div>
                <!-- User Information -->
                <div class="user-info mb-2">
                    <span class="author fw-bold"><?= $post['username']; ?></span>
                    <span class="timestamp text-muted"> | <?= date('F j, Y, g:i a', strtotime($post['created_at'])); ?></span>
                </div>
                <!-- Post Content -->
                <div class="post-content">
                    <h5 class="mb-1"><?= $post['title']; ?></h5>
                    <p class="mb-0"><?= $post['content']; ?></p>
                </div>
                <!-- Action Buttons -->
                <div class="action-buttons mt-3">
                    <button type="button" class="btn btn-success btn-sm" id="replybtn">Reply</button>
                    <button type="button" class="btn btn-info btn-sm btn-edit" id="editbtn" data-bs-toggle="modal" data-bs-target="#editModal">Edit</button>
                    <button type="button" class="btn btn-danger btn-sm" id="deletebtn" data-bs-toggle="modal" data-bs-target="#deleteModal">Delete</button>
                </div>
                <!-- Inside forumdetails.php -->
                <div class="mt-3" id="replyBox" style="display: none;">
                    <form action="/forums/replyToPost/<?= $post['id']; ?>" method="post">
                        <textarea class="form-control tinymce" name="reply_content" rows="3" placeholder="Enter your reply here..."></textarea>
                        <button type="submit" class="btn btn-primary mt-2">Submit Reply</button>
                    </form>
                </div>
            </div>

            <!-- Display Replies -->
            <?php if (!empty($replies)): ?>
                <div class="replies-container">
                    <h6>Replies:</h6>
                    <?php foreach ($replies as $reply): ?>
                        <div class="reply-card border rounded mb-3 p-3">
                            <!-- Reply Author and Timestamp -->
                            <?php
                            // Retrieve user information for the reply author
                            $replyAuthor = ""; // Initialize with an empty string
                            // Check if user_id is available in the reply data
                            if (isset($reply['user_id'])) {
                                // Retrieve the user information from your user table using the user_id
                                // Assuming you have a method to fetch user information in your model
                                $replyAuthor = auth()->user()->username; // Replace getUserUsernameById with the actual method
                            }
                            ?>
                            <div class="reply-author-info text-muted mb-2">
                                Reply by: <?= $replyAuthor; ?> <br> <?= date('F j, Y, g:i a', strtotime($reply['created_at'])); ?>
                            </div>
                            <!-- Reply Content -->
                            <p class="mb-0"><?= $reply['content']; ?></p>
                            <!-- Action Buttons for Replies -->
                            <div class="action-buttons mt-2">
                                <!-- Edit Button -->
                                <button type="button" class="btn btn-primary btn-sm btn-edit edit-reply" data-reply-id="<?= $reply['id']; ?>" data-bs-toggle="modal" data-bs-target="#editReplyModal">Edit</button>
                                <!-- Delete Button -->
                                <form action="/forums/deleteReply/<?= $reply['id']; ?>" method="post" class="d-inline-block">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p>No replies yet.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this post?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                <form action="/forums/deletePost/<?= $post['id']; ?>" method="post" class="d-inline">
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit" class="btn btn-danger">Yes</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Post</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editForm" action="/forums/updatePost/<?= $post['id']; ?>" method="post">
                    <div class="mb-3">
                        <label for="editTitle" class="form-label">Title</label>
                        <input type="text" class="form-control" id="editTitle" name="title" value="<?= $post['title']; ?>">
                    </div>
                    <div class="mb-3">
                        <label for="editContent" class="form-label">Content</label>
                        <textarea class="form-control tinymce" id="editContent" name="content" rows="5"><?= $post['content']; ?></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" form="editForm">Save changes</button>
            </div>
        </div>
    </div>
</div>

<!-- Script to handle toggling visibility of reply textbox -->
<script>
    document.getElementById('replybtn').addEventListener('click', function() {
        document.getElementById('replyBox').style.display = 'block';
    });
</script>

<!-- Bootstrap JS Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
<!-- TinyMCE initialization -->
<script src="https://cdn.tiny.cloud/1/eectismo1492cjcg16e1j17geuwjywoji6ldcnpay2cqxlay/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    tinymce.init({
        selector: 'textarea.tinymce',
        plugins: 'autolink lists link image',
        toolbar: 'undo redo | formatselect | bold italic underline | alignleft aligncenter alignright | bullist numlist | link image'
    });
</script>
</body>
</html>
