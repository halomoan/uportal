<template>
  <div class="timeline">
    <template v-for="(item,idx) in tldata">
      <!-- timeline time label -->

      <div
        class="time-label"
        :key="item.id"
        v-if="idx === 0 || tldata[idx].date != tldata[idx - 1].date"
      >
        <span class="bg-red">{{item.date}}</span>
      </div>
      <!-- /.timeline time label -->
      <!-- timeline item -->
      <div v-bind:key="'body' + item.id">
        <i class="fas fa-envelope bg-blue"></i>
        <div class="timeline-item">
          <span class="time">
            <i class="fas fa-clock"></i>
            {{ item.created}}
          </span>
          <h3 class="timeline-header">
            <a href="#">{{ item.from}}</a>
            {{ item.title}}
          </h3>

          <div class="timeline-body">{{ item.body}}</div>
          <div class="timeline-footer">
            <a class="btn btn-primary btn-sm">Read more</a>
            <a class="btn btn-danger btn-sm">Delete</a>
          </div>
        </div>
      </div>
      <!-- END timeline item -->
    </template>

    <div v-if="page < 3">
      <div class="timeline-inverse">
        <a class="btn btn-success btn-sm text-white" v-on:click="getMore">More...</a>
      </div>
    </div>
    <div>
      <i class="fas fa-clock bg-gray"></i>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      page: 0,
      tldata: []
    };
  },
  methods: {
    getUserTimeline() {
      const uri = "/api/timeline?page=" + this.page;
      axios
        .get(uri)
        .then(result => {
          console.log(result);
          this.tldata = [...this.tldata, ...result.data];
        })
        .catch(err => {});
    },
    getMore() {
      this.page = this.page + 1;
      this.getUserTimeline();
    }
  },
  mounted() {
    this.getUserTimeline();
  }
};
</script>
