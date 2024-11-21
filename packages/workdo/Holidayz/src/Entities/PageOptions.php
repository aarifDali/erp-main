<?php

namespace Workdo\Holidayz\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PageOptions extends Model
{
    use HasFactory;

    protected $table = "hotel_page_options";

    protected $fillable = [
        'name',
        'slug',
        'enable_page_header',
        'enable_page_footer',
        'contents',
        'workspace',
        'created_by',
    ];

    public static function create($data)
    {
        $obj          = new Utility();
        $table        = with(new PageOptions)->getTable();
        $data['slug'] = $obj->createSlug($table, $data['name']);
        $page        = static::query()->create($data);

        return $page;
    }

}
