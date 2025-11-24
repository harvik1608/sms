<!DOCTYPE html>
<html>
	<head>
	    <meta charset="UTF-8">
	    <link rel="preconnect" href="https://fonts.googleapis.com">
	    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	    <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&display=swap" rel="stylesheet">
	    <style>
	    	body {
	    		font-family: Nunito, serif !important;
	    		font-optical-sizing: auto;
		        font-weight: 400;
		        font-style: normal;
	    	}
	    </style>
	    <title></title>
	</head>
	<body style="font-family: Arial, sans-serif; background-color: #f7f7f7; padding: 20px;">
	    <table style="max-width: 600px; margin: auto; background-color: #ffffff; border-radius: 8px; padding: 20px;">
	        <tr>
	            <td>Hi {{ $name }},</td>
	        </tr>
	        <tr>
	            <td>We received a request to reset the password for your account.<br>Click the link below to choose a new password:</td>
	        </tr>
	        <tr>
	            <td><p>Reset Password <a href="{{ $link }}">Click Here</a></p></td>
	        </tr>
	        <tr>
	            <td>If you didn’t request this change, you can safely ignore this email.</td>
	        </tr>
	        <tr>
	            <td><p>Thanks<br>{{ config('constant.app_name') }} Team.</p></td>
	        </tr>
	    </table>
	</body>
</html>