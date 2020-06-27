<template>
  <div class="card card-olive">
    <div class="card-header">
      <h3 class="card-title">Import Users</h3>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
      <div class="row">
        <div class="col-6">
          <button class="btn btn-block bg-gradient-fuchsia" @click="getTemplate">Download Template</button>
          <button class="btn btn-block bg-gradient-green mt-3" @click="gotoUsers">Manage Users</button>
        </div>
        <div class="col-6">
          <div class="form-group">
            <div class="input-group input-group-sm">
              <div class="custom-file">
                <input
                  type="file"
                  @change="setFile"
                  class="custom-file-input"
                  id="userFile"
                  accept=".csv"
                />
                <label class="custom-file-label" id="lblUserFile" for="userFile">Choose file</label>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-6">
              <button
                class="btn btn-block bg-gradient-primary"
                @click="uploadFile('clear')"
              >Clear And Upload Users</button>
            </div>
            <div class="col-6">
              <button
                class="btn btn-block bg-gradient-navy"
                @click="uploadFile('append')"
              >Upload Users And Append</button>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- /.card-body -->
    <div class="overlay" v-if="inprogress">
      <i class="fas fa-3x fa-sync-alt fa-spin"></i>
      <div class="text-bold pl-2">Loading...</div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      file: null,
      inprogress: false
    };
  },
  methods: {
    gotoUsers() {
      this.$router.push({ path: "/users" });
    },
    getTemplate() {
      axios({
        url: "api/getfile/users",
        method: "GET"
      }).then(resp => {
        var fileURL = window.URL.createObjectURL(new Blob([resp.data]));
        var fileLink = document.createElement("a");

        fileLink.href = fileURL;
        fileLink.setAttribute("download", "users.csv");
        document.body.appendChild(fileLink);

        fileLink.click();
      });
    },
    setFile(e) {
      this.file = e.target.files[0];

      if (this.file) {
        const name = this.file["name"];
        const ext = name.substring(name.lastIndexOf(".") + 1);
        if (ext === "csv") {
          $("#lblUserFile").html(name);
        } else {
          Swal.fire("Wrong File Extension!", "Please select a .csv file");
          $("#lblUserFile").html("Choose File");
        }
      } else {
        $("#lblUserFile").html("Choose File");
      }
    },
    uploadFile(mode) {
      if (!this.file) {
        Swal.fire("No File Selected!", "Please select a file");
        return;
      }

      if (mode === "clear") {
        Swal.fire({
          title: "Are you sure to clear prior data and re-upload?",
          text: "Please Enter YES to confirm",
          input: "text",
          inputAttributes: {
            autocapitalize: true
          },
          showCancelButton: true,
          confirmButtonText: "Confirm"
        }).then(result => {
          if (result.value) {
            if (result.value === "YES") {
              const formData = new FormData();
              formData.append("fmode", "clear");
              formData.append("file", this.file);
              formData.append("fcat", "users");
              this._uploadFile(formData);
            } else {
              Swal.fire("You've Enter Otherwise.", "We Canceled This Process");
            }
          }
        });
      } else {
        Swal.fire({
          title: "Confirm",
          text: "Please Confirm To Upload",
          showCancelButton: true,
          confirmButtonText: "Confirm"
        }).then(result => {
          if (result.value) {
            const formData = new FormData();
            formData.append("fmode", "append");
            formData.append("file", this.file);
            formData.append("fcat", "users");
            this._uploadFile(formData);
          }
        });
      }
    },
    _uploadFile(formData) {
      axios
        .post("api/putfile", formData, {
          headers: {
            "Content-Type": "multipart/form-data"
          }
        })
        .then(function() {
          console.log("SUCCESS!!");
        })
        .catch(function() {
          console.log("FAILURE!!");
        });
    }
  }
};
</script>
