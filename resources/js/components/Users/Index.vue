<template>
    <div>
        <div class="content-wrapper">
            <div class="row justify-content-center">
                <div class="col-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Users</h4>

                            <div class="d-flex flex-wrap justify-content-end">
                                <button class="btn btn-primary" @click="addUser">New</button>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <input type="text" class="form-control w-400px" @keyup="searchKeyUp"
                                            v-model="filterData.search" placeholder="Search User">
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th class="pt-1">
                                                Name
                                            </th>
                                            <th class="pt-1">
                                                Email
                                            </th>
                                            <th class="pt-1">
                                                Immediate Head
                                            </th>
                                            <th class="pt-1">
                                                Department
                                            </th>
                                            <th class="pt-1">
                                                Company
                                            </th>
                                            <th class="pt-1">
                                                Role
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
                                            <td colspan="7">
                                                <div class="dot-opacity-loader">
                                                    <span></span>
                                                    <span></span>
                                                    <span></span>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr v-else v-for="(user, index) in items" :key="index">
                                            <td>
                                                {{ user.name }}
                                            </td>
                                            <td>{{ user.email }}</td>
                                            <td>{{ user.immediate_head ? user.immediate_head.user_info.name : "" }}</td>
                                            <td>{{ user.department ? user.department.department_info.department : "" }}
                                            </td>
                                            <td>
                                                <span
                                                    v-if="user.company">{{ user.company.company_info ? user.company.company_info.company_name : "" }}</span>

                                            </td>
                                            <td>
                                                {{ user.roles ? displayRoles(user.roles) : "" }}
                                            </td>
                                            <td>
                                                {{ user.status }}
                                            </td>
                                            <td>
                                                <button type="button"
                                                    class="btn btn-inverse-primary btn-rounded btn-icon"
                                                    title="Edit User" @click="editUser(user)">
                                                    <i class="ti-pencil"></i>
                                                </button>
                                                <button type="button"
                                                    class="btn btn-inverse-success btn-rounded btn-icon"
                                                    title="Change Password" @click="changePassword(user)">
                                                    <i class="ti-key"></i>
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

        <div class="modal fade" id="user-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true" data-backdrop="static">
            <div class="modal-dialog modal-dialog-centered modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="col-12 modal-title text-center">{{ action }} User</h5>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">User</label>
                                    <input type="text" class="form-control" placeholder="User" v-model="user.name">
                                    <span class="text-danger" v-if="errors.name">{{ errors.name[0] }}</span>
                                </div>
                                <div class="form-group">
                                    <label for="">Email</label>
                                    <input type="text" class="form-control" placeholder="Email" v-model="user.email">
                                    <span class="text-danger" v-if="errors.email">{{ errors.email[0] }}</span>
                                </div>
                                <div class="form-group" v-if="action == 'Add'">
                                    <label for="">Password</label>
                                    <input type="password" class="form-control" placeholder="Password"
                                        v-model="user.password">
                                    <span class="text-danger" v-if="errors.password">{{ errors.password[0] }}</span>
                                </div>
                                <div class="form-group" v-if="action == 'Add'">
                                    <label for="">Password Confirmation</label>
                                    <input type="password" class="form-control" placeholder="Password Confirmation"
                                        v-model="user.password_confirmation">
                                    <span class="text-danger"
                                        v-if="errors.password_confirmation">{{ errors.password_confirmation[0] }}</span>
                                </div>
                                <div class="form-group">
                                    <label for="">Immediate Head</label>
                                    <multiselect v-model="user.immediate_head" :options="immediate_heads"
                                        placeholder="Select Immediate Head" label="name" track-by="id"></multiselect>
                                    <span class="text-danger"
                                        v-if="errors.immediate_head">{{ errors.immediate_head[0] }}</span>
                                </div>
                                <div class="form-group">
                                    <label for="">Department</label>
                                    <multiselect v-model="user.department" :options="departments"
                                        placeholder="Select Department" label="department" track-by="id"></multiselect>
                                    <span class="text-danger" v-if="errors.department">{{ errors.department[0] }}</span>
                                </div>
                                <div class="form-group">
                                    <label for="">Company</label>
                                    <multiselect v-model="user.company" :options="companies"
                                        placeholder="Select Company" label="company_name" track-by="id"></multiselect>
                                    <span class="text-danger" v-if="errors.company">{{ errors.company[0] }}</span>
                                </div>
                                <div class="form-group">
                                    <label for="">Role</label>
                                    <multiselect v-model="user.roles" :options="roles" :multiple="true"
                                        :close-on-select="true" :clear-on-select="true" :preselect-first="true"
                                        placeholder="Select Role" :preserve-search="true" label="name" track-by="id">
                                    </multiselect>
                                    <span class="text-danger" v-if="errors.roles">{{ errors.roles[0] }}</span>
                                </div>
                                <div class="form-group">
                                    <label for="">Status</label>
                                    <select class="form-control" v-model="user.status">
                                        <option value="">Choose Status</option>
                                        <option value="Active">Active</option>
                                        <option value="Inactive">Inactive</option>
                                    </select>
                                    <span class="text-danger" v-if="errors.status">{{ errors.status[0] }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary btn-md" @click="saveUser">Save</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="change-password-modal" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="col-12 modal-title text-center">{{ action }} Password</h5>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">Password</label>
                                    <input type="password" class="form-control" placeholder="New Password"
                                        v-model="change_password.new_password">
                                    <span class="text-danger"
                                        v-if="errors.new_password">{{ errors.new_password[0] }}</span>
                                </div>
                                <div class="form-group">
                                    <label for="">Confirm Password</label>
                                    <input type="password" class="form-control" placeholder="Confirm Password"
                                        v-model="change_password.confirm_password">
                                    <span class="text-danger"
                                        v-if="errors.confirm_password">{{ errors.confirm_password[0] }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary btn-md" @click="saveChangePassword">Save</button>
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
    mixins: [listFormMixins],
    components: {
        Multiselect
    },
    data() {
        return {
            endpoint: '/users',
            change_password: {
                id: '',
                new_password: '',
                confirm_password: '',
            },
            user: {
                id: '',
                name: '',
                email: '',
                immediate_head: '',
                department: '',
                company: '',
                roles: [],
                status: ''
            },
            action: '',
            errors: [],

            //Options
            companies: [],
            departments: [],
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
        addUser() {
            this.clearFields();
            this.action = 'Add';
            $('#user-modal').modal('show');
        },
        saveChangePassword() {
            let v = this;
            Swal.fire({
                title: 'Are you sure you want to change this password?',
                icon: 'question',
                showDenyButton: true,
                confirmButtonText: `Yes`,
                denyButtonText: `No`,
            }).then((result) => {
                if (result.isConfirmed) {
                    let formData = new FormData();
                    var postURL = `/users/change-password`;
                    formData.append('id', v.change_password.id ? v.change_password.id : "");
                    formData.append('new_password', v.change_password.new_password ? v.change_password.new_password : "");
                    formData.append('new_password_confirmation', v.change_password.confirm_password ? v.change_password.confirm_password : "");
                    axios.post(postURL, formData)
                        .then(response => {
                            if (response.data.status == "success") {
                                if (v.action == 'Update') {
                                    var index = this.items.findIndex(item => item.id == v.user.id);
                                    this.items.splice(index, 1, response.data.user);
                                } else {
                                    this.fetchList();
                                }

                                Swal.fire('Password has been changed!', '', 'success');

                                $('#change-password-modal').modal('hide');

                                this.clearFields();
                            } else {
                                Swal.fire('Error: Cannot changed. Please try again.', '', 'error');
                            }
                        })
                        .catch(error => {
                            console.log(error);
                            // this.errors = error.response.data;
                        })
                }
            })
        },
        changePassword(user) {
            this.change_password.id = user.id;
            this.change_password.new_password = user.new_password;
            this.change_password.confirm_password = user.confirm_password;
            this.action = 'Update';
            $('#change-password-modal').modal('show');
        },
        displayRoles(roles) {
            var roles_arr = [];
            if (roles.length > 0) {
                roles.forEach(e => {
                    var role = JSON.parse(e.roles);
                    roles_arr.push(role.name);
                });
                return roles_arr.join(', ');
            }
        },
        clearFields() {
            let v = this;
            v.user.id = '';
            v.user.name = '';
            v.user.email = '';
            v.user.password = '';
            v.user.password_confirmation = '';
            v.user.immediate_head = '';
            v.user.department = '';
            v.user.company = '';
            v.user.roles = [];
            v.user.status = '';

            v.change_password.id = '';
            v.change_password.new_password = '';
            v.change_password.confirm_password = '';
        },
        editUser(user) {
            let v = this;
            v.errors = [];
            v.user.id = user.id;
            v.user.name = user.name;
            v.user.email = user.email;
            v.user.immediate_head = user.immediate_head ? user.immediate_head.user_info : "";
            v.user.department = user.department ? user.department.department_info : "";
            v.user.company = user.company ? user.company.company_info : "";

            if (user.roles.length > 0) {
                var roles = [];
                user.roles.forEach((item) => {
                    var item_roles = JSON.parse(item.roles);
                    roles.push(item_roles);
                })
                v.user.roles = roles;
            }

            v.user.status = user.status;
            v.action = 'Update';
            $('#user-modal').modal('show');
        },
        saveUser() {
            let v = this;
            Swal.fire({
                title: 'Are you sure you want to save this user?',
                icon: 'question',
                showDenyButton: true,
                confirmButtonText: `Yes`,
                denyButtonText: `No`,
            }).then((result) => {
                if (result.isConfirmed) {
                    let formData = new FormData();
                    var postURL = `/users/store`;
                    if (v.action == "Update") {
                        formData.append('id', v.user.id ? v.user.id : "");
                        postURL = `/users/update`;
                    }
                    if (v.action == "Add") {
                        formData.append('password', v.user.password ? v.user.password : "");
                        formData.append('password_confirmation', v.user.password_confirmation ? v.user.password_confirmation : "");
                    }
                    formData.append('name', v.user.name ? v.user.name : "");
                    formData.append('email', v.user.email ? v.user.email : "");
                    formData.append('immediate_head', v.user.immediate_head ? v.user.immediate_head.id : "");
                    formData.append('department', v.user.department ? v.user.department.id : "");
                    formData.append('company', v.user.company ? v.user.company.id : "");
                    formData.append('roles', v.user.roles.length > 0 ? JSON.stringify(v.user.roles) : "");
                    formData.append('status', v.user.status ? v.user.status : "");
                    axios.post(postURL, formData)
                        .then(response => {
                            if (response.data.status == "success") {
                                if (v.action == 'Update') {
                                    var index = this.items.findIndex(item => item.id == v.user.id);
                                    this.items.splice(index, 1, response.data.user);
                                } else {
                                    this.fetchList();
                                }

                                Swal.fire('User has been saved!', '', 'success');

                                $('#user-modal').modal('hide');

                                this.clearFields();
                            } else {
                                Swal.fire('Error: Cannot changed. Please try again.', '', 'error');
                            }
                        })
                        .catch(error => {
                            // console.log(error);
                            this.errors = error.response.data.errors;
                        })
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