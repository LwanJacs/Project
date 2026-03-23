<!DOCTYPE html>
<html>
    <head>
        
        <!--tells browser what character encoding the page uses. 
        Without it mignht not display some characters-->
        <meta charset="utf-8">
        
        <!--width=device-width makes the page mathc device screen width.
        initial scale=1.0 sets the initial zoom to normal (100%)-->
        <meta name="viewport"
        content="width=device-width, initial-scale=1.0">
        
        <!--Imports Font Awesome icons-->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        
        <!--connets to externl css file for styling an customization-->
        <link rel="stylesheet" href="new_style.css">
        <title>Registraion</title>
    </head>

    <body>
        <!--creates a form where users can enter data. 
        autocomplete="off" stops the browser from suggesting previously entered values.
        class="form" connects this form to css styling-->
        <form autocomplete="off" class="form">
            <!--class connects to css file allowing it for it to be styled-->
            
            <p class="title">Registraion</p>
            <p class="message">Signup now and get full access to our webpage.</p>
            
            <!--div is a container element.
            class="form-group" is used for styling. 
            This will make firstname and lastname sit next to each other.-->
            <div class="form-group">
                <label>
                    <input type="text" required>
                    
                    <!---this for a float labeling effect to create a clean animation.-->
                    <span>Firstname</span>
                </label>
                <label>
                    <input type="text" required>
                    <span>Lastname</span>
                </label>
            </div>
            <label>
                <!--wrong format must be added.-->
                <input type="email" required>
                <span>Email</span>
            </label>
            <label>
                
                <!--id used in javascript.-->
                <input type="password" id="password" required>
                <span>Password</span>
                
                <!-- Font awesome icon-->
                <span class="icon" id="togglePassword">
                    <i class="fa fa-eye-slash"></i>
                </span>
            </label>
            <label>
                <input type="password" id="passwordConfirm" required>
                <span>Confirm password</span>
                <span class="icon" id="togglePasswordConfirm">
                    <i class="fa fa-eye-slash"></i>
                </span>
            </label>
            <button class="submit">Submit</button>
            <p class="signin">
                Already have an account?
                <a href="#">Signin</a>
            </p>
        </form>
        <script src="main.js"></script>
    </body>
</html>