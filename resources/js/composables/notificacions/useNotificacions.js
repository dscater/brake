import axios from "axios";
import { onMounted, ref } from "vue";
import { usePage } from "@inertiajs/vue3";

const oNotificacion = ref({
    id: 0,
});

export const useNotificacions = () => {
    const { flash } = usePage().props;
    const getNotificacions = async () => {
        try {
            const response = await axios.get(route("notificacions.listado"), {
                headers: { Accept: "application/json" },
            });
            return response.data.notificacions;
        } catch (err) {
            Swal.fire({
                icon: "error",
                title: "Error",
                text: `${
                    flash.error
                        ? flash.error
                        : err.response?.data
                        ? err.response?.data?.message
                        : "Hay errores en el formulario"
                }`,
                confirmButtonColor: "#3085d6",
                confirmButtonText: `Aceptar`,
            });
            throw err; // Puedes manejar el error según tus necesidades
        }
    };

    const getNotificacionsApi = async (data) => {
        try {
            const response = await axios.get(
                route("notificacions.paginado", data),
                {
                    headers: { Accept: "application/json" },
                }
            );
            return response.data.notificacions;
        } catch (err) {
            Swal.fire({
                icon: "error",
                title: "Error",
                text: `${
                    flash.error
                        ? flash.error
                        : err.response?.data
                        ? err.response?.data?.message
                        : "Hay errores en el formulario"
                }`,
                confirmButtonColor: "#3085d6",
                confirmButtonText: `Aceptar`,
            });
            throw err; // Puedes manejar el error según tus necesidades
        }
    };

    const setNotificacion = (item = null) => {
        if (item) {
            oNotificacion.value.id = item.id;
            return oNotificacion;
        }
        return false;
    };

    const limpiarNotificacion = () => {
        oNotificacion.value.id = 0;
    };

    onMounted(() => {});

    return {
        oNotificacion,
        getNotificacions,
        getNotificacionsApi,
        setNotificacion,
        limpiarNotificacion,
    };
};
