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
        <i class="fas fa-envelope bg-blue" v-if="item.type === 1"></i>
        <i class="fas fa-user bg-green" v-if="item.type === 2"></i>
        <i class="fas fa-bullhorn bg-red" v-if="item.type === 3"></i>
        <div class="timeline-item">
          <span class="time">
            <i class="fas fa-clock"></i>
            {{ item.created}}
          </span>
          <h3 class="timeline-header">
            <a href="#">{{ item.from}}</a>
            {{ item.title}}
          </h3>

          <div class="timeline-body" v-html="item.body"></div>
          <div class="timeline-footer">
            <router-link
              :to="{ name: item.link, params: {year: item.param1 }}"
              class="small-box-footer"
              v-if="item.link != ''"
            >
              <!-- <router-link :to="item.link" class="small-box-footer" v-if="item.link != ''"> -->
              <a class="btn btn-primary btn-sm">Show Me</a>
            </router-link>
          </div>
        </div>
      </div>
      <!-- END timeline item -->
    </template>

    <div v-if="page < 3 && !noMore">
      <div class="timeline-inverse">
        <a class="btn btn-success btn-sm text-white" v-on:click="getMore">More ...</a>
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
      noMore: false,
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
          if (jQuery.isArray(result.data)) {
            if (result.data.length > 0) {
              this.tldata = [...this.tldata, ...result.data];
            } else {
              this.noMore = true;
            }
          }
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
