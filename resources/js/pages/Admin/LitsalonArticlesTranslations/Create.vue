<template>
    <div>
        <h1 class="px-8 font-semibold text-xl text-gray-800 leading-tight">
            Литературный салон / Перевод / Создание
        </h1>
        <div class="px-8 py-8">
            <div v-if="isloading">Loading</div>
            <div v-else class="flex gap-6">
                <div class="w-full max-w-3xl bg-gray-100 rounded-md">
                    <div class="p-6">
                        <text-input disabled v-model="parentForm.title" label="Заголовок" />
                        <text-input disabled v-model="parentForm.subtitle" label="Подзаголовок(должность)" />
                        <text-input disabled v-model="parentForm.dates" label="Года" />
                        <textarea-input disabled v-model="parentForm.short_description" label="Короткое описание" />
                        <tiptap :editable="false" v-model="parentForm.description" label="Описание" />
                        <tiptap :editable="false" v-model="parentForm.biography" label="Биография" />
                        <tiptap :editable="false" v-model="parentForm.about_creativity" label="О творчестве" />
                    </div>
                    <div class="pb-4 w-full">
                        <h3 class="px-6 pb-2 font-semibold text-lg text-gray-800 leading-tight">
                            Публикации:
                        </h3>
                        <div class="mb-6 p-6 bg-gray-200">
                            <div
                                v-for="(publication, publicationIndex) in parentForm.publications"
                                :key="publicationIndex"
                                class="mb-3 bg-gray-100 border border-gray-300 rounded-md"
                            >
                                <div class="flex justify-between items-center p-3 rounded-t-md border-b border-gray-300 bg-white">
                                    <div>
                                        <span class="font-semibold mr-6"># {{ publicationIndex + 1 }}</span>
                                        <span v-if="publication.created_at">{{ publication.created_at | displayDateTime }}</span>
                                    </div>
                                    <div>
                                    </div>
                                </div>
                                <div class="p-3">
                                    <div>
                                        <text-input disabled v-model="parentForm.publications[publicationIndex].title" label="Заголовок" />
                                    </div>
                                    <div>
                                        <text-input disabled v-model="parentForm.publications[publicationIndex].subtitle" label="Подзаголовок" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="pb-4 w-full">
                        <h3 class="px-6 pb-2 font-semibold text-lg text-gray-800 leading-tight">
                            Награды:
                        </h3>
                        <div class="mb-6 p-6 bg-gray-200">
                            <div
                                v-for="(award, awardIndex) in parentForm.awards"
                                :key="awardIndex"
                                class="mb-3 bg-gray-100 border border-gray-300 rounded-md"
                            >
                                <div class="flex justify-between items-center p-3 rounded-t-md border-b border-gray-300 bg-white">
                                    <div>
                                        <span class="font-semibold mr-6"># {{ awardIndex + 1 }}</span>
                                        <span v-if="award.created_at">{{ award.created_at | displayDateTime }}</span>
                                    </div>
                                    <div>
                                    </div>
                                </div>
                                <div class="p-3">
                                    <div>
                                        <text-input disabled v-model="parentForm.awards[awardIndex].title" label="Заголовок" />
                                    </div>
                                    <div>
                                        <text-input disabled v-model="parentForm.awards[awardIndex].subtitle" label="Подзаголовок" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="w-full max-w-3xl bg-white rounded-md shadow">
                    <form @submit.prevent="store">
                        <div class="p-6">
                            <text-input v-model="form.title" :error="errors.title" id="title" label="Заголовок" />
                            <text-input v-model="form.subtitle" :error="errors.subtitle" id="subtitle" label="Подзаголовок(должность)" />
                            <text-input v-model="form.dates" :error="errors.dates" id="dates" label="Года" />
                            <textarea-input v-model="form.short_description" :error="errors.short_description" id="short_description" label="Короткое описание" />
                            <tiptap v-model="form.description" :error="errors.description" id="description" label="Описание" />
                            <tiptap v-model="form.biography" :error="errors.biography" id="biography" label="Биография" />
                            <tiptap v-model="form.about_creativity" :error="errors.about_creativity" id="about_creativity" label="О творчестве" />
                        </div>
                        <div class="pb-4 w-full">
                            <h3 class="px-6 pb-2 font-semibold text-lg text-gray-800 leading-tight">
                                Публикации:
                            </h3>
                            <div class="mb-6 p-6 bg-gray-200">
                                <div
                                    v-for="(publication, publicationIndex) in form.publications"
                                    :key="publicationIndex"
                                    class="mb-3 bg-gray-100 border border-gray-300 rounded-md"
                                >
                                    <div class="flex justify-between items-center p-3 rounded-t-md border-b border-gray-300 bg-white">
                                        <div>
                                            <span class="font-semibold mr-6"># {{ publicationIndex + 1 }}</span>
                                            <span v-if="publication.created_at">{{ publication.created_at | displayDateTime }}</span>
                                        </div>
                                        <div>
                                        </div>
                                    </div>
                                    <div class="p-3">
                                        <div>
                                            <text-input v-model="form.publications[publicationIndex].title" label="Заголовок" />
                                        </div>
                                        <div>
                                            <text-input v-model="form.publications[publicationIndex].subtitle" label="Год" />
                                        </div>
                                        <div>
                                            <text-input v-model="form.publications[publicationIndex].description" label="Описание" />
                                        </div>
                                        <div>
                                            <tiptap v-model="form.publications[publicationIndex].text_body" :error="errors.text_body" id="text_body" label="Текст произведения" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="pb-4 w-full">
                            <h3 class="px-6 pb-2 font-semibold text-lg text-gray-800 leading-tight">
                                Награды:
                            </h3>
                            <div class="mb-6 p-6 bg-gray-200">
                                <div
                                    v-for="(award, awardIndex) in form.awards"
                                    :key="awardIndex"
                                    class="mb-3 bg-gray-100 border border-gray-300 rounded-md"
                                >
                                    <div class="flex justify-between items-center p-3 rounded-t-md border-b border-gray-300 bg-white">
                                        <div>
                                            <span class="font-semibold mr-6"># {{ awardIndex + 1 }}</span>
                                            <span v-if="award.created_at">{{ award.created_at | displayDateTime }}</span>
                                        </div>
                                        <div>
                                        </div>
                                    </div>
                                    <div class="p-3">
                                        <div>
                                            <text-input v-model="form.awards[awardIndex].title" label="Заголовок" />
                                        </div>
                                        <div>
                                            <text-input v-model="form.awards[awardIndex].subtitle" label="Подзаголовок" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="p-6">
                            <button-primary type="submit" :isLoading="isStoreloading">Сохранить</button-primary>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { eventBus } from '@/app'

    import TextInput from '@/components/Admin/TextInput'
    import TextareaInput from '@/components/Admin/TextareaInput'
    import SelectInput from '@/components/Admin/SelectInput'
    import Tiptap from '@/components/Admin/Tiptap.vue'
    import ButtonSecondary from '@/components/Admin/ButtonSecondary'
    import ButtonPrimary from '@/components/Admin/ButtonPrimary'

    export default {
        components: {
            TextInput,
            TextareaInput,
            SelectInput,
            Tiptap,
            ButtonSecondary,
            ButtonPrimary,
        },
        data: () => ({
            isloading: true,
            isStoreloading: false,
            form: {
                title: null,
                subtitle: null,
                dates: null,
                short_description: null,
                description: null,
                biography: null,
                about_creativity: null,
                publications: [],
                awards: [],
            },
            parentForm: {
                title: null,
                subtitle: null,
                dates: null,
                short_description: null,
                description: null,
                biography: null,
                about_creativity: null,
                publications: [],
                awards: [],
            },
            errors: {},
        }),
        mounted() {
            this.setData()
        },
        computed: {

        },
        methods: {
            setData() {
                axios.get('/api/admin/litsalon_articles/' + this.$route.params.litsalon_article_id + '/litsalon_articles_translations/create')
                .then(res => {
                    this.parentForm.title = res.data.litsalon_article.title
                    this.parentForm.subtitle = res.data.litsalon_article.subtitle
                    this.parentForm.dates = res.data.litsalon_article.dates
                    this.parentForm.short_description = res.data.litsalon_article.short_description
                    this.parentForm.description = res.data.litsalon_article.description
                    this.parentForm.biography = res.data.litsalon_article.biography
                    this.parentForm.about_creativity = res.data.litsalon_article.about_creativity
                    this.parentForm.publications = res.data.litsalon_article.publications
                    this.parentForm.awards = res.data.litsalon_article.awards

                    this.form.publications = res.data.publications_translations
                    this.form.awards = res.data.awards_translations
                })
                .finally(() => this.isloading = false)
            },
            store() {
                this.isStoreloading = true
                axios.post('/api/admin/litsalon_articles/' + this.$route.params.litsalon_article_id + '/litsalon_articles_translations', this.form)
                .then(res => {
                    this.errors = {}
                    eventBus.$emit('flash-message', 'success', res.data.success)
                    this.$router.push({name: 'admin-litsalon_articles-index'})
                })
                .catch(err => {
                    this.errors = {}
                    if(err.response.data.errors) {
                        eventBus.$emit('flash-message', 'error', 'The given data was invalid.')
                        this.errors = err.response.data.errors
                    }
                })
                .finally(() => this.isStoreloading = false)
            },
        },
    }
</script>
