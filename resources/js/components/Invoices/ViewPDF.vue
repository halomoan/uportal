<template>
  <div>
    <div class="content-header pb-1">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Invoice - Preview</h1>
          </div>
          <div class="col-sm-6">
            <router-link to="/invoices" class="d-flex flex-row-reverse" exact-active-class="active">
              <button class="btn btn-outline-primary btn-sm pull-right">Close</button>
            </router-link>
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
  methods: {},
  mounted() {
    axios
      //   .get("api/invoices/1", { responseType: "arraybuffer" })
      .get("api/invoices/1")
      .then(response => {
        console.log(response);
        // let blob = new Blob([response.data], { type: "application/pdf" });
        // console.log(blob);
        pdf.embed(response.data, "#pdf-content");
      })
      .catch(err => {});
    // pdf.embed("api/invoices/1", "#pdf-content");
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
