<template>
  <div>
    <div class="overlay-wrapper">
      <div class="form-group row">
        <label for="select-type" v-show="type == null" class="col-sm-2 col-form-label">Type:</label>
        <div class="col-sm-10">
          <select
            v-show="type == null"
            class="form-control"
            id="select-type"
            v-model="oList.type"
            @change="getAvailRcptList(false)"
          >
            <option value="group">Group</option>
            <option value="person">User</option>
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
              v-model="oList.checkAvailList"
              multiple
              class="form-control"
              id="unassigned"
              size="10"
            >
              <option v-for="(rcpt,index) in oList.availList" :key="rcpt.id" :value="index">
                {{
                rcpt.name
                }}
              </option>
            </select>
          </div>
        </div>
        <div class="col-2 d-flex flex-column justify-content-center align-items-center">
          <button type="button" class="btn btn-info mb-2" @click.prevent="addToList">&gt;</button>
          <button type="button" class="btn btn-default" @click.prevent="removeFromList">&lt;</button>
        </div>
        <div class="col-5">
          <div class="form-group">
            <label for="setList">
              Assigned
              Member(s):
            </label>
            <select
              v-model="oList.checkSetList"
              multiple
              class="form-control"
              id="setList"
              size="10"
            >
              <option v-for="(rcpt,index) in oList.setList" :key="index" :value="index">
                {{
                rcpt.name
                }}
              </option>
            </select>
          </div>
        </div>
      </div>
      <div class="overlay" v-if="inprogress">
        <i class="fas fa-3x fa-sync-alt fa-spin"></i>
        <div class="text-bold pl-2">Loading...</div>
      </div>
    </div>
    <!-- ./overlay-wrapper -->
  </div>
</template>

<script>
export default {
  props: {
    id: { required: true },
    url: { type: String, required: true },
    type: { type: String },
  },
  data() {
    return {
      inprogress: false,
      oList: {
        type: "group",
        allList: [],
        availList: [],
        setList: [],
        checkAvailList: [],
        checkSetList: [],
        changed: false,
      },
    };
  },
  methods: {
    getAvailRcptList(applyDefault) {
      if (this.type != null) {
        this.oList.type = this.type;
      }

      if (this.oList.type === "person") {
        this.inprogress = true;
        axios
          .get("api/user?qtype=person")
          .then(({ data }) => {
            let setListByPerson = this.oList.setList.filter(function (data) {
              return data.type === "person";
            });

            this.oList.availList = _.differenceBy(data, setListByPerson, "id");

            this.inprogress = false;
          })
          .catch(() => {
            this.inprogress = false;
            Swal.fire({
              icon: "error",
              title: "Oops...",
              text: "Failed to retrieve Group Info!",
              footer: "<a href='/news'>Let me redo again</a>",
            });
          });
      } else {
        this.inprogress = true;
        axios
          .get("api/group")
          .then(({ data }) => {
            if (applyDefault) {
              this.oList.setList = data.filter(function (data) {
                if (data.is_default) {
                  data.name = "GROUP\\" + data.name;
                  return data;
                }
              });
              this.$emit("userGroupList", this.oList.setList);

              this.oList.availList = _.differenceBy(
                data,
                this.oList.setList,
                "id"
              );
            } else {
              let setListByGroup = this.oList.setList.filter(function (data) {
                return data.type === "group";
              });
              this.oList.availList = _.differenceBy(data, setListByGroup, "id");
            }

            this.inprogress = false;
          })
          .catch(() => {
            this.inprogress = false;
            Swal.fire({
              icon: "error",
              title: "Oops...",
              text: "Failed to retrieve Group Info!",
              footer: "<a href='/news'>Let me redo again</a>",
            });
          });
      }
    },
    setSelectedList(data) {      
      let setList = [];
      if (data.groups) {
        setList = [...data.groups];
      }

      if (data.users) {
        setList = [...setList, ...data.users];
      }

      this.oList.setList = setList.map((data) => {
        if (data.type === "group") {
          data.name = "GROUP\\" + data.name;
        } else {
          data.name = "USER\\" + data.name;
        }
        return data;
      });

      //this.$emit("userGroupList", this.oList.setList);

      this.oList.changed = false;

      this.getAvailRcptList(false);
    },
    getSelectedList(id) {
      this.inprogress = true;

      axios
        .get(this.url + "/" + id)
        .then(({ data }) => {
          let setList = [];
          if (data.groups) {
            setList = [...data.groups];
          }

          if (data.users) {
            setList = [...setList, ...data.users];
          }

          this.oList.setList = setList.map((data) => {
            if (data.type === "group") {
              data.name = "GROUP\\" + data.name;
            } else {
              data.name = "USER\\" + data.name;
            }
            return data;
          });

          this.$emit("userGroupList", this.oList.setList);

          this.oList.changed = false;

          this.getAvailRcptList(false);
          this.inprogress = false;
        })
        .catch((e) => {
          this.inprogress = false;
          Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "Failed to retrieve Member Info!",
            footer: "<a href='/news'>Let me redo again</a>",
          });
        });
    },
    addToList() {
      if (!this.oList.availList.length) {
        return;
      }

      let selected = [];

      for (var i in this.oList.checkAvailList) {
        let idx = this.oList.checkAvailList[i];

        selected.push(this.oList.availList[idx]);
      }

      selected = selected.map((data) => {
        if (data.type === "group") {
          data.name = "GROUP\\" + data.name;
        } else {
          data.name = "USER\\" + data.name;
        }
        return data;
      });

      this.oList.setList = [...this.oList.setList, ...selected];

      this.oList.availList = _.differenceBy(
        this.oList.availList,
        selected,
        "id"
      );
      this.oList.checkAvailList = [];

      this.oList.changed = true;
      this.$emit("userGroupList", this.oList.setList);
    },
    removeFromList() {
      if (!this.oList.setList.length) {
        return;
      }
      let selected = [];
      for (var i in this.oList.checkSetList) {
        let idx = this.oList.checkSetList[i];

        selected.push(this.oList.setList[idx]);
      }

      this.oList.setList = _.difference(this.oList.setList, selected);

      selected = selected.filter((data) => {
        return data.type === this.oList.type;
      });

      selected = selected.map((data) => {
        if (data.type === "group") {
          //data.name = "GROUP\\" + data.name;
          data.name = data.name.substring(6, 100);
        } else {
          //data.name = "USER\\" + data.name;
          data.name = data.name.substring(5, 100);
        }
        return data;
      });

      this.oList.availList = [...this.oList.availList, ...selected];
      this.oList.checkSetList = [];

      this.oList.changed = true;
      this.$emit("userGroupList", this.oList.setList);
    },

    isListChanged() {
      return this.oList.changed;
    },
    setListChanged(val) {
      this.oList.changed = val;
    },
    clearSetList() {
      this.oList.setList = [];
    },
  },
};
</script>

<style>
</style>