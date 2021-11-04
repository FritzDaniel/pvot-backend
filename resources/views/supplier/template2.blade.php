@extends('admin.layouts.app')

@section('title')
    Mul-App | ###
@endsection

@section('css')

@endsection

@section('headerTitle')
    ###
    <small>###</small>
@endsection

@section('content')

    <div class="row">
        <div class="col-md-3">
            <a href="#" class="btn btn-primary btn-block margin-bottom">
                <i class="fa fa-circle"></i> ###
            </a>

            <div class="box box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title">Navigation</h3>

                    <div class="box-tools">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="box-body no-padding">
                    <ul class="nav nav-pills nav-stacked">
                        <li>
                            <a href="#"><i class="fa fa-circle"></i> ###
                                <span class="label label-info pull-right">0</span>
                            </a>
                        </li>
                        <li>
                            <a href="#" id="#"><i class="fa fa-circle"></i> ###
                                <span class="label label-primary pull-right">0</span>
                            </a>
                        </li>
                        <li>
                            <a href="#" id="#"><i class="fa fa-circle"></i> ###
                                <span class="label label-warning pull-right">0</span>
                            </a>
                        </li>
                        <li>
                            <a href="#" id="#"><i class="fa fa-circle"></i> ###
                                <span class="label label-success pull-right">0</span>
                            </a>
                        </li>
                        <li>
                            <a href="#" id="#"><i class="fa fa-circle"></i> ###
                                <span class="label label-danger pull-right">0</span>
                            </a>
                        </li>
                    </ul>

                </div>
                <!-- /.box-body -->
            </div>
            <!-- /. box -->
        </div>
        <!-- /.col -->
        <div class="col-md-9">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">###</h3>

                    <div class="box-tools">
                        <form action="#" class="input-group input-group-sm" style="width: 150px;" method="GET">
                            <input type="text" name="search" class="form-control pull-right" placeholder="Search">

                            <div class="input-group-btn">
                                <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                            </div>
                        </form>
                    </div>
                    <!-- /.box-tools -->
                </div>
                <!-- /.box-header -->

                <div class="box-body no-padding">
                    <div class="table-responsive mailbox-messages">
                        <table class="table table-hover table-striped">
                            <thead>
                            <tr>
                                <th>###</th>
                                <th>###</th>
                                <th>###</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>###</td>
                                <td>###</td>
                                <td>
                                    <a href="#" class="btn btn-sm btn-primary"><i class="fa fa-eye"></i></a>
                                    <a href="#" class="btn btn-sm btn-info"><i class="fa fa-edit"></i></a>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <!-- /.table -->
                    </div>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /. box -->
        </div>
        <!-- /.col -->
    </div>

@endsection

@section('js')

@endsection