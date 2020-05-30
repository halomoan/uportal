<template>
  <div>
    <div v-if="$Role.isAdmin()">
      <div class="content-header pb-1">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1>News</h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item">
                  <a href="#">Home</a>
                </li>
                <li class="breadcrumb-item active">News</li>
              </ol>
            </div>
          </div>
        </div>
      </div>
      <!-- ./content-header -->
      <div class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-12">
              <div class="card">
                <div class="card-header">
                  <h3 class="card-title">Editor</h3>

                  <div class="card-tools">
                    <button
                      type="button"
                      class="btn btn-tool"
                      data-card-widget="collapse"
                      data-toggle="tooltip"
                      title="Collapse"
                    >
                      <i class="fas fa-minus"></i>
                    </button>
                  </div>
                </div>
                <div class="card-body card-cyan">
                  <div class="row">
                    <div class="col-6">
                      <div class="form-group row">
                        <label for="title" class="col-sm-2 col-form-label">Title</label>
                        <div class="col-sm-10">
                          <input
                            type="text"
                            class="form-control"
                            autocomplete="off"
                            v-model="form.title"
                            :class="{
                                                            'is-invalid': form.errors.has(
                                                                'title'
                                                            )
                                                        }"
                            id="title"
                            name="title"
                            placeholder="Please give a title"
                            required
                          />
                          <has-error :form="form" field="title"></has-error>
                        </div>
                      </div>
                    </div>
                    <div class="col-6">
                      <div class="form-group row">
                        <label class="col-sm-2 col-form-label width">Validity:</label>
                        <date-range-picker
                          ref="picker"
                          :locale-data="
                                                        dPickerRange.locale
                                                    "
                          v-model="dateRange"
                          @update="dateRangeUpdate"
                          :timePicker="true"
                          :dateFormat="
                                                        dPickerRange.dateFormat
                                                    "
                        >
                          <template v-slot:input="picker" style="min-width: 350px;">
                            {{
                            picker.startDate
                            | humanDate
                            }}
                            -
                            {{
                            picker.endDate
                            | humanDate
                            }}
                          </template>
                        </date-range-picker>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-6">
                      <div class="form-group row">
                        <label for="author" class="col-sm-2 col-form-label">Author</label>
                        <div class="col-sm-10">
                          <input
                            type="text"
                            class="form-control"
                            autocomplete="off"
                            v-model="form.author"
                            :disabled="
                                                            !form.showAuthor
                                                        "
                            :class="{
                                                            'is-invalid': form.errors.has(
                                                                'author'
                                                            )
                                                        }"
                            id="author"
                            name="author"
                            placeholder="Enter author name (optional)"
                          />
                          <has-error :form="form" field="author"></has-error>
                        </div>
                      </div>
                    </div>
                    <div class="col-6">
                      <div class="form-group pt-2">
                        <div class="form-check">
                          <input
                            class="form-check-input"
                            type="checkbox"
                            id="showAuthor"
                            v-model="
                                                            form.showAuthor
                                                        "
                            @change="
                                                            showAuthor($event)
                                                        "
                          />
                          <label class="form-check-label" for="showAuthor">Show Author</label>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-12">
                      <span class="news is-invalid" />
                      <has-error :form="form" field="description"></has-error>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-12">
                      <label class="mb-2 col-form-label">Output Preview:</label>
                      <button
                        class="btn btn-warning float-right btn-sm"
                        @click="showPreview"
                      >Preview</button>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-12">
                      <div class="callout callout-danger">
                        <p class="text-sm font-italic text-gray">
                          {{
                          preview.date | humanDate
                          }}
                        </p>
                        <p v-html="preview.description"></p>
                        <p class="text-sm text-gray">{{ preview.title }}</p>
                        <p
                          v-show="preview.showAuthor"
                          class="text-sm font-italic text-gray"
                        >By: {{ preview.author }}</p>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer" style="display: block;">
                  <button
                    v-show="editMode"
                    class="btn btn-primary float-right btn-sm"
                    @click="update"
                  >Update</button>
                  <button
                    v-show="!editMode"
                    class="btn btn-primary float-right btn-sm"
                    @click="submit"
                  >Submit</button>
                  <button
                    v-show="editMode"
                    class="btn btn-default float-right btn-sm mr-3"
                    @click="cancel"
                  >Cancel</button>
                  <button
                    v-show="!editMode"
                    class="btn btn-default float-right btn-sm mr-3"
                    @click="cancel"
                  >Cancel</button>
                </div>
                <!-- /.card-footer-->
                <div class="overlay" v-if="inprogress">
                  <i class="fas fa-3x fa-sync-alt fa-spin"></i>
                  <div class="text-bold pl-2">Loading...</div>
                </div>
              </div>
            </div>
          </div>
          <!-- ./row -->
        </div>
      </div>
      <!-- ./content -->
    </div>
    <div v-if="!$Role.isAdminOrUser()">
      <not-found></not-found>
    </div>
  </div>
</template>

<script>
import DateRangePicker from "vue2-daterange-picker";
import "vue2-daterange-picker/dist/vue2-daterange-picker.css";
import moment from "moment";

export default {
  components: { DateRangePicker },
  data() {
    let startDate = new Date();
    let endDate = new Date();
    endDate.setDate(endDate.getDate() + 6);
    return {
      editMode: false,
      inprogress: false,
      newsText: "",
      form: new Form({
        title: "",
        author: "",
        showAuthor: false
      }),
      dateRange: { startDate, endDate },
      dPickerRange: {
        locale: {
          direction: "ltr",
          format: "mm/dd/yyyy",
          separator: " - ",
          applyLabel: "Apply",
          cancelLabel: "Cancel",
          weekLabel: "W",
          customRangeLabel: "Custom Range",
          daysOfWeek: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"],
          monthNames: [
            "Jan",
            "Feb",
            "Mar",
            "Apr",
            "May",
            "Jun",
            "Jul",
            "Aug",
            "Sep",
            "Oct",
            "Nov",
            "Dec"
          ],
          firstDay: 0,
          format: "dd-mm-yyyy HH:MM:ss"
        }
      },
      preview: {
        date: this.today,
        title: "",
        description: "",
        author: "",
        showAuthor: false
      }
    };
  },
  methods: {
    disabledDate(time) {
      return time < this.yesterday;
    },
    showAuthor(e) {
      this.form.showAuthor = e.target.checked;
    },
    showPreview() {
      this.preview.date = this.dateRange.startDate;
      this.preview.title = this.form.title;
      this.preview.author = this.form.author;
      this.preview.description = $(".news").summernote("code");
      this.preview.showAuthor = this.form.showAuthor;
    },

    update() {
      if (this.$Role.isAdmin()) {
        Swal.fire({
          title: "Are you sure?",
          text: "You want to update the news?",
          icon: "question",
          showCancelButton: true,
          confirmButtonColor: "#3085d6",
          cancelButtonColor: "#d33",
          confirmButtonText: "Yes, Please Update"
        }).then(result => {
          if (result.value) {
            this.form.description = $(".news").summernote("code");
            this.form.validFrom = moment(this.dateRange.startDate).format();
            this.form.validTo = moment(this.dateRange.endDate).format();

            this.$Progress.start();
            this.inprogress = true;
            this.form
              .put("api/news/" + this.form.id)
              .then(data => {
                Toast.fire({
                  icon: "success",
                  title: "News updated successfully"
                });

                this.inprogress = false;
                this.$Progress.finish();

                this.goBack();
              })
              .catch(error => {
                this.inprogress = false;
                this.$Progress.fail();
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

    submit() {
      if (this.$Role.isAdmin()) {
        Swal.fire({
          title: "Are you sure?",
          text: "You want to create the news?",
          icon: "question",
          showCancelButton: true,
          confirmButtonColor: "#3085d6",
          cancelButtonColor: "#d33",
          confirmButtonText: "Yes, Please Create"
        }).then(result => {
          if (result.value) {
            this.form.description = $(".news").summernote("code");
            this.form.validFrom = this.dateRange.startDate;
            this.form.validTo = this.dateRange.endDate;
            this.$Progress.start();
            this.inprogress = true;
            this.form
              .post("api/news")
              .then(data => {
                Toast.fire({
                  icon: "success",
                  title: "News created successfully"
                });

                this.reset();

                this.inprogress = false;
                this.$Progress.finish();
              })
              .catch(error => {
                this.inprogress = false;
                this.$Progress.fail();
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
    cancel() {
      this.goBack();
    },
    reset() {
      this.preview.date = this.today;
      this.preview.title = "";
      this.preview.author = "";
      this.preview.description = "";
      this.preview.showAuthor = false;
      $(".news").summernote("code", "");
      this.form.title = "";
      this.form.author = "";
      this.form.description = "";
      this.form.showAuthor = false;
    },
    goBack() {
      window.history.length > 1
        ? this.$router.go(-1)
        : this.$router.push("/users");
    },
    getNewsData(newsId) {
      this.inprogress = true;
      this.$Progress.start();
      axios
        .get("api/news/" + newsId)
        .then(({ data }) => {
          this.form.id = data.id;
          this.form.title = data.title;
          this.form.author = data.author;
          $(".news").summernote("code", data.description);
          this.form.showAuthor = data.showauthor;

          this.dateRange.startDate = new Date(data.validFrom);
          this.dateRange.endDate = new Date(data.validTo);

          this.inprogress = false;
          this.$Progress.finish();
        })
        .catch(() => {
          this.inprogress = false;
          this.$Progress.fail();
        });
    },
    dateRangeUpdate(dates) {
      this.dateRange.startDate = dates.startDate;
      this.dateRange.endDate = dates.endDate;
    }
  },
  mounted() {
    $(function() {
      $(".news").summernote({
        placeholder: "Hi Write something on here...",
        tabsize: 2,
        height: 420
      });
    });

    //New or Edit News
    const newsId = this.$route.query.newsId;
    if (newsId) {
      this.editMode = true;
      this.getNewsData(newsId);
    } else {
      this.editMode = false;
    }
  }
};
</script>
<style></style>
