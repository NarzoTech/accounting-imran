@extends('admin.layouts.master')
@section('title')
    <title>{{ __('Invoice List') }}</title>
@endsection
@section('admin-content')
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h4 class="section_title">{{ __('Invoice List') }}</h4>
                <div>
                    <x-admin.add-button :href="route('admin.invoice.create')" />
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive list_table">
                    <table class="table" id="invoice_table">
                        <thead>
                            <tr>
                                <th width="5%">{{ __('SN') }}</th>
                                <th width="30%">{{ __('Invoice') }}</th>
                                <th width="15%">{{ __('Price') }}</th>
                                <th width="10%">{{ __('Category') }}</th>
                                <th width="15%">{{ __('Status') }}</th>
                                <th width="15%">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <x-admin.delete-modal />
@endsection

@push('js')
    <script>
        "use strict"

        function deleteData(id) {
            var url = "{{ route('admin.invoice.destroy', ':id') }}";
            url = url.replace(':id', id);
            $('#deleteForm').attr('action', url);
            $('#deleteModal').modal('show');
        }
    </script>
@endpush
