<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="css.css">
    <style>
        .error-message {
            color: red;
            font-size: 12px;
            margin-top: 5px;
            display: block;
        }
    </style>
</head>
<body>
    <form name="RegForm"
     onsubmit="return validateForm()">
        <h2 class="card-title text-center">Register</h2>
        <div class="form-group">
            <input type="text" class="form-control" id="fname" placeholder="Enter First Name">
            <span id="fname-error" class="error-message"></span>
        </div> 
        <div class="form-group">
            <input type="text" class="form-control" id="sname" placeholder="Enter Second Name">
            <span id="sname-error" class="error-message"></span>
        </div>
        <div class="form-group">
            <input type="email" class="form-control" id="email" placeholder="Enter Email">
            <span id="email-error" class="error-message"></span>
        </div>
        <p>
            <input type="checkbox" id="agree" name="Agree" />
            <label for="agree">I agree to the above
                information</label>
            <span id="agree-error" class="error-message"></span>
        </p>
        <button type="submit" class="btn btn-primary">Create Account</button>
        <br>
        <br>
        <h4>Already have an account?</h4>
        <a href="sign_in.php">Login</a>
    </form>
    <script src="js.js"></script>

</body>
</html>