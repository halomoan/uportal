<template>
  <div>
    <section class="content-header pb-1">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Invoice</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item">
                <a href="#">Home</a>
              </li>
              <li class="breadcrumb-item">
                <a href="/invoices">Invoices</a>
              </li>
              <li class="breadcrumb-item active">Detail</li>
            </ol>
          </div>
        </div>
      </div>
      <!-- /.container-fluid -->
    </section>
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <!-- Main content -->
            <div class="invoice p-3 mb-3">
              <!-- title row -->
              <div class="row">
                <div class="col-12">
                  <h4>
                    <img src="img/uol.png" /> UOL Group Limited.
                    <small class="float-right">Date: 2/10/2020</small>
                  </h4>
                </div>
                <!-- /.col -->
              </div>
              <!-- info row -->
              <div class="row invoice-info">
                <div class="col-sm-4 invoice-col">
                  From
                  <address>
                    <strong>UOL Group Limited</strong>
                    <br />101 Thompson Road, #33-00 United Square
                    <br />Singapore, 307591
                    <br />Phone: (804) 123-5432
                    <br />Email: uol.finance@example.com
                  </address>
                </div>
                <!-- /.col -->
                <div class="col-sm-4 invoice-col">
                  To
                  <address>
                    <strong>Smiggle Online</strong>
                    <br />457 St Kilda Road
                    <br />Melbourne, VIC, Australia 3004
                    <br />Phone: (555) 539-1037
                    <br />Email: smiggle@example.com
                  </address>
                </div>
                <!-- /.col -->
                <div class="col-sm-4 invoice-col">
                  <b>Invoice #007612</b>
                  <br />
                  <br />
                  <b>Order ID:</b> 4F3S8J
                  <br />
                  <b>Payment Due:</b> 2/22/2014
                  <br />
                  <b>Account:</b> 968-34567
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->

              <!-- Table row -->
              <div class="row">
                <div class="col-12 table-responsive">
                  <table class="table table-striped">
                    <thead>
                      <tr>
                        <th>Qty</th>
                        <th>Type</th>
                        <th>Serial #</th>
                        <th>Description</th>
                        <th>Subtotal</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>1</td>
                        <td>Office Rental</td>
                        <td>455-981-221</td>
                        <td>El snort testosterone trophy driving gloves handsome</td>
                        <td>$64.50</td>
                      </tr>
                      <tr>
                        <td>1</td>
                        <td>Electric Bill</td>
                        <td>247-925-726</td>
                        <td>Wes Anderson umami biodiesel</td>
                        <td>$50.00</td>
                      </tr>
                      <tr>
                        <td>1</td>
                        <td>Water Bill</td>
                        <td>735-845-642</td>
                        <td>Terry Richardson helvetica tousled street art master</td>
                        <td>$10.70</td>
                      </tr>
                      <tr>
                        <td>1</td>
                        <td>Maintenance Fee</td>
                        <td>422-568-642</td>
                        <td>Tousled lomo letterpress</td>
                        <td>$25.99</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->

              <div class="row">
                <!-- accepted payments column -->
                <div class="col-6">
                  <p class="lead">Payment Methods:</p>
                  <h4>Bank Transfer</h4>
                  <p
                    class="text-muted well well-sm shadow-none"
                    style="margin-top: 10px;"
                  >Lorem ipsum dolor sit amet consectetur adipisicing elit. Sapiente quod corrupti nesciunt qui labore sed neque. Est at sapiente facere fuga, corrupti dignissimos ex, modi voluptas officia minima eum officiis?</p>
                </div>
                <!-- /.col -->
                <div class="col-6">
                  <p class="lead">Amount Due 2/22/2014</p>

                  <div class="table-responsive">
                    <table class="table">
                      <tbody>
                        <tr>
                          <th style="width:50%">Subtotal:</th>
                          <td>$250.30</td>
                        </tr>
                        <tr>
                          <th>Tax (9.3%)</th>
                          <td>$10.34</td>
                        </tr>
                        <tr>
                          <th>Shipping:</th>
                          <td>$5.80</td>
                        </tr>
                        <tr>
                          <th>Total:</th>
                          <td>$265.24</td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->

              <!-- this row will not appear when printing -->
              <div class="row no-print">
                <div class="col-12">
                  <a href="/printInvoice?inv_no=123" target="_blank" class="btn btn-success">
                    <i class="fas fa-print"></i> Print
                  </a>

                  <button type="button" class="btn btn-primary" style="margin-right: 5px;">
                    <i class="fas fa-download"></i> Generate PDF
                  </button>
                  <button
                    type="button"
                    class="btn btn-default float-right"
                    @click.prevent="$router.go(-1)"
                  >
                    <i class="fas fa-close"></i> Close
                  </button>
                </div>
              </div>
            </div>
            <!-- /.invoice -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </section>
  </div>
</template>

<script>
export default {
  methods: {}
};
</script>
