import { ref } from 'vue';

interface ConfirmState {
    show: boolean;
    title: string;
    message: string;
    onConfirm: (() => void) | null;
    isLoading: boolean;
}

const state = ref<ConfirmState>({
    show: false,
    title: 'Are you sure?',
    message: 'This action cannot be undone.',
    onConfirm: null,
    isLoading: false
});

export function useConfirm() {
    function ask(options: { 
        title?: string; 
        message?: string; 
        onConfirm: () => void;
    }) {
        state.value.title = options.title || 'Are you sure?';
        state.value.message = options.message || 'This action cannot be undone.';
        state.value.onConfirm = options.onConfirm;
        state.value.show = true;
        state.value.isLoading = false;
    }

    function confirm() {
        if (state.value.onConfirm) {
            state.value.isLoading = true;
            state.value.onConfirm();
        }
    }

    function cancel() {
        state.value.show = false;
        state.value.onConfirm = null;
        state.value.isLoading = false;
    }

    function close() {
        state.value.show = false;
        state.value.isLoading = false;
    }

    return {
        state,
        ask,
        confirm,
        cancel,
        close
    };
}
