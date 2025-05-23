<template>
    <div class="pb-4 w-full">
        <label v-if="label" class="mb-2 block" :for="id">{{ label }}:</label>
        <multiselect
            :id="id"
            ref="input"
            :class="{ 'input-error': error }"
            v-model="selected"
            :options="options"
            placeholder=""
            :multiple="true"
            label="title"
            track-by="title"
            taggable="true"
        ></multiselect>
        <div v-if="error" class="text-red-400">{{ error | displayError }}</div>
    </div>
</template>

<script>
    import Multiselect from 'vue-multiselect'

    export default {
        components: {
            Multiselect,
        },
        props: {
            id: String,
            value: [String, Number, Boolean, Array, Object],
            options: [String, Number, Boolean, Array, Object],
            label: String,
            error: [String, Array],
        },
        data: () => ({

        }),
        computed: {
            selected: {
                get() {
                    var input = []
                    for(var option of this.options) {
                        if(this.value.includes(option.id)) {
                            input.push(option)
                        }
                    }
                    return input
                },
                set(newValue) {
                    var output = []
                    for(var option of newValue) {
                        output.push(option.id)
                    }
                    this.$emit('input', output)
                }
            }
        },
        mounted() {

        },
        watch: {

        },
        methods: {
            focus() {
                this.$refs.input.focus()
            },
            select() {
                this.$refs.input.select()
            },
        },
    }
</script>
