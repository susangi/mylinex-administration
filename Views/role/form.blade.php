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
            @foreach(\Administration\Models\Menu::roots()->get() as $root)
                <div class="super">
                    <div class="row">
                            <div class="col-md-12">
                                <input type="checkbox" class="mr-1 superParentCheckBox">
                                <label class="font-weight-bold text-right">{{$root->title}}</label>
                            </div>
                        @foreach($root->children()->get() as $child)
                            <div class="col-md-5 ml-3 main-parent">
                                <input type="checkbox" class="mr-1 parentCheckBox"><label
                                    class="font-weight-bold">{{$child->title}}</label>
                                <div class="col-md-12">
                                    <ul>
                                        @foreach($child->permissions()->get() as $permission)
                                            <li class="text-nowrap">
                                                <input type="checkbox" name="permissions[]" value="{{$permission->name}}" class="mr-1 childCheckBox">{{$permission->name}}
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

