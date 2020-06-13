<template>
  <div>
    <div class="content-header pb-1">
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
          <div class="col-lg-4 col-4">
            <!-- small box -->
            <div class="small-box bg-success">
              <div class="inner">
                <h3>{{ stat.noOfNewInvoice }}</h3>
                <p>New Invoice</p>
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
          <div class="col-lg-4 col-4">
            <!-- small box -->
            <div class="small-box bg-warning">
              <div class="inner">
                <h3>{{ stat.noOfNewAnnounce }}</h3>
                <p>New Announcement</p>
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
          <div class="col-lg-4 col-4">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
                <h3>{{ stat.lastVisit }}</h3>
                <p>Last Visit</p>
              </div>
              <div class="icon">
                <i class="ion ion-eye"></i>
              </div>
              <router-link to="/dashboard" class="small-box-footer">
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
                  Invoices
                </h3>
                <div class="card-tools">
                  <ul class="nav nav-pills ml-auto">
                    <li class="nav-item">
                      <a
                        class="nav-link"
                        v-bind:class="{ 'active' : tabIndex === 0 }"
                        href="#month-chart"
                        @click.stop.prevent="setActiveTab(0)"
                      >3 Months</a>
                    </li>
                    <li class="nav-item">
                      <a
                        class="nav-link"
                        v-bind:class="{ 'active' : tabIndex === 1 }"
                        href="#yoy-chart"
                        @click.stop.prevent="setActiveTab(1)"
                      >Y-o-Y</a>
                    </li>
                  </ul>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <div class="tab-content p-0">
                  <div
                    class="chart tab-pane"
                    v-bind:class="{ 'active' : tabIndex === 0}"
                    id="month-chart"
                  >
                    <div class="d-flex flex-column">
                      <div class="d-flex">
                        <p class="d-flex flex-column">
                          <span
                            class="text-bold text-lg"
                          >{{ invoiceMTHChart.aux.amount | currency('SGD',2) }}</span>
                          <span>This Month</span>
                        </p>
                        <p class="ml-auto d-flex flex-column text-right">
                          <span
                            v-bind:class="parseFloat(invoiceMTHChart.aux.perctg) >= 0.0 ? 'text-danger' : 'text-success'"
                          >
                            <i
                              class="fas"
                              v-bind:class="invoiceMTHChart.aux.perctg >= 0 ? 'fa-arrow-up' : 'fa-arrow-down'"
                            ></i>
                            {{ invoiceMTHChart.aux.perctg | currency('',2) }} %
                          </span>
                          <span class="text-muted">Since last month</span>
                        </p>
                      </div>
                      <div class="chart-container" style="position: relative;height: 300px;">
                        <canvas id="invoicemth-canvas" height="300" style="height: 300px;"></canvas>
                      </div>
                    </div>
                  </div>
                  <!-- ./tab-pane -->
                  <div
                    class="chart tab-pane"
                    v-bind:class="{ 'active' : tabIndex === 1 }"
                    id="yoy-chart"
                  >
                    <div class="d-flex flex-column">
                      <div class="d-flex">
                        <p class="d-flex flex-column">
                          <span
                            class="text-bold text-lg"
                          >{{ invoiceYOYChart.aux.amount | currency('SGD',2) }}</span>
                          <span>This Month</span>
                        </p>
                        <p class="ml-auto d-flex flex-column text-right">
                          <span
                            v-bind:class="parseFloat(invoiceYOYChart.aux.perctg) >= 0.0 ? 'text-danger' : 'text-success'"
                          >
                            <i
                              class="fas"
                              v-bind:class="invoiceYOYChart.aux.perctg >= 0 ? 'fa-arrow-up' : 'fa-arrow-down'"
                            ></i>
                            {{ invoiceYOYChart.aux.perctg | currency('',2) }} %
                          </span>
                          <span class="text-muted">Compare to Last Year</span>
                        </p>
                      </div>
                      <div class="chart-container" style="position: relative;height: 300px;">
                        <canvas id="invoiceyoy-canvas" height="300" style="height: 300px;"></canvas>
                      </div>
                    </div>
                  </div>
                  <!-- ./tab-pane -->
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
                  <timeline></timeline>
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
import Chart from "chart.js";
import invoice1Chart from "../charts/barchart-invoice1.js";
import Timeline from "./Common/Timeline.vue";

export default {
  components: {
    Timeline
  },
  data() {
    return {
      tabIndex: 0,
      stat: {
        noOfNewInvoice: 0,
        noOfNewAnnounce: 0,
        lastVisit: ""
      },
      invoiceMTHChart: invoice1Chart,
      invoiceYOYChart: _.cloneDeep(invoice1Chart)
    };
  },

  methods: {
    setActiveTab(idx) {
      this.tabIndex = idx;
    },
    getSingleStats() {
      let uri = "/api/dashboard?s=true";
      axios.get(uri).then(({ data }) => {
        this.stat.noOfNewInvoice = data.noOfNewInvoice
          ? data.noOfNewInvoice
          : 0;
        this.stat.noOfNewAnnounce = data.noOfNewAnnounce
          ? data.noOfNewAnnounce
          : 0;
        this.stat.lastVisit = data.lastVisit;
      });
    },
    createChart(chartId, chartData) {
      const ctx = document.getElementById(chartId);
      const myChart = new Chart(ctx, {
        type: chartData.type,
        data: chartData.data,
        options: chartData.options
      });
    },
    getInvoicesMTHChart() {
      let uri = "/api/dashboard?chart=invoicemth";
      axios.get(uri).then(({ data }) => {
        this.invoiceMTHChart.data.datasets[0].data = [...data.data];
        this.invoiceMTHChart.data.labels = data.labels;

        this.invoiceMTHChart.aux.amount = data.amount;
        this.invoiceMTHChart.aux.perctg = data.perctg;

        this.createChart("invoicemth-canvas", this.invoiceMTHChart);
      });
    },
    getInvoicesYOYChart() {
      let uri = "/api/dashboard?chart=invoiceyoy";
      axios.get(uri).then(({ data }) => {
        this.invoiceYOYChart.data.datasets[0].data = [...data.data];
        this.invoiceYOYChart.data.datasets[0].backgroundColor[1] = this.invoiceMTHChart.data.datasets[0].backgroundColor[2];
        this.invoiceYOYChart.data.labels = data.labels;

        this.invoiceYOYChart.aux.amount = data.amount;
        this.invoiceYOYChart.aux.perctg = data.perctg;
        this.createChart("invoiceyoy-canvas", this.invoiceYOYChart);
      });
    }
  },
  mounted() {
    this.getSingleStats();
    this.getInvoicesMTHChart();
    this.getInvoicesYOYChart();
    this.get;
  }
};
</script>
