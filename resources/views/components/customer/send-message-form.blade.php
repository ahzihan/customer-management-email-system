<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12 col-lg-12 center-screen">
            <div class="card animated fadeIn w-90  p-4">
                <div class="card-body">
                    <h4>Offer Message</h4>
                    <br/>
                    <label>Customer Email Address</label>
                    <select class="form-control" id="email" name="email">
                        <option value="">Select Email</option>
                    </select>
                    <br/>
                    <label>Message</label>
                    <textarea class="form-control" name="messageId" id="messageId" cols="30" rows="3"></textarea>
                    <br/>
                    <button onclick="VerifyEmail()"  class="btn w-100 float-end btn-primary">Send</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    getCustomer();
    async function getCustomer(){
        let res = await axios.get("/list-customer")
        res.data.forEach(function (item,i) {
            let option=`<option value="${item['email']}">${item['email']}</option>`;
            $("#email").append(option);
        });
    }
   async function VerifyEmail() {
        let email = $('#email').val().trim();
        let messageText = $('#messageId').val().trim();

        let mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;

        if(email.length===0){
            errorToast("Email is required");
        }
        else if(!email.match(mailformat)){
            errorToast("Invalid Email Address");
        }
        else{
            showLoader();
            let res = await axios.post('/send-message', {email: email,messageText:messageText});
            hideLoader();

            if(res.status===200 && res.data['status']==='success'){
                successToast(res.data['message']);
            }
            else{
                errorToast(res.data['message']);
            }
        }

    }
</script>
