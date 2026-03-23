<!DOCTYPE html>
<html>
<head>
    <title>CAPTCHA Example</title>
</head>
<body>

@if ($errors->any())
    <div style="color: red;">
        <strong>{{ $errors->first() }}</strong>
    </div>
@endif

@if (session('success'))
    <div style="color: green;">
        <strong>{{ session('success') }}</strong>
    </div>
@endif 

<form method="POST" action="/your-form-handler">
    @csrf
    <div>
        <img id="captcha" src="{{$captcha}}" alt="CAPTCHA" />
    </div>
    <div>
        <input type="text" name="captcha_input" required placeholder="Enter CAPTCHA">
    </div>
    <button type="submit">Submit</button>
</form>

<script>
    function loadCaptcha() {
        fetch('/captcha')
            .then(response => response.json())
            .then(data => {
                document.getElementById('captcha').src = data.captcha;
            });
    }

    window.onload = loadCaptcha;
</script>

</body>
</html>
