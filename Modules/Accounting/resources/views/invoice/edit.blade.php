@extends('admin.layouts.master')
@section('title')
    <title>{{ __('Edit Invoice') }} - {{ $invoice->invoice_number }}</title>
@endsection
@section('admin-content')
    @include('accounting::invoice._form')
@endsection
