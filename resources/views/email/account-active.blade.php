<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Welcome</title>

    <!-- Hope Ui Design System Css -->
    <link rel="stylesheet" href="{{asset('assets/css/hope-ui.min.css?v=1.2.0')}}" />

    <!-- Custom Css -->
    <link rel="stylesheet" href="{{asset('assets/css/custom.min.css?v=1.2.0')}}" />
</head>
<body>
    <div class="container-fluid">
        <div class="row d-flex justify-content-center align-items-center">
            <div class="card my-3">
                <img src="{{ asset('assets/images/dashboard/top-image.jpg') }}" class="card-img-top">
                <div class="card-body">
                    <h5 class="card-title text-center">Your account has been actived</h5>
                    <p class="card-text text-center">Here's your username and password for login to Helpdesk Ticketing System.</p>
                    <p class="card-text text-center">Please kindly change your password frequently.</p>
                    <table align="center">
                        <tr>
                            <th>Email</th>
                            <td>:</td>
                            <td>{{ $data['email'] }}</td>
                        </tr>
                        <tr>
                            <th>Username</th>
                            <td>:</td>
                            <td>{{ $data['username'] }}</td>
                        </tr>
                        <tr>
                            <th>Password</th>
                            <td>:</td>
                            <td>{{ $data['password'] }}</td>
                        </tr>
                        <tr>
                            <th>Role</th>
                            <td>:</td>
                            <td>{{ $data['role'] }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- App Script -->
    <script src="{{asset('assets/js/hope-ui.js')}}" defer></script>
</body>
</html>