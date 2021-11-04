@extends('admin.layouts.app')

@section('title')
    PVOT-Digital | Admin Dashboard
@endsection

@section('css')

@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="#">Testimony</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('headerTitle')
    Edit Testimony
@endsection

@section('content')

    <div class="container-fluid">
        <a href="{{ route('admin.testimony') }}" class="btn btn-primary mb-3"><i class="fa fa-angle-left"></i> Back</a>
        <div class="row">

            <div class="col-12">
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h4><i class="icon fa fa-times"></i> Failed!</h4>
                        {{ session('error') }}
                    </div>
                @endif
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
                        <h3 class="card-title">Create Testimony</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <form id="updatePassword" class="fpms" action="{{ route('admin.testimony.update',$data->id) }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="exampleInputFile">Photo</label> <br>
                                <img src="{{ asset($data->photo) }}" alt="" class="mb-3" style="width: 75px; height: 75px;">
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="exampleInputFile" name="photo">
                                        <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                    </div>
                                    <div class="input-group-append">
                                        <span class="input-group-text">Upload</span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Name</label>
                                <input type="text" name="name" class="form-control" placeholder="Name" value="{{ $data->name }}">
                            </div>
                            <div class="form-group">
                                <label>Age</label>
                                <input type="number" name="age" class="form-control" placeholder="Age" value="{{ $data->age }}">
                            </div>
                            <div class="form-group">
                                <label>Testimony</label>
                                <textarea name="testimony" class="form-control" placeholder="Testimony">{{ $data->testimoni }}</textarea>
                            </div>
                            <div class="form-group">
                                <label>Company</label>
                                <input type="text" name="company" class="form-control" placeholder="Company" value="{{ $data->company }}">
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
