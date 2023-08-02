<div class="modal" id="create-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <form id="insertData">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Create Customer</h5>
                </div>
                <div class="modal-body">
                    <div class="container">
                        <div class="row">
                            <div class="col-12 p-1">
                                <label class="form-label">Customer Name *</label>
                                <input type="text" class="form-control" id="customerName">
                                <label class="form-label">Customer Email *</label>
                                <input type="text" class="form-control" id="customerEmail">
                                <label class="form-label">Customer Mobile *</label>
                                <input type="text" class="form-control" id="customerMobile">
                                <label class="form-label">Address</label>
                                <textarea class="form-control" name="" id="customerAddress" cols="30" rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="modal-close" class="btn btn-sm btn-danger" data-bs-dismiss="modal" aria-label="Close">Close</button>
                    <button class="btn btn-sm  btn-success" >Save</button>
                </div>
            </div>
        </form>
    </div>
</div>


<script>

    $("#insertData").on('submit',async function (e) {
        e.preventDefault();

        let customerName = $('#customerName').val();
        let customerEmail = $('#customerEmail').val();
        let customerMobile = $('#customerMobile').val();
        let customerAddress = $('#customerAddress').val();
        if (customerName.length === 0) {
            errorToast("Customer Name Required !")
        }
        else if(customerEmail.length===0){
            errorToast("Customer Email Required !")
        }
        else if(customerMobile.length===0){
            errorToast("Customer Mobile Required !")
        } else {
            $('#create-modal').modal('hide');
            showLoader();
            let res = await axios.post("/create-customer",{cus_name:customerName,email:customerEmail,mobile:customerMobile,address:customerAddress})
            hideLoader();
            if(res.status===201){
                successToast('Customer Created Successfully!');
                $("#insertData").trigger("reset");
                await getList();
            }
            else{
                errorToast("Request fail !")
            }

        }

    });


</script>
