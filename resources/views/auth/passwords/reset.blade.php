@extends('auth.layout.main')

@section('title', trans('labels.auth.password_reset.title'))

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        {{ trans('labels.auth.password_reset.title') }}
                    </div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('auth.password.update') }}">
                            @csrf

                            <input type="hidden" name="token" value="{{ $token }}">

                            <div class="form-group row">
                                <label for="email" class="col-md-4 col-form-label text-md-right">{{ trans('labels.auth.password_reset.form.email') }}</label>

                                <div class="col-md-6">
                                    <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>

                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password" class="col-md-4 col-form-label text-md-right">{{ trans('labels.auth.password_reset.form.password') }}</label>

                                <div class="col-md-6">
                                    <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" required autocomplete="new-password">

                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ trans('labels.auth.password_reset.form.password_confirm') }}</label>

                                <div class="col-md-6">
                                    <input type="password" name="password_confirmation" id="password-confirm" class="form-control" required>
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ trans('buttons.auth.password_reset') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
