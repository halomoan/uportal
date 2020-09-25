<template>
  <div>
    <div v-if="$Role.isAdminOrUser()">
      <div class="content-header pb-1">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1>Fixed Asset Images</h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item">
                  <a href="#">Home</a>
                </li>
                <li class="breadcrumb-item active">View Images</li>
              </ol>
            </div>
          </div>
        </div>
      </div>
      <!-- ./content-header -->
      <div class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-lg-12 col-12">
              <div class="card">
                <div class="card-body">
                  <div class="row">
                    <div class="col-6">
                      <div class="form-group row">
                        <label for="company" class="col-sm-2 col-form-label"
                          >Company</label
                        >
                        <div class="col-sm-10">
                          <select
                            id="cocode"
                            name="cocode"
                            class="form-control"
                            style="width: 100%"
                            tabindex="-1"
                            aria-hidden="true"
                            v-model="company"
                          >
                            <option
                              v-for="item in companies"
                              v-bind:key="item.CoCode"
                              :value="item.CoCode"
                            >
                              {{ item.CoCode }} - {{ item.Name }}
                            </option>
                          </select>
                          <has-error :form="form" field="company"></has-error>
                        </div>
                      </div>
                    </div>
                    <div class="col-6">
                      <button
                        type="button"
                        class="btn btn-info"
                        @click.prevent="refrehImage()"
                      >
                        <span>Refresh</span>
                      </button>
                    </div>
                  </div>
                  <br />
                  <div class="row">
                    <div class="col-6">
                      <ul class="list-unstyled">
                        <li
                          class="media mb-3 border-bottom border-right"
                          v-for="item in leftImages"
                          v-bind:key="item.id"
                        >
                          <img
                            class="mr-3 img-fluid"
                            :src="item.url"
                            alt="Fixed asset image"
                            width="80px"
                            height="80px"
                            @click="zoomImage(item)"
                          />
                          <div class="media-body">
                            <h5 class="mt-0 mb-1">
                              {{ item.id }}
                              <span
                                v-show="item.hasNew"
                                class="badge badge-danger"
                                >!</span
                              >
                            </h5>
                            {{ item.desc }}
                            <br />
                            <b>Inventory:</b>
                            {{ item.qty }}
                            <br />
                            <b>Location:</b>
                            {{ item.loc }}
                          </div>
                        </li>
                      </ul>
                    </div>
                    <div class="col-6">
                      <ul class="list-unstyled">
                        <li
                          class="media mb-3 border-bottom border-right"
                          v-for="item in rightImages"
                          v-bind:key="item.id"
                        >
                          <img
                            class="mr-3 img-fluid"
                            :src="item.url"
                            alt="Fixed asset image"
                            width="80px"
                            height="80px"
                            @click="zoomImage(item)"
                          />
                          <div class="media-body">
                            <h5 class="mt-0 mb-1">
                              {{ item.id }}
                              <span
                                v-show="item.hasNew"
                                class="badge badge-danger"
                                >!</span
                              >
                            </h5>
                            {{ item.desc }}
                            <br />
                            <b>Inventory:</b>
                            {{ item.qty }}
                            <br />
                            <b>Location:</b>
                            {{ item.loc }}
                          </div>
                        </li>
                      </ul>
                    </div>
                  </div>
                </div>
                <!-- ./card-body -->
                <div class="card-footer pb-0">
                  <div class="d-flex justify-content-end text-right">
                    <pagination
                      :records="pgImages.records"
                      @paginate="getTableData"
                      v-model="pgImages.page"
                      :per-page="pgImages.perpage"
                    ></pagination>
                  </div>
                </div>
                <!-- /.card-footer -->
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
    <!-- ./ if  -->
  </div>
</template>

<script>
export default {
  data() {
    return {
      baseUri: "api/facode",
      images: null,
      form: new Form({
        company: "",
      }),
      pgImages: {
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
      inprogress: false,
      company: null,
      companies: [],
    };
  },
  computed: {
    leftImages: function () {
      var oImages = this.images;
      if (!this.images) return [];
      return this.images.filter(function (image) {
        return oImages.indexOf(image) % 2 === 0;
      });
    },
    rightImages: function () {
      var oImages = this.images;
      if (!this.images) return [];
      return this.images.filter(function (image) {
        return oImages.indexOf(image) % 2 === 1;
      });
    },
  },
  methods: {
    getTableData(page) {
      let filter = "&cocode=" + this.company;
      this.inprogress = true;
      axios
        .get(this.baseUri + "?qtype=image" + filter + "&page=" + page)
        .then(({ data }) => {
          this.images = data.data;
          this.pgImages.records = data.total;
          this.pgImages.page = data.current_page;
          this.pgImages.perpage = data.per_page;
          this.inprogress = false;
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
    refrehImage() {
      this.getTableData(1);
    },
    zoomImage(item) {
      if (item.hasNew) {
        Swal.fire({
          width: 600,
          input: "radio",
          inputOptions: { old: "Keep Current", new: "Keep New" },
          inputValidator: (value) => {
            if (!value) {
              return "You need to choose Keep Current or Keep New!";
            }
          },
          confirmButtonText: "Change",
          cancelButtonText: "Close",

          showCancelButton: true,
          html: `
           <div class="row">
            <div class="col-6 pr-2 border border-success">
              <img src="${item.url}"/>
              <b>Current Image</b>
            </div>
            <div class="col-6 border border-success">
              <img src="${item.urlNew}"/>
              <b>New Image</b>
            </div>
           </div>
           <div class="row pt-2">
           <div class="col-12">
           ${item.desc}
           </div>
           </div>

          `,
          title: item.id,
        }).then((result) => {
          if (result.isConfirmed) {
            var cmd = null;
            if (result.value === "old") {
              cmd = { id: item.id, keepNew: false, keepCurrent: true };
            } else {
              cmd = { id: item.id, keepNew: true, keepCurrent: false };
            }
            console.log(cmd);
            axios.put(this.baseUri + "/" + item.id, cmd).then(({ data }) => {
              console.log(data);
            });
          }
        });
      } else {
        Swal.fire({
          imageUrl: item.url,
          imageAlt: "Fixed Asset Image",
          title: item.id,
          text: item.desc,
        });
      }
    },
  },
  mounted() {
    this.getCompanies();
    this.getTableData(1);
  },
};
</script>
