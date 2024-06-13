import axios from "axios";
import { onMounted, ref } from "vue";
import { usePage } from "@inertiajs/vue3";

const oTipoProducto = ref({
    id: 0,
    nombre: "",
    descripcion: "",
    fecha_registro: "",
    _method: "POST",
});

export const useTipoProductos = () => {
    const { flash } = usePage().props;
    const getTipoProductos = async () => {
        try {
            const response = await axios.get(route("tipo_productos.listado"), {
                headers: { Accept: "application/json" },
            });
            return response.data.tipo_productos;
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

    const getTipoProductosApi = async (data) => {
        try {
            const response = await axios.get(
                route("tipo_productos.paginado", data),
                {
                    headers: { Accept: "application/json" },
                }
            );
            return response.data.tipo_productos;
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
    const saveTipoProducto = async (data) => {
        try {
            const response = await axios.post(route("tipo_productos.store", data), {
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

    const deleteTipoProducto = async (id) => {
        try {
            const response = await axios.delete(
                route("tipo_productos.destroy", id),
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

    const setTipoProducto = (item = null) => {
        if (item) {
            oTipoProducto.value.id = item.id;
            oTipoProducto.value.nombre = item.nombre;
            oTipoProducto.value.descripcion = item.descripcion;
            oTipoProducto.value.fecha_registro = item.fecha_registro;
            oTipoProducto.value._method = "PUT";
            return oTipoProducto;
        }
        return false;
    };

    const limpiarTipoProducto = () => {
        oTipoProducto.value.id = 0;
        oTipoProducto.value.nombre = "";
        oTipoProducto.value.descripcion = "";
        oTipoProducto.value.fecha_registro = "";
        oTipoProducto.value._method = "POST";
    };

    onMounted(() => {});

    return {
        oTipoProducto,
        getTipoProductos,
        getTipoProductosApi,
        saveTipoProducto,
        deleteTipoProducto,
        setTipoProducto,
        limpiarTipoProducto,
    };
};
