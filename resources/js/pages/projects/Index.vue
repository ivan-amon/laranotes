<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { ArrowRight, CirclePlus, FolderOpen } from '@lucide/vue';
import { Button } from '@/components/ui/button';
import { create, index as projectsIndex } from '@/routes/projects';
import type { Project } from '@/types';

defineProps<{
    projects: Project[];
}>();

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Projects',
                href: projectsIndex(),
            },
        ],
    },
});
</script>

<template>
    <Head title="Projects" />

    <div
        class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4"
    >
        <div
            v-if="projects.length"
            class="grid auto-rows-min gap-4 sm:grid-cols-2 lg:grid-cols-3"
        >
            <div
                v-for="project in projects"
                :key="project.id"
                class="border-sidebar-border/70 dark:border-sidebar-border relative flex min-h-40 flex-col justify-between gap-4 overflow-hidden rounded-xl border p-4 sm:p-6"
            >
                <div class="flex flex-col gap-2">
                    <div class="flex items-center gap-2">
                        <FolderOpen
                            class="text-muted-foreground size-5 shrink-0"
                        />
                        <h2
                            class="line-clamp-2 text-base font-semibold wrap-break-word sm:text-lg"
                        >
                            {{ project.title }}
                        </h2>
                    </div>

                    <p
                        v-if="project.description"
                        class="text-muted-foreground line-clamp-3 text-sm wrap-break-word"
                    >
                        {{ project.description }}
                    </p>
                </div>

                <Link
                    :href="`/projects/${project.id}/pages`"
                    class="text-primary inline-flex items-center gap-1.5 self-start text-sm font-medium underline-offset-4 hover:underline"
                >
                    View pages
                    <ArrowRight class="size-4" />
                </Link>
            </div>
        </div>

        <p v-else class="text-muted-foreground text-sm">
            You don't have any projects yet.
        </p>

        <div>
            <Button as-child class="w-full sm:w-auto">
                <Link :href="create()">
                    <CirclePlus />
                    Create New Project
                </Link>
            </Button>
        </div>
    </div>
</template>
