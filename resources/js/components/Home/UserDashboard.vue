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
                                    <p class="mb-4">Total Requests</p>
                                    <p class="fs-30 mb-2" style="cursor:pointer"
                                        @click="redirectTo('/user-document-requests')">
                                        {{ dashboardData ? dashboardData.total_document_request : "0" }}
                                    </p>
                                    <p>as of Today</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-4 stretch-card transparent">
                            <div class="card card-dark-blue">
                                <div class="card-body">
                                    <p class="mb-4">Total Copy Requests</p>
                                    <p class="fs-30 mb-2" style="cursor:pointer"
                                        @click="redirectTo('/user-document-copy-requests')">
                                        {{ dashboardData ? dashboardData.total_document_copy_request : "0" }}
                                    </p>
                                    <p>as of Today</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-4 stretch-card transparent">
                            <div class="card card-light-blue">
                                <div class="card-body">
                                    <p class="mb-4">Total Documents</p>
                                    <p class="fs-30 mb-2" style="cursor:pointer"
                                        @click="redirectTo('/user-document-uploads')">
                                        {{ dashboardData ? dashboardData.total_document_upload : "0" }}
                                    </p>
                                    <p>as of Today</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 grid-margin stretch-card" v-if="toDiscussDocuments.length > 0">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title" title="For Process Owner">To Discuss Documents</h4>

                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <th class="pt-1">
                                                    Control Code
                                                </th>
                                                <th class="pt-1">
                                                    Title
                                                </th>
                                                <th class="pt-1">
                                                    Company
                                                </th>
                                                <th class="pt-1">
                                                    Department
                                                </th>
                                                <th class="pt-1">
                                                    Category
                                                </th>
                                                <th class="pt-1">
                                                    Effective Date
                                                </th>
                                                <th class="pt-1">
                                                    Is Discuss?
                                                </th>
                                            </thead>
                                            <tbody>
                                                <tr v-for="(document, index) in toDiscussDocuments" :key="index">
                                                    <td>
                                                        {{ document.control_code }}
                                                    </td>
                                                    <td>
                                                        {{ document.title }}
                                                    </td>
                                                    <td>
                                                        {{ document.company_info ? document.company_info.company_name : "" }}
                                                    </td>
                                                    <td>
                                                        {{ document.department_info ? document.department_info.department : "" }}
                                                    </td>
                                                    <td>
                                                        {{ document.document_category_info ? document.document_category_info.category_description : "" }}
                                                    </td>
                                                    <td>
                                                        {{ document.effective_date }}
                                                    </td>
                                                    <td>
                                                        <button v-if="document.is_discussed == '1'" type="button"
                                                            class="btn btn-inverse-success btn-rounded btn-icon"
                                                            title="Discussed">
                                                            <i class="ti-check"></i>
                                                        </button>
                                                        <button v-else type="button"
                                                            class="btn btn-inverse-primary btn-rounded btn-icon"
                                                            title="Tag as Discussed" @click="tagAsDiscuss(document)">
                                                            <i class="ti-help-alt"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>

                                    </div>
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

            toDiscussDocuments: [],
        }
    },
    created() {
        this.getDashboardData();
        this.getToDiscussDocuments();
    },
    methods: {
        redirectTo(url) {
            window.location.href = url;
        },
        getDashboardData() {
            let v = this;
            v.dashboardData = [];
            axios.get('/user-dashboard-data')
                .then(response => {
                    v.dashboardData = response.data;
                })
                .catch(error => {

                })
        },
        tagAsDiscuss(document) {
            let v = this;
            if (document) {
                let formData = new FormData();
                formData.append('id', document.id ? document.id : "");
                formData.append('is_discussed', "1");
                axios.post(`/user-document-uploads/is-discuss-document`, formData)
                    .then(response => {
                        if (response.data.status == "success") {
                            var index = v.toDiscussDocuments.findIndex(item => item.id == document.id);
                            v.toDiscussDocuments.splice(index, 1, response.data.document_upload);
                        }
                    })
                    .catch(error => {
                        v.errors = error.response.data.errors;
                    })
            }
        },
        getToDiscussDocuments() {
            let v = this;
            v.toDiscussDocuments = [];
            axios.get('/user-document-uploads/to-discuss-documents')
                .then(response => {
                    v.toDiscussDocuments = response.data;
                })
                .catch(error => {

                })
        }
    },
}
</script>

<style lang="scss" scoped>

</style>