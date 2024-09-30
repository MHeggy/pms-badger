<?php

namespace App\Models;

use CodeIgniter\Model;

class TicketRepliesModel extends Model
{
    protected $table = 'ticket_replies';
    protected $primaryKey = 'replyID';
    protected $allowedFields = ['ticketID', 'userID', 'reply_text', 'created_at'];
}
