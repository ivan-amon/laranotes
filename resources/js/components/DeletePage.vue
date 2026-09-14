<script setup lang="ts">
import { Form } from '@inertiajs/vue3';
import { Trash2 } from '@lucide/vue';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogClose,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from '@/components/ui/dialog';
import { destroy } from '@/routes/projects/pages';
import type { Page, Project } from '@/types';

defineProps<{
    project: Project;
    page: Page;
}>();
</script>

<template>
    <Dialog>
        <DialogTrigger as-child>
            <Button
                type="button"
                variant="outline"
                class="text-muted-foreground hover:text-foreground w-full sm:w-auto"
                data-test="delete-page-button"
            >
                <Trash2 />
                Delete
            </Button>
        </DialogTrigger>

        <DialogContent>
            <Form
                v-bind="destroy.form({ project: project.id, page: page.id })"
                class="space-y-6"
                v-slot="{ processing }"
            >
                <DialogHeader class="space-y-3">
                    <DialogTitle>
                        Are you sure you want to delete this page?
                    </DialogTitle>
                    <DialogDescription>
                        This page will be permanently deleted. This cannot be
                        undone.
                    </DialogDescription>
                </DialogHeader>

                <DialogFooter class="gap-2">
                    <DialogClose as-child>
                        <Button type="button" variant="secondary">
                            Cancel
                        </Button>
                    </DialogClose>

                    <Button
                        type="submit"
                        variant="destructive"
                        :disabled="processing"
                        data-test="confirm-delete-page-button"
                    >
                        Delete
                    </Button>
                </DialogFooter>
            </Form>
        </DialogContent>
    </Dialog>
</template>
