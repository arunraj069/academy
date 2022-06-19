@extends('layouts.teacher')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header" align="center">{{ __('Assign Marks') }}
                    <span style="float: left;"><a href="{{ route('teacher.getStudentMark')}}"><button class="btn btn-info" >List</button></a></span></div>

                <div class="card-body">
                    @if(\Session::has('error'))
                        <div class="alert alert-danger">{{ \Session::get('error') }}</div>
                    @endif
                    @if(\Session::has('success'))
                        <div class="alert alert-success">{{ \Session::get('success') }}</div>
                    @endif
                    @if($user->isNotEmpty())
                    <form method="POST" action="{{ route('teacher.createStudentMarkSubmit') }}">
                        @csrf
                        <input type="hidden" name="type" value="{{ !empty($marks) ? $token : 'new'}}">

                        <div class="row mb-3">
                            <label for="student" class="col-md-2 col-form-label text-md-end">{{ __('Student') }}</label>

                            <div class="col-md-6 form-group">
                                <select class="form-select" name="student" id="student" required {{ !empty($marks) ? 'readonly' : ''}}>
                                  <option value="">Choose</option>
                                  @foreach($user as $list)
                                  <option value="{{$list['id']}}" {{ !empty($marks) && $marks->first()->user_id == $list['id'] ? 'selected' : '' }}>{{$list['name']}}</option>
                                  @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="term" class="col-md-2 col-form-label text-md-end">{{ __('Term') }}</label>

                            <div class="col-md-6 form-group">
                                <select class="form-select" name="term" id="term" required {{ !empty($marks) ? 'readonly' : ''}}>
                                  <option value="">Choose</option>
                                  @foreach($term as $list)
                                  <option value="{{$list['id']}}" {{ !empty($marks) && $marks->first()->term_id == $list['id'] ? 'selected' : '' }}>{{$list['name']}}</option>
                                  @endforeach
                                </select>
                            </div>
                        </div>
                        @if($subjects->isNotEmpty())
                        @foreach($subjects as $list)
                        <div class="row mb-3">
                            <label for="subject" class="col-md-2 col-form-label text-md-end">{{$list->name}}</label>

                            <div class="col-md-6">
                                <input id="subject_{{$list->id}}" type="number" class="form-control" name="subject[{{$list->id}}]" value="{{ !empty($marks) ? $marks[$list->id]['marks'] : '' }}" required autocomplete="name" autofocus step="any" max="200">
                            </div>
                        </div>
                        @endforeach
                        @endif
                        <div class="row mb-0">
                            <div class="col-md-4 offset-md-2">
                                <button type="submit" class="btn btn-primary">
                                    {{ !empty($marks) ? __('Update') :__('Assign') }}
                                </button>

                                <!-- @if (Route::has('password.request'))
                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                @endif -->
                            </div>
                        </div>
                    </form>
                    @else
                        <h5>No students enrolled.</h5>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
