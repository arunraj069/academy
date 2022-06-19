@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header" align="center">{{ __('Student List') }} <span style="float: left;"><a href="{{ route('admin.createStudent')}}"><button class="btn btn-info" >Create</button></a></span></div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <table id="list-student-table" class="table table-striped dt-responsive nowrap table-vertical" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>SL/NO</th> 
                                <th>Name</th> 
                                <th>Email</th>                              
                                <th>Age</th>
                                <th>Gender</th>
                                <th>Reporting</th>
                                 <th>Created At</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection