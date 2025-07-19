@extends('admin.layouts.master')
@section('title')
    <title>{{ __('Create Invoice') }}</title>
@endsection
@section('admin-content')
    @include('accounting::invoice._form')
@endsection
