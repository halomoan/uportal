<template>
    <div>
        <div class="content-header pb-1">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Users</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item">
                                <a href="#">Home</a>
                            </li>
                            <li class="breadcrumb-item active">Users</li>
                        </ol>
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
        pdf.embed("./img/HPR.pdf", "#pdf-content");
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
