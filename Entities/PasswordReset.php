<?php

namespace Modules\AdminManagement\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PasswordReset extends Model
{
    use HasFactory;

    protected $primaryKey = null;
    public $incrementing = false;
    public $timestamps = false;

    protected $table = 'password_reset_tokens';

    protected $fillable = [
        'email',
        'token'
    ];

    protected static function newFactory()
    {
        return \Modules\AdminManagement\Database\factories\PasswordResetFactory::new();
    }
}
