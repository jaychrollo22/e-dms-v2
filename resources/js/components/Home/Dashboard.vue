<template>
    <div>

        <div class="content-wrapper">
            <div class="row">
                <div class="col-md-12 grid-margin">
                    <div class="row">
                        <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                            <h3 class="font-weight-bold">Welcome to E-DMS</h3>
                            <h6 class="font-weight-normal mb-0">All systems are running smoothly!</h6>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-md-3 mb-4 stretch-card transparent">
                            <div class="card card-tale">
                                <div class="card-body">
                                    <p class="mb-4">Pending Requests</p>
                                    <p class="fs-30 mb-2" style="cursor:pointer"
                                        @click="redirectTo('/document-requests?status=Pending')">
                                        {{ dashboardData? dashboardData.new_document_request : "0" }}
                                    </p>
                                    <p>as of Today</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-4 stretch-card transparent">
                            <div class="card card-dark-blue">
                                <div class="card-body">
                                    <p class="mb-4">New Copy Requests</p>
                                    <p class="fs-30 mb-2" style="cursor:pointer"
                                        @click="redirectTo('/document-copy-requests?status=New')">
                                        {{ dashboardData? dashboardData.new_document_copy_request : "0" }}
                                    </p>
                                    <p>as of Today</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-4 stretch-card transparent">
                            <div class="card card-light-blue">
                                <div class="card-body">
                                    <p class="mb-4">New Access Requests</p>
                                    <p class="fs-30 mb-2" style="cursor:pointer"
                                        @click="redirectTo('/access-requests?status=New')">
                                        {{ dashboardData? dashboardData.new_access_request : "0" }}
                                    </p>
                                    <p>as of Today</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-4 stretch-card transparent">
                            <div class="card card-light-danger">
                                <div class="card-body">
                                    <p class="mb-4">Pending Document Uploads</p>
                                    <p class="fs-30 mb-2" style="cursor:pointer"
                                        @click="redirectTo('/document-uploads?status=Pending')">
                                        {{ dashboardData? dashboardData.new_document_upload : "0" }}
                                    </p>
                                    <p>as of Today</p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
export default {
    data() {
        return {
            dashboardData: '',
            errors: '',
        }
    },
    created() {
        this.getDashboardData();
    },
    methods: {
        redirectTo(url) {
            window.location.href = url;
        },
        getDashboardData() {
            let v = this;
            v.dashboardData = [];
            axios.get('/dashboard-data')
                .then(response => {
                    v.dashboardData = response.data;
                })
                .catch(error => {
                    v.errors = error.response.data.error;
                })
        }
    },

}
</script>

<style lang="scss" scoped>

</style>