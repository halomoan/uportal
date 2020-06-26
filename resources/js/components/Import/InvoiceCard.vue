<template>
  <div class="card card-primary">
    <div class="card-header">
      <h3 class="card-title">Import Invoices</h3>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
      <div class="row">
        <div class="col-6">
          <div class="form-group row">
            <label class="pr-3 pt-2" for="cocode">Company:</label>
            <select
              id="cocode"
              name="cocode"
              class="form-control"
              style="width: 60%;"
              tabindex="-1"
              aria-hidden="true"
              v-model="company"
              @change="getYears()"
            >
              <option
                v-for="item in companies"
                v-bind:key="item.CoCode"
                :value="item.CoCode"
              >{{ item.CoCode}} - {{ item.Name }}</option>
            </select>
          </div>
        </div>

        <div class="col-6">
          <div class="form-group d-flex justify-content-end">
            <label class="pl-3 pr-3 pt-2" for="year">Year:</label>

            <select
              id="year"
              name="year"
              class="form-control"
              style="width: 30%;"
              aria-hidden="true"
              v-model="year"
              @change="getInvoiceH()"
            >
              <option
                v-for="item in years"
                v-bind:key="item.year"
                :value="item.year"
              >{{ item.year }}</option>
            </select>
            <button
              type="button"
              class="btn btn-outline btn-outline-primary ml-2"
              @click="createYear"
            >Add Year</button>
            <button
              type="button"
              class="btn btn-outline btn-outline-danger ml-2"
              @click="deleteYear"
            >Remove Year</button>
          </div>
        </div>
      </div>
      <div class="row pt-3">
        <div class="col-12">
          <table class="table table-bordered text-center">
            <tbody>
              <tr>
                <td v-for="item in items.slice(0,6)" v-bind:key="item.Month">
                  <button
                    type="button"
                    class="btn btn-block"
                    @click="importInvoice(item.Month)"
                    v-bind:class="{ 'bg-gradient-primary' : item.TotRec > 0,'bg-gradient-secondary' : item.TotRec == 0 }"
                  >{{ item.Month | MMM }}-{{ item.Year }}</button>
                  <span
                    class="text-red"
                    v-if="item.TotRec != item.NoOfRec"
                  >{{ item.NoOfRec }} / {{ item.TotRec}}</span>
                  <span v-if="item.TotRec == item.NoOfRec">{{ item.TotRec}}</span>
                  records
                  <br />
                  <span class="text-sm font-italic">Last update: {{ item.updated_at | humanDate }}</span>
                </td>
              </tr>
              <tr>
                <td v-for="item in items.slice(6,12)" v-bind:key="item.Month">
                  <button
                    type="button"
                    class="btn btn-block"
                    @click="importInvoice(item.Month)"
                    v-bind:class="{ 'bg-gradient-primary' : item.TotRec > 0,'bg-gradient-secondary' : item.TotRec == 0 }"
                  >{{ item.Month | MMM }}-{{item.Year}}</button>
                  <span
                    class="text-red"
                    v-if="item.TotRec != item.NoOfRec"
                  >{{ item.NoOfRec }} / {{ item.TotRec}}</span>
                  <span v-if="item.TotRec == item.NoOfRec">{{ item.TotRec}}</span>
                  records
                  <br />
                  <span class="text-sm font-italic">Last update: {{ item.updated_at | humanDate }}</span>
                </td>
              </tr>
            </tbody>
          </table>
          <div v-show="items.length < 1" class="text-center">- Empty -</div>
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
export default {
  data() {
    return {
      inprogress: false,
      company: null,
      year: null,
      companies: [],
      years: [],
      items: []
    };
  },
  methods: {
    getCompanies() {
      axios.get("api/company").then(({ data }) => {
        this.companies = data;
        if (this.companies && this.companies.length > 0) {
          this.company = this.companies[0].CoCode;

          this.getYears();
        }
      });
    },
    getYears() {
      this.years = [];
      this.items = [];
      axios
        .get("api/impinvoice?years=true&cocode=" + this.company)
        .then(({ data }) => {
          this.years = data;
          if (this.years && this.years.length > 0) {
            this.year = this.years[0].year;
            this.getInvoiceH();
          }
        });
    },

    getInvoiceH() {
      this.inprogress = true;
      axios
        .get("api/impinvoice?year=" + this.year + "&cocode=" + this.company)
        .then(({ data }) => {
          this.items = [...data];
          this.inprogress = false;
        });
    },
    createYear() {
      Swal.mixin({
        input: "text",
        inputAttributes: {
          maxlength: 4
        },
        //confirmButtonText: "Next &rarr;",
        confirmButtonText: "Create",
        showCancelButton: true,
        progressSteps: ["1"],
        inputValidator: value => {
          return new Promise(resolve => {
            if (value.length === 4 && !isNaN(value)) {
              resolve();
            } else {
              resolve("You must enter a valid year");
            }
          });
        }
      })
        .queue([
          {
            title: "Create Year",
            text: "Please Enter A Valid Year Number"
          }
        ])
        .then(result => {
          if (result.value) {
            const year = result.value[0];

            this.inprogress = true;
            axios
              .post("api/impinvoice", {
                year,
                cocode: this.company
              })
              .then(resp => {
                this.getYears();
                this.inprogress = false;
              })
              .catch(err => {
                this.inprogress = false;
                let message = err.response.data.message;
                const errors = err.response.data.errors;

                for (const error in errors) {
                  for (const msg in errors[error]) {
                    message = errors[error][msg];
                  }
                }

                if (message) {
                  Swal.fire("Failed!", message, "warning");
                } else {
                  Swal.fire("Failed!", "There is something wrong.", "warning");
                }
              });
          }
        });
    },
    deleteYear() {
      if (!this.year) {
        return;
      }
      // console.log(this.items.some(item => item.TotRecord > 0));
      // return;

      Swal.fire({
        title: "Are you sure?",
        text: "You are going to delete invoices in year: " + this.year,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!"
      }).then(result => {
        if (result.value) {
          this.inprogress = true;
          axios
            .delete("api/impinvoice/" + this.year + "," + this.company)
            .then(resp => {
              this.inprogress = false;
            })
            .catch(err => {
              this.inprogress = false;
              let message = err.response.data.message;
              const errors = err.response.data.errors;

              for (const error in errors) {
                for (const msg in errors[error]) {
                  message = errors[error][msg];
                }
              }

              if (message) {
                Swal.fire("Failed!", message, "warning");
              } else {
                Swal.fire("Failed!", "There is something wrong.", "warning");
              }
            });
        }
      });
    },

    importInvoice(idx) {
      let text = null;
      for (let i = 0; i < this.companies.length; i++) {
        if (this.companies[i].CoCode === this.company) {
          text = this.companies[i].Name;
          i = 1000;
        }
      }

      localStorage.company = text;

      const payload = JSON.stringify(this.items[idx - 1]);
      localStorage.setItem("invoiceh", payload);

      this.$router.push({ name: "importInv" });
    }
  },
  mounted() {
    this.getCompanies();
  }
};
</script>
