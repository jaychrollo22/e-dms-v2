<template>
    <div>
        <div class="content-wrapper">
            <div class="row justify-content-center">
                <div class="col-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Departments</h4>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <input type="text" class="form-control w-400px" @keyup="searchKeyUp"
                                            v-model="filterData.search" placeholder="Search Department">
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th class="pt-1">
                                                Department
                                            </th>
                                            <th class="pt-1">
                                                Action
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(department, index) in items" :key="index">
                                            <td>
                                                {{ department.department }}
                                            </td>
                                            <td>
                                                <button type="button"
                                                    class="btn btn-inverse-primary btn-rounded btn-icon"
                                                    title="Edit Department" @click="editDepartment(department)">
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

        <div class="modal fade" id="department-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true" data-backdrop="static">
            <div class="modal-dialog modal-dialog-centered modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="col-12 modal-title text-center">{{ action }} Department</h5>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">Department</label>
                                    <input type="text" class="form-control" placeholder="Department"
                                        v-model="department.department">
                                    <span class="text-danger" v-if="errors.department">{{ errors.department[0] }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary btn-md" @click="saveDepartment">Save</button>
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
            endpoint: '/departments',
            department: {
                id: '',
                department: ''
            },
            action: '',
            errors: [],
        }
    },
    created() {
        this.fetchList();
    },
    methods: {
        editDepartment(department) {
            let v = this;
            v.errors = [];
            v.department.id = department.id;
            v.department.department = department.department;
            v.action = 'Update';
            $('#department-modal').modal('show');
        },
        saveDepartment() {
            let v = this;
            Swal.fire({
                title: 'Are you sure you want to save this department?',
                icon: 'question',
                showDenyButton: true,
                confirmButtonText: `Yes`,
                denyButtonText: `No`,
            }).then((result) => {
                if (result.isConfirmed) {
                    let formData = new FormData();
                    var postURL = `/departments/store`;
                    if (v.action == "Update") {
                        formData.append('id', v.department.id ? v.department.id : "");
                        postURL = `/departments/update`;
                    }
                    formData.append('department', v.department.department ? v.department.department : "");

                    axios.post(postURL, formData)
                        .then(response => {
                            if (response.data.status == "success") {
                                if (v.action == 'Update') {
                                    var index = this.items.findIndex(item => item.id == v.department.id);
                                    this.items.splice(index, 1, response.data.department);
                                } else {
                                    this.fetchList();
                                }

                                Swal.fire('Deparment has been saved!', '', 'success');

                                $('#department-modal').modal('hide');

                                v.department.id = '';
                                v.department.department = '';

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