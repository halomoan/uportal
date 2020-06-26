<template>
  <div>
    <div class="content-header pb-1">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Import Invoice</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item">
                <router-link to="/home">Home</router-link>
              </li>

              <li class="breadcrumb-item">
                <router-link to="/import">Import</router-link>
              </li>

              <li class="breadcrumb-item active">Import Invoice</li>
            </ol>
          </div>
        </div>
      </div>
    </div>
    <!-- ./content-header -->
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-12 col-12">
            <div class="card card-primary">
              <!-- /.card-header -->
              <div class="card-body">
                <div class="row">
                  <div class="col-6">
                    <h4 class="m-0">{{ name }} ({{item.CoCode}})</h4>

                    <h5 class="m-0">{{item.Month | MMM }} - {{ item.Year }}</h5>
                  </div>
                  <div class="col-6">
                    <div class="d-flex justify-content-end">
                      <button class="btn bg-gradient-secondary btn-sm mr-2" @click="goBack">
                        <i class="fas fa-chevron-left"></i>
                        Back
                      </button>
                      <button class="btn bg-gradient-info btn-sm mr-2" @click="doFilter">
                        <i class="fas fa-filter"></i>
                        Filter
                      </button>
                      <button class="btn bg-gradient-green btn-sm mr-2" @click="doPublish">
                        <i class="fas fa-sign-out-alt"></i>
                        Publish
                      </button>
                      <button class="btn bg-gradient-warning btn-sm mr-2" @click="showLog">
                        <i class="fas fa-list"></i>
                        Log
                      </button>
                      <button class="btn bg-gradient-primary btn-sm" @click="doImport">
                        <i class="fas fa-file-import"></i>
                        Import
                      </button>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-12">
                    <div class="d-flex justify-content-end text-right">
                      <i
                        class="fas fa-filter mr-3 pb-2 text-orange"
                        v-show="filter.show"
                        @click="doFilter"
                      ></i>
                      <pagination
                        :records="pgTable.records"
                        @paginate="showInvoice"
                        v-model="pgTable.page"
                        :per-page="pgTable.perpage"
                      ></pagination>
                    </div>
                    <table class="table table-hover text-nowrap">
                      <thead>
                        <tr>
                          <th>Invoice Date</th>
                          <th>Invoice No</th>
                          <th>Customer</th>
                          <th>Email</th>
                          <th>Description</th>
                          <th class="text-right">Amount</th>
                          <th class="text-center">PDF</th>
                          <th class="text-center">Read</th>
                          <th class="text-center">Published</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="invoice in pgTable.invoices" :key="invoice.id">
                          <td>{{invoice.invdate | humanDate}}</td>
                          <td>{{invoice.invno}}</td>
                          <td>{{invoice.name}}</td>
                          <td>{{invoice.email}}</td>
                          <td>{{invoice.desc}}</td>
                          <td class="text-right">{{invoice.amount | currency}}</td>
                          <td class="text-center">
                            <router-link :to="{ name: 'viewPDF', params: { id:  invoice.id }}">
                              <i class="far fa-file-pdf text-red"></i>
                            </router-link>
                          </td>
                          <td class="text-center">
                            <i
                              :title="invoice.unread ? 'Unread Yet' : 'Read'"
                              class="fas fa-book-reader"
                              v-bind:class="{ 'text-blue': invoice.unread }"
                              @click="toggleItemRead(invoice.id,invoice.unread)"
                            ></i>
                          </td>
                          <td class="text-center">
                            <i
                              :title="invoice.published ? 'Published' : 'Not Published Yet'"
                              class="fas fa-sign-out-alt"
                              v-bind:class="{ 'text-green': invoice.published}"
                              @click="toggleItemPublish(invoice.id,invoice.published)"
                            ></i>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
              <!-- /.card-body -->
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- ./content -->

    <!-- Log Modal -->
    <div class="modal" tabindex="-1" role="dialog" id="logModal">
      <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Log Viewer</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <div class="modal-body">
            <div class="overlay-wrapper text-center">
              <overlay-scrollbars
                style="max-height: 350px"
                :options="{ scrollbars: { autoHide: 'scroll' } }"
              >
                <table class="table text-nowrap table-hover">
                  <thead class="thead-dark">
                    <tr>
                      <th>No</th>
                      <th>Date</th>
                      <th>Text</th>
                    </tr>
                  </thead>

                  <tbody>
                    <tr v-for="(log,index) in logs" :key="index">
                      <td>{{index + 1}}</td>
                      <td>{{log.created_at | humanDate}}</td>
                      <td>{{log.text}}</td>
                    </tr>
                  </tbody>
                </table>
                <div class="w-100 text-center" v-if="logs.length === 0">- Empty -</div>
              </overlay-scrollbars>
              <div class v-if="inprogress.log">
                <i class="fas fa-3x fa-sync-alt fa-spin"></i>
                <div class="text-bold pl-2">Loading...</div>
              </div>
            </div>
            <!-- ./overlay-wrapper -->
          </div>
          <!-- ./modal-body -->
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>
    <!-- Filter Modal -->
    <div class="modal" tabindex="-1" role="dialog" id="filterModal">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Advanced Filter</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <div class="modal-body">
            <form>
              <div class="form-group row">
                <label for="invdate" class="col-sm-3 col-form-label">Inv. Date</label>

                <div class="col-sm-9">
                  <input
                    type="date"
                    class="form-control"
                    v-model="filter.invdate"
                    name="invdate"
                    placeholder="Select A Date"
                  />
                </div>
              </div>
              <div class="form-group row">
                <label for="invno" class="col-sm-3 col-form-label">Inv. No</label>

                <div class="col-sm-9">
                  <input
                    type="text"
                    class="form-control"
                    v-model="filter.invno"
                    name="invno"
                    placeholder="Invoice No"
                  />
                </div>
              </div>
              <div class="form-group row">
                <label for="custname" class="col-sm-3 col-form-label">Cust. Name</label>

                <div class="col-sm-9">
                  <input
                    type="text"
                    class="form-control"
                    v-model="filter.custname"
                    name="custname"
                    placeholder="Customer Name"
                  />
                </div>
              </div>
              <div class="form-group row">
                <label for="email" class="col-sm-3 col-form-label">Email</label>

                <div class="col-sm-9">
                  <input
                    type="email"
                    class="form-control"
                    v-model="filter.email"
                    name="email"
                    placeholder="Customer Email"
                  />
                </div>
              </div>

              <fieldset class="form-group">
                <div class="row">
                  <label class="col-form-label col-sm-3 pt-0">Published</label>
                  <div class="col-sm-9">
                    <div class="form-check form-check-inline">
                      <input
                        class="form-check-input"
                        type="radio"
                        name="published1"
                        value="1"
                        v-model="filter.published"
                        checked
                      />
                      <label class="form-check-label" for="published1">Yes</label>
                    </div>
                    <div class="form-check form-check-inline">
                      <input
                        class="form-check-input"
                        type="radio"
                        name="published2"
                        v-model="filter.published"
                        value="0"
                      />
                      <label class="form-check-label" for="published2">No</label>
                    </div>
                  </div>
                </div>
              </fieldset>
              <fieldset class="form-group">
                <div class="row">
                  <label class="col-form-label col-sm-3 pt-0">Unread</label>
                  <div class="col-sm-9">
                    <div class="form-check form-check-inline">
                      <input
                        class="form-check-input"
                        type="radio"
                        name="unread1"
                        value="1"
                        v-model="filter.unread"
                        checked
                      />
                      <label class="form-check-label" for="unread1">Yes</label>
                    </div>
                    <div class="form-check form-check-inline">
                      <input
                        class="form-check-input"
                        type="radio"
                        name="unread2"
                        v-model="filter.unread"
                        value="0"
                      />
                      <label class="form-check-label" for="unread2">No</label>
                    </div>
                  </div>
                </div>
              </fieldset>
            </form>
          </div>
          <!-- ./modal-body -->
          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-primary"
              data-dismiss="modal"
              @click="showInvoice(1)"
            >Submit</button>
            <button
              type="button"
              class="btn btn-secondary"
              data-dismiss="modal"
              @click="clearFilter"
            >Clear Filter</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      inprogress: {
        log: false,
        invoice: false
      },
      filter: {
        show: null,
        invdate: null,
        invno: null,
        custname: null,
        email: null,
        published: null,
        unread: null
      },
      pgTable: {
        invoices: [],
        uri: "api/impinvoice?invoiceh=",
        page: 1,
        perpage: 10,
        records: 0,
        options: {
          chunksNavigation: scroll,
          texts: {
            count: "|||"
          }
        }
      },
      item: {},
      name: "",
      logs: []
    };
  },
  methods: {
    goBack() {
      this.$router.push({ path: "/import" });
    },
    showLog() {
      $("#logModal").modal("toggle");
      this.inprogress.log = true;
      this.logs = [];
      axios.get("api/impinvoice?log=" + this.item.id).then(resp => {
        this.logs = resp.data;
        this.inprogress.log = false;
      });
    },
    doFilter() {
      $("#filterModal").modal("toggle");
      this.filter.show = true;
    },
    showInvoice(page) {
      this.inprogress.invoice = true;
      this.pgTable.invoices = [];

      let filter = this.filter.invdate
        ? "&qinvdate=" + this.filter.invdate
        : "";
      filter += this.filter.invno ? "&qinvno=" + this.filter.invno : "";
      filter += this.filter.custname ? "&qname=" + this.filter.custname : "";
      filter += this.filter.email ? "&qemail=" + this.filter.email : "";
      filter +=
        this.filter.published != null
          ? "&qpublished=" + this.filter.published
          : "";
      filter +=
        this.filter.unread != null ? "&qunread=" + this.filter.unread : "";

      axios
        .get(this.pgTable.uri + this.item.id + filter + "&page=" + page)
        .then(resp => {
          const invoiceData = resp.data;

          this.pgTable.invoices = invoiceData;

          this.pgTable.invoices = invoiceData.data;
          this.pgTable.records = invoiceData.total;
          this.pgTable.page = invoiceData.current_page;
          this.pgTable.perpage = invoiceData.per_page;

          this.inprogress.invoice = false;
        });
    },
    toggleItemPublish(id, published) {
      const text = published
        ? "Change To Hide From Customer"
        : "Let Customer Able To See";

      Swal.fire({
        title: "Toggle Invoice Publish Status",
        text: text,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, Change It!"
      }).then(result => {
        //Send request to the server
        if (result.value) {
          axios
            .put("api/invoice/" + id, {
              published: !published
            })
            .then(result => {
              if (result.status === 200) {
                let result = _.find(this.pgTable.invoices, function(invoice) {
                  if (invoice.id === id) {
                    return true;
                  }
                });

                if (result) {
                  result.published = !published;
                }
                Swal.fire("Publish Status Updated!", "Update", "success");
              }
            })
            .catch(error => {
              let message = error.response.data.message;
              if (message) {
                Swal.fire("Failed!", message, "warning");
              } else {
                Swal.fire("Failed!", "There is something wrong.", "warning");
              }
            });
        }
      });
    },
    toggleItemRead(id, unread) {
      const text = unread ? "Unread To Read ?" : "Read To Unread ?";

      Swal.fire({
        title: "Toggle Invoice Read Status",
        text: text,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, Change It!"
      }).then(result => {
        //Send request to the server
        if (result.value) {
          axios
            .put("api/invoice/" + id, {
              unread: !unread
            })
            .then(result => {
              if (result.status === 200) {
                let result = _.find(this.pgTable.invoices, function(invoice) {
                  if (invoice.id === id) {
                    return true;
                  }
                });

                if (result) {
                  result.unread = !unread;
                }

                Swal.fire("Read Status Updated!", "Update", "success");
              }
            })
            .catch(error => {
              let message = error.response.data.message;
              if (message) {
                Swal.fire("Failed!", message, "warning");
              } else {
                Swal.fire("Failed!", "There is something wrong.", "warning");
              }
            });
        }
      });
    },
    clearFilter() {
      for (var key in this.filter) {
        this.filter[key] = null;
      }
      this.showInvoice(1);
    },

    doPublish() {
      Swal.fire({
        title: "Publish Invoices",
        text: "Are You Going To Publish The Invoices?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, publish them!"
      }).then(result => {
        //Send request to the server
        if (result.value) {
          axios
            .put("api/impinvoice/" + this.item.id, {
              publish: true
            })
            .then(result => {
              if (result.status === 200) {
                Swal.fire(
                  "Published!",
                  "The Invoices Has Been Published.",
                  "success"
                );
              }
            })
            .catch(error => {
              let message = error.response.data.message;
              if (message) {
                Swal.fire("Failed!", message, "warning");
              } else {
                Swal.fire("Failed!", "There is something wrong.", "warning");
              }
            });
        }
      });
    },
    doImport() {
      const month = this.item.Month;
      const year = this.item.Year;
      const cocode = this.item.CoCode;

      this.inprogress.invoice = true;
      this.$Progress.start();
      axios
        .put("api/impinvoice/" + month + "," + year + "," + cocode)
        .then(resp => {
          this.inprogress.invoice = false;
          this.$Progress.finish();
          this.showInvoice(1);
        })
        .catch(err => {
          this.inprogress.invoice = false;
          let message = false;
          if (err && err.response && err.response.data) {
            message = err.response.data.message;
            const errors = err.response.data.errors;

            for (const error in errors) {
              for (const msg in errors[error]) {
                message = errors[error][msg];
              }
            }
          }
          this.$Progress.fail();

          if (message) {
            Swal.fire("Failed!", message, "warning");
          } else {
            Swal.fire("Failed!", "There is something wrong.", "warning");
          }
        });
    }
  },
  beforeMount() {
    if (localStorage.company) {
      this.name = localStorage.company;
    }

    if (localStorage.getItem("invoiceh")) {
      try {
        this.item = JSON.parse(localStorage.getItem("invoiceh"));
      } catch (e) {
        localStorage.removeItem("invoiceh");
      }
    }
  },
  mounted() {
    this.showInvoice(1);
  }
};
</script>
