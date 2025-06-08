/**
 * SweetAlert2 Configuration for RutasMotor
 * Custom styling and functions for form confirmations
 */

// Configuración global de SweetAlert2
const SweetAlertConfig = {
    customClass: {
        confirmButton: 'swal-confirm-btn',
        cancelButton: 'swal-cancel-btn',
        popup: 'swal-popup-custom',
        title: 'swal-title-custom',
        content: 'swal-content-custom',
        actions: 'swal-actions-custom'
    },
    buttonsStyling: false,
    confirmButtonText: '<i class="fas fa-check"></i> Confirmar',
    cancelButtonText: '<i class="fas fa-times"></i> Cancelar',
    showClass: {
        popup: 'animate__animated animate__fadeInDown animate__faster'
    },
    hideClass: {
        popup: 'animate__animated animate__fadeOutUp animate__faster'
    }
};

// Función para confirmación de eliminación
function confirmDelete(title = '¿Estás seguro?', text = 'Esta acción no se puede deshacer') {
    return Swal.fire({
        title: title,
        text: text,
        icon: 'warning',
        showCancelButton: true,
        ...SweetAlertConfig,
        confirmButtonText: '<i class="fas fa-trash"></i> Eliminar',
        iconColor: '#ef4444'
    });
}

// Función para confirmación de guardado
function confirmSave(title = '¿Guardar cambios?', text = 'Se guardarán todos los cambios realizados') {
    return Swal.fire({
        title: title,
        text: text,
        icon: 'question',
        showCancelButton: true,
        ...SweetAlertConfig,
        confirmButtonText: '<i class="fas fa-save"></i> Guardar',
        iconColor: '#f25444'
    });
}

// Función para mostrar éxito
function showSuccess(title = '¡Éxito!', text = 'La operación se completó correctamente') {
    return Swal.fire({
        title: title,
        text: text,
        icon: 'success',
        ...SweetAlertConfig,
        showCancelButton: false,
        confirmButtonText: '<i class="fas fa-check"></i> Entendido',
        iconColor: '#22c55e'
    });
}

// Función para mostrar error
function showError(title = '¡Error!', text = 'Ha ocurrido un error inesperado') {
    return Swal.fire({
        title: title,
        text: text,
        icon: 'error',
        ...SweetAlertConfig,
        showCancelButton: false,
        confirmButtonText: '<i class="fas fa-exclamation-triangle"></i> Entendido',
        iconColor: '#ef4444'
    });
}

// Función para mostrar información
function showInfo(title = 'Información', text = '') {
    return Swal.fire({
        title: title,
        text: text,
        icon: 'info',
        ...SweetAlertConfig,
        showCancelButton: false,
        confirmButtonText: '<i class="fas fa-info-circle"></i> Entendido',
        iconColor: '#f25444'
    });
}

// Función para confirmación de envío de formulario
function confirmFormSubmit(form, title = '¿Enviar formulario?', text = 'Por favor revisa la información antes de continuar') {
    return Swal.fire({
        title: title,
        text: text,
        icon: 'question',
        showCancelButton: true,
        ...SweetAlertConfig,
        confirmButtonText: '<i class="fas fa-paper-plane"></i> Enviar',
        iconColor: '#f25444'
    }).then((result) => {
        if (result.isConfirmed) {
            // Mostrar loading
            Swal.fire({
                title: 'Enviando...',
                text: 'Por favor espera',
                icon: 'info',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Enviar formulario
            form.submit();
        }
    });
}

// Función para Toast notifications
function showToast(title, icon = 'success', position = 'top-end') {
    const Toast = Swal.mixin({
        toast: true,
        position: position,
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer);
            toast.addEventListener('mouseleave', Swal.resumeTimer);
        },
        customClass: {
            popup: 'swal-toast-custom'
        }
    });

    return Toast.fire({
        icon: icon,
        title: title
    });
}

// Auto-inicialización cuando el DOM está listo
document.addEventListener('DOMContentLoaded', function() {
    // Interceptar formularios con confirmación
    const formsWithConfirmation = document.querySelectorAll('form[data-confirm="true"]');
    formsWithConfirmation.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const title = form.dataset.confirmTitle || '¿Enviar formulario?';
            const text = form.dataset.confirmText || 'Por favor revisa la información antes de continuar';
            
            confirmFormSubmit(form, title, text);
        });
    });

    // Interceptar botones de eliminación
    const deleteButtons = document.querySelectorAll('[data-action="delete"]');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            const title = button.dataset.confirmTitle || '¿Estás seguro?';
            const text = button.dataset.confirmText || 'Esta acción no se puede deshacer';
            const href = button.href || button.dataset.href;
            
            confirmDelete(title, text).then((result) => {
                if (result.isConfirmed && href) {
                    window.location.href = href;
                }
            });
        });
    });

    // Mostrar mensajes de sesión si existen
    if (typeof sessionMessages !== 'undefined') {
        if (sessionMessages.success) {
            showSuccess('¡Éxito!', sessionMessages.success);
        }
        if (sessionMessages.error) {
            showError('¡Error!', sessionMessages.error);
        }
        if (sessionMessages.info) {
            showInfo('Información', sessionMessages.info);
        }
    }
});
