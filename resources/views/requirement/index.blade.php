@extends('layouts.default')
@section('title','نیازمندی های پروژه')
@section('content')

<div class="container">
<div class="card shadow-sm mt-5">
    <div class="card-header d-flex justify-content-between">
        <h2>نیاز مندی های پروژه <span class="text-success text-weight-bold">{{ $project->title }}</span></h2>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#add_requirements"> افزودن نیازمندی</button>
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
            {{-- <div class="col-md-2 pt-3 align-left">
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#mdlAddUser">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus" viewBox="0 0 16 16">
                        <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                    </svg>
                افزودن کاربر
                </button>
            </div> --}}
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

<div class="modal fade" id="add_requirements">
    <div class="modal-dialog">
      <div class="modal-content">

        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">افزودن نیازمندی ها</h4>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <form action="" action="post">
            <input type="hidden" name="project_id" value="{{ $project->id }}">
        <!-- Modal body -->
        <div class="modal-body">
            <div class="mb-3 mt-3">
                <label for="title" class="form-label">عنوان</label>
                <input type="title" class="form-control" id="title"  name="title" >
              </div>
              <div class="mb-3 mt-3">
                <label for="description" class="form-label">توضیحات</label>
               <textarea name="description" id="description" cols="30" rows="10" class="form-control"></textarea>
              </div>
        </div>

        <!-- Modal footer -->
        <div class="modal-footer">
          <button type="submit" id="submit-form" class="btn btn-success" data-bs-dismiss="modal">افزودن</button>
        </div>
        </form>
      </div>
    </div>
  </div>


@endsection
@section('scripts')
<script src="{{ asset('js/pages/requiremnet.js') }}"></script>
@endsection

