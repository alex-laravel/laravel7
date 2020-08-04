@extends('backend.layout.main')

@section('title', 'Backend')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        Backend Page
                    </div>

                    <div class="card-body">
                        @guest
                            You are on the Backend Page!
                        @else
                            You are logged in!
                        @endguest
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
