@extends('frontend.layout.main')

@section('title', 'Frontend')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        Frontend Page
                    </div>

                    <div class="card-body">
                        @guest
                            You are on the Frontend Page!
                        @else
                            You are logged in!
                        @endguest
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
