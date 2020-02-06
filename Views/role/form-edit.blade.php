<div class="form-group">
    <div class="row">
        <div class="col-md-2 text-right mt-2">
            <label>Name</label>
        </div>
        <div class="col-md-10">
            <input type="text" id="txtName" name='name' class="form-control name" required>
        </div>
    </div>
    {{--    <div class="row">--}}
    {{--        <div class="col-md-2 text-right mt-2">--}}
    {{--            <label>Permissions</label>--}}
    {{--        </div>--}}
    {{--        <div class="col-md-10">--}}
    {{--            {!! Form::select('permissions[]', $permissions, null,['class'=>'form-control input_tags','multiple'=>'multiple','required']) !!}--}}
    {{--        </div>--}}
    {{--    </div>--}}
    <div class="row">
        <div class="col-md-2 text-right mt-2">
            <label>Permissions</label>
        </div>
        <div class="col-md-9 ml-2 mt-5">
            <div class="" id="permissionForm"></div>
        </div>
    </div>
</div>

