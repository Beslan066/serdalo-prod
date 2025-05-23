<template>
    <button 
        @click="deleteRequest" 
        type="button" 
        class="min-h-9 inline-flex items-center px-4 py-2 bg-red-400 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition"
    >
        <spin-small v-if="isLoading" />
        <i v-if="!isLoading" class="fas fa-trash-alt"></i>
    </button>
</template>

<script>
    import { eventBus } from '@/app'
    
    import SpinSmall from '@/components/SpinSmall'

    export default {
        components: {
            SpinSmall,
        },
        props: {
            uri: {
                type: String,
            },
        },
        data: () => ({
            isLoading: false,
        }),
        methods: {
            deleteRequest() {
                if(confirm('Вы уверены?')) {
                    this.isLoading = true
                    axios.delete(this.uri)
                    .then(res => {
                        this.$emit('deleted')
                        eventBus.$emit('flash-message', 'success', res.data.success)
                    })
                    .catch(err => {
                        if(err.response.data.errors) {
                            eventBus.$emit('flash-message', 'error', 'The given data was invalid.')
                            this.errors = err.response.data.errors
                        }
                        if(err.response.data.error) {
                            eventBus.$emit('flash-message', 'error', err.response.data.error);
                        }
                        if(err.response.data.message) {
                            eventBus.$emit('flash-message', 'error', err.response.data.message);
                        }
                    })
                    .finally(() => this.isLoading = false)
                }
            }
        }
    }
</script>
