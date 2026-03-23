<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'My Laravel App')</title>

    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Toastr CSS for toast notifications -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">

    <!-- Additional Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <!-- Header Section -->
    <header>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href="{{ url('/') }}">My Laravel App</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item"><a class="nav-link" href="{{ url('/') }}">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('/about') }}">About</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('/contact') }}">Contact</a></li>
                </ul>
            </div>
        </nav>
    </header>

    <!-- Main Content Section -->
    <main class="container mt-4">
        @yield('content')
    </main>

    <!-- Footer Section -->
    <footer class="text-center py-4">
        <p>&copy; {{ date('Y') }} My Laravel App. All rights reserved.</p>
    </footer>

    <!-- jQuery and Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

    <!-- Toastr JS for toast notifications -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <!-- CSRF Token for AJAX requests -->
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Success and Error Toasts triggered by AJAX responses
        $(document).ready(function () {
            // Handle success toast
            @if(session('success'))
                toastr.success("{{ session('success') }}");
            @endif

            // Handle error toast
            @if(session('error'))
                toastr.error("{{ session('error') }}");
            @endif
        });

      
        $('#someButton').click(function () {
            $.ajax({
                url: '{{ route('some.route') }}',
                type: 'POST',
                data: {key: 'value'},
                success: function(response) {
                    toastr.success(response.message); 
                },
                error: function(xhr) {
                    toastr.error(xhr.responseJSON.message); 
                }
            });
        });
    </script>

    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
