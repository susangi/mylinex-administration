<div class="form-group">
    <div class="row">
        <div class="col-md-3 text-right mt-2">
            <label>Is Parent Menu</label>
        </div>
        <div class="col-md-9 mt-2">
            <input type="checkbox" class="isParent" name="isParent" id="isParent" checked>
            {{--            </div>--}}
        </div>
    </div>

    <div class="row d-none parentIdDiv" id="parentIdDiv">
        <div class="col-md-3 text-right mt-2">
            <label>Parent Menu</label>
        </div>
        <div class="col-md-9">
            {!! Form::select('parent_id',$roots,null,['class'=>'form-control parent_id','placeholder'=>'Select Parent Menu','id'=>'parentId']) !!}
        </div>
    </div>

    <div class="row">
        <div class="col-md-3 text-right mt-2">
            <label>Name</label>
        </div>
        <div class="col-md-9">
            <input type="text" name='title' class="form-control title" required>
        </div>
    </div>

    <div class="row d-none routeDiv" id="routeDiv">
        <div class="col-md-3 text-right mt-2">
            <label>Route Name</label>
        </div>
        <div class="col-md-9">
            <input type="text" name='url' class="form-control route" required>
        </div>
    </div>

    <div class="row d-none chckobox" id="chckobox">
        <div class="col-md-3 text-right mt-2">
            <label>Permissions</label>
        </div>
        <div class="col-md-9 mt-2">
            <div class="">
                <input type="checkbox" class="index" name="permissions[]" value="index" >
                <label>index</label>
            </div>
            <div class="">
                <input type="checkbox" class="create" name="permissions[]" value="create" >
                <label>create</label>
            </div>
            <div class="">
                <input type="checkbox" class="show" name="permissions[]" value="show" >
                <label>show</label>
            </div>
            <div class="">
                <input type="checkbox" class="edit" name="permissions[]" value="edit" >
                <label>edit</label>
            </div>
            <div class="">
                <input type="checkbox" class="delete" name="permissions[]" value="delete" >
                <label>delete</label>
            </div>
        </div>
    </div>

    <div class="row d-none tagsDiv" id="tagsDiv">
        <div class="col-md-3 text-right mt-2">
            <label>Permissions (comma separated)</label>
        </div>
        <div class="col-md-9 mt-2">
            <input type="text" name="permission_tags[]" class="tgs" data-role="tagsinput">
        </div>
    </div>

</div>
