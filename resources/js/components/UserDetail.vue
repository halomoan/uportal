<template>
  <div>
    <div v-if="$Role.isAdmin()">
      <section class="content-header pb-1">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1>
                <span v-show="editMode">Edit User</span>
                <span v-show="!editMode">Create User</span>
              </h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item">
                  <a href="/users">Users</a>
                </li>
                <li class="breadcrumb-item active">
                  <span v-show="editMode">Edit User</span>
                  <span v-show="!editMode">Create User</span>
                </li>
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
                <div class="card-header p-2">
                  <ul class="nav nav-pills">
                    <li class="nav-item">
                      <a class="nav-link active" href="#general" data-toggle="tab">General</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" href="#billing" data-toggle="tab">Billing</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" href="#group" data-toggle="tab">Group</a>
                    </li>
                  </ul>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                  <div class="overlay-wrapper">
                    <div class="tab-content">
                      <!-- /.tab-pane -->
                      <div class="tab-pane active" id="general">
                        <!-- <form class="form-horizontal" @submit.prevent="createUser"> -->
                        <div class="form-group row">
                          <label for="name" class="col-sm-2 col-form-label">Name</label>
                          <div class="col-sm-10">
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
                              name="name"
                              placeholder="Name"
                              required
                            />
                            <has-error :form="form" field="name"></has-error>
                          </div>
                        </div>
                        <div class="form-group row">
                          <label for="company" class="col-sm-2 col-form-label">Company</label>
                          <div class="col-sm-10">
                            <input
                              type="text"
                              class="form-control"
                              autocomplete="off"
                              :class="{
                                                                'is-invalid': form.errors.has(
                                                                    'company'
                                                                )
                                                            }"
                              id="company"
                              v-model="
                                                                form.company
                                                            "
                              name="company"
                              placeholder="company"
                              required
                            />
                            <has-error :form="form" field="company"></has-error>
                          </div>
                        </div>
                        <div class="form-group row">
                          <label for="email" class="col-sm-2 col-form-label">Email</label>
                          <div class="col-sm-10">
                            <input
                              type="email"
                              class="form-control"
                              autocomplete="off"
                              :class="{
                                                                'is-invalid': form.errors.has(
                                                                    'email'
                                                                )
                                                            }"
                              id="email"
                              v-model="form.email"
                              name="email"
                              placeholder="Email address"
                              required
                            />
                            <has-error :form="form" field="email"></has-error>
                          </div>
                        </div>
                        <form>
                          <div class="form-group row">
                            <label for="password" class="col-sm-2 col-form-label">Password</label>
                            <div class="col-sm-10">
                              <input
                                type="password"
                                class="form-control"
                                id="password"
                                :class="{
                                                                    'is-invalid': form.errors.has(
                                                                        'password'
                                                                    )
                                                                }"
                                name="password"
                                v-model="
                                                                    form.password
                                                                "
                                placeholder="Password"
                                required
                              />
                              <has-error :form="form" field="password"></has-error>
                            </div>
                          </div>
                          <div class="form-group row">
                            <label for="repassword" class="col-sm-2 col-form-label">
                              Re-Type
                              Password
                            </label>
                            <div class="col-sm-10">
                              <input
                                type="password"
                                class="form-control"
                                id="repassword"
                                :class="{
                                                                    'is-invalid': form.errors.has(
                                                                        'repassword'
                                                                    )
                                                                }"
                                name="repassword"
                                v-model="
                                                                    form.repassword
                                                                "
                                placeholder="Re-type Password"
                                required
                              />
                              <has-error :form="form" field="repassword"></has-error>
                            </div>
                          </div>
                        </form>
                        <div class="form-group row">
                          <label for="type" class="col-sm-2 col-form-label">Type</label>
                          <div class="col-sm-10">
                            <select
                              name="type"
                              v-model="form.urole"
                              id="type"
                              class="form-control"
                              :class="{
                                                                'is-invalid': form.errors.has(
                                                                    'type'
                                                                )
                                                            }"
                            >
                              <option value>Select User Role</option>
                              <option value="admin">Admin</option>
                              <option value="user">Standard User</option>
                            </select>
                            <has-error :form="form" field="type"></has-error>
                          </div>
                        </div>
                        <!-- </form> -->
                      </div>
                      <!-- /.tab-pane -->
                      <div class="tab-pane" id="billing">
                        <form class="form-horizontal" @submit.prevent="createUser">
                          <div class="form-group row">
                            <label for="billaddr" class="col-sm-2 col-form-label">
                              Billing
                              Address
                            </label>
                            <div class="col-sm-10">
                              <textarea
                                rows="5"
                                class="form-control"
                                :class="{
                                                                    'is-invalid': form.errors.has(
                                                                        'billaddr'
                                                                    )
                                                                }"
                                id="billaddr"
                                v-model="
                                                                    form.billaddr
                                                                "
                                name="billaddr"
                                placeholder="billaddr"
                              />
                              <has-error :form="form" field="billaddr"></has-error>
                            </div>
                          </div>
                        </form>
                      </div>
                      <!-- /.tab-pane -->
                      <div class="tab-pane" id="group">
                        <div class="row">
                          <div class="col-5">
                            <div class="form-group">
                              <label for="unassigned">
                                Available
                                Group:
                              </label>
                              <select
                                v-model="
                                                                    groups.checkAvailGroup
                                                                "
                                multiple
                                class="form-control"
                                id="unassigned"
                                size="10"
                              >
                                <option
                                  v-for="(group,
                                                                    index) in groups.availGroup"
                                  :key="
                                                                        group.id
                                                                    "
                                  :value="
                                                                        index
                                                                    "
                                >
                                  {{
                                  group.name
                                  }}
                                </option>
                              </select>
                            </div>
                          </div>
                          <div
                            class="col-2 d-flex flex-column justify-content-center align-items-center"
                          >
                            <button
                              type="button"
                              class="btn btn-info mb-2"
                              @click.prevent="
                                                                addUserGroup
                                                            "
                            >&gt;</button>
                            <button
                              type="button"
                              class="btn btn-default"
                              @click.prevent="
                                                                removeUserGroup
                                                            "
                            >&lt;</button>
                          </div>
                          <div class="col-5">
                            <div class="form-group">
                              <label for="usergroup">
                                User
                                Group:
                              </label>
                              <select
                                v-model="
                                                                    groups.checkUserGroup
                                                                "
                                multiple
                                class="form-control"
                                id="usergroup"
                                size="10"
                              >
                                <option
                                  v-for="(group,
                                                                    index) in groups.userGroup"
                                  :key="index"
                                  :value="
                                                                        index
                                                                    "
                                >
                                  {{
                                  group.name
                                  }}
                                </option>
                              </select>
                            </div>
                          </div>
                        </div>
                      </div>
                      <!-- /.tab-pane -->
                      <!-- /.form -->
                    </div>
                    <!-- /.tab-content -->
                    <div class="overlay" v-if="inprogress">
                      <i class="fas fa-3x fa-sync-alt fa-spin"></i>
                      <div class="text-bold pl-2">Loading...</div>
                    </div>
                  </div>
                  <!-- /.overlay-wrapper -->
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                  <button
                    type="button"
                    class="btn btn-info"
                    @click.prevent="editMode ? editUser() : createUser()"
                  >
                    <span v-show="editMode">Modify</span>
                    <span v-show="!editMode">Create</span>
                  </button>
                  <button
                    type="button"
                    class="btn btn-default float-right"
                    @click.prevent="goBack"
                  >Cancel</button>
                </div>
              </div>
            </div>
          </div>
          <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
      </section>
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
      form: new Form({
        id: "",
        name: "",
        email: "",
        password: "",
        repassword: "",
        groups: [],
        type: "",
        photo: "",
        billaddr: ""
      }),
      groups: {
        allGroups: [],
        availGroup: [],
        userGroup: [],
        checkAvailGroup: [],
        checkUserGroup: []
      },
      inprogress: false,
      editMode: false
    };
  },
  methods: {
    editUser() {
      this.form.groups = _.map(this.groups.userGroup, "id");

      this.$Progress.start();
      this.inprogress = true;
      this.form
        .put("api/user/" + this.form.id)
        .then(() => {
          this.$Progress.finish();
          this.inprogress = false;
          Toast.fire({
            icon: "success",
            title: "User modified successfully"
          });
          this.goBack();
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
    createUser() {
      this.form.groups = _.map(this.groups.userGroup, "id");

      this.$Progress.start();
      this.inprogress = true;

      this.form
        .post("api/user")
        .then(({ data }) => {
          this.$Progress.finish();
          this.inprogress = false;
          Toast.fire({
            icon: "success",
            title: "User created successfully"
          });

          this.goBack();
        })
        .catch(() => {
          this.$Progress.fail();
          this.inprogress = false;
        });
    },
    goBack() {
      window.history.length > 1
        ? this.$router.go(-1)
        : this.$router.push("/users");
    },
    addUserGroup() {
      if (!this.groups.availGroup.length) {
        return;
      }
      let selected = [];
      for (var i in this.groups.checkAvailGroup) {
        let idx = this.groups.checkAvailGroup[i];

        selected.push(this.groups.availGroup[idx]);
      }

      this.groups.userGroup = [
        ...new Set([...this.groups.userGroup, ...selected])
      ];

      this.groups.availGroup = _.differenceBy(
        this.groups.availGroup,
        this.groups.userGroup,
        "id"
      );
    },
    removeUserGroup() {
      if (!this.groups.userGroup.length) {
        return;
      }
      let selected = [];
      for (var i in this.groups.checkUserGroup) {
        let idx = this.groups.checkUserGroup[i];

        selected.push(this.groups.userGroup[idx]);
      }
      this.groups.availGroup = [
        ...new Set([...this.groups.availGroup, ...selected])
      ];

      this.groups.userGroup = _.differenceBy(
        this.groups.userGroup,
        this.groups.availGroup,
        "id"
      );
    },
    getGroups() {
      axios
        .get("api/group")
        .then(({ data }) => {
          this.groups.allGroups = data;
          this.groups.availGroup = this.groups.allGroups.filter(function(item) {
            return item.is_enabled && !item.is_default;
          });
          this.groups.userGroup = this.groups.allGroups.filter(function(item) {
            return item.is_default;
          });

          //New or Edit User
          const userId = this.$route.query.userId;
          if (typeof userId === "undefined") {
            this.editMode = false;
            this.form.reset();
          } else if (userId) {
            this.getUserData(userId);
          } else {
            this.editMode = false;
            this.form.reset();
          }
        })
        .catch(() => {
          Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "Failed to retrieve Group Info!",
            footer: "<a href='/users'>Let me redo again</a>"
          });
        });
    },

    getUserData(userId) {
      this.inprogress = true;
      this.$Progress.start();
      axios
        .get("api/user/" + userId)
        .then(({ data }) => {
          this.form = new Form(data);

          if (data.groups && data.groups.length > 0) {
            this.groups.userGroup = [];

            Vue.set(this.groups, "userGroup", data.groups);

            this.groups.availGroup = [];

            Vue.set(
              this.groups,
              "availGroup",
              _.differenceBy(this.groups.allGroups, this.groups.userGroup, "id")
            );
          } else {
            this.groups.availGroup = [];

            Vue.set(this.groups, "availGroup", [...this.groups.allGroups]);

            this.groups.userGroup = [];
          }

          //this.$forceUpdate();

          this.editMode = true;
          this.inprogress = false;
          this.$Progress.finish();
        })
        .catch(() => {
          this.editMode = false;
          this.inprogress = false;
          this.form.reset();
          this.$Progress.fail();
          Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "Something went wrong!",
            footer: "<a href='/users'>Let me redo again</a>"
          });
        });
    }
  },
  mounted() {
    this.inprogress = false;

    this.getGroups();
  }
};
</script>
