<script setup lang="ts">
import { useHttp } from '@inertiajs/vue3';
import { FileDown, LoaderCircle } from '@lucide/vue';
import { computed, onBeforeUnmount, onMounted, ref } from 'vue';
import { toast } from 'vue-sonner';
import {
    show,
    store,
} from '@/actions/App/Http/Controllers/PagePdfExportController';
import { Button } from '@/components/ui/button';
import type { Page, PageExportStatus, Project } from '@/types';

type ExportStatusResponse = {
    status: PageExportStatus;
    file: string | null;
};

const POLL_INTERVAL_MS = 2000;

const props = defineProps<{
    project: Project;
    page: Page;
}>();

const exportRequest = useHttp<Record<string, never>, ExportStatusResponse>({});
const statusRequest = useHttp<Record<string, never>, ExportStatusResponse>({});

const status = ref<PageExportStatus>(props.page.export_status);
const isExporting = computed(
    () => exportRequest.processing || isInProgress(status.value),
);

let pollTimer: ReturnType<typeof setTimeout> | undefined;
let isUnmounted = false;

function routeArgs(): { project: number; page: number } {
    return { project: props.project.id, page: props.page.id };
}

function isInProgress(exportStatus: PageExportStatus): boolean {
    return exportStatus === 'pending' || exportStatus === 'processing';
}

function handleStatus(response: ExportStatusResponse): void {
    if (isUnmounted) {
        return;
    }

    status.value = response.status;

    if (isInProgress(response.status)) {
        pollTimer = setTimeout(pollStatus, POLL_INTERVAL_MS);
    } else if (response.status === 'completed') {
        toast.success('PDF exported.', {
            description: response.file ?? undefined,
        });
    } else if (response.status === 'failed') {
        toast.error('Could not export the page.');
    }
}

async function exportPdf(): Promise<void> {
    try {
        handleStatus(await exportRequest.post(store.url(routeArgs())));
    } catch {
        toast.error('Could not export the page.');
    }
}

async function pollStatus(): Promise<void> {
    try {
        handleStatus(await statusRequest.get(show.url(routeArgs())));
    } catch {
        if (!isUnmounted) {
            status.value = 'idle';
            toast.error('Could not check the export status.');
        }
    }
}

onMounted(() => {
    // Resume polling if an export was already running when the page loaded.
    if (isInProgress(status.value)) {
        void pollStatus();
    }
});

onBeforeUnmount(() => {
    isUnmounted = true;
    clearTimeout(pollTimer);
});
</script>

<template>
    <Button
        type="button"
        variant="outline"
        :disabled="isExporting"
        class="w-full sm:w-auto"
        data-test="export-page-pdf-button"
        @click="exportPdf"
    >
        <LoaderCircle v-if="isExporting" class="animate-spin" />
        <FileDown v-else />
        {{ isExporting ? 'Exporting...' : 'Export PDF' }}
    </Button>
</template>
