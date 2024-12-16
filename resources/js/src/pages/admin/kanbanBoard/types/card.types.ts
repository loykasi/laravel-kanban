export type CardCreatedType = {
    projectId: string,
    card: {
        id: string;
        listId: string;
        name: string;
        description: string;
        order: number;
    }
}

export type CardDeletedType = {
    projectId: string,
    listId: string,
    cardId: string,
}

export type CardUpdatedType = {
    fromListId: string;
    card: {
        id: string;
        listId: string;
        name: string;
        description: string;
        order: number;
    }
}