import { useForm } from "@inertiajs/vue3";
import axios from "axios";
import { onMounted, reactive } from "vue";
import { usePage } from "@inertiajs/vue3";

const oSalida = reactive({
    id: 0,
    tipo_salida_id: null,
    descripcion: "",
    unidad_solicitante: "",
    fecha_salida: "",
    salida_detalles: reactive([]),
    eliminados: reactive([]),
    _method: "POST",
});

export const useSalidas = () => {
    const { flash } = usePage().props;
    const getSalidas = async (data) => {
        try {
            const response = await axios.get(route("salidas.listado"), {
                headers: { Accept: "application/json" },
                params: data,
            });
            return response.data.salidas;
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

    const getSalidasApi = async (data) => {
        try {
            const response = await axios.get(route("salidas.paginado", data), {
                headers: { Accept: "application/json" },
            });
            return response.data.salidas;
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
    const saveSalida = async (data) => {
        try {
            const response = await axios.post(route("salidas.store", data), {
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

    const deleteSalida = async (id) => {
        try {
            const response = await axios.delete(route("salidas.destroy", id), {
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

    const setSalida = (
        item = null,
        proveedor = false,
        tipo_salida = false
    ) => {
        if (item) {
            oSalida.id = item.id;
            oSalida.proveedor_id = item.proveedor_id;
            oSalida.tipo_salida_id = item.tipo_salida_id;
            if (tipo_salida) {
                oSalida.tipo_salida = item.tipo_salida;
                oSalida.fecha_salida_t = item.fecha_salida_t;
            }
            oSalida.descripcion = item.descripcion;
            oSalida.unidad_solicitante = item.unidad_solicitante;
            oSalida.fecha_salida = item.fecha_salida;
            oSalida.salida_detalles = reactive([...item.salida_detalles]);
            oSalida.eliminados = reactive([]);
            oSalida._method = "PUT";
            return oSalida;
        }
        return false;
    };

    const limpiarSalida = () => {
        oSalida.id = 0;
        oSalida.tipo_salida_id = null;
        oSalida.descripcion = "";
        oSalida.unidad_solicitante = "";
        oSalida.fecha_salida = "";
        oSalida.salida_detalles = reactive([]);
        oSalida.eliminados = reactive([]);
        oSalida._method = "POST";
    };

    onMounted(() => {});

    return {
        oSalida,
        getSalidas,
        getSalidasApi,
        saveSalida,
        deleteSalida,
        setSalida,
        limpiarSalida,
    };
};
