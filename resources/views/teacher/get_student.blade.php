@extends('layouts.teacher')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header" align="center">{{ __('Mark List') }} <span style="float: left;"><a href="{{ route('teacher.createStudentMark')}}"><button class="btn btn-info" >Create</button></a></span></div>

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
                                <th>Action</th>
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
                                <td><p><a href="{{ route('teacher.createStudentMark') }}/{{ Crypt::encrypt( $details['term_id'].'-'.$details['user_id'])}}" class="text-muted mark_edit" data-toggle="tooltip" data-id="" title="Edit Mark"><button class="btn btn-sm btn-info">edit</button></a>&nbsp<span><form action="{{ route('teacher.deleteStudentMark', Crypt::encrypt( $details['term_id'].'-'.$details['user_id']))}}" method="post"  class="mark_delete">
                                   @method('DELETE')
                                   @csrf
                                   <input class="btn btn-sm btn-danger mark_delete" type="submit" value="Delete" />
                                </form> </span></p>
                            </tr>
                            @endforeach
                        </tbody>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection