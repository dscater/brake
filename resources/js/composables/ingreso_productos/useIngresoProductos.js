import axios from "axios";
import { onMounted, ref } from "vue";
import { usePage } from "@inertiajs/vue3";

const oIngresoProducto = ref({
    id: 0,
    tipo_producto_id:"",
    producto_id:"",
    cantidad:"",
    descripcion:"",
    fecha_ingreso:"",
    _method: "POST",
});

export const useIngresoProductos = () => {
    const { flash } = usePage().props;
    const getIngresoProductos = async (data) => {
        try {
            const response = await axios.get(
                route("ingreso_productos.listado", data),
                {
                    headers: { Accept: "application/json" },
                }
            );
            return response.data.ingreso_productos;
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

    const getIngresoProductosApi = async (data) => {
        try {
            const response = await axios.get(
                route("ingreso_productos.paginado", data),
                {
                    headers: { Accept: "application/json" },
                }
            );
            return response.data.ingreso_productos;
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
    const saveIngresoProducto = async (data) => {
        try {
            const response = await axios.post(route("ingreso_productos.store", data), {
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

    const deleteIngresoProducto = async (id) => {
        try {
            const response = await axios.delete(
                route("ingreso_productos.destroy", id),
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

    const setIngresoProducto = (item = null) => {
        if (item) {
            oIngresoProducto.value.id = item.id;
            oIngresoProducto.value.tipo_producto_id = item.tipo_producto_id;
            oIngresoProducto.value.producto_id = item.producto_id;
            oIngresoProducto.value.cantidad = item.cantidad;
            oIngresoProducto.value.descripcion = item.descripcion;
            oIngresoProducto.value.fecha_ingreso = item.fecha_ingreso;
            oIngresoProducto.value._method = "PUT";
            return oIngresoProducto;
        }
        return false;
    };

    const limpiarIngresoProducto = () => {
        oIngresoProducto.value.id = 0;
        oIngresoProducto.value.tipo_producto_id="";
        oIngresoProducto.value.producto_id="";
        oIngresoProducto.value.cantidad="";
        oIngresoProducto.value.descripcion="";
        oIngresoProducto.value.fecha_ingreso="";
        oIngresoProducto.value._method = "POST";
    };

    onMounted(() => {});

    return {
        oIngresoProducto,
        getIngresoProductos,
        getIngresoProductosApi,
        saveIngresoProducto,
        deleteIngresoProducto,
        setIngresoProducto,
        limpiarIngresoProducto,
    };
};
