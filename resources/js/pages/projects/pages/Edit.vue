<script setup lang="ts">
import { Head, setLayoutProps } from '@inertiajs/vue3';
import DeletePage from '@/components/DeletePage.vue';
import PageEditor from '@/components/PageEditor.vue';
import { index as projectsIndex } from '@/routes/projects';
import { edit, index as pagesIndex, update } from '@/routes/projects/pages';
import type { BreadcrumbItem, Page, Project } from '@/types';

const props = defineProps<{
    project: Project;
    page: Page;
}>();

setLayoutProps<{ breadcrumbs: BreadcrumbItem[] }>({
    breadcrumbs: [
        { title: 'Projects', href: projectsIndex() },
        { title: props.project.title, href: pagesIndex(props.project) },
        {
            title: 'Edit page',
            href: edit({ project: props.project.id, page: props.page.id }),
        },
    ],
});
</script>

<template>
    <Head title="Edit page" />

    <div class="flex h-full flex-1 flex-col rounded-xl p-4">
        <PageEditor
            :form="update.form({ project: project.id, page: page.id })"
            :content="page.content"
        >
            <template #actions>
                <DeletePage :project="project" :page="page" />
            </template>
        </PageEditor>
    </div>
</template>
