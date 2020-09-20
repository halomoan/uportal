<template>
  <div>
    <div class="overlay-wrapper">
      <div class="row">
        <div class="col-5">
          <div class="form-group">
            <label for="unassigned">
              Available
              Company(s):
            </label>
            <select
              v-model="oList.checkAvailList"
              multiple
              class="form-control"
              id="unassigned"
              size="10"
            >
              <option v-for="(item,index) in oList.availList" :key="item.CoCode" :value="index">
                {{
                item.CoCode + ' ' + item.Name
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
              Company(s):
            </label>
            <select
              v-model="oList.checkSetList"
              multiple
              class="form-control"
              id="setList"
              size="10"
            >
              <option v-for="(item,index) in oList.setList" :key="item.CoCode" :value="index">
                {{
                item.CoCode + ' ' + item.Name
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
  },
  // watch: {
  //   id: function(newVal, oldVal) {
  //     this.getSelectedList();
  //   }
  // },
  data() {
    return {
      inprogress: false,
      oList: {
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
    getAvailList() {
      this.inprogress = true;

      axios
        .get(this.url)
        .then(({ data }) => {
          this.oList.availList = _.differenceBy(
            data,
            this.oList.setList,
            "CoCode"
          );

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
    },

    // Main Entry
    getSelectedList(id) {
      this.inprogress = true;

      axios
        .get(this.url + "/" + id)
        .then(({ data }) => {
          if (data) {
            this.oList.setList = [...data];
          } else {
            this.oList.setList = [];
          }

          this.$emit("CompanyList", this.oList.setList);

          this.oList.changed = false;

          this.getAvailList();
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

      this.oList.setList = [...this.oList.setList, ...selected];

      this.oList.availList = _.differenceBy(
        this.oList.availList,
        selected,
        "CoCode"
      );
      this.oList.checkAvailList = [];

      this.oList.changed = true;
      this.$emit("CompanyList", this.oList.setList);
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

      this.oList.availList = [...this.oList.availList, ...selected];
      this.oList.checkSetList = [];

      this.oList.changed = true;
      this.$emit("CompanyList", this.oList.setList);
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