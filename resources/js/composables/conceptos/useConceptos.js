import axios from "axios";
import { onMounted, ref } from "vue";
import { usePage } from "@inertiajs/vue3";

const oConcepto = ref({
    id: 0,
    categoria_id: null,
    nombre: "",
    descripcion: "",
    _method: "POST",
});

export const useConceptos = () => {
    const { flash } = usePage().props;
    const getConceptos = async () => {
        try {
            const response = await axios.get(route("conceptos.listado"), {
                headers: { Accept: "application/json" },
            });
            return response.data.conceptos;
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

    const getConceptosApi = async (data) => {
        try {
            const response = await axios.get(
                route("conceptos.paginado", data),
                {
                    headers: { Accept: "application/json" },
                }
            );
            return response.data.conceptos;
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
    const saveConcepto = async (data) => {
        try {
            const response = await axios.post(route("conceptos.store", data), {
                headers: { Accept: "application/json" },
            });
            Swal.fire({
                icon: "success",
                title: "Correcto",
                text: `${flash.bien ? flash.bien : "Proceso realizado"}`,
                confirmButtonColor: "#3085d6",
                confirmButtonText: `Aceptar`,
            });
            return response.data;
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
            console.error("Error:", err);
            throw err; // Puedes manejar el error según tus necesidades
        }
    };

    const deleteConcepto = async (id) => {
        try {
            const response = await axios.delete(
                route("conceptos.destroy", id),
                {
                    headers: { Accept: "application/json" },
                }
            );
            Swal.fire({
                icon: "success",
                title: "Correcto",
                text: `${flash.bien ? flash.bien : "Proceso realizado"}`,
                confirmButtonColor: "#3085d6",
                confirmButtonText: `Aceptar`,
            });
            return response.data;
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

    const setConcepto = (item = null) => {
        if (item) {
            oConcepto.value.id = item.id;
            oConcepto.value.categoria_id = item.categoria_id;
            oConcepto.value.nombre = item.nombre;
            oConcepto.value.descripcion = item.descripcion;
            oConcepto.value._method = "PUT";
            return oConcepto;
        }
        return false;
    };

    const limpiarConcepto = () => {
        oConcepto.value.id = 0;
        oConcepto.value.categoria_id = null;
        oConcepto.value.nombre = "";
        oConcepto.value.descripcion = "";
        oConcepto.value._method = "POST";
    };

    onMounted(() => {});

    return {
        oConcepto,
        getConceptos,
        getConceptosApi,
        saveConcepto,
        deleteConcepto,
        setConcepto,
        limpiarConcepto,
    };
};
