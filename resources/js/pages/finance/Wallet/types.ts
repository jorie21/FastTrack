export interface Wallet {
    id: number;
    uuid: string;
    name: string;
    type: string;
    balance: number;
    icon: string;
    color: string;
    is_active: boolean;
}

export interface WalletForm {
    name: string;
    type: string;
    balance: string;
    icon: string;
    color: string;
    is_active: boolean;
}
