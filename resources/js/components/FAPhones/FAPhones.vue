<template>
  <div class="container-fluid">
    <div v-if="$Role.isAdmin()">
      <section class="content-header pb-1">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1>Phones</h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item">
                  <a href="#">Home</a>
                </li>
                <li class="breadcrumb-item active">Phones</li>
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
                  <a href="#" @click.prevent="addNewPhone">
                    <i class="fa fa-plus" title="Add New Phone"></i>
                  </a>

                  <div class="card-tools">
                    <div class="input-group input-group-sm" style="width: 250px;">
                      <i
                        class="fas fa-filter pt-2 pr-2"
                        :class="filter.show ? 'text-orange': 'text-gray'"
                        @click="doFilter"
                      ></i>
                      <input
                        v-model="searchText"
                        type="text"
                        name="table_search"
                        class="form-control float-right"
                        placeholder="Search Name Or Phone ID"
                        @keyup.enter="searchTable"
                      />

                      <div class="input-group-append">
                        <button type="button" class="btn btn-default" @click="searchTable">
                          <i class="fas fa-search"></i>
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body table-responsive p-0">
                  <table class="table table-hover text-nowrap">
                    <thead class="thead-dark">
                      <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Co.Code</th>
                        <th>Phone ID</th>
                        <th>Registered At</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="user in users" :key="user.id">
                        <td>{{ user.id }}</td>
                        <td>{{ user.name }}</td>
                        <td align="center">{{ user.company }}</td>
                        <td>{{ user.email }}</td>
                        <td>
                          {{
                          user.created_at
                          | humanDate
                          }}
                        </td>
                        <td>
                          <a href class="fa fa-edit" @click.prevent="editPhone(user.id)"></a>
                          /
                          <a
                            href
                            class="fa fa-trash text-red"
                            @click.prevent="deletePhone(user.id)"
                          ></a>
                        </td>
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
      <div
        class="modal fade"
        id="addNew"
        tabindex="-1"
        role="dialog"
        aria-labelledby="addNewLabel"
        aria-hidden="true"
      >
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="addNewLabel">Add New</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body"></div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <button type="button" class="btn btn-primary">Create</button>
            </div>
          </div>
        </div>
      </div>
      <!-- ./ AddNew Modal -->
      <!-- Filter Modal -->
      <div class="modal" tabindex="-1" role="dialog" id="filterModal">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="overlay-wrapper">
              <div class="modal-header">
                <h5 class="modal-title">Advanced Filter</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>

              <div class="modal-body">
                <form>
                  <div class="form-group row">
                    <label for="name" class="col-sm-3 col-form-label">Name</label>

                    <div class="col-sm-9">
                      <input
                        type="text"
                        class="form-control"
                        v-model="filter.name"
                        name="name"
                        placeholder="Name"
                      />
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="company" class="col-sm-3 col-form-label">Company</label>
                    <div class="col-sm-9">
                      <input
                        type="text"
                        class="form-control"
                        v-model="filter.company"
                        name="company"
                        placeholder="Company"
                      />
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="email" class="col-sm-3 col-form-label">Phone ID</label>

                    <div class="col-sm-9">
                      <input
                        type="email"
                        class="form-control"
                        v-model="filter.email"
                        name="email"
                        placeholder="Email"
                      />
                    </div>
                  </div>
                  <!-- <div class="form-group">
                    <div class="row">
                      <div class="col-8">
                        <label for="group">Group</label>
                        <span class="text-sm font-italic">Shift+Click for multi selection</span>
                      </div>
                      <div class="col-4 d-flex justify-content-end">
                        <a href class="text-sm text-blue" @click.prevent="clearGroup">Clear</a>
                      </div>
                    </div>

                    <select multiple class="form-control" id="group" v-model="filter.group">
                      <option v-for="group in groups" :key="group.id">{{ group.name }}</option>
                    </select>
                  </div>
                  <span>Selected Group: {{this.filter.group}}</span>-->
                </form>
              </div>
              <!-- ./modal-body -->
              <div class="modal-footer">
                <button
                  type="button"
                  class="btn btn-primary"
                  data-dismiss="modal"
                  @click="getTableData(1)"
                >Submit</button>
                <button type="button" class="btn btn-secondary" @click="clearFilter">Clear Filter</button>
              </div>
            </div>
            <div class v-if="inprogress.filter">
              <i class="fas fa-3x fa-sync-alt fa-spin"></i>
              <div class="text-bold pl-2">Loading...</div>
            </div>
          </div>
          <!-- ./overlay-wrapper -->
        </div>
      </div>
      <!-- ./Filter Modal -->
    </div>
    <div v-if="!$Role.isAdmin()">
      <not-found></not-found>
    </div>
  </div>
</template>
<script>
export default {
  data() {
    return {
      inprogress: {
        filter: false,
      },
      users: {},
      baseUri: "api/user?qtype=phone",
      pgUsers: {
        page: 1,
        perpage: 10,
        records: 0,
        options: {
          chunksNavigation: scroll,
          texts: {
            count: "|||",
          },
        },
      },

      searchText: "",
      filter: {
        show: null,
        name: null,
        company: null,
        email: null,
        group: [],
      },
      groups: [],
    };
  },
  methods: {
    getTableData(page) {
      if (this.$Role.isAdmin()) {
        let filter = "";

        filter += this.filter.name ? "&qname=" + this.filter.name : "";
        filter += this.filter.company ? "&qcompany=" + this.filter.company : "";
        filter += this.filter.email ? "&qemail=" + this.filter.email : "";

        if (this.filter.group.length > 0) {
          let ids = "";
          for (var i = 0; i < this.filter.group.length; i++) {
            let group = _.find(this.groups, (item) => {
              if (item.name === this.filter.group[i]) {
                return item;
              }
            });
            ids += group.id + ",";
          }

          filter += "&qgroup=" + ids.substring(0, ids.length - 1);
        }

        axios.get(this.baseUri + filter + "&page=" + page).then(({ data }) => {
          this.users = data.data;
          this.pgUsers.records = data.total;
          this.pgUsers.page = data.current_page;
          this.pgUsers.perpage = data.per_page;
        });
      }
    },

    addNewPhone() {
      this.$router.push({ path: "/faphoneform", query: {} });
    },
    editPhone(id) {
      this.$router.push({ path: "/faphoneform", query: { userId: id } });
    },
    deletePhone(id) {
      if (this.$Role.isAdmin()) {
        Swal.fire({
          title: "Are you sure?",
          text: "You won't be able to revert this!",
          icon: "warning",
          showCancelButton: true,
          confirmButtonColor: "#3085d6",
          cancelButtonColor: "#d33",
          confirmButtonText: "Yes, delete it!",
        }).then((result) => {
          //Send request to the server
          if (result.value) {
            axios
              .delete("api/user/" + id)
              .then((result) => {
                if (result.status === 200) {
                  Swal.fire(
                    "Deleted!",
                    "Your user has been deleted.",
                    "success"
                  );
                  this.getTableData(this.pgUsers.page);
                }
              })
              .catch((error) => {
                let message = error.response.data.message;
                if (message) {
                  Swal.fire("Failed!", message, "warning");
                } else {
                  Swal.fire("Failed!", "There is something wrong.", "warning");
                }
              });
          }
        });
      }
    },

    searchTable() {
      var uri = "";

      if (this.$Role.isAdmin()) {
        if (this.searchText) {
          uri = this.baseUri + "&q=" + this.searchText + "&page=";
        } else {
          uri = this.baseUri + "&page=1";
        }
        this.$Progress.start();
        axios
          .get(uri)
          .then(({ data }) => {
            this.users = data.data;
            this.pgUsers.records = data.total;
            this.pgUsers.page = data.current_page;
            this.pgUsers.perpage = data.per_page;
            this.$Progress.finish();
          })
          .catch(() => {
            Toast.fire({
              icon: "error",
              title: "Something is wrong. Failed to search.",
            });
            this.$Progress.fail();
          });
      }
    },
    doFilter() {
      axios
        .get("api/group")
        .then((resp) => {
          if (resp.data) {
            this.groups = resp.data;
          }
        })
        .catch((err) => {});

      $("#filterModal").modal("toggle");
      this.filter.show = true;
    },
    clearGroup() {
      this.filter.group = [];
    },
    clearFilter() {
      $("#filterModal").modal("toggle");
      for (var key in this.filter) {
        if (key === "group") {
          this.filter[key] = [];
        } else {
          this.filter[key] = null;
        }
      }
      this.getTableData(1);
    },
  },

  mounted() {
    Fire.$on("GLOBAL_SEARCH", () => {
      this.searchText = this.$parent.searchText;
    });
    this.getTableData(1);
  },
};
</script>
