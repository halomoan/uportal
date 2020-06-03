.<template>
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
                <div class="card-header pb-0">
                  <div class="row">
                    <div class="col-4">
                      <a href="#" @click.prevent="createNews">
                        <i class="fa far fa-file"></i> Create
                      </a>
                    </div>
                    <div class="col-8">
                      <div class="d-flex justify-content-end">
                        <div class="form-group">
                          <select
                            class="form-control form-control-sm text-b"
                            v-model="selSince"
                            @change="selSinceChange"
                          >
                            <option value="today">Today</option>
                            <option value="thismth">This month</option>
                            <option value="upto2mths">Up To 2 Months</option>
                            <option value="upto1year">Up To 1 Year</option>
                            <option value="upto2years">Up To 2 Years</option>
                          </select>
                        </div>
                        <div class="form-group row">
                          <div class="col-xs-6">
                            <input
                              v-model="searchText"
                              type="text"
                              name="table_search"
                              class="form-control form-control-sm ml-4"
                              placeholder="Search title"
                              @keyup.enter="searchTable"
                            />
                          </div>
                          <div class="col-xs-6">
                            <button
                              type="button"
                              class="btn btn-default btn-sm"
                              @click="searchTable"
                            >
                              <i class="fas fa-search"></i>
                            </button>
                          </div>
                        </div>
                        <!-- ./card-tool -->
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
                          tooltip="Delete"
                          @click.prevent="deleteNews(news.id)"
                        ></a>
                        |
                        <a
                          href
                          class="fa fa-arrow-circle-up text-green"
                          tooltip="Publish"
                          @click.prevent="publishFor(news.id)"
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
      <!-- ./section -->
      <div class="modal fade" id="publishModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Publish - Set Recipient(s)</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <user-group-select
                ref="sel"
                v-on:userGroupList="setUserGroup"
                :id="newsId"
                url="api/news"
              ></user-group-select>
            </div>
            <!-- ./modal-body -->
            <div class="modal-footer">
              <button
                type="button"
                class="btn btn-secondary"
                data-dismiss="modal"
                @click="closePublishModal"
              >Close</button>
              <button type="button" class="btn btn-primary" @click="saveUserGroup">Save changes</button>
            </div>
            <!-- ./modal-footer -->
          </div>
        </div>
      </div>
      <!-- ./modal -->
    </div>
    <div v-if="!$Role.isAdminOrUser()">
      <not-found></not-found>
    </div>
  </div>
</template>

<script>
import UserGroupSelect from "./UserGroupSelect.vue";
export default {
  components: {
    UserGroupSelect
  },
  data() {
    return {
      tabIndex: 0,
      pgTable: [
        {
          news: [],
          uri: "api/news?",
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
      selSince: "today",
      searchText: "",
      userGroup: null,
      newsId: 0
    };
  },
  methods: {
    getListData(page) {
      if (this.$Role.isAdminOrUser()) {
        let uri =
          this.pgTable[this.tabIndex].uri +
          "t=" +
          this.selSince +
          "&page=" +
          page;

        axios.get(uri).then(({ data }) => {
          this.pgTable[this.tabIndex].news = data.data;
          this.pgTable[this.tabIndex].records = data.total;
          this.pgTable[this.tabIndex].page = data.current_page;
          this.pgTable[this.tabIndex].perpage = data.per_page;
        });
      }
    },
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
    closePublishModal() {
      this.$refs.sel.clearSetList();
    },
    publishFor(id) {
      this.newsId = id;
      $("#publishModal").modal("show");
    },
    selSinceChange() {
      this.getListData(1);
    },
    searchTable() {
      if (this.searchText) {
        this.pgTable[this.tabIndex].uri =
          "api/news?" +
          "&q=" +
          this.searchText +
          "&t=" +
          this.selSince +
          "&page=";
      } else {
        this.pgTable[this.tabIndex].uri =
          "api/news?" + "t=" + this.selSince + "&page=";
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
    },

    setUserGroup(data) {
      this.userGroup = data;
    },

    saveUserGroup() {
      if (this.$Role.isAdmin()) {
        Swal.fire({
          title: "Publish News",
          text: "You are going to publish this news to the selected member(s)",
          icon: "warning",
          showCancelButton: true,
          confirmButtonColor: "#3085d6",
          cancelButtonColor: "#d33",
          confirmButtonText: "Yes, Publish it!"
        }).then(result => {
          //Send request to the server
          let toUser = _.map(
            this.userGroup.filter(data => {
              return data.type === "person";
            }),
            "id"
          );
          let toGroup = _.map(
            this.userGroup.filter(data => {
              return data.type === "group";
            }),
            "id"
          );
          if (result.value) {
            axios
              .put("api/news/" + this.newsId, { toUser, toGroup })
              .then(resp => {
                if (resp) {
                  console.log(resp.data);
                }
                $("#publishModal").modal("hide");
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
