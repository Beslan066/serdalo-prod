<template>
    <div class="pb-4 w-full">
        <label v-if="label" class="mb-2 block" :for="id">{{ label }}:</label>
        <select :id="id" ref="input" v-model="modelValue" v-bind="$attrs" class="block w-full p-2 border rounded border-gray-400" :class="{ 'input-error': error }" >
            <slot />
        </select>
        <div v-if="error" class="text-red-400">{{ displayError }}</div>
    </div>
</template>

<script>
    export default {
        inheritAttrs: false,
        props: {
            id: String,
            modelValue: [String, Number, Boolean, Array, Object],
            label: String,
            error: [String, Array],
        },
        data() {
            return {
                selected: this.value,
            }
        },
        computed: {
            displayError() {
                return (Array.isArray(this.error)) ? this.error.join('; ') : this.error
            }
        },
        watch: {
            selected(selected) {
                this.$emit('input', selected)
            },
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