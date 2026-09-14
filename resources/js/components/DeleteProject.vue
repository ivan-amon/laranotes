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
import { destroy } from '@/routes/projects';
import type { Project } from '@/types';

defineProps<{
    project: Project;
}>();
</script>

<template>
    <Dialog>
        <DialogTrigger as-child>
            <Button
                variant="outline"
                class="text-muted-foreground hover:text-foreground w-full sm:w-auto"
                data-test="delete-project-button"
            >
                <Trash2 />
                Delete Project
            </Button>
        </DialogTrigger>

        <DialogContent>
            <Form
                v-bind="destroy.form(project)"
                class="space-y-6"
                v-slot="{ processing }"
            >
                <DialogHeader class="space-y-3">
                    <DialogTitle>
                        Are you sure you want to delete this project?
                    </DialogTitle>
                    <DialogDescription>
                        "{{ project.title }}" and all of its pages will be
                        permanently deleted. This cannot be undone.
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
                        data-test="confirm-delete-project-button"
                    >
                        Delete
                    </Button>
                </DialogFooter>
            </Form>
        </DialogContent>
    </Dialog>
</template>
