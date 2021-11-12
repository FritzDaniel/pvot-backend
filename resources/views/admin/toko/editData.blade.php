    @extends('admin.layouts.app')

    @section('title')
        PVOT-Digital | Admin Dashboard
    @endsection

    @section('css')

    @endsection

    @section('breadcrumb')
        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
        <li class="breadcrumb-item active"><a href="#">Toko</a></li>
        <li class="breadcrumb-item active">Edit Data</li>
    @endsection

    @section('headerTitle')
        Edit Data Toko
    @endsection

    @section('content')

        <div class="container-fluid">
            <a href="{{ route('admin.toko') }}" class="btn btn-primary mb-3"><i class="fa fa-angle-left"></i> Back</a>
            <div class="row">
                <div class="col-12">
                    <!-- Default box -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Edit Data Toko</h3>

                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <form id="updatePassword" class="fpms" action="{{ route('admin.toko.updateData',$data->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf

                                <div class="form-group">
                                    <label for="">Toko Name</label>
                                    <input type="text" name="namaToko" value="{{ $data->namaToko }}" class="form-control">
                                </div>

                                <div class="form-group">
                                    <label for="">Toko Email</label>
                                    <input type="text" value="{{ $data->emailToko }}" class="form-control" disabled>
                                </div>

                                <div class="form-group">
                                    <label for="">Toko Phone</label>
                                    <input type="text" name="handphoneToko" value="{{ $data->handphoneToko }}" class="form-control">
                                </div>

                                <div class="form-group">
                                    <label for="">Toko Address</label>
                                    <input type="text" name="alamatToko" value="{{ $data->alamatToko }}" class="form-control">
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputFile">Toko Picture</label> <br>
                                    <img src="{{ asset($data->fotoToko) }}" alt="" class="mb-3" style="width: 75px; height: 75px;">
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="exampleInputFile" name="fotoToko">
                                            <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                        </div>
                                        <div class="input-group-append">
                                            <span class="input-group-text">Upload</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputFile">Toko Header Picture</label> <br>
                                    <img src="{{ asset($data->fotoHeaderToko) }}" alt="" class="mb-3" style="width: 75px; height: 75px;">
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="exampleInputFile" name="fotoHeaderToko">
                                            <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                        </div>
                                        <div class="input-group-append">
                                            <span class="input-group-text">Upload</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="">Toko Description</label>
                                    <textarea name="description" class="form-control">{{ $data->description }}</textarea>
                                </div>

                            </form>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                            <button id="submitForm" class="btn btn-primary bpms">Save</button>
                        </div>
                        <!-- /.card-footer-->
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
