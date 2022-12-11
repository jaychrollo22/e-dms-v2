<template>
    <div>
        <div class="content-wrapper">
            <div class="row justify-content-center">
                <div class="col-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Document Requests</h4>
                            <div class="d-flex flex-wrap justify-content-end">
                                <a href="/document-requests/create" class="btn btn-primary">New Request</a>
                            </div>
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
                                                DICR No.
                                            </th>
                                            <th class="pt-1">
                                                Requested Date
                                            </th>
                                            <th class="pt-1">
                                                Requestors Name
                                            </th>
                                            <th class="pt-1">
                                                Department
                                            </th>
                                            <th class="pt-1">
                                                Title
                                            </th>
                                            <th class="pt-1">
                                                Proposed Effective Date
                                            </th>
                                            <th class="pt-1">
                                                Type of Request
                                            </th>
                                            <th class="pt-1">
                                                Action
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(request, index) in items" :key="index">
                                            <td>
                                                {{ request.dicr_number }}
                                            </td>
                                            <td>
                                                {{ request.requested_date }}
                                            </td>
                                            <td>
                                                {{ request.requestor_info.name }}
                                            </td>
                                            <td>
                                                {{ request.department_info.department }}
                                            </td>
                                            <td>
                                                {{ request.title }}
                                            </td>
                                            <td>
                                                {{ request.proposed_effective_date }}
                                            </td>
                                            <td>
                                                <div :class="getTypeStyle(request.type_of_request)">
                                                    {{ request.type_of_request }}
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-inverse-info btn-rounded btn-icon"
                                                    title="View Request" @click="viewRequest(request)">
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
        <div class="modal fade" id="request-view-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true" data-backdrop="static">
            <div class="modal-dialog modal-md modal-document-view" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="col-12 modal-title">View Document Request</h5>
                    </div>
                    <div class="modal-body">
                        <div class="row" v-if="document_request">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Title</label>
                                        <input type="text" class="form-control" name="Title" placeholder="Title"
                                            v-model="document_request.title" :disabled="disableDocumentRequest">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Proposed Effective Date</label>
                                        <input type="date" class="form-control" name="Propose Effective Date"
                                            v-model="document_request.proposed_effective_date"
                                            :disabled="disableDocumentRequest">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Type of Request</label>
                                        <div class="form-group row">
                                            <div class="col-sm-3">
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                        <input v-model="document_request.type_of_request" type="radio"
                                                            class="form-check-input" id="New" value="New"
                                                            :disabled="disableDocumentRequest">
                                                        New
                                                        <i class="input-helper"></i></label>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                        <input v-model="document_request.type_of_request" type="radio"
                                                            class="form-check-input" id="Revision" value="Revision"
                                                            :disabled="disableDocumentRequest">
                                                        Revision
                                                        <i class="input-helper"></i></label>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                        <input v-model="document_request.type_of_request" type="radio"
                                                            class="form-check-input" id="Discontinuance"
                                                            value="Discontinuance" :disabled="disableDocumentRequest">
                                                        Discontinuance
                                                        <i class="input-helper"></i></label>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                        <input v-model="document_request.type_of_request" type="radio"
                                                            class="form-check-input" id="Obsolete" value="Obsolete"
                                                            :disabled="disableDocumentRequest">
                                                        Obsolete
                                                        <i class="input-helper"></i></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Type of Document Information</label>
                                        <div class="form-group row">
                                            <div class="col-sm-3">
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                        <input v-model="document_request.type_of_documented_information"
                                                            type="radio" class="form-check-input" id="Policy"
                                                            value="Policy" :disabled="disableDocumentRequest">
                                                        Policy
                                                        <i class="input-helper"></i></label>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                        <input v-model="document_request.type_of_documented_information"
                                                            type="radio" class="form-check-input" id="Procedure"
                                                            value="Procedure" :disabled="disableDocumentRequest">
                                                        Procedure
                                                        <i class="input-helper"></i></label>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                        <input v-model="document_request.type_of_documented_information"
                                                            type="radio" class="form-check-input" id="Form" value="Form"
                                                            :disabled="disableDocumentRequest">
                                                        Form
                                                        <i class="input-helper"></i></label>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                        <input v-model="document_request.type_of_documented_information"
                                                            type="radio" class="form-check-input" id="Others"
                                                            value="Others" :disabled="disableDocumentRequest">
                                                        Others
                                                        <i class="input-helper"></i></label>
                                                </div>
                                            </div>
                                            <div class="col-sm-12"
                                                v-if="document_request.type_of_documented_information == 'Others'">
                                                <div class="form-group"
                                                    v-if="document_request.type_of_documented_information == 'Others'">
                                                    <input type="text" class="form-control" placeholder="Others Specify"
                                                        v-model="document_request.type_of_documented_information_others"
                                                        :disabled="disableDocumentRequest">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="">Description & Purpose of Documentation Request:</label>
                                        <textarea v-model="document_request.description_purpose_of_documentation"
                                            class="form-control" cols="30" rows="5"
                                            placeholder="Description & Purpose of Documentation Request"
                                            :disabled="disableDocumentRequest"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <iframe id="frame-raw-file"
                                        v-if="validateFileFormat(document_request.attachment_file)"
                                        :src="('storage/document_requests/' + document_request.attachment_file + '#toolbar=0&navpanes=0&scrollbar=0')"
                                        frameborder="1" width="100%" height="700px"></iframe>
                                    <a v-else :href="('storage/document_requests/' + document_request.attachment_file)"
                                        class="btn btn-outline-info btn-icon-text">View Attachment
                                        <i class="ti-download btn-icon-append"></i>
                                    </a>
                                </div>
                            </div>
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
            endpoint: '/document-requests',
            document_requests: [],
            errors: [],
            document_request: '',
            disableDocumentRequest: true,
            document_attachment: '',
        }
    },
    created() {
        this.fetchList();
    },
    methods: {
        getTypeStyle(type) {
            if (type == 'New') {
                return 'badge badge-primary';
            } else if (type == 'Revision') {
                return 'badge badge-success';
            } else if (type == 'Obsolete') {
                return 'badge badge-danger';
            } else if (type == 'Discontinuance') {
                return 'badge badge-warning';
            }
        },
        validateFileFormat(document) {
            if (document.indexOf('.pdf') != -1 || document.indexOf('.png') != -1 || document.indexOf('.jpg') != -1) {
                return true;
            } else {
                return false;
            }
        },
        viewRequest(request) {
            this.document_request = request;
            $('#request-view-modal').modal('show');
        }
    },
}
</script>

<style lang="scss" scoped>

</style>