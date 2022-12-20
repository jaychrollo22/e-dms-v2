<template>
    <div>
        <div class="content-wrapper">
            <div class="row justify-content-center">
                <div class="col-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Documents</h4>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <input type="text" class="form-control w-400px" @keyup="searchKeyUp"
                                            v-model="filterData.search" placeholder="Search Document">
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th class="pt-1">
                                                Control Code
                                            </th>
                                            <th class="pt-1">
                                                Title
                                            </th>
                                            <th class="pt-1">
                                                Company
                                            </th>
                                            <th class="pt-1">
                                                Department
                                            </th>
                                            <th class="pt-1">
                                                Category
                                            </th>
                                            <th class="pt-1">
                                                Effective Date
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(document, index) in items" :key="index">
                                            <td>
                                                {{ document.document_upload_info.control_code }}
                                            </td>
                                            <td>
                                                {{ document.document_upload_info.title }}
                                            </td>
                                            <td>
                                                {{ document.document_upload_info.company_info ? document.document_upload_info.company_info.company_name : "" }}
                                            </td>
                                            <td>
                                                {{ document.document_upload_info.department_info ? document.document_upload_info.department_info.department : "" }}
                                            </td>
                                            <td>
                                                {{ document.document_upload_info.document_category_info ? document.document_upload_info.document_category_info.category_description : "" }}
                                            </td>
                                            <!-- <td>
                                                {{ document.document_category_info ? document.document_category_info.tag_info.description : "" }}
                                            </td> -->
                                            <td>
                                                {{ document.document_upload_info.effective_date }}
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

<script>
import listFormMixins from '../../list-form-mixins.vue';
export default {
    mixins: [listFormMixins],
    data() {
        return {
            endpoint: '/user-document-uploads',
        }
    },
    created() {
        this.fetchList();
    },
    methods: {
        getStatus(status) {
            if (status == 'Pending') {
                return 'badge badge-primary';
            } else if (status == 'Approved') {
                return 'badge badge-success';
            }
            else if (status == 'Disapproved') {
                return 'badge badge-danger';
            }
        },
    },
}
</script>

<style lang="scss" scoped>

</style>