<template>
    <div class="flex flex-wrap justify-between gap-6 mx-auto pt-4">
        <div v-if="pagination.total" class="flex items-center text-sm">
            Выводится: {{ pagination.data.length }} из {{ pagination.total }}
        </div>
        <div v-if="pagination.links && pagination.links.length > 3">
            <div class="flex flex-wrap justify-end gap-1">
                <div v-for="(link, link_key) in pagination.links" :key="link_key">
                    <span v-if="link.url === null" class="block px-4 py-3 text-sm leading-4 text-gray-400 border rounded" v-html="link.label"></span>
                    <a v-else class="block px-3 py-3 text-sm leading-4 border rounded hover:bg-white focus:border-indigo-500 focus:text-indigo-500" :class="{ 'bg-white': link.active }" href="#" @click.prevent="changePage(link.url)" v-html="link.label"></a>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            pagination: Object,
        },
        methods : {
            changePage(url) {
                if(!url) {
                    return;
                }
                var page = url.replace(this.pagination.path, '')
                page = page.replace('?page=', '')
                this.$emit('paginate', page);
            }
        }
    }
</script>
