<script setup lang="ts">
import { useHttp } from '@inertiajs/vue3';
import { FileDown, LoaderCircle } from '@lucide/vue';
import { toast } from 'vue-sonner';
import { Button } from '@/components/ui/button';
import { exportMethod } from '@/routes/projects/pages';
import type { Page, Project } from '@/types';

const props = defineProps<{
    project: Project;
    page: Page;
}>();

const http = useHttp<Record<string, never>, { message: string; file: string }>(
    {},
);

async function exportPdf(): Promise<void> {
    try {
        const response = await http.post(
            exportMethod.url({
                project: props.project.id,
                page: props.page.id,
            }),
        );

        toast.success(response.message, { description: response.file });
    } catch {
        toast.error('Could not export the page.');
    }
}
</script>

<template>
    <Button
        type="button"
        variant="outline"
        :disabled="http.processing"
        class="w-full sm:w-auto"
        data-test="export-page-pdf-button"
        @click="exportPdf"
    >
        <LoaderCircle v-if="http.processing" class="animate-spin" />
        <FileDown v-else />
        {{ http.processing ? 'Exporting...' : 'Export PDF' }}
    </Button>
</template>
