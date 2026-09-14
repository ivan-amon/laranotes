<script setup lang="ts">
import { Form } from '@inertiajs/vue3';
import { Save } from '@lucide/vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import type { RouteFormDefinition } from '@/wayfinder';

defineProps<{
    form: RouteFormDefinition<'post'>;
    content?: string;
}>();
</script>

<template>
    <Form
        v-bind="form"
        class="flex flex-1 flex-col gap-4"
        v-slot="{ errors, processing }"
    >
        <label for="content" class="sr-only">Page content</label>
        <textarea
            id="content"
            name="content"
            required
            autofocus
            :value="content"
            :aria-invalid="!!errors.content"
            placeholder="Start writing..."
            class="placeholder:text-muted-foreground selection:bg-primary selection:text-primary-foreground dark:bg-input/30 border-input focus-visible:border-ring focus-visible:ring-ring/50 aria-invalid:ring-destructive/20 dark:aria-invalid:ring-destructive/40 aria-invalid:border-destructive min-h-[60vh] w-full flex-1 resize-y rounded-xl border bg-transparent p-4 text-base leading-relaxed shadow-xs transition-[color,box-shadow] outline-none focus-visible:ring-[3px] sm:p-6"
        />
        <InputError :message="errors.content" />

        <div class="flex flex-col gap-2 sm:flex-row sm:justify-end">
            <div
                v-if="$slots['leading-actions']"
                class="flex flex-col gap-2 sm:mr-auto sm:flex-row"
            >
                <slot name="leading-actions" />
            </div>

            <Button
                type="submit"
                :disabled="processing"
                class="w-full sm:w-auto"
                data-test="save-page-button"
            >
                <Save />
                {{ processing ? 'Saving...' : 'Save' }}
            </Button>

            <slot name="actions" />
        </div>
    </Form>
</template>
