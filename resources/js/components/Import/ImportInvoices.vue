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
                    <h4 class="m-0">{{item.CoCode}} - {{ name }}</h4>

                    <h5 class="m-0">{{item.Month | MMM }} - {{ item.Year }}</h5>
                  </div>
                  <div class="col-6">
                    <div class="d-flex justify-content-end">
                      <button class="btn bg-gradient-primary btn-sm">
                        <i class="fas fa-upload"></i>
                        Execute
                      </button>
                      <button class="btn bg-gradient-primary btn-sm">
                        <i class="fas fa-chevron-left"></i>
                        Back
                      </button>
                    </div>
                  </div>
                </div>
                <div class="row pt-2">
                  <div class="col-12">
                    <div class="progress w-100">
                      <div
                        class="progress-bar bg-success"
                        role="progressbar"
                        style="width: 25%;"
                        aria-valuenow="25"
                        aria-valuemin="0"
                        aria-valuemax="100"
                      >25%</div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-12">
                    <table class="table table-hover text-nowrap">
                      <thead>
                        <tr>
                          <th>Date</th>
                          <th>Invoice No</th>
                          <th>Description</th>
                          <th class="text-right">Amount</th>
                          <th class="text-right">Filename</th>
                          <th class="text-right"></th>
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
  props: { name: String, item: Object },
  data() {
    return {};
  },
  methods: {},
  mounted() {
    console.log(this.item, this.name);
  }
};
</script>
