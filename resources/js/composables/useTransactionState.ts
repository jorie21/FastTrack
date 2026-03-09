import axios from 'axios'
import { ref } from 'vue'
import type { Transaction, Category } from '@/pages/finance/Transaction/types'

export interface TransactionForm {
  title: string
  amount: string
  type: 'income' | 'expense'
  category: string
  date: string
  note: string
}

export function useTransactionState() {
  // --- Modal state ---
  const open = ref(false)

  function openModal() { open.value = true }
  function closeModal() { open.value = false }

  // --- Form state ---
  const form = ref<TransactionForm>({
    title: '',
    amount: '',
    type: 'expense',
    category: '',
    date: '',
    note: '',
  })

  function resetForm(): void {
    form.value = {
      title: '',
      amount: '',
      type: 'expense',
      category: '',
      date: '',
      note: '',
    }
  }

  // --- Data ---
  const transactions = ref<Transaction[]>([])
  const categories = ref<Category[]>([])

  // --- Fetch data ---
  async function fetchTransactions(): Promise<void> {
    const response = await axios.get<Transaction[]>('/api/transactions')
    transactions.value = response.data
  }

  async function fetchCategories(): Promise<void> {
    const response = await axios.get<Category[]>('/api/categories')
    categories.value = response.data
  }

  // --- Submit transaction ---
  async function submit(): Promise<void> {
    if (!form.value.title || !form.value.amount || !form.value.category) return

    const cat = categories.value.find(c => c.name === form.value.category)

    const payload: Omit<Transaction, 'id'> = {
      title: form.value.title,
      amount: Number(form.value.amount),
      type: form.value.type,
      category: form.value.category,
      categoryColor: cat?.color ?? '#63d478',
      date: form.value.date || new Date().toISOString().split('T')[0],
      note: form.value.note || undefined,
    }

    const response = await axios.post<Transaction>('/api/transactions', payload)
    transactions.value.push(response.data)

    resetForm()
    closeModal()
  }

  return {
    open,
    form,
    categories,
    transactions,
    openModal,
    closeModal,
    resetForm,
    submit,
    fetchTransactions,
    fetchCategories,
  }
}