import axios from 'axios'
import { ref } from 'vue'
import type { Category } from '@/pages/finance/Transaction/types'

export interface CategoryForm {
  name: string
  icon: string
  color: string
  type: Category['type']
}

export function useCategoryState() {
  // --- Modal state ---
  const open = ref(false)

  function openModal() { open.value = true }
  function closeModal() { open.value = false }

  // --- Form state ---
  const form = ref<CategoryForm>({
    name: '',
    icon: '📦',
    color: '#63d478',
    type: 'expense',
  })

  function resetForm(): void {
    form.value = {
      name: '',
      icon: '📦',
      color: '#63d478',
      type: 'expense',
    }
  }

  // --- Category data ---
  const categories = ref<Category[]>([])

  // --- Fetch categories ---
  async function fetchCategories(): Promise<void> {
    const response = await axios.get<Category[]>('/api/categories')
    categories.value = response.data
  }

  // --- Submit category ---
  async function submit(): Promise<void> {
    if (!form.value.name) return

    const payload: Omit<Category, 'id'> = { ...form.value }

    const response = await axios.post<Category>('/api/categories', payload)
    categories.value.push(response.data)

    resetForm()
    closeModal()
  }

  return {
    open,
    form,
    categories,
    openModal,
    closeModal,
    resetForm,
    submit,
    fetchCategories,
  }
}