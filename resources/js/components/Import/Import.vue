<template>
  <div>
    <div class="content-header pb-1">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Import</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item">
                <a href="#">Home</a>
              </li>
              <li class="breadcrumb-item active">Import</li>
            </ol>
          </div>
        </div>
      </div>
    </div>
    <!-- ./content-header -->
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-12 col-12">
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Import Invoices</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <div class="row">
                  <div class="col-5"></div>
                  <div class="col-5"></div>

                  <div class="col-2 d-flex justify-content-end">
                    <select
                      class="form-control"
                      style="width: 50%;"
                      tabindex="-1"
                      aria-hidden="true"
                    >
                      <option selected="selected"></option>
                      <option selected>2020</option>
                      <option>2021</option>
                      <option>2022</option>
                      <option>2023</option>
                      <option>2024</option>
                      <option>2025</option>
                    </select>
                  </div>
                </div>
                <div class="row pt-3">
                  <div class="col-12">
                    <table class="table table-bordered text-center">
                      <tbody>
                        <tr>
                          <td>
                            <button type="button" class="btn btn-block bg-gradient-primary">Jan-2020</button>
                            <code>235</code> records
                            <br />
                            <span class="text-sm font-italic">Last update: 25-May-2020</span>
                          </td>
                          <td>
                            <button type="button" class="btn btn-block bg-gradient-primary">Feb-2020</button>
                            <code>235</code> records
                            <br />
                            <span class="text-sm font-italic">Last update: 25-May-2020</span>
                          </td>
                          <td>
                            <button type="button" class="btn btn-block bg-gradient-primary">Mar-2020</button>
                            <code>235</code> records
                            <br />
                            <span class="text-sm font-italic">Last update: 25-May-2020</span>
                          </td>
                          <td>
                            <button type="button" class="btn btn-block bg-gradient-primary">Apr-2020</button>
                            <code>235</code> records
                            <br />
                            <span class="text-sm font-italic">Last update: 25-May-2020</span>
                          </td>
                          <td>
                            <button type="button" class="btn btn-block bg-gradient-primary">May-2020</button>
                            <code>235</code> records
                            <br />
                            <span class="text-sm font-italic">Last update: 25-May-2020</span>
                          </td>
                          <td>
                            <button type="button" class="btn btn-block bg-gradient-primary">Jun-2020</button>
                            <code>235</code> records
                            <br />
                            <span class="text-sm font-italic">Last update: 25-May-2020</span>
                          </td>
                        </tr>
                        <tr>
                          <td>
                            <button type="button" class="btn btn-block bg-gradient-primary">Jul-2020</button>
                            <code>235</code> records
                            <br />
                            <span class="text-sm font-italic">Last update: 25-May-2020</span>
                          </td>
                          <td>
                            <button type="button" class="btn btn-block bg-gradient-primary">Aug-2020</button>
                            <code>235</code> records
                            <br />
                            <span class="text-sm font-italic">Last update: 25-May-2020</span>
                          </td>
                          <td>
                            <button type="button" class="btn btn-block bg-gradient-primary">Sep-2020</button>
                            <code>235</code> records
                            <br />
                            <span class="text-sm font-italic">Last update: 25-May-2020</span>
                          </td>
                          <td>
                            <button type="button" class="btn btn-block bg-gradient-info">Oct-2020</button>
                            <code>0</code> records
                          </td>
                          <td>
                            <button type="button" class="btn btn-block bg-gradient-info">Nov-2020</button>
                            <code>0</code> records
                          </td>
                          <td>
                            <button type="button" class="btn btn-block bg-gradient-info">Dec-2020</button>
                            <code>0</code> records
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
              <!-- /.card-body -->
            </div>
          </div>
        </div>
        <!-- ./row -->
        <div class="row">
          <div class="col-lg-12 col-12">
            <div class="card card-olive">
              <div class="card-header">
                <h3 class="card-title">Import Users</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <div class="row">
                  <div class="col-6">
                    <div class="form-group">
                      <div class="input-group">
                        <div class="custom-file">
                          <input type="file" class="custom-file-input" id="exampleInputFile" />
                          <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                        </div>
                      </div>
                    </div>
                    <button class="btn btn-block bg-gradient-indigo">Upload Users</button>
                  </div>
                  <div class="col-6">
                    <button class="btn btn-block bg-gradient-fuchsia">Download Template</button>
                  </div>
                </div>
              </div>
              <!-- /.card-body -->
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- ./content -->
  </div>
</template>

<script>
export default {};
</script>
