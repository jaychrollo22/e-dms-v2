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
                        <!-- To Discuss Documents -->
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
                        <!-- Document Copy Request For Approval -->
                        <div class="col-md-12 grid-margin stretch-card"
                            v-if="immediateHeadCopyRequetsForApproval.length > 0">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title" title="For Process Owner">Document Copy Request For Approval
                                    </h4>

                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <th class="pt-1">
                                                    Requestor
                                                </th>
                                                <th class="pt-1">
                                                    Details
                                                </th>
                                            </thead>
                                            <tbody>
                                                <tr v-for="(document, index) in immediateHeadCopyRequetsForApproval"
                                                    :key="index">
                                                    <td>
                                                        {{ document.user_details_info ? document.user_details_info.name : "" }}
                                                    </td>
                                                    <td>
                                                        <div class="mt-2"
                                                            v-for="(copy_request, x) in document.pending_copy_requests"
                                                            :key="x">
                                                            Document :
                                                            {{ copy_request.document_upload_info.control_code + ' ' + copy_request.document_upload_info.title }}
                                                            |
                                                            Remarks : {{ copy_request.remarks }}
                                                            <button type="button"
                                                                class="btn btn-sm btn-outline-warning btn-fw"
                                                                @click="viewCopyRequest(copy_request)">{{ copy_request.immediate_head_approval }}</button>
                                                            <!-- <button
                                                                @click="viewDocument(copy_request.document_upload_info)"
                                                                type="button"
                                                                class="btn btn-inverse-primary btn-rounded btn-icon"
                                                                title="View Document">
                                                                <i class="ti-eye"></i>
                                                            </button> -->
                                                        </div>
                                                    </td>

                                                </tr>
                                            </tbody>
                                        </table>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Discontinuance Request For Approval -->
                        <div class="col-md-12 grid-margin stretch-card"
                            v-if="immediateHeadDiscontinuanceRequetsForApproval.length > 0">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title" title="For Process Owner">Document Discontinuance Request For
                                        Approval
                                    </h4>

                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <th class="pt-1">
                                                    Requestor
                                                </th>
                                                <th class="pt-1">
                                                    Details
                                                </th>
                                            </thead>
                                            <tbody>
                                                <tr v-for="(discontinuance_request, index) in immediateHeadDiscontinuanceRequetsForApproval"
                                                    :key="index">
                                                    <td>
                                                        {{ discontinuance_request.user_details_info ? discontinuance_request.user_details_info.name : "" }}
                                                    </td>
                                                    <td>
                                                        <div class="mt-2"
                                                            v-for="(request, x) in discontinuance_request.discontinuance_requests"
                                                            :key="x">
                                                            Document :
                                                            {{ request.document_upload_info.control_code + ' - ' + request.document_upload_info.title }}
                                                            |
                                                            Title :
                                                            {{ request.title }}
                                                            <button type="button"
                                                                class="btn btn-sm btn-outline-warning btn-fw"
                                                                @click="viewDiscontinuanceRequest(request)">{{ request.immediate_head_approval }}</button>
                                                            <!-- <button @click="viewDocument(request.document_upload_info)"
                                                                type="button"
                                                                class="btn btn-inverse-primary btn-rounded btn-icon"
                                                                title="View Document">
                                                                <i class="ti-eye"></i>
                                                            </button> -->
                                                        </div>
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
        <!-- View Document -->
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
        <!-- View Copy Request -->
        <div class="modal fade" id="copy-request-view-modal" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content" v-if="view_copy_request">
                    <div class="modal-header">
                        <h5 class="col-12 modal-title">
                            {{ view_copy_request.document_upload_info.title }}
                        </h5>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <label for="">For Approval</label>
                                <div class="form-group row">
                                    <div class="col-sm-3">
                                        <div class="form-check">
                                            <label class="form-check-label">
                                                <input v-model="view_copy_request.immediate_head_approval" type="radio"
                                                    class="form-check-input" id="Approved" value="Approved">
                                                Approve
                                                <i class="input-helper"></i></label>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-check">
                                            <label class="form-check-label">
                                                <input v-model="view_copy_request.immediate_head_approval" type="radio"
                                                    class="form-check-input" id="Disapproved" value="Disapproved">
                                                Disapprove
                                                <i class="input-helper"></i></label>
                                        </div>
                                    </div>
                                </div>
                                <span class="text-danger" v-if="errors.status">{{ errors.status[0] }}</span>
                            </div>
                            <div class="col-md-12">
                                <label for="">Status Remarks</label>
                                <div class="form-group">
                                    <textarea v-model="view_copy_request.immediate_head_approval_remarks" cols="30"
                                        rows="5" class="form-control" placeholder="Approval Remarks"></textarea>
                                    <span class="text-danger"
                                        v-if="errors.immediate_head_approval_remarks">{{ errors.immediate_head_approval_remarks[0] }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-md btn-primary btn-rounded"
                            :disabled="saveDisableCopyRequest" title="Approve"
                            @click="submitApprovalCopyRequest(view_copy_request)">
                            Submit
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- View Discontinuance Request -->
        <div class="modal fade" id="discontinuance-request-view-modal" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content" v-if="view_discontinuance_request">
                    <div class="modal-header">
                        <h5 class="col-12 modal-title">
                            {{ view_discontinuance_request.document_upload_info.title }}
                        </h5>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <label for="">For Approval</label>
                                <div class="form-group row">
                                    <div class="col-sm-3">
                                        <div class="form-check">
                                            <label class="form-check-label">
                                                <input v-model="view_discontinuance_request.immediate_head_approval"
                                                    type="radio" class="form-check-input" id="Approved"
                                                    value="Approved">
                                                Approve
                                                <i class="input-helper"></i></label>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-check">
                                            <label class="form-check-label">
                                                <input v-model="view_discontinuance_request.immediate_head_approval"
                                                    type="radio" class="form-check-input" id="Disapproved"
                                                    value="Disapproved">
                                                Disapprove
                                                <i class="input-helper"></i></label>
                                        </div>
                                    </div>
                                </div>
                                <span class="text-danger" v-if="errors.status">{{ errors.status[0] }}</span>
                            </div>
                            <div class="col-md-12">
                                <label for="">Status Remarks</label>
                                <div class="form-group">
                                    <textarea v-model="view_discontinuance_request.immediate_head_approval_remarks"
                                        cols="30" rows="5" class="form-control"
                                        placeholder="Approval Remarks"></textarea>
                                    <span class="text-danger"
                                        v-if="errors.immediate_head_approval_remarks">{{ errors.immediate_head_approval_remarks[0] }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-md btn-primary btn-rounded"
                            :disabled="saveDisableDiscontinuanceRequest" title="Approve"
                            @click="submitApprovalDiscontinuanceRequest(view_discontinuance_request)">
                            Submit
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- View Document -->
        <div class="modal fade" id="view-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
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
            immediateHeadCopyRequetsForApproval: [],
            immediateHeadDiscontinuanceRequetsForApproval: [],
            view_copy_request: '',
            saveDisableCopyRequest: false,
            view_discontinuance_request: '',
            saveDisableDiscontinuanceRequest: false,
        }
    },
    created() {
        this.getDashboardData();
        this.getToDiscussDocuments();
        this.getImmediateHeadCopyRequestsForApproval();
        this.getImmediateHeadDiscontinuanceRequestsForApproval();
    },
    methods: {
        submitApprovalDiscontinuanceRequest(discontinuance_request) {
            let v = this;
            v.saveDisableDiscontinuanceRequest = true;
            if (discontinuance_request) {
                let formData = new FormData();
                formData.append('id', discontinuance_request.id ? discontinuance_request.id : "");
                formData.append('immediate_head_approval', discontinuance_request.immediate_head_approval ? discontinuance_request.immediate_head_approval : "");
                formData.append('immediate_head_approval_remarks', discontinuance_request.immediate_head_approval_remarks ? discontinuance_request.immediate_head_approval_remarks : "");
                axios.post(`/document-requests/update-discontinuance-immediate-head-approval`, formData)
                    .then(response => {
                        v.saveDisableDiscontinuanceRequest = false;
                        if (response.data.status == "success") {
                            this.getImmediateHeadDiscontinuanceRequestsForApproval();
                            Swal.fire('Document discontinuance request has been ' + discontinuance_request.immediate_head_approval, '', 'success');
                            $('#discontinuance-request-view-modal').modal('hide');
                            this.view_discontinuance_request = '';
                        }
                    })
                    .catch(error => {
                        v.errors = error.response.data.errors;
                        v.saveDisableDiscontinuanceRequest = false
                    })
            }
        },
        viewDiscontinuanceRequest(discontinuance_request) {
            this.view_discontinuance_request = Object.assign({}, discontinuance_request);
            $('#discontinuance-request-view-modal').modal('show');
        },
        submitApprovalCopyRequest(copy_request) {
            let v = this;
            v.saveDisableCopyRequest = true;
            if (copy_request) {
                let formData = new FormData();
                formData.append('id', copy_request.id ? copy_request.id : "");
                formData.append('immediate_head_approval', copy_request.immediate_head_approval ? copy_request.immediate_head_approval : "");
                formData.append('immediate_head_approval_remarks', copy_request.immediate_head_approval_remarks ? copy_request.immediate_head_approval_remarks : "");
                axios.post(`/document-copy-requests/update-immediate-head-approval`, formData)
                    .then(response => {
                        v.saveDisableCopyRequest = false;
                        if (response.data.status == "success") {
                            this.getImmediateHeadCopyRequestsForApproval();
                            Swal.fire('Document copy request has been ' + copy_request.immediate_head_approval, '', 'success');
                            $('#copy-request-view-modal').modal('hide');
                            this.view_copy_request = '';
                        }
                    })
                    .catch(error => {
                        v.errors = error.response.data.errors;
                        v.saveDisableCopyRequest = false
                    })
            }
        },
        viewCopyRequest(copy_request) {
            this.view_copy_request = Object.assign({}, copy_request);
            $('#copy-request-view-modal').modal('show');
        },
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
        },
        getImmediateHeadCopyRequestsForApproval() {
            let v = this;
            v.immediateHeadCopyRequetsForApproval = [];
            axios.get('/immediate-heads-for-approval-copy-requests')
                .then(response => {
                    v.immediateHeadCopyRequetsForApproval = response.data;
                })
                .catch(error => {

                })
        },
        getImmediateHeadDiscontinuanceRequestsForApproval() {
            let v = this;
            v.immediateHeadDiscontinuanceRequetsForApproval = [];
            axios.get('/immediate-heads-for-approval-discontinuance-requests')
                .then(response => {
                    v.immediateHeadDiscontinuanceRequetsForApproval = response.data;
                })
                .catch(error => {

                })
        }
    },
}
</script>

<style lang="scss" scoped>

</style>