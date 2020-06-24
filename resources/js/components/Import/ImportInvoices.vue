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
                      <button class="btn bg-gradient-green btn-sm mr-2" @click="goBack">
                        <i class="fas fa-chevron-left"></i>
                        Back
                      </button>
                      <button class="btn bg-gradient-primary btn-sm" @click="doImport">
                        <i class="fas fa-file-import"></i>
                        Execute
                      </button>
                    </div>
                  </div>
                </div>
                <div class="row pt-2">
                  <div class="col-12">
                    <!-- <div class="progress w-100">
                      <div
                        class="progress-bar bg-blue-light"
                        role="progressbar"
                        style="width: 95%;"
                        aria-valuenow="95"
                        aria-valuemin="0"
                        aria-valuemax="100"
                      >95%</div>
                    </div>-->
                  </div>
                </div>
                <div class="row">
                  <div class="col-12">
                    <table class="table table-hover text-nowrap">
                      <thead>
                        <tr>
                          <th>Date</th>
                          <th>Invoice No</th>
                          <th>Customer</th>
                          <th>Description</th>
                          <th class="text-right">Amount</th>
                          <th class="text-right">Status</th>
                        </tr>
                      </thead>
                      <tbody></tbody>
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
  </div>
</template>

<script>
export default {
  props: { text: String, payload: Object },
  data() {
    return {
      inprogress: false,
      item: {},
      name: ""
    };
  },
  methods: {
    goBack() {
      this.$router.push({ path: "/import" });
    },
    doImport() {
      console.log(this.item);
      const month = this.item.Month;
      const year = this.item.Year;
      const cocode = this.item.CoCode;

      this.inprogress = true;
      this.$Progress.start();
      axios
        .put("api/impinvoice/" + month + "," + year + "," + cocode)
        .then(resp => {
          console.log(resp);
          this.inprogress = false;
          this.$Progress.finish();
        })
        .catch(err => {
          this.inprogress = false;
          let message = err.response.data.message;
          const errors = err.response.data.errors;

          for (const error in errors) {
            for (const msg in errors[error]) {
              message = errors[error][msg];
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
    this.item = this.payload;
    this.name = this.text;
    if (!this.item) {
      this.item = {
        id: 1,
        CoCode: "1001",
        Month: "01",
        Year: "2020"
      };
      if (!this.text) {
        this.name = "UOL Group Limited";
      }
    }
  }
};
</script>
