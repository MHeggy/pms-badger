<?php

namespace App\Models;

use CodeIgniter\Model;

class SupportTicketModel extends Model {
    protected $table = 'support_tickets';
    protected $primaryKey = 'ticketID';
    protected $allowedFields = ['userID', 'issue_title', 'issue_description', 'status', 'created_at'];
}