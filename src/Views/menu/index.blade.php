@extends('Administration::layouts.app')
@section('title','Menu')
@section('content')
    <div class="hk-pg-header">
        <h4 class="hk-sec-title">All Menu</h4>
        <button type="button" class="btn btn-primary bg-primary-blue" data-toggle="modal"
                data-target="#menuCreateModal">
            New Menu
        </button>
    </div>
    <div class="row">
        <div class="col-xl-12">
            <section class="hk-sec-wrapper">
                <div class="row">
                    <div class="col-sm">
                        <div class="table-wrap">
                            <table id="menuTable" class="table table-hover w-100 display">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Title</th>
                                    <th>Route Name</th>
                                    <th>Parent Menu</th>
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

    <div class="modal fade " id="menuCreateModal" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalForms"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header p-3">
                    <h5 class="modal-title">Create new menu</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body mt-3 mb-3">
                    {!! Form::open(['method' => 'post', 'id' => 'menuCreateForm']) !!}
                    @include('Administration::menu.form')
                    <div class="float-right">
                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
                        <button
                            onclick="FormOptions.submitForm('menuCreateForm','menuCreateModal','menuTable')"
                            type="button" class="btn btn btn-outline-primary-blue">Create
                        </button>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="menuEditModal" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalForms"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header p-3">
                    <h5 class="modal-title">Edit menu</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>

                <div class="modal-body mt-3 mb-3">
                    {!! Form::open(['method' => 'put', 'id' => 'menuEditForm','class'=>'needs-validation','novalidate']) !!}
                    @include('Administration::menu.form')
                    <div class="float-right">
                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
                        <button
                            onclick="FormOptions.submitForm('menuEditForm','menuEditModal','menuTable')"
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

    @include('Administration::layouts.includes.styles.form')
    <link href="{{asset('plugins/datatables/jquery.dataTables.css')}}" rel="stylesheet" type="text/css"/>
    <style>
        .bootstrap-tagsinput .tag{
            background-color: #034691;
        }

        .bootstrap-tagsinput {
            width: 100%;
        }
    </style>
@endpush
@push('scripts')
    @include('Administration::layouts.includes.scripts.form')
    <script src="{{asset('plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('js/FormOptions.js')}}"></script>
    <script src="{{asset('js/dataTable.js')}}"></script>
    <script>
        DataTableOption.initDataTable('menuTable', 'menu/table/data');
        FormOptions.initValidation('menuCreateForm');
        FormOptions.initValidation('menuEditForm');

        function edit(menu) {
            let id = menu.dataset.id;
            let title = menu.dataset.title;
            let url = menu.dataset.url;
            let permissions = menu.dataset.permissions;
            let parent_id = menu.dataset.parent_id;

            $("#menuEditForm").find('.title').val(title);

            if (parent_id == '' || parent_id == undefined) {
                $('.isParent').prop('checked', true)
                $('.parentIdDiv').addClass('d-none')
                $('.chckobox').addClass('d-none')
                $('.tagsDiv').addClass('d-none')
                $('.routeDiv').addClass('d-none')
            } else {
                $('.isParent').prop('checked', false)
                $('.parentIdDiv').removeClass('d-none')
                $('.chckobox').removeClass('d-none')
                $('.tagsDiv').removeClass('d-none')
                $('.routeDiv').removeClass('d-none')

                $("#menuEditForm").find('.route').val(url);
                $("#menuEditForm").find('.parent_id').val(parent_id);


                $.each(JSON.parse(permissions), function( index, value ) {
                   switch (value) {
                       case title.toLowerCase()+' index':
                           $('.index').prop('checked', true);
                       break

                       case title.toLowerCase()+' create':
                           $('.create').prop('checked', true);
                       break

                       case title.toLowerCase()+' show':
                           $('.show').prop('checked', true);
                       break

                       case title.toLowerCase()+' edit':
                           $('.edit').prop('checked', true);
                       break

                       case title.toLowerCase()+' delete':
                           $('.delete').prop('checked', true);
                       break

                       default:
                           $('.tgs').tagsinput('add', value);
                   }
                });

            }

            $("#menuEditForm").attr('action', '/menu/' + id);
            ModalOptions.toggleModal('menuEditModal');
        }

        $('.isParent').click(function () {
            if ($(this).prop("checked") == true) {
                $('.parentIdDiv').addClass('d-none')
                $('.chckobox').addClass('d-none')
                $('.tagsDiv').addClass('d-none')
                $('.routeDiv').addClass('d-none')
            } else if ($(this).prop("checked") == false) {
                $('.parentIdDiv').removeClass('d-none')
                $('.chckobox').removeClass('d-none')
                $('.tagsDiv').removeClass('d-none')
                $('.routeDiv').removeClass('d-none')
            }
        });

        $('#menuEditModal').on('hidden.bs.modal', function (e) {
            $('.index').prop('checked', false);
            $('.create').prop('checked', false);
            $('.show').prop('checked', false);
            $('.edit').prop('checked', false);
            $('.delete').prop('checked', false);

            $('.tgs').tagsinput('removeAll');
        })
    </script>
@endpush
