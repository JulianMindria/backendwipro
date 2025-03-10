<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable {
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = ['username', 'email', 'password', 'superior_id'];

    protected $hidden = ['password'];

    public function proposals() {
        return $this->hasMany(Proposal::class, 'proposal_initiator');
    }

    public function approvals() {
        return $this->hasMany(Proposal::class, 'proposal_approver');
    }
}
