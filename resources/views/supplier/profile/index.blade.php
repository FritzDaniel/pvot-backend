@extends('supplier.layouts.app')

@section('title')
    PVOT-Digital | Supplier Dashboard
@endsection

@section('css')

@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
    <li class="breadcrumb-item active">Profile</li>
@endsection

@section('headerTitle')
    Profile
@endsection

@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                @if (session('message'))
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h4><i class="icon fa fa-check"></i> Success!</h4>
                        {{ session('message') }}
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
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Update Profile</h3>

                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <form id="updatePassword" class="fpms" action="{{ route('supplier.profile.update') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="exampleInputFile">Profile Picture</label> <br>
                                <img src="{{ asset($data->profilePicture) }}" alt="" class="mb-3" style="width: 75px; height: 75px;">
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="exampleInputFile" name="profilePicture">
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
                                <label>Email</label>
                                <input type="email" name="" class="form-control" placeholder="Email" value="{{ $data->email }}" disabled>
                            </div>
                            <div class="form-group">
                                <label>Phone</label>
                                <input type="text" name="phone" class="form-control" placeholder="Phone" value="{{ $data->phone }}">
                            </div>
                            <div class="form-group">
                                <label>Country</label>
                                <input type="text" name="country" class="form-control" placeholder="Country" value="{{ $data->country }}">
                            </div>
                            <div class="form-group">
                                <label>Address</label>
                                <input type="text" name="address" class="form-control" placeholder="Address" value="{{ $data->alamat }}">
                            </div>
                            <div class="form-group">
                                <label>Province</label>
                                <input type="text" name="province" class="form-control" placeholder="Province" value="{{ $data->provinsi }}">
                            </div>
                            <div class="form-group">
                                <label>Postal Code</label>
                                <input type="text" name="postalCode" class="form-control" placeholder="Postal Code" value="{{ $data->kodepos }}">
                            </div>
                            <div class="form-group">
                                <label>Old Password</label>
                                <input type="password" name="old_password" class="form-control" placeholder="Old Password" value="{{ old('old_password') }}">
                            </div>
                            <div class="form-group">
                                <label>New Password</label>
                                <input type="password" name="new_password" class="form-control" placeholder="New Password" value="{{ old('new_password') }}">
                            </div>
                            <div class="form-group">
                                <label>Confirm Password</label>
                                <input type="password" name="c_password" class="form-control" placeholder="Confirm Password" value="{{ old('c_password') }}">
                            </div>
                            <div class="form-group">
                                <input type="submit" value="Save" class="btn btn-primary bpms">
                            </div>
                        </form>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        Updated at {{ \Carbon\Carbon::now()->format('d-m-Y') }}
                    </div>
                    <!-- /.card-footer-->
                </div>
                <!-- /.card -->
            </div>
        </div>
    </div>

@endsection

@section('js')

    <script src="{{ asset('assets/adminlte/plugins/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>

    <script>
        $(function () {
            bsCustomFileInput.init();
        });
    </script>

@endsection
