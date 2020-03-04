<?php

namespace Administration\Controllers;

use Administration\Models\ChangeLog;
use Administration\Models\Documentation;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ChangeLogController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $versions = ChangeLog::version()->get()->pluck('version','version');
        $stability = ['dev'=>'dev', 'alpha'=>'alpha', 'beta'=>'beta', 'rc'=>'rc', 'stable'=>'stable'];
        return view('Administration::documentation.admin.changelog.index',compact('versions','stability'));
    }

    public function changelog()
    {
        $user = auth()->user();
        $side_panel = [];
        $roots = Documentation::roots()->orderBy('order', 'asc')->get();
        foreach ($roots as $root) {
            $permission = $root->permissions;
            if ($permission->count() > 0){
                $permission = $permission->pluck('name');
                if (!$user->hasAnyAccess($permission[0])){
                    continue;
                }
            }
            $children = $root->children()->orderBy('order', 'asc')->get();
            if ($children->count() > 0){
                foreach ($children as $child) {
                    $permission = $child->permissions;
                    if ($permission->count() > 0){
                        $permission = $permission->pluck('name');
                        if (!$user->hasAnyAccess($permission[0])){
                            continue;
                        }
                    }
                    $side_panel[$root['title']][] = $child['title'];
                }
            } else {
                $side_panel[$root['title']] = [];
            }
        }
        $logs = ChangeLog::all();
        $label_class = [
            'dev' => 'uk-label-danger',
            'alpha' => 'uk-label-warning',
            'beta' => 'uk-label-warning',
            'rc' => 'uk-label-success',
            'stable' => '',
        ];
        return view('Administration::documentation.changelog',compact('logs','label_class','side_panel','user'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'version' => 'required',
            'description' => 'required',
            'stability' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->messages()->first());
        }

        $changelog = ChangeLog::firstOrCreate([
            'version' => $request->version,
            'description' => $request->description,
            'stability' => $request->stability
        ]);

        if ($changelog->wasRecentlyCreated) {
            $msg = 'Change Log Created Successfully';
            return $this->sendResponse($changelog, $msg);
        } else {
            $msg = 'Change Log Already Exists';
            return $this->sendError($msg);
        }
    }

    public function update(Request $request, ChangeLog $changelog)
    {
        $validator = Validator::make($request->all(), [
            'version' => 'required',
            'description' => 'required',
            'stability' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->messages()->first());
        }

        if ($changelog->version != $request->version) {
            $exist = ChangeLog::whereTitle($request->stability)->whereDescription($request->description)->first();
            if (!empty($exist)) {
                $msg = 'Change Log Already Exists';
                return $this->sendError($msg);
            }
        }

        $changelog->version = $request->version;
        $changelog->description = $request->description;
        $changelog->stability = $request->stability;
        $changelog->save();

        return $this->sendResponse($changelog, 'Change Log Updated Successfully');
    }

    public function destroy(Request $request, ChangeLog $changelog)
    {
        $changelog->delete();
        return $this->sendResponse('', 'Change Log Deleted Successfully');
    }

    public function tableData(Request $request)
    {
        $user = Auth::user();
        $order_by = $request->order;
        $search = $request->search['value'];
        $start = $request->start;
        $length = $request->length;
        $order_by_str = $order_by[0]['dir'];

        $columns = ['id', 'version', 'stability', 'description'];
        $order_column = $columns[$order_by[0]['column']];

        $changelogs = ChangeLog::tableData($order_column, $order_by_str, $start, $length);
        if (is_null($search) || empty($search)) {
            $changelogs = $changelogs->get();
            $docs_count = ChangeLog::all()->count();
        } else {
            $changelogs = $changelogs->searchData($search)->get();
            $docs_count = $changelogs->count();
        }

        $data[][] = array();
        $i = 0;
        $edit_btn = null;
        $delete_btn = null;
        $can_edit = ($user->hasPermissionTo('changelog edit')) ? 1 : 0;
        $can_delete = ($user->hasPermissionTo('changelog delete')) ? 1 : 0;

        foreach ($changelogs as $key => $changelog) {
            if ($can_edit) {
                $edit_btn = "<i class='icon-md icon-pencil mr-3' onclick=\"editChangeLog(this)\" data-id='{$changelog->id}' data-version='{$changelog->version}' data-stability='{$changelog->stability}' data-description='{$changelog->description}'></i>";
            }
            if ($can_delete) {
                $url ="'changelog/".$changelog->id."'";
                $delete_btn = "<i class='icon-md icon-trash' onclick=\"FormOptions.deleteRecord(" . $changelog->id . ",$url,'changelogTable')\"></i>";
            }

            $data[$i] = array(
                $changelog->id,
                $changelog->version,
                $changelog->stability,
                Str::limit($changelog->description,30),
                $edit_btn . $delete_btn
            );
            $i++;
        }

        if ($docs_count == 0) {
            $data = [];
        }

        $json_data = [
            "draw" => intval($_REQUEST['draw']),
            "recordsTotal" => intval($docs_count),
            "recordsFiltered" => intval($docs_count),
            "data" => $data
        ];

        return json_encode($json_data);
    }
}
