import { useForm } from "@inertiajs/vue3";
import axios from "axios";
import { onMounted, reactive } from "vue";
import { usePage } from "@inertiajs/vue3";

const oIngreso = reactive({
    id: 0,
    proveedor_id: null,
    tipo_ingreso_id: null,
    precio: "",
    nro_factura: "",
    descripcion: "",
    fecha_ingreso: "",
    ingreso_detalles: reactive([]),
    eliminados: reactive([]),
    _method: "POST",
});

export const useIngresos = () => {
    const { flash } = usePage().props;
    const getIngresos = async (data) => {
        try {
            const response = await axios.get(route("ingresos.listado"), {
                headers: { Accept: "application/json" },
                params: data,
            });
            return response.data.ingresos;
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

    const getIngresosApi = async (data) => {
        try {
            const response = await axios.get(route("ingresos.paginado", data), {
                headers: { Accept: "application/json" },
            });
            return response.data.ingresos;
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
    const saveIngreso = async (data) => {
        try {
            const response = await axios.post(route("ingresos.store", data), {
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
            throw err; // Puedes manejar el error según tus necesidades
        }
    };

    const deleteIngreso = async (id) => {
        try {
            const response = await axios.delete(route("ingresos.destroy", id), {
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
            throw err; // Puedes manejar el error según tus necesidades
        }
    };

    const setIngreso = (
        item = null,
        proveedor = false,
        tipo_ingreso = false
    ) => {
        if (item) {
            oIngreso.id = item.id;
            oIngreso.proveedor_id = item.proveedor_id;
            if (proveedor) {
                oIngreso.proveedor = item.proveedor;
                oIngreso.fecha_ingreso_t = item.fecha_ingreso_t;
            }
            oIngreso.tipo_ingreso_id = item.tipo_ingreso_id;
            if (tipo_ingreso) {
                oIngreso.tipo_ingreso = item.tipo_ingreso;
            }
            oIngreso.precio = item.precio;
            oIngreso.nro_factura = item.nro_factura;
            oIngreso.descripcion = item.descripcion;
            oIngreso.fecha_ingreso = item.fecha_ingreso;
            oIngreso.ingreso_detalles = reactive([...item.ingreso_detalles]);
            oIngreso.eliminados = reactive([]);
            oIngreso._method = "PUT";
            return oIngreso;
        }
        return false;
    };

    const limpiarIngreso = () => {
        oIngreso.id = 0;
        oIngreso.proveedor_id = null;
        oIngreso.tipo_ingreso_id = null;
        oIngreso.precio = "";
        oIngreso.nro_factura = "";
        oIngreso.descripcion = "";
        oIngreso.fecha_ingreso = "";
        oIngreso.ingreso_detalles = reactive([]);
        oIngreso.eliminados = reactive([]);
        oIngreso._method = "POST";
    };

    onMounted(() => {});

    return {
        oIngreso,
        getIngresos,
        getIngresosApi,
        saveIngreso,
        deleteIngreso,
        setIngreso,
        limpiarIngreso,
    };
};
