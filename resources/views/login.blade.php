<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://code.jquery.com/jquery-3.6.1.min.js"
        integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>login</title>
</head>

<body>
    <div class="container my-5">
        <form id="form-login">
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}">
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" value="{{ old('password') }}">
            </div>
            <input class="d-none" type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="col d-flex justify-content-end">
                <div class="col text-danger" id="error-msg"></div>
                <button id="btn-login" type="button" class="btn btn-primary mx-1">log in</button>
            </div>
        </form>
    </div>
</body>
<script>
    $(function() {
        $("#btn-login").on("click", function() {
            if(!$("#email").val() || !$("#password").val()){
                return $("#error-msg").text('please input email/password.');
            }
            const form = $("#form-login")[0];
            const formdata = new FormData(form);
            const login = new Promise((res, rej) => {
                $.ajax({
                    type: "POST",
                    url: "/LogIn",
                    dataType: "json",
                    contentType: false, //required
                    processData: false, // required
                    data: formdata,
                    success: function(response) {
                        res(response);
                    },
                    error: function(error) {
                        rej(error);
                    }
                });
            });
            (async () => {
                try {
                    const result = await login;
                    if (!result.status) {
                        $("#error-msg").text(result.msg);
                        return;
                    }
                    window.location.href = window.location.href;
                } catch (e) {

                }
            })();
        })
    });
</script>

</html>
