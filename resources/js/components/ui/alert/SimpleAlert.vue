<template>
    <Transition enter-active-class="transition ease-out duration-300"
        enter-from-class="opacity-0 transform -translate-y-2" enter-to-class="opacity-100 transform translate-y-0"
        leave-active-class="transition ease-in duration-200" leave-from-class="opacity-100 transform translate-y-0"
        leave-to-class="opacity-0 transform -translate-y-2">
        <div v-if="show" :class="[
            'fixed top-4 right-4 z-50 max-w-sm w-full p-4 rounded-lg shadow-lg border-l-4',
            alertClasses
        ]" role="alert">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <!-- Ikon SVG langsung disertakan untuk kemandirian komponen -->
                    <svg v-if="type === 'success'" class="w-5 h-5 text-green-600" fill="currentColor"
                        viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd"></path>
                    </svg>
                    <svg v-else-if="type === 'error'" class="w-5 h-5 text-red-600" fill="currentColor"
                        viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                            clip-rule="evenodd"></path>
                    </svg>
                    <svg v-else-if="type === 'warning'" class="w-5 h-5 text-yellow-600" fill="currentColor"
                        viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0L14.77 10.82c.765 1.36-.174 3.099-1.743 3.099H6.973c-1.569 0-2.508-1.739-1.743-3.099L8.257 3.099zM10 13a1 1 0 100-2 1 1 0 000 2zm0-4a1 1 0 00-1 1v2a1 1 0 102 0V10a1 1 0 00-1-1z"
                            clip-rule="evenodd"></path>
                    </svg>
                    <svg v-else-if="type === 'info'" class="w-5 h-5 text-blue-600" fill="currentColor"
                        viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2h2a1 1 0 001-1V9a1 1 0 10-2 0H9z"
                            clip-rule="evenodd"></path>
                    </svg>
                    <svg v-else class="w-5 h-5 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2h2a1 1 0 001-1V9a1 1 0 10-2 0H9z"
                            clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-3 flex-1">
                    <p class="text-sm font-medium">{{ title }}</p>
                    <p v-if="message" class="text-sm mt-1">{{ message }}</p>
                </div>
                <div class="ml-4 flex-shrink-0">
                    <button @click="close"
                        class="inline-flex text-gray-400 hover:text-gray-600 focus:outline-none focus:text-gray-600 transition ease-in-out duration-150">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </Transition>
</template>

<script setup lang="ts">
import { computed, onMounted, ref } from 'vue'

interface Props {
    type: 'success' | 'error' | 'warning' | 'info'
    title: string
    message?: string
    autoClose?: boolean
    duration?: number
}

const props = withDefaults(defineProps<Props>(), {
    autoClose: true,
    duration: 5000
})

const emit = defineEmits<{
    close: []
}>()

const show = ref(true)

const alertClasses = computed(() => {
    switch (props.type) {
        case 'success':
            return 'bg-green-50 border-green-400 text-green-800'
        case 'error':
            return 'bg-red-50 border-red-400 text-red-800'
        case 'warning':
            return 'bg-yellow-50 border-yellow-400 text-yellow-800'
        case 'info':
            return 'bg-blue-50 border-blue-400 text-blue-800'
        default:
            return 'bg-gray-50 border-gray-400 text-gray-800'
    }
})

// iconComponent tidak lagi dibutuhkan karena SVG langsung di template

const close = () => {
    show.value = false
    setTimeout(() => {
        emit('close')
    }, 200) // Durasi ini harus sesuai dengan durasi transisi leave-active-class
}

onMounted(() => {
    if (props.autoClose) {
        setTimeout(() => {
            close()
        }, props.duration)
    }
})
</script>
