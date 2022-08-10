@foreach ($roots as $root)
    @php
        $permissionList = $root->derivedPermissions();
        $isAllowed = $user->hasAnyAccess($permissionList, true);
        $html = '';
    @endphp
    <li class="nav-item dropdown show-on-hover">

        @if (true)
            @php
                $routeList = collect($root->descendants()->get()->pluck('url'));
                $isActiveParent = null;
            @endphp
            <a class='nav-link dropdown-toggle ".$isActiveParent."' href='#' role='button' data-toggle='dropdown'
               aria-haspopup='true' aria-expanded='false'>
                {{$root->title}};
            </a>

            <div class="dropdown-menu" data-dropdown-in="fadeIn" data-dropdown-out="fadeOut">
                <div class="sub-dropdown-menu show-on-hover">
                    @foreach ($root->descendants()->get() as $child)
                        @php
                            $childPermissionList = \Administration\Models\Menu::find($child->id)->permissions()->get()->pluck('id');
                             $isAllowed =$user->hasAnyAccess($childPermissionList, true);
                             $isActive =  null;
                        @endphp
                        @if ($isAllowed)
                            <a href="{{ route($child->url)}}" class='dropdown-item {{$isActive}}'>{{$child->title}}</a>
                        @endif
                    @endforeach
                </div>
            </div>
        @endif
    </li>
@endforeach
