<?php

namespace App\Modules\Login\Models;

use CodeIgniter\Model;

class LoginModel extends Model
{
    protected $table = 'admin_users';
    protected $primarykey = 'id';
    protected $protectFields = false;

    
}