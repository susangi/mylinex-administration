<?php

namespace Administration\Controllers;

use Administration\Models\Documentation;
use Administration\Models\Permission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Administration\Models\Role;

class DocumentationController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $permissions = Permission::all()->pluck('name', 'id');
        $parents = Documentation::parents()->get()->pluck('title', 'id');
        return view('Administration::documentation.admin.doc.index', compact('parents', 'permissions'));
    }

    public function contact()
    {
        $user = auth()->user();
        $side_panel = [];
        $roots = Documentation::roots()->orderBy('order', 'asc')->get();
        foreach ($roots as $root) {
            $permission = $root->permissions;
            if ($permission->count() > 0){
                $permission = $permission->pluck('name');
                if (!$user->hasAnyAccess($permission)){
                    continue;
                }
            }
            $children = $root->children()->orderBy('order', 'asc')->get();
            if ($children->count() > 0){
                foreach ($children as $child) {
                    $permission = $child->permissions;
                    if ($permission->count() > 0){
                        $permission = $permission->pluck('name');
                        if (!$user->hasAnyAccess($permission)){
                            continue;
                        }
                    }
                    $side_panel[$root['title']][] = $child['title'];
                }
            } else {
                $side_panel[$root['title']] = [];
            }
        }
        return view('Administration::documentation.contact', compact('side_panel','user'));
    }

    public function documentation()
    {
        $user = auth()->user();
        $side_panel = [];
        $roots = Documentation::roots()->orderBy('order', 'asc')->get();
        foreach ($roots as $root) {
            $permission = $root->permissions;
            if ($permission->count() > 0){
                $permission = $permission->pluck('name')->toArray();
                if (!$user->hasAnyAccess($permission)){
                    continue;
                }
            }
            $children = $root->children()->orderBy('order', 'asc')->get();
            if ($children->count() > 0){
                foreach ($children as $child) {
                    $permission = $child->permissions;
                    if ($permission->count() > 0){
                        $permission = $permission->pluck('name')->toArray();
                        if (!$user->hasAnyAccess($permission)){
                            continue;
                        }
                    }
                    $side_panel[$root['title']][] = $child['title'];
                }
            } else {
                $side_panel[$root['title']] = [];
            }
        }
        return view('Administration::documentation.index', compact('roots', 'side_panel','user'));
    }

    public function sendMail(Request $request)
    {
        $app_name = env('APP_NAME', 'Admin');
        $to_name = "Support";
        $to_email = "support@mylinex.com";
        $message = $request->message;
        $subject = $request->_subject;
        $from_name = $request->name;
        $from_email = $request->_replyto;
        $data = array(
            'logo' => public_path("images/myl-logo-icon.png"),
            "subject" => $subject,
            "message" => $message,
            "email" => $from_email,
            "our_email" => 'support@mylinex.com',
            "corporation" => "Mylinex Internation (Pvt) Ltd"
        );
        try {
            Mail::send('layouts.mail', compact('data'),
                function ($message) use ($to_name, $to_email, $subject, $app_name, $from_name, $from_email) {
                    $message->to($to_email, $to_name)->from($from_email, $from_name)->subject("Support request from $app_name documentation");
                }
            );
            return redirect()->back()->with([
                'alert-type' => 'success',
                'message' => 'Support request successfully sent.'
            ]);
        } catch (\Exception $exception) {
            return redirect()->back()->with([
                'alert-type' => 'error',
                'message' => 'Support request sending failed.'
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
            'order' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->messages()->first());
        }

        $depth = 0;
        $parent = null;
        if (empty($request->is_parent)) {
            $parent = $request->parent;
            $parent_depth = Documentation::find($parent)->depth;
            $depth = $parent_depth + 1;
        }

        try {
            DB::beginTransaction();
            $doc = Documentation::firstOrCreate([
                'title' => $request->title,
                'description' => $request->description,
                'unique_id' => Str::slug($request->title),
                'parent' => $parent,
                'depth' => $depth,
                'order' => $request->order
            ]);
            if (!empty($request->permission)) {
                $doc->syncPermissions([$request->permission]);
            }
            DB::commit();
            if ($doc->wasRecentlyCreated) {
                $msg = 'Post Created Successfully';
                return $this->sendResponse($doc, $msg);
            } else {
                $msg = 'Post Already Exists';
                return $this->sendError($msg);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            $msg = 'Something went wrong.';
            return $this->sendError($msg);
        }
    }

    public function update(Request $request, Documentation $doc)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
            'order' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->messages()->first());
        }

        $depth = 0;
        $parent = null;
        if (empty($request->is_parent)) {
            $parent = $request->parent;
            $parent_depth = Documentation::find($parent)->depth;
            $depth = $parent_depth + 1;
        }

        if ($doc->name != $request->name) {
            $exist = Documentation::whereTitle($request->title)->first();
            if (!empty($exist)) {
                $msg = 'Post Already Exists';
                return $this->sendError($msg);
            }
        }

        try {
            DB::beginTransaction();
            $doc->title = $request->title;
            $doc->description = $request->description;
            $doc->unique_id = Str::slug($request->title);
            $doc->parent = $parent;
            $doc->depth = $depth;
            $doc->order = $request->order;
            $doc->save();
            if (!empty($request->permission)) {
                $doc->syncPermissions([$request->permission]);
            } else {
                $permission = $doc->permissions;
                $permission = $permission->pluck('name');
                $doc->revokePermissionTo($permission[0]);
            }
            DB::commit();
            $msg = 'Post Updated Successfully';
            return $this->sendResponse($doc, $msg);
        } catch (\Exception $e) {
            DB::rollBack();
            $msg = 'Something went wrong.';
            return $this->sendError($msg);
        }
    }

    public function destroy(Request $request, Documentation $doc)
    {
        $doc->delete();
        return $this->sendResponse('', 'Post Deleted Successfully');
    }

    public function tableData(Request $request)
    {
        $user = Auth::user();
        $order_by = $request->order;
        $search = $request->search['value'];
        $start = $request->start;
        $length = $request->length;
        $order_by_str = $order_by[0]['dir'];

        $columns = ['id', 'title', 'description', 'parent', 'depth', 'order'];
        $order_column = $columns[$order_by[0]['column']];

        $docs = Documentation::tableData($order_column, $order_by_str, $start, $length);
        if (is_null($search) || empty($search)) {
            $docs = $docs->get();
            $docs_count = Documentation::all()->count();
        } else {
            $docs = $docs->searchData($search)->get();
            $docs_count = $docs->count();
        }

        $data[][] = array();
        $i = 0;
        $edit_btn = null;
        $delete_btn = null;
        $can_edit = ($user->hasPermissionTo('doc edit')) ? 1 : 0;
        $can_delete = ($user->hasPermissionTo('doc delete')) ? 1 : 0;

        foreach ($docs as $key => $doc) {

            $permission = $doc->permissions;
            $permission = $permission->pluck('id');

            $encoded_description = e($doc->description);
            if ($can_edit) {
                $edit_btn = "<i class='icon-md icon-pencil mr-3' onclick=\"editPost(this)\" data-id='{$doc->id}' data-title='{$doc->title}' data-description='{$encoded_description}' data-parent='{$doc->parent}' data-depth='{$doc->depth}' data-order='{$doc->order}' data-permissions='{$permission}'></i>";
            }
            if ($can_delete) {
                $url = "'doc/" . $doc->id . "'";
                $delete_btn = "<i class='icon-md icon-trash' onclick=\"FormOptions.deleteRecord(" . $doc->id . ",$url,'docTable')\"></i>";
            }

            $data[$i] = array(
                $doc->id,
                $doc->title,
                Str::limit($doc->description, 30),
                $doc->parent,
                $doc->order,
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
