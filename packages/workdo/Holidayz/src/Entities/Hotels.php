<?php

namespace Workdo\Holidayz\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Hotels extends Model
{
    use HasFactory;

    use HasFactory;

    protected $table = "hotels";

    protected $fillable = [
        'workspace',
        'name',
        'slug',
        'email',
        'phone',
        'ratting',
        'check_in',
        'check_out',
        'short_description',
        'address',
        'state',
        'city',
        'zip_code',
        'policy',
        'logo',
        'invoice_logo',
        'description',
        'created_by',
        'is_active',
        'enable_subdomain',
        'subdomain',
        'enable_storelink',
        'enable_domain',
        'hotel_theme',
        'theme_dir',
        'domains',
        'mail_driver','mail_host','mail_port','mail_username','mail_password','mail_encryption','mail_from_address','mail_from_name'
    ];


    protected static function boot()
    {
        parent::boot();

        static::created(function ($hotel) {
            $hotel->slug = $hotel->createSlug($hotel->name);

            $hotel->save();
        });
    }


    private function createSlug($name)
    {
        if (static::whereSlug($slug = \Str::slug($name))->exists()) {

            $max = static::whereName($name)->latest('id')->skip(1)->value('slug');

            if (isset($max[-1]) && is_numeric($max[-1])) {

                return preg_replace_callback('/(\d+)$/', function ($mathces) {

                    return $mathces[1] + 1;
                }, $max);
            }
            return "{$slug}-2";
        }
        return $slug;
    }


}
