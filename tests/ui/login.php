<?php
session_start();
print_r($_SESSION);
?>
<!doctype html>
<html lang="en">
<head>
    <!--https://getbootstrap.com/docs/4.3/examples/sign-in/-->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="../../node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../node_modules/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="style.css">

    <title>Login</title>
</head>

<body>

    <div class="container">
        <div id="userLogin">
            <div id="userLoginAlert" class="alert alert-danger alert-dismissible fade show d-none" role="alert">
                {{ alert }}
                <button type="button" class="close" aria-label="Close" v-on:click="hideAlert()">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form>
                <div class="form-group">
                    <label for="userLoginId">{{ $t('USER_ID') }}</label>
                    <input class="form-control" name="USER_ID" id="userLoginId" v-bind:placeholder="$t('USER_ID')"
                        v-model="id" v-validate="'required'" maxlength="32" aria-required="true">
                    <small id="userLoginIdError" class="form-text text-danger">{{ errors.first('USER_ID') }}</small>
                </div>
                <div class="form-group">
                    <label for="userLoginPassword">{{ $t('PASSWORD') }}</label>
                    <input type="password" class="form-control" id="userLoginPassword" name="PASSWORD"
                        v-bind:placeholder="$t('PASSWORD')" v-model="password" v-validate="'required'" ref="PASSWORD"
                        aria-required="true">
                    <small id="userEditPasswordError"
                        class="form-text text-danger">{{ errors.first('PASSWORD') }}</small>
                </div>
            </form>
            <button type="submit" class="btn btn-primary" v-on:click="login()">{{ $t('LOGIN') }}</button>
            <button type="submit" class="btn btn-primary" v-on:click="logout()">{{ $t('LOGOUT') }}</button>
        </div>
    </div>

    <script src="../../node_modules/jquery/dist/jquery.min.js"></script>
    <script src="../../node_modules/popper.js/dist/umd/popper.min.js"></script>
    <script src="../../node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="../../node_modules/vue/dist/vue.js"></script>
    <script src="../../node_modules/vue-i18n/dist/vue-i18n.min.js"></script>
    <script src="../../node_modules/vee-validate/dist/vee-validate.min.js"></script>
    <script src="../../node_modules/vee-validate/dist/locale/en.js"></script>
    <script src="../../node_modules/vee-validate/dist/locale/de.js"></script>
    <script src="login.js"></script>

</body>

</html>