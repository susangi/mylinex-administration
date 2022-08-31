<?php

namespace Administration\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Traits\LogsActivity;
use Administration\Traits\ActivityLogOptionsTrait;

class ActivityLog extends Model
{
    use ActivityLogOptionsTrait, LogsActivity;

    protected $table = "activity_log";

    public function getCreatedAtAttribute($value)
    {
        return date('d-m-Y H:i:s', strtotime($value));
    }

    public function getPropertiesAttribute($value)
    {
        return json_decode($value);
    }

    public function causer(){
        return $this->belongsTo(User::class);
    }

    public function scopeTableData($query, $order_column, $order_by_str, $start, $length)
    {
        $user = Auth::user();
        $is_super_admin = $user->hasRole('Super Admin') ? true : false;
        $is_admin = $user->hasRole('Admin') ? true : false;
        if (!($is_super_admin || $is_admin)){
            $query->whereCauserId($user->id);
        }
        return $query->orderBy($order_column, $order_by_str)
            ->offset($start)
            ->limit($length);
    }

    public function scopeFilterByUser($query,$is_admin,$user_id)
    {
        if (!$is_admin){
            $query->whereCauserId($user_id);
        }
        return $query;
    }

    public function scopeSearchData($query, $term)
    {
        return $query
            ->Where('id', 'like', "%" . $term . "%")
            ->orWhere('log_name', 'like', "%" . $term . "%")
            ->orWhere('description', 'like', "%" . $term . "%")
            ->orWhere('subject_id', 'like', "%" . $term . "%")
            ->orWhere('subject_type', 'like', "%" . $term . "%")
            ->orWhere('causer_id', 'like', "%" . $term . "%")
            ->orWhere('causer_type', 'like', "%" . $term . "%");
    }

    public function scopeFilterData($query,$daterRange,$performed_on,$causedBy,$activity)
    {
        if (!empty($daterRange)){
            $date  = explode(' - ',$daterRange);
            $start_date = $date[0];
            $end_date = $date[1];
            $query->whereBetween('created_at',[$start_date,$end_date]);
        }
        if (!empty($performed_on)){
            $query->where('subject_type', '=',$performed_on);
        }
        if (!empty($causedBy)){
            $query->where('causer_id', '=',$causedBy);
        }
        if (!empty($activity)){
            $query->where('description', '=',$activity);
        }
        return $query;

    }

    public function scopeCausedByList($query){
        return $query->with('causer')->groupBy('causer_id');
    }

    public function scopePerformedOnList($query){
        return $query->groupBy('subject_type');
    }
}
