@extends('layouts.app')
@section('title','Docs')
@section('content')
    <div class="hk-pg-header">
        <h4 class="hk-sec-title">All docs</h4>
        <button type="button" class="btn btn-primary bg-primary-blue" data-toggle="modal"
                data-target="#docCreateModal">
            New doc
        </button>
    </div>
    <div class="row">
        <div class="col-xl-12">
            <section class="hk-sec-wrapper">
                <div class="row">
                    <div class="col-sm">
                        <div class="table-wrap">
                            <table id="docTable" class="table table-hover w-100 display">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Title</th>
                                    <th>Description</th>
                                    <th>Parent</th>
                                    <th>Order</th>
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

    <div class="modal fade" id="docCreateModal" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalForms"
         aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header p-3">
                    <h5 class="modal-title">Create new doc</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body mt-3 mb-3">
                    {!! Form::open(['method' => 'post', 'id' => 'docCreateForm']) !!}
                    @include('Administration::documentation.admin.doc.form',['editable'=>false])
                    <div class="float-right">
                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
                        <button
                                onclick="FormOptions.submitForm('docCreateForm','docCreateModal','docTable', false, FormOptions.clearTrumbowygInput)"
                                type="button" class="btn btn btn-outline-primary-blue">Create
                        </button>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="docEditModal" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalForms"
         aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header p-3">
                    <h5 class="modal-title">Edit doc</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body mt-3 mb-3">
                    {!! Form::open(['method' => 'put', 'id' => 'docEditForm','class'=>'needs-validation','novalidate']) !!}
                    @include('Administration::documentation.admin.doc.form',['editable'=>true])
                    <div class="float-right">
                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
                        <button
                                onclick="FormOptions.submitForm('docEditForm','docEditModal','docTable', false, FormOptions.clearTrumbowygInput)"
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
    <link href="{{asset('plugins/trumbowyg/ui/trumbowyg.min.css')}}" rel="stylesheet" type="text/css"/>
@endpush

@push('scripts')
    @include('layouts.includes.scripts.form')
    <!-- Data Table JavaScript -->
    <script src="{{asset('js/main.js')}}"></script>
    <script src="{{asset('plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('js/dataTables-data.js')}}"></script>
    <script src="{{asset('plugins/trumbowyg/trumbowyg.min.js')}}"></script>

    <script>
        DataTableOption.initDataTable('docTable', 'doc/table/data');
        FormOptions.initValidation('docCreateForm');
        FormOptions.initValidation('docEditForm');

        $('textarea').trumbowyg();
        $(".permission").select2();

        function getPatents(form_id) {
            $(form_id + ' .parent_row').removeClass('d-none');
            if ($(form_id + ' .is_parent').is(':checked')){
                $(form_id + ' .parent_row').addClass('d-none');
            }
        }

        function editPost(doc) {
            let id = doc.dataset.id;
            let title = doc.dataset.title;
            let description = doc.dataset.description;
            let parent = doc.dataset.parent;
            let order = doc.dataset.order;
            let permissions = JSON.parse(doc.dataset.permissions);
            $("#docEditForm").find('.title').val(title);
            $('#docEditForm .description').trumbowyg('html', description);
            $("#docEditForm").find('.parent').val(parent);
            $("#docEditForm").find('.order').val(order);

            $("#docEditForm").find('.is_parent').prop("checked", false);
            if (empty(parent)){
                $("#docEditForm").find('.is_parent').prop("checked", true);
            }
            getPatents("#docEditForm");

            $("#docEditForm").find('.permission').val(permissions[0]);
            $("#docEditForm").find('.permission').trigger('change');
            $("#docEditForm").attr('action', '/doc/' + id);
            ModalOptions.toggleModal('docEditModal');

        }

    </script>
@endpush