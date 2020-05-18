<template>
    <div>
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>New User</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item">
                                <a href="#">Users</a>
                            </li>
                            <li class="breadcrumb-item active">New User</li>
                        </ol>
                    </div>
                </div>
            </div>
            <!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header p-2">
                                <ul class="nav nav-pills">
                                    <li class="nav-item">
                                        <a
                                            class="nav-link active"
                                            href="#general"
                                            data-toggle="tab"
                                            >General</a
                                        >
                                    </li>
                                    <li class="nav-item">
                                        <a
                                            class="nav-link"
                                            href="#billing"
                                            data-toggle="tab"
                                            >Billing</a
                                        >
                                    </li>
                                    <li class="nav-item">
                                        <a
                                            class="nav-link"
                                            href="#profile"
                                            data-toggle="tab"
                                            >Profile</a
                                        >
                                    </li>
                                </ul>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <div class="overlay-wrapper">
                                    <div class="tab-content">
                                        <!-- /.tab-pane -->
                                        <div
                                            class="tab-pane active"
                                            id="general"
                                        >
                                            <form
                                                class="form-horizontal"
                                                @submit.prevent="createUser"
                                            >
                                                <div class="form-group row">
                                                    <label
                                                        for="name"
                                                        class="col-sm-2 col-form-label"
                                                        >Name</label
                                                    >
                                                    <div class="col-sm-10">
                                                        <input
                                                            type="text"
                                                            class="form-control"
                                                            :class="{
                                                                'is-invalid': form.errors.has(
                                                                    'name'
                                                                )
                                                            }"
                                                            id="name"
                                                            v-model="form.name"
                                                            name="name"
                                                            placeholder="Name"
                                                            required
                                                        />
                                                        <has-error
                                                            :form="form"
                                                            field="name"
                                                        ></has-error>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label
                                                        for="email"
                                                        class="col-sm-2 col-form-label"
                                                        >Email</label
                                                    >
                                                    <div class="col-sm-10">
                                                        <input
                                                            type="email"
                                                            class="form-control"
                                                            :class="{
                                                                'is-invalid': form.errors.has(
                                                                    'email'
                                                                )
                                                            }"
                                                            id="email"
                                                            v-model="form.email"
                                                            name="email"
                                                            placeholder="Email address"
                                                            required
                                                        />
                                                        <has-error
                                                            :form="form"
                                                            field="email"
                                                        ></has-error>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label
                                                        for="password"
                                                        class="col-sm-2 col-form-label"
                                                        >Password</label
                                                    >
                                                    <div class="col-sm-10">
                                                        <input
                                                            type="password"
                                                            class="form-control"
                                                            id="password"
                                                            :class="{
                                                                'is-invalid': form.errors.has(
                                                                    'password'
                                                                )
                                                            }"
                                                            name="password"
                                                            v-model="
                                                                form.password
                                                            "
                                                            placeholder="Password"
                                                            required
                                                        />
                                                        <has-error
                                                            :form="form"
                                                            field="password"
                                                        ></has-error>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label
                                                        for="repassword"
                                                        class="col-sm-2 col-form-label"
                                                        >Re-Type Password</label
                                                    >
                                                    <div class="col-sm-10">
                                                        <input
                                                            type="password"
                                                            class="form-control"
                                                            id="repassword"
                                                            :class="{
                                                                'is-invalid': form.errors.has(
                                                                    'repassword'
                                                                )
                                                            }"
                                                            name="repassword"
                                                            v-model="
                                                                form.repassword
                                                            "
                                                            placeholder="Re-type Password"
                                                            required
                                                        />
                                                        <has-error
                                                            :form="form"
                                                            field="repassword"
                                                        ></has-error>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label
                                                        for="type"
                                                        class="col-sm-2 col-form-label"
                                                        >Type</label
                                                    >
                                                    <div class="col-sm-10">
                                                        <select
                                                            name="type"
                                                            v-model="form.type"
                                                            id="type"
                                                            class="form-control"
                                                            :class="{
                                                                'is-invalid': form.errors.has(
                                                                    'type'
                                                                )
                                                            }"
                                                        >
                                                            <option value
                                                                >Select User
                                                                Role</option
                                                            >
                                                            <option
                                                                value="admin"
                                                                >Admin</option
                                                            >
                                                            <option value="user"
                                                                >Standard
                                                                User</option
                                                            >
                                                        </select>
                                                        <has-error
                                                            :form="form"
                                                            field="type"
                                                        ></has-error>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <!-- /.tab-pane -->
                                        <div class="tab-pane" id="billing">
                                            <form
                                                class="form-horizontal"
                                                @submit.prevent="createUser"
                                            >
                                                <div class="form-group row">
                                                    <label
                                                        for="billaddr"
                                                        class="col-sm-2 col-form-label"
                                                        >Billing Address</label
                                                    >
                                                    <div class="col-sm-10">
                                                        <input
                                                            type="textarea"
                                                            class="form-control"
                                                            :class="{
                                                                'is-invalid': form.errors.has(
                                                                    'billaddr'
                                                                )
                                                            }"
                                                            id="billaddr"
                                                            v-model="
                                                                form.billaddr
                                                            "
                                                            name="billaddr"
                                                            placeholder="billaddr"
                                                        />
                                                        <has-error
                                                            :form="form"
                                                            field="billaddr"
                                                        ></has-error>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <!-- /.tab-pane -->
                                        <div
                                            class="tab-pane"
                                            id="profile"
                                        ></div>
                                        <!-- /.tab-pane -->
                                        <!-- /.form -->
                                    </div>
                                    <!-- /.tab-content -->
                                    <div class="overlay" v-if="inprogress">
                                        <i
                                            class="fas fa-3x fa-sync-alt fa-spin"
                                        ></i>
                                        <div class="text-bold pl-2">
                                            Loading...
                                        </div>
                                    </div>
                                </div>
                                <!-- /.overlay-wrapper -->
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer">
                                <button
                                    type="button"
                                    class="btn btn-info"
                                    @click.prevent="createUser"
                                >
                                    Submit
                                </button>
                                <button
                                    type="button"
                                    class="btn btn-default float-right"
                                    @click.prevent="goBack"
                                >
                                    Cancel
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </section>
    </div>
</template>

<script>
export default {
    data() {
        return {
            form: new Form({
                name: "",
                email: "",
                password: "",
                repassword: "",
                type: "",
                billaddr: "Halomoan"
            }),
            inprogress: false
        };
    },
    methods: {
        createUser() {
            this.$Progress.start();
            this.inprogress = true;

            this.form.post("api/user").then(({ data }) => {
                Toast.fire({
                    icon: "success",
                    title: "User created successfully"
                });

                this.goBack();
            });

            this.$Progress.finish();
            this.inprogress = false;
        },
        goBack() {
            window.history.length > 1
                ? this.$router.go(-1)
                : this.$router.push("/users");
        }
    },
    created() {
        this.inprogress = false;
    }
};
</script>
