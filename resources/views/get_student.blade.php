@extends('layouts.teacher')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header" align="center">{{ __('Mark List') }} </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <table id="list-student-marks-table" class="table table-striped dt-responsive nowrap table-vertical" width="100%" cellspacing="0">
                        @if($termMarks->isNotEmpty())
                        <thead>
                            <tr>
                                <th>SL/NO</th> 
                                <th>Name</th>  
                                @foreach($subjects as $sub)
                                    <th>{{  $sub->name }}</th>
                                @endforeach                            
                                <th>Term</th>
                                <th>Total</th>
                                <th>Created_at</th>
                            </tr>
                        </thead>
                        <tbody>   
                            @foreach($termMarks as $details)
                            <tr> 
                                <td>{{ $loop->iteration }}</td>         
                                <td>{{ $details['user'] }}</td> 
                                @foreach($subjects as $sub)
                                    <td>{{ $details['marks'][$sub->name] }}</td>
                                @endforeach  
                                <td>{{ $details['term'] }}</td> 
                                <td>{{ $details['sum'] }}</td>
                                <td>{{ Utils::dateTimeFormat($details['created_at']) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        @else
                        <p> No results found </p>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection