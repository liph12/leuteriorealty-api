<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LR | Email Verification</title>
</head>
<style>
h1,
h2,
h3,
h4,
h5 {
    font-family: 'Abyssinica SIL', serif;
}
</style>

<body class="pt-5">
    <h2 class="text-success">
        Dear {{ $name }},
    </h2>
    <p class="text-xs">Here your email verification code.</p>
    <p class="text-xs">Please submit this code to continue with the process.</p>
    <div class="text-center">
        <h1>{{ $verification }}</h1>
    </div>
    <p class="text-xs">Regards, LR Support Team</p>
</body>

</html>