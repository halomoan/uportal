<template>
  <div class="container-fluid">
    <div v-if="$Role.isAdminOrUser()">
      <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1>Invoices</h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item">
                  <a href="#">Home</a>
                </li>
                <li class="breadcrumb-item active">Invoices</li>
              </ol>
            </div>
          </div>
        </div>
        <!-- /.container-fluid -->
      </section>

      <!-- Main content -->
      <section class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-12">
              <div class="card">
                <div class="card-header">
                  <!-- <a href="#" @click.prevent="addNewUser">
                    <i class="fa fa-user-plus"></i>
                  </a>-->

                  <div class="card-tools">
                    <div class="input-group input-group-sm" style="width: 250px;">
                      <input
                        type="text"
                        name="table_search"
                        class="form-control float-right"
                        placeholder="Search"
                      />

                      <div class="input-group-append">
                        <button type="submit" class="btn btn-default">
                          <i class="fas fa-search"></i>
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body table-responsive p-0">
                  <table class="table table-hover text-nowrap">
                    <thead>
                      <tr>
                        <th>Date</th>
                        <th>Invoice No</th>
                        <th>Description</th>
                        <th class="text-right">Amount</th>
                        <th class="text-right">Filename</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="invoice in invoices" :key="invoice.id">
                        <td
                          v-bind:class="{ 'text-bold' : invoice.unread, 'text-black-50': ! invoice.unread }"
                        >{{ invoice.inv_date | humanDate }}</td>
                        <td
                          v-bind:class="{ 'text-bold' : invoice.unread, 'text-black-50': ! invoice.unread }"
                        >
                          <router-link to="/invoiced">{{ invoice.inv_no }}</router-link>
                        </td>
                        <td
                          v-bind:class="{ 'text-bold' : invoice.unread, 'text-black-50': ! invoice.unread }"
                        >{{ invoice.title }}</td>
                        <td
                          class="text-green text-right"
                          v-bind:class="{ 'text-bold' : invoice.unread, 'text-black-50': ! invoice.unread }"
                        >{{ invoice.amount | currency('SGD',2) }}</td>
                        <td
                          class="text-right"
                          v-bind:class="{ 'text-bold' : invoice.unread, 'text-black-50': ! invoice.unread }"
                        >{{ invoice.filename | upText }}</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
                <!-- /.card-body -->
                <div class="card-footer pb-0">
                  <div class="d-flex justify-content-end text-right">
                    <pagination
                      :records="pgUsers.records"
                      @paginate="getTableData"
                      v-model="pgUsers.page"
                      :per-page="pgUsers.perpage"
                    ></pagination>
                  </div>
                </div>
                <!-- /.card-footer -->
              </div>
              <!-- /.card -->
            </div>
          </div>
          <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
      </section>
    </div>
    <div v-if="!$Role.isAdminOrUser()">
      <not-found></not-found>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      invoices: {},
      pgUsers: {
        uri: "api/invoices?page=",
        page: 1,
        perpage: 10,
        records: 0
      }
    };
  },
  methods: {
    getTableData(page) {
      if (this.$Role.isAdminOrUser()) {
        axios.get(this.pgUsers.uri + page).then(({ data }) => {
          this.invoices = data.data;
          this.pgUsers.records = data.total;
          this.pgUsers.page = data.current_page;
          this.pgUsers.perpage = data.per_page;
          this.hasNew();
        });
      }
    },
    hasNew() {
      let result = false;
      result = _.find(this.invoices, function(invoice) {
        if (invoice.unread) {
          return true;
        }
      });
      this.$parent.newFlag("INVOICES", result);
    }
  },
  mounted() {
    Fire.$on("GLOBAL_SEARCH", () => {
      let query = this.$parent.searchText;
      if (query) {
        this.pgUsers.uri = "api/invoices?q=" + query + "&page=";
      } else {
        this.pgUsers.uri = "api/invoices?page=";
      }
      this.$Progress.start();
      axios
        .get(this.pgUsers.uri)
        .then(({ data }) => {
          this.invoices = data.data;
          this.pgUsers.records = data.total;
          this.pgUsers.page = data.current_page;
          this.pgUsers.perpage = data.per_page;
          this.$Progress.finish();
        })
        .catch(() => {
          Toast.fire({
            icon: "error",
            title: "Something is wrong. Failed to search."
          });
          this.$Progress.fail();
        });
    });
    this.getTableData(1);
  }
};
</script>
