export interface Category {
    id: number;
    uuid: string;
    name: string;
    color: string;
    icon: string;
    type: 'income' | 'expense' | 'both';
}

export interface Transaction {
    id: number;
    uuid: string;
    title: string;
    category: string;
    category_id: number;
    categoryColor: string;
    amount: number;
    type: 'income' | 'expense';
    date: string;
    note?: string;
}

export interface TransactionPayload {
    name: string,
    amount: number,
    category_id: number
}