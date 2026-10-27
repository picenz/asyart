<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asyart - Home</title>
</head>

<body>
    <h1>Welcome to Asyart!</h1>
    <p>Jelajahi koleksi baju kami tanpa harus login.</p>

    <a href="{{ route('products.index') }}">Lihat Produk</a>
    <a href="{{ route('login') }}">Login</a>
    <a href="{{ route('register') }}">Register</a>
</body>

</html>
