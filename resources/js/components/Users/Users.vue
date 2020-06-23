<template>
  <div class="container-fluid">
    <div v-if="$Role.isAdmin()">
      <section class="content-header pb-1">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1>Users</h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item">
                  <a href="#">Home</a>
                </li>
                <li class="breadcrumb-item active">Users</li>
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
                  <a href="#" @click.prevent="addNewUser">
                    <i class="fa fa-user-plus"></i>
                  </a>
                  <div class="card-tools">
                    <div class="input-group input-group-sm" style="width: 250px;">
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
                </div>
                <!-- /.card-header -->
                <div class="card-body table-responsive p-0">
                  <table class="table table-hover text-nowrap">
                    <thead>
                      <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Company</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Registered At</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="user in users" :key="user.id">
                        <td>{{ user.id }}</td>
                        <td>{{ user.name }}</td>
                        <td>{{ user.company }}</td>
                        <td>{{ user.email }}</td>
                        <td>{{ user.urole | upText }}</td>
                        <td>
                          {{
                          user.created_at
                          | humanDate
                          }}
                        </td>
                        <td>
                          <a
                            href
                            class="fa fa-edit"
                            @click.prevent="
                                                            editUser(user.id)
                                                        "
                          ></a>
                          /
                          <a
                            href
                            class="fa fa-trash text-red"
                            @click.prevent="
                                                            deleteUser(user.id)
                                                        "
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
      users: {},
      pgUsers: {
        uri: "api/user?page=",
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
      searchText: ""
    };
  },
  methods: {
    getTableData(page) {
      if (this.$Role.isAdmin()) {
        axios.get(this.pgUsers.uri + page).then(({ data }) => {
          this.users = data.data;
          this.pgUsers.records = data.total;
          this.pgUsers.page = data.current_page;
          this.pgUsers.perpage = data.per_page;
        });
      }
    },

    addNewUser() {
      this.$router.push({ path: "/userd", query: {} });
    },
    editUser(id) {
      this.$router.push({ path: "/userd", query: { userId: id } });
    },
    deleteUser(id) {
      if (this.$Role.isAdmin()) {
        Swal.fire({
          title: "Are you sure?",
          text: "You won't be able to revert this!",
          icon: "warning",
          showCancelButton: true,
          confirmButtonColor: "#3085d6",
          cancelButtonColor: "#d33",
          confirmButtonText: "Yes, delete it!"
        }).then(result => {
          //Send request to the server
          if (result.value) {
            axios
              .delete("api/user/" + id)
              .then(result => {
                if (result.status === 200) {
                  Swal.fire(
                    "Deleted!",
                    "Your user has been deleted.",
                    "success"
                  );
                  this.getTableData(this.pgUsers.page);
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
      }
    },

    searchTable() {
      if (this.$Role.isAdmin()) {
        if (this.searchText) {
          this.pgUsers.uri = "api/user?q=" + this.searchText + "&page=";
        } else {
          this.pgUsers.uri = "api/user?page=";
        }
        this.$Progress.start();
        axios
          .get(this.pgUsers.uri)
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
              title: "Something is wrong. Failed to search."
            });
            this.$Progress.fail();
          });
      }
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
