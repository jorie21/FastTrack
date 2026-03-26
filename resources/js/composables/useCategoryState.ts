import axios from 'axios'
import { ref } from 'vue'
import type { Category } from '@/pages/finance/Transaction/types'
import { useErrorState } from './useErrorState'

export interface CategoryForm {
  name: string
  icon: string
  color: string
  type: Category['type']
}

// --- GLOBAL STATE (SHARED) ---
const categories = ref<Category[]>([])
const isLoading = ref(false)
const isEditing = ref(false)
const currentUuid = ref<string | null>(null)
const open = ref(false)

const form = ref<CategoryForm>({
  name: '',
  icon: 'Package',
  color: '#63d478',
  type: 'expense',
})

export function useCategoryState() {
  const { openErrorModal, closeErrorModal, showErrorModal, deleteErrorMessage } = useErrorState();

  // --- Modal state ---
  
  const openModal = () => { open.value = true }
  
  const closeModal = () => { 
    open.value = false
    isEditing.value = false
    currentUuid.value = null
    resetForm()
  }

  // --- Form state ---

  function resetForm(): void {
    form.value = {
      name: '',
      icon: 'Package',
      color: '#63d478',
      type: 'expense',
    }
  }

  // --- 1. FETCH (Load all) ---
  async function fetchCategories(): Promise<void> {
    try {
      isLoading.value = true
      const response = await axios.get<Category[]>('/categories')
      categories.value = response.data
    } catch (error) {
      console.error('Failed to fetch:', error)
    } finally {
      isLoading.value = false
    }
  }

  // --- 2. SUBMIT (Create New) ---
  async function submit(): Promise<void> {
    if (!form.value.name) return
    const payload: Omit<Category, 'id' | 'uuid'> = { ...form.value }

    try {
      const response = await axios.post<Category>('/categories', payload)
      // Instant UI update by pushing to the shared array
      categories.value.push(response.data)
      closeModal()
    } catch (error) {
      console.error('Failed to save:', error)
      throw error
    }
  }

  // --- 3. DELETE ---
  async function deleteCategory(uuid: string): Promise<void> {
    isLoading.value = true

    try {
      await axios.delete(`/categories/${uuid}`)
      categories.value = categories.value.filter((c) => c.uuid !== uuid)
    } catch (error: any) {
      const msg = error.response?.data?.message || 'Failed to delete category'
      openErrorModal(msg)
      console.error('Failed to delete category:', error)
    } finally {
      isLoading.value = false
    }
  }

  // --- 4. EDIT (Prepare the form) ---
  function editCategory(category: Category): void {
    isEditing.value = true
    currentUuid.value = category.uuid
    
    form.value = {
      name: category.name,
      icon: category.icon,
      color: category.color,
      type: category.type,
    }
    openModal()
  }

  // --- 5. UPDATE (Save existing) ---
  async function updateCategory(): Promise<void> {
    if (!currentUuid.value || !form.value.name) return

    try {
      const response = await axios.put<Category>(`/categories/${currentUuid.value}`, form.value)
      const index = categories.value.findIndex((c) => c.uuid === currentUuid.value)
      if (index !== -1) {
        categories.value[index] = response.data
      }

      closeModal()
    } catch (error) {
      console.error('Failed to update:', error)
      throw error
    }
  }

  return {
    open,
    form,
    categories,
    isLoading,
    isEditing,
    showErrorModal,
    deleteErrorMessage,
    openModal,
    closeModal,
    closeErrorModal,
    resetForm,
    submit,
    fetchCategories,
    deleteCategory,
    editCategory,
    updateCategory,
  }
}
