@extends('auth.layout.main')

@section('title', trans('labels.auth.password_confirm.title'))

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    {{ trans('labels.auth.password_confirm.title') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('auth.password.confirm') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ trans('labels.auth.password_confirm.form.password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ trans('buttons.auth.password_confirm') }}
                                </button>

                                @if (Route::has('auth.password.request'))
                                    <a class="btn btn-link" href="{{ route('auth.password.request') }}">
                                        {{ trans('labels.auth.password_confirm.forgot_password') }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
