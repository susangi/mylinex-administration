@foreach(\Administration\Models\Menu::roots()->get() as $root)
    <div class="super">
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-5">
                <input type="checkbox" class="mr-1 superParentCheckBox">
                <label class="font-weight-bold text-right">{{$root->title}}</label>
            </div>
        </div>
        <div class="row">
            @foreach($root->children()->get() as $child)
                @if (empty($child->url) && !empty($child->parent_id))
                    <div class="sub-super">
                        <div class="row">
                            <div class="col-md-3"></div>
                            <div class="col-md-5">
                                <input type="checkbox" class="mr-1 secondParentCheckBox">
                                <label class="font-weight-bold text-right">{{$child->title}}</label>
                            </div>
                        </div>
                        <div class="row">
                            @foreach($child->children()->get() as $children)
                                <div class="col-md-6 main-parent">
                                    <input type="checkbox" class="mr-1 parentCheckBox"><label
                                        class="font-weight-bold">{{$children->title}}</label>
                                    <div class="col-md-12">
                                        <ul>
                                            @foreach($children->permissions()->get() as $permission)
                                                <li class="text-nowrap">
                                                    <input type="checkbox" name="permissions[]"
                                                           {{ ($rolePermissions->contains($permission->name))?'checked':null}}
                                                           value="{{$permission->name}}"
                                                           class="mr-1 childCheckBox">{{$permission->name}}
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="col-md-6 main-parent">
                        <input type="checkbox" class="mr-1 parentCheckBox"><label
                            class="font-weight-bold">{{$child->title}}</label>
                        <div class="col-md-12">
                            <ul>
                                @foreach($child->permissions()->get() as $permission)
                                    <li class="text-nowrap">
                                        <input type="checkbox" name="permissions[]"
                                               {{ ($rolePermissions->contains($permission->name))?'checked':null}}
                                               value="{{$permission->name}}"
                                               class="mr-1 childCheckBox">{{$permission->name}}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
@endforeach
