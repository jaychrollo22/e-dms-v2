<template>
    <div>
        <div class="content-wrapper">
            <div class="row justify-content-center">
                <div class="col-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Companies</h4>
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
                                                Code
                                            </th>
                                            <th class="pt-1">
                                                Company
                                            </th>
                                            <th class="pt-1">
                                                Action
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(company, index) in items" :key="index">
                                            <td>
                                                {{ company.company_code }}
                                            </td>
                                            <td>
                                                {{ company.company_name }}
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
            <div class="modal-dialog modal-dialog-centered modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="col-12 modal-title text-center">{{ action }} Company</h5>
                    </div>
                    <div class="modal-body">
                        <div class="row">
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
            },
            action: '',
            errors: [],
        }
    },
    created() {
        this.fetchList();
    },
    methods: {
        editCompany(company) {
            let v = this;
            v.errors = [];
            v.company.id = company.id;
            v.company.company_code = company.company_code;
            v.company.company_name = company.company_name;
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
    },
}
</script>

<style lang="scss" scoped>

</style>