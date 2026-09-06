<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MobileAppLink extends Model
{
    protected $table = 'mobile_app_links';
    protected $fillable = ['platform', 'url'];
    public $timestamps = true;
}
?>
