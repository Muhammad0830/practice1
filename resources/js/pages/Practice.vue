<script setup lang="ts">
import { ref } from 'vue';

const count = ref(0);

function increment() {
    count.value++;
}

function decrement() {
    count.value--;
}

defineProps<{
    data?: { id: number; title: string }[];
}>();
</script>

<template>
    <Head title="Practice" />

    <div class="p-2">
        <h1 class="mb-4 text-2xl">This is practice playground</h1>

        <div class="mb-4 flex items-center gap-2">
            <button class="py-.5 rounded border px-2" @click="decrement">
                decrement
            </button>
            <div class="min-w-6 text-center">{{ count }}</div>
            <button class="py-.5 rounded border px-2" @click="increment">
                increment
            </button>
        </div>

        <div
            v-for="item in data"
            :key="item.id"
            class="flex items-center gap-2"
        >
            <span>{{ item.id + 1 }}</span>
            <span>{{ item.title }}</span>
        </div>
    </div>
</template>
