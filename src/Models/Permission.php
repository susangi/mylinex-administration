<?php

namespace Administration\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Administration\Traits\ActivityLogOptionsTrait;

class Permission extends Model
{
    use LogsActivity, ActivityLogOptionsTrait;
    protected static $logName = 'permissions';
    protected static $logAttributes = ['*'];

    protected $fillable = ['name', 'guard_name','menu_id'];

    public function scopeTableData($query, $order_column, $order_by_str, $start, $length)
    {
        return $query->orderBy($order_column, $order_by_str)
            ->offset($start)
            ->limit($length);
    }

    public function scopeSearchData($query, $term)
    {
        return $query
            ->Where('id', 'like', "%" . $term . "%")
            ->orWhere('name', 'like', "%" . $term . "%")
            ->orWhere('guard_name', 'like', "%" . $term . "%");
    }
}
