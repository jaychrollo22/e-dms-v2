<template>
    <div>
        <div class="content-wrapper">
            <div class="row justify-content-center">
                <div class="col-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Document Category</h4>

                            <div class="d-flex flex-wrap justify-content-end">
                                <button class="btn btn-primary" @click="addDocumentCategory">New</button>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <input type="text" class="form-control w-400px" @keyup="searchKeyUp"
                                            v-model="filterData.search" placeholder="Search Document Category">
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th class="pt-1">
                                                Document Category Code
                                            </th>
                                            <th class="pt-1">
                                                Document Category
                                            </th>
                                            <th class="pt-1">
                                                Tag
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
                                        <tr v-for="(category, index) in items" :key="index">
                                            <td>
                                                {{ category.code }}
                                            </td>
                                            <td>
                                                {{ category.category_description }}
                                            </td>
                                            <td>
                                                {{ category.tag_info ? category.tag_info.description : "" }}
                                            </td>
                                            <td>
                                                {{ category.status }}
                                            </td>
                                            <td>
                                                <button type="button"
                                                    class="btn btn-inverse-primary btn-rounded btn-icon"
                                                    title="Edit Category" @click="editDocumentCategory(category)">
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

        <div class="modal fade" id="document-category-modal" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="col-12 modal-title text-center">{{ action }} Document Category</h5>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">Document Category Code</label>
                                    <input type="text" class="form-control" placeholder="Code"
                                        v-model="document_category.code">
                                    <span class="text-danger"
                                        v-if="errors.category_description">{{ errors.category_description[0] }}</span>
                                </div>
                                <div class="form-group">
                                    <label for="">Document Category</label>
                                    <input type="text" class="form-control" placeholder="Document Category"
                                        v-model="document_category.category_description">
                                    <span class="text-danger"
                                        v-if="errors.category_description">{{ errors.category_description[0] }}</span>
                                </div>
                                <div class="form-group">
                                    <label for="">Tag</label>
                                    <select class="form-control" v-model="document_category.tag">
                                        <option value="">Choose tag</option>
                                        <option v-for="(tag, index) in tags" :key="index" :value="tag.id">
                                            {{ tag.description }}
                                        </option>

                                    </select>
                                    <span class="text-danger" v-if="errors.tag">{{ errors.tag[0] }}</span>
                                </div>
                                <div class="form-group">
                                    <label for="">Status</label>
                                    <select class="form-control" v-model="document_category.status">
                                        <option value="">Choose Status</option>
                                        <option value="Active">Active</option>
                                        <option value="Inactive">Inactive</option>
                                    </select>
                                    <span class="text-danger" v-if="errors.status">{{ errors.status[0] }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary btn-md" @click="saveDocumentCategory">Save</button>
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
            endpoint: '/document-categories',
            document_category: {
                id: '',
                code: '',
                category_description: '',
                tag: '',
                status: ''
            },
            action: '',
            errors: [],
            tags: [],
        }
    },
    created() {
        this.fetchList();
        this.fetchTags();
    },
    methods: {
        addDocumentCategory() {
            this.clearFields();
            this.action = 'Add';
            $('#document-category-modal').modal('show');
        },
        clearFields() {
            let v = this;
            v.errors = [];
            v.document_category.id = '';
            v.document_category.code = '';
            v.document_category.category_description = '';
            v.document_category.tag = '';
            v.document_category.status = '';
        },
        editDocumentCategory(document_category) {
            let v = this;
            v.errors = [];
            v.document_category.id = document_category.id;
            v.document_category.code = document_category.code;
            v.document_category.category_description = document_category.category_description;
            v.document_category.tag = document_category.tag;
            v.document_category.status = document_category.status;
            v.action = 'Update';
            $('#document-category-modal').modal('show');
        },
        saveDocumentCategory() {
            let v = this;
            Swal.fire({
                title: 'Are you sure you want to save this Document Category?',
                icon: 'question',
                showDenyButton: true,
                confirmButtonText: `Yes`,
                denyButtonText: `No`,
            }).then((result) => {
                if (result.isConfirmed) {
                    let formData = new FormData();
                    var postURL = `/document-categories/store`;
                    if (v.action == "Update") {
                        formData.append('id', v.document_category.id ? v.document_category.id : "");
                        postURL = `/document-categories/update`;
                    }
                    formData.append('code', v.document_category.code ? v.document_category.code : "");
                    formData.append('category_description', v.document_category.category_description ? v.document_category.category_description : "");
                    formData.append('tag', v.document_category.tag ? v.document_category.tag : "");
                    formData.append('status', v.document_category.status ? v.document_category.status : "");

                    axios.post(postURL, formData)
                        .then(response => {
                            if (response.data.status == "success") {
                                if (v.action == 'Update') {
                                    var index = this.items.findIndex(item => item.id == v.document_category.id);
                                    this.items.splice(index, 1, response.data.document_category);
                                } else {
                                    this.fetchList();
                                }

                                Swal.fire('Document Category has been saved!', '', 'success');

                                $('#document-category-modal').modal('hide');

                                this.clearFields();

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

        fetchTags() {
            let v = this;
            v.tags = [];
            axios.get('/tag-options')
                .then(response => {
                    v.tags = response.data;
                })
                .catch(error => {
                    v.errors = error.response.data.error;
                })
        }
    },
}
</script>

<style lang="scss" scoped>

</style>