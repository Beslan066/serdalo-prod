<template>
    <div>
        <h1 class="px-8 font-semibold text-xl text-gray-800 leading-tight">
            Статистика / Материалы
        </h1>
        <div class="px-8 py-8">
            <div class="mb-4 bg-white rounded-md shadow p-3">
                <form @submit.prevent="setData(1)">
                    <div class="grid grid-cols-4 gap-2">
                        <text-input v-model="filter.start_date" :error="filterErrors.start_date" id="start_date" label="Дата с" type="date" />
                        <text-input v-model="filter.end_date" :error="filterErrors.end_date" id="end_date" label="по" type="date" />
                        <div class="pb-4 w-full">
                            <label class="mb-2 block">Язык перевода:</label>
                            <select v-model="filter.lang" :class="{ 'input-error': filterErrors.lang }" class="block w-full p-2 border rounded border-gray-400">
                                <option :value="null" />
                                <option :value="'rus'">Русский язык</option>
                                <option :value="'inh'">Ингушский язык</option>
                            </select>
                            <div v-if="filterErrors.lang" class="text-red-400">{{ filterErrors.lang | displayError }}</div>
                        </div>
                    </div>
                    <button-primary class="mr-2" type="submit" :isLoading="isLoading">Применить</button-primary>
                    <button-secondary @click="resetFilterData">Сбросить</button-secondary>
                </form>
            </div>
            <div v-for="author in authors.data" :key="author.id">
                <h2 class="text-xl mt-4">{{ author.title }}</h2>
                <h2 class="mb-1">Общее кол-во строк: {{ author.total_string_count }}</h2>
                <div v-if="author.materials.length > 0" class="bg-white rounded-md shadow overflow-x-auto">
                    <table class="table-fixed w-full">
                        <tr class="text-left font-bold">
                            <th class="pl-6 pr-3 py-3 w-52">Дата создания</th>
                            <th class="px-3 py-3 max-w-xs">Заголовок</th>
                            <th class="pl-3 pr-6 py-3">Кол-во строк</th>
                        </tr>
                        <tr v-for="material in author.materials" :key="material.id" class="hover:bg-gray-100 focus-within:bg-gray-100">
                            <td class="border-t pl-6 pr-3 py-3">
                                {{ material.created_at | displayDateTime }}
                            </td>
                            <td class="border-t px-3 py-3">
                                <router-link :to="'/admin/materials/' + material.id + '/edit'" target="_blank" class="text-sm text-blue-400">{{ material.title }}</router-link>
                            </td>
                            <td class="border-t pl-3 pr-6 py-3">
                                {{ material.string_count }}
                            </td>
                        </tr>
                    </table>
                </div>
                <div v-else>
                    <div class="bg-white border-t px-4 py-3">Нет записей за период.</div>
                </div>
            </div>
            <div v-if="empty_data">
                <div class="border-t px-6 py-4">No authors found.</div>
            </div>
            <pagination v-if="authors.links" :pagination="authors" @paginate="setDataPagination" />
        </div>
    </div>
</template>

<script>
    import ButtonLinkPrimary from '@/components/Admin/ButtonLinkPrimary'
    import Pagination from '@/components/Admin/Pagination'
    import ButtonActionDelete from '@/components/Admin/ButtonActionDelete'
    import TextInput from '@/components/Admin/TextInput'
    import SelectInput from '@/components/Admin/SelectInput'
    import ButtonSecondary from '@/components/Admin/ButtonSecondary'
    import ButtonPrimary from '@/components/Admin/ButtonPrimary'
    import MultiselectInput from '@/components/Admin/MultiselectInput.vue'
    import ButtonDefault from '@/components/Admin/ButtonDefault'

    export default {
        components: {
            ButtonLinkPrimary,
            Pagination,
            ButtonActionDelete,
            TextInput,
            SelectInput,
            ButtonSecondary,
            ButtonPrimary,
            MultiselectInput,
            ButtonDefault,
        },
        data: () => ({
            isLoading: true,
            isFilterLoading: false,
            current_page: null,
            authors: [],
            filter: {
                start_date: null,
                end_date: null,
                lang: null,
            },
            filterErrors: {
                start_date: null,
                end_date: null,
                lang: null,
            },
            empty_data: false,
        }),
        mounted() {
            this.setData()
        },
        methods: {
            resetFilterData() {
                this.filter = {
                    title: null,
                    content: null,
                }
                this.setData(1)
            },
            setData(page = '') {
                this.isLoading = true
                var queryParams = {}
                if(page) {
                    queryParams.page = page
                } else if(this.current_page > 0) {
                    queryParams.page = this.current_page
                }
                for(var filter_field in this.filter) {
                    if(this.filter[filter_field]) {
                        queryParams[filter_field] = this.filter[filter_field]
                    }
                }
                axios.get('/api/admin/statistics/materials', {params: queryParams})
                .then(res => {
                    if(res.data.authors.data.length === 0) {
                        this.empty_data = true
                    }
                    this.current_page = res.data.current_page
                    this.authors = res.data.authors
                })
                .finally(() => this.isLoading = false)
            },
            setDataPagination(page) {
                this.setData(page)
            },
            deleted(id) {
                this.setData()
            },
        }
    }
</script>
