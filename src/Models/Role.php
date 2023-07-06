<?php

namespace Administration\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Traits\HasPermissions;
use Administration\Traits\ActivityLogOptionsTrait;
use \Spatie\Permission\Models\Role as Roles;

class Role extends Roles
{
    use LogsActivity, HasPermissions, ActivityLogOptionsTrait, HasFactory;
    protected static $logName = 'roles';
    protected static $logAttributes = ['*'];

    protected $fillable = ['name', 'guard_name'];

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
