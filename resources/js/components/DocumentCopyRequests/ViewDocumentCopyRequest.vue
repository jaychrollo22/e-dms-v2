<template>
    <div>
        <div class="content-wrapper">
            <div class="row justify-content-center">
                <div class="col-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">View Document Copy Requests</h4>
                            <small class="text-danger">Valid Until : {{ document_copy_request.expiration_date }}</small>
                            <vue-pdf-embed v-if="document_copy_request"
                                :source="'/document-uploads-view-signed-copy?id=' + document_copy_request.document_upload_info.id"
                                class="src-pdf mt-2" />

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import VuePdfEmbed from 'vue-pdf-embed/dist/vue2-pdf-embed'
export default {
    props: [
        'propRequest'
    ],
    components: {
        VuePdfEmbed,
    },
    created() {
        this.document_copy_request = Object.assign({}, this.propRequest);
    },
    data() {
        return {
            document_copy_request: ''
        }
    },
}
</script>

<style lang="scss" scoped>

</style>