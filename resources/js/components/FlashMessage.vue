<template>
    <div class="fixed top-0 right-0 m-6 z-10" v-if="messages">
        <div v-for="message, message_index in messages" :key="message_index">
            <transition name="slide-fade">
                <div
                    v-if="message.show"
                    :class="{
                        'bg-red-200 text-red-900': message.type === 'error',
                        'bg-green-200 text-green-900': message.type === 'success',
                    }"
                    class="relative rounded-lg shadow-md mb-2 p-6 pr-10"
                    style="min-width: 240px"
                >
                    <button class="cursor-pointer absolute top-0 right-0 py-2 px-3 hover:opacity-75" @click.prevent="destroyMessage(message_index)">
                        <i class="fas fa-times text-red-600"></i>
                    </button>
                    <div class="flex items-center">
                        {{ message.text }}
                    </div>
                </div>
            </transition>
        </div>
    </div>
</template>

<script>
    import { eventBus } from '@/app'

    export default {
        data: () => ({
            messages: [],
        }),
        mounted() {
            eventBus.$on('flash-message', (type, text) => {
                this.messages.push({show: true, type: type, text: text})
                var index = this.messages.length - 1

                this.messages[index].timer = setTimeout(() => {
                    this.destroyMessage(index)
                }, 5000);
            });
        },
        methods: {
            destroyMessage(index) {
                clearTimeout(this.messages[index].timer)
                this.messages[index].show = false
            }
        },
    }
</script>

<style scoped>
    .slide-fade-enter-active,
    .slide-fade-leave-active {
        transition: all 0.4s;
    }
    .slide-fade-enter,
    .slide-fade-leave-to {
        transform: translateX(400px);
        opacity: 0;
    }
</style>