export type ListCreatedType = {
    list: {
        id: string;
        projectId: string;
        name: string;
        order: number;
    }
}

export type ListDeletedType = {
    listId: string
}

export type ListUpdatedType = {
    list: {
        id: string;
        projectId: string;
        name: string;
        order: number;
    }
}