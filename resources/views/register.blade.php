<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register</title>
</head>

<body>
  <div class="register">
    <h1>Register</h1>

    <form action="{{ route("login.user") }}" method="post">
      @csrf 
      <input type="text" name="name" placeholder="Enter your name" maxlength="50">
      <input type="password" name="password" placeholder="Enter your password" maxlength="50">
      <a href="{{ route("login.page") }}">Already registered ?</a>
      <button type="submit">Register</button>
    </form>
  </div>
</body>

</html>