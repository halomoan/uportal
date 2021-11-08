<template>
  <div>
    <div v-if="$Role.isAdmin()">
      <section class="content-header pb-1">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1>
                <span v-show="editMode">Edit Phone</span>
                <span v-show="!editMode">Create Phone</span>
              </h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item">
                  <a href="/faphones">Phones</a>
                </li>
                <li class="breadcrumb-item active">
                  <span v-show="editMode">Edit Phone</span>
                  <span v-show="!editMode">Create Phone</span>
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
                          <label for="company" class="col-sm-2 col-form-label">Company Code</label>
                          <div class="col-sm-10">
                            <select
                              id="cocode"
                              name="cocode"
                              class="form-control"
                              style="width: 100%;"
                              tabindex="-1"
                              aria-hidden="true"
                              v-model="company"
                            >
                              <option
                                v-for="item in companies"
                                v-bind:key="item.CoCode"
                                :value="item.CoCode"
                              >{{ item.CoCode}} - {{ item.Name }}</option>
                            </select>
                            <has-error :form="form" field="company"></has-error>
                          </div>
                        </div>
                        <div class="form-group row">
                          <label for="phoneid" class="col-sm-2 col-form-label">Phone ID</label>
                          <div class="col-sm-10">
                            <input
                              type="text"
                              minlength="16"
                              class="form-control"
                              autocomplete="off"
                              :class="{'is-invalid': form.errors.has('phoneid')}"
                              id="phoneid"
                              v-model="phoneid"
                              name="phoneid"
                              placeholder="Phone unique id"
                              required
                            />
                            <has-error :form="form" field="phoneid"></has-error>
                          </div>
                        </div>
                        <div class="form-group row">
                          <label for="group" class="col-sm-2 col-form-label">Group</label>
                          <div class="col-sm-10">
                            <select
                              name="group"
                              v-model="group"
                              id="group"
                              class="form-control"
                              :class="{'is-invalid': form.errors.has('group')}"
                            >
                              <option
                                v-for="group in groups"
                                :key="group.id"
                                :value="group.id"
                              >{{ group.name }}</option>
                            </select>
                            <has-error :form="form" field="group"></has-error>
                          </div>
                        </div>
                        <!-- </form> -->
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
                    @click.prevent="editMode ? editPhone() : createPhone()"
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
export default {
  data() {
    return {
      form: new Form({
        id: "",
        name: "",
        email: "",
        password: "",
        repassword: "",
        company: "",
        groups: [],
        urole: "user",
      }),
      phoneid: "",
      group: "",
      groups: [],
      company: null,
      companies: [],
      inprogress: false,
      editMode: false,
    };
  },
  methods: {
    validateForm() {
      var isValid = true;
      this.form.errors.clear();

      if (this.phoneid.length === 0) {
        this.form.errors.set("phoneid", "Please enter the phone unique id");
        isValid = false;
      } else if (this.phoneid.length < 16) {
        this.form.errors.set("phoneid", "Min. Length is 16");
        isValid = false;
      }

      if (this.form.name.length === 0) {
        this.form.errors.set("name", "Name is required");
        isValid = false;
      }

      if (this.group.length === 0) {
        this.form.errors.set("group", "Group is required");
        isValid = false;
      }

      return isValid;
    },

    mapToForm() {
      if (this.validateForm()) {
        this.form.password = "Faconf!gN3w";
        this.form.repassword = "Faconf!gN3w";
        this.form.type = "phone";
        this.form.company = this.company;
        this.form.email = this.phoneid.trim() + "@uportal.test";
        this.form.fagroup = this.group;
        this.form.groups = [this.group];
        return true;
      }
      return false;
    },

    mapToScreen(data) {
      var email = data.email;
      const pos = email.search("@");
      if (pos > 0) {
        const emailDomain = email.slice(pos);
        this.phoneid = email.slice(0, -emailDomain.length);
      }
      if (data.fagroup) {
        this.group = data.fagroup;
      }

      this.company = data.company;
    },

    editPhone() {
      if (!this.mapToForm()) {
        return;
      }

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
        });
    },
    createPhone() {
      if (!this.mapToForm()) {
        return;
      }
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
          let message = e.response.data.message;
          const errors = e.response.data.errors;

          for (const error in errors) {
            if (error === "email") {
              // for (const msg in errors[error]) {
              //   message = errors[error][msg];
              // }
              message = "The phone has been registered";
            }
          }

          if (message) {
            Swal.fire("Failed!", message, "warning");
          } else {
            Swal.fire("Failed!", "There is something wrong.", "warning");
          }
        });
    },
    goBack() {
      window.history.length > 1
        ? this.$router.go(-1)
        : this.$router.push("/Phones");
    },

    getPhoneData(userId) {
      this.inprogress = true;
      this.$Progress.start();
      axios
        .get("api/user/" + userId)
        .then(({ data }) => {
          this.form = new Form(data);
          this.mapToScreen(data);

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
            footer: "<a href='/Phones'>Let me redo again</a>",
          });
        });
    },
    getGroups() {
      axios.get("api/group?t=barcd").then(({ data }) => {
        this.groups = data;
        const userId = this.$route.query.userId;
        if (userId) {
          this.getPhoneData(userId);
        }
      });
    },
    getCompanies() {
      axios.get("api/company").then(({ data }) => {
        this.companies = data;
        if (this.companies && this.companies.length > 0) {
          this.company = this.companies[0].CoCode;
        }
      });
    },
    // getFAConfig() {
    //   axios.get("api/fabarcode?q=config").then(({ data }) => {
    //     this.groups = data;
    //     const userId = this.$route.query.userId;
    //     if (userId) {
    //       this.getPhoneData(userId);
    //     }
    //   });
    // },
  },
  mounted() {
    this.inprogress = false;
    this.getCompanies();
    this.getGroups();
  },
};
</script>
