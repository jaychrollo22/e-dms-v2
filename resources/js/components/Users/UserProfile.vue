<template>
    <div>
        <div class="content-wrapper">
            <div class="row">
                <div class="col-md-4 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="row mb-2 mt-2">
                                <img v-if="user_photo_src" :src="user_photo_src" class="img-lg rounded-circle mb-2">
                                <h4>{{ user_profile.name }}</h4>
                                <p class="text-muted mb-0">{{ 'Email : ' + user_profile.email }}</p>
                                <p class="text-muted mb-0">
                                    {{ user_profile.company ? 'Company : ' + user_profile.company.company_info.company_name : "" }}
                                </p>
                                <p class="text-muted mb-0">
                                    {{ user_profile.department ? 'Department : ' + user_profile.department.department_info.department : "" }}
                                </p>
                                <p class="text-muted mb-0">
                                    {{ user_profile.immediate_head ? 'Immediate Head : ' + user_profile.immediate_head.user_info.name : "" }}
                                </p>
                                <p class="text-muted mb-0">
                                    {{ user_profile.roles ? 'Roles : ' + displayRoles(user_profile.roles) : "" }}
                                </p>
                            </div>

                            <button class="btn btn-sm btn-primary" @click="changePassword">Change Password</button>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="change-password-modal" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="col-12 modal-title text-center">Change Password</h5>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">Password</label>
                                    <input type="password" class="form-control" placeholder="New Password"
                                        v-model="change_password.new_password">
                                    <span class="text-danger"
                                        v-if="errors.new_password">{{ errors.new_password[0] }}</span>
                                </div>
                                <div class="form-group">
                                    <label for="">Confirm Password</label>
                                    <input type="password" class="form-control" placeholder="Confirm Password"
                                        v-model="change_password.confirm_password">
                                    <span class="text-danger"
                                        v-if="errors.confirm_password">{{ errors.confirm_password[0] }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary btn-md" @click="saveChangePassword">Save</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>

import Swal from 'sweetalert2'
export default {
    props: ['propProfile'],
    data() {
        return {
            user_profile: '',
            user_photo_src: '',
            change_password: {
                id: '',
                new_password: '',
                confirm_password: '',
            },
            errors: []
        }
    },
    created() {
        this.user_profile = JSON.parse(this.propProfile);
    },
    methods: {
        saveChangePassword() {
            let v = this;
            Swal.fire({
                title: 'Are you sure you want to change password?',
                icon: 'question',
                showDenyButton: true,
                confirmButtonText: `Yes`,
                denyButtonText: `No`,
            }).then((result) => {
                if (result.isConfirmed) {
                    let formData = new FormData();
                    var postURL = `/users/change-password`;
                    formData.append('id', v.change_password.id ? v.change_password.id : "");
                    formData.append('new_password', v.change_password.new_password ? v.change_password.new_password : "");
                    formData.append('new_password_confirmation', v.change_password.confirm_password ? v.change_password.confirm_password : "");
                    axios.post(postURL, formData)
                        .then(response => {
                            if (response.data.status == "success") {
                                Swal.fire('Password has been changed!', '', 'success');
                                $('#change-password-modal').modal('hide');
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
        changePassword() {
            this.change_password.id = this.user_profile.id;
            this.change_password.new_password = "";
            this.change_password.confirm_password = "";
            $('#change-password-modal').modal('show');
        },
        displayRoles(roles) {
            var roles_arr = [];
            if (roles.length > 0) {
                roles.forEach(e => {
                    var role = JSON.parse(e.roles);
                    roles_arr.push(role.name);
                });
                return roles_arr.join(', ');
            }
        },
    },
}   
</script>

<style lang="scss" scoped>

</style>