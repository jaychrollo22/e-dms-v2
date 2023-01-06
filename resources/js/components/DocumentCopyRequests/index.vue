<template>
    <div>
        <div class="content-wrapper">
            <div class="row justify-content-center">
                <div class="col-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Document Copy Requests</h4>
                            <div class="d-flex flex-wrap justify-content-end">
                                <!-- <a href="/document-copy-requests/create" class="btn btn-primary">New</a> -->
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
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th class="pt-1">
                                                Requested Date
                                            </th>
                                            <th class="pt-1">
                                                Requestors Name
                                            </th>
                                            <th class="pt-1">
                                                Requested Document
                                            </th>
                                            <th class="pt-1">
                                                File Copy Type
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
                                        <tr v-if="isProcessing">
                                            <td colspan="6">
                                                <div class="dot-opacity-loader">
                                                    <span></span>
                                                    <span></span>
                                                    <span></span>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr v-else v-for="(request, index) in items" :key="index">
                                            <td>
                                                {{ request.requested_date }}
                                            </td>
                                            <td>
                                                <strong>{{ request.requestor_info.name }} <br></strong>
                                                <small>{{ request.company_info.company_name }} <br></small>
                                                <small>{{ request.department_info.department }} <br></small>
                                            </td>
                                            <td>
                                                {{ request.document_upload_info.title }}
                                            </td>
                                            <td>
                                                {{ request.file_copy_type }}
                                            </td>
                                            <td>
                                                <div :class="getStatusStyle(request.status)">
                                                    {{ request.status }}
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-inverse-info btn-rounded btn-icon"
                                                    title="View Request" @click="viewCopyRequest(request)">
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
        <div class="modal fade" id="copy-request-view-modal" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
            <div class="modal-dialog modal-md modal-document-view" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="col-12 modal-title">View Document Copy Request</h5>
                    </div>
                    <div class="modal-body" v-if="document_copy_request">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Control Code</label>
                                    <input :value="document_copy_request.document_upload_info.control_code" type="text"
                                        class="form-control" :disabled="disableFields">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Document Title</label>
                                    <input :value="document_copy_request.document_upload_info.title" type="text"
                                        class="form-control" :disabled="disableFields">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <textarea v-model="document_copy_request.remarks" class="form-control" cols="30"
                                        rows="5" placeholder="Remarks" :disabled="disableFields"></textarea>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">File Copy Type</label>
                                    <div class="form-group row">
                                        <div class="col-sm-3">
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                    <input v-model="document_copy_request.file_copy_type" type="radio"
                                                        class="form-check-input" id="SoftCopy" value="Soft Copy"
                                                        :disabled="disableFields">
                                                    Soft Copy
                                                    <i class="input-helper"></i></label>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                    <input v-model="document_copy_request.file_copy_type" type="radio"
                                                        class="form-check-input" id="HardCopy" value="Hard Copy"
                                                        :disabled="disableFields">
                                                    Hard Copy
                                                    <i class="input-helper"></i></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="">For Approval Status</label>
                                <div class="form-group row">
                                    <div class="col-sm-3">
                                        <div class="form-check">
                                            <label class="form-check-label">
                                                <input v-model="document_copy_request.status" type="radio"
                                                    class="form-check-input" id="Approved" value="Approved">
                                                Approve
                                                <i class="input-helper"></i></label>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-check">
                                            <label class="form-check-label">
                                                <input v-model="document_copy_request.status" type="radio"
                                                    class="form-check-input" id="Disapproved" value="Disapproved">
                                                Disapprove
                                                <i class="input-helper"></i></label>
                                        </div>
                                    </div>
                                </div>
                                <span class="text-danger" v-if="errors.status">{{ errors.status[0] }}</span>
                            </div>
                            <div class="col-md-6" v-if="document_copy_request.status == 'Approved'">
                                <label for="">Expiration Date</label>
                                <div class="form-group">
                                    <input v-model="document_copy_request.expiration_date" type="date"
                                        class="form-control">
                                    <span class="text-danger"
                                        v-if="errors.expiration_date">{{ errors.expiration_date[0] }}</span>
                                </div>
                            </div>
                            <div class="col-md-12"
                                v-if="document_copy_request.status == 'Approved' || document_copy_request.status == 'Disapproved'">
                                <label for="">Status Remarks</label>
                                <div class="form-group">
                                    <textarea v-model="document_copy_request.status_remarks" cols="30" rows="5"
                                        class="form-control" placeholder="Approval Remarks"></textarea>
                                    <span class="text-danger"
                                        v-if="errors.status_remarks">{{ errors.status_remarks[0] }}</span>
                                </div>

                            </div>
                        </div>

                        <button class="btn btn-sm btn-primary" @click="updateDocumentCopyRequest"
                            :disabled="saveDisable">{{ saveDisable? 'Saving...': 'Save Changes' }}</button>

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
import Swal from 'sweetalert2'
import Multiselect from 'vue-multiselect'
export default {
    components: {
        Multiselect
    },
    mixins: [listFormMixins],
    data() {
        return {
            endpoint: '/document-copy-requests',
            document_requests: [],
            document_copy_request: "",
            disableFields: true,
            errors: [],
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
        this.getURLFilter();
        this.fetchList();
        this.fetchCompanies();
        this.fetchDepartments();
    },
    methods: {
        getURLFilter() {
            const queryString = window.location.search;
            const urlParams = new URLSearchParams(queryString);
            var status = urlParams.get('status');
            if (status) {
                this.filterData.status = status;
            }
        },
        getStatusStyle(status) {
            if (status == 'New') {
                return 'badge badge-primary';
            } else if (status == 'Approved') {
                return 'badge badge-success';
            } else if (status == 'Disapproved') {
                return 'badge badge-danger';
            }
        },
        viewCopyRequest(request) {
            this.document_copy_request = Object.assign({}, request);
            $('#copy-request-view-modal').modal('show');
        },
        updateDocumentCopyRequest() {
            let v = this;
            v.saveDisable = true;
            Swal.fire({
                text: "Are you sure you want to save this document copy request?",
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
                    formData.append('id', v.document_copy_request.id ? v.document_copy_request.id : "");
                    formData.append('status', v.document_copy_request.status ? v.document_copy_request.status : "");
                    formData.append('status_remarks', v.document_copy_request.status_remarks ? v.document_copy_request.status_remarks : "");
                    if (v.document_copy_request.status == 'Approved') {
                        formData.append('expiration_date', v.document_copy_request.expiration_date ? v.document_copy_request.expiration_date : "");
                        formData.append('is_expired', '0');
                    } else {
                        formData.append('expiration_date', "");
                    }
                    axios.post(`/document-copy-requests/update-approval`, formData)
                        .then(response => {
                            if (response.data.status == "success") {
                                Swal.fire('Document copy request has been updated!', '', 'success');
                                v.saveDisable = false;
                                var index = this.items.findIndex(item => item.id == v.document_copy_request.id);
                                this.items.splice(index, 1, response.data.document_copy_request);
                                $('#copy-request-view-modal').modal('hide');
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
    },
}
</script>

<style lang="scss" scoped>

</style>