<script setup lang="ts">
import { Form, Link, useForm, usePage } from '@inertiajs/vue3';
import type { InertiaForm } from '@inertiajs/vue3';
import { Undo2 } from '@lucide/vue';
import { onUnmounted, ref, watch } from 'vue';
import { route } from 'ziggy-js';
import {
    Dialog,
    DialogContent,
    DialogFooter,
    DialogTitle,
} from '@/components/ui/dialog';
import { validate } from '@/Forms/UserCreateForm';
import type { UserCreateFormProps } from '@/Forms/UserCreateForm';

const page = usePage();

const flashSuccess = ref({ message: '', isOpen: false });
const flashError = ref({ message: '', isOpen: false });
const isOpen = ref(false);

let closeTimeoutId: ReturnType<typeof setTimeout> | null = null;
let clearTimeoutId: ReturnType<typeof setTimeout> | null = null;

const form: InertiaForm<UserCreateFormProps> = useForm({
    name: '',
    email: '',
    password: '',
});

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

function validateForm(): void {
    const success = validate(form);

    if (success) {
        isOpen.value = true;
    }
}

function submit(): void {
    form.post(route('signup'), {
        onSuccess: () => {
            form.reset('password');
        },
        onError: (errors) => {
            console.error('Validation errors:', errors);
        },
    });
}

function clearTimers(): void {
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
        <div class="mb-4 flex items-center justify-between gap-4">
            <h1 class="text-2xl">Create New User</h1>

            <Link
                :href="route('users')"
                class="flex items-center gap-2 self-start rounded border bg-gray-600 px-2 py-0.5"
            >
                <Undo2 class="size-4" />
                <span>Back</span>
            </Link>
        </div>

        <Form id="user-register-form" @submit.prevent="submit" class="flex">
            <div class="flex flex-col gap-5 rounded border p-2">
                <div class="relative flex items-center gap-2">
                    <label for="name" class="min-w-30">Name</label>
                    <input
                        type="text"
                        v-model="form.name"
                        class="min-h-8 rounded border bg-[#001026] p-1"
                    />
                    <span
                        v-if="form.errors.name"
                        class="absolute top-full left-32 mt-0.5 text-xs text-red-500"
                    >
                        {{ form.errors.name }}
                    </span>
                </div>

                <div class="relative flex items-center gap-2">
                    <label for="email" class="min-w-30">Email</label>
                    <input
                        type="email"
                        v-model="form.email"
                        class="min-h-8 rounded border bg-[#001026] p-1"
                    />
                    <span
                        v-if="form.errors.email"
                        class="absolute top-full left-32 mt-0.5 text-xs text-red-500"
                    >
                        {{ form.errors.email }}
                    </span>
                </div>

                <div class="relative flex items-center gap-2">
                    <label for="password" class="min-w-30">Password</label>
                    <input
                        type="password"
                        v-model="form.password"
                        class="min-h-8 rounded border bg-[#001026] p-1"
                    />
                    <span
                        v-if="form.errors.password"
                        class="absolute top-full left-32 mt-0.5 text-xs text-red-500"
                    >
                        {{ form.errors.password }}
                    </span>
                </div>

                <button
                    type="button"
                    @click="validateForm"
                    class="cursor-pointer self-start rounded bg-blue-600 px-2 py-0.5"
                >
                    Submit
                </button>
            </div>
        </Form>

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

        <Dialog v-model:open="isOpen">
            <DialogContent>
                <DialogTitle>ユーザ登録の値をもう一度確認</DialogTitle>

                <div class="mt-4 flex flex-col gap-2 rounded p-2">
                    <span class="rounded bg-black px-1"
                        >Name: {{ form.name }}</span
                    >
                    <span class="rounded bg-black px-1"
                        >Name: {{ form.email }}</span
                    >
                </div>

                <DialogFooter>
                    <button
                        form="user-register-form"
                        type="submit"
                        class="cursor-pointer self-start rounded bg-blue-600 px-2 py-0.5"
                    >
                        Submit
                    </button>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    </div>
</template>
