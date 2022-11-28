<template>
    <div>
        <div class="content-wrapper">
            <div class="row justify-content-center">
                <div class="col-lg-4 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Application</h4>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row justify-content-center mb-2 mt-2" v-if="app_logo_src">
                                        <img :src="app_logo_src" style="width:200px;height:auto;border:2px dotted ;">
                                    </div>
                                    <div class="row justify-content-center mb-2 mt-2" v-else-if="settings.app_logo">
                                        <img :src="'img/' + settings.app_logo"
                                            style="width:200px;height:auto;border:2px dotted ;">
                                    </div>

                                    <div class="form-group">
                                        <label>App Logo <span class="text-danger">*</span></label>
                                        <input v-on:change="appLogoHandleFileUpload()" type="file" accept="image/png"
                                            id="app_logo" ref="file" class="form-control">
                                        <span class="text-danger" v-if="errors.app_logo">{{ errors.app_logo[0] }}</span>
                                    </div>


                                    <div class="row justify-content-center mb-2 mt-2" v-if="app_icon_src">
                                        <img :src="app_icon_src" style="width:200px;height:auto;border:2px dotted ;">
                                    </div>
                                    <div class="row justify-content-center mb-2 mt-2" v-else-if="settings.app_icon">
                                        <img :src="'img/' + settings.app_icon"
                                            style="width:200px;height:auto;border:2px dotted ;">
                                    </div>

                                    <div class="form-group">
                                        <label>App Icon <span class="text-danger">*</span></label>
                                        <input v-on:change="appIconHandleFileUpload()" type="file" accept="image/png"
                                            id="app_icon" ref="file" class="form-control">
                                        <span class="text-danger" v-if="errors.app_icon">{{ errors.app_icon[0] }}</span>
                                    </div>

                                </div>
                            </div>

                        </div>
                        <div class="card-footer">
                            <button @click="submit" class="btn btn-primary me-2">Submit</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
import Swal from 'sweetalert2'
export default {
    data() {
        return {
            settings: '',
            errors: [],
            app_logo: '',
            app_logo_src: '',
            app_icon: '',
            app_icon_src: '',
        }
    },
    created() {
        this.getSettings();
    },
    methods: {
        submit() {
            let v = this;
            v.errors = [];
            v.saveDisable = true;
            if (v.app_logo_src || v.app_icon_src) {
                Swal.fire({
                    text: "Are you sure you want to save?",
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
                        if (v.app_logo_src) {
                            formData.append('app_logo', v.app_logo);
                        }
                        if (v.app_icon_src) {
                            formData.append('app_icon', v.app_icon);
                        }

                        axios.post(`settings-store`, formData)
                            .then(response => {
                                if (response.data.status == "success") {
                                    Swal.fire({
                                        text: 'Settings has been changed. Thank you.',
                                        icon: 'success',
                                        confirmButtonText: 'Okay',
                                        timer: 10000
                                    }).then(okay => {
                                        if (okay) {
                                            location.reload();
                                        }
                                    });
                                }
                            })
                            .catch(error => {

                            })
                    }
                })
            }
        },
        appLogoHandleFileUpload() {
            var photo = document.getElementById("app_logo");
            this.app_logo_src = window.URL.createObjectURL(photo.files[0]);
            this.app_logo = photo.files[0];
        },
        appIconHandleFileUpload() {
            var photo = document.getElementById("app_icon");
            this.app_icon_src = window.URL.createObjectURL(photo.files[0]);
            this.app_icon = photo.files[0];
        },
        getSettings() {
            let v = this;
            v.settings = '';
            axios.get('/settings-data')
                .then(response => {
                    if (response.data) {
                        v.settings = response.data;
                    }

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