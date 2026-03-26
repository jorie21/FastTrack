import { ref } from 'vue';

const showErrorModal = ref(false);
const deleteErrorMessage = ref<string | null>(null);

export function useErrorState() {
    const openErrorModal = (message: string) => {
        deleteErrorMessage.value = message;
        showErrorModal.value = true;
    };

    const closeErrorModal = () => {
        showErrorModal.value = false;
        deleteErrorMessage.value = null;
    };

    return {
        showErrorModal,
        deleteErrorMessage,
        openErrorModal,
        closeErrorModal,
    };
}
