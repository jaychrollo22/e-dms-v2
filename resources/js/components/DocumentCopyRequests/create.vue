<template>
    <div>
        <div class="content-wrapper">
            <div class="row justify-content-center">
                <div class="col-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Create Copy Request</h4>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="">Select Document</label>
                                        <multiselect v-model="document_copy_request.document_upload_id"
                                            :options="document_uploads" placeholder="Select Document" label="title"
                                            track-by="id">
                                        </multiselect>
                                        <span class="text-danger"
                                            v-if="errors.document_upload_id">{{ errors.document_upload_id[0] }}</span>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <textarea v-model="document_copy_request.remarks" class="form-control" cols="30"
                                            rows="5" placeholder="Remarks"></textarea>
                                        <span class="text-danger" v-if="errors.remarks">{{ errors.remarks[0] }}</span>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="">File Copy Type</label>
                                        <div class="form-group row">
                                            <div class="col-sm-3">
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                        <input v-model="document_copy_request.file_copy_type"
                                                            type="radio" class="form-check-input" id="SoftCopy"
                                                            value="Soft Copy">
                                                        Soft Copy
                                                        <i class="input-helper"></i></label>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                        <input v-model="document_copy_request.file_copy_type"
                                                            type="radio" class="form-check-input" id="HardCopy"
                                                            value="Hard Copy">
                                                        Hard Copy
                                                        <i class="input-helper"></i></label>
                                                </div>
                                            </div>
                                        </div>
                                        <span class="text-danger"
                                            v-if="errors.file_copy_type">{{ errors.file_copy_type[0] }}</span>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="card-footer">
                            <button class="btn btn-primary btn-md" @click="saveDocumentCopyRequest"
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
            document_copy_request: {
                remarks: '',
                document_upload_id: '',
                file_copy_type: '',
            },
            document_uploads: [],
            errors: [],
            saveDisable: false,
        }
    },
    created() {
        this.getDocumentUploads();
    },
    methods: {
        getDocumentUploads() {
            let v = this;
            v.document_uploads = [];
            axios.get('/document-uploads-request-copy-options')
                .then(response => {
                    v.document_uploads = response.data;
                })
                .catch(error => {
                    v.errors = error.response.data.error;
                })
        },
        clearFields() {
            this.document_copy_request.remarks = '';
            this.document_copy_request.document_upload_id = '';
            this.document_copy_request.file_copy_type = '';
        },
        saveDocumentCopyRequest() {
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

                    formData.append('remarks', v.document_copy_request.remarks ? v.document_copy_request.remarks : "");
                    formData.append('document_upload_id', v.document_copy_request.document_upload_id ? v.document_copy_request.document_upload_id.id : "");
                    formData.append('file_copy_type', v.document_copy_request.file_copy_type ? v.document_copy_request.file_copy_type : "");

                    axios.post(`/document-copy-requests/store`, formData)
                        .then(response => {
                            if (response.data.status == "success") {
                                Swal.fire('Document copy request has been saved!', '', 'success').then(function () {
                                    window.location.href = '/user-document-copy-requests';
                                });
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
    },
}
</script>

<style lang="scss" scoped>

</style>