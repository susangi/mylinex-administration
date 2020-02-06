<?php

namespace Administration\Controllers;

use Administration\Models\ActivityLog;
use Administration\Models\Permission;
use Administration\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class ActivityLogController extends Controller
{
    public function index()
    {
//        $causers = ActivityLog::CausedByList()->get()->pluck('causer.name', 'causer.id');
        $causers = User::all()->pluck('name', 'id');
        $performed_on = ActivityLog::PerformedOnList()->get()->pluck('subject_type', 'subject_type');
        return (Auth::user()->hasAnyAccess('activity log view')) ?
            view('Administration::activity-logs.index', compact('causers', 'performed_on')) : abort(403);
    }

    public function tableData(Request $request, $filterData = false)
    {
        $user = Auth::user();
        $order_by = $request->order;
        $search = $request->search['value'];
        $start = $request->start;
        $length = $request->length;
        $order_by_str = $order_by[0]['dir'];

        $columns = ['id', 'log_name', 'description', 'subject_id', 'subject_type', 'causer_id', 'causer_type', 'properties', 'created_at'];
        $order_column = $columns[$order_by[0]['column']];

        $activityLogs = ActivityLog::tableData($order_column, $order_by_str, $start, $length);

        if ($request->filter) {
            $daterRange = $request->date_range;
            $performed_on = $request->performed_on;
            $causedBy = $request->caused_by;
            $activity = $request->log_activity;
            $activityLogs = $activityLogs->FilterData($daterRange, $performed_on, $causedBy, $activity)->get();
            $activityLogsCount = $activityLogs->count();
        } else if (is_null($search) || empty($search) && is_null($request->filter)) {
            $activityLogs = $activityLogs->get();
            $activityLogsCount = ActivityLog::all()->count();
        } else {
            $activityLogs = $activityLogs->searchData($search)->get();
            $activityLogsCount = $activityLogs->count();
        }


        $data[][] = array();
        $i = 0;
        $edit_btn = null;
        $delete_btn = null;


        foreach ($activityLogs as $key => $log) {
            $tmp = explode("\\", $log->subject_type);
            $subject_type = end($tmp);

            $tmp = explode("\\", $log->causer_type);
            $causer_type = end($tmp);

            if ($log->description == 'created' || $log->description == 'deleted') {
                $result_array = json_decode(json_encode($log->properties->attributes), TRUE);
            }
            if ($log->description == 'updated') {
                $it_1 = json_decode(json_encode($log->properties->attributes), TRUE);
                $it_2 = json_decode(json_encode($log->properties->old), TRUE);
                $result_array = array_diff($it_1, $it_2);
            }

            $causer = ($causer_type == 'User') ? User::whereId($log->causer_id)->withTrashed()->first()->name : $log->causer_id;
            $subject = ($subject_type == 'User') ? User::whereId($log->subject_id)->withTrashed()->first()->name : $log->subject_id;

            if (empty($result_array[0])) {
//                echo "they are same";
            } else {

            }
//
//            $tmp = collect($result_array);
//            $properties = $tmp->map(function ($item, $key) {
//                if (!is_array($item)){
//                    return " $key=>$item";
//                } else {
//                    return " $key=>".implode(",",$item);
//                }
//            })->flatten()->implode(",");
//
            
            $js = json_encode($result_array, JSON_PRETTY_PRINT);
            $jsonView = '<div class=""><textarea class="terminal-container">' . $js . '</textarea></div>';


            $data[$i] = array(
                $log->log_name,
                $log->description,
                $subject,
                $subject_type,
                $causer,
                $causer_type,
                $jsonView,
                $log->created_at,
            );
            $i++;
        }

        if ($activityLogsCount == 0) {
            $data = [];
        }

        $json_data = [
            "draw" => intval($_REQUEST['draw']),
            "recordsTotal" => intval($activityLogsCount),
            "recordsFiltered" => intval($activityLogsCount),
            "data" => $data
        ];

        return json_encode($json_data);
    }
}
