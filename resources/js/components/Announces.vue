<template>
  <div>
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Annoucements</h1>
          </div>
          <!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item">
                <a href="#">Home</a>
              </li>
              <li class="breadcrumb-item active">Annoucements</li>
            </ol>
          </div>
          <!-- /.col -->
        </div>
      </div>
    </div>
    <!-- ./content-header -->
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-12">
            <div class="card card-default">
              <div class="card-header">
                <h3 class="card-title">
                  <i class="fas fa-bullhorn"></i>
                  News
                </h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body bg-gray-light">
                <div class="d-flex justify-content-end text-right">
                  <pagination
                    :options="pgTable[0].options"
                    :records="pgTable[0].records"
                    @paginate="getListData"
                    v-model="pgTable[0].page"
                    :per-page="pgTable[0].perpage"
                  ></pagination>
                </div>
                <div class="row" v-for="news in pgTable[tabIndex].news" v-bind:key="news.id">
                  <div class="col-12">
                    <p class="text-sm">
                      Created on:
                      {{
                      news.created_at
                      | humanDateTime
                      }}
                    </p>
                    <div class="callout elevation-2" v-bind:class="'callout-' + news.color">
                      <p class="text-sm font-italic text-gray">{{ news.validFrom | humanDate }}</p>
                      <p v-html="news.description"></p>
                      <p class="text-sm text-gray">{{ news.title }}</p>
                      <p
                        v-show="news.showauthor"
                        class="text-sm font-italic text-gray"
                      >By: {{ news.author }}</p>
                    </div>
                  </div>
                </div>
              </div>
              <!-- /.card-body -->
              <div class="card-footer pb-0">
                <div class="d-flex justify-content-end text-right">
                  <pagination
                    :options="pgTable[0].options"
                    :records="pgTable[0].records"
                    @paginate="getListData"
                    v-model="pgTable[0].page"
                    :per-page="pgTable[0].perpage"
                  ></pagination>
                </div>
              </div>
              <!-- ./card-footer -->
            </div>
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
      tabIndex: 0,
      pgTable: [
        {
          news: [],
          uri: "api/news?",
          page: 1,
          perpage: 3,
          records: 0,
          options: {
            chunksNavigation: scroll,
            texts: {
              count: "|||"
            }
          }
        }
      ],
      selSince: "upto2mths"
    };
  },
  methods: {
    getListData(page) {
      if (this.$Role.isAdminOrUser()) {
        let uri =
          this.pgTable[this.tabIndex].uri +
          "t=" +
          this.selSince +
          "&up=" +
          this.pgTable[this.tabIndex].perpage +
          "&page=" +
          page;

        axios.get(uri).then(({ data }) => {
          this.pgTable[this.tabIndex].news = data.data;
          this.pgTable[this.tabIndex].records = data.total;
          this.pgTable[this.tabIndex].page = data.current_page;
          this.pgTable[this.tabIndex].perpage = parseInt(data.per_page);
        });
      }
    }
  },
  mounted() {
    this.getListData(1);
  }
};
</script>
