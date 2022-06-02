@extends('layouts.default')
@section('title','نیازمندی های پروژه')
@section('content')

<div class="container">
<div class="card shadow-sm mt-5">
    <div class="card-header">
        <h2>نیاز مندی های پروژه x</h2>
    </div>
    <div class="card-body">
        <table class="table table-bordered data-table">

            <thead>

                <tr>

                    <th>No</th>

                    <th>Name</th>

                    <th>Email</th>

                    <th width="100px">Action</th>

                </tr>

            </thead>

            <tbody>

            </tbody>

        </table>
    </div>
</div>
</div>



@endsection
@section('scripts')
<script src="{{ asset('js/pages/requiremnet.js') }}"></script>
@endsection

