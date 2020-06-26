<template>
  <div class="card card-olive">
    <div class="card-header">
      <h3 class="card-title">Import Users</h3>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
      <div class="row">
        <div class="col-6">
          <button class="btn btn-block bg-gradient-fuchsia">Download Template</button>
          <button class="btn btn-block bg-gradient-green mt-3">Manage Users</button>
        </div>
        <div class="col-6">
          <div class="form-group">
            <div class="input-group input-group-sm">
              <div class="custom-file">
                <input type="file" class="custom-file-input" id="userFile" />
                <label class="custom-file-label" for="userFile">Choose file</label>
              </div>
            </div>
          </div>
          <button class="btn btn-block bg-gradient-indigo">Upload Users</button>
        </div>
      </div>
    </div>
    <!-- /.card-body -->
    <div class="overlay" v-if="inprogress">
      <i class="fas fa-3x fa-sync-alt fa-spin"></i>
      <div class="text-bold pl-2">Loading...</div>
    </div>
  </div>
</template>

<script>
export default {};
</script>
