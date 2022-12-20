<template>
    <div>
        <h4>User Access Request</h4>
        <h6 class="font-weight-light">Fill up form</h6>
        <div class="form-group">
            <label for="name">Name</label>
            <div class="input-group">
                <div class="input-group-prepend bg-transparent">
                    <span class="input-group-text bg-transparent border-right-0">
                        <i class="ti-user text-primary"></i>
                    </span>
                </div>
                <input v-model="access_request.name" type="text" name="name"
                    class="form-control form-control-lg border-left-0" id="name" placeholder="Name">
            </div>
            <span class="text-danger" v-if="errors.name">{{ errors.name[0] }}</span>
        </div>
        <div class="form-group">
            <label for="name">Email</label>
            <div class="input-group">
                <div class="input-group-prepend bg-transparent">
                    <span class="input-group-text bg-transparent border-right-0">
                        <i class="ti-email text-primary"></i>
                    </span>
                </div>
                <input v-model="access_request.email" type="text" name="email"
                    class="form-control form-control-lg border-left-0" id="email" placeholder="Email">
            </div>
            <span class="text-danger" v-if="errors.email">{{ errors.email[0] }}</span>
        </div>
        <div class="form-group">
            <label for="name">Company</label>
            <div class="input-group">
                <div class="input-group-prepend bg-transparent">
                    <span class="input-group-text bg-transparent border-right-0">
                        <i class="ti-settings text-primary"></i>
                    </span>
                </div>
                <select v-model="access_request.company" class="form-control form-control-lg border-left-0"
                    name="company" id="company">
                    <option value="">Choose Company</option>
                    <option v-for="(company, index) in companies" :key="index" :value="company.id">
                        {{ company.company_name }}
                    </option>
                </select>
            </div>
            <span class="text-danger" v-if="errors.company">{{ errors.company[0] }}</span>
        </div>
        <div class="form-group">
            <label for="name">Department</label>
            <div class="input-group">
                <div class="input-group-prepend bg-transparent">
                    <span class="input-group-text bg-transparent border-right-0">
                        <i class="ti-settings text-primary"></i>
                    </span>
                </div>
                <select v-model="access_request.department" class="form-control form-control-lg border-left-0"
                    name="department" id="department">
                    <option value="">Choose Department</option>
                    <option v-for="(department, index) in departments" :key="index" :value="department.id">
                        {{ department.department }}
                    </option>
                </select>
            </div>
            <span class="text-danger" v-if="errors.department">{{ errors.department[0] }}</span>
        </div>
        <div class="form-group">
            <label for="name">Remarks</label>
            <textarea v-model="access_request.remarks" class="form-control" cols="30" rows="5"
                placeholder="Please Indicate (Immediate Superior/Remarks)"></textarea>
        </div>
        <span class="text-danger" v-if="errors.remarks">{{ errors.remarks[0] }}</span>

        <div class="my-3">
            <button class="btn btn-block btn-primary btn-md font-weight-medium auth-form-btn" @click="saveUserAccess"
                :disabled="saveDisable">Save</button>
            <a href='/login' class="btn btn-block btn-danger btn-md font-weight-medium auth-form-btn"
                :disabled="saveDisable">Back to
                Login</a>
        </div>

    </div>
</template>

<script>
import Swal from 'sweetalert2'
export default {
    data() {
        return {
            access_request: {
                name: '',
                email: '',
                company: '',
                department: '',
                remarks: '',
            },
            departments: [],
            companies: [],
            errors: [],
            saveDisable: false,
        }
    },
    created() {
        this.fetchCompanies();
        this.fetchDepartments();
    },
    methods: {
        clearFields() {
            this.access_request.name = '';
            this.access_request.email = '';
            this.access_request.department = '';
            this.access_request.company = '';
            this.access_request.remarks = '';
        },
        saveUserAccess() {
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

                    formData.append('name', v.access_request.name ? v.access_request.name : "");
                    formData.append('email', v.access_request.email ? v.access_request.email : "");
                    formData.append('company', v.access_request.company ? v.access_request.company : "");
                    formData.append('department', v.access_request.department ? v.access_request.department : "");
                    formData.append('remarks', v.access_request.remarks ? v.access_request.remarks : "");

                    axios.post(`/access-requests/store`, formData)
                        .then(response => {
                            if (response.data.status == "success") {
                                Swal.fire('Access request has been saved!', '', 'success');
                                v.saveDisable = false;
                                this.clearFields();
                            } else {
                                Swal.fire('Error: Cannot saved. Please try again.' + response.data.message, '', 'error');
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