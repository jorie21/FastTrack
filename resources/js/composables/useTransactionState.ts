import axios from 'axios'
import { ref } from 'vue'
import type { Transaction } from '@/pages/finance/Transaction/types'
import { useDashboardState } from './useDashboardState'
import { useWalletState } from './useWalletState'

export interface TransactionForm {
  title: string
  amount: string
  type: 'income' | 'expense'
  category_id: number | string
  wallet_id: number | string
  date: string
  note: string
}

// --- GLOBAL STATE (SHARED) ---
const transactions = ref<Transaction[]>([])
const isLoading = ref(false)
const isEditing = ref(false)
const currentUuid = ref<string | null>(null)
const open = ref(false)

const form = ref<TransactionForm>({
  title: '',
  amount: '',
  type: 'expense',
  category_id: '',
  wallet_id: '',
  date: '',
  note: '',
})

export function useTransactionState() {
  const openModal = () => { open.value = true }
  
  const closeModal = () => { 
    open.value = false
    isEditing.value = false
    currentUuid.value = null
    resetForm()
  }

  function resetForm(): void {
    form.value = {
      title: '',
      amount: '',
      type: 'expense',
      category_id: '',
      wallet_id: '',
      date: new Date().toISOString().split('T')[0],
      note: '',
    }
  }

  // --- 1. FETCH ---
  async function fetchTransactions(filters: Record<string, any> = {}): Promise<void> {
    try {
      isLoading.value = true
      const response = await axios.get<Transaction[]>('/transactions', { params: filters })
      transactions.value = response.data
    } catch (error) {
      console.error('Failed to fetch transactions:', error)
    } finally {
      isLoading.value = false
    }
  }

  // --- 2. SUBMIT (Create) ---
  async function submit(): Promise<void> {
    if (!form.value.title || !form.value.amount || !form.value.category_id || !form.value.wallet_id) return

    isLoading.value = true

    const payload = {
      description: form.value.title, // 'title' in UI maps to 'description' in DB
      amount: Number(form.value.amount),
      type: form.value.type,
      category_id: form.value.category_id,
      wallet_id: form.value.wallet_id,
      transaction_date: form.value.date,
      note: form.value.note,
    }

    try {
      const response = await axios.post<Transaction>('/transactions', payload)
      transactions.value.unshift(response.data)
      
      // Refresh wallets and today stats to keep balances in sync
      const { fetchWallets } = useWalletState()
      const { fetchTodayStats } = useDashboardState()
      await Promise.all([fetchWallets(), fetchTodayStats()])

      closeModal()
    } catch (error) {
      console.error('Failed to save transaction:', error)
      throw error
    } finally {
      isLoading.value = false
    }
  }

  // --- 3. DELETE ---
  async function deleteTransaction(uuid: string): Promise<void> {
    const original = [...transactions.value]
    transactions.value = transactions.value.filter((t) => t.uuid !== uuid)

    try {
      await axios.delete(`/transactions/${uuid}`)
      
      // Refresh wallets and today stats to keep balances in sync
      const { fetchWallets } = useWalletState()
      const { fetchTodayStats } = useDashboardState()
      await Promise.all([fetchWallets(), fetchTodayStats()])
    } catch (error) {
      transactions.value = original
      console.error('Failed to delete transaction:', error)
    }
  }

  // --- 4. EDIT (Prepare) ---
  function editTransaction(transaction: Transaction): void {
    isEditing.value = true
    currentUuid.value = transaction.uuid

    // Extract date from transaction_date (it might be ISO string or already YYYY-MM-DD)
    const rawDate = (transaction as any).transaction_date || transaction.date || '';
    const formattedDate = rawDate ? rawDate.split('T')[0] : '';

    form.value = {
      title: transaction.title || (transaction as any).description || '',
      amount: String(transaction.amount),
      type: transaction.type,
      category_id: (transaction as any).category_id || '',
      wallet_id: (transaction as any).wallet_id || '',
      date: formattedDate,
      note: transaction.note || '',
    }
    openModal()
  }

  // --- 5. UPDATE ---
  async function updateTransaction(): Promise<void> {
    if (!currentUuid.value || !form.value.title) return

    isLoading.value = true

    const payload = {
      description: form.value.title,
      amount: Number(form.value.amount),
      type: form.value.type,
      category_id: form.value.category_id,
      wallet_id: form.value.wallet_id,
      transaction_date: form.value.date,
      note: form.value.note,
    }

    try {
      const response = await axios.put<Transaction>(`/transactions/${currentUuid.value}`, payload)
  // ... rest of code ...
      const index = transactions.value.findIndex((t) => t.uuid === currentUuid.value)
      if (index !== -1) {
        transactions.value[index] = response.data
      }

      // Refresh wallets and today stats to keep balances in sync
      const { fetchWallets } = useWalletState()
      const { fetchTodayStats } = useDashboardState()
      await Promise.all([fetchWallets(), fetchTodayStats()])

      closeModal()
    } catch (error) {
      console.error('Failed to update transaction:', error)
      throw error
    } finally {
      isLoading.value = false
    }
  }

  return {
    open,
    form,
    transactions,
    isLoading,
    isEditing,
    openModal,
    closeModal,
    resetForm,
    submit,
    fetchTransactions,
    deleteTransaction,
    editTransaction,
    updateTransaction,
  }
}
