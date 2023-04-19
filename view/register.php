<!--Student ID: 101399392-->
<!--Student Name: Mahyar Ghasemi Khah-->
<!--CLass: lecture: Tuesday 10-12, Lab: Wednesday 10-12-->

<!DOCTYPE html>
<html lang="eng">
<head>
    <meta charset="UTF-8">
    <title>Assignment 3</title>
    <meta name="description" content="This is Assignmen 3 for COMP1230.">
    <meta name="author" content="Mahyar Ghasemi Khah">
    <meta name="viewpoint" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Exo+2:ital,wght@0,100;0,200;0,300;0,400;1,100;1,200;1,300;1,400&family=Roboto+Slab:wght@400;500;600;700&family=Slabo+27px&display=swap"
          rel="stylesheet">

    <!--second from style-->
    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Roboto, -apple-system, 'Helvetica Neue', 'Segoe UI', Arial, sans-serif;
            background: #3b4465;
        }

        .forms-section {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .section-title {
            font-size: 32px;
            letter-spacing: 1px;
            color: #fff;
        }

        .forms {
            display: flex;
            align-items: flex-start;
            margin-top: 30px;
        }

        .form-wrapper {
            animation: hideLayer .3s ease-out forwards;
        }

        .form-wrapper.is-active {
            animation: showLayer .3s ease-in forwards;
        }

        @keyframes showLayer {
            50% {
                z-index: 1;
            }
            100% {
                z-index: 1;
            }
        }

        @keyframes hideLayer {
            0% {
                z-index: 1;
            }
            49.999% {
                z-index: 1;
            }
        }

        .switcher {
            position: relative;
            cursor: pointer;
            display: block;
            margin-right: auto;
            margin-left: auto;
            padding: 0;
            text-transform: uppercase;
            font-family: inherit;
            font-size: 16px;
            letter-spacing: .5px;
            color: #999;
            background-color: transparent;
            border: none;
            outline: none;
            transform: translateX(0);
            transition: all .3s ease-out;
        }

        .form-wrapper.is-active .switcher-login {
            color: #fff;
            transform: translateX(90px);
        }

        .form-wrapper.is-active .switcher-signup {
            color: #fff;
            transform: translateX(-90px);
        }

        .underline {
            position: absolute;
            bottom: -5px;
            left: 0;
            overflow: hidden;
            pointer-events: none;
            width: 100%;
            height: 2px;
        }

        .underline::before {
            content: '';
            position: absolute;
            top: 0;
            left: inherit;
            display: block;
            width: inherit;
            height: inherit;
            background-color: currentColor;
            transition: transform .2s ease-out;
        }

        .switcher-login .underline::before {
            transform: translateX(101%);
        }

        .switcher-signup .underline::before {
            transform: translateX(-101%);
        }

        .form-wrapper.is-active .underline::before {
            transform: translateX(0);
        }

        .form {
            overflow: hidden;
            min-width: 260px;
            margin-top: 50px;
            padding: 30px 25px;
            border-radius: 5px;
            transform-origin: top;
        }

        .form-login {
            animation: hideLogin .3s ease-out forwards;
        }

        .form-wrapper.is-active .form-login {
            animation: showLogin .3s ease-in forwards;
        }

        @keyframes showLogin {
            0% {
                background: #d7e7f1;
                transform: translate(40%, 10px);
            }
            50% {
                transform: translate(0, 0);
            }
            100% {
                background-color: #fff;
                transform: translate(35%, -20px);
            }
        }

        @keyframes hideLogin {
            0% {
                background-color: #fff;
                transform: translate(35%, -20px);
            }
            50% {
                transform: translate(0, 0);
            }
            100% {
                background: #d7e7f1;
                transform: translate(40%, 10px);
            }
        }

        .form-signup {
            animation: hideSignup .3s ease-out forwards;
        }

        .form-wrapper.is-active .form-signup {
            animation: showSignup .3s ease-in forwards;
        }

        @keyframes showSignup {
            0% {
                background: #d7e7f1;
                transform: translate(-40%, 10px) scaleY(.8);
            }
            50% {
                transform: translate(0, 0) scaleY(.8);
            }
            100% {
                background-color: #fff;
                transform: translate(-35%, -20px) scaleY(1);
            }
        }

        @keyframes hideSignup {
            0% {
                background-color: #fff;
                transform: translate(-35%, -20px) scaleY(1);
            }
            50% {
                transform: translate(0, 0) scaleY(.8);
            }
            100% {
                background: #d7e7f1;
                transform: translate(-40%, 10px) scaleY(.8);
            }
        }

        .form fieldset {
            position: relative;
            opacity: 0;
            margin: 0;
            padding: 0;
            border: 0;
            transition: all .3s ease-out;
        }

        .form-login fieldset {
            transform: translateX(-50%);
        }

        .form-signup fieldset {
            transform: translateX(50%);
        }

        .form-wrapper.is-active fieldset {
            opacity: 1;
            transform: translateX(0);
            transition: opacity .4s ease-in, transform .35s ease-in;
        }

        .form legend {
            position: absolute;
            overflow: hidden;
            width: 1px;
            height: 1px;
            clip: rect(0 0 0 0);
        }

        .input-block {
            margin-bottom: 20px;
        }

        .input-block label {
            font-size: 14px;
            color: #a1b4b4;
        }

        .input-block input {
            display: block;
            width: 100%;
            margin-top: 8px;
            padding-right: 15px;
            padding-left: 15px;
            font-size: 16px;
            line-height: 40px;
            color: #3b4465;
            background: #eef9fe;
            border: 1px solid #cddbef;
            border-radius: 2px;
        }

        .form [type='submit'] {
            opacity: 0;
            display: block;
            min-width: 120px;
            margin: 30px auto 10px;
            font-size: 18px;
            line-height: 40px;
            border-radius: 25px;
            border: none;
            transition: all .3s ease-out;
        }

        .form-wrapper.is-active .form [type='submit'] {
            opacity: 1;
            transform: translateX(0);
            transition: all .4s ease-in;
        }

        .btn-login {
            color: #fbfdff;
            background: #3c474f;
            transform: translateX(-30%);
        }

        .btn-signup {
            color: #3c474f;
            background: #fbfdff;
            box-shadow: inset 0 0 0 2px #3c474f;
            transform: translateX(30%);
        }

        #err-modal {
            display: flex;
            justify-content: center;
            align-items: center;
            visibility: hidden;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100vh;
            color: #f5f5f5;
            background: rgba(0, 0, 0, 0.5);
            transition: opacity 500ms ease-in-out;
            z-index: 100;
        }

        #err-modal #err-content {
            padding: 2px 15px;
            border-radius: 10px;
            background-color: #ec5668;
            text-align: center;
        }
    </style>

</head>
<body>

<div id="err-modal">
    <div id="err-content">
        <p id="err-msg"></p>
    </div>
</div>

<!--second form-->
<section class="forms-section">
    <h1 class="section-title">Welcome</h1>
    <div class="forms">
        <div class="form-wrapper is-active">
            <button type="button" class="switcher switcher-login">
                Login
                <span class="underline"></span>
            </button>
            <!--Login in form-->
            <form method="post" class="form form-login">
                <fieldset>
                    <legend>Please, enter your email and password for login.</legend>
                    <div class=" input-block">
                        <label for="login-username">Username</label>
                        <input id="login-username" type="text" name="username" minlength="4" maxlength="8" required>
                    </div>
                    <?php
                    //Prefill the username into the login page upon revisiting the site
                    if (isset($_COOKIE['username'])) {
                        $username = $_COOKIE['username'];
                        echo "<script>document.getElementById('login-username').value = '$username';</script>";
                    }
                    ?>
                    <div class="input-block">
                        <label for="login-email">E-mail</label>
                        <input id="login-email" type="email" name="login-email" required>
                    </div>
                    <div class="input-block">
                        <label for="login-password">Password</label>
                        <input id="login-password" type="password" name="login-password" minlength="8" required>
                    </div>
                </fieldset>
                <button type="submit" class="btn-login">Login</button>
            </form>
        </div>

        <!--Register in form-->
        <div class="form-wrapper">
            <button type="button" class="switcher switcher-signup">
                Sign Up
                <span class="underline"></span>
            </button>
            <form method="post" class="form form-signup">
                <fieldset>
                    <legend>Please, enter your email, password and password confirmation for sign up.</legend>
                    <div class=" input-block">
                        <label for="signup-username">Username</label>
                        <input id="signup-username" type="text" name="username" minlength="4" maxlength="8" required>
                    </div>
                    <div class="input-block">
                        <label for="signup-email">E-mail</label>
                        <input id="signup-email" type="email" name="signup-email" required>
                    </div>
                    <div class="input-block">
                        <label for="signup-password">Password</label>
                        <input id="signup-password" type="password" name="password" minlength="8" required>
                    </div>
                    <div class="input-block">
                        <label for="signup-password-confirm">Confirm password</label>
                        <input id="signup-password-confirm" type="password" name="confirmedPassword" required>
                    </div>
                </fieldset>
                <input type="submit" name="submit" class="btn-signup" value="Continue">
            </form>
        </div>
    </div>
</section>


<script>
    const switchers = [...document.querySelectorAll('.switcher')]

    switchers.forEach(item => {
        item.addEventListener('click', function () {
            switchers.forEach(item => item.parentElement.classList.remove('is-active'))
            this.parentElement.classList.add('is-active')
        })
    })

    function showError(errMsg) {
        document.getElementById('err-msg').innerText = errMsg;
        document.getElementById('err-modal').style.visibility = 'visible';
        document.getElementById('err-modal').style.opacity = '1';

        setTimeout(function () {
            document.getElementById('err-modal').style.opacity = '0';
            setTimeout(function () {
                document.getElementById('err-modal').style.visibility = 'hidden';
            }, 500);
        }, 2000);
    }

    let errorMsg = "<?php echo $errorMes?>";
    if (errorMsg) {
        showError(errorMsg);
    }
</script>
</body>
</html>