<template>
    <div>
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>News</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item">
                                <a href="#">Home</a>
                            </li>
                            <li class="breadcrumb-item active">News</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <!-- ./content-header -->
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Editor</h3>

                                <div class="card-tools">
                                    <button
                                        type="button"
                                        class="btn btn-tool"
                                        data-card-widget="collapse"
                                        data-toggle="tooltip"
                                        title="Collapse"
                                    >
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group row">
                                            <label
                                                for="title"
                                                class="col-sm-2 col-form-label"
                                                >Title</label
                                            >
                                            <div class="col-sm-10">
                                                <input
                                                    type="text"
                                                    class="form-control"
                                                    autocomplete="off"
                                                    :class="{
                                                        'is-invalid': form.errors.has(
                                                            'title'
                                                        )
                                                    }"
                                                    id="title"
                                                    name="title"
                                                    placeholder="Please give a title"
                                                    required
                                                />
                                                <has-error
                                                    :form="form"
                                                    field="title"
                                                ></has-error>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group row">
                                            <label
                                                class="col-sm-2 col-form-label width"
                                                >Validity:</label
                                            >
                                            <vue-datepicker-local
                                                inputClass="text-dark"
                                                v-model="dateRange"
                                                range-separator="to"
                                                :local="local"
                                                :disabled-date="disabledDate"
                                            />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <span class="news"></span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <label class="mb-2 col-form-label"
                                            >Output Preview:</label
                                        >
                                        <div class="callout callout-danger">
                                            <button
                                                type="button"
                                                data-dismiss="callout"
                                                aria-hidden="true"
                                                class="close"
                                            >
                                                ×
                                            </button>
                                            <span v-html="newsText"></span>
                                            <p>
                                                Dear valued customer, last
                                                warning.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer" style="display: block;">
                                <button class="btn btn-primary" @click="submit">
                                    Submit
                                </button>
                            </div>
                            <!-- /.card-footer-->
                        </div>
                    </div>
                </div>
                <!-- ./row -->
                <div class="row">
                    <div class="col-12">
                        <div class="callout callout-danger">
                            <button
                                type="button"
                                data-dismiss="callout"
                                aria-hidden="true"
                                class="close"
                            >
                                ×
                            </button>
                            <span v-html="newsText"></span>
                            <p>Dear valued customer, last warning.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- ./content -->
    </div>
</template>

<script>
import VueDatepickerLocal from "vue-datepicker-local";
import moment from "moment";

export default {
    components: { VueDatepickerLocal },
    data() {
        return {
            newsText: "abc",
            form: new Form({}),
            today: moment(),
            time: new Date(),
            dateRange: [
                moment().format(),
                moment()
                    .add("30", "days")
                    .format()
            ],
            local: {
                dow: 0, // Sunday is the first day of the week
                hourTip: "Select Hour", // tip of select hour
                minuteTip: "Select Minute", // tip of select minute
                secondTip: "Select Second", // tip of select second
                yearSuffix: "", // suffix of head year
                monthsHead: "January_February_March_April_May_June_July_August_September_October_November_December".split(
                    "_"
                ), // months of head
                months: "Jan_Feb_Mar_Apr_May_Jun_Jul_Aug_Sep_Oct_Nov_Dec".split(
                    "_"
                ), // months of panel
                weeks: "Su_Mo_Tu_We_Th_Fr_Sa".split("_"), // weeks,
                cancelTip: "cancel",
                submitTip: "confirm"
            }
        };
    },
    methods: {
        disabledDate(time) {
            return time < this.today;
        },
        submit() {
            this.newsText = $(".news").summernote("code");
        }
    },
    mounted() {
        $(function() {
            $(".news").summernote({
                placeholder: "Hi Write something on here...",
                tabsize: 2,
                height: 420
            });
        });
    },
    beforeDestroy() {
        //document.head.removeChild(this.jsScript);
    }
};
</script>
<style>
.datepicker-range .datepicker-popup {
    width: 415px !important;
}
</style>
