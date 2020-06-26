<template>
  <div>
    <div v-if="$Role.isAdmin()">
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
              <InvoiceCard></InvoiceCard>
            </div>
          </div>
          <!-- ./row -->
          <div class="row">
            <div class="col-lg-12 col-12">
              <UserCard></UserCard>
            </div>
          </div>
        </div>
      </div>

      <!-- ./content -->
    </div>
    <div v-if="!$Role.isAdmin()">
      <not-found></not-found>
    </div>
  </div>
</template>

<script>
import InvoiceCard from "./InvoiceCard";
import UserCard from "./UserCard";

export default {
  components: {
    InvoiceCard,
    UserCard
  },
  data() {
    return {
      inprogress: false
    };
  },
  methods: {},
  mounted() {}
};
</script>
