@extends('layouts.app')
@section('title','Permissions')
@section('content')
    {{--    <permission-index-component></permission-index-component>--}}
    <div class="hk-pg-header">
        <h4 class="hk-sec-title">All permissions</h4>
        <button type="button" class="btn btn-primary bg-primary-blue" data-toggle="modal"
                data-target="#permissionCreateModalForms">
            New Permission
        </button>
    </div>
    <div class="row">
        <div class="col-xl-12">
            <section class="hk-sec-wrapper">
                <div class="row">
                    <div class="col-sm">
                        <div class="table-wrap">
                            <table id="permissionTable" class="table table-hover w-100 display">
                                <thead>
                                <tr>
                                    <th>Permission Name</th>
                                    <th>Guard</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <div class="modal fade" id="permissionCreateModalForms" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalForms"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header p-3">
                    <h5 class="modal-title">Create new permission</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body mt-3 mb-3">
                    {!! Form::open(['method' => 'post', 'id' => 'permissionCreateForm']) !!}
                    @include('Administration::permission.form')
                    <div class="float-right">
                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
                        <button
                            onclick="FormOptions.submitForm('permissionCreateForm','permissionCreateModalForms','permissionTable')"
                            type="button" class="btn btn btn-outline-primary-blue">Create
                        </button>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="permissionEditModalForms" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalForms"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header p-3">
                    <h5 class="modal-title">Edit permission</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body mt-3 mb-3">
                    {!! Form::open(['method' => 'put', 'id' => 'permissionEditForm','class'=>'needs-validation','novalidate']) !!}
                    @include('Administration::permission.form')
                    <div class="float-right">
                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
                        <button
                            onclick="FormOptions.submitForm('permissionEditForm','permissionEditModalForms','permissionTable')"
                            type="button" class="btn btn-outline-primary-blue">Update now
                        </button>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>

@endsection
@push('styles')
    @include('layouts.includes.styles.form')
    <link href="{{asset('plugins/datatables/jquery.dataTables.css')}}" rel="stylesheet" type="text/css"/>
@endpush

@push('scripts')
    @include('layouts.includes.scripts.form')
    <!-- Data Table JavaScript -->
    <script src="{{asset('plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('js/dataTables-data.js')}}"></script>

    <script>
        DataTableOption.initDataTable('permissionTable', 'permissions/table/data');
        FormOptions.initValidation('permissionCreateForm');
        FormOptions.initValidation('permissionEditForm');

        function editPermission(permission) {
            let id = permission.dataset.id;
            let name = permission.dataset.name;
            $("#permissionEditForm").find('#txtName').val(name);
            $("#permissionEditForm").attr('action', '/permissions/' + id);
            ModalOptions.toggleModal('permissionEditModalForms');
        }
    </script>
@endpush
