<template>
    <div>
        <div class="content-wrapper">
            <div class="row justify-content-center">
                <div class="col-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Create Request</h4>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Title</label>
                                        <input type="text" class="form-control" name="Title" placeholder="Title"
                                            v-model="document_request.title">
                                        <span class="text-danger" v-if="errors.title">{{ errors.title[0] }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Proposed Effective Date</label>
                                        <input type="date" class="form-control" name="Propose Effective Date"
                                            v-model="document_request.proposed_effective_date">
                                        <span class="text-danger"
                                            v-if="errors.proposed_effective_date">{{ errors.proposed_effective_date[0] }}</span>
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
                                                            class="form-check-input" id="New" value="New">
                                                        New
                                                        <i class="input-helper"></i></label>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                        <input v-model="document_request.type_of_request" type="radio"
                                                            class="form-check-input" id="Revision" value="Revision">
                                                        Revision
                                                        <i class="input-helper"></i></label>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                        <input v-model="document_request.type_of_request" type="radio"
                                                            class="form-check-input" id="Discontinuance"
                                                            value="Discontinuance">
                                                        Discontinuance
                                                        <i class="input-helper"></i></label>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                        <input v-model="document_request.type_of_request" type="radio"
                                                            class="form-check-input" id="Obsolete" value="Obsolete">
                                                        Obsolete
                                                        <i class="input-helper"></i></label>
                                                </div>
                                            </div>
                                        </div>
                                        <span class="text-danger"
                                            v-if="errors.type_of_request">{{ errors.type_of_request[0] }}</span>
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
                                                            value="Policy">
                                                        Policy
                                                        <i class="input-helper"></i></label>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                        <input v-model="document_request.type_of_documented_information"
                                                            type="radio" class="form-check-input" id="Procedure"
                                                            value="Procedure">
                                                        Procedure
                                                        <i class="input-helper"></i></label>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                        <input v-model="document_request.type_of_documented_information"
                                                            type="radio" class="form-check-input" id="Form"
                                                            value="Form">
                                                        Form
                                                        <i class="input-helper"></i></label>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                        <input v-model="document_request.type_of_documented_information"
                                                            type="radio" class="form-check-input" id="Others"
                                                            value="Others">
                                                        Others
                                                        <i class="input-helper"></i></label>
                                                </div>
                                            </div>
                                            <div class="col-sm-12"
                                                v-if="document_request.type_of_documented_information == 'Others'">
                                                <div class="form-group"
                                                    v-if="document_request.type_of_documented_information == 'Others'">
                                                    <input type="text" class="form-control" placeholder="Others Specify"
                                                        v-model="document_request.type_of_documented_information_others">
                                                </div>
                                            </div>
                                        </div>
                                        <span class="text-danger"
                                            v-if="errors.type_of_documented_information">{{ errors.type_of_documented_information[0] }}</span>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="">Description & Purpose of Documentation Request:</label>
                                        <textarea v-model="document_request.description_purpose_of_documentation"
                                            class="form-control" cols="30" rows="5"
                                            placeholder="Description & Purpose of Documentation Request"></textarea>
                                        <span class="text-danger"
                                            v-if="errors.description_purpose_of_documentation">{{ errors.description_purpose_of_documentation[0] }}</span>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group"
                                        v-if="document_request.type_of_request == 'Revision' || document_request.type_of_request == 'Discontinuance' || document_request.type_of_request == 'Obsolete'">
                                        <label for="">Select Document</label>
                                        <multiselect v-model="document_request.document_upload_id"
                                            :options="document_uploads" placeholder="Select Document" label="title"
                                            track-by="id">
                                        </multiselect>
                                        <span class="text-danger"
                                            v-if="errors.attachment_file">{{ errors.attachment_file[0] }}</span>
                                    </div>
                                    <div class="form-group"
                                        v-if="document_request.type_of_request == 'New' || document_request.type_of_request == 'Revision'">
                                        <label for="">Attachment - Draft</label>
                                        <input @change="uploadAttachment" name="attachment_file" type="file"
                                            accept="application/*" id="attachment_file" class="form-control">
                                        <span class="text-danger"
                                            v-if="errors.attachment_file">{{ errors.attachment_file[0] }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button class="btn btn-primary btn-md" @click="saveDocumentRequest"
                                :disabled="saveDisable">{{ saveDisable ? 'Saving...' : 'Save' }}</button>
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
import Swal from 'sweetalert2'
import Multiselect from 'vue-multiselect'
export default {
    components: {
        Multiselect
    },
    data() {
        return {
            document_request: {
                title: '',
                proposed_effective_date: '',
                type_of_request: '',
                type_of_documented_information: '',
                type_of_documented_information_others: '',
                description_purpose_of_documentation: '',
            },
            errors: [],
            saveDisable: false,

            attachment_file: '',

            document_uploads: [],
        }
    },
    created() {
        this.getDocumentUploads();
    },
    methods: {
        getDocumentUploads() {
            let v = this;
            v.document_uploads = [];
            axios.get('/document-uploads-request-options')
                .then(response => {
                    v.document_uploads = response.data;
                })
                .catch(error => {
                    v.errors = error.response.data.error;
                })
        },
        clearFields() {
            this.document_request.title = '';
            this.document_request.proposed_effective_date = '';
            this.document_request.type_of_request = '';
            this.document_request.type_of_documented_information = '';
            this.document_request.type_of_documented_information_others = '';
            this.document_request.description_purpose_of_documentation = '';
            this.attachment_file = '';
        },
        uploadAttachment(e) {
            var files = e.target.files || e.dataTransfer.files;
            if (!files.length)
                return;
            this.attachment_file = files[0];
        },
        saveDocumentRequest() {
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

                    formData.append('title', v.document_request.title ? v.document_request.title : "");
                    formData.append('proposed_effective_date', v.document_request.proposed_effective_date ? v.document_request.proposed_effective_date : "");
                    formData.append('type_of_request', v.document_request.type_of_request ? v.document_request.type_of_request : "");
                    formData.append('type_of_documented_information', v.document_request.type_of_documented_information ? v.document_request.type_of_documented_information : "");
                    formData.append('type_of_documented_information_others', v.document_request.type_of_documented_information_others ? v.document_request.type_of_documented_information_others : "");
                    formData.append('description_purpose_of_documentation', v.document_request.description_purpose_of_documentation ? v.document_request.description_purpose_of_documentation : "");
                    formData.append('document_upload_id', v.document_request.document_upload_id ? v.document_request.document_upload_id.id : "");

                    formData.append('attachment_file', v.attachment_file ? v.attachment_file : "");

                    axios.post(`/document-requests/store`, formData)
                        .then(response => {
                            if (response.data.status == "success") {
                                Swal.fire('Document request has been saved!', '', 'success').then(function () {
                                    window.location.href = '/user-document-requests';
                                });
                            } else {
                                Swal.fire('Error: Cannot saved. Please try again.', '', 'error');
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
    },
}
</script>

<style lang="scss" scoped>

</style>