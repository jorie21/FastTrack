import axios from 'axios'
import { ref } from 'vue'
import type { Wallet, WalletForm } from '@/pages/finance/Wallet/types'
import { useErrorState } from './useErrorState'

// --- GLOBAL STATE (SHARED) ---
const wallets = ref<Wallet[]>([])
const isLoading = ref(false)
const isEditing = ref(false)
const currentUuid = ref<string | null>(null)
const open = ref(false)
const errorMessage = ref<string | null>(null)

const form = ref<WalletForm>({
    name: '',
    type: 'digital',
    balance: '0',
    icon: 'Wallet',
    color: '#63d478',
    is_active: true,
})

export function useWalletState() {
    const { openErrorModal, closeErrorModal, showErrorModal, deleteErrorMessage } = useErrorState();

    const openModal = () => { 
        errorMessage.value = null
        open.value = true 
    }
    
    const closeModal = () => { 
        open.value = false
        isEditing.value = false
        currentUuid.value = null
        errorMessage.value = null
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
        errorMessage.value = null
        const payload = { ...form.value, balance: Number(form.value.balance) }

        try {
            const response = await axios.post<Wallet>('/wallets', payload)
            wallets.value.unshift(response.data)
            closeModal()
        } catch (error: any) {
            errorMessage.value = error.response?.data?.message || 'Failed to save wallet'
            console.error('Failed to save wallet:', error)
        } finally {
            isLoading.value = false
        }
    }

    // --- 3. DELETE ---
    async function deleteWallet(uuid: string): Promise<void> {
        errorMessage.value = null
        isLoading.value = true

        try {
            await axios.delete(`/wallets/${uuid}`)
            wallets.value = wallets.value.filter((w) => w.uuid !== uuid)
        } catch (error: any) {
            const msg = error.response?.data?.message || 'Failed to delete wallet'
            openErrorModal(msg)
            console.error('Failed to delete wallet:', error)
        } finally {
            isLoading.value = false
        }
    }

    // --- 4. EDIT ---
    function editWallet(wallet: Wallet): void {
        isEditing.value = true
        currentUuid.value = wallet.uuid
        errorMessage.value = null
        
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
        errorMessage.value = null
        const payload = { ...form.value, balance: Number(form.value.balance) }

        try {
            const response = await axios.put<Wallet>(`/wallets/${currentUuid.value}`, payload)
            const index = wallets.value.findIndex((w) => w.uuid === currentUuid.value)
            if (index !== -1) {
                wallets.value[index] = response.data
            }
            closeModal()
        } catch (error: any) {
            errorMessage.value = error.response?.data?.message || 'Failed to update wallet'
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
        errorMessage,
        showErrorModal,
        deleteErrorMessage,
        openModal,
        closeModal,
        closeErrorModal,
        resetForm,
        submit,
        fetchWallets,
        deleteWallet,
        editWallet,
        updateWallet,
    }
}
