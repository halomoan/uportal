<template>
  <div>
    <div v-if="$Role.isAdmin()">
      <section class="content-header pb-1">
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
        <!-- /.container-fluid -->
      </section>

      <!-- Main content -->
      <section class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-12">
              <div class="card card-default">
                <div class="card-header">
                  <a href="#" @click.prevent="createNews">
                    <i class="fa far fa-file"></i> Create
                  </a>
                  <div class="card-tools">
                    <div class="input-group input-group-sm" style="width: 250px;">
                      <input
                        v-model="searchText"
                        type="text"
                        name="table_search"
                        class="form-control float-right"
                        placeholder="Search"
                        @keyup.enter="searchTable"
                      />

                      <div class="input-group-append">
                        <button type="button" class="btn btn-default" @click="searchTable">
                          <i class="fas fa-search"></i>
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
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
                        <a
                          href
                          class="fa fa-edit pl-3"
                          @click.prevent="editNews(news.id)"
                        ></a>
                        |
                        <a
                          href
                          class="fa fa-trash text-red"
                          @click.prevent="deleteNews(news.id)"
                        ></a>
                      </p>
                      <div class="callout callout-danger elevation-2">
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
                <!-- ./card-body -->
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
              <!-- /.card -->
            </div>
          </div>
          <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
      </section>
    </div>
    <div v-if="!$Role.isAdminOrUser()">
      <not-found></not-found>
    </div>
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
          uri: "api/news?page=",
          page: 1,
          perpage: 10,
          records: 0,
          options: {
            texts: {
              count: "|||"
            }
          }
        }
      ],
      searchText: ""
    };
  },
  methods: {
    createNews() {
      this.$router.push({ path: "/newsd", query: {} });
    },
    editNews(id) {
      this.$router.push({ path: "/newsd", query: { newsId: id } });
    },
    deleteNews(id) {
      if (this.$Role.isAdmin()) {
        Swal.fire({
          title: "Are you sure?",
          text: "You won't be able to revert this!",
          icon: "warning",
          showCancelButton: true,
          confirmButtonColor: "#3085d6",
          cancelButtonColor: "#d33",
          confirmButtonText: "Yes, delete it!"
        }).then(result => {
          //Send request to the server
          if (result.value) {
            axios
              .delete("api/news/" + id)
              .then(() => {
                if (result.value) {
                  Swal.fire("Deleted!", "The group been deleted.", "success");
                  this.getListData(this.pgTable[this.tabIndex].page);
                }
              })
              .catch(error => {
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
    getListData(page) {
      if (this.$Role.isAdminOrUser()) {
        let uri = this.pgTable[this.tabIndex].uri + page;

        axios.get(uri).then(({ data }) => {
          this.pgTable[this.tabIndex].news = data.data;
          this.pgTable[this.tabIndex].records = data.total;
          this.pgTable[this.tabIndex].page = data.current_page;
          this.pgTable[this.tabIndex].perpage = data.per_page;
        });
      }
    },
    searchTable() {
      if (this.searchText) {
        this.pgTable[this.tabIndex].uri =
          "api/news?" + "&q=" + this.searchText + "&page=";
      } else {
        this.pgTable[this.tabIndex].uri = "api/news?" + "&page=";
      }
      this.$Progress.start();
      axios
        .get(this.pgTable[this.tabIndex].uri)
        .then(({ data }) => {
          this.pgTable[this.tabIndex].news = data.data;
          this.pgTable[this.tabIndex].records = data.total;
          this.pgTable[this.tabIndex].page = data.current_page;
          this.pgTable[this.tabIndex].perpage = data.per_page;
          this.$Progress.finish();
        })
        .catch(() => {
          Toast.fire({
            icon: "error",
            title: "Something is wrong. Failed to search."
          });
          this.$Progress.fail();
        });
    }
  },
  mounted() {
    Fire.$on("GLOBAL_SEARCH", () => {
      this.searchText = this.$parent.searchText;
    });
    this.getListData(1);
  }
};
</script>
