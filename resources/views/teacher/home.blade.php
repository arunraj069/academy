@extends('layouts.teacher')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if($students->isNotEmpty())
                    {{ __('You are Assigned with!') }}
                    @foreach($students as $list)
                        <p>{{ $list->name.' '.$list->email }}</p>
                    @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection