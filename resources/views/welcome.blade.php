@extends('template')

@section('title')
Naive Bayes
@endsection
@section('head')
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="\css\index.css">
@endsection
@section('body')
    <div class="flex-center position-ref full-height">
            @if (Route::has('login'))
                <div class="top-right links">
                    @auth
                        <a href="{{ url('/home') }}">Home</a>
                    @else
                        <a href="{{ route('login') }}">Login</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}">Register</a>
                        @endif
                    @endauth
                </div>
            @endif

            <div class="content">
                <div class="title m-b-md">
                    Naive Bayes
                </div>
                <div class="links">
                    <form id="form" action="/view-table" method="post" enctype="multipart/form-data">
                        @csrf
                        <label for="file-upload" class="button">
                            Pilih File
                        </label>
                        <input id="file-upload" name="file" type="file"/>
                    </form>
                </div>
            </div>
        </div>
    </body>
    <script>
        document.getElementById("file-upload").onchange = function() {
            document.getElementById("form").submit();
        }
    </script>
@endsection
