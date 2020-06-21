<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>UPortal</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css')}}">

</head>

<body>
    <div class="d-flex align-items-center justify-content-center vh-100">

        <div class="row">
            <div class="col-lg-6 col-sm-6 p-0">
                <img src="img/USQ.jpg" width="100%">
            </div>
            <div class="col-lg-6 col-sm-6 border border-gray">
                <div class="d-flex align-items-center flex-column h-100">
                    <div class="p-6">&nbsp;</div>
                    <div class="d-flex align-items-center flex-column ">
                        <div class="row">
                            <div class="col-12">
                                <img src="img/logo.jpeg" alt="">
                            </div>
                        </div>
                        <div class="p-6">&nbsp;</div>
                        <div class="p-6">&nbsp;</div>

                        <div class="row p-2">
                            <div class="col-12 text-bold">
                                ACCOUNT LOGIN
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="input-group">
                                    <input type="text" placeholder="Email" class="form-control">
                                    <input type="password" placeholder="Password" class="form-control">
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-block btn-success mt-2">Login</button>
                        <div class="d-inline mt-2">Forgot <a href>Username/Password</a>
                        </div>

                    </div>


                </div>
            </div>
        </div>
</body>

</html>