<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Setting extends Model
{
    protected $table = 'settings';
    protected $primaryKey = 'key';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['key', 'value'];

    private static array $cache = [];

    public function get(string $key, mixed $default = null): mixed
    {
        if (isset(self::$cache[$key])) return self::$cache[$key];
        $val = DB::table('settings')->where('key', $key)->value('value');
        self::$cache[$key] = ($val !== null) ? $val : $default;
        return self::$cache[$key];
    }

    public function set(string $key, mixed $value): void
    {
        self::$cache[$key] = $value;
        DB::table('settings')->updateOrInsert(
            ['key' => $key],
            ['value' => $value, 'updated_at' => now()]
        );
    }

    public function getAll(): array
    {
        return array_map(fn($r) => (array)$r, DB::table('settings')->orderBy('key')->get()->toArray());
    }

    public function setMany(array $data): void
    {
        foreach ($data as $key => $value) {
            $this->set($key, $value);
        }
    }
}
