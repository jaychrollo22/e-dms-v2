<template>
    <div>
        <div class="content-wrapper">
            <div class="row justify-content-center">
                <div class="col-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Document Uploads</h4>

                            <div class="d-flex flex-wrap justify-content-end">
                                <button class="btn btn-primary text-white btn-icon" @click="addDocumentUpload"><i
                                        class="ti-plus"></i> </button>
                            </div>

                            <div class="row mt-2">
                                <div class="col-md-6">
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
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <div class="input-group-prepend bg-transparent">
                                                <span class="input-group-text bg-transparent border-right-0">
                                                    <i class="ti-settings text-primary"></i>
                                                </span>
                                            </div>
                                            <select v-model="filterData.company" @change="searchKeyUp"
                                                class="form-control form-control-lg border-left-0" name="company"
                                                id="company">
                                                <option value="">Choose Company</option>
                                                <option v-for="(company, index) in companies" :key="index"
                                                    :value="company.id">
                                                    {{ company.company_name }}
                                                </option>
                                            </select>
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
                                            <select v-model="filterData.department" @change="searchKeyUp"
                                                class="form-control form-control-lg border-left-0" name="department"
                                                id="department">
                                                <option value="">Choose Department</option>
                                                <option v-for="(department, index) in departments" :key="index"
                                                    :value="department.id">
                                                    {{ department.department }}
                                                </option>
                                            </select>
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
                                            <select v-model="filterData.status" @change="searchKeyUp"
                                                class="form-control form-control-lg border-left-0" name="status"
                                                id="status">
                                                <option value="">Choose Status</option>
                                                <option value="Pending">Pending</option>
                                                <option value="Approved">Approved</option>
                                                <option value="Disapproved">Disapproved</option>
                                            </select>
                                        </div>
                                        <span class="text-danger" v-if="errors.status">{{ errors.status[0] }}</span>
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
                                            <!-- <th class="pt-1">
                                                Tag
                                            </th> -->
                                            <th class="pt-1">
                                                Effective Date
                                            </th>
                                            <th class="pt-1">
                                                Status
                                            </th>
                                            <th class="pt-1 text-center">
                                                Action
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-if="isProcessing">
                                            <td colspan="12">
                                                <div class="dot-opacity-loader">
                                                    <span></span>
                                                    <span></span>
                                                    <span></span>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr v-else v-for="(document, index) in items" :key="index">
                                            <td>
                                                {{ document.control_code }}
                                            </td>
                                            <td>
                                                {{ document.title }}
                                            </td>
                                            <td>
                                                {{ document.company_info ? document.company_info.company_code : "" }}
                                            </td>
                                            <td>
                                                {{ document.department_info ? document.department_info.department : "" }}
                                            </td>
                                            <td>
                                                {{ document.document_category_info ? document.document_category_info.category_description : "" }}
                                            </td>
                                            <!-- <td>
                                                {{ document.document_category_info ? document.document_category_info.tag_info.description : "" }}
                                            </td> -->
                                            <td>
                                                {{ document.effective_date }}
                                            </td>
                                            <td>
                                                <div :class="getStatus(document.status)">
                                                    {{ document.status }}
                                                </div>
                                                <br>
                                                <div v-if="document.is_discontinuance == '1'"
                                                    class="badge badge-warning mt-1">
                                                    Discontinuance
                                                </div> <br>
                                                <div v-if="document.is_obsolete == '1'" class="badge badge-danger mt-1">
                                                    Obsolete
                                                </div>
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
                                                    title="Assign Users" @click="assignUser(document)">
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

        <!-- Document Upload -->
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
                                    <label for="">Control Code</label>
                                    <input type="text" class="form-control" placeholder="Control Code"
                                        v-model="document_upload.control_code" :disabled="auto_generate_control_code">
                                    <span class="text-danger" v-if="errors.title">{{ errors.control_code[0] }}</span>
                                    <div class="form-check"
                                        v-if="document_upload.control_code == '' || document_upload.control_code == null">
                                        <label class="form-check-label text-muted">
                                            <input type="checkbox" v-model="auto_generate_control_code"
                                                class="form-check-input">
                                            <a href="#">Generate Control Code</a>
                                            <i class="input-helper"></i><i class="input-helper"></i></label>
                                    </div>
                                </div>

                            </div>
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
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input type="checkbox" class="form-check-input" true-value="1"
                                                false-value="0" v-model="document_upload.is_form">
                                            Form
                                            <i class="input-helper"></i>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6" v-if="document_upload.document_category.id == '7'">
                                <div class="form-group">
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input type="checkbox" class="form-check-input" true-value="1"
                                                false-value="0" v-model="document_upload.is_procedure_link">
                                            Link Specific Policy
                                            <i class="input-helper"></i>
                                        </label>
                                    </div>
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
                                <div class="form-group">
                                    <label>Document Attachment (Raw)</label>
                                    <input @change="uploadRawFile" name="attachment_raw_file" type="file"
                                        accept="application/*" id="attachment_raw_file" class="form-control">
                                    <span class="text-danger"
                                        v-if="errors.attachment_raw_file">{{ errors.attachment_raw_file[0] }}</span>
                                </div>
                                <div class="form-group" v-if="document_upload.is_form == '1'">
                                    <label>Document Attachment (Fillable Copy)</label>
                                    <input @change="uploadFillableCopy" name="attachment_fillable_copy" type="file"
                                        accept="application/*" id="attachment_fillable_copy" class="form-control">
                                    <span class="text-danger"
                                        v-if="errors.attachment_fillable_copy">{{ errors.attachment_fillable_copy[0] }}</span>
                                </div>
                                <div class="form-group">
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
                            :disabled="saveDisable">{{ saveDisable? 'Saving...': 'Save' }}</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- View Document -->
        <div class="modal fade" id="document-view-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true" data-backdrop="static">
            <div class="modal-dialog modal-md modal-document-view" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="col-12 modal-title">{{ view_document.title }}</h5>
                    </div>
                    <div class="modal-body">
                        <div class="row" v-if="view_document">
                            <div class="col-3">
                                <ul class="nav nav-tabs nav-tabs-vertical" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link" id="raw-file-tab-vertical" data-bs-toggle="tab"
                                            href="#raw-file" role="tab" aria-controls="raw-file" aria-selected="true">
                                            <i class="ti-file text-info mr-2"></i>
                                            Raw File
                                        </a>
                                    </li>
                                    <li class="nav-item" v-if="view_document.is_form == '1'">
                                        <a class="nav-link" id="fillable-copy-tab-vertical" data-bs-toggle="tab"
                                            href="#fillable-copy" role="tab" aria-controls="fillable-copy"
                                            aria-selected="false">
                                            <i class="ti-file text-primary mr-2"></i>
                                            Fillable Copy
                                        </a>
                                    </li>
                                    <li class="nav-item">
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
                                    <div :class="'tab-pane fade show active'" id="raw-file" role="tabpanel"
                                        aria-labelledby="raw-file-tab-vertical">
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
                                    <div :class="view_document.is_form == '1' ? 'tab-pane fade' : 'tab-pane fade'"
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
                                                :src="('document-uploads-view-signed-copy?id=' + view_document.id + '#toolbar=0&navpanes=0&scrollbar=0')"
                                                frameborder="1" width="100%" height="600px"></iframe>
                                            <a v-else
                                                :href="('storage/document_uploads/' + view_document.attachment_signed_copy)"
                                                class="btn btn-outline-info btn-icon-text">View Attachment
                                                <i class="ti-download btn-icon-append"></i>
                                            </a>
                                        </div>

                                        <div class="row mt-5">
                                            <div class="col-md-6">
                                                <label>Upload Revision Attachment <a
                                                        v-if="view_document.attachment_signed_copy_revision" :href="'storage/document_uploads/' +
                                                        view_document.attachment_signed_copy_revision" target="_blank"
                                                        class="badge badge-pill badge-success">View</a></label>
                                                <input @change="uploadSignedCopyRevision"
                                                    name="attachment_signed_copy_revision" type="file"
                                                    accept="application/*" id="attachment_signed_copy_revision"
                                                    class="form-control">

                                                <span class="text-danger"
                                                    v-if="errors.attachment_signed_copy_revision">{{ errors.attachment_signed_copy_revision[0] }}</span>
                                            </div>
                                            <div class="col-md-6">
                                                <label>Revision Implementation Date</label>
                                                <div class="form-group">
                                                    <input class="form-control" type="date"
                                                        v-model="view_document.implementation_date">
                                                </div>
                                            </div>
                                            <div class="col-md-12">
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
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="">For Approval Status</label>
                                <div class="form-group row">
                                    <div class="col-sm-3">
                                        <div class="form-check">
                                            <label class="form-check-label">
                                                <input v-model="view_document.status" type="radio"
                                                    class="form-check-input" id="Approved" value="Approved">
                                                Approve
                                                <i class="input-helper"></i></label>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-check">
                                            <label class="form-check-label">
                                                <input v-model="view_document.status" type="radio"
                                                    class="form-check-input" id="Disapproved" value="Disapproved">
                                                Disapprove
                                                <i class="input-helper"></i></label>
                                        </div>
                                    </div>
                                </div>
                                <span class="text-danger" v-if="errors.status">{{ errors.status[0] }}</span>
                            </div>
                            <div class="col-md-12"
                                v-if="view_document.status == 'Approved' || view_document.status == 'Disapproved'">
                                <label for="">Status Remarks</label>
                                <div class="form-group">
                                    <textarea v-model="view_document.status_remarks" cols="30" rows="5"
                                        class="form-control" placeholder="Approval Remarks"></textarea>
                                    <span class="text-danger"
                                        v-if="errors.status_remarks">{{ errors.status_remarks[0] }}</span>
                                </div>
                            </div>
                        </div>

                        <button class="btn btn-sm btn-primary" @click="updateDocumentUpload"
                            :disabled="saveDisable">{{ saveDisable? 'Saving...': 'Save Changes' }}</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Assign User -->
        <div class="modal fade" id="assign-user-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true" data-backdrop="static">
            <div class="modal-dialog modal-md modal-document-view" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="col-12 modal-title">{{ view_document.title }} | Assign Users</h5>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="Search User"
                                        v-model="filter.search_user">
                                    <span class="text-danger"
                                        v-if="errors.search_user">{{ errors.search_user[0] }}</span>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <multiselect v-model="filter.company" :options="companies"
                                        placeholder="Select Company" label="company_name" track-by="id"></multiselect>
                                    <span class="text-danger" v-if="errors.company">{{ errors.company[0] }}</span>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <multiselect v-model="filter.department" :options="departments"
                                        placeholder="Select Department" label="department" track-by="id"></multiselect>
                                    <span class="text-danger" v-if="errors.department">{{ errors.department[0] }}</span>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-md btn-primary" @click="filterUser">Apply Filter</button>
                            </div>

                            <div class="col-md-12">

                                <ul class="nav nav-tabs" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="filtered-user-tab" data-bs-toggle="tab"
                                            href="#filtered-user" role="tab" aria-controls="filtered-user"
                                            aria-selected="true">
                                            <i class="ti-user text-warning mr-2"></i>
                                            Filtered Users
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="assigned-user-tab" data-bs-toggle="tab"
                                            href="#assigned-user" role="tab" aria-controls="assigned-user"
                                            aria-selected="false">
                                            <i class="ti-user text-success mr-2"></i>
                                            Assigned Users
                                        </a>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <!-- Filtered Users -->
                                    <div class="tab-pane fade active show" id="filtered-user" role="tabpanel"
                                        aria-labelledby="filtered-user-tab">
                                        <div class="mb-2">
                                            Show
                                            <select v-model="itemsPerPageFilteredUser" @change="showPageFilteredUser">
                                                <option value="5">5</option>
                                                <option value="10">10</option>
                                                <option value="25">25</option>
                                                <option value="50">50</option>
                                                <option value="100">100</option>
                                            </select>
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>
                                                            <div class="form-check">
                                                                <label class="form-check-label">
                                                                    <input type="checkbox" class="form-check-input"
                                                                        true-value="1" false-value="0" id="select-all"
                                                                        v-model="isSelectAll" @change="selectAllUsers">
                                                                    Select All ({{ bulkCheckSelectedIds.length }})
                                                                    <i class="input-helper"></i>
                                                                </label>
                                                            </div>
                                                        </th>
                                                        <th class="pt-1">
                                                            Employee Information
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody v-if="(filterUsers.length > 0)">
                                                    <tr v-for="(filter_user, i) in filteredQueues" :key="i">
                                                        <td width="20" align="center">
                                                            <div class="form-check">
                                                                <label class="form-check-label">
                                                                    <input type="checkbox" class="form-check-input"
                                                                        v-if="showCheckUser(filter_user)" true-value="1"
                                                                        false-value="0" :id="filter_user.id + '-select'"
                                                                        :value="filter_user.id"
                                                                        v-model="bulkCheckSelectedIds">
                                                                    <input type="checkbox" class="form-check-input"
                                                                        title="Selected" v-else disabled checked>
                                                                    <i class="input-helper"></i>
                                                                </label>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <strong>{{ filter_user.name }}</strong> <br>
                                                            <small
                                                                v-if="filter_user.company">{{ filter_user.company.company_info ? filter_user.company.company_info.company_name : "" }}</small>
                                                            <br>
                                                            <small
                                                                v-if="filter_user.department">{{ filter_user.department.department_info ? filter_user.department.department_info.department : "" }}</small>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="row mb-3 mt-3 ml-1" v-if="filteredQueues.length">
                                            <div class="col-6">
                                                <button :disabled="!showPreviousLinkFilteredUser()"
                                                    class="btn btn-default btn-sm btn-fill"
                                                    v-on:click="setPageFilteredUser(currentPageFilteredUser - 1)">
                                                    Previous </button>
                                                <span class="text-dark">Page {{ currentPageFilteredUser + 1 }} of
                                                    {{ totalPagesFilteredUser }}</span>
                                                <button :disabled="!showNextLinkFilteredUser()"
                                                    class="btn btn-default btn-sm btn-fill"
                                                    v-on:click="setPageFilteredUser(currentPageFilteredUser + 1)"> Next
                                                </button>
                                            </div>
                                        </div>

                                        <button v-if="bulkCheckSelectedIds.length > 0" class="btn btn-success btn-sm"
                                            @click="saveAssignUser" :disabled="saveDisable">Save</button>

                                    </div>
                                    <!-- Assigned Users -->
                                    <div class="tab-pane fade" id="assigned-user" role="tabpanel"
                                        aria-labelledby="assigned-user-tab">
                                        <div class="mb-2">
                                            Show
                                            <select v-model="itemsPerPageAssignedUser" @change="showPageAssignedUser">
                                                <option value="5">5</option>
                                                <option value="10">10</option>
                                                <option value="25">25</option>
                                                <option value="50">50</option>
                                                <option value="100">100</option>
                                            </select>
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th width="20" class="pt-3">
                                                            <div class="form-check">
                                                                <label class="form-check-label">
                                                                    <input type="checkbox" class="form-check-input"
                                                                        true-value="1" false-value="0" id="remove-all"
                                                                        v-model="isRemoveAll" @change="removeAllUsers">
                                                                    Select All ({{ bulkAssignSelectedIds.length }})
                                                                    <i class="input-helper"></i>
                                                                </label>
                                                            </div>
                                                        </th>
                                                        <th class="pt-3">
                                                            Employee Information
                                                        </th>
                                                        <th class="pt-3 text-center">
                                                            Document Permission
                                                        </th>

                                                    </tr>
                                                </thead>
                                                <tbody v-if="(assignedUsers.length > 0)">
                                                    <tr v-for="(assigned_user, i) in assignedQueues" :key="i">
                                                        <td align="center">
                                                            <div class="form-check">
                                                                <label class="form-check-label">
                                                                    <input type="checkbox" title="Selected"
                                                                        :id="assigned_user.id + '-remove'"
                                                                        :value="assigned_user.id"
                                                                        v-model="bulkAssignSelectedIds"
                                                                        class="form-check-input">
                                                                    <i class="input-helper"></i>
                                                                </label>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <strong>{{ assigned_user.user_info.name }}</strong> <br>
                                                            <small
                                                                v-if="assigned_user.user_info.company">{{ assigned_user.user_info.company.company_info ? assigned_user.user_info.company.company_info.company_name : "" }}</small>
                                                            <br>
                                                            <small
                                                                v-if="assigned_user.user_info.department">{{ assigned_user.user_info.department.department_info ? assigned_user.user_info.department.department_info.department : "" }}</small>
                                                        </td>
                                                        <td class="text-center">
                                                            <button v-if="assigned_user.can_print == '1'" type="button"
                                                                class="btn btn-inverse-success btn-rounded btn-icon"
                                                                title="Disable Print" @click="canPrint(assigned_user)">
                                                                <i class="ti-printer"></i>
                                                            </button>
                                                            <button v-else type="button"
                                                                class="btn btn-inverse-secondary btn-rounded btn-icon"
                                                                title="Enable Print" @click="canPrint(assigned_user)">
                                                                <i class="ti-printer"></i>
                                                            </button>

                                                            <button v-if="assigned_user.can_download == '1'"
                                                                type="button"
                                                                class="btn btn-inverse-primary btn-rounded btn-icon"
                                                                title="Disable Download"
                                                                @click="canDownload(assigned_user)">
                                                                <i class="ti-cloud-down"></i>
                                                            </button>
                                                            <button v-else type="button"
                                                                class="btn btn-inverse-secondary btn-rounded btn-icon"
                                                                title="Enable Download"
                                                                @click="canDownload(assigned_user)">
                                                                <i class="ti-cloud-down"></i>
                                                            </button>

                                                            <button v-if="assigned_user.can_edit == '1'" type="button"
                                                                class="btn btn-inverse-warning btn-rounded btn-icon"
                                                                title="Disable Edit" @click="canEdit(assigned_user)">
                                                                <i class="ti-pencil"></i>
                                                            </button>
                                                            <button v-else type="button"
                                                                class="btn btn-inverse-secondary btn-rounded btn-icon"
                                                                title="Enable Edit" @click="canEdit(assigned_user)">
                                                                <i class="ti-pencil"></i>
                                                            </button>


                                                            <button v-if="view_document.is_form == '1'" type="button"
                                                                :class="getClassCanFill(assigned_user.can_fill)"
                                                                :title="getTitleCanFill(assigned_user.can_fill)"
                                                                @click="canFill(assigned_user)">
                                                                <i class="ti-pencil-alt"></i>
                                                            </button>

                                                        </td>

                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="row mb-3 mt-3 ml-1" v-if="assignedQueues.length">
                                            <div class="col-6">
                                                <button :disabled="!showPreviousLinkAssignedUser()"
                                                    class="btn btn-default btn-sm btn-fill"
                                                    v-on:click="setPageAssignedUser(currentPageAssignedUser - 1)">
                                                    Previous </button>
                                                <span class="text-dark">Page {{ currentPageAssignedUser + 1 }} of
                                                    {{ totalPagesAssignedUser }}</span>
                                                <button :disabled="!showNextLinkAssignedUser()"
                                                    class="btn btn-default btn-sm btn-fill"
                                                    v-on:click="setPageAssignedUser(currentPageAssignedUser + 1)"> Next
                                                </button>
                                            </div>
                                        </div>

                                        <button v-if="bulkAssignSelectedIds.length > 0"
                                            class="btn btn-danger btn-sm text-white" @click="saveRemoveAssignUser"
                                            :disabled="saveDisable"><i class="ti-trash"></i>
                                            Remove ({{ bulkAssignSelectedIds.length }})</button>
                                        <button v-if="bulkAssignSelectedIds.length > 0"
                                            class="btn btn-success btn-sm text-white"
                                            @click="saveCanPrintUserAssignUser" :disabled="saveDisable"><i
                                                class="ti-printer"></i>
                                            Allow Print ({{ bulkAssignSelectedIds.length }})</button>
                                        <button v-if="bulkAssignSelectedIds.length > 0"
                                            class="btn btn-primary btn-sm text-white"
                                            @click="saveCanDownloadUserAssignUser" :disabled="saveDisable"><i
                                                class="ti-cloud-down"></i>
                                            Allow Download ({{ bulkAssignSelectedIds.length }})</button>
                                        <button v-if="bulkAssignSelectedIds.length > 0"
                                            class="btn btn-warning btn-sm text-white" @click="saveCanEditUserAssignUser"
                                            :disabled="saveDisable"><i class="ti-pencil"></i>
                                            Allow Edit ({{ bulkAssignSelectedIds.length }})</button>


                                        <button v-if="view_document.is_form == '1' && bulkAssignSelectedIds.length > 0"
                                            class="btn btn-info btn-sm text-white" @click="saveCanFillUserAssignUser"
                                            :disabled="saveDisable"><i class="ti-pencil-alt"></i>
                                            Allow Fill ({{ bulkAssignSelectedIds.length }})</button>

                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">

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
    props: ['role'],
    mixins: [listFormMixins],
    components: {
        Multiselect
    },
    data() {
        return {
            endpoint: '/document-uploads',
            auto_generate_control_code: false,
            document_upload: {
                id: '',
                control_code: '',
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
            attachment_signed_copy_revision: '',
            implementation_date: '',

            saveDisable: false,

            view_document: '',

            filter: {
                search_user: '',
                company: '',
                department: '',
            },

            filterUsers: [],
            currentPageFilteredUser: 0,
            itemsPerPageFilteredUser: 10,
            showAssignUser: true,

            documentUploadUsers: [],
            currentPageAssignedUser: 0,
            itemsPerPageAssignedUser: 5,

            isSelectAll: 0,
            bulkCheckSelectedIds: [],

            isRemoveAll: 0,
            bulkAssignSelectedIds: [],

            filterData: {
                company: '',
                department: '',
                status: '',
            },

            isAllowedToApprove: false,
            role_ids: [],

        }
    },
    created() {
        this.fetchList();
        this.fetchCompanies();
        this.fetchDepartments();
        this.fetchDocumentCategories();
        this.fetchUsers();

        this.getRole();
    },
    methods: {
        showPageFilteredUser() {
            this.currentPageFilteredUser = 0;
        },
        showPageAssignedUser() {
            this.currentPageAssignedUser = 0;
        },
        getRole() {
            this.role_ids = JSON.parse(this.role);
            if (this.role_ids.includes(1)) { //Administrator
                this.isAllowedToApprove = true;
            }
            else if (this.role_ids.includes(3)) { //DCO Holdings
                this.isAllowedToApprove = true;
            } else {
                this.isAllowedToApprove = false;
            }
        },
        getTitleCanFill(status) {
            var status_title = status == 1 ? 'Disable' : 'Enable';
            return status_title + ' Fill';
        },
        getClassCanFill(status) {
            if (status == '1') {
                return 'btn btn-inverse-info btn-rounded btn-icon';
            } else {
                return 'btn btn-inverse-secondary btn-rounded btn-icon';
            }
        },
        updateDocumentUpload() {
            let v = this;
            v.saveDisable = true;
            Swal.fire({
                text: "Are you sure you want to save this document request?",
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
                    formData.append('id', v.view_document.id ? v.view_document.id : "");
                    formData.append('status', v.view_document.status ? v.view_document.status : "");
                    formData.append('status_remarks', v.view_document.status_remarks ? v.view_document.status_remarks : "");
                    axios.post(`/document-uploads/update-approval`, formData)
                        .then(response => {
                            if (response.data.status == "success") {
                                Swal.fire('Document request has been updated!', '', 'success');
                                v.saveDisable = false;
                                var index = this.items.findIndex(item => item.id == v.view_document.id);
                                this.items.splice(index, 1, response.data.document_upload);
                                $('#request-view-modal').modal('hide');
                            } else {
                                Swal.fire('Error: Cannot saved. Please try again.', '', 'error');
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
        canDownload(document_user) {
            let v = this;
            if (document_user) {
                var index = v.documentUploadUsers.findIndex(item => item.id == document_user.id);
                let formData = new FormData();
                formData.append('id', document_user.id ? document_user.id : "");
                axios.post(`/document-uploads/save-document-upload-user-download`, formData)
                    .then(response => {
                        if (response.data.status == "saved") {
                            v.documentUploadUsers.splice(index, 1, response.data.document_upload_user);
                        }
                    })
                    .catch(error => {
                        v.errors = error.response.data.errors;
                    })
            }
        },
        canPrint(document_user) {
            let v = this;
            if (document_user) {
                var index = v.documentUploadUsers.findIndex(item => item.id == document_user.id);
                let formData = new FormData();
                formData.append('id', document_user.id ? document_user.id : "");
                axios.post(`/document-uploads/save-document-upload-user-print`, formData)
                    .then(response => {
                        if (response.data.status == "saved") {
                            v.documentUploadUsers.splice(index, 1, response.data.document_upload_user);
                        }
                    })
                    .catch(error => {
                        v.errors = error.response.data.errors;
                    })
            }
        },
        canFill(document_user) {
            let v = this;
            if (document_user) {
                var index = v.documentUploadUsers.findIndex(item => item.id == document_user.id);
                let formData = new FormData();
                formData.append('id', document_user.id ? document_user.id : "");
                axios.post(`/document-uploads/save-document-upload-user-fill`, formData)
                    .then(response => {
                        if (response.data.status == "saved") {
                            v.documentUploadUsers.splice(index, 1, response.data.document_upload_user);
                        }
                    })
                    .catch(error => {
                        v.errors = error.response.data.errors;
                    })
            }
        },
        canEdit(document_user) {
            let v = this;
            if (document_user) {
                var index = v.documentUploadUsers.findIndex(item => item.id == document_user.id);
                let formData = new FormData();
                formData.append('id', document_user.id ? document_user.id : "");
                axios.post(`/document-uploads/save-document-upload-user-edit`, formData)
                    .then(response => {
                        if (response.data.status == "saved") {
                            v.documentUploadUsers.splice(index, 1, response.data.document_upload_user);
                        }
                    })
                    .catch(error => {
                        v.errors = error.response.data.errors;
                    })
            }
        },
        getAssignedUsers() {
            let v = this;
            v.documentUploadUsers = [];
            axios.get('/document-uploads/get-users?id=' + v.view_document.id)
                .then(response => {
                    v.documentUploadUsers = response.data;
                })
                .catch(error => {
                    v.errors = error.response.data.error;
                })
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
        showCheckUser(item) {
            let v = this;
            if (v.view_document.users) {
                var user_check = Object.values(v.view_document.users).filter(user => {
                    if (item.id == user.user_id && user.status == '1') {
                        return user;
                    }
                });
                if (user_check.length > 0) {
                    return false;
                } else {
                    return true;
                }

            } else {
                return true;
            }

        },
        saveAssignUser() {
            let v = this;
            v.saveDisable = true;
            Swal.fire({
                text: "Are you sure you want to save this document users?",
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
                    var postURL = `/document-uploads/store-user`;
                    formData.append('id', v.view_document.id ? v.view_document.id : "");
                    formData.append('user_ids', v.bulkCheckSelectedIds.length > 0 ? JSON.stringify(v.bulkCheckSelectedIds) : "");

                    axios.post(postURL, formData)
                        .then(response => {
                            if (response.data.status == "success") {
                                Swal.fire('Users (' + response.data.count + ') has been assigned! ', '', 'success');
                                v.saveDisable = false;
                                this.view_document = response.data.document_upload;
                                var index = this.items.findIndex(item => item.id == v.view_document.id);
                                this.items.splice(index, 1, response.data.document_upload);
                                this.getAssignedUsers();
                                this.filterUser(); //

                                this.bulkCheckSelectedIds = [];
                                this.isSelectAll = 0;

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
                } else {
                    v.saveDisable = false;
                }
            })
        },
        saveRemoveAssignUser() {
            let v = this;
            v.saveDisable = true;
            Swal.fire({
                text: "Are you sure you want to remove this document users?",
                icon: "question",
                showCancelButton: true,
                buttonsStyling: false,
                confirmButtonText: "Yes, Remove",
                cancelButtonText: "No, cancel",
                customClass: {
                    confirmButton: "btn font-weight-bold btn-primary",
                    cancelButton: "btn font-weight-bold btn-default"
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    let formData = new FormData();
                    var postURL = `/document-uploads/remove-user`;
                    formData.append('id', v.view_document.id ? v.view_document.id : "");
                    formData.append('document_user_ids', v.bulkAssignSelectedIds.length > 0 ? JSON.stringify(v.bulkAssignSelectedIds) : "");

                    axios.post(postURL, formData)
                        .then(response => {
                            if (response.data.status == "success") {
                                Swal.fire('Users (' + response.data.count + ') has been removed! ', '', 'success');
                                v.saveDisable = false;
                                this.view_document = response.data.document_upload;
                                this.filterUser();

                                v.bulkAssignSelectedIds.forEach(function (item) {
                                    var index = v.documentUploadUsers.findIndex(item2 => item2.id == item);
                                    v.documentUploadUsers.splice(index, 1);
                                });

                                v.bulkAssignSelectedIds = [];
                                v.isRemoveAll = 0;
                            } else {
                                Swal.fire('Error: Cannot changed. Please try again.', '', 'error');
                                v.saveDisable = false;
                            }
                        })
                        .catch(error => {
                            console.log(error);
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
        saveCanPrintUserAssignUser() {
            let v = this;
            v.saveDisable = true;
            Swal.fire({
                text: "Are you sure you want to allow print for this document users?",
                icon: "question",
                showCancelButton: true,
                buttonsStyling: false,
                confirmButtonText: "Yes, Allow",
                cancelButtonText: "No, cancel",
                customClass: {
                    confirmButton: "btn font-weight-bold btn-primary",
                    cancelButton: "btn font-weight-bold btn-default"
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    let formData = new FormData();
                    var postURL = `/document-uploads/allow-print-user`;
                    formData.append('id', v.view_document.id ? v.view_document.id : "");
                    formData.append('document_user_ids', v.bulkAssignSelectedIds.length > 0 ? JSON.stringify(v.bulkAssignSelectedIds) : "");

                    axios.post(postURL, formData)
                        .then(response => {
                            if (response.data.status == "success") {
                                Swal.fire('Users (' + response.data.count + ') has been allowed to print! ', '', 'success');
                                v.saveDisable = false;
                                this.view_document = response.data.document_upload;
                                this.filterUser();
                                this.getAssignedUsers();

                                v.bulkAssignSelectedIds = [];
                                v.isRemoveAll = 0;
                            } else {
                                Swal.fire('Error: Cannot changed. Please try again.', '', 'error');
                                v.saveDisable = false;
                            }
                        })
                        .catch(error => {
                            console.log(error);
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
        saveCanDownloadUserAssignUser() {
            let v = this;
            v.saveDisable = true;
            Swal.fire({
                text: "Are you sure you want to allow download for this document users?",
                icon: "question",
                showCancelButton: true,
                buttonsStyling: false,
                confirmButtonText: "Yes, Allow",
                cancelButtonText: "No, cancel",
                customClass: {
                    confirmButton: "btn font-weight-bold btn-primary",
                    cancelButton: "btn font-weight-bold btn-default"
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    let formData = new FormData();
                    var postURL = `/document-uploads/allow-download-user`;
                    formData.append('id', v.view_document.id ? v.view_document.id : "");
                    formData.append('document_user_ids', v.bulkAssignSelectedIds.length > 0 ? JSON.stringify(v.bulkAssignSelectedIds) : "");

                    axios.post(postURL, formData)
                        .then(response => {
                            if (response.data.status == "success") {
                                Swal.fire('Users (' + response.data.count + ') has been allowed to download! ', '', 'success');
                                v.saveDisable = false;
                                this.view_document = response.data.document_upload;
                                this.filterUser();
                                this.getAssignedUsers();

                                v.bulkAssignSelectedIds = [];
                                v.isRemoveAll = 0;
                            } else {
                                Swal.fire('Error: Cannot changed. Please try again.', '', 'error');
                                v.saveDisable = false;
                            }
                        })
                        .catch(error => {
                            console.log(error);
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
        saveCanFillUserAssignUser() {
            let v = this;
            v.saveDisable = true;
            Swal.fire({
                text: "Are you sure you want to allow fill for this document users?",
                icon: "question",
                showCancelButton: true,
                buttonsStyling: false,
                confirmButtonText: "Yes, Allow",
                cancelButtonText: "No, cancel",
                customClass: {
                    confirmButton: "btn font-weight-bold btn-primary",
                    cancelButton: "btn font-weight-bold btn-default"
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    let formData = new FormData();
                    var postURL = `/document-uploads/allow-fill-user`;
                    formData.append('id', v.view_document.id ? v.view_document.id : "");
                    formData.append('document_user_ids', v.bulkAssignSelectedIds.length > 0 ? JSON.stringify(v.bulkAssignSelectedIds) : "");

                    axios.post(postURL, formData)
                        .then(response => {
                            if (response.data.status == "success") {
                                Swal.fire('Users (' + response.data.count + ') has been allowed to fil! ', '', 'success');
                                v.saveDisable = false;
                                this.view_document = response.data.document_upload;
                                this.filterUser();
                                this.getAssignedUsers();

                                v.bulkAssignSelectedIds = [];
                                v.isRemoveAll = 0;
                            } else {
                                Swal.fire('Error: Cannot changed. Please try again.', '', 'error');
                                v.saveDisable = false;
                            }
                        })
                        .catch(error => {
                            console.log(error);
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
        saveCanEditUserAssignUser() {
            let v = this;
            v.saveDisable = true;
            Swal.fire({
                text: "Are you sure you want to allow edit for this document users?",
                icon: "question",
                showCancelButton: true,
                buttonsStyling: false,
                confirmButtonText: "Yes, Allow",
                cancelButtonText: "No, cancel",
                customClass: {
                    confirmButton: "btn font-weight-bold btn-primary",
                    cancelButton: "btn font-weight-bold btn-default"
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    let formData = new FormData();
                    var postURL = `/document-uploads/allow-edit-user`;
                    formData.append('id', v.view_document.id ? v.view_document.id : "");
                    formData.append('document_user_ids', v.bulkAssignSelectedIds.length > 0 ? JSON.stringify(v.bulkAssignSelectedIds) : "");

                    axios.post(postURL, formData)
                        .then(response => {
                            if (response.data.status == "success") {
                                Swal.fire('Users (' + response.data.count + ') has been allowed to fil! ', '', 'success');
                                v.saveDisable = false;
                                this.view_document = response.data.document_upload;
                                this.filterUser();
                                this.getAssignedUsers();

                                v.bulkAssignSelectedIds = [];
                                v.isRemoveAll = 0;
                            } else {
                                Swal.fire('Error: Cannot changed. Please try again.', '', 'error');
                                v.saveDisable = false;
                            }
                        })
                        .catch(error => {
                            console.log(error);
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
        selectAllUsers() {
            let v = this;
            v.bulkCheckSelectedIds = [];
            if (v.isSelectAll == 1) {
                v.filterUsers.forEach(function (item) {
                    if (v.showCheckUser(item)) {
                        v.bulkCheckSelectedIds.push(item.id);
                    }
                });
            } else {
                v.bulkCheckSelectedIds = [];
            }
        },
        removeAllUsers() {
            let v = this;
            if (v.isRemoveAll == '1') {
                v.bulkAssignSelectedIds = [];
                v.documentUploadUsers.forEach(function (item) {
                    v.bulkAssignSelectedIds.push(item.id);
                });
            } else if (v.isRemoveAll == '0') {
                v.bulkAssignSelectedIds = [];
            }
        },
        filterUser() {
            let v = this;
            v.filterUsers = [];
            v.showAssignUser = false;
            var company = v.filter.company ? v.filter.company.id : "";
            var department = v.filter.department ? v.filter.department.id : "";
            var search_user = v.filter.search_user ? v.filter.search_user : "";
            axios.get('/user-options?company=' + company + '&department=' + department + '&search_user=' + search_user)
                .then(response => {
                    v.filterUsers = response.data;
                })
                .catch(error => {
                    v.errors = error.response.data.error;
                })
        },
        assignUser(document) {
            this.isSelectAll = 0;
            this.isRemoveAll = 0;
            this.bulkCheckSelectedIds = [];
            this.bulkAssignSelectedIds = [];
            this.filterUser();
            this.view_document = document;
            this.getAssignedUsers();
            $('#assign-user-modal').modal('show');
        },
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
                        formData.append('attachment_signed_copy_revision', v.attachment_signed_copy_revision ? v.attachment_signed_copy_revision : "");
                        formData.append('implementation_date', v.view_document.implementation_date ? v.view_document.implementation_date : "");
                    }
                    axios.post(postURL, formData)
                        .then(response => {

                            if (response.data.status == "success") {
                                v.saveDisable = false;

                                Swal.fire('Document has been changed!', '', 'success');


                                var index = this.items.findIndex(item => item.id == v.view_document.id);
                                this.items.splice(index, 1, response.data.document_upload);

                                this.view_document = response.data.document_upload;

                                document.getElementById('attachment_raw_file').value = null;
                                document.getElementById('attachment_fillable_copy').value = null;
                                document.getElementById('attachment_signed_copy_revision').value = null;

                                this.attachment_raw_file = '';
                                this.attachment_fillable_copy = '';
                                this.attachment_signed_copy_revision = '';
                                this.implementation_date = '';
                            } else {
                                Swal.fire('Error: Cannot changed. Please try again.', '', 'error');
                                v.saveDisable = false;
                            }
                        })
                        .catch(error => {
                            console.log(error);
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
            this.view_document = '';
            this.view_document = document;

            $('#document-view-modal').modal('show');
        },
        usersDocumentUpload() {

        },
        editDocumentUpload(document_upload) {
            this.clearFields();
            this.action = 'Edit';
            this.document_upload.id = document_upload.id;
            this.document_upload.control_code = document_upload.control_code;
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
            v.document_upload.control_code = '';
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

                    formData.append('control_code', v.document_upload.control_code ? v.document_upload.control_code : "");
                    formData.append('title', v.document_upload.title ? v.document_upload.title : "");
                    formData.append('effective_date', v.document_upload.effective_date ? v.document_upload.effective_date : "");
                    formData.append('document_category', v.document_upload.document_category ? v.document_upload.document_category.id : "");
                    formData.append('is_form', v.document_upload.is_form ? v.document_upload.is_form : "");
                    formData.append('is_procedure_link', v.document_upload.is_procedure_link ? v.document_upload.is_procedure_link : "");
                    formData.append('company', v.document_upload.company ? v.document_upload.company.id : "");
                    formData.append('department', v.document_upload.department ? v.document_upload.department.id : "");
                    formData.append('process_owner', v.document_upload.process_owner ? v.document_upload.process_owner.id : "");

                    formData.append('auto_generate_control_code', v.auto_generate_control_code ? v.auto_generate_control_code : "");

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
                            }
                            else if (response.data.status == "warning") {
                                Swal.fire({
                                    title: 'Control Code has been failed.',
                                    text: response.data.message,
                                    icon: "warning",
                                    confirmButtonText: "Ok, got it!"
                                }).then(okay => {
                                    v.saveDisable = false;
                                    v.errors = error.response.data.errors;
                                });
                            }
                            else {
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
        uploadSignedCopyRevision(e) {
            var files = e.target.files || e.dataTransfer.files;
            if (!files.length)
                return;
            this.attachment_signed_copy_revision = files[0];
        },
        setPageFilteredUser(pageNumber) {
            this.currentPageFilteredUser = pageNumber;
        },
        resetStartRowFilteredUser() {
            this.currentPageFilteredUser = 0;
        },
        showPreviousLinkFilteredUser() {
            return this.currentPageFilteredUser == 0 ? false : true;
        },
        showNextLinkFilteredUser() {
            return this.currentPageFilteredUser == (this.totalPagesFilteredUser - 1) ? false : true;
        },

        setPageAssignedUser(pageNumber) {
            this.currentPageAssignedUser = pageNumber;
        },
        resetStartRowAssignedUser() {
            this.currentPageAssignedUser = 0;
        },
        showPreviousLinkAssignedUser() {
            return this.currentPageAssignedUser == 0 ? false : true;
        },
        showNextLinkAssignedUser() {
            return this.currentPageAssignedUser == (this.totalPagesAssignedUser - 1) ? false : true;
        },
    },
    computed: {
        filteredUsers() {
            let self = this;
            return Object.values(self.filterUsers).filter(user => {
                return user.name.toLowerCase().includes(this.filter.search_user.toLowerCase())
            });
        },
        totalPagesFilteredUser() {
            return Math.ceil(Object.values(this.filteredUsers).length / Number(this.itemsPerPageFilteredUser))
        },
        filteredQueues() {
            var index = this.currentPageFilteredUser * Number(this.itemsPerPageFilteredUser);
            var queues_array = this.filteredUsers.slice(index, index + Number(this.itemsPerPageFilteredUser));

            if (this.currentPageFilteredUser >= this.totalPagesFilteredUser) {
                this.currentPageFilteredUser = this.totalPagesFilteredUser - 1
            }

            if (this.currentPageFilteredUser == -1) {
                this.currentPageFilteredUser = 0;
            }

            return queues_array;
        },
        assignedUsers() {
            let self = this;
            if (self.view_document) {
                return Object.values(self.documentUploadUsers).filter(user => {
                    return user.user_info.name.toLowerCase().includes(this.filter.search_user.toLowerCase())
                });
            } else {
                return [];
            }

        },
        totalPagesAssignedUser() {
            return Math.ceil(Object.values(this.assignedUsers).length / Number(this.itemsPerPageAssignedUser))
        },
        assignedQueues() {
            var index = this.currentPageAssignedUser * Number(this.itemsPerPageAssignedUser);
            var queues_array = this.assignedUsers.slice(index, index + Number(this.itemsPerPageAssignedUser));

            if (this.currentPageAssignedUser >= this.totalPagesAssignedUser) {
                this.currentPageAssignedUser = this.totalPagesAssignedUser - 1
            }

            if (this.currentPageAssignedUser == -1) {
                this.currentPageAssignedUser = 0;
            }

            return queues_array;
        },
    },
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