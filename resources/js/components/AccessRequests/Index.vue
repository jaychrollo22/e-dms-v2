<template>
    <div>
        <div class="content-wrapper">
            <div class="row justify-content-center">
                <div class="col-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Access Requests</h4>
                            <div class="d-flex flex-wrap justify-content-end">
                                <a href="/access-requests/create" class="btn btn-primary">New Request</a>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <input type="text" class="form-control w-400px" @keyup="searchKeyUp"
                                            v-model="filterData.search" placeholder="Search Requestor">
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
                                                Email
                                            </th>
                                            <th class="pt-1">
                                                Department
                                            </th>
                                            <th class="pt-1">
                                                Company
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
                                                {{ request.requested_date }}
                                            </td>
                                            <td>
                                                {{ request.name }}
                                            </td>
                                            <td>
                                                {{ request.email }}
                                            </td>
                                            <td>
                                                {{ request.department_info.department }}
                                            </td>
                                            <td>
                                                {{ request.company_info.company_name }}
                                            </td>
                                            <td>
                                                <div :class="getStatusStyle(request.status)">
                                                    {{ request.status }}
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-inverse-info btn-rounded btn-icon"
                                                    title="View Request" @click="viewAccessRequest(request)">
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
        <div class="modal fade" id="access-request-view-modal" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
            <div class="modal-dialog modal-md modal-document-view" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="col-12 modal-title">View Access Request</h5>
                    </div>
                    <div class="modal-body" v-if="access_request">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend bg-transparent">
                                            <span class="input-group-text bg-transparent border-right-0">
                                                <i class="ti-user text-primary"></i>
                                            </span>
                                        </div>
                                        <input v-model="access_request.name" type="text" name="name"
                                            class="form-control form-control-lg border-left-0" id="name"
                                            placeholder="Name" :disabled="viewDisable">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Email</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend bg-transparent">
                                            <span class="input-group-text bg-transparent border-right-0">
                                                <i class="ti-email text-primary"></i>
                                            </span>
                                        </div>
                                        <input v-model="access_request.email" type="text" name="email"
                                            class="form-control form-control-lg border-left-0" id="email"
                                            placeholder="Email" :disabled="viewDisable">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Company</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend bg-transparent">
                                            <span class="input-group-text bg-transparent border-right-0">
                                                <i class="ti-settings text-primary"></i>
                                            </span>
                                        </div>
                                        <select v-model="access_request.company"
                                            class="form-control form-control-lg border-left-0" name="company"
                                            id="company" :disabled="viewDisable">
                                            <option value="">Choose Company</option>
                                            <option v-for="(company, index) in companies" :key="index"
                                                :value="company.id">
                                                {{ company.company_name }}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Department</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend bg-transparent">
                                            <span class="input-group-text bg-transparent border-right-0">
                                                <i class="ti-settings text-primary"></i>
                                            </span>
                                        </div>
                                        <select v-model="access_request.department"
                                            class="form-control form-control-lg border-left-0" name="department"
                                            id="department" :disabled="viewDisable">
                                            <option value="">Choose Department</option>
                                            <option v-for="(department, index) in departments" :key="index"
                                                :value="department.id">
                                                {{ department.department }}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <label for="">Remarks</label>
                                <div class="form-group">
                                    <textarea v-model="access_request.remarks" class="form-control" cols="30" rows="5"
                                        placeholder="Please Indicate (Immediate Superior/Remarks)"
                                        :disabled="viewDisable"></textarea>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-12">
                                <label for="">For Approval Status</label>
                                <div class="form-group row">
                                    <div class="col-sm-3">
                                        <div class="form-check">
                                            <label class="form-check-label">
                                                <input v-model="access_request.status" type="radio"
                                                    class="form-check-input" id="Approved" value="Approved"
                                                    :disabled="saveDisable">
                                                Approve
                                                <i class="input-helper"></i></label>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-check">
                                            <label class="form-check-label">
                                                <input v-model="access_request.status" type="radio"
                                                    class="form-check-input" id="Disapproved" value="Disapproved"
                                                    :disabled="saveDisable">
                                                Disapprove
                                                <i class="input-helper"></i></label>
                                        </div>
                                    </div>
                                </div>
                                <span class="text-danger" v-if="errors.status">{{ errors.status[0] }}</span>
                            </div>
                            <div class="col-md-6" v-if="access_request.status == 'Approved'">
                                <div class="form-group">
                                    <label for="name">Username</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend bg-transparent">
                                            <span class="input-group-text bg-transparent border-right-0">
                                                <i class="ti-email text-primary"></i>
                                            </span>
                                        </div>
                                        <input v-model="access_request.email" type="text" name="email"
                                            class="form-control form-control-lg border-left-0" id="email"
                                            placeholder="Email" :disabled="saveDisable">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6" v-if="access_request.status == 'Approved'">
                                <div class="form-group">

                                    <label for="name">Password</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend bg-transparent">
                                            <span class="input-group-text bg-transparent border-right-0">
                                                <i class="ti-key text-primary"></i>
                                            </span>
                                        </div>
                                        <input v-model="access_request.initial_password" type="password"
                                            name="initial_password" class="form-control form-control-lg border-left-0"
                                            id="initial_password" placeholder="Initial Password"
                                            :disabled="saveDisable">
                                    </div>
                                    <span class="text-default" v-if="access_request.initial_password">Password :
                                        {{ access_request.initial_password }}</span>
                                    <span class="text-danger"
                                        v-if="errors.initial_password">{{ errors.initial_password[0] }}</span>
                                </div>
                            </div>
                            <div class="col-md-6" v-if="access_request.status == 'Approved'">
                                <div class="form-group">
                                    <label for="">Immediate Head</label>
                                    <multiselect v-model="access_request.immediate_head" :options="immediate_heads"
                                        placeholder="Select Immediate Head" label="name" track-by="id"
                                        :disabled="saveDisable"></multiselect>
                                    <span class="text-danger"
                                        v-if="errors.immediate_head">{{ errors.immediate_head[0] }}</span>
                                </div>
                            </div>
                            <div class="col-md-6" v-if="access_request.status == 'Approved'">
                                <div class="form-group">
                                    <label for="">Role</label>
                                    <multiselect v-model="access_request.roles" :options="roles" :multiple="true"
                                        :close-on-select="true" :clear-on-select="true" :preselect-first="true"
                                        placeholder="Select Role" :preserve-search="true" label="name" track-by="id"
                                        :disabled="saveDisable">
                                    </multiselect>
                                    <span class="text-danger" v-if="errors.roles">{{ errors.roles[0] }}</span>
                                </div>
                            </div>
                            <div class="col-md-12"
                                v-if="access_request.status == 'Approved' || access_request.status == 'Disapproved'">
                                <label for="">Status Remarks</label>
                                <div class="form-group">
                                    <textarea v-model="access_request.status_remarks" cols="30" rows="5"
                                        class="form-control" placeholder="Approval Remarks"
                                        :disabled="saveDisable"></textarea>
                                    <span class="text-danger"
                                        v-if="errors.status_remarks">{{ errors.status_remarks[0] }}</span>
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-sm btn-primary" @click="updateAccessRequest"
                            :disabled="saveDisable">{{ 'Save Changes' }}</button>
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
            endpoint: '/access-requests',
            access_request: "",
            disableFields: true,
            errors: [],
            viewDisable: true,
            saveDisable: false,
            roles: [],
            immediate_heads: [],
        }
    },
    created() {
        this.fetchList();
        this.fetchCompanies();
        this.fetchDepartments();
        this.fetchRoles();
        this.fetchImmediateHeads();
    },
    methods: {
        updateAccessRequest() {
            let v = this;
            v.saveDisable = true;
            Swal.fire({
                text: "Are you sure you want to save this access request?",
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
                    formData.append('id', v.access_request.id ? v.access_request.id : "");
                    formData.append('status', v.access_request.status ? v.access_request.status : "");
                    formData.append('status_remarks', v.access_request.status_remarks ? v.access_request.status_remarks : "");
                    if (v.access_request.status == 'Approved') {
                        formData.append('initial_password', v.access_request.initial_password ? v.access_request.initial_password : "");

                        formData.append('immediate_head', v.access_request.immediate_head ? v.access_request.immediate_head.id : "");
                        formData.append('roles', v.access_request.roles.length > 0 ? JSON.stringify(v.access_request.roles) : "");
                    } else {
                        formData.append('initial_password', "");
                    }
                    axios.post(`/access-requests/update`, formData)
                        .then(response => {
                            if (response.data.status == "success") {
                                Swal.fire('Access request has been updated!', '', 'success');
                                v.saveDisable = false;
                                var index = this.items.findIndex(item => item.id == v.access_request.id);
                                this.items.splice(index, 1, response.data.access_request);
                                $('#access-request-view-modal').modal('hide');
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
        viewAccessRequest(request) {
            this.access_request = Object.assign({}, request);
            this.access_request.roles = request.roles ? JSON.parse(request.roles) : "";
            this.access_request.immediate_head = request.immediate_head_info ? request.immediate_head_info : "";
            if (this.access_request.status == 'Approved') {
                this.saveDisable = true;
            } else {
                this.saveDisable = false;
            }
            $('#access-request-view-modal').modal('show');
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
        fetchRoles() {
            let v = this;
            v.roles = [];
            axios.get('/roles-options')
                .then(response => {
                    v.roles = response.data;
                })
                .catch(error => {
                    v.errors = error.response.data.error;
                })
        },
        fetchImmediateHeads() {
            let v = this;
            v.immediate_heads = [];
            axios.get('/immediate-heads')
                .then(response => {
                    v.immediate_heads = response.data;
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