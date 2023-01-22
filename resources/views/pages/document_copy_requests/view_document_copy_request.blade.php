@extends('layouts.app_user')

@section('content')
<view-document-copy-requests :prop-request="{{$copy_request}}"></view-document-copy-requests>
@endsection
