<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle = "Messages"; ?></title>
    <link rel="stylesheet" href="<?php echo base_url('/assets/css/messages.css') ?>">
    <!-- Link to Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<!-- Header content -->
<div id="header">
    <header>
        <?php include 'header.php' ?>
    </header>
</div>
<main>
    <!-- Message container -->
    <div id="messages-container">
        <?php foreach ($messages as $message): ?>
            <div class="message-card">
                <!-- User Information -->
                <div class="user-info">
                    <span class="author"><?= $message['username']; ?> | </span>
                    <span class="timestamp"><?= date('F j, Y, g:i a', strtotime($message['timestamp'])); ?></span>
                </div>
                <!-- Message Content -->
                <div class="message-content">
                    <h5><?= $message['title']; ?></h5>
                    <p><?= $message['messageText']; ?></p>
                </div>
                <!-- Action Buttons -->
                <div class="action-buttons">
                    <form action="/messages/delete/<?= $message['messageID']; ?>" method="post">
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                    </form>
                    <button type="button" class="btn btn-primary btn-sm edit-message" data-message-id="<?= $message['messageID']; ?>" data-message-text="<?= $message['messageText']; ?>">Edit</button>
                </div>
            </div>
        <?php endforeach; ?>
    </div>


    <!-- Edit Message Modal -->
    <div class="modal" id="editMessageModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Message</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editMessageForm" action="/messages/update" method="post">
                        <input type="hidden" name="messageID" id="editMessageID">
                        <textarea name="message" id="editMessageText" class="form-control"></textarea>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <br>
                            <button type="submit" class="btn btn-primary" id="updateMessageBtn">Update</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>


    <form id="message-form" action="/messages/create" method="post">
        <!-- Use Bootstrap form group to wrap the title input -->
        <div class="form-group">
            <!-- Add Bootstrap form control class to the title input -->
            <input type="text" name="title" id="title" class="form-control" placeholder="Title">
        </div>
        <textarea name="message" id="message-input" placeholder="Write your message here..."></textarea>
        <button type="submit">Post</button>
    </form>
    <!-- Scripts -->
    <script src="<?php echo base_url('/assets/js/messages.js') ?>"></script>
    <script src="<?php echo base_url('/assets/js/main.js')?>"></script>
</main>
</body>
</html>