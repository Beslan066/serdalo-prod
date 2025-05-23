<template>
    <button 
        @click="$emit('click')" 
        :type="type" 
        :class="[classes]"
        class="min-h-9 inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition"
    >
        <spin v-if="isLoading" />
        <slot></slot>
    </button>
</template>

<script>
    import Spin from '@/components/Spin'

    export default {
        components: {
            Spin,
        },
        props: {
            classes: {
                type: [String, Object],
                default: 'bg-indigo-400',
            },
            type: {
                type: String,
                default: 'button',
            },
            isLoading: {
                type: Boolean,
                default: false,
            },
        }
    }
</script>
