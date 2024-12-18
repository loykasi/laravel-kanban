export type CardCommentCreatedType = {
    cardId: string,
    comment: {
        id: string,
        content: string,
        card_id: string,
        user_id: string,
        created_at: string,
        updated_at: string,
    }
}

export type CardCommentDeletedType = {
    cardId: string,
    commentId: string
}