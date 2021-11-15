@extends('admin.layouts.app')

@section('title')
    PVOT-Digital | Admin Dashboard
@endsection

@section('css')

@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="#">Education</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('headerTitle')
    Edit Education
@endsection

@section('content')

    <div class="container-fluid">
        <a href="{{ route('admin.education') }}" class="btn btn-primary mb-3"><i class="fa fa-angle-left"></i> Back</a>
        <div class="row">

            <div class="col-12">
                @if (count($errors) > 0)
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h4><i class="icon fa fa-ban"></i> There were some problems with your input!</h4>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </div>
            @endif
            <!-- Default box -->
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Edit Education</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <form id="updatePassword" class="fpms" action="{{ route('admin.education.update',$data->id) }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label>Group</label>
                                <select name="group" id="" class="form-control">
                                    <option value="">Select Group</option>
                                    <option value="1" {{ $data->group == 1 ? 'selected' : null }}>Pengenalan Dropshipper</option>
                                    <option value="2" {{ $data->group == 2 ? 'selected' : null }}>Konsep Toko Online</option>
                                    <option value="3" {{ $data->group == 3 ? 'selected' : null }}>Materi Tambahan Penting</option>
                                    <option value="4" {{ $data->group == 4 ? 'selected' : null }}>Materi Edukasi Marketplace (Shopee)</option>
                                    <option value="5" {{ $data->group == 5 ? 'selected' : null }}>Materi Edukasi Marketplace (Tokopedia)</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Title</label>
                                <input type="text" name="title" class="form-control" placeholder="Title" value="{{ $data->title }}">
                            </div>
                            <div class="form-group">
                                <label>Url</label>
                                <input type="text" name="url" class="form-control" placeholder="Url Video (YOUTUBE)" value="{{ $data->url_youtube }}">
                            </div>
                        </form>
                    </div>

                    <div class="card-footer">
                        <button id="submitForm" class="btn btn-primary bpms">Save</button>
                    </div>

                </div>
                <!-- /.card -->
            </div>
        </div>
    </div>

@endsection

@section('js')

    <script>
        $('#submitForm').on('click',function(){
            $('#updatePassword').submit();
        });
    </script>

@endsection
