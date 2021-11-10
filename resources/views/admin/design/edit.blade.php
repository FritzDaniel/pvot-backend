@extends('admin.layouts.app')

@section('title')
    PVOT-Digital | Admin Dashboard
@endsection

@section('css')

@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="#">Design</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('headerTitle')
    Edit Design
@endsection

@section('content')

    <div class="container-fluid">
        <a href="{{ route('admin.design') }}" class="btn btn-primary mb-3"><i class="fa fa-angle-left"></i> Back</a>
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
                        <h3 class="card-title">Edit Design</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <form id="updatePassword" class="fpms" action="{{ route('admin.design.update',$data->id) }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label>Design Name</label>
                                <input type="text" name="designName" class="form-control" placeholder="Category Name" value="{{ $data->designName }}">
                            </div>
                            <div class="form-group">
                                <label>Design for Supplier</label>
                                <select name="supplier" class="form-control select2">
                                    <option value="">- Choose Supplier -</option>
                                    @foreach($supplier as $sup)
                                        <option value="{{ $sup->id }}">{{ $sup->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputFile">Logo</label> <br>
                                <img src="{{ asset($data->designImage) }}" alt="" class="mb-3" style="width: 75px; height: 75px;">
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="exampleInputFile" name="designImage">
                                        <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                    </div>
                                    <div class="input-group-append">
                                        <span class="input-group-text">Upload</span>
                                    </div>
                                </div>
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

    <script src="{{ asset('assets/adminlte/plugins/select2/js/select2.full.min.js') }}"></script>
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

    <script>
        $(function () {
            //Initialize Select2 Elements
            $('.select2').select2({
                theme: 'bootstrap4'
            })
        });
    </script>

@endsection
