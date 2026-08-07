import type { InertiaForm } from '@inertiajs/vue3';
import { z } from 'zod';

export interface UserCreateFormProps {
    name: string;
    email: string;
    password: string;
}

export const signupSchema = z.object({
    name: z.string().min(1, '名前は必須です。'),
    email: z
        .email('メールアドレスは正しくありません。')
        .min(1, 'メールアドレスは必須です。'),
    password: z
        .string()
        .min(8, 'パスワーどは査定８文字じゃなければなりません。')
        .min(1, 'パスワードは必須です。'),
});

export function validate(form: InertiaForm<UserCreateFormProps>): boolean {
    form.clearErrors();

    const result = signupSchema.safeParse(form.data());

    if (!result.success) {
        result.error.issues.forEach((issue) => {
            const fieldName = issue.path[0] as keyof typeof form.data;
            form.setError(fieldName, issue.message);
        });

        return false;
    }

    return true;
}
