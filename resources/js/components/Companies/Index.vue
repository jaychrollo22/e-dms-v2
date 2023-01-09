<template>
    <div>
        <div class="content-wrapper">
            <div class="row justify-content-center">
                <div class="col-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Companies</h4>

                            <div class="d-flex flex-wrap justify-content-end">
                                <button class="btn btn-primary" @click="addCompany">New</button>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <input type="text" class="form-control w-400px" @keyup="searchKeyUp"
                                            v-model="filterData.search" placeholder="Search Company">
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th class="pt-1">
                                                Company Logo
                                            </th>
                                            <th class="pt-1">
                                                Company Code
                                            </th>
                                            <th class="pt-1">
                                                Company
                                            </th>
                                            <th class="pt-1">
                                                Control Code Series No.
                                            </th>
                                            <th class="pt-1">
                                                Stamp
                                            </th>

                                            <th class="pt-1">
                                                Action
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(company, index) in items" :key="index">
                                            <td>
                                                <img v-if="company.logo" :src="'storage/company_logos/' + company.logo"
                                                    width="100px">
                                            </td>
                                            <td>
                                                {{ company.company_code }}
                                            </td>
                                            <td>
                                                {{ company.company_name }}
                                            </td>
                                            <td>
                                                {{ company.control_code_series_number }}
                                            </td>
                                            <td>
                                                <a v-if="company.stamp" :href="'storage/stamps/' + company.stamp"
                                                    target="_blank"><i class="ti-settings"></i></a>
                                            </td>
                                            <td>
                                                <button type="button"
                                                    class="btn btn-inverse-primary btn-rounded btn-icon"
                                                    title="Edit Company" @click="editCompany(company)">
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

        <div class="modal fade" id="company-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true" data-backdrop="static">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="col-12 modal-title text-center">{{ action }} Company</h5>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Logo</label>
                                    <input v-on:change="logoHandleFileUpload()" type="file" accept="image/*" id="logo"
                                        ref="file" class="form-control">
                                    <span class="text-danger" v-if="errors.logo">{{ errors.logo[0] }}</span>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">Company Code</label>
                                    <input type="text" class="form-control" placeholder="Company Code"
                                        v-model="company.company_code">
                                    <span class="text-danger"
                                        v-if="errors.company_code">{{ errors.company_code[0] }}</span>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">Company Name</label>
                                    <input type="text" class="form-control" placeholder="Company Name"
                                        v-model="company.company_name">
                                    <span class="text-danger"
                                        v-if="errors.company_name">{{ errors.company_name[0] }}</span>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">Control Code Series No.</label>
                                    <input type="number" class="form-control" placeholder="Control Code Series No."
                                        v-model="company.control_code_series_number">
                                    <span class="text-danger"
                                        v-if="errors.control_code_series_number">{{ errors.control_code_series_number[0] }}</span>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Stamp</label>
                                    <input v-on:change="stampHandleFileUpload()" type="file" accept="image/*" id="stamp"
                                        ref="file" class="form-control">
                                    <span class="text-danger" v-if="errors.stamp">{{ errors.stamp[0] }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary btn-md" @click="saveCompany">Save</button>
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
            endpoint: '/companies',

            company: {
                id: '',
                company_code: '',
                company_name: '',
                control_code_series_number: '',
            },
            action: '',
            errors: [],

            stamp: '',
            stamp_src: '',
            logo: '',
            logo_src: '',
        }
    },
    created() {
        this.fetchList();
    },
    methods: {
        addCompany() {
            this.clearFields();
            this.action = 'Add';
            $('#company-modal').modal('show');
        },
        clearFields() {
            let v = this;
            v.errors = [];
            v.company.id = '';
            v.company.company_code = '';
            v.company.company_name = '';
            v.company.control_code_series_number = '';

            v.company.stamp = '';
            v.company.stamp_src = '';
            v.company.logo = '';
            v.company.logo_src = '';

            document.getElementById("stamp").value = '';
            document.getElementById("logo").value = '';
        },
        editCompany(company) {
            let v = this;
            v.clearFields();
            v.errors = [];
            v.company.id = company.id;
            v.company.company_code = company.company_code;
            v.company.company_name = company.company_name;
            v.company.control_code_series_number = company.control_code_series_number;
            v.action = 'Update';
            $('#company-modal').modal('show');
        },
        saveCompany() {
            let v = this;
            Swal.fire({
                title: 'Are you sure you want to save this company?',
                icon: 'question',
                showDenyButton: true,
                confirmButtonText: `Yes`,
                denyButtonText: `No`,
            }).then((result) => {
                if (result.isConfirmed) {
                    let formData = new FormData();
                    var postURL = `/companies/store`;
                    if (v.action == "Update") {
                        formData.append('id', v.company.id ? v.company.id : "");
                        postURL = `/companies/update`;
                    }
                    formData.append('company_code', v.company.company_code ? v.company.company_code : "");
                    formData.append('company_name', v.company.company_name ? v.company.company_name : "");
                    formData.append('control_code_series_number', v.company.control_code_series_number ? v.company.control_code_series_number : "");


                    formData.append('stamp', v.stamp ? v.stamp : "");
                    formData.append('logo', v.logo ? v.logo : "");


                    axios.post(postURL, formData)
                        .then(response => {
                            if (response.data.status == "success") {
                                if (v.action == 'Update') {
                                    var index = this.items.findIndex(item => item.id == v.company.id);
                                    this.items.splice(index, 1, response.data.company);
                                } else {
                                    this.fetchList();
                                }

                                Swal.fire('company has been saved!', '', 'success');

                                $('#company-modal').modal('hide');

                                v.company.id = '';
                                v.company.company_code = '';
                                v.company.company_name = '';
                                v.stamp = '';
                                v.logo = '';

                            } else {
                                Swal.fire('Error: Cannot changed. Please try again.', '', 'error');
                            }
                        })
                        .catch(error => {
                            this.errors = error.response.data.errors;
                        })
                }
            })
        },
        stampHandleFileUpload() {
            var photo = document.getElementById("stamp");
            this.stamp_src = window.URL.createObjectURL(photo.files[0]);
            this.stamp = photo.files[0];
        },
        logoHandleFileUpload() {
            var photo = document.getElementById("logo");
            this.logo_src = window.URL.createObjectURL(photo.files[0]);
            this.logo = photo.files[0];
        },
    },
}
</script>

<style lang="scss" scoped>

</style>