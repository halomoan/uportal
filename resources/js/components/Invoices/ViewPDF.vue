<template>
  <div>
    <div class="content-header pb-1">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Invoice - Preview</h1>
          </div>
          <div class="col-sm-6">
            <div class="d-flex flex-row-reverse">
              <a class="btn btn-outline-warning btn-sm pull-right" @click="$router.go(-1)">Close</a>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- ./content-header -->
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12 vh-100">
            <div id="pdf-content"></div>
          </div>
        </div>
      </div>
    </div>
    <!-- ./content -->
  </div>
</template>

<script>
import pdf from "pdfobject";

export default {
  props: { id: Number },
  methods: {},
  mounted() {
    axios

      .get("api/invoices/" + this.id)
      .then(response => {
        pdf.embed(response.data, "#pdf-content");
      })
      .catch(err => {});
  }
};
</script>

<style lang="scss" scoped>
#pdf-content {
  width: 99%;
  height: 100%;
  position: absolute;
  top: 0;
  background: #999;
}
</style>
