<template>
  <div>
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Dashboard</h1>
          </div>
          <!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item">
                <a href="#">Home</a>
              </li>
              <li class="breadcrumb-item active">Dashboard</li>
            </ol>
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div>

      <!-- /.container-fluid -->
    </div>
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-6 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
              <div class="inner">
                <h3>2</h3>
                <p>New Invoices</p>
              </div>
              <div class="icon">
                <i class="ion ion-email"></i>
              </div>
              <router-link to="/invoices" class="small-box-footer">
                More info
                <i class="fas fa-arrow-circle-right"></i>
              </router-link>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-6 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
              <div class="inner">
                <h3>1</h3>
                <p>New Announcements</p>
              </div>
              <div class="icon">
                <i class="ion ion-chatbubble"></i>
              </div>
              <router-link to="/announces" class="small-box-footer">
                More info
                <i class="fas fa-arrow-circle-right"></i>
              </router-link>
            </div>
          </div>
          <!-- ./col -->
        </div>
        <div class="row">
          <section class="col-lg-7 connectedSortable ui-sortable">
            <!-- Custom tabs (Charts with tabs)-->
            <div class="card">
              <div class="card-header ui-sortable-handle" style="cursor: move;">
                <h3 class="card-title">
                  <i class="fas fa-chart-pie mr-1"></i>
                  Sales
                </h3>
                <div class="card-tools">
                  <ul class="nav nav-pills ml-auto">
                    <li class="nav-item">
                      <a class="nav-link active" href="#revenue-chart" data-toggle="tab">Area</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" href="#sales-chart" data-toggle="tab">Donut</a>
                    </li>
                  </ul>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <div class="tab-content p-0">
                  <!-- Morris chart - Sales -->
                  <div
                    class="chart tab-pane active"
                    id="revenue-chart"
                    style="position: relative; height: 300px;"
                  >
                    <div class="chartjs-size-monitor">
                      <div class="chartjs-size-monitor-expand">
                        <div class></div>
                      </div>
                      <div class="chartjs-size-monitor-shrink">
                        <div class></div>
                      </div>
                    </div>
                    <canvas
                      id="revenue-chart-canvas"
                      height="300"
                      style="height: 300px; display: block; width: 620px;"
                      width="620"
                      class="chartjs-render-monitor"
                    ></canvas>
                  </div>
                  <div
                    class="chart tab-pane"
                    id="sales-chart"
                    style="position: relative; height: 300px;"
                  >
                    <canvas
                      id="sales-chart-canvas"
                      height="0"
                      style="height: 0px; display: block; width: 0px;"
                      class="chartjs-render-monitor"
                      width="0"
                    ></canvas>
                  </div>
                </div>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </section>
          <section class="col-lg-5 connectedSortable ui-sortable">
            <div class="card">
              <div class="card-header ui-sortable-handle" style="cursor: move;">
                <h3 class="card-title">
                  <i class="fas fa-calendar-alt mr-1"></i>
                  Timeline
                </h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <div class="tab-content p-0">
                  <div class="timeline">
                    <!-- timeline time label -->
                    <div class="time-label">
                      <span class="bg-red">10 Feb. 2014</span>
                    </div>
                    <!-- /.timeline-label -->
                    <!-- timeline item -->
                    <div>
                      <i class="fas fa-envelope bg-blue"></i>
                      <div class="timeline-item">
                        <span class="time">
                          <i class="fas fa-clock"></i> 12:05
                        </span>
                        <h3 class="timeline-header">
                          <a href="#">Support Team</a> sent you an email
                        </h3>

                        <div class="timeline-body">
                          Etsy doostang zoodles disqus groupon greplin oooj voxy zoodles,
                          weebly ning heekya handango imeem plugg dopplr jibjab, movity
                          jajah plickers sifteo edmodo ifttt zimbra. Babblely odeo kaboodle
                          quora plaxo ideeli hulu weebly balihoo...
                        </div>
                        <div class="timeline-footer">
                          <a class="btn btn-primary btn-sm">Read more</a>
                          <a class="btn btn-danger btn-sm">Delete</a>
                        </div>
                      </div>
                    </div>
                    <!-- END timeline item -->
                    <!-- timeline item -->
                    <div>
                      <i class="fas fa-user bg-green"></i>
                      <div class="timeline-item">
                        <span class="time">
                          <i class="fas fa-clock"></i> 5 mins ago
                        </span>
                        <h3 class="timeline-header no-border">
                          <a href="#">Sarah Young</a> accepted your friend request
                        </h3>
                      </div>
                    </div>
                    <!-- END timeline item -->
                    <!-- timeline item -->
                    <div>
                      <i class="fas fa-comments bg-yellow"></i>
                      <div class="timeline-item">
                        <span class="time">
                          <i class="fas fa-clock"></i> 27 mins ago
                        </span>
                        <h3 class="timeline-header">
                          <a href="#">Jay White</a> commented on your post
                        </h3>
                        <div class="timeline-body">
                          Take me to your leader!
                          Switzerland is small and neutral!
                          We are more like Germany, ambitious and misunderstood!
                        </div>
                        <div class="timeline-footer">
                          <a class="btn btn-warning btn-sm">View comment</a>
                        </div>
                      </div>
                    </div>
                    <!-- END timeline item -->
                    <!-- timeline time label -->
                    <div class="time-label">
                      <span class="bg-green">3 Jan. 2014</span>
                    </div>
                    <!-- /.timeline-label -->
                    <!-- timeline item -->
                    <div>
                      <i class="fa fa-camera bg-purple"></i>
                      <div class="timeline-item">
                        <span class="time">
                          <i class="fas fa-clock"></i> 2 days ago
                        </span>
                        <h3 class="timeline-header">
                          <a href="#">Mina Lee</a> uploaded new photos
                        </h3>
                        <div class="timeline-body">
                          <img src="http://placehold.it/150x100" alt="..." />
                          <img src="http://placehold.it/150x100" alt="..." />
                          <img src="http://placehold.it/150x100" alt="..." />
                          <img src="http://placehold.it/150x100" alt="..." />
                          <img src="http://placehold.it/150x100" alt="..." />
                        </div>
                      </div>
                    </div>
                    <!-- END timeline item -->
                    <!-- timeline item -->
                    <div>
                      <i class="fas fa-video bg-maroon"></i>

                      <div class="timeline-item">
                        <span class="time">
                          <i class="fas fa-clock"></i> 5 days ago
                        </span>

                        <h3 class="timeline-header">
                          <a href="#">Mr. Doe</a> shared a video
                        </h3>

                        <div class="timeline-body">
                          <div class="embed-responsive embed-responsive-16by9">
                            <iframe
                              class="embed-responsive-item"
                              src="https://www.youtube.com/embed/tMWkeBIohBs"
                              frameborder="0"
                              allowfullscreen
                            ></iframe>
                          </div>
                        </div>
                        <div class="timeline-footer">
                          <a href="#" class="btn btn-sm bg-maroon">See comments</a>
                        </div>
                      </div>
                    </div>
                    <!-- END timeline item -->
                    <div>
                      <i class="fas fa-clock bg-gray"></i>
                    </div>
                  </div>
                </div>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </section>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  mounted() {}
};
</script>
