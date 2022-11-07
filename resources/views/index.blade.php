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
    <title>index</title>
</head>

<body>
    <div class="container">
        <div class="col d-flex justify-content-center m-5">
            <span class="h3">hello, <a href="/user/{{ $userID }}">{{ $name }}</a>.</span>
        </div>
        <div class="col d-flex justify-content-end">
            <button type="button" class="btn btn-primary" id="btn-logout">log out</button>
        </div>
    </div>
</body>
<script>
    $(function() {
        $("#btn-logout").on("click", function(){
            const logout = new Promise((res, rej) => {
                $.ajax({
                    type: "POST",
                    url: "/LogOut",
                    dataType: "json",
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
                    const result = await logout;
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
