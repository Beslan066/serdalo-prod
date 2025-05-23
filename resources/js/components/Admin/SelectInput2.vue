<template>
    <div class="pb-4 w-full">
        <label v-if="label" class="mb-2 block" :for="id">{{ label }}:</label>
        <select 
            :id="id" 
            ref="input" 
            v-model="selected" 
            v-bind="$attrs" 
            class="block w-full p-2 border rounded border-gray-400" 
            :class="{ 'input-error': error }" 
        >
            <option v-for="(option, optionIndex) in options" :key="optionIndex" :value="optionIndex">{{ option }}</option>
        </select>
    </div>
</template>

<script>
    export default {
        inheritAttrs: false,
        props: {
            id: String,
            modelValue: [String, Number, Boolean, Array, Object],
            options: [String, Number, Boolean, Array, Object],
            label: String,
            error: [String, Array],
        },
        /*data() {
            return {
                selected: this.value,
            }
        },*/
        computed: {
            displayError() {
                return (Array.isArray(this.error)) ? this.error.join('; ') : this.error
            },
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
        watch: {
            /*selected(selected) {
                console.log(selected)
                this.$emit('input', selected)
            },*/
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