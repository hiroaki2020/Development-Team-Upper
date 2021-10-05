<template>
    <div id="overlay" @click.stop="$emit('close-modal')" class="z-40 fixed top-0 left-0 w-full h-full bg-black bg-opacity-50 flex items-center justify-center cursor-default">
        <div id="content" class="z-50 w-auto h-auto p-6 bg-white flex items-center justify-center relative" @click.stop>
            <div @click.stop="$emit('close-modal')" class="absolute -top-1 right-1 text-2xl cursor-pointer">×</div>
            <slot></slot>
        </div>
    </div>
</template>

<script>
    export default {
        emits: [
            'close-modal',
        ],

        methods: {
        }
    }
</script>