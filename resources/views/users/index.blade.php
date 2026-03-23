<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users List</title>
</head>
<body>
    <h1>Users List</h1>

    <table border="1" cellpadding="10" cellspacing="0">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users->items() as $user)  <!-- Accessing the items -->
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->created_at }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="pagination">
        <!-- Previous Page -->
        @if ($users->previousPageUrl()) 
            <a href="{{ $users->previousPageUrl() }}">Previous</a>
        @else
            <span>Previous</span>
        @endif

        <!-- Next Page -->
        @if ($users->nextPageUrl()) 
            <a href="{{ $users->nextPageUrl() }}">Next</a>
        @else
            <span>Next</span>
        @endif
    </div>
</body>
</html>
