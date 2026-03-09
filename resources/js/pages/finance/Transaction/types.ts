export interface Category {
    id: number;
    name: string;
    color: string;
    icon: string;
    type: 'income' | 'expense' | 'both';
}

export interface Transaction {
    id: number;
    title: string;
    category: string;
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