@extends('admin.layouts.master')
@section('title')
    <title>{{ __('Pagination Settings') }}</title>
@endsection
@section('admin-content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="section_title">{{ __('Pagination Settings') }}</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.update-custom-pagination') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="table-responsive mb-3">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th width="50%">{{ __('Section Name') }}</th>
                                        <th width="50%">{{ __('Quantity') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($custom_paginations as $custom_pagination)
                                        <tr>
                                            <td>{{ $custom_pagination->section_name }}</td>
                                            <td>
                                                <input type="number" value="{{ $custom_pagination->item_qty }}"
                                                    name="quantities[]" class="form-control">
                                                <input type="hidden" value="{{ $custom_pagination->id }}" name="ids[]">
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <x-admin.update-button :text="__('Update')" />
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
