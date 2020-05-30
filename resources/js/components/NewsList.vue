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
                    <div class="col-6">
                      <a href="#" @click.prevent="createNews">
                        <i class="fa far fa-file"></i> Create
                      </a>
                    </div>
                    <div class="col-6">
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
              <form>
                <div class="form-group row">
                  <label for="recipient-type" class="col-sm-2 col-form-label">Recipient:</label>
                  <div class="col-sm-10">
                    <select
                      class="form-control"
                      id="recipient-type"
                      v-model="rcpts.type"
                      @change="getAvailRcptList()"
                    >
                      <option value="group">Group</option>
                      <option value="user">User</option>
                    </select>
                  </div>
                </div>
                <div class="row">
                  <div class="col-5">
                    <div class="form-group">
                      <label for="unassigned">
                        Available
                        Member(s):
                      </label>
                      <select
                        v-model="rcpts.checkAvailList"
                        multiple
                        class="form-control"
                        id="unassigned"
                        size="10"
                      >
                        <option
                          v-for="(rcpt,index) in rcpts.availList"
                          :key="rcpt.id"
                          :value="index"
                        >
                          {{
                          rcpt.name
                          }}
                        </option>
                      </select>
                    </div>
                  </div>
                  <div class="col-2 d-flex flex-column justify-content-center align-items-center">
                    <button type="button" class="btn btn-info mb-2" @click.prevent="addsetList">&gt;</button>
                    <button
                      type="button"
                      class="btn btn-default"
                      @click.prevent="removesetList"
                    >&lt;</button>
                  </div>
                  <div class="col-5">
                    <div class="form-group">
                      <label for="setList">
                        Assigned
                        Member(s):
                      </label>
                      <select
                        v-model="rcpts.checkSetList"
                        multiple
                        class="form-control"
                        id="setList"
                        size="10"
                      >
                        <option v-for="(rcpt,index) in rcpts.setList" :key="index" :value="index">
                          {{
                          rcpt.name
                          }}
                        </option>
                      </select>
                    </div>
                  </div>
                </div>
              </form>
            </div>
            <!-- ./modal-body -->
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <button type="button" class="btn btn-primary">Save changes</button>
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
export default {
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
      rcpts: {
        type: "user",
        allList: [],
        availList: [],
        setList: [],
        checkAvailList: [],
        checkSetList: []
      }
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
    getAvailRcptList() {
      if (this.rcpts.type === "user") {
      } else {
        axios
          .get("api/group")
          .then(({ data }) => {
            this.rcpts.availList = data;
          })
          .catch(() => {
            Swal.fire({
              icon: "error",
              title: "Oops...",
              text: "Failed to retrieve Group Info!",
              footer: "<a href='/news'>Let me redo again</a>"
            });
          });
      }
    },
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
    publishFor(id) {
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
