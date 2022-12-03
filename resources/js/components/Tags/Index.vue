<template>
    <div>
        <div class="content-wrapper">
            <div class="row justify-content-center">
                <div class="col-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Tags</h4>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <input type="text" class="form-control w-400px" @keyup="searchKeyUp"
                                            v-model="filterData.search" placeholder="Search Tags">
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th class="pt-1">
                                                Tags
                                            </th>
                                            <th class="pt-1">
                                                Action
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(tag, index) in items" :key="index">
                                            <td>
                                                {{ tag.description }}
                                            </td>
                                            <td>
                                                <button type="button"
                                                    class="btn btn-inverse-primary btn-rounded btn-icon"
                                                    title="Edit Category" @click="editTag(tag)">
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

        <div class="modal fade" id="tag-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true" data-backdrop="static">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="col-12 modal-title text-center">{{ action }} Tag</h5>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">Tag</label>
                                    <input type="text" class="form-control" placeholder="Tag" v-model="tag.description">
                                    <span class="text-danger"
                                        v-if="errors.description">{{ errors.description[0] }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary btn-md" @click="saveTag">Save</button>
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
            endpoint: '/tags',
            tag: {
                id: '',
                description: '',
            },
            action: '',
            errors: [],
        }
    },
    created() {
        this.fetchList();
    },
    methods: {
        editTag(tag) {
            let v = this;
            v.errors = [];
            v.tag.id = tag.id;
            v.tag.description = tag.description;
            v.action = 'Update';
            $('#tag-modal').modal('show');
        },
        saveTag() {
            let v = this;
            Swal.fire({
                title: 'Are you sure you want to save this Tag?',
                icon: 'question',
                showDenyButton: true,
                confirmButtonText: `Yes`,
                denyButtonText: `No`,
            }).then((result) => {
                if (result.isConfirmed) {
                    let formData = new FormData();
                    var postURL = `/tags/store`;
                    if (v.action == "Update") {
                        formData.append('id', v.tag.id ? v.tag.id : "");
                        postURL = `/tags/update`;
                    }
                    formData.append('description', v.tag.description ? v.tag.description : "");

                    axios.post(postURL, formData)
                        .then(response => {
                            if (response.data.status == "success") {
                                if (v.action == 'Update') {
                                    var index = this.items.findIndex(item => item.id == v.tag.id);
                                    this.items.splice(index, 1, response.data.tag);
                                } else {
                                    this.fetchList();
                                }

                                Swal.fire('Document Category has been saved!', '', 'success');

                                $('#tag-modal').modal('hide');

                                v.tag.id = '';
                                v.tag.description = '';
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