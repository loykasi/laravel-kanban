import {useToast} from 'vue-toast-notification';

const toast = useToast();

function showError(message: string) {
    toast.error(message, {
        position: 'bottom-right',
        duration: 4000,
        dismissible: true
    });
}

function showSuccess(message: string) {
    toast.success(message, {
        position: 'bottom-right',
        duration: 4000,
        dismissible: true
    });
}

const toastNotification = {
    showError,
    showSuccess
};

export default toastNotification