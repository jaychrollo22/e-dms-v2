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
                                    <p class="mb-4">Document Requests</p>
                                    <p class="fs-30 mb-2" style="cursor:pointer"
                                        @click="redirectTo('/user-document-requests')">
                                        {{ dashboardData? dashboardData.total_document_request : "0" }}
                                    </p>
                                    <p>Total</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-4 stretch-card transparent">
                            <div class="card card-dark-blue">
                                <div class="card-body">
                                    <p class="mb-4">Document Copy Requests</p>
                                    <p class="fs-30 mb-2" style="cursor:pointer"
                                        @click="redirectTo('/user-document-copy-requests')">
                                        {{ dashboardData? dashboardData.total_document_copy_request : "0" }}
                                    </p>
                                    <p>Total</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-4 stretch-card transparent">
                            <div class="card card-light-blue">
                                <div class="card-body">
                                    <p class="mb-4">Documents</p>
                                    <p class="fs-30 mb-2" style="cursor:pointer"
                                        @click="redirectTo('/user-document-uploads')">
                                        {{ dashboardData? dashboardData.total_document_upload : "0" }}
                                    </p>
                                    <p>Total</p>
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
                                                        <button v-if="document.is_discussed == '1'"
                                                            @click="viewDocument(document)" type="button"
                                                            class="btn btn-inverse-success btn-rounded btn-icon"
                                                            title="Tag as Discussed">
                                                            <i class="ti-check"></i>
                                                        </button>
                                                        <button v-else @click="viewDocument(document)" type="button"
                                                            class="btn btn-inverse-primary btn-rounded btn-icon"
                                                            title="View Document">
                                                            <i class="ti-eye"></i>
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
        <div class="modal fade" id="document-view-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true" data-backdrop="static">
            <div class="modal-dialog modal-md modal-document-view" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="col-12 modal-title">
                            {{ view_document? view_document.control_code + ' | ' + view_document.title : "" }}
                        </h5>


                    </div>
                    <div class="modal-body" v-if="view_document">
                        <vue-pdf-embed v-if="view_document.attachment_signed_copy"
                            :source="'document-uploads-view-signed-copy?id=' + view_document.id" class="src-pdf mt-2" />
                    </div>
                    <div class="modal-footer">
                        <button v-if="view_document.is_discussed == '' || view_document.is_discussed == null"
                            type="button" class="btn btn-md btn-primary btn-rounded" title="Tag as Discussed"
                            @click="tagAsDiscuss(view_document)">
                            Tag as Discussed
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Swal from 'sweetalert2'
import VuePdfEmbed from 'vue-pdf-embed/dist/vue2-pdf-embed'
export default {
    components: {
        VuePdfEmbed,
    },
    data() {
        return {
            dashboardData: '',
            errors: '',
            view_document: '',
            toDiscussDocuments: [],
        }
    },
    created() {
        this.getDashboardData();
        this.getToDiscussDocuments();
    },
    methods: {
        viewDocument(document) {
            this.view_document = document;
            $('#document-view-modal').modal('show');
        },
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
                            Swal.fire('Document request has been tag as discussed.', '', 'success');

                            $('#document-view-modal').modal('hide');
                            this.view_document = '';
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