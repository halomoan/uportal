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
                      <a class="nav-link" href="#fixedasset" data-toggle="tab">Fixed Asset</a>
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
                              :class="{'is-invalid': form.errors.has('name')}"
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
                              :class="{'is-invalid': form.errors.has('company')}"
                              id="company"
                              v-model="form.company"
                              name="company"
                              placeholder="Company Name"
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
                              :class="{'is-invalid': form.errors.has('email')}"
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
                                :class="{'is-invalid': form.errors.has('password')}"
                                name="password"
                                v-model="form.password"
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
                                :class="{'is-invalid': form.errors.has('repassword')}"
                                name="repassword"
                                v-model="form.repassword"
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
                              :class="{'is-invalid': form.errors.has('type')}"
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
                      <div class="tab-pane" id="fixedasset">
                        <div class="form-group form-check">
                          <input
                            type="checkbox"
                            class="form-check-input"
                            id="allowfa"
                            v-model="form.fauser"
                          />
                          <label class="form-check-label" for="allowfa">Fixed Asset Module</label>
                        </div>
                        <div class="form-group row">
                          <label for="group" class="col-sm-2 col-form-label">SAP System</label>
                          <div class="col-sm-10">
                            <select
                              name="fagroup"
                              v-model="form.fagroup"
                              id="fagroup"
                              class="form-control"
                              :class="{'is-invalid': form.errors.has('fagroup')}"
                            >
                              <option
                                v-for="fagroup in fagroups"
                                :key="fagroup.id"
                                :value="fagroup.id"
                              >{{ fagroup.name }}</option>
                            </select>
                            <has-error :form="form" field="fagroup"></has-error>
                          </div>
                        </div>
                        <company-select
                          ref="sel"
                          v-on:CompanyList="setFACocode"
                          :id="userId"
                          url="api/facompany"
                        ></company-select>
                      </div>
                      <!-- /.tab-pane -->
                      <div class="tab-pane" id="group">
                        <user-group-select
                          ref="group"
                          v-on:userGroupList="setUserGroup"
                          :id="userId"
                          type="group"
                          url="api/group"
                        ></user-group-select>
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
                  <button type="button" class="btn btn-default" @click.prevent="goBack">Cancel</button>
                  <button
                    type="button"
                    class="btn btn-info float-right"
                    @click.prevent="editMode ? editUser() : createUser()"
                  >
                    <span v-show="!editMode">Create</span>
                    <span v-show="editMode">Modify</span>
                  </button>
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
import CompanySelect from "../Common/CompanySelect.vue";
import UserGroupSelect from "../Common/UserGroupSelect.vue";

export default {
  components: {
    CompanySelect,
    UserGroupSelect,
  },
  data() {
    return {
      form: new Form({
        id: "",
        name: "",
        email: "",
        type: "person",
        password: "",
        repassword: "",
        groups: [],
        urole: "",
        photo: "",
        fauser: false,
        facocodes: [],
        fagroup: "",
      }),
      inprogress: false,
      editMode: false,
      userId: 0,
      facocodes: null,
      userGroup: null,
      fagroups: [],
    };
  },
  methods: {
    editUser() {
      this.form.groups = _.map(this.userGroup, "id");
      this.form.facocodes = _.map(this.facocodes, "CoCode");

      this.$Progress.start();
      this.inprogress = true;

      this.form
        .put("api/user/" + this.form.id)
        .then(() => {
          this.$Progress.finish();
          this.inprogress = false;
          Toast.fire({
            icon: "success",
            title: "User modified successfully",
          });
          this.goBack();
        })
        .catch((e) => {
          this.$Progress.fail();
          this.inprogress = false;
          this.catchPrompt(e);
        });
    },
    createUser() {
      this.form.groups = _.map(this.userGroup, "id");
      this.form.facocodes = _.map(this.facocodes, "CoCode");

      this.$Progress.start();
      this.inprogress = true;

      this.form
        .post("api/user")
        .then(({ data }) => {
          this.$Progress.finish();
          this.inprogress = false;
          Toast.fire({
            icon: "success",
            title: "User created successfully",
          });

          this.goBack();
        })
        .catch((e) => {
          this.$Progress.fail();
          this.inprogress = false;
          this.catchPrompt(e);
        });
    },
    goBack() {
      window.history.length > 1
        ? this.$router.go(-1)
        : this.$router.push("/users");
    },

    getUserData(userId) {
      this.inprogress = true;
      this.$Progress.start();
      axios
        .get("api/user/" + userId)
        .then(({ data }) => {
          this.form = new Form(data);

          if (data.groups && data.groups.length > 0) {
            this.$refs.group.setSelectedList(data);
            this.userGroup = data.groups;
          } else {
            this.$refs.group.getAvailRcptList(true);
          }

          //Get Selected FA

          this.$refs.sel.getSelectedList(userId);

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
            footer: "<a href='/users'>Let me redo again</a>",
          });
        });
    },
    getfagroups(init) {
      axios.get("api/group?t=barcd").then(({ data }) => {
        this.fagroups = data;
        if (init && this.fagroups.length > 0) {
          this.form.fagroup = this.fagroups[0].id;
        }
      });
    },
    setFACocode(data) {
      this.facocodes = data;
    },
    setUserGroup(data) {
      this.userGroup = data;
    },
    catchPrompt(e) {
      let message = e.response.data.message;
      const errors = e.response.data.errors;

      for (const error in errors) {
        if (error === "groups") {
          for (const msg in errors[error]) {
            message = errors[error][msg];
          }
        }
      }

      if (message) {
        Swal.fire("Failed!", message, "warning");
      } else {
        Swal.fire("Failed!", "There is something wrong.", "warning");
      }
    },
  },
  mounted() {
    this.inprogress = false;
    this.userId = this.$route.query.userId;

    if (this.userId) {
      this.getUserData(this.userId);
      this.getfagroups(false);
    } else {
      this.editMode = false;
      this.$refs.group.getAvailRcptList(true);
      this.getfagroups(true);
      this.form.reset();
    }
  },
};
</script>
