<script setup lang="ts">
import { Form, Head, Link } from '@inertiajs/vue3';
import Heading from '@/components/Heading.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { create, index as projectsIndex, store } from '@/routes/projects';

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Projects',
                href: projectsIndex(),
            },
            {
                title: 'Create project',
                href: create(),
            },
        ],
    },
});
</script>

<template>
    <Head title="Create project" />

    <div class="flex h-full flex-1 flex-col rounded-xl p-4">
        <div class="w-full max-w-xl">
            <Heading
                title="Create project"
                description="Give your project a title and an optional description"
            />

            <Form
                v-bind="store.form()"
                class="space-y-6"
                v-slot="{ errors, processing }"
            >
                <div class="grid gap-2">
                    <Label for="title">Title</Label>
                    <Input
                        id="title"
                        name="title"
                        required
                        autofocus
                        minlength="3"
                        maxlength="255"
                        placeholder="My project"
                    />
                    <InputError :message="errors.title" />
                </div>

                <div class="grid gap-2">
                    <Label for="description">
                        Description
                        <span class="text-muted-foreground font-normal">
                            (optional)
                        </span>
                    </Label>
                    <Input
                        id="description"
                        name="description"
                        minlength="3"
                        maxlength="100"
                        placeholder="What is this project about?"
                    />
                    <InputError :message="errors.description" />
                </div>

                <div
                    class="flex flex-col-reverse gap-2 sm:flex-row sm:items-center"
                >
                    <Button variant="outline" as-child>
                        <Link :href="projectsIndex()">Cancel</Link>
                    </Button>
                    <Button
                        type="submit"
                        :disabled="processing"
                        data-test="create-project-button"
                    >
                        Create project
                    </Button>
                </div>
            </Form>
        </div>
    </div>
</template>
