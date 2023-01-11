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
                                        <div class="input-group">
                                            <div class="input-group-prepend bg-transparent">
                                                <span class="input-group-text bg-transparent border-right-0">
                                                    <i class="ti-search text-primary"></i>
                                                </span>
                                            </div>
                                            <input v-model="filterData.search" @keyup="searchKeyUp" type="text"
                                                name="search" class="form-control form-control-lg border-left-0"
                                                id="search" placeholder="Search Document">
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
                                            <select v-model="filterData.document_category" @change="searchKeyUp"
                                                class="form-control form-control-lg border-left-0"
                                                name="document_category" id="document_category">
                                                <option value="">Choose Document Category</option>
                                                <option v-for="(document_category, index) in document_categories"
                                                    :key="index" :value="document_category.id">
                                                    {{ document_category.category_description }}
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
                                            <th class="pt-1 text-center">
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
                                        <tr v-else v-for="(document, index) in items" :key="index">
                                            <td>
                                                {{ document.document_upload_info.control_code }}
                                            </td>
                                            <td>
                                                {{ document.document_upload_info.title }}
                                            </td>
                                            <td>
                                                {{ document.document_upload_info.company_info ? document.document_upload_info.company_info.company_code : "" }}
                                            </td>
                                            <td>
                                                {{ document.document_upload_info.department_info ? document.document_upload_info.department_info.department : "" }}
                                            </td>
                                            <td>
                                                {{ document.document_upload_info.document_category_info ? document.document_upload_info.document_category_info.category_description : "" }}
                                            </td>
                                            <td>
                                                {{ document.document_upload_info.effective_date }}
                                            </td>
                                            <td class="text-center">
                                                <div
                                                    v-if="isValidToAcknowledge(document.document_upload_info.document_category)">
                                                    <div
                                                        v-if="(document.is_acknowledge == null || document.is_acknowledge == '')">
                                                        <button @click="acknowledgeDocument(document)" type="button"
                                                            class="btn btn-inverse-success btn-rounded btn-icon"
                                                            title="Acknowledge">
                                                            <i class="ti-thumb-up"></i>
                                                        </button>
                                                    </div>
                                                    <div v-else>
                                                        <button @click="viewDocument(document)" type="button"
                                                            class="btn btn-inverse-primary btn-rounded btn-icon"
                                                            title="Yes">
                                                            <i class="ti-eye"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                <div v-else>
                                                    <button @click="viewDocument(document)" type="button"
                                                        class="btn btn-inverse-primary btn-rounded btn-icon"
                                                        title="Yes">
                                                        <i class="ti-eye"></i>
                                                    </button>
                                                </div>
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

        <div class="modal fade" id="document-view-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true" data-backdrop="static">
            <div class="modal-dialog modal-md modal-document-view" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="col-12 modal-title">
                            {{ view_document? view_document.document_upload_info.title : "" }}
                        </h5>
                    </div>
                    <div class="modal-body" v-if="view_document">
                        <button v-if="view_document.can_print == '1'" type="button"
                            class="btn btn-inverse-success btn-rounded btn-icon" title="Print"
                            @click="printDocument('document-uploads-view-signed-copy?id=' + view_document.document_upload_info.id)">
                            <i class="ti-printer"></i>
                        </button>
                        <button v-if="view_document.can_download == '1'" type="button"
                            class="btn btn-inverse-primary btn-rounded btn-icon" title="Download"
                            @click="redirectDocument('document-uploads-download-signed-copy?id=' + view_document.document_upload_info.id)">
                            <i class="ti-cloud-down"></i>
                        </button>
                        <button
                            v-if="view_document.can_fill == '1' && view_document.document_upload_info.attachment_fillable_copy"
                            type="button" class="btn btn-inverse-info btn-rounded btn-icon" title="Fill"
                            @click="redirectDocument('storage/document_uploads/' + view_document.document_upload_info.attachment_fillable_copy)">
                            <i class="ti-pencil-alt"></i>
                        </button>
                        <vue-pdf-embed v-if="view_document.document_upload_info.attachment_signed_copy"
                            :source="'document-uploads-view-signed-copy?id=' + view_document.document_upload_info.id"
                            class="src-pdf mt-2" />
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
import print from 'print-js'
import listFormMixins from '../../list-form-mixins.vue';
import VuePdfEmbed from 'vue-pdf-embed/dist/vue2-pdf-embed'
export default {
    components: {
        VuePdfEmbed,
    },
    mixins: [listFormMixins],
    data() {
        return {
            endpoint: '/user-document-uploads',
            view_document: '',
            document_categories: [],

            filterData: {
                document_category: '',
            },
        }
    },
    created() {
        this.fetchList();
        this.fetchDocumentCategories();
    },
    methods: {
        isValidToAcknowledge(category) {
            if (category == '12') {
                return false;
            }
            else if (category == '11') {
                return false;
            }
            else if (category == '6') {
                return false;
            } else {
                return true;
            }
        },
        printDocument(src) {
            printJS({ printable: src, type: 'pdf', showModal: true });
        },
        redirectDocument(src) {
            if (src) {
                window.location = src;
            } else {
                alert('Warning: Link is not exist. Please contact Administrator. Thank you.');
            }

        },
        viewDocument(document) {
            this.view_document = document;
            $('#document-view-modal').modal('show');
        },
        acknowledgeDocument(document) {
            let v = this;
            if (document) {
                let formData = new FormData();
                formData.append('id', document.id ? document.id : "");
                formData.append('is_acknowledge', "1");
                axios.post(`/user-document-uploads/acknowledge-document`, formData)
                    .then(response => {
                        if (response.data.status == "success") {
                            var index = v.items.findIndex(item => item.id == document.id);
                            v.items.splice(index, 1, response.data.document_upload_user);
                        }
                    })
                    .catch(error => {
                        v.errors = error.response.data.errors;
                    })
            }
        },
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
        fetchDocumentCategories() {
            let v = this;
            v.document_categories = [];
            axios.get('/document-category-options')
                .then(response => {
                    v.document_categories = response.data;
                })
                .catch(error => {
                    v.errors = error.response.data.error;
                })
        },
    },
}
</script>

<style>
.src-pdf {
    border: 1px solid;
}

@media only screen and (max-width: 400px) {
    .src-pdf {
        width: 400px !important;
    }
}

@media only screen and (max-width: 600px) {
    .src-pdf {
        width: 100% !important;
    }
}
</style>