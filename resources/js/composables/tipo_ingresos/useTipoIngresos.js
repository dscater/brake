import axios from "axios";
import { onMounted, ref } from "vue";
import { usePage } from "@inertiajs/vue3";

const oTipoIngreso = ref({
    id: 0,
    nombre: "",
    descripcion: "",
    fecha_registro: "",
    _method: "POST",
});

export const useTipoIngresos = () => {
    const { flash } = usePage().props;
    const getTipoIngresos = async () => {
        try {
            const response = await axios.get(route("tipo_ingresos.listado"), {
                headers: { Accept: "application/json" },
            });
            return response.data.tipo_ingresos;
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

    const getTipoIngresosApi = async (data) => {
        try {
            const response = await axios.get(
                route("tipo_ingresos.paginado", data),
                {
                    headers: { Accept: "application/json" },
                }
            );
            return response.data.tipo_ingresos;
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
    const saveTipoIngreso = async (data) => {
        try {
            const response = await axios.post(route("tipo_ingresos.store", data), {
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

    const deleteTipoIngreso = async (id) => {
        try {
            const response = await axios.delete(
                route("tipo_ingresos.destroy", id),
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

    const setTipoIngreso = (item = null) => {
        if (item) {
            oTipoIngreso.value.id = item.id;
            oTipoIngreso.value.nombre = item.nombre;
            oTipoIngreso.value.descripcion = item.descripcion;
            oTipoIngreso.value.fecha_registro = item.fecha_registro;
            oTipoIngreso.value._method = "PUT";
            return oTipoIngreso;
        }
        return false;
    };

    const limpiarTipoIngreso = () => {
        oTipoIngreso.value.id = 0;
        oTipoIngreso.value.nombre = "";
        oTipoIngreso.value.descripcion = "";
        oTipoIngreso.value.fecha_registro = "";
        oTipoIngreso.value._method = "POST";
    };

    onMounted(() => {});

    return {
        oTipoIngreso,
        getTipoIngresos,
        getTipoIngresosApi,
        saveTipoIngreso,
        deleteTipoIngreso,
        setTipoIngreso,
        limpiarTipoIngreso,
    };
};
