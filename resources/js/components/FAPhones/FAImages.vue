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
                        <label for="company" class="col-sm-2 col-form-label">Company</label>
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
                    </div>
                    <div class="col-6">
                      <button type="button" class="btn btn-info" @click.prevent="refrehImage()">
                        <span>Refresh</span>
                      </button>
                    </div>
                  </div>
                  <br />
                  <div class="row">
                    <div class="col-6">
                      <ul class="list-unstyled">
                        <li class="media" v-for="item in leftImages" v-bind:key="item.id">
                          <img
                            class="mr-3"
                            :src="item.url"
                            alt="Fixed asset image"
                            width="60px"
                            height="60px"
                          />
                          <div class="media-body">
                            <h5 class="mt-0 mb-1">{{item.id}}</h5>
                            {{item.desc}}
                          </div>
                        </li>
                      </ul>
                    </div>
                    <div class="col-6">
                      <ul class="list-unstyled">
                        <li class="media" v-for="item in rightImages" v-bind:key="item.id">
                          <img
                            class="mr-3"
                            :src="item.url"
                            alt="Fixed asset image"
                            width="80px"
                            height="80px"
                          />
                          <div class="media-body">
                            <h5 class="mt-0 mb-1">{{item.id}}</h5>
                            {{item.desc}}
                          </div>
                        </li>
                      </ul>
                    </div>
                  </div>
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
      baseUri: "api/facode?qtype=image",
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
      let filter = "";

      axios.get(this.baseUri + filter + "&page=" + page).then(({ data }) => {
        this.images = data.data;
        this.pgImages.records = data.total;
        this.pgImages.page = data.current_page;
        this.pgImages.perpage = data.per_page;
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
  },
  mounted() {
    this.getCompanies();
    this.getTableData(1);
  },
};
</script>
