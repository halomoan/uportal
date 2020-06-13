<template>
  <div class="timeline">
    <template v-for="item in tldata">
      <!-- timeline time label -->
      <div class="time-label" :key="item.id">
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
    <div>
      <i class="fas fa-clock bg-gray"></i>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      tldata: [
        {
          id: 1,
          date: "3 Jan. 2014",
          created: "5 days ago",
          from: "",
          title: "New Invoice",
          body:
            "Take me to your leader! Switzerland is small and neutral! We are more like Germany, ambitious",
          readmore: "/invoice"
        },
        {
          id: 2,
          date: "3 Jan. 2014",
          created: "5 days ago",
          from: "Mr. Doe",
          title: "shared a video",
          body:
            "Take me to your leader! Switzerland is small and neutral! We are more like Germany, ambitious"
        },
        {
          id: 3,
          date: "3 Jan. 2014",
          created: "5 days ago",
          from: "Mr. Doe",
          title: "shared a video",
          body:
            "Take me to your leader! Switzerland is small and neutral! We are more like Germany, ambitious"
        }
      ]
    };
  }
};
</script>
