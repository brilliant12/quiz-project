<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <form action="{{ route('saveExcel') }}" method="POST">
        @csrf
        <label for="name">Name:</label>
        <input type="text" name="name" required><br>
    
        <label for="email">Email:</label>
        <input type="email" name="email" required><br>
    
        <label for="phone">Phone:</label>
        <input type="text" name="phone" required><br>
    
        <button type="submit">Submit</button>
    </form>
    
</body>
</html>