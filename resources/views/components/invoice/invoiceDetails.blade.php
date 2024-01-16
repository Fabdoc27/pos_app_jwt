<!-- Modal -->
<div class="modal animated zoomIn" id="details-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Invoice</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div id="invoice" class="modal-body p-3">
                <div class="container-fluid">
                    <br />
                    <div class="row">
                        <div class="col-8">
                            <span class="text-bold text-dark">BILLED TO </span>
                            <p class="text-xs mx-0 my-1">Name: <span id="cust_name"></span> </p>
                            <p class="text-xs mx-0 my-1">Email: <span id="cust_email"></span></p>
                            <p class="text-xs mx-0 my-1">User ID: <span id="cust_id"></span> </p>
                        </div>
                        <div class="col-4">
                            <img class="w-40" src="{{ 'images/logo.png' }}">
                            <p class="text-bold mx-0 my-1 text-dark">Invoice </p>
                            <p class="text-xs mx-0 my-1">Date: {{ date('Y-m-d') }} </p>
                        </div>
                    </div>
                    <hr class="mx-0 my-2 p-0 bg-secondary" />
                    <div class="row">
                        <div class="col-12">
                            <table class="table w-100" id="invoiceTable">
                                <thead class="w-100">
                                    <tr class="text-xs text-bold">
                                        <td>Name</td>
                                        <td>Qty</td>
                                        <td>Total</td>
                                    </tr>
                                </thead>
                                <tbody class="w-100" id="invoiceList">

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <hr class="mx-0 my-2 p-0 bg-secondary" />

                    <div class="row">
                        <div class="col-12">
                            <p class="text-bold text-xs my-1 text-dark"> TOTAL: <i class="bi bi-currency-dollar"></i>
                                <span id="total"></span>
                            </p>
                            <p class="text-bold text-xs my-2 text-dark"> PAYABLE: <i class="bi bi-currency-dollar"></i>
                                <span id="payable"></span>
                            </p>
                            <p class="text-bold text-xs my-1 text-dark"> VAT(5%): <i class="bi bi-currency-dollar"></i>
                                <span id="vat"></span>
                            </p>
                            <p class="text-bold text-xs my-1 text-dark"> Discount: <i class="bi bi-currency-dollar"></i>
                                <span id="discount"></span>
                            </p>
                        </div>
                    </div>

                </div>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn bg-gradient-primary" data-bs-dismiss="modal">Close</button>
                <button onclick="printInvoice()" class="btn bg-gradient-success">Print</button>
            </div>
        </div>
    </div>
</div>


<script>
    async function InvoiceDetails(inv_id, cust_id) {
        showLoader();
        let res = await axios.post("/invoice-details", {
            inv_id: inv_id
            cust_id: cust_id,
        })
        hideLoader();

        document.getElementById('cust_id').innerText = res.data['customer']['user_id']
        document.getElementById('cust_name').innerText = res.data['customer']['name']
        document.getElementById('cust_email').innerText = res.data['customer']['email']
        document.getElementById('total').innerText = res.data['invoice']['total']
        document.getElementById('payable').innerText = res.data['invoice']['payable']
        document.getElementById('vat').innerText = res.data['invoice']['vat']
        document.getElementById('discount').innerText = res.data['invoice']['discount']

        let invoiceList = $('#invoiceList');
        invoiceList.empty();

        res.data['product'].forEach(function(item, index) {
            let row = `<tr class="text-sm">
                        <td>${item['name']}</td>
                        <td>${item['quantity']}</td>
                        <td>${item['sale_price']}</td>
                    </tr>`
            invoiceList.append(row)
        });
    }

    function printInvoice() {
        let printContents = document.getElementById('invoice').innerHTML;
        let originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();

        document.body.innerHTML = originalContents;
        setTimeout(function() {
            location.reload();
        }, 1000);
    }
</script>