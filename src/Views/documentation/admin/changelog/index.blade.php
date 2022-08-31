@extends('layouts.app')
@section('title','Change Log')
@section('content')
    <div class="hk-pg-header">
        <h4 class="hk-sec-title">All Change Logs</h4>
        <button type="button" class="btn btn-primary bg-primary-blue" data-toggle="modal"
                data-target="#changelogCreateModal">
            New Change Logs
        </button>
    </div>
    <div class="row">
        <div class="col-xl-12">
            <section class="hk-sec-wrapper">
                <div class="row">
                    <div class="col-sm">
                        <div class="table-wrap">
                            <table id="changelogTable" class="table table-hover w-100 display">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Version</th>
                                    <th>Stability</th>
                                    <th>Description</th>
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

    <div class="modal fade" id="changelogCreateModal" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalForms"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header p-3">
                    <h5 class="modal-title">Create new Change Logs</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body mt-3 mb-3">
                    {!! Form::open(['method' => 'post', 'id' => 'changelogCreateForm']) !!}
                    @include('Administration::documentation.admin.changelog.form',['editable'=>false])
                    <div class="float-right">
                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
                        <button
                                onclick="FormOptions.submitForm('changelogCreateForm','changelogCreateModal','changelogTable', true, FormOptions.clearTrumbowygInput)"
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
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header p-3">
                    <h5 class="modal-title">Edit Change Logs</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body mt-3 mb-3">
                    {!! Form::open(['method' => 'put', 'id' => 'changelogEditForm','class'=>'needs-validation','novalidate']) !!}
                    @include('Administration::documentation.admin.changelog.form',['editable'=>true])
                    <div class="float-right">
                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
                        <button
                                onclick="FormOptions.submitForm('changelogEditForm','docEditModal','changelogTable', true, FormOptions.clearTrumbowygInput)"
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
        DataTableOption.initDataTable('changelogTable', 'changelog/table/data');
        FormOptions.initValidation('changelogCreateForm');
        FormOptions.initValidation('changelogEditForm');

        $('textarea').trumbowyg();
        $(".version").select2({
            tags: true
        });

        function getPatents(form_id) {
            $(form_id + ' .parent_row').removeClass('d-none');
            if ($(form_id + ' .is_parent').is(':checked')){
                $(form_id + ' .parent_row').addClass('d-none');
            }
        }

        function editChangeLog(changelog) {
            let id = changelog.dataset.id;
            let version = changelog.dataset.version;
            let description = changelog.dataset.description;
            let stability = changelog.dataset.stability;
            $('#changelogEditForm .description').trumbowyg('html', description);

            $("#changelogEditForm").find('.version').val(version);
            $("#changelogEditForm").find('.version').trigger('change');

            $("#changelogEditForm").find('.stability').val(stability);
            $("#changelogEditForm").find('.stability').trigger('change');
            $("#changelogEditForm").attr('action', '/changelog/' + id);
            ModalOptions.toggleModal('docEditModal');

        }

    </script>
@endpush