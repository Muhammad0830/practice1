<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { Plus } from '@lucide/vue';
import { onUnmounted, ref, watch } from 'vue';
import { route } from 'ziggy-js';
import type { User } from '@/types';

defineProps<{ data?: User[] }>();

const page = usePage();

const flashSuccess = ref({ message: '', isOpen: false });
const flashError = ref({ message: '', isOpen: false });

let closeTimeoutId: ReturnType<typeof setTimeout> | null = null;
let clearTimeoutId: ReturnType<typeof setTimeout> | null = null;

onUnmounted(() => clearTimers());

watch(
    () => page.props.flash as { success: string; error: string },
    (flash) => {
        const hasSuccess = Boolean(flash?.success);
        const hasError = Boolean(flash?.error);

        flashSuccess.value = {
            message: flash?.success || '',
            isOpen: hasSuccess,
        };
        flashError.value = { message: flash?.error || '', isOpen: hasError };

        clearTimers();

        if (hasSuccess || hasError) {
            closeTimeoutId = setTimeout(() => {
                flashSuccess.value.isOpen = false;
                flashError.value.isOpen = false;
            }, 2000);

            clearTimeoutId = setTimeout(() => {
                flashSuccess.value.message = '';
                flashError.value.message = '';
            }, 2300);
        }
    },
    { immediate: true, deep: true },
);

function clearTimers() {
    if (closeTimeoutId) {
        clearTimeout(closeTimeoutId);
    }

    if (clearTimeoutId) {
        clearTimeout(clearTimeoutId);
    }
}
</script>

<template>
    <div class="flex flex-col px-6 py-4">
        <div class="mb-4 flex items-center justify-between gap-2">
            <h1 class="text-2xl">Users list</h1>

            <Link
                :href="route('users-create')"
                class="flex cursor-pointer items-center gap-2 self-start rounded border bg-blue-600 px-2 py-0.5"
            >
                <Plus class="size-4" />
                <span>Create New</span>
            </Link>
        </div>

        <div
            v-for="(item, index) in data"
            :key="item.id"
            class="grid grid-cols-[15px_auto_1fr_auto] items-center gap-2"
        >
            <span>{{ index + 1 }}.</span>
            <span>{{ item.name }}</span>
            <div class="border-b-1 border-white/10"></div>
            <span>{{ item.email }}</span>
        </div>
    </div>

    <div
        :class="[
            'transition-translate fixed right-5 bottom-5 rounded border p-4 duration-300',
            flashSuccess.isOpen || flashError.isOpen
                ? 'translate-x-0'
                : 'translate-x-[200%]',
            { 'border-green-600': flashSuccess.isOpen },
            { 'border-red-600': flashError.isOpen },
        ]"
    >
        <div class="alert alert-success">
            {{ flashSuccess.message }}
        </div>
        <div v-if="!flashSuccess" class="alert alert-danger">
            {{ flashError.message }}
        </div>
    </div>
</template>
