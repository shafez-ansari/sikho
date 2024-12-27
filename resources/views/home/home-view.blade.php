@extends('home.home-master')

@section('content')

<div class="form-container" style="max-width: 500px; margin: auto; border: 1px solid #ccc; border-radius: 10px; padding: 20px; background-color: #fff; box-shadow: 0px 4px 6px rgba(0,0,0,0.1);">
    <div style="background-color: #333; padding: 10px 15px; border-radius: 8px 8px 0 0;">
        <h2 style="color: white; font-size: 16px; margin: 0;">Verify Yourself</h2>
    </div>
    <div style="padding: 15px;">
        <label style="width: 100%; color: #333; font-size: 14px; margin-bottom: 5px;">Enter your unique ID or Email</label>
        <div class="row">
            <div class="col-md-12 eml-bx" style="margin-bottom: 15px;">
                <div class="mbx" style="position: relative;">
                    <input type="email" name="loginEmail" class="form-control" id="loginEmail" placeholder="Email or UID" style="padding-right: 80px; border-radius: 4px; border: 1px solid #ddd;">
                    <a class="button vrf-btn" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background-color: #28a745; color: white; padding: 5px 10px; border-radius: 4px; text-decoration: none; cursor: pointer; font-size: 12px;" id="verifyBtn" onclick="verifyOtp()">Verify</a>
                </div>
                <span class="text-danger" id="emailError" style="font-size: 12px;"></span>
            </div>
            <div class="col-md-12" style="margin-bottom: 15px;">
                <input type="number" class="form-control" name="loginOtp" style="display: none; border-radius: 4px; border: 1px solid #ddd;" id="loginOtp" placeholder="OTP">
                <span class="text-danger" id="otpError" style="font-size: 12px;"></span>
            </div>
        </div>

        <a class="button" id="resendBtn" style="display: none; color: #28a745; font-size: 12px; cursor: pointer; margin-bottom: 15px;" onclick="resendOtp()">Resend</a>

        <button id="loginSubmit" style="display: none; width: 100%; background-color: #333; color: white; border: none; padding: 10px 0; border-radius: 4px; cursor: pointer; font-size: 14px;" onclick="submitOtp()">Submit</button>
    </div>
</div>

<script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>

<script type="text/javascript">
    function verifyOtp() {
        var email = $('#loginEmail').val();
        if (email == "") {
            $("#emailError").empty().html("Please enter email or unique ID");
        } else {
            $.ajax({
                type: 'get',
                url: "/verify-email",
                data: { 'email': email },
                success: function (data) {
                    if (data['loginMsg'] == "Invalid email or unique ID") {
                        $("#emailError").empty().html(data['loginMsg']);
                    } else {
                        $("#emailError").empty().html('');
                        $("#loginOtp").show();
                        $("#otpError").show();
                        $("#loginSubmit").show();
                        $("#resendBtn").show();
                        $("#loginEmail").attr("disabled", "disabled");
                    }
                }
            });
        }
    }

    function resendOtp() {
        var email = $('#loginEmail').val();
        $("#emailError").empty().html('');
        $.ajax({
            type: 'get',
            url: "/resend-otp",
            data: { 'email': email },
            success: function (data) {
                if (data['loginMsg'] == "OTP re-generated") {
                    console.log(data);
                }
            }
        });
    }

    function submitOtp() {
        var email = $('#loginEmail').val();
        var otp = $('#loginOtp').val();
        if (otp == "") {
            $("#otpError").empty().html("Please enter OTP");
        } else {
            $.ajax({
                type: 'get',
                url: "/submit-otp",
                data: { 'email': email, 'otp': otp },
                success: function (data) {
                    if (data['loginMsg'] == "Invalid OTP") {
                        $("#otpError").empty().html(data['loginMsg']);
                    } else {
                        window.location = data['loginMsg'];
                    }
                }
            });
        }
    }
</script>

@endsection
