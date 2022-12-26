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
                                <div class="col-md-3">
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
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <div class="input-group-prepend bg-transparent">
                                                <span class="input-group-text bg-transparent border-right-0">
                                                    <i class="ti-settings text-primary"></i>
                                                </span>
                                            </div>
                                            <select v-model="filterData.company" @change="searchKeyUp"
                                                class="form-control form-control-lg border-left-0" name="company"
                                                id="company">
                                                <option value="">Choose Company</option>
                                                <option v-for="(company, index) in companies" :key="index"
                                                    :value="company.id">
                                                    {{ company.company_name }}
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <div class="input-group-prepend bg-transparent">
                                                <span class="input-group-text bg-transparent border-right-0">
                                                    <i class="ti-settings text-primary"></i>
                                                </span>
                                            </div>
                                            <select v-model="filterData.department" @change="searchKeyUp"
                                                class="form-control form-control-lg border-left-0" name="department"
                                                id="department">
                                                <option value="">Choose Department</option>
                                                <option v-for="(department, index) in departments" :key="index"
                                                    :value="department.id">
                                                    {{ department.department }}
                                                </option>
                                            </select>
                                        </div>
                                        <span class="text-danger"
                                            v-if="errors.department">{{ errors.department[0] }}</span>
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
                                                <strong>{{ request.requestor_info.name }} <br></strong>
                                                <small>{{ request.company_info.company_name }} <br></small>
                                                <small>{{ request.department_info.department }} <br></small>
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
                                                    <i class="ti-eye"></i>
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
                                    <div class="form-group"
                                        v-if="document_request.type_of_request == 'Revision' || document_request.type_of_request == 'Discontinuance' || document_request.type_of_request == 'Obsolete'">
                                        <label for="">Selected Document</label>
                                        <input type="text"
                                            :value="document_request.document_upload_info.control_code + ' | ' + document_request.document_upload_info.title"
                                            class="form-control" :disabled="disableDocumentRequest">
                                    </div>
                                    <div v-if="document_request.attachment_file">
                                        <iframe id="frame-raw-file"
                                            v-if="validateFileFormat(document_request.attachment_file)"
                                            :src="('storage/document_requests/' + document_request.attachment_file)"
                                            frameborder="1" width="100%" height="700px"></iframe>
                                        <a v-else
                                            :href="('storage/document_requests/' + document_request.attachment_file)"
                                            class="btn btn-outline-info btn-icon-text">View Attachment
                                            <i class="ti-download btn-icon-append"></i>
                                        </a>
                                    </div>

                                </div>
                                <hr class="mt-3">
                                <div class="row">
                                    <div class="col-md-6 ">
                                        <label for="">For Approval Status</label>
                                        <div class="form-group row">
                                            <div class="col-sm-3">
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                        <input v-model="document_request.status" type="radio"
                                                            class="form-check-input" id="Approved" value="Approved">
                                                        Approve
                                                        <i class="input-helper"></i></label>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                        <input v-model="document_request.status" type="radio"
                                                            class="form-check-input" id="Disapproved"
                                                            value="Disapproved">
                                                        Disapprove
                                                        <i class="input-helper"></i></label>
                                                </div>
                                            </div>
                                        </div>
                                        <span class="text-danger" v-if="errors.status">{{ errors.status[0] }}</span>
                                    </div>
                                    <div class="col-md-12"
                                        v-if="document_request.status == 'Approved' || document_request.status == 'Disapproved'">
                                        <label for="">Status Remarks</label>
                                        <div class="form-group">
                                            <textarea v-model="document_request.status_remarks" cols="30" rows="5"
                                                class="form-control" placeholder="Approval Remarks"></textarea>
                                            <span class="text-danger"
                                                v-if="errors.status_remarks">{{ errors.status_remarks[0] }}</span>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <button class="btn btn-sm btn-primary" @click="updateDocumentRequest"
                            :disabled="saveDisable">{{ saveDisable ? 'Saving...' : 'Save Changes' }}</button>
                    </div>

                </div>
            </div>
        </div>

    </div>
</template>

<script>
import listFormMixins from '../../list-form-mixins.vue';
import Swal from 'sweetalert2'
export default {
    mixins: [listFormMixins],
    data() {
        return {
            endpoint: '/document-requests',
            document_requests: [],
            errors: [],
            document_request: '',
            disableDocumentRequest: true,
            document_attachment: '',
            saveDisable: false,

            companies: [],
            departments: [],
            filterData: {
                company: '',
                department: '',
            }
        }
    },
    created() {
        this.fetchList();
        this.fetchCompanies();
        this.fetchDepartments();
    },
    methods: {
        fetchCompanies() {
            let v = this;
            v.companies = [];
            axios.get('/companies-options')
                .then(response => {
                    v.companies = response.data;
                })
                .catch(error => {
                    v.errors = error.response.data.error;
                })
        },
        fetchDepartments() {
            let v = this;
            v.departments = [];
            axios.get('/departments-options')
                .then(response => {
                    v.departments = response.data;
                })
                .catch(error => {
                    v.errors = error.response.data.error;
                })
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
                    formData.append('status', v.document_request.status ? v.document_request.status : "");
                    formData.append('status_remarks', v.document_request.status_remarks ? v.document_request.status_remarks : "");
                    axios.post(`/document-requests/update-approval`, formData)
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
            this.document_request = Object.assign({}, request);
            $('#request-view-modal').modal('show');
        }
    },
}
</script>

<style lang="scss" scoped>

</style>