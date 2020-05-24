<template>
  <div class="container-fluid">
    <div class="row justify-content-center mt-2">
      <div class="col-md-12">
        <!-- Add the bg color to the header using any of the bg-* classes -->
        <div class="bg-white pb-3">
          <div class="row">
            <div class="col-md-6">
              <img class="mt-2 ml-2" :src="getPhoto()" alt />
            </div>
            <div class="col-md-6 d-flex flex-column align-items-end pr-3">
              <h3 class="widget-user-username text-right text-blue" v-html="form.company"></h3>
              <h5 class="widget-user-desc text-right" v-html="form.name"></h5>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row justify-content-center">
      <div class="col-md-12">
        <div class="card elevation-2">
          <div class="card-header">
            <ul class="nav nav-pills">
              <li class="nav-item">
                <a class="nav-link active" href="#general" data-toggle="tab">General</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#billing" data-toggle="tab">Billing</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#password" data-toggle="tab">Password</a>
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

                  <div class="form-group row">
                    <label for="photo" class="col-sm-2 col-form-label">Photo</label>
                    <div class="col-sm-10">
                      <div class="input-group">
                        <div class="custom-file">
                          <input type="file" @change="setFile" class="custom-file-input" id="photo" />
                          <label class="custom-file-label" for="photo" id="lblPhoto">Choose file</label>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!-- </form> -->
                </div>
                <!-- /.tab-pane -->
                <div class="tab-pane" id="billing">
                  <form class="form-horizontal" @submit.prevent="createUser">
                    <div class="form-group row">
                      <label for="billaddr" class="col-sm-2 col-form-label">Billing Address</label>
                      <div class="col-sm-10">
                        <textarea
                          rows="5"
                          class="form-control"
                          :class="{'is-invalid': form.errors.has('billaddr')}"
                          id="billaddr"
                          v-model="form.billaddr"
                          name="billaddr"
                          placeholder="billaddr"
                        />
                        <has-error :form="form" field="billaddr"></has-error>
                      </div>
                    </div>
                  </form>
                </div>
                <!-- /.tab-pane -->
                <div class="tab-pane" id="password">
                  <div class="form-group row">
                    <label for="password" class="col-sm-2 col-form-label">Current Password</label>
                    <div class="col-sm-10">
                      <input
                        type="password"
                        class="form-control"
                        id="curpassword"
                        :class="{'is-invalid': form.errors.has('curpassword')}"
                        name="curpassword"
                        v-model="form.curpassword"
                        placeholder="Current Password"
                        required
                      />
                      <has-error :form="form" field="curpassword"></has-error>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="password" class="col-sm-2 col-form-label">New Password</label>
                    <div class="col-sm-10">
                      <input
                        type="password"
                        class="form-control"
                        id="password"
                        :class="{'is-invalid': form.errors.has('password')}"
                        name="password"
                        v-model="form.password"
                        placeholder="New Password"
                        required
                      />
                      <has-error :form="form" field="password"></has-error>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="repassword" class="col-sm-2 col-form-label">Re-Type Password</label>
                    <div class="col-sm-10">
                      <input
                        type="password"
                        class="form-control"
                        id="repassword"
                        :class="{'is-invalid': form.errors.has('repassword')}"
                        name="repassword"
                        v-model="form.repassword"
                        placeholder="Re-type New Password"
                        required
                      />
                      <has-error :form="form" field="repassword"></has-error>
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
            <button type="button" class="btn btn-success" @click.prevent="updateProfile">
              <span v-show="editMode">Modify</span>
            </button>
            <button type="button" class="btn btn-default float-right" @click.prevent="goBack">Cancel</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      form: new Form({}),
      inprogress: false,
      photo: "",
      editMode: true,
      company: "ABC"
    };
  },
  methods: {
    getPhoto() {
      //return this.photo;
      const isBase64 = /^data:image\/[a-z]+;base64/gi;
      if (isBase64.test(this.photo)) {
        return this.photo;
      } else {
        return "/storage/" + this.photo;
      }
    },
    setFile(e) {
      let file = e.target.files[0];
      if (file) {
        $("#lblPhoto").html(file["name"]);
      } else {
        $("#lblPhoto").html("Choose File");
        this.form.photo = null;
        return;
      }
      let reader = new FileReader();

      let limit = 1024 * 1024 * 2;
      if (file["size"] > limit) {
        Swal.fire({
          type: "error",
          title: "Oops...",
          text:
            "You are uploading a large file. Max photo size allowed is 2 MB."
        });
        return false;
      }
      reader.onloadend = file => {
        this.form.photo = reader.result;
      };
      reader.readAsDataURL(file);
    },

    formChanged(objOne, objTwo) {
      return !!!_([objOne])
        .filter(objTwo)
        .size();
    },

    updateProfile() {
      let originalData = this.form.originalData;
      originalData.password = "*";
      originalData.repassword = "*";
      originalData.curpassword = "*";
      let newData = this.form;

      if (this.formChanged(newData, originalData)) {
        this.$Progress.start();
        this.inprogress = true;
        this.form
          .post("api/profile")
          .then(() => {
            this.$Progress.finish();
            this.inprogress = false;
            this.photo = this.form.photo;
            Toast.fire({
              icon: "success",
              title: "Profile modified successfully"
            });
            this.goBack();
          })
          .catch(() => {
            this.$Progress.fail();
            this.inprogress = false;
          });
      } else {
        //Swal.fire("Nothing was changed. No updates is required");
        Swal.fire({
          type: "info",
          title: "Oops...",
          text: "Nothing has changed. No updates is required"
        });
      }
    }
  },
  created() {
    axios.get("api/profile").then(({ data }) => {
      this.form = new Form(data);
      this.photo = this.form.photo;
    });
  }
};
</script>
