<?php
declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\DB;
use PDO;

class Database
{
    public static function connection(): PDO
    {
        return DB::connection()->getPdo();
    }
}
