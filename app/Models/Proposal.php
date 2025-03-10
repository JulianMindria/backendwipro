<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proposal extends Model {
    use HasFactory;

    protected $fillable = [
        'proposal_name', 'proposal_objective', 'proposal_realization', 
        'proposal_budget', 'proposal_file', 'status', 
        'proposal_approver', 'proposal_initiator', 'content'
    ];

    public function initiator() {
        return $this->belongsTo(User::class, 'proposal_initiator');
    }

    public function approver() {
        return $this->belongsTo(User::class, 'proposal_approver');
    }
}
