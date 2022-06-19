@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header" align="center">{{ __('Create Student') }}
                    <span style="float: left;"><a href="{{ route('admin.getStudent')}}"><button class="btn btn-info" >List</button></a></span></div>

                <div class="card-body">
                    @if(\Session::has('error'))
                        <div class="alert alert-danger">{{ \Session::get('error') }}</div>
                    @endif
                    @if(\Session::has('success'))
                        <div class="alert alert-success">{{ \Session::get('success') }}</div>
                    @endif
                    <form method="POST" action="{{ route('admin.createStudentSubmit') }}">
                        @csrf
                        <input type="hidden" name="type" value="{{ !empty($getuser) ? Crypt::encrypt($getuser->id) : 'new'}}">
                        <div class="row mb-3">
                            <label for="name" class="col-md-2 col-form-label text-md-end">{{ __('Name') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="name" value="{{ !empty($getuser) ? $getuser->name : old('name')}}" required autocomplete="name" autofocus minlength="3" maxlength="255" {{ !empty($getuser) ? 'readonly' :''}}>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="email" class="col-md-2 col-form-label text-md-end">{{ __('Email') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ !empty($getuser) ? $getuser->email : old('email')}}" required autocomplete="email" minlength="3" maxlength="255" {{ !empty($getuser) ? 'readonly' :''}}>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="email" class="col-md-2 col-form-label text-md-end">{{ __('Age') }}</label>

                            <div class="col-md-6">
                                <input id="age" type="number" class="form-control" name="age" value="{{ !empty($getuser) ? $getuser->age : old('age')}}" required autocomplete="age" maxlength="3">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="gender" class="col-md-2 col-form-label text-md-end">{{ __('Gender') }}</label>

                            <div class="col-md-6 form-group">
                                <select class="form-select" name="gender" id="gender" required>
                                  <option value="">Choose</option>
                                  @foreach($gender as $list)
                                  <option value="{{$list['id']}}" {{ !empty($getuser) && $getuser->gender == $list['id'] ? 'selected' :  old('gender') == $list['id'] ? 'selected' : '' }}>{{$list['name']}}</option> 
                                  @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="teacher" class="col-md-2 col-form-label text-md-end">{{ __('Tutor') }}</label>

                            <div class="col-md-6 form-group">
                                <select class="form-select" name="teacher" id="teacher" required>
                                  <option value="">Choose</option>
                                  @foreach($teacher as $list)
                                  <option value="{{$list['id']}}" {{ !empty($getuser) && $getuser->teacher == $list['id'] ? 'selected' :  old('gender') == $list['id'] ? 'selected' : '' }}>{{$list['name']}}</option>
                                  @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row mb-0">
                            <div class="col-md-4 offset-md-2">
                                <button type="submit" class="btn btn-primary">
                                    {{ !empty($getuser) ? __('Update') :__('Register') }}
                                </button>

                                <!-- @if (Route::has('password.request'))
                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                @endif -->
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
