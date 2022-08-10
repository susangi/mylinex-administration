<?php

namespace Administration\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Administration\Traits\ActivityLogOptionsTrait;
use Baum\NestedSet\Node as WorksAsNestedSet;
use Spatie\Permission\Traits\HasPermissions;
use Spatie\Activitylog\Traits\LogsActivity;

class Documentation extends Model
{
    //
    use ActivityLogOptionsTrait, SoftDeletes, WorksAsNestedSet, HasPermissions, LogsActivity;
    protected $guard_name = 'web';
    protected static $logName = 'documentation';
    protected static $logAttributes = ['*'];
    protected $table = 'doc';
    protected $parentColumnName = 'parent';
    protected $leftColumnName = 'left';
    protected $rightColumnName = 'right';
    protected $depthColumnName = 'depth';
    protected $orderColumnName = 'order';
    protected $fillable = ['title', 'description', 'parent', 'left', 'right', 'depth', 'order', 'created_by', 'updated_by'];

    public static function boot()
    {
        parent::boot();

        static::saving(function ($documentation) {
            $documentation->created_by = Auth::user()->id;
        });

        static::updating(function ($documentation) {
            $documentation->updated_by = Auth::user()->id;
        });

        static::deleting(function ($documentation) {
            $documentation->updated_by = Auth::user()->id;
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class,'created_by');
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
            ->orWhere('title', 'like', "%" . $term . "%")
            ->orWhere('description', 'like', "%" . $term . "%")
            ->orWhere('parent', 'like', "%" . $term . "%")
            ->orWhere('depth', 'like', "%" . $term . "%")
            ->orWhere('order', 'like', "%" . $term . "%");
    }

    public function scopeParents($query)
    {
        return $query->whereDepth(0);
    }
}
