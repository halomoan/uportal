<template>
  <div>
    <div class="card card-olive">
      <div class="card-header">
        <h3 class="card-title">Import Users</h3>
      </div>
      <!-- /.card-header -->
      <div class="card-body">
        <div class="overlay-wrapper">
          <div class="row">
            <div class="col-6">
              <div class="row">
                <div class="col-12">
                  <button class="btn btn-block btn-default" @click="getTemplate">Download Template</button>
                </div>
              </div>
              <div class="row">
                <div class="col-6">
                  <button
                    class="btn btn-block bg-gradient-green mt-3"
                    @click="gotoUsers"
                  >Manage Users</button>
                </div>
                <div class="col-6">
                  <button class="btn btn-block bg-gradient-warning mt-3" @click="showLog">Log Viewer</button>
                </div>
              </div>
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
          <div class="overlay" v-if="inprogress.main">
            <i class="fas fa-3x fa-sync-alt fa-spin"></i>
            <div class="text-bold pl-2">Loading...</div>
          </div>
        </div>
        <!-- ./overlay-wrapper -->
      </div>
      <!-- /.card-body -->
      <!-- Log Modal -->
      <div class="modal" tabindex="-1" role="dialog" id="logModal">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Log Viewer</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>

            <div class="modal-body">
              <div class="overlay-wrapper text-center">
                <overlay-scrollbars
                  style="max-height: 350px"
                  :options="{ scrollbars: { autoHide: 'scroll' } }"
                >
                  <table class="table text-nowrap table-hover">
                    <thead class="thead-dark">
                      <tr>
                        <th>No</th>
                        <th>Date</th>
                        <th class="text-left">Text</th>
                      </tr>
                    </thead>

                    <tbody>
                      <tr v-for="(log,index) in logs" :key="index">
                        <td>{{index + 1}}</td>
                        <td>{{log.created_at | humanDate}}</td>
                        <td class="text-left">{{log.text}}</td>
                      </tr>
                    </tbody>
                  </table>
                  <div class="w-100 text-center" v-if="logs.length === 0">- Empty -</div>
                </overlay-scrollbars>
                <div class v-if="inprogress.log">
                  <i class="fas fa-3x fa-sync-alt fa-spin"></i>
                  <div class="text-bold pl-2">Loading...</div>
                </div>
              </div>
              <!-- ./overlay-wrapper -->
            </div>
            <!-- ./modal-body -->
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
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
      file: null,
      inprogress: {
        main: false,
        log: false
      },
      logs: []
    };
  },
  methods: {
    gotoUsers() {
      this.$router.push({ path: "/users" });
    },
    showLog() {
      $("#logModal").modal("toggle");
      this.inprogress.log = true;
      this.logs = [];
      axios.get("api/file?logger=users").then(resp => {
        this.logs = resp.data;
        this.inprogress.log = false;
      });
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
              Swal.fire("That was not a YES.", "We Canceled This Process");
            }
          }
        });
      } else {
        Swal.fire({
          title: "Confirmation",
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
      this.inprogress.main = true;
      axios
        .post("api/putfile", formData, {
          headers: {
            "Content-Type": "multipart/form-data"
          }
        })
        .then(resp => {
          this.inprogress.main = false;
        })
        .catch(err => {
          this.inprogress.main = false;
        });
    }
  }
};
</script>
