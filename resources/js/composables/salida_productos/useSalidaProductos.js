import axios from "axios";
import { onMounted, ref } from "vue";
import { usePage } from "@inertiajs/vue3";

const oSalidaProducto = ref({
    id: 0,
    tipo_producto_id:"",
    producto_id:"",
    cantidad:"",
    descripcion:"",
    fecha_salida:"",
    _method: "POST",
});

export const useSalidaProductos = () => {
    const { flash } = usePage().props;
    const getSalidaProductos = async (data) => {
        try {
            const response = await axios.get(
                route("salida_productos.listado", data),
                {
                    headers: { Accept: "application/json" },
                }
            );
            return response.data.salida_productos;
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

    const getSalidaProductosApi = async (data) => {
        try {
            const response = await axios.get(
                route("salida_productos.paginado", data),
                {
                    headers: { Accept: "application/json" },
                }
            );
            return response.data.salida_productos;
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
    const saveSalidaProducto = async (data) => {
        try {
            const response = await axios.post(route("salida_productos.store", data), {
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

    const deleteSalidaProducto = async (id) => {
        try {
            const response = await axios.delete(
                route("salida_productos.destroy", id),
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

    const setSalidaProducto = (item = null) => {
        if (item) {
            oSalidaProducto.value.id = item.id;
            oSalidaProducto.value.tipo_producto_id = item.tipo_producto_id;
            oSalidaProducto.value.producto_id = item.producto_id;
            oSalidaProducto.value.cantidad = item.cantidad;
            oSalidaProducto.value.descripcion = item.descripcion;
            oSalidaProducto.value.fecha_salida = item.fecha_salida;
            oSalidaProducto.value._method = "PUT";
            return oSalidaProducto;
        }
        return false;
    };

    const limpiarSalidaProducto = () => {
        oSalidaProducto.value.id = 0;
        oSalidaProducto.value.tipo_producto_id="";
        oSalidaProducto.value.producto_id="";
        oSalidaProducto.value.cantidad="";
        oSalidaProducto.value.descripcion="";
        oSalidaProducto.value.fecha_salida="";
        oSalidaProducto.value._method = "POST";
    };

    onMounted(() => {});

    return {
        oSalidaProducto,
        getSalidaProductos,
        getSalidaProductosApi,
        saveSalidaProducto,
        deleteSalidaProducto,
        setSalidaProducto,
        limpiarSalidaProducto,
    };
};
