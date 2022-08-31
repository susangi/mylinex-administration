<?php

namespace Administration\Models;

use Baum\NestedSet\Node as WorksAsNestedSet;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Traits\LogsActivity;
use Administration\Traits\ActivityLogOptionsTrait;

class Menu extends Model
{
    use WorksAsNestedSet,SoftDeletes, LogsActivity, ActivityLogOptionsTrait;
    protected $table = 'menu';
    protected $fillable = ['title', 'url', 'description', 'permissions', 'parent_id', 'left', 'right', 'depth'];
    protected $parentColumnName = 'parent_id';
    protected $leftColumnName = 'left';
    protected $rightColumnName = 'right';
    protected $depthColumnName = 'depth';
    protected $orderColumnName = null;

    public function getPermissionsAttribute($value)
    {
        return json_decode($value);
    }

    public function permissions()
    {
        return $this->hasMany(Permission::class);
    }

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
            ->orWhere('name', 'like', "%" . $term . "%");
    }

    public function scopeDerivedPermissions($query)
    {
        $children = $this->children()->get();
        $permissionsList =collect();
        foreach ($children as $child) {
            $menu = collect(Menu::find($child->id)->permissions()->get()->pluck('id'));
            $permissionsList =$permissionsList->merge($menu);
        }
        return $permissionsList;
    }

    public function parentMenu(){
        return $this->belongsTo(Menu::class,'parent_id','id');
    }

}
