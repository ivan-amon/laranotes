<script setup lang="ts">
import { Head, setLayoutProps } from '@inertiajs/vue3';
import PageEditor from '@/components/PageEditor.vue';
import { index as projectsIndex } from '@/routes/projects';
import { create, index as pagesIndex, store } from '@/routes/projects/pages';
import type { BreadcrumbItem, Project } from '@/types';

const props = defineProps<{
    project: Project;
}>();

setLayoutProps<{ breadcrumbs: BreadcrumbItem[] }>({
    breadcrumbs: [
        { title: 'Projects', href: projectsIndex() },
        { title: props.project.title, href: pagesIndex(props.project) },
        { title: 'New page', href: create(props.project) },
    ],
});
</script>

<template>
    <Head title="New page" />

    <div class="flex h-full flex-1 flex-col rounded-xl p-4">
        <PageEditor :form="store.form(project)" />
    </div>
</template>
