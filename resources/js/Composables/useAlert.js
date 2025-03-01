import Swal from 'sweetalert2';
import { router } from '@inertiajs/vue3';

const successAlert = (model, action) => {
    Swal.fire({
        title: 'Sukses!',
        text: `${model} berhasil ${action}.`,
        icon: 'success',
        showConfirmButton: false,
        timer: 2000
    });
};

const warningAlert = (title, text) => {
    Swal.fire({
        title: title,
        text: text,
        icon: 'warning',
        showConfirmButton: true,
        confirmButtonText: 'OK'
    });
};

const errorAlert = (title, text) => {
    Swal.fire({
        title: title,
        text: text,
        icon: 'error',
        showConfirmButton: true,
        confirmButtonText: 'OK'
    });
};

const deleteConfirm = (model, route, id) => {
    Swal.fire({
        title: 'Apakah anda yakin',
        text: "Data yang dihapus tidak bisa dikembalikan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText: 'Batal',
        confirmButtonText: 'Ya'
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(`/apps/${route}/${id}`);
            successAlert(model, 'dihapus');
        }
    });
};

export {
    successAlert,
    warningAlert,
    errorAlert,
    deleteConfirm
};
