<template>
  <div>
    <div class="overlay-wrapper">
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
              v-model="rcpts.checkAvailList"
              multiple
              class="form-control"
              id="unassigned"
              size="10"
            >
              <option v-for="(rcpt,index) in rcpts.availList" :key="rcpt.id" :value="index">
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
  data() {
    return {
      inprogress: false,
      rcpts: {
        type: "person",
        allList: [],
        availList: [],
        setList: [],
        checkAvailList: [],
        checkSetList: []
      }
    };
  },
  methods: {
    getAvailRcptList() {
      if (this.rcpts.type === "person") {
        this.inprogress = true;
        axios
          .get("api/user")
          .then(({ data }) => {
            let setListByPerson = this.rcpts.setList.filter(function(data) {
              return data.type === "person";
            });

            this.rcpts.availList = _.differenceBy(data, setListByPerson, "id");

            this.inprogress = false;
          })
          .catch(() => {
            this.inprogress = false;
            Swal.fire({
              icon: "error",
              title: "Oops...",
              text: "Failed to retrieve Group Info!",
              footer: "<a href='/news'>Let me redo again</a>"
            });
          });
      } else {
        this.inprogress = true;
        axios
          .get("api/group")
          .then(({ data }) => {
            let setListByGroup = this.rcpts.setList.filter(function(data) {
              return data.type === "group";
            });

            this.rcpts.availList = _.differenceBy(data, setListByGroup, "id");

            this.inprogress = false;
          })
          .catch(() => {
            this.inprogress = false;
            Swal.fire({
              icon: "error",
              title: "Oops...",
              text: "Failed to retrieve Group Info!",
              footer: "<a href='/news'>Let me redo again</a>"
            });
          });
      }
    },

    addToList() {
      if (!this.rcpts.availList.length) {
        return;
      }

      let selected = [];

      for (var i in this.rcpts.checkAvailList) {
        let idx = this.rcpts.checkAvailList[i];

        selected.push(this.rcpts.availList[idx]);
      }

      this.rcpts.setList = [...this.rcpts.setList, ...selected];

      this.rcpts.availList = _.differenceBy(
        this.rcpts.availList,
        selected,
        "id"
      );
      this.rcpts.checkAvailList = [];

      // this.$emit("userGroupList", this.rcpts.setList);
    },
    removeFromList() {
      if (!this.rcpts.setList.length) {
        return;
      }
      let selected = [];
      for (var i in this.rcpts.checkSetList) {
        let idx = this.rcpts.checkSetList[i];

        selected.push(this.rcpts.setList[idx]);
      }

      this.rcpts.setList = _.difference(this.rcpts.setList, selected);

      selected = selected.filter(data => {
        return data.type === this.rcpts.type;
      });

      this.rcpts.availList = [...this.rcpts.availList, ...selected];
      this.rcpts.checkSetList = [];

      this.$emit("userGroupList", this.rcpts.setList);
    },
    clearSetList() {
      this.rcpts.setList = [];
    }
  }
};
</script>

<style>
</style>