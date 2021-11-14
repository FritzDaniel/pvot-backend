@extends('admin.layouts.app')

@section('title')
    PVOT-Digital | Admin Dashboard
@endsection

@section('css')

@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="#">Education</a></li>
    <li class="breadcrumb-item active">Create</li>
@endsection

@section('headerTitle')
    Create Education
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
                        <h3 class="card-title">Create Education</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <form id="updatePassword" class="fpms" action="{{ route('admin.education.store') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label>Group</label>
                                <select name="group" id="" class="form-control">
                                    <option value="">Select Group</option>
                                    <option value="1">Pengenalan Dropshipper</option>
                                    <option value="2">Konsep Toko Online</option>
                                    <option value="3">Materi Tambahan Penting</option>
                                    <option value="4">Materi Edukasi Marketplace (Shopee)</option>
                                    <option value="5">Materi Edukasi Marketplace (Tokopedia)</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Title</label>
                                <input type="text" name="title" class="form-control" placeholder="Title" value="{{ old('title') }}">
                            </div>
                            <div class="form-group">
                                <label>Url</label>
                                <input type="text" name="url" class="form-control" placeholder="Embed Url Video *(EMBED YOUTUBE URL)" value="{{ old('url') }}">
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

    <script src="{{ asset('assets/adminlte/plugins/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>

    <script>
        $('#submitForm').on('click',function(){
            $('#updatePassword').submit();
        });
    </script>

    <script>
        $(function () {
            bsCustomFileInput.init();
        });
    </script>

@endsection
