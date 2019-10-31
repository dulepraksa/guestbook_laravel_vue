<template>
<div>
    <ul class="pagination">
        <li class="pagination-item" :class="{ 'disabled':meta.current_page === 1 }">
            <a href="" @click.prevent ="switched(meta.current_page - 1)">
                <span>&laquo;</span>
            </a>
        </li>
        <li class="pagination-item" :class="{ 'active' :meta.current_page === x }" v-for="x in meta.last_page" :key="x">
            <a  :class="{ 'active' :meta.current_page === x }" href="" @click.prevent="switched(x)">{{ x }}</a>
        </li>
        <li class="pagination-item" :class="{ 'disabled': meta.current_page === meta.last_page}">
            <a href=""  @click.prevent ="switched(meta.current_page + 1)">
                <span>&raquo;</span>
            </a>
        </li>
    </ul>
</div>
</template>
<script>
export default {
    props: [
        'meta'
    ],
    methods: {
        switched(page) {
            if (this.pageIsOutOfBounds(page)) {
                return
            }
            this.$emit('pagination:switched', page)

            this.$router.replace({
                query: {
                    page
                }
            })
        },
        pageIsOutOfBounds(page) {
            return page <= 0 || page > this.meta.last_page
        }
    },
}
</script>

