@extends('home.home-master')

@section('content')

<div class="form-container">
    <h2>Verify Yourself</h2>
    <!-- <form method="POST" action="{{ url('submit-otp')}}" id="formId">
    @csrf -->
        <label style="width: 100%; color : #000; text-align: left;">Enter your unique ID or Email</label>
        <div class="row">
            <div class="col-md-6">
                <input type="email" name="loginEmail" class="form-control" id="loginEmail" placeholder="Email">
                <span class="text-danger" id="emailError"></span>
            </div>
            <div class="col-md-6">
                <input type="number" class="form-control" name="loginOtp" style="display:none" id="loginOtp" placeholder="OTP" >
                <span class="text-danger" id="otpError"></span>
            </div>            
        </div>
        <a class="button" style="display:none" id="resendBtn" style="color:red" onclick="resendOtp()">Resend</a>
        <a class="button"  id="verifyBtn" style="color:red" onclick="verifyOtp()">Verify</a>
        
        <!-- Moved the "Verify" button below the email input field -->
        <button id="loginSubmit" style="display:none" class="verify-btn" onclick="submitOtp()">Submit</button>
        
    <!-- </form> -->
</div>
<script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>

<script type="text/javascript">
    
    function verifyOtp() { 
                    
        var email = $('#loginEmail').val();
        if(email == ""){
            $("#emailError").empty().html("Please enter email or unique Id");
        }
        else {
            $.ajax({
                    type:'get',
                    url: "/verify-email",
                    data: {'email' : email},
                    success:function(data){
                        
                        if(data['loginMsg'] == "Invalid email or unique Id"){
                            $("#emailError").empty().html(data['loginMsg']);      
                        }
                        else {
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
                type:'get',
                url: "/resend-otp",
                data: {'email' : email},
                success:function(data){
                    if(data['loginMsg'] == "OTP re-generated"){
                        console.log(data);                          
                    }
                }
            });
    }

    function submitOtp() {
        var email = $('#loginEmail').val();
        var otp = $('#loginOtp').val();
        if(otp == ""){
            $("#otpError").empty().html("Please enter OTP");
        }
        else {
            $.ajax({
                type:'get',
                url: "/submit-otp",
                data: {'email' : email, 'otp' : otp},
                success:function(data){
                    
                    if(data['loginMsg'] == "Invalid OTP"){
                        $("#otpError").empty().html(data['loginMsg']);                          
                    }
                    else {
                        window.location = data['loginMsg'];
                    }
                }
            });
        }
        
       
    }
</script>

@endsection