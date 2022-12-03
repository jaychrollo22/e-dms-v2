<template>
    <div class="d-flex justify-content-between align-items-center flex-wrap" v-if="hasPagination">
        <div class="d-flex align-items-center py-3">

            <select class="font-weight-bold mr-4 border-0 bg-light" @change="changeLimit($event)" style="width: 75px;">
                <option value="5" :selected="pagination.per_page === '5'">5</option>
                <option value="10" :selected="pagination.per_page === '10'">10</option>
                <option value="20" :selected="pagination.per_page === '20'">20</option>
                <option value="50" :selected="pagination.per_page === '50'">50</option>
                <option value="100" :selected="pagination.per_page === '100'">100</option>
            </select>

            <span class="text-muted">Displaying {{ getItemCount() }} of {{ pagination.total }} records</span>
        </div>

        <nav>
            <ul class="pagination d-flex flex-wrap justify-content-end pagination-rounded-flat pagination-primary">
                <li class="page-item"><button class="page-link" @click="goToPage(pagination.prev_page_url)"><i
                            class="ti-angle-left"></i></button></li>

                <li v-for="(range, index) in pagination.range" :key="index" @click="goToPage(range)"
                    :class="range == pagination.current_page ? 'page-item active' : 'page-item'">
                    <button class="page-link">{{ range }}</button>
                </li>

                <li class="page-item"><button class="page-link" @click="goToPage(pagination.next_page_url)"><i
                            class="ti-angle-right"></i></button></li>
            </ul>
        </nav>
    </div>
</template>

<script>

export default {

    props: ['pagination'],

    methods: {
        goToPage(page) {
            if (typeof page === 'string') {
                page = page.split('page=').slice(1).join("page=")
            }
            this.$emit('updatePage', page);

            this.pagination.current_page = page;
        },

        changeLimit(event) {
            this.$emit('doChangeLimit', event.target.value);
        },

        getItemCount() {

            let total = this.pagination.per_page;

            if (this.pagination.per_page >= this.pagination.total) {
                total = this.pagination.total;
            }

            return total;
        }
    },

    computed: {
        hasPagination: function () {
            return !_.isEmpty(this.pagination);
        }
    }
}

</script>
