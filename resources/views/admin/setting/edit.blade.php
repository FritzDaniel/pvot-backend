@extends('admin.layouts.app')

@section('title')
    PVOT-Digital | Admin Dashboard
@endsection

@section('css')

@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
    <li class="breadcrumb-item active">Template</li>
@endsection

@section('headerTitle')
    Template
@endsection

@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <!-- Default box -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Title</h3>

                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <form class="fpms" id="updatePassword" action="{{ route('admin.settings.update',$data->id) }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="">Setting Name</label>
                                <input type="text" value="{{ $data->name }}" class="form-control" disabled>
                            </div>
                            <div class="form-group">
                                <label for="">Setting Value</label>
                                <input type="text" value="{{ $data->value }}" name="value" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="">Setting Type</label>
                                <input type="text" value="{{ $data->tipe }}" class="form-control" disabled>
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
