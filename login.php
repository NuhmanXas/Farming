<!DOCTYPE html>
<html lang="en">
<head>
   <!-- basic -->
   <meta charset="utf-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <!-- mobile metas -->
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <!-- site metas -->
   <title>Login</title>
   <meta name="keywords" content="">
   <meta name="description" content="">
   <meta name="author" content="">
   <!-- bootstrap css -->
   <link rel="stylesheet" href="css/bootstrap.min.css">
   <!-- style css -->
   <link rel="stylesheet" href="css/style.css">
   <!-- Responsive-->
   <link rel="stylesheet" href="css/responsive.css">
   <!-- favicon -->
   <link rel="icon" href="images/fevicon.png" type="image/gif" />
   <!-- Scrollbar Custom CSS -->
   <link rel="stylesheet" href="css/jquery.mCustomScrollbar.min.css">
   <!-- Tweaks for older IEs-->
   <link rel="stylesheet" href="https://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.css" media="screen">
   <style>
    body {
    margin: 0;
    padding: 0;
    font-family: Arial, sans-serif;
    background: url('images/login_bg.webp') no-repeat center center fixed;
    background-size: cover;
}

.login-container {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

.login-form {
    background: rgba(237, 237, 240, 0.452); /* Blue tint with 50% transparency */
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
    text-align: center;
    width: 400px;
    height: 400px;
}

.login-form h2 {
    margin-bottom: 20px;
    color: #0a0a0a62; 
}

.login-form input {
    display: block;
    width: 100%;
    padding: 10px;
    margin-bottom: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

.login-form a {
    display: block;
    margin-bottom: 10px;
    color: #007bff;
    text-decoration: none;
}

.login-form button {
    padding: 10px 20px;
    border: none;
    background: #3131e4;
    color: #fff;
    border-radius: 5px;
    cursor: pointer;
}

.login-form button:hover {
    background:silver;
}

   </style>
   
</head>
<body>
<div class="login-container">
    <form class="login-form">
        <h2>Login</h2>
        <input type="email" placeholder="Email" required>
        <input type="password" placeholder="Password" required>
        <a href="#">Forgot Password</a>
        <a href="#">Signup</a>
        <button type="submit">Login</button>
    </form>
</div>
</body>
</html>