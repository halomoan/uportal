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
                      class="tab-pane fade"
                      id="year-0"
                      role="tabpanel"
                      aria-labelledby="year0"
                      v-bind:class="{ 'active' : tabIndex === 0, 'show' : tabIndex === 0}"
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
                              <tr v-for="invoice in invoices[0]" :key="invoice.id">
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
                              :records="pgTable[0].records"
                              @paginate="getTableData"
                              v-model="pgTable[0].page"
                              :per-page="pgTable[0].perpage"
                            ></pagination>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div
                      class="tab-pane fade"
                      id="year-1"
                      role="tabpanel"
                      aria-labelledby="year1"
                      v-bind:class="{ 'active' : tabIndex === 1, 'show' : tabIndex === 1}"
                    >Mauris tincidunt mi at erat gravida, eget tristique urna bibendum. Mauris pharetra purus ut ligula tempor, et vulputate metus facilisis. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Maecenas sollicitudin, nisi a luctus interdum, nisl ligula placerat mi, quis posuere purus ligula eu lectus. Donec nunc tellus, elementum sit amet ultricies at, posuere nec nunc. Nunc euismod pellentesque diam.</div>
                    <div
                      class="tab-pane fade"
                      id="year-2"
                      role="tabpanel"
                      aria-labelledby="year2"
                      v-bind:class="{ 'active' : tabIndex === 2, 'show' : tabIndex === 2}"
                    >Morbi turpis dolor, vulputate vitae felis non, tincidunt congue mauris. Phasellus volutpat augue id mi placerat mollis. Vivamus faucibus eu massa eget condimentum. Fusce nec hendrerit sem, ac tristique nulla. Integer vestibulum orci odio. Cras nec augue ipsum. Suspendisse ut velit condimentum, mattis urna a, malesuada nunc. Curabitur eleifend facilisis velit finibus tristique. Nam vulputate, eros non luctus efficitur, ipsum odio volutpat massa, sit amet sollicitudin est libero sed ipsum. Nulla lacinia, ex vitae gravida fermentum, lectus ipsum gravida arcu, id fermentum metus arcu vel metus. Curabitur eget sem eu risus tincidunt eleifend ac ornare magna.</div>
                    <div
                      class="tab-pane fade"
                      id="year-3"
                      role="tabpanel"
                      aria-labelledby="year3"
                      v-bind:class="{ 'active' : tabIndex === 3, 'show' : tabIndex === 3}"
                    >Pellentesque vestibulum commodo nibh nec blandit. Maecenas neque magna, iaculis tempus turpis ac, ornare sodales tellus. Mauris eget blandit dolor. Quisque tincidunt venenatis vulputate. Morbi euismod molestie tristique. Vestibulum consectetur dolor a vestibulum pharetra. Donec interdum placerat urna nec pharetra. Etiam eget dapibus orci, eget aliquet urna. Nunc at consequat diam. Nunc et felis ut nisl commodo dignissim. In hac habitasse platea dictumst. Praesent imperdiet accumsan ex sit amet facilisis.</div>
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
      invoices: [],
      pgTable: [
        {
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
    },
    getTableData(page) {
      if (this.$Role.isAdminOrUser()) {
        let uri =
          this.pgTable[this.tabIndex].uri +
          page +
          "&y=" +
          this.years[this.tabIndex];

        axios.get(uri).then(({ data }) => {
          this.invoices[this.tabIndex] = data.data;
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
          this.invoices[this.tabIndex] = data.data;
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
