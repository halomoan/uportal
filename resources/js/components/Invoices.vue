<template>
  <div>
    <div v-if="$Role.isAdminOrUser()">
      <section class="content-header pb-1">
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
              <div class="card card-primary card-outline card-outline-tabs">
                <div class="card-header p-0 border-bottom-0">
                  <div class="card-tools">
                    <div class="input-group input-group-sm mt-3" style="width: 250px;">
                      <input
                        v-model="searchText"
                        type="text"
                        name="table_search"
                        class="form-control float-right"
                        placeholder="Search"
                        @keyup.enter="searchTable"
                      />

                      <div class="input-group-append">
                        <button type="button" class="btn btn-default" @click="searchTable">
                          <i class="fas fa-search"></i>
                        </button>
                      </div>
                    </div>
                  </div>
                  <ul class="nav nav-tabs" id="yearly-tab" role="tablist">
                    <li class="nav-item" v-for="(year,index) in years" v-bind:key="year">
                      <a
                        href="#"
                        class="nav-link"
                        v-bind:class="{ 'active' : tabIndex === index }"
                        :id="'year' + year"
                        role="tab"
                        :aria-controls="'year-' + index"
                        aria-selected="true"
                        @click.stop.prevent="setActive(index)"
                      >{{year}}</a>
                    </li>
                  </ul>
                </div>
                <div class="card-body">
                  <div class="tab-content" id="yearly-tabContent">
                    <div
                      v-for="(year,index) in years"
                      v-bind:key="year"
                      class="tab-pane fade"
                      :id="'year-' + index"
                      role="tabpanel"
                      :aria-labelledby="'year' + index"
                      v-bind:class="{ 'active' : tabIndex === index, 'show' : tabIndex === index}"
                    >
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
                            <tbody>
                              <tr v-for="invoice in pgTable[index].invoices" :key="invoice.id">
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
                                >{{ invoice.title | truncate( 50 ) }}</td>
                                <td
                                  class="text-green text-right"
                                  v-bind:class="{ 'text-bold' : invoice.unread, 'text-black-50': ! invoice.unread }"
                                >{{ invoice.amount | currency('SGD',2) }}</td>

                                <td
                                  class="text-right"
                                  v-bind:class="{ 'text-bold' : invoice.unread, 'text-black-50': ! invoice.unread }"
                                >{{ invoice.filename | upText }}</td>
                                <td>
                                  <i class="far fa-file-pdf text-red"></i>
                                </td>
                              </tr>
                            </tbody>
                          </table>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-12">
                          <div class="d-flex justify-content-end text-right">
                            <pagination
                              :records="pgTable[tabIndex].records"
                              @paginate="getTableData"
                              v-model="pgTable[tabIndex].page"
                              :per-page="pgTable[tabIndex].perpage"
                            ></pagination>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- /.card -->
              </div>
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
      tabIndex: 0,
      _invoices: [],
      pgTable: [
        {
          invoices: {},
          uri: "api/invoices?page=",
          page: 1,
          perpage: 10,
          records: 0
        },
        {
          invoices: {},
          uri: "api/invoices?page=",
          page: 1,
          perpage: 10,
          records: 0
        },
        {
          invoices: {},
          uri: "api/invoices?page=",
          page: 1,
          perpage: 10,
          records: 0
        },
        {
          invoices: {},
          uri: "api/invoices?page=",
          page: 1,
          perpage: 10,
          records: 0
        }
      ],
      years: ["2020", "2019", "2018", "2017"],
      searchText: ""
    };
  },
  methods: {
    setActive(tab) {
      this.tabIndex = tab;
      this.getTableData(1);
    },
    getTableData(page) {
      if (this.$Role.isAdminOrUser()) {
        let uri =
          this.pgTable[this.tabIndex].uri +
          page +
          "&y=" +
          this.years[this.tabIndex];

        axios.get(uri).then(({ data }) => {
          this.pgTable[this.tabIndex].invoices = data.data;
          this.pgTable[this.tabIndex].records = data.total;
          this.pgTable[this.tabIndex].page = data.current_page;
          this.pgTable[this.tabIndex].perpage = data.per_page;
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
      this.$forceUpdate();
    },
    searchTable() {
      if (this.searchText) {
        this.pgTable[this.tabIndex].uri =
          "api/invoices?" +
          "y=" +
          this.years[this.tabIndex] +
          "&q=" +
          this.searchText +
          "&page=";
      } else {
        this.pgTable[this.tabIndex].uri =
          "api/invoices?" + "y=" + this.years[this.tabIndex] + "&page=";
      }
      this.$Progress.start();
      axios
        .get(this.pgTable[this.tabIndex].uri)
        .then(({ data }) => {
          //this.invoices[this.tabIndex] = data.data;
          this.pgTable[this.tabIndex].invoices = data.data;
          this.pgTable[this.tabIndex].records = data.total;
          this.pgTable[this.tabIndex].page = data.current_page;
          this.pgTable[this.tabIndex].perpage = data.per_page;
          this.$Progress.finish();
        })
        .catch(() => {
          Toast.fire({
            icon: "error",
            title: "Something is wrong. Failed to search."
          });
          this.$Progress.fail();
        });
    }
  },
  mounted() {
    Fire.$on("GLOBAL_SEARCH", () => {
      this.searchText = this.$parent.searchText;
    });
    this.getTableData(1);
  }
};
</script>
