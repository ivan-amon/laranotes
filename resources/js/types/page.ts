export type PageExportStatus =
    | 'idle'
    | 'pending'
    | 'processing'
    | 'completed'
    | 'failed';

export type Page = {
    id: number;
    project_id: number;
    content: string;
    export_status: PageExportStatus;
    export_file: string | null;
    created_at: string;
    updated_at: string;
};
