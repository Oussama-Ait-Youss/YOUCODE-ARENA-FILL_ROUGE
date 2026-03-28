<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form method="POST" action="{{ route('logout') }}">
    @csrf
    <button type="submit" style="padding: 10px; background: red; color: white; border: none; border-radius: 5px; cursor: pointer;">
        Log Out
    </button>
</form>
</body>
</html>