<template>
    <div>
        <div class="content-wrapper">
            <div class="row justify-content-center">
                <div class="col-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Document Requests</h4>
                            <div class="d-flex flex-wrap justify-content-end">
                                <a href="/document-requests/create" class="btn btn-primary">New Request</a>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <div class="input-group-prepend bg-transparent">
                                                <span class="input-group-text bg-transparent border-right-0">
                                                    <i class="ti-search text-primary"></i>
                                                </span>
                                            </div>
                                            <input v-model="filterData.search" @keyup="searchKeyUp" type="text"
                                                name="search" class="form-control form-control-lg border-left-0"
                                                id="search" placeholder="Search Requests">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th class="pt-1">
                                                DICR No.
                                            </th>
                                            <th class="pt-1">
                                                Requested Date
                                            </th>
                                            <th class="pt-1">
                                                Requestors Name
                                            </th>
                                            <th class="pt-1">
                                                Department
                                            </th>
                                            <th class="pt-1">
                                                Title
                                            </th>
                                            <th class="pt-1">
                                                Proposed Effective Date
                                            </th>
                                            <th class="pt-1">
                                                Type of Request
                                            </th>
                                            <th class="pt-1">
                                                Status
                                            </th>
                                            <th class="pt-1">
                                                Action
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(request, index) in items" :key="index">
                                            <td>
                                                {{ request.dicr_number }}
                                            </td>
                                            <td>
                                                {{ request.requested_date }}
                                            </td>
                                            <td>
                                                {{ request.requestor_info.name }}
                                            </td>
                                            <td>
                                                {{ request.department_info.department }}
                                            </td>
                                            <td>
                                                {{ request.title }}
                                            </td>
                                            <td>
                                                {{ request.proposed_effective_date }}
                                            </td>
                                            <td>
                                                <div :class="getTypeStyle(request.type_of_request)">
                                                    {{ request.type_of_request }}
                                                </div>
                                            </td>
                                            <td>
                                                <div :class="getStatusStyle(request.status)">
                                                    {{ request.status }}
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-inverse-info btn-rounded btn-icon"
                                                    title="View Request" @click="viewRequest(request)">
                                                    <i class="ti-pencil"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <table-pagination v-if="items.length > 0" :pagination="pagination"
                                v-on:updatePage="goToPage" v-on:doChangeLimit="changePageCount" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- View Request -->
        <div class="modal fade" id="request-view-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true" data-backdrop="static">
            <div class="modal-dialog modal-md modal-document-view" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="col-12 modal-title">View Document Request</h5>
                    </div>
                    <div class="modal-body">
                        <div class="row" v-if="document_request">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Title</label>
                                        <input type="text" class="form-control" name="Title" placeholder="Title"
                                            v-model="document_request.title" :disabled="disableDocumentRequest">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Proposed Effective Date</label>
                                        <input type="date" class="form-control" name="Propose Effective Date"
                                            v-model="document_request.proposed_effective_date"
                                            :disabled="disableDocumentRequest">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Type of Request</label>
                                        <div class="form-group row">
                                            <div class="col-sm-3">
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                        <input v-model="document_request.type_of_request" type="radio"
                                                            class="form-check-input" id="New" value="New"
                                                            :disabled="disableDocumentRequest">
                                                        New
                                                        <i class="input-helper"></i></label>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                        <input v-model="document_request.type_of_request" type="radio"
                                                            class="form-check-input" id="Revision" value="Revision"
                                                            :disabled="disableDocumentRequest">
                                                        Revision
                                                        <i class="input-helper"></i></label>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                        <input v-model="document_request.type_of_request" type="radio"
                                                            class="form-check-input" id="Discontinuance"
                                                            value="Discontinuance" :disabled="disableDocumentRequest">
                                                        Discontinuance
                                                        <i class="input-helper"></i></label>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                        <input v-model="document_request.type_of_request" type="radio"
                                                            class="form-check-input" id="Obsolete" value="Obsolete"
                                                            :disabled="disableDocumentRequest">
                                                        Obsolete
                                                        <i class="input-helper"></i></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Type of Document Information</label>
                                        <div class="form-group row">
                                            <div class="col-sm-3">
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                        <input v-model="document_request.type_of_documented_information"
                                                            type="radio" class="form-check-input" id="Policy"
                                                            value="Policy" :disabled="disableDocumentRequest">
                                                        Policy
                                                        <i class="input-helper"></i></label>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                        <input v-model="document_request.type_of_documented_information"
                                                            type="radio" class="form-check-input" id="Procedure"
                                                            value="Procedure" :disabled="disableDocumentRequest">
                                                        Procedure
                                                        <i class="input-helper"></i></label>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                        <input v-model="document_request.type_of_documented_information"
                                                            type="radio" class="form-check-input" id="Form" value="Form"
                                                            :disabled="disableDocumentRequest">
                                                        Form
                                                        <i class="input-helper"></i></label>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                        <input v-model="document_request.type_of_documented_information"
                                                            type="radio" class="form-check-input" id="Others"
                                                            value="Others" :disabled="disableDocumentRequest">
                                                        Others
                                                        <i class="input-helper"></i></label>
                                                </div>
                                            </div>
                                            <div class="col-sm-12"
                                                v-if="document_request.type_of_documented_information == 'Others'">
                                                <div class="form-group"
                                                    v-if="document_request.type_of_documented_information == 'Others'">
                                                    <input type="text" class="form-control" placeholder="Others Specify"
                                                        v-model="document_request.type_of_documented_information_others"
                                                        :disabled="disableDocumentRequest">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="">Description & Purpose of Documentation Request:</label>
                                        <textarea v-model="document_request.description_purpose_of_documentation"
                                            class="form-control" cols="30" rows="5"
                                            placeholder="Description & Purpose of Documentation Request"
                                            :disabled="disableDocumentRequest"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group" v-if="disableDocumentRequest">
                                        <input v-if="document_request.document_upload_info" type="text"
                                            class="form-control" disabled
                                            v-model="document_request.document_upload_info.title">
                                    </div>
                                    <div v-else-if="document_request.type_of_request == 'Revision' || document_request.type_of_request == 'Discontinuance' || document_request.type_of_request == 'Obsolete'"
                                        class="form-group">
                                        <label for="">Select Document</label>
                                        <multiselect v-model="document_request.document_upload_id"
                                            :options="document_uploads" placeholder="Select Document" label="title"
                                            track-by="id">
                                        </multiselect>
                                        <span class="text-danger"
                                            v-if="errors.attachment_file">{{ errors.attachment_file[0] }}</span>
                                    </div>

                                    <div class="form-group"
                                        v-if="document_request.type_of_request == 'New' || document_request.type_of_request == 'Revision'">
                                        <label for="">Attachment - Draft</label>
                                        <input @change="uploadAttachment" name="attachment_file" type="file"
                                            accept="application/*" id="attachment_file" class="form-control"
                                            :disabled="disableDocumentRequest">
                                        <span class="text-danger"
                                            v-if="errors.attachment_file">{{ errors.attachment_file[0] }}</span>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div v-if="document_request.attachment_file">
                                        <a :href="('storage/document_requests/' + document_request.attachment_file)"
                                            class="btn btn-outline-info btn-icon-text">View Attachment
                                            <i class="ti-download btn-icon-append"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button v-if="disableDocumentRequest" disabled class="btn btn-primary btn-md">Save</button>
                        <button v-else @click="updateDocumentRequest" :disabled="saveDisable"
                            class="btn btn-primary btn-md">{{ saveDisable ? 'Saving...' : 'Save' }}</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>
<style src="vue-multiselect/dist/vue-multiselect.min.css">

</style>
<script>
import listFormMixins from '../../list-form-mixins.vue';
import Multiselect from 'vue-multiselect'
import Swal from 'sweetalert2'
export default {
    components: {
        Multiselect
    },
    mixins: [listFormMixins],
    data() {
        return {
            endpoint: '/user-document-requests',
            document_requests: [],
            errors: [],
            document_request: '',
            disableDocumentRequest: true,
            document_attachment: '',
            saveDisable: false,

            attachment_file: '',

            document_uploads: []
        }
    },
    created() {
        this.fetchList();
        this.getDocumentUploads();
    },
    methods: {
        getDocumentUploads() {
            let v = this;
            v.document_uploads = [];
            axios.get('/document-uploads-request-options')
                .then(response => {
                    v.document_uploads = response.data;
                })
                .catch(error => {
                    v.errors = error.response.data.error;
                })
        },
        uploadAttachment(e) {
            var files = e.target.files || e.dataTransfer.files;
            if (!files.length)
                return;
            this.attachment_file = files[0];
        },
        updateDocumentRequest() {
            let v = this;
            v.saveDisable = true;
            Swal.fire({
                text: "Are you sure you want to save this document request?",
                icon: "question",
                showCancelButton: true,
                buttonsStyling: false,
                confirmButtonText: "Yes, Save",
                cancelButtonText: "No, cancel",
                customClass: {
                    confirmButton: "btn font-weight-bold btn-primary",
                    cancelButton: "btn font-weight-bold btn-default"
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    let formData = new FormData();
                    formData.append('id', v.document_request.id ? v.document_request.id : "");

                    formData.append('title', v.document_request.title ? v.document_request.title : "");
                    formData.append('proposed_effective_date', v.document_request.proposed_effective_date ? v.document_request.proposed_effective_date : "");
                    formData.append('type_of_request', v.document_request.type_of_request ? v.document_request.type_of_request : "");
                    formData.append('type_of_documented_information', v.document_request.type_of_documented_information ? v.document_request.type_of_documented_information : "");
                    formData.append('type_of_documented_information_others', v.document_request.type_of_documented_information_others ? v.document_request.type_of_documented_information_others : "");
                    formData.append('description_purpose_of_documentation', v.document_request.description_purpose_of_documentation ? v.document_request.description_purpose_of_documentation : "");
                    formData.append('document_upload_id', v.document_request.document_upload_id ? v.document_request.document_upload_id.id : "");

                    if (v.attachment_file) {
                        formData.append('attachment_file', v.attachment_file ? v.attachment_file : "");
                    }

                    axios.post(`/user-document-requests/update`, formData)
                        .then(response => {
                            if (response.data.status == "success") {
                                Swal.fire('Document request has been updated!', '', 'success');
                                v.saveDisable = false;
                                var index = this.items.findIndex(item => item.id == v.document_request.id);
                                this.items.splice(index, 1, response.data.document_request);
                                $('#request-view-modal').modal('hide');
                            } else {
                                Swal.fire('Error: Cannot saved. Please try again.', '', 'error');
                                v.saveDisable = false;
                            }
                        })
                        .catch(error => {
                            Swal.fire({
                                text: 'Sorry, looks like there are some errors detected, please try again..',
                                icon: "error",
                                confirmButtonText: "Ok, got it!"
                            }).then(okay => {
                                v.saveDisable = false;
                                v.errors = error.response.data.errors;
                            });

                        })

                } else {
                    v.saveDisable = false;
                }
            })
        },
        getTypeStyle(type) {
            if (type == 'New') {
                return 'badge badge-primary';
            } else if (type == 'Revision') {
                return 'badge badge-success';
            } else if (type == 'Obsolete') {
                return 'badge badge-danger';
            } else if (type == 'Discontinuance') {
                return 'badge badge-warning';
            }
        },
        getStatusStyle(type) {
            if (type == 'Pending') {
                return 'badge badge-info';
            } else if (type == 'Approved') {
                return 'badge badge-success';
            } else if (type == 'Disapproved') {
                return 'badge badge-danger';
            }
        },
        validateFileFormat(document) {
            if (document.indexOf('.pdf') != -1 || document.indexOf('.png') != -1 || document.indexOf('.jpg') != -1) {
                return true;
            } else {
                return false;
            }
        },
        viewRequest(request) {
            if (request.status == 'Approved') {
                this.disableDocumentRequest = true;
            } else {
                this.disableDocumentRequest = false;
            }
            this.document_request = Object.assign({}, request);
            if (request.document_upload_info) {
                this.document_request.document_upload_id = request.document_upload_info;
            }

            $('#request-view-modal').modal('show');
        }
    },
}
</script>

<style lang="scss" scoped>

</style>