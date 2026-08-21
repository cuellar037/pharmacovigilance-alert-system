import Swal from 'sweetalert2'

// Global config
export const Toast = Swal.mixin({
  toast: true,
  position: 'top-end',
  showConfirmButton: false,
  timer: 3000,
  timerProgressBar: true,
  didOpen: (toast) => {
    toast.addEventListener('mouseenter', Swal.stopTimer)
    toast.addEventListener('mouseleave', Swal.resumeTimer)
  }
})

// Preconfigured alerts
export const alertSuccess = (message) => {
  Toast.fire({
    icon: 'success',
    title: message
  })
}

export const alertError = (message) => {
  Toast.fire({
    icon: 'error',
    title: message
  })
}

export const alertWarning = (message) => {
  Toast.fire({
    icon: 'warning',
    title: message
  })
}

export const alertInfo = (message) => {
  Toast.fire({
    icon: 'info',
    title: message
  })
}

// Confirmation modal
export const confirmAlert = (title, text, confirmButtonText = 'Confirmar') => {
  return Swal.fire({
    title: title,
    text: text,
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: confirmButtonText,
    cancelButtonText: 'Cancelar'
  })
}

// Success modal
export const successModal = (title, text) => {
  return Swal.fire({
    icon: 'success',
    title: title,
    text: text,
    confirmButtonText: 'OK'
  })
}

// Error modal
export const errorModal = (title, text) => {
  return Swal.fire({
    icon: 'error',
    title: title,
    text: text,
    confirmButtonText: 'OK'
  })
}

export default Swal