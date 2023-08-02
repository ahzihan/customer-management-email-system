<div class="modal" id="update-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <form id="updateForm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Update Customer</h5>
                </div>
                <div class="modal-body">
                    <div class="container">
                        <div class="row">
                            <div class="col-12 p-1">
                                <label class="form-label">Customer Name *</label>
                                <input type="text" class="form-control" id="customerNameUpdate">
                                <label class="form-label">Customer Email *</label>
                                <input type="text" class="form-control" id="customerEmailUpdate">
                                <label class="form-label">Customer Mobile *</label>
                                <input type="text" class="form-control" id="customerMobileUpdate">
                                <label class="form-label">Customer Address</label>
                                <textarea class="form-control" name="" id="customerAddress" cols="30" rows="3"></textarea>

                                <input type="text" class="d-none" id="updateID">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="modal-close" class="btn btn-sm btn-danger" data-bs-dismiss="modal" aria-label="Close">Close</button>
                    <button class="btn btn-sm  btn-success" >Update</button>
                </div>
            </div>
        </form>
    </div>
</div>


<script>

    async function FillUpUpdateForm(id){
        $('#updateID').val(id);
        showLoader();
        let res=await axios.post("/edit-customer",{id:id})
        hideLoader();
        console.log(res.data['address']);
        $('#customerNameUpdate').val(res.data['cus_name']);
        $('#customerEmailUpdate').val(res.data['email']);
        $('#customerMobileUpdate').val(res.data['mobile']);
        $('#customerAddress').val(res.data['address']);
    }

    $("#updateForm").on('submit',async function (e) {
        e.preventDefault();

        let customerName = $('#customerNameUpdate').val();
        let customerEmail = $('#customerEmailUpdate').val();
        let customerMobile = $('#customerMobileUpdate').val();
        let customerAddress = $('#customerAddress').val();
        let updateID = $('#updateID').val();

        if (customerName.length === 0) {
            errorToast("Customer Name Required !");
        }
        else if(customerEmail.length===0){
            errorToast("Customer Email Required !");
        }
        else if(customerMobile.length===0){
            errorToast("Customer Mobile Required !");
        }
        else {

            $('#update-modal').modal('hide');
            showLoader();
            let res = await axios.post("/update-customer",{cus_name:customerName,email:customerEmail,mobile:customerMobile,address:customerAddress,id:updateID})
            hideLoader();

            if(res.status===200 && res.data===1){
                successToast('Customer Updated Successfully!');
                $("#updateForm").trigger("reset");
                await getList();
            }
            else{
                errorToast("Request fail !");
            }
        }

    });

</script>
