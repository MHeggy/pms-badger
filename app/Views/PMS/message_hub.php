<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title><?php $pageTitle = 'Message Hub' ?></title>
    <style>
        .message-container {
            max-height: 500px;
            overflow-y: auto;
        }
        .message-item {
            padding: 10px;
            border-bottom: 1px solid #ddd;
            transition: all 0.3s ease-in-out;
        }
        .message-item:hover {
            transform: scale(1.01);
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            z-index: 1;
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
        .compose-button {
            position: absolute;
            top: 10px;
            right: 10px;
        }
        .edit-button, .save-button, .cancel-button {
            cursor: pointer;
            margin-left: 5px;
        }

        .message-item:hover .hide-button {
            display: block; /* Show hide button on hover */
        }

        .hide-button {
            display: none;
            position: absolute;
            top: 10px;
            right: 10px;
        }
    </style>
</head>
<body>
<!-- Header content -->
<header>
    <?php include 'header.php'; ?>
</header>
<br><br><br><br>
<div class="container mt-5">
    <div class="card position-relative">
        <div class="card-header">
            <h2>Your Messages</h2>
        </div>
        <div class="compose-button">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#composeModal">
                Compose
            </button>
        </div>
        <!-- Your compose message form and modal goes here -->
        <div class="card-footer">
            <!-- Modal -->
            <div class="modal fade" id="composeModal" tabindex="-1" aria-labelledby="composeModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="composeModalLabel">Compose Message</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <!-- Form to select recipient -->
                            <form id="selectRecipientForm">
                                <div class="mb-3">
                                    <label for="recipient" class="form-label">Select Recipient</label>
                                    <select class="form-select" id="recipient" required>
                                        <option value="" selected disabled>Select Recipient</option>
                                        <!-- Add options dynamically here -->
                                        <?php foreach ($users as $user): ?>
                                            <option value="<?= $user->id ?>"><?= $user->username ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="message" class="form-label">Message</label>
                                    <textarea class="form-control" id="message" rows="3" required></textarea>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" id="sendButton">Send</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="message-container">
            <?php if (empty($threads)): ?>
                <p>No threads for now.</p>
            <?php else: ?>
                <?php foreach ($threads as $thread): ?>
                    <div class="message-item" data-recipient-id="<?= $thread['recipient_id'] ?>" data-bs-toggle="modal" data-bs-target="#messageModal">
                        <p><strong><?= $thread['username'] ?>:</strong></p>
                        <p>Last Message: <?= $thread['last_message'] ?></p>
                        <p>Time Sent: <?= $thread['last_message_time'] ?></p>
                        <p>Unread Messages: <?= $thread['unread_count'] ?></p> <!-- Add this line -->
                        <button class="btn btn-sm btn-outline-secondary hide-button">Hide</button>
                        <button class="btn btn-primary mark-as-read-button" data-chat-partner-id="<?= $thread['recipient_id'] ?>">Mark as Read</button>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal for viewing conversation and sending message back -->
<div class="modal fade" id="messageModal" tabindex="-1" aria-labelledby="messageModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="messageModalLabel">Conversation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Conversation view -->
                <div id="messageConversation">
                    <!-- Initially empty, will be populated with conversation messages -->
                    <?php if (!empty($conversationMessages)): ?>
                        <?php foreach ($conversationMessages as $message): ?>
                            <div class="card mb-3 message" data-message-id="<?= $message['id'] ?>" data-sender-id="<?= $message['sender_id'] ?>">
                                <div class="card-body">
                                    <h5 class="card-title"><?= $message['sender_username'] ?></h5>
                                    <p class="card-text message-content"><?= $message['message'] ?></p>
                                    <p class="card-text"><small class="text-muted"><?= date('F jS, Y', strtotime($message['sent_at'])) ?> at <?= date('h:i A', strtotime($message['sent_at'])) ?></small></p>
                                    <?php if ($message['sender_id'] == auth()->id()): ?>
                                        <div class="message-buttons">
                                            <button class="btn btn-outline-primary btn-sm edit-button">Edit</button>
                                            <button class="btn btn-outline-danger btn-sm delete-button">Delete</button>
                                            <button class="btn btn-outline-success btn-sm save-button" style="display: none;">Save</button>
                                            <button class="btn btn-outline-danger btn-sm cancel-button" style="display: none;">Cancel</button>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p id="noMessages">No messages in this conversation.</p>
                    <?php endif; ?>
                </div>
            </div>
            <!-- Message input form -->
            <div class="modal-footer">
                <textarea class="form-control" id="replyMessage" rows="3" placeholder="Type your message here"></textarea>
                <button type="button" class="btn btn-primary" id="sendReplyButton">Reply</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        // Function to handle edit button click
        $(document).on('click', '.edit-button', function() {
            var messageCard = $(this).closest('.message');
            var messageContent = messageCard.find('.message-content');
            var originalMessage = messageContent.text();

            messageContent.html('<textarea class="form-control edit-textarea" rows="3">' + originalMessage + '</textarea>');
            messageCard.find('.edit-button').hide();
            messageCard.find('.save-button, .cancel-button').show();
        });

        // Function to handle delete button click
        $(document).on('click', '.message .delete-button', function() {
            var messageCard = $(this).closest('.message');
            var messageId = messageCard.data('message-id');

            // Remove the message card from the DOM
            messageCard.remove();

            // Optionally, you can also send an AJAX request to delete the message from the database
            $.ajax({
                url: '<?= base_url('personalMessages/deleteMessage') ?>',
                type: 'POST',
                data: {
                    message_id: messageId
                },
                success: function(response) {
                    // Handle success response if needed
                },
                error: function(response) {
                    alert('Failed to delete message');
                }
            });
        });


        // Function to handle save button click
        $(document).on('click', '.save-button', function() {
            var messageCard = $(this).closest('.message');
            var messageId = messageCard.data('message-id');
            var newMessage = messageCard.find('.edit-textarea').val();

            $.ajax({
                url: '<?= base_url('personalMessages/editMessage') ?>',
                type: 'POST',
                data: {
                    message_id: messageId,
                    new_message: newMessage
                },
                success: function(response) {
                    messageCard.find('.message-content').text(newMessage);
                    messageCard.find('.edit-button').show();
                    messageCard.find('.save-button, .cancel-button').hide();
                },
                error: function(response) {
                    alert('Failed to update message');
                }
            });
        });

        // Function to handle cancel button click
        $(document).on('click', '.cancel-button', function() {
            var messageCard = $(this).closest('.message');
            var originalMessage = messageCard.find('.edit-textarea').text();

            messageCard.find('.message-content').text(originalMessage);
            messageCard.find('.edit-button').show();
            messageCard.find('.save-button, .cancel-button').hide();
        });

        $('.mark-as-read-button').on('click', function(event) {
            event.stopPropagation(); // Prevent triggering the modal open
            var chatPartnerId = $(this).data('chat-partner-id');

            fetch('/personalMessages/mark-as-read', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '<?= csrf_hash() ?>' // Make sure this token is correct
                },
                body: JSON.stringify({ chatPartnerId: chatPartnerId })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Messages marked as read');
                        location.reload(); // Optionally, refresh to update the unread count
                    } else {
                        alert('Failed to mark messages as read');
                    }
                })
                .catch(error => console.error('Error:', error));
        });


        $('.message-item').on('click', function() {
            const recipientId = $(this).data('recipient-id');
            const senderId = <?= auth()->id(); ?>;

            $('#messageModal').data('recipient-id', recipientId);

            $.ajax({
                url: '<?= base_url('personalMessages/getMessages') ?>',
                type: 'GET',
                data: {
                    currentUserId: senderId,
                    chatPartnerId: recipientId
                },
                success: function(response) {
                    $('#messageModal .modal-body').empty();

                    response.forEach(function(message) {
                        var messageHtml = '<div class="card mb-3 message" data-message-id="' + message.id + '" data-sender-id="' + message.sender_id + '">' +
                            '<div class="card-body">' +
                            '<h5 class="card-title">' + message.sender_username + '</h5>' +
                            '<p class="card-text message-content">' + message.message + '</p>' +
                            '<p class="card-text"><small class="text-muted">' + message.sent_at + '</small></p>';

                        if (message.sender_id == senderId) {
                            messageHtml += '<div class="message-buttons">' +
                                '<button class="btn btn-outline-primary btn-sm edit-button">Edit</button>' +
                                '<button class="btn btn-outline-success btn-sm save-button" style="display: none;">Save</button>' +
                                '<button class="btn btn-outline-danger btn-sm cancel-button" style="display: none;">Cancel</button>' +
                                '</div>';
                        }

                        messageHtml += '</div></div>';

                        $('#messageModal .modal-body').append(messageHtml);
                    });
                },
                error: function(response) {
                    alert('Failed to fetch messages');
                }
            });
        });

        $('#sendReplyButton').on('click', function() {
            const recipientId = $('#messageModal').data('recipient-id');
            const senderId = <?= auth()->id(); ?>;
            const message = $('#replyMessage').val();

            if (!message) {
                alert('Message is required');
                return;
            }

            $.ajax({
                url: '<?= base_url('personalMessages/sendMessage') ?>',
                type: 'POST',
                data: {
                    sender_id: senderId,
                    receiver_id: recipientId,
                    message: message
                },
                success: function(response) {
                    $('#replyMessage').val('');
                    fetchMessages(senderId, recipientId);
                },
                error: function(response) {
                    alert('Failed to send message');
                }
            });
        });

        $('#sendButton').on('click', function() {
            const recipient = $('#recipient').val();
            const message = $('#message').val();
            const sender_id = <?= auth()->id(); ?>;

            if (!recipient || !message) {
                alert('Recipient and message are required');
                return;
            }

            $.ajax({
                url: '<?= base_url('personalMessages/sendMessage') ?>',
                type: 'POST',
                data: {
                    sender_id: sender_id,
                    receiver_id: recipient,
                    message: message
                },
                success: function(response) {
                    $('#message').val('');
                    $('#composeModal').modal('hide');
                    fetchMessages();
                },
                error: function(response) {
                    alert('Failed to send message');
                }
            });
        });

        $('#composeModal').on('hidden.bs.modal', function() {
            $('#message').val('');
            $('#recipient').prop('selectedIndex', 0);
        });
        // Function to handle hide button click
        $(document).on('click', '.hide-button', function() {
            // Hide the parent message-item element
            $(this).closest('.message-item').hide();
        });
    });

    function fetchMessages(currentUserId, chatPartnerId) {
        $.ajax({
            url: '<?= base_url('personalMessages/getMessages') ?>',
            type: 'GET',
            data: {
                currentUserId: currentUserId,
                chatPartnerId: chatPartnerId
            },
            success: function(response) {
                $('#messageModal .modal-body').empty();
                response.forEach(function(message) {
                    var messageHtml = '<div class="card mb-3 message" data-message-id="' + message.id + '" data-sender-id="' + message.sender_id + '">' +
                        '<div class="card-body">' +
                        '<h5 class="card-title">' + message.sender_username + '</h5>' +
                        '<p class="card-text message-content">' + message.message + '</p>' +
                        '<p class="card-text"><small class="text-muted">' + message.sent_at + '</small></p>';

                    if (message.sender_id == currentUserId) {
                        messageHtml += '<div class="message-buttons">' +
                            '<button class="btn btn-outline-primary btn-sm edit-button">Edit</button>' +
                            '<button class="btn btn-outline-success btn-sm save-button" style="display: none;">Save</button>' +
                            '<button class="btn btn-outline-danger btn-sm cancel-button" style="display: none;">Cancel</button>' +
                            '</div>';
                    }

                    messageHtml += '</div></div>';

                    $('#messageModal .modal-body').append(messageHtml);
                });
            },
            error: function(response) {
                alert('Failed to fetch messages');
            }
        });
    }
</script>
</body>
</html>