<script setup lang="ts">
import { Head, Link, setLayoutProps, useForm } from '@inertiajs/vue3';
import { ArrowRight, FilePlus, FileText, FolderOpen } from '@lucide/vue';
import { watch } from 'vue';
import DeleteProject from '@/components/DeleteProject.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import {
    index as projectsIndex,
    update as updateProject,
} from '@/routes/projects';
import { create, edit, index as pagesIndex } from '@/routes/projects/pages';
import type { BreadcrumbItem, Page, Project } from '@/types';

const props = defineProps<{
    project: Project;
    pages: Page[];
}>();

const form = useForm({
    title: props.project.title,
    description: props.project.description ?? '',
});

watch(
    () => props.project.title,
    (title) => {
        setLayoutProps<{ breadcrumbs: BreadcrumbItem[] }>({
            breadcrumbs: [
                { title: 'Projects', href: projectsIndex() },
                { title, href: pagesIndex(props.project) },
            ],
        });
    },
    { immediate: true },
);

/**
 * Save the project's title and description when either field has changed.
 */
function saveProject(): void {
    if (!form.isDirty) {
        return;
    }

    form.patch(updateProject.url(props.project), {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            form.defaults({
                title: props.project.title,
                description: props.project.description ?? '',
            });
            form.reset();
        },
    });
}

/**
 * Leave the field, which saves it through the blur handler.
 */
function commitField(event: KeyboardEvent): void {
    (event.target as HTMLInputElement).blur();
}

/**
 * Throw away unsaved changes and leave the field.
 */
function discardChanges(event: KeyboardEvent): void {
    form.reset();
    form.clearErrors();
    (event.target as HTMLInputElement).blur();
}

/**
 * Split a page's content into its first non-empty line and the rest,
 * so the first line can act as the card heading.
 */
function preview(page: Page): { heading: string; body: string } {
    const lines = page.content.trim().split('\n');

    return {
        heading: lines[0]?.trim() || 'Untitled page',
        body: lines.slice(1).join('\n').trim(),
    };
}
</script>

<template>
    <Head :title="`${project.title} pages`" />

    <div
        class="flex h-full flex-1 flex-col gap-6 overflow-x-auto rounded-xl p-4"
    >
        <div class="flex flex-col gap-2">
            <header
                class="flex flex-col gap-3 pt-4 sm:flex-row sm:items-center sm:gap-4"
            >
                <h1 class="sr-only">{{ project.title }}</h1>

                <div class="flex min-w-0 items-center gap-3 sm:gap-4">
                    <div
                        class="bg-muted text-muted-foreground flex size-10 shrink-0 items-center justify-center rounded-lg sm:size-12"
                    >
                        <FolderOpen class="size-5 sm:size-6" />
                    </div>

                    <input
                        v-model="form.title"
                        type="text"
                        maxlength="255"
                        autocomplete="off"
                        aria-label="Project title"
                        :aria-invalid="!!form.errors.title"
                        class="hover:bg-muted/60 focus-visible:bg-background focus-visible:ring-ring/50 aria-invalid:ring-destructive/40 -ml-2 field-sizing-content max-w-full min-w-0 rounded-md bg-transparent px-2 py-0.5 text-xl font-semibold tracking-tight outline-none focus-visible:ring-[3px] aria-invalid:ring-2 sm:text-2xl"
                        @blur="saveProject"
                        @keydown.enter.prevent="commitField"
                        @keydown.escape="discardChanges"
                    />
                </div>

                <div
                    v-if="project.description"
                    class="border-sidebar-border/70 dark:border-sidebar-border min-w-0 sm:max-w-sm sm:border-l sm:pl-2"
                >
                    <input
                        v-model="form.description"
                        type="text"
                        maxlength="100"
                        autocomplete="off"
                        aria-label="Project description"
                        :aria-invalid="!!form.errors.description"
                        class="text-muted-foreground hover:bg-muted/60 focus-visible:bg-background focus-visible:text-foreground focus-visible:ring-ring/50 aria-invalid:ring-destructive/40 field-sizing-content max-w-full min-w-0 rounded-md bg-transparent px-2 py-1 text-sm outline-none focus-visible:ring-[3px] aria-invalid:ring-2"
                        @blur="saveProject"
                        @keydown.enter.prevent="commitField"
                        @keydown.escape="discardChanges"
                    />
                </div>
            </header>

            <InputError :message="form.errors.title" />
            <InputError :message="form.errors.description" />
        </div>

        <div
            v-if="pages.length"
            class="grid auto-rows-min gap-4 sm:grid-cols-2 lg:grid-cols-3"
        >
            <div
                v-for="page in pages"
                :key="page.id"
                class="border-sidebar-border/70 dark:border-sidebar-border relative flex min-h-40 flex-col justify-between gap-4 overflow-hidden rounded-xl border p-4 sm:p-6"
            >
                <div class="flex flex-col gap-2">
                    <div class="flex items-center gap-2">
                        <FileText
                            class="text-muted-foreground size-5 shrink-0"
                        />
                        <h2
                            class="min-w-0 truncate text-base font-semibold sm:text-lg"
                        >
                            {{ preview(page).heading }}
                        </h2>
                    </div>

                    <p
                        v-if="preview(page).body"
                        class="text-muted-foreground line-clamp-3 text-sm wrap-break-word whitespace-pre-line"
                    >
                        {{ preview(page).body }}
                    </p>
                </div>

                <Link
                    :href="edit({ project: project.id, page: page.id })"
                    class="text-primary inline-flex items-center gap-1.5 self-start text-sm font-medium underline-offset-4 hover:underline"
                >
                    Open editor
                    <ArrowRight class="size-4" />
                </Link>
            </div>
        </div>

        <p v-else class="text-muted-foreground text-sm">
            This project doesn't have any pages yet.
        </p>

        <div class="flex flex-col gap-2 sm:flex-row sm:items-center">
            <Button as-child class="w-full sm:w-auto">
                <Link :href="create(project)">
                    <FilePlus />
                    New Page
                </Link>
            </Button>

            <DeleteProject :project="project" />
        </div>
    </div>
</template>
