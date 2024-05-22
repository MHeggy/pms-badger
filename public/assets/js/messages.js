// JavaScript to handle opening the edit message modal and populating it with message data
$(document).ready(function() {
    $('.edit-message').click(function() {
        var messageID = $(this).data('message-id');
        var messageText = $(this).data('message-text');

        $('#editMessageID').val(messageID);
        $('#editMessageText').val(messageText);

        $('#editMessageModal').modal('show');
    });

    // JavaScript to handle submitting the edit message form
    $('#updateMessageBtn').click(function() {
        $('#editMessageForm').submit();
    });
});