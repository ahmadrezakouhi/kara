@extends('layouts.default')
@section('title','نیازمندی های پروژه')
@section('content')

<div class="container">
<div class="card shadow-sm mt-5">
    <div class="card-header">
        <h2>نیاز مندی های پروژه x</h2>
    </div>
    <div class="card-body">
        <div class="row pt-3">
            <div class="col-md-10">
                <form id='sf' action="getData" method = "POST">
                    @csrf
                    <div class="row pt-3">
                        <div class="col-md-5">
                            <div class="mb-3 row">
                                <label for="title" class="col-sm-4 col-form-label">عنوان</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="search-title" name="search-title">
                                </div>
                            </div>

                        </div>

                        <div class="col-md-4">
                            <button type="button" class="btn btn-primary mb-3" id="btn-filter">جستجو</button>
                        </div>
                    </div>
                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                </form>
            </div>
            <div class="col-md-2 pt-3 align-left">
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#mdlAddUser">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus" viewBox="0 0 16 16">
                        <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                    </svg>
                افزودن کاربر
                </button>
            </div>
        </div>
            <table id="tbl_requirements" class="table table-bordered user_datatable">
                <thead>

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

