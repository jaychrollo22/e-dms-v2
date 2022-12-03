<template>
    <div>
        <div class="content-wrapper">
            <div class="row justify-content-center">
                <div class="col-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Document Uploads</h4>

                            <div class="d-flex flex-wrap justify-content-end">
                                <button class="btn btn-primary" @click="addDocumentUpload">Add</button>
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
                                                Tag
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
                                        <tr v-for="(document, index) in items" :key="index">
                                            <td>
                                                {{ document.control_code }}
                                            </td>
                                            <td>
                                                {{ document.title }}
                                            </td>
                                            <td>
                                                {{ document.company_info ? document.company_info.company_name : "" }}
                                            </td>
                                            <td>
                                                {{ document.department_info ? document.department_info.department : "" }}
                                            </td>
                                            <td>
                                                {{ document.document_category_info ? document.document_category_info.category_description : "" }}
                                            </td>
                                            <td>
                                                {{ document.document_category_info ? document.document_category_info.tag_info.description : "" }}
                                            </td>
                                            <td>
                                                {{ document.effective_date }}
                                            </td>
                                            <td class="text-center">
                                                <button type="button"
                                                    class="btn btn-inverse-primary btn-rounded btn-icon"
                                                    title="Edit Document" @click="editDocumentUpload(document)">
                                                    <i class="ti-pencil"></i>
                                                </button>
                                                <button type="button" class="btn btn-inverse-info btn-rounded btn-icon"
                                                    title="View Document" @click="viewDocumentUpload(document)">
                                                    <i class="ti-eye"></i>
                                                </button>
                                                <button type="button"
                                                    class="btn btn-inverse-success btn-rounded btn-icon"
                                                    title="Assign Users" @click="usersDocumentUpload(document)">
                                                    <i class="ti-user"></i>
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


        <div class="modal fade" id="document-upload-modal" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
            <div class="modal-dialog modal-md modal-document-upload" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="col-12 modal-title text-center">{{ action }} Document Upload</h5>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">Title</label>
                                    <input type="text" class="form-control" placeholder="Title"
                                        v-model="document_upload.title">
                                    <span class="text-danger" v-if="errors.title">{{ errors.title[0] }}</span>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">Effective Date</label>
                                    <input type="date" class="form-control" placeholder="Effective Date"
                                        v-model="document_upload.effective_date">
                                    <span class="text-danger"
                                        v-if="errors.effective_date">{{ errors.effective_date[0] }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">Document Category</label>
                                    <multiselect v-model="document_upload.document_category"
                                        :options="document_categories" placeholder="Select Document Category"
                                        label="category_description" track-by="id"></multiselect>
                                    <span class="text-danger"
                                        v-if="errors.document_category">{{ errors.document_category[0] }}</span>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">Company</label>
                                    <multiselect v-model="document_upload.company" :options="companies"
                                        placeholder="Select Company" label="company_name" track-by="id"></multiselect>
                                    <span class="text-danger" v-if="errors.company">{{ errors.company[0] }}</span>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">Department</label>
                                    <multiselect v-model="document_upload.department" :options="departments"
                                        placeholder="Select Department" label="department" track-by="id"></multiselect>
                                    <span class="text-danger" v-if="errors.department">{{ errors.department[0] }}</span>
                                </div>
                            </div>

                            <div class="col-md-12" v-if="(document_upload.document_category && action == 'Add')">
                                <div class="form-group" v-if="document_upload.document_category.id != '6'">
                                    <label>Document Attachment</label>
                                    <input @change="uploadRawFile" name="attachment_raw_file" type="file"
                                        accept="application/*" id="attachment_raw_file" class="form-control">
                                    <span class="text-danger"
                                        v-if="errors.attachment_raw_file">{{ errors.attachment_raw_file[0] }}</span>
                                </div>
                                <div class="form-group" v-if="document_upload.document_category.id == '6'">
                                    <label>Document Attachment (Fillable Copy)</label>
                                    <input @change="uploadFillableCopy" name="attachment_fillable_copy" type="file"
                                        accept="application/*" id="attachment_fillable_copy" class="form-control">
                                    <span class="text-danger"
                                        v-if="errors.attachment_fillable_copy">{{ errors.attachment_fillable_copy[0] }}</span>
                                </div>
                                <div class="form-group" v-if="document_upload.document_category.id == '6'">
                                    <label>Document Attachment (Signed Copy)</label>
                                    <input @change="uploadSignedCopy" name="attachment_signed_copy" type="file"
                                        accept="application/*" id="attachment_signed_copy" class="form-control">
                                    <span class="text-danger"
                                        v-if="errors.attachment_signed_copy">{{ errors.attachment_signed_copy[0] }}</span>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input type="checkbox" class="form-check-input" true-value="1"
                                                false-value="0" v-model="document_upload.assign_stamp">
                                            Assign Stamp
                                            <i class="input-helper"></i>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">Process Owner</label>
                                    <multiselect v-model="document_upload.process_owner" :options="process_owners"
                                        placeholder="Select Process Owner" label="name" track-by="id"></multiselect>
                                    <span class="text-danger"
                                        v-if="errors.process_owner">{{ errors.process_owner[0] }}</span>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary btn-md" @click="saveDocumentUpload"
                            :disabled="saveDisable">{{ saveDisable ? 'Saving...' : 'Save' }}</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="document-view-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true" data-backdrop="static">
            <div class="modal-dialog modal-md modal-document-view" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="col-12 modal-title">{{ view_document.title }}</h5>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-3">
                                <ul class="nav nav-tabs nav-tabs-vertical" role="tablist">
                                    <li class="nav-item" v-if="view_document.document_category != '6'">
                                        <a class="nav-link" id="raw-file-tab-vertical" data-bs-toggle="tab"
                                            href="#raw-file" role="tab" aria-controls="raw-file" aria-selected="true">
                                            <i class="ti-file text-info mr-2"></i>
                                            Raw File
                                        </a>
                                    </li>
                                    <li class="nav-item" v-if="view_document.document_category == '6'">
                                        <a class="nav-link" id="fillable-copy-tab-vertical" data-bs-toggle="tab"
                                            href="#fillable-copy" role="tab" aria-controls="fillable-copy"
                                            aria-selected="false">
                                            <i class="ti-file text-primary mr-2"></i>
                                            Fillable Copy
                                        </a>
                                    </li>
                                    <li class="nav-item" v-if="view_document.document_category == '6'">
                                        <a class="nav-link" id="signed-copy-tab-vertical" data-bs-toggle="tab"
                                            href="#signed-copy" role="tab" aria-controls="signed-copy"
                                            aria-selected="false">
                                            <i class="ti-file text-success mr-2"></i>
                                            Signed Copy
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="revisions-tab-vertical" data-bs-toggle="tab"
                                            href="#revisions" role="tab" aria-controls="revisions"
                                            aria-selected="false">
                                            <i class="ti-time text-danger mr-2"></i>
                                            Revisions
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-9">
                                <div class="tab-content tab-content-vertical">
                                    <div :class="view_document.document_category != '6' ? 'tab-pane fade show active' : 'tab-pane fade'"
                                        id="raw-file" role="tabpanel" aria-labelledby="raw-file-tab-vertical">
                                        <h5>Document Attachment (Raw File)</h5>
                                        <div v-if="view_document.attachment_raw_file">
                                            <iframe id="frame-raw-file"
                                                v-if="validateFileFormat(view_document.attachment_raw_file)"
                                                :src="('storage/document_uploads/' + view_document.attachment_raw_file + '#toolbar=0&navpanes=0&scrollbar=0')"
                                                frameborder="1" width="100%" height="600px"></iframe>
                                            <a v-else
                                                :href="'storage/document_uploads/' + view_document.attachment_raw_file"
                                                class="btn btn-outline-info btn-icon-text">View Attachment
                                                <i class="ti-download btn-icon-append"></i>
                                            </a>
                                        </div>
                                        <div class="row mt-5">
                                            <label>Upload Revision Attachment</label>
                                            <div class="col-md-9">

                                                <input @change="uploadRawFile" name="attachment_raw_file" type="file"
                                                    accept="application/*" id="attachment_raw_file"
                                                    class="form-control">
                                                <span class="text-danger"
                                                    v-if="errors.attachment_raw_file">{{ errors.attachment_raw_file[0] }}</span>
                                            </div>
                                            <div class="col-md-3">
                                                <button @click="saveDocumentRevision('Raw File')"
                                                    class="btn btn-primary btn-md">Save Changes</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div :class="view_document.document_category == '6' ? 'tab-pane fade show active' : 'tab-pane fade'"
                                        class="tab-pane fade" id="fillable-copy" role="tabpanel"
                                        aria-labelledby="fillable-copy-tab-vertical">
                                        <h5>Document Attachment (Fillable Copy)</h5>

                                        <div v-if="view_document.attachment_fillable_copy">
                                            <iframe id="frame-fillable-copy"
                                                v-if="validateFileFormat(view_document.attachment_fillable_copy)"
                                                :src="('storage/document_uploads/' + view_document.attachment_fillable_copy + '#toolbar=0&navpanes=0&scrollbar=0')"
                                                frameborder="1" width="100%" height="600px"></iframe>
                                            <a v-else
                                                :href="('storage/document_uploads/' + view_document.attachment_fillable_copy)"
                                                class="btn btn-outline-info btn-icon-text">View Attachment
                                                <i class="ti-download btn-icon-append"></i>
                                            </a>
                                        </div>

                                        <div class="row mt-5">
                                            <label>Upload Revision Attachment</label>
                                            <div class="col-md-9">

                                                <input @change="uploadFillableCopy" name="attachment_fillable_copy"
                                                    type="file" accept="application/*" id="attachment_fillable_copy"
                                                    class="form-control">
                                                <span class="text-danger"
                                                    v-if="errors.attachment_fillable_copy">{{ errors.attachment_fillable_copy[0] }}</span>
                                            </div>
                                            <div class="col-md-3">
                                                <button @click="saveDocumentRevision('Fillable Copy')"
                                                    class="btn btn-primary btn-md">Save Changes</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="signed-copy" role="tabpanel"
                                        aria-labelledby="signed-copy-tab-vertical">
                                        <h5>Document Attachment (Signed Copy)</h5>

                                        <div v-if="view_document.attachment_signed_copy">
                                            <iframe id="frame-signed-copy"
                                                v-if="validateFileFormat(view_document.attachment_signed_copy)"
                                                :src="('storage/document_uploads/' + view_document.attachment_signed_copy + '#toolbar=0&navpanes=0&scrollbar=0')"
                                                frameborder="1" width="100%" height="600px"></iframe>
                                            <a v-else
                                                :href="('storage/document_uploads/' + view_document.attachment_signed_copy)"
                                                class="btn btn-outline-info btn-icon-text">View Attachment
                                                <i class="ti-download btn-icon-append"></i>
                                            </a>
                                        </div>

                                        <div class="row mt-5">
                                            <label>Upload Revision Attachment</label>
                                            <div class="col-md-9">

                                                <input @change="uploadSignedCopy" name="attachment_signed_copy"
                                                    type="file" accept="application/*" id="attachment_signed_copy"
                                                    class="form-control">
                                                <span class="text-danger"
                                                    v-if="errors.attachment_signed_copy">{{ errors.attachment_signed_copy[0] }}</span>
                                            </div>
                                            <div class="col-md-3">
                                                <button @click="saveDocumentRevision('Signed Copy')"
                                                    class="btn btn-primary btn-md">Save Changes</button>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="tab-pane fade" id="revisions" role="tabpanel"
                                        aria-labelledby="revisions-tab-vertical">
                                        <h5>Revisions</h5>
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th class="pt-1">
                                                            Attachment
                                                        </th>
                                                        <th class="pt-1">
                                                            File Type
                                                        </th>
                                                        <th class="pt-1">
                                                            Revision Date
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody v-if="view_document.revisions">
                                                    <tr v-for="(item, index) in view_document.revisions" :key="index">
                                                        <td>
                                                            <a :href="'storage/document_uploads/' + item.attachment"
                                                                target="_blank">
                                                                {{ item.attachment }}</a>
                                                        </td>
                                                        <td>{{ item.file_type }}</td>
                                                        <td>{{ item.created_at }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
    mixins: [listFormMixins],
    components: {
        Multiselect
    },
    data() {
        return {
            endpoint: '/document-uploads',
            document_upload: {
                id: '',
                title: '',
                effective_date: '',
                document_category: '',
                assign_stamp: '',
                company: '',
                department: '',
                attachment_raw_file: '',
                attachment_fillable_copy: '',
                attachment_signed_copy: '',
                assign_stamp: '0',
                process_owner: '',
            },
            action: '',
            companies: [],
            departments: [],
            document_categories: [],
            process_owners: [],
            errors: [],


            attachment_raw_file: '',
            attachment_fillable_copy: '',
            attachment_signed_copy: '',

            saveDisable: false,

            view_document: '',
        }
    },
    created() {
        this.fetchList();
        this.fetchCompanies();
        this.fetchDepartments();
        this.fetchDocumentCategories();
        this.fetchUsers();
    },
    methods: {
        saveDocumentRevision(file_type) {
            let v = this;
            v.saveDisable = true;
            Swal.fire({
                text: "Are you sure you want to save this document revision?",
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
                    var postURL = `/document-uploads/store-revision`;

                    formData.append('id', v.view_document.id ? v.view_document.id : "");
                    formData.append('file_type', file_type);

                    if (file_type == 'Raw File') {
                        formData.append('attachment_raw_file', v.attachment_raw_file ? v.attachment_raw_file : "");
                    }
                    if (file_type == 'Fillable Copy') {
                        formData.append('attachment_fillable_copy', v.attachment_fillable_copy ? v.attachment_fillable_copy : "");
                    }
                    if (file_type == 'Signed Copy') {
                        formData.append('attachment_signed_copy', v.attachment_signed_copy ? v.attachment_signed_copy : "");
                    }
                    axios.post(postURL, formData)
                        .then(response => {
                            if (response.data.status == "success") {
                                Swal.fire('Document has been changed!', '', 'success');
                                v.saveDisable = false;
                                this.view_document = response.data.document_upload;
                                document.getElementById('attachment_raw_file').value = null;
                                document.getElementById('attachment_fillable_copy').value = null;
                                document.getElementById('attachment_signed_copy').value = null;
                                this.attachment_raw_file = '';
                                this.attachment_fillable_copy = '';
                                this.attachment_signed_copy = '';
                            } else {
                                Swal.fire('Error: Cannot changed. Please try again.', '', 'error');
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
                }
            })
        },
        validateFileFormat(document) {
            if (document.indexOf('.pdf') != -1 || document.indexOf('.png') != -1 || document.indexOf('.jpg') != -1) {
                return true;
            } else {
                return false;
            }
        },
        viewDocumentUpload(document) {
            this.view_document = document;
            $('#document-view-modal').modal('show');
        },
        usersDocumentUpload() {

        },
        editDocumentUpload(document_upload) {
            this.clearFields();
            this.action = 'Edit';
            this.document_upload.id = document_upload.id;
            this.document_upload.title = document_upload.title;
            this.document_upload.effective_date = document_upload.effective_date;
            this.document_upload.assign_stamp = document_upload.assign_stamp;
            this.document_upload.document_category = document_upload.document_category_info;
            this.document_upload.company = document_upload.company_info;
            this.document_upload.department = document_upload.department_info;
            this.document_upload.process_owner = document_upload.process_owner_info;
            $('#document-upload-modal').modal('show');
        },
        addDocumentUpload() {
            this.clearFields();
            this.action = 'Add';
            $('#document-upload-modal').modal('show');
        },
        clearFields() {
            let v = this;
            v.errors = [];
            v.document_upload.id = '';
            v.document_upload.title = '';
            v.document_upload.effective_date = '';
            v.document_upload.document_category = '';
            v.document_upload.company = '';
            v.document_upload.department = '';
            v.document_upload.attachment_raw_file = '';
            v.document_upload.attachment_fillable_copy = '';
            v.document_upload.attachment_signed_copy = '';
            v.document_upload.assign_stamp = '0';
            v.document_upload.process_owner = '';
        },
        saveDocumentUpload() {
            let v = this;
            v.saveDisable = true;
            Swal.fire({
                text: "Are you sure you want to save this document?",
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
                    var postURL = `/document-uploads/store`;

                    if (v.action == "Edit") {
                        formData.append('id', v.document_upload.id ? v.document_upload.id : "");
                        postURL = `/document-uploads/update`;
                    }

                    formData.append('title', v.document_upload.title ? v.document_upload.title : "");
                    formData.append('effective_date', v.document_upload.effective_date ? v.document_upload.effective_date : "");
                    formData.append('document_category', v.document_upload.document_category ? v.document_upload.document_category.id : "");
                    formData.append('company', v.document_upload.company ? v.document_upload.company.id : "");
                    formData.append('department', v.document_upload.department ? v.document_upload.department.id : "");
                    formData.append('process_owner', v.document_upload.process_owner ? v.document_upload.process_owner.id : "");

                    if (v.action == "Add") {
                        formData.append('attachment_raw_file', v.attachment_raw_file ? v.attachment_raw_file : "");
                        formData.append('attachment_fillable_copy', v.attachment_fillable_copy ? v.attachment_fillable_copy : "");
                        formData.append('attachment_signed_copy', v.attachment_signed_copy ? v.attachment_signed_copy : "");
                    }

                    formData.append('assign_stamp', v.document_upload.assign_stamp ? v.document_upload.assign_stamp : "");

                    axios.post(postURL, formData)
                        .then(response => {
                            if (response.data.status == "success") {
                                if (v.action == 'Update') {
                                    var index = this.items.findIndex(item => item.id == v.document_upload.id);
                                    this.items.splice(index, 1, response.data.document_upload);
                                } else {
                                    this.fetchList();
                                }

                                Swal.fire('Document has been changed!', '', 'success');

                                $('#document-upload-modal').modal('hide');

                                this.clearFields();
                                v.saveDisable = false;
                            } else {
                                Swal.fire('Error: Cannot changed. Please try again.', '', 'error');
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
        fetchUsers() {
            let v = this;
            v.process_owners = [];
            axios.get('/user-options')
                .then(response => {
                    v.process_owners = response.data;
                })
                .catch(error => {
                    v.errors = error.response.data.error;
                })
        },
        uploadRawFile(e) {
            var files = e.target.files || e.dataTransfer.files;
            if (!files.length)
                return;
            this.attachment_raw_file = files[0];
        },
        uploadFillableCopy(e) {
            var files = e.target.files || e.dataTransfer.files;
            if (!files.length)
                return;
            this.attachment_fillable_copy = files[0];
        },
        uploadSignedCopy(e) {
            var files = e.target.files || e.dataTransfer.files;
            if (!files.length)
                return;
            this.attachment_signed_copy = files[0];
        },

    }
}
</script>

<style>
.modal-document-upload {
    max-width: 700px !important;
}

.modal-document-view {
    max-width: 1200px !important;
}
</style> 