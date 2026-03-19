import axios from 'axios'
import { ref } from 'vue'
import type { Wallet, WalletForm } from '@/pages/finance/Wallet/types'

// --- GLOBAL STATE (SHARED) ---
const wallets = ref<Wallet[]>([])
const isLoading = ref(false)
const isEditing = ref(false)
const currentUuid = ref<string | null>(null)
const open = ref(false)

const form = ref<WalletForm>({
    name: '',
    type: 'digital',
    balance: '0',
    icon: 'Wallet',
    color: '#63d478',
    is_active: true,
})

export function useWalletState() {
    const openModal = () => { open.value = true }
    
    const closeModal = () => { 
        open.value = false
        isEditing.value = false
        currentUuid.value = null
        resetForm()
    }

    function resetForm(): void {
        form.value = {
            name: '',
            type: 'digital',
            balance: '0',
            icon: 'Wallet',
            color: '#63d478',
            is_active: true,
        }
    }

    // --- 1. FETCH ---
    async function fetchWallets(): Promise<void> {
        try {
            isLoading.value = true
            const response = await axios.get<Wallet[]>('/wallets')
            wallets.value = response.data
        } catch (error) {
            console.error('Failed to fetch wallets:', error)
        } finally {
            isLoading.value = false
        }
    }

    // --- 2. SUBMIT ---
    async function submit(): Promise<void> {
        if (!form.value.name || !form.value.type) return

        isLoading.value = true
        const payload = { ...form.value, balance: Number(form.value.balance) }

        try {
            const response = await axios.post<Wallet>('/wallets', payload)
            wallets.value.unshift(response.data)
            closeModal()
        } catch (error) {
            console.error('Failed to save wallet:', error)
        } finally {
            isLoading.value = false
        }
    }

    // --- 3. DELETE ---
    async function deleteWallet(uuid: string): Promise<void> {
        const original = [...wallets.value]
        wallets.value = wallets.value.filter((w) => w.uuid !== uuid)

        try {
            await axios.delete(`/wallets/${uuid}`)
        } catch (error) {
            wallets.value = original
            console.error('Failed to delete wallet:', error)
        }
    }

    // --- 4. EDIT ---
    function editWallet(wallet: Wallet): void {
        isEditing.value = true
        currentUuid.value = wallet.uuid
        
        form.value = {
            name: wallet.name,
            type: wallet.type,
            balance: String(wallet.balance),
            icon: wallet.icon,
            color: wallet.color,
            is_active: wallet.is_active,
        }
        openModal()
    }

    // --- 5. UPDATE ---
    async function updateWallet(): Promise<void> {
        if (!currentUuid.value || !form.value.name) return

        isLoading.value = true
        const payload = { ...form.value, balance: Number(form.value.balance) }

        try {
            const response = await axios.put<Wallet>(`/wallets/${currentUuid.value}`, payload)
            const index = wallets.value.findIndex((w) => w.uuid === currentUuid.value)
            if (index !== -1) {
                wallets.value[index] = response.data
            }
            closeModal()
        } catch (error) {
            console.error('Failed to update wallet:', error)
        } finally {
            isLoading.value = false
        }
    }

    return {
        open,
        form,
        wallets,
        isLoading,
        isEditing,
        openModal,
        closeModal,
        resetForm,
        submit,
        fetchWallets,
        deleteWallet,
        editWallet,
        updateWallet,
    }
}
