<template>
  <div>
    <div class="content-header pb-1">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>User Group</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item">
                <a href="dashboard">Home</a>
              </li>
              <li class="breadcrumb-item active">User Group</li>
            </ol>
          </div>
        </div>
      </div>
    </div>
    <!-- ./content-header -->
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-4 col-4">
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Input Form</h3>
              </div>
              <!-- /.card-header -->

              <div class="card-body">
                <div class="form-group">
                  <label for="name">Name</label>
                  <input
                    type="text"
                    class="form-control"
                    autocomplete="off"
                    :class="{
                                            'is-invalid': form.errors.has(
                                                'name'
                                            )
                                        }"
                    id="name"
                    v-model="form.name"
                    placeholder="Enter group name (max. 50 chars)"
                    required
                    maxlength="50"
                    @keyup.enter="
                                            mode === 'create'
                                                ? createGroup()
                                                : modifyGroup(form.id)
                                        "
                  />
                  <has-error :form="form" field="name"></has-error>
                </div>
                <div class="form-check">
                  <input
                    type="checkbox"
                    class="form-check-input"
                    id="isDefault"
                    v-model="form.is_default"
                  />
                  <label class="form-check-label" for="isDefault">Default Group</label>
                </div>
                <div class="form-check">
                  <input
                    type="checkbox"
                    class="form-check-input"
                    id="isEnabled"
                    v-model="form.is_enabled"
                  />
                  <label class="form-check-label" for="isEnabled">Active</label>
                </div>
              </div>
              <!-- /.card-body -->

              <div class="card-footer">
                <button
                  v-if="mode === 'create'"
                  type="button"
                  class="btn btn-primary"
                  @click.prevent="createGroup"
                >Create</button>
                <button
                  v-if="mode === 'edit'"
                  type="button"
                  class="btn btn-primary"
                  @click.prevent="modifyGroup"
                >Modify</button>
                <button
                  type="button"
                  class="btn btn-default float-right"
                  v-if="mode === 'edit'"
                  @click.prevent="cancelEdit"
                >Cancel</button>
              </div>
            </div>
          </div>
          <div class="col-lg-8 col-8">
            <div class="card card-primary">
              <div class="card-header">
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
                      <th>Default Group</th>
                      <th>Status</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="group in groups" :key="group.id">
                      <td>{{ group.id }}</td>
                      <td>{{ group.name }}</td>
                      <td>
                        {{
                        group.is_default
                        ? "Yes"
                        : "No"
                        }}
                      </td>
                      <td>
                        {{
                        group.is_enabled
                        ? "Active"
                        : "Deactivated"
                        }}
                      </td>
                      <td>
                        <a
                          href
                          class="fa fa-edit"
                          @click.prevent="
                                                        editGroup(group.id)
                                                    "
                        ></a>
                        /
                        <a
                          href
                          class="fa fa-trash text-red"
                          @click.prevent="
                                                        deleteGroup(group.id)
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
                    :records="pgGroups.records"
                    @paginate="getTableData"
                    v-model="pgGroups.page"
                    :per-page="pgGroups.perpage"
                  ></pagination>
                </div>
              </div>
              <!-- ./card-footer -->
            </div>
            <!-- ./card -->
          </div>
        </div>
      </div>
    </div>
    <!-- ./content -->
  </div>
</template>

<script>
export default {
  data() {
    return {
      groups: {},
      form: new Form({
        name: "",
        is_default: false,
        is_enabled: true
      }),
      pgGroups: {
        uri: "api/group?page=",
        page: 1,
        perpage: 10,
        records: 0
      },
      searchText: "",
      inprogress: false,
      mode: "create"
    };
  },
  methods: {
    getTableData(page) {
      if (this.$Role.isAdmin()) {
        axios.get(this.pgGroups.uri + page).then(({ data }) => {
          this.groups = data.data;
          this.pgGroups.records = data.total;
          this.pgGroups.page = data.current_page;
          this.pgGroups.perpage = data.per_page;
        });
      }
    },
    createGroup() {
      if (this.$Role.isAdmin()) {
        this.$Progress.start();
        this.inprogress = true;
        this.form
          .post("api/group")
          .then(({ data }) => {
            this.$Progress.finish();
            this.inprogress = false;
            this.form = new Form(data);
            this.form.name = "";
            Toast.fire({
              icon: "success",
              title: "Group created successfully"
            });

            this.getTableData(1);
          })
          .catch(error => {
            this.$Progress.fail();
            this.inprogress = false;
            let message = error.response.data.message;
            if (message) {
              Swal.fire("Failed!", message, "warning");
            } else {
              Swal.fire("Failed!", "There is something wrong.", "warning");
            }
          });
      }
    },
    editGroup(id) {
      this.$Progress.start();
      this.inprogress = true;
      this.form
        .get("api/group/" + id)
        .then(({ data }) => {
          this.$Progress.finish();
          this.inprogress = false;
          this.form = new Form(data);
          this.mode = "edit";
        })
        .catch(error => {
          this.$Progress.fail();
          this.inprogress = false;
          let message = error.response.data.message;
          if (message) {
            Swal.fire("Failed!", message, "warning");
          } else {
            Swal.fire("Failed!", "There is something wrong.", "warning");
          }
        });
    },
    modifyGroup() {
      if (this.$Role.isAdmin()) {
        this.$Progress.start();
        this.inprogress = true;
        this.form
          .put("api/group/" + this.form.id)
          .then(({ data }) => {
            this.$Progress.finish();
            this.inprogress = false;
            this.mode = "create";
            this.form = new Form(data);
            Toast.fire({
              icon: "success",
              title: "Modified successfully"
            });
            this.getTableData(this.pgGroups.page);
          })
          .catch(error => {
            this.$Progress.fail();
            this.inprogress = false;
            let message = error.response.data.message;
            if (message) {
              Swal.fire("Failed!", message, "warning");
            } else {
              Swal.fire("Failed!", "There is something wrong.", "warning");
            }
          });
      }
    },
    cancelEdit() {
      this.form.name = "";
      this.mode = "create";
    },
    deleteGroup(id) {
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
              .delete("api/group/" + id)
              .then(() => {
                if (result.value) {
                  Swal.fire("Deleted!", "The group been deleted.", "success");
                  this.getTableData(this.pgGroups.page);
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
          this.pgGroups.uri = "api/group?q=" + this.searchText + "&page=";
        } else {
          this.pgGroups.uri = "api/group?q=%&page=";
        }
        this.$Progress.start();
        axios
          .get(this.pgGroups.uri)
          .then(({ data }) => {
            this.groups = data.data;
            this.pgGroups.records = data.total;
            this.pgGroups.page = data.current_page;
            this.pgGroups.perpage = data.per_page;
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
  created() {
    this.getTableData(1);
  }
};
</script>
