<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle = 'Messages' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo base_url('/assets/css/main.css') ?>">
    <style>
        .message-container {
            max-height: 500px;
            overflow-y: auto;
        }
        .message-item {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        .message-item:last-child {
            border-bottom: none;
        }
        .message-sent {
            background-color: #e7f3ff;
            text-align: right;
        }
        .message-received {
            background-color: #f7f7f7;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <h1 class="mb-4">Personal Messages</h1>
    <div class="card">
        <div class="card-header">
            <h2>Chat with <?= $chatPartnerName ?></h2>
        </div>
        <div class="card-body message-container">
            <?php foreach ($messages as $message): ?>
                <div class="message-item <?= $message['sender_id'] == $currentUserId ? 'message-sent' : 'message-received' ?>">
                    <p><?= $message['message'] ?></p>
                    <small class="text-muted"><?= $message['sent_at'] ?></small>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="card-footer">
            <form id="sendMessageForm">
                <div class="input-group">
                    <input type="hidden" id="sender_id" value="<?= $currentUserId ?>">
                    <input type="hidden" id="receiver_id" value="<?= $chatPartnerId ?>">
                    <input type="text" id="message" class="form-control" placeholder="Type your message here...">
                    <button type="submit" class="btn btn-primary">Send</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        $('#sendMessageForm').on('submit', function(e) {
            e.preventDefault();
            const message = $('#message').val();
            const sender_id = $('#sender_id').val();
            const receiver_id = $('#receiver_id').val();

            if (message.trim() === '') {
                alert('Message cannot be empty');
                return;
            }

            $.ajax({
                url: '<?= base_url('message/sendMessage') ?>',
                type: 'POST',
                data: {
                    sender_id: sender_id,
                    receiver_id: receiver_id,
                    message: message
                },
                success: function(response) {
                    $('#message').val('');
                    // Optionally, fetch and display the new message
                    fetchMessages();
                },
                error: function(response) {
                    alert('Failed to send message');
                }
            });
        });

        function fetchMessages() {
            $.ajax({
                url: '<?= base_url('message/getMessages/' . $currentUserId . '/' . $chatPartnerId) ?>',
                type: 'GET',
                success: function(response) {
                    $('.message-container').empty();
                    response.forEach(function(message) {
                        const messageClass = message.sender_id == <?= $currentUserId ?> ? 'message-sent' : 'message-received';
                        $('.message-container').append(`
                        <div class="message-item ${messageClass}">
                            <p>${message.message}</p>
                            <small class="text-muted">${message.sent_at}</small>
                        </div>
                    `);
                    });
                },
                error: function(response) {
                    alert('Failed to fetch messages');
                }
            });
        }

        // Fetch messages initially and set an interval to fetch them periodically
        fetchMessages();
        setInterval(fetchMessages, 3000);
    });
</script>
</body>
</html>
