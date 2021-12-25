@extends('layouts.app_plain')
@section('title','CheckIn CheckOut')
@section('content')
<div class="d-flex justify-content-center align-items-center" style="height: 100vh;">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="text-center my-5">
                    <h4 class="text-center mb-3">QR Code</h4>
                    <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(200)->generate($hash_value)) !!} ">
                    <p class="text-muted">Scan QR code to checkin or checkout!</p>
                </div>
                <hr>
                
                <div class="text-center my-5">
                    <h5 class="text-center mb-3">Pin Code</h5>
                    <input type="text" name="mycode" id="pincode-input1" autofocus>
                    <p class="text-muted mt-4">Enter Pin Code to checkin or checkout!</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('extra_js')
    <script>
        $(document).ready(function(){
            $('#pincode-input1').pincodeInput({inputs:6,complete:function(value, e, errorElement)
            {
                $.ajax({
                    url:'/checkin-checkout/store',
                    method:'POST',
                    data:
                    {
                        "pin_code" : value,
                    },
                    success: function (res) {
                        if(res.status == 'success'){
                            Toast.fire({
                                icon: 'success',
                                title: res.message,
                            });
                        }else{
                            Toast.fire({
                                icon: 'error',
                                title: res.message,
                            });
                        }
                    $('.pincode-input-container .pincode-input-text').val('');
                    $('.pincode-input-text').first().select().focus();
                    }
                });
            }});
            $('.pincode-input-text').first().select().focus();
        });
    </script>
@endsection