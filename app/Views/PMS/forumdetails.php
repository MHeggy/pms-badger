<?php $pageTitle = $post['title']; ?>

<header>
    <?php include 'header.php' ?>
</header>

<!-- Link to Bootstrap CSS and FontAwesome -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<style>
    .reply-author-info {
        font-size: 0.9rem;
    }

    /* Consistent styling for action buttons */
    .action-buttons .btn {
        padding: 0.25rem 0.5rem;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        margin-right: 0.5rem;
        color: white;
        transition: background-color 0.3s ease;
    }

    /* Specific colors for action buttons */
    .action-buttons .btn-reply { background-color: #6c757d; }
    .action-buttons .btn-reply:hover { background-color: #5a6268; }

    .action-buttons .btn-edit { background-color: #17a2b8; }
    .action-buttons .btn-edit:hover { background-color: #138496; }

    .action-buttons .btn-delete { background-color: #dc3545; }
    .action-buttons .btn-delete:hover { background-color: #c82333; }

    .post-card, .reply-card {
        background-color: #f9f9f9;
        padding: 1rem;
        border-radius: 10px;
    }

    .post-content h5 {
        font-weight: bold;
    }

    .container {
        padding-top: 20px;
    }

    /* Styling for replies */
    .replies-container h6 {
        font-weight: bold;
    }

    .replies-container .reply-card {
        background-color: #f0f8ff;
        margin-top: 1rem;
    }

    .modal-footer {
        display: flex;
        justify-content: space-between;
    }

    .modal-header {
        padding: 1rem 1.5rem;
    }

    .modal-body {
        padding: 1.5rem;
    }

    .modal-footer {
        padding: 1rem 1.5rem;
        display: flex;
        justify-content: space-between;
    }

    .modal-title {
        display: flex;
        align-items: center;
    }

    .modal-title i {
        margin-right: 0.5rem;
    }

    .btn-primary, .btn-danger, .btn-secondary {
        display: inline-flex;
        align-items: center;
    }

    .btn-primary i, .btn-danger i, .btn-secondary i {
        margin-right: 0.5rem;
    }

    .modal-header.bg-danger {
        background-color: #dc3545;
    }

    .modal-header.bg-info {
        background-color: #17a2b8;
    }
</style>

<br><br><br><br>
<!-- Main content -->
<div class="container mt-4">
    <div class="row justify-content-center align-items-center">
        <div class="col-md-6">
            <div class="post-card border rounded mb-3 p-3">
                <!-- Back Button -->
                <div class="mb-2">
                    <a href="/forums" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Back to Forums
                    </a>
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
                <!-- Action Buttons with Icons Only -->
                <div class="action-buttons mt-3">
                    <button type="button" class="btn btn-reply" id="replybtn">
                        <i class="fas fa-reply"></i>
                    </button>
                    <button type="button" class="btn btn-edit" id="editbtn" data-bs-toggle="modal" data-bs-target="#editModal">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button type="button" class="btn btn-delete" id="deletebtn" data-bs-toggle="modal" data-bs-target="#deleteModal">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
                <!-- Reply Box -->
                <div class="mt-3" id="replyBox" style="display: none;">
                    <form action="/forums/replyToPost/<?= $post['id']; ?>" method="post">
                        <textarea class="form-control tinymce" name="reply_content" rows="3" placeholder="Enter your reply here..."></textarea>
                        <button type="submit" class="btn btn-primary mt-2">
                            <i class="fas fa-paper-plane"></i> Submit Reply
                        </button>
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
                            <div class="reply-author-info text-muted mb-2">
                                Reply by: <?= auth()->user()->username; ?> <br> <?= date('F j, Y, g:i a', strtotime($reply['created_at'])); ?>
                            </div>
                            <!-- Reply Content -->
                            <p class="mb-0"><?= $reply['content']; ?></p>
                            <!-- Action Buttons for Replies -->
                            <div class="action-buttons mt-2">
                                <button type="button" class="btn btn-edit edit-reply" data-reply-id="<?= $reply['id']; ?>" data-bs-toggle="modal" data-bs-target="#editReplyModal">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="/forums/deleteReply/<?= $reply['id']; ?>" method="post" class="d-inline-block">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" class="btn btn-delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
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

<!-- Edit Reply Modal -->
<div class="modal fade" id="editReplyModal" tabindex="-1" aria-labelledby="editReplyModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editReplyModalLabel">
                    <i class="fas fa-edit"></i> Edit Reply
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editReplyForm" action="/forums/updateReply/<?= $reply['id']; ?>" method="post">
                    <div class="mb-3">
                        <label for="editReplyContent" class="form-label">Reply Content</label>
                        <textarea class="form-control tinymce" id="editReplyContent" name="reply_content" rows="4"><?= $reply['content']; ?></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i> Close
                </button>
                <button type="submit" class="btn btn-primary" form="editReplyForm">
                    <i class="fas fa-save"></i> Save changes
                </button>
            </div>
        </div>
    </div>
</div>

<!-- TinyMCE initialization -->
<script src="https://cdn.tiny.cloud/1/eectismo1492cjcg16e1j17geuwjywoji6ldcnpay2cqxlay/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    tinymce.init({
        selector: 'textarea.tinymce',
        height: 200,
        menubar: false,
        plugins: 'lists link image',
        toolbar: 'undo redo | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link'
    });

    document.getElementById("replybtn").addEventListener("click", function() {
        const replyBox = document.getElementById("replyBox");
        replyBox.style.display = replyBox.style.display === "none" ? "block" : "none";
    });
</script>
</body>
</html>