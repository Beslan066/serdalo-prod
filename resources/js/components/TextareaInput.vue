<template>
    <div class="pb-4 w-full">
        <label v-if="label" class="mb-2 block" :for="id">{{ label }}:</label>
        <textarea 
            :id="id" 
            ref="input" 
            v-bind="$attrs" 
            class="block w-full p-2 border rounded border-gray-400" 
            :class="{ 'input-error': error }" 
            :value="value"
            @input="$emit('input', $event.target.value)"
        />
        <div v-if="error" class="text-red-400">{{ displayError }}</div>
    </div>
</template>

<script>
    export default {
        inheritAttrs: false,
        props: {
            id: {
                type: String,
            },
            value: String,
            label: String,
            error: [String, Array],
        },
        computed: {
            displayError() {
                return (Array.isArray(this.error)) ? this.error.join('; ') : this.error
            }
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