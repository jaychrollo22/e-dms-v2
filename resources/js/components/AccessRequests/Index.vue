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
            errors: []
        }
    },
    created() {
        this.fetchList();
    },
    methods: {
        getStatusStyle(status) {
            if (status == 'New') {
                return 'badge badge-primary';
            } else if (status == 'Approved') {
                return 'badge badge-success';
            } else if (status == 'Disapproved') {
                return 'badge badge-danger';
            }
        },
    },
}
</script>

<style lang="scss" scoped>

</style>