<script setup lang="ts">
import { ref } from 'vue';

const count = ref(0);

function increment() {
    count.value++;
}

function decrement() {
    count.value--;
}

const props = defineProps<{
    data?: { id: number; title: string }[];
}>();

</script>

<template>

    <Head title="Practice" />

    <div class="p-2">
        <h1 class="mb-4 text-2xl">This is practice playground</h1>

        <div class="flex items-center gap-2 mb-4">
            <button class="border rounded px-2 py-.5" @click="decrement">decrement</button>
            <div class="min-w-6 text-center">{{ count }}</div>
            <button class="border rounded px-2 py-.5" @click="increment">increment</button>
        </div>

        <div class="flex gap-2 items-center" v-for="item in data" :key="item.id">
            <span>{{ item.id + 1 }}</span>
            <span>{{ item.title }}</span>
        </div>
    </div>
</template>