@extends('auth.layout.main')

@section('title', trans('labels.auth.verify.title'))

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        {{ trans('labels.auth.verify.title') }}
                    </div>

                    <div class="card-body">
                        {{ trans('strings.auth.verify.check_email_for_verification_link') }}
                        {{ trans('strings.auth.verify.not_receive_email') }}
                        <form class="d-inline" method="POST" action="{{ route('auth.verification.resend') }}">
                            @csrf
                            <button type="submit" class="btn btn-link p-0 m-0 align-baseline">{{ trans('strings.auth.verify.another_verification_email') }}</button>.
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
