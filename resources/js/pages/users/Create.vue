<script setup lang="ts">
import { Dialog, DialogContent, DialogFooter, DialogTitle } from '@/components/ui/dialog';
import { validate } from '@/Forms/UserCreateForm';
import { Form, Link, useForm, usePage } from '@inertiajs/vue3';
import { Undo2 } from '@lucide/vue';
import { onUnmounted, ref, watch } from 'vue';
import { route } from 'ziggy-js';

const page = usePage();

const flashSuccess = ref({ message: '', isOpen: false });
const flashError = ref({ message: '', isOpen: false });
const isOpen = ref(false);

let closeTimeoutId: ReturnType<typeof setTimeout> | null = null;
let clearTimeoutId: ReturnType<typeof setTimeout> | null = null;

const form = useForm({
    name: '',
    email: '',
    password: '',
});

onUnmounted(() => clearTimers())

watch(
    () => page.props.flash as { success: string; error: string },
    (flash) => {
        const hasSuccess = Boolean(flash?.success);
        const hasError = Boolean(flash?.error);

        flashSuccess.value = { message: flash?.success || '', isOpen: hasSuccess };
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
    { immediate: true, deep: true }
)

function validateForm(): void {
    const success = validate(form)
    if (success) isOpen.value = true;
}

function submit(): void {
    form.post(route('signup'), {
        onSuccess: (message) => {
            console.log('message', message);
            form.reset('password');
        },
        onError: (errors) => {
            console.error('Validation errors:', errors)
        }
    });
}

function clearTimers(): void {
    if (closeTimeoutId) clearTimeout(closeTimeoutId);
    if (clearTimeoutId) clearTimeout(clearTimeoutId);
}

</script>

<template>
    <div class="flex flex-col px-6 py-4">
        <div class="flex gap-4 items-center justify-between mb-4">
            <h1 class="text-2xl">Create New User</h1>

            <Link :href="route('users')"
                class="flex items-center gap-2 rounded border bg-gray-600 px-2 py-0.5 self-start">
                <Undo2 class="size-4" />
                <span>Back</span>
            </Link>
        </div>

        <Form id="user-register-form" @submit.prevent="submit" class="flex">
            <div class="flex flex-col gap-5 p-2 border rounded">
                <div class="relative flex gap-2 items-center">
                    <label for="name" class="min-w-30">Name</label>
                    <input type="text" v-model="form.name" class="p-1 border rounded bg-[#001026] min-h-8">
                    <span v-if="form.errors.name" class="absolute top-full left-32 mt-0.5 text-xs text-red-500">
                        {{ form.errors.name }}
                    </span>
                </div>

                <div class="relative flex gap-2 items-center">
                    <label for="email" class="min-w-30">Email</label>
                    <input type="email" v-model="form.email" class="p-1 border rounded bg-[#001026] min-h-8">
                    <span v-if="form.errors.email" class="absolute top-full left-32 mt-0.5 text-xs text-red-500">
                        {{ form.errors.email }}
                    </span>
                </div>

                <div class="relative flex gap-2 items-center">
                    <label for="password" class="min-w-30">Password</label>
                    <input type="password" v-model="form.password" class="p-1 border rounded bg-[#001026] min-h-8">
                    <span v-if="form.errors.password" class="absolute top-full left-32 mt-0.5 text-xs text-red-500">
                        {{ form.errors.password }}
                    </span>
                </div>

                <button type="button" @click="validateForm"
                    class="self-start px-2 py-0.5 rounded bg-blue-600 cursor-pointer">Submit</button>
            </div>
        </Form>

        <div :class="[
            'fixed bottom-5 right-5 p-4 border rounded transition-translate duration-300',
            flashSuccess.isOpen || flashError.isOpen ? 'translate-x-0' : 'translate-x-[200%]',
            { 'border-green-600': flashSuccess.isOpen }, { 'border-red-600': flashError.isOpen }]">
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

                <div class="flex flex-col gap-2 p-2 rounded mt-4">
                    <span class="bg-black rounded px-1">Name: {{ form.name }}</span>
                    <span class="bg-black rounded px-1">Name: {{ form.email }}</span>
                </div>

                <DialogFooter>
                    <button form="user-register-form" type="submit"
                        class="self-start px-2 py-0.5 rounded bg-blue-600 cursor-pointer">Submit</button>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    </div>
</template>