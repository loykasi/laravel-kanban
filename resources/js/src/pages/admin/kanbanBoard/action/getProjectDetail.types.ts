
export type SingleProjectResponseType = {
    data: {
        id: string;
        name: string;
        slug: string;
        lists: ListType[];
    };
};

export type ListType = {
    id: string;
    projectId: string;
    name: string;
    order: number;
    isCreating: boolean;
    isEditing: boolean;
    cards: CardType[];
};

export type CardType = {
    id: string;
    listId: string;
    name: string;
    order: number;
    description: string;
    isEditing: boolean;
}