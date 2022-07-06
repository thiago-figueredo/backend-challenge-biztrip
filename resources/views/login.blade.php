<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
  <title>Login</title>
</head>

<body>
  <div class="login">
    <h1>Login</h1>

    <form action="{{ route("api.auth.login") }}" method="post">
      @csrf 
      <input type="text" name="name" placeholder="Enter your name" maxlength="50">
      <input type="password" name="password" placeholder="Enter your password" maxlength="50">
      <a href="{{ route("api.auth.register") }}">Not logged in ?</a>
      <button type="submit">Login</button>
    </form>
  </div>

</html>